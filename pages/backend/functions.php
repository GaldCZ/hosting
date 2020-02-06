<?php

require 'vendor/autoload.php';
use Aws\Ec2\Ec2Client;
use Aws\Route53\Route53Client;

function getQcount($user) {
	require '/var/www/html/pages/backend/conf.php';

	$sql_select_r="SELECT * FROM users_requests.request_by_user WHERE user_id = '$user' AND done = 'waiting';";
	$result = mysqli_query($conn, $sql_select_r);
	$counter=mysqli_num_rows($result);
	return $counter;
}

function getQlist($user) {
	require '/var/www/html/pages/backend/conf.php';

	$sql_select_q="SELECT * FROM users_requests.request_by_user WHERE user_id = '$user' AND done = 'waiting';";
        
	$result = mysqli_query($conn, $sql_select_q);

	return $result;
}

function getVPSover($user) {
        require '/var/www/html/pages/backend/conf.php';
	
	$sql_select_v="SELECT * FROM users_requests.users_compute WHERE user_id = $user AND instance_type = 'vps';";
	$result = mysqli_query($conn, $sql_select_v);
	return $result;       
}

function getAPPSover($user) {
        require '/var/www/html/pages/backend/conf.php';
	
	$sql_select_v="SELECT * FROM users_requests.users_compute WHERE user_id = $user AND instance_type= 'wp';";
	$result = mysqli_query($conn, $sql_select_v);
	return $result;       
}

function getVPScount($user) {
        require '/var/www/html/pages/backend/conf.php';
	
	$sql_select_c="SELECT id FROM users_requests.users_compute WHERE user_id = $user AND instance_type = 'vps';";
	$result = mysqli_query($conn, $sql_select_c);
	$counter=mysqli_num_rows($result);
	return $counter;      
}

function updateAPP($rid,$action,$uid){

    require '/var/www/html/pages/backend/conf.php';
    
    $sql_del_update="UPDATE users_requests.request_by_user SET request_type='".$action."', request_date='NOW()', done='waiting' WHERE r_id='".$rid."' AND user_id='".$uid."';";


        if (mysqli_query($conn, $sql_del_update)) {
            return "Instance is accesable";
        }
        else {
            return "Error: " . $sql_del_update . "<br>" . mysqli_error($conn);
        }


}

function getAppDNS($rid) {
    
    require '/var/www/html/pages/backend/conf.php';
    
    $sql_select_r="SELECT domain FROM users_requests.request_by_user WHERE r_id = $rid;";
    $result = mysqli_query($conn, $sql_select_r);
    $row    = mysqli_fetch_row($result);

    return $row;

}

function getAppPWD($rid) {
    
    require '/var/www/html/pages/backend/conf.php';
    
    $sql_select_r="SELECT request_string FROM users_requests.request_by_user WHERE r_id = $rid;";
    $result = mysqli_query($conn, $sql_select_r);
    $row    = mysqli_fetch_row($result);
    return $row;

}

function getAPPcount($user) {
        require '/var/www/html/pages/backend/conf.php';
	
	$sql_select_c="SELECT id FROM users_requests.users_compute WHERE user_id = $user AND instance_type IN ('wp','app');";
	$result = mysqli_query($conn, $sql_select_c);
	$counter=mysqli_num_rows($result);
	return $counter;      
}

function getDomains() {
    
        $result = $ec2Client->listResourceRecordSets([
            'HostedZoneId' => 'ZTXMYNPEUDOQV', // REQUIRED
            'StartRecordType' => 'SOA|A|TXT|NS|CNAME|MX|NAPTR|PTR|SRV|SPF|AAAA|CAA',
        ]);
	return $result;      
}


function getHelthStatus($type) {

    if($type=="k8s"){
        exec("curl --connect-timeout 1 18.203.2.118:443",$o,$v);
        if($v==0){
            return "We are online";
        }
        else{
            return "System is offline";
        }
    }

    if($type=="web"){
        exec("uptime -p",$o);
        if(isset($o)){
            return $o;
        }
    }
}

function getServerStatus() {
    return "OK";
}

function getVPSstatus($vpsid, $region) {

    $ec2Client = new Aws\Ec2\Ec2Client([
    'region' => $region,
    'version' => '2016-11-15',
    'profile' => 'default']);

    $result = $ec2Client->describeInstances([

    'InstanceIds' => [$vpsid],

]);
    //$status=$result['Reservations'][0]['Instances'][0]['State']['Name'];
    return $result;
}

function createWP ($user,$dns,$size) {

    $cmd = 'helm install client-'.$user.' stable/wordpress --set service.annotations."dns\.alpha\.kubernetes\.io\/internal"='.$dns.'.pjhosting.org.uk --dry-run';
    $output = shell_exec($cmd);
    return $output;
}


function createVPS ($region, $user, $image, $size, $string, $key, $type){
    require '/var/www/html/pages/backend/conf.php';
    
$ec2Client = new Aws\Ec2\Ec2Client([
    'region' => $region,
    'version' => '2016-11-15',
    'profile' => 'default'
]);

////////////////////////
if(empty($key)){
    $key="id_rsa";
}

if($type=="app"){
    $sg="sg-052b2a6636e54f9ed";
    $key="id_rsa";
    $subnet="subnet-0e86e15f15eb1eeff";
    if($image=="ami-0c16aed40c0ed2354"){
        $type="wp";
    }
    
}
if($type=="vps"){
    $sg="sg-07c06d32aefd31eae"; 
    $subnet="subnet-03dbfb65e26aeecf9";
}


    $securityGroupName = $user.'-'.$type.'-security-group';
    $result = $ec2Client->createSecurityGroup(array(
    'GroupName'   => $securityGroupName,
    'Description' => 'Users'.$user.' own sg',
    'VpcId' => 'vpc-0dd3944356428f0e8',
));

// Get the security group ID (optional)
$securityGroupId = $result->get('GroupId'); 




// Make tag for SG
// $ec2Client->createTags([

//     'Resources' => [$securityGroupId],
//     'Tags' => [ 
//         [
//             'Name' => 'User',
//             'Value' => '$user'
//         ],
//     ],
// ]);

// Launch an instance with the key pair and security group
$launchInstance = $ec2Client->runInstances(array(
    'ImageId'        => $image,
    'MinCount'       => 1,
    'MaxCount'       => 1,
    'InstanceType'   => $size,
    'KeyName'        => $key,
    'SubnetId'       => $subnet,
    'SecurityGroupIds' => array("$sg","$securityGroupId"),
    
    'TagSpecifications' => [
        [
            'ResourceType' => 'instance',
            'Tags' => [
                [
                    'Key' => 'Name',
                    'Value' => 'Client'.$user,
                ],
   
            ],
        ],
        // ...
    ],
));
//return instance id
$output=$launchInstance;
$id = $output['Instances'][0]['InstanceId'];

if(empty($id)){
    echo "something is wrong";
}
else{
    $sql_update="UPDATE users_requests.request_by_user SET done = 'done' WHERE request_type = 'create' AND request_string = '$string' LIMIT 1;";
    echo "done";
    if (mysqli_query($conn, $sql_update)) {
        echo "New instance created successfully";

        sleep(10); #TODO

        $result = $ec2Client->describeInstances([
        'InstanceIds' => [$id],
        ]);

        //$result2 = $ec2Client->get('PublicIpAddress');
        $publicIP=$result['Reservations'][0]['Instances'][0]['PublicIpAddress'];

        $sql_compute = "INSERT INTO users_requests.users_compute (user_id, instance_id, instance_type, instance_region, instance_ip, instance_size) VALUES('$user', '$id', '$type', '$region', '$publicIP', '$size');";
        if (mysqli_query($conn, $sql_compute)) {
            echo "Instance is accesable";
        }
        else {
            echo "Error: " . $sql_compute . "<br>" . mysqli_error($conn);
        }
    } else {
        echo "Error: " . $sql_update . "<br>" . mysqli_error($conn);
    }
}


}

function getCrons($u_id) {

    require '/var/www/html/pages/backend/conf.php';
    $sql_cron="SELECT id, user_id, instance_ids, `from`, till FROM users_cron.vps_cron WHERE user_id = $u_id;";

    $result = mysqli_query($conn, $sql_cron);
    return $result; 
}


function AddIngressRule($sg_id, $protocol, $ip, $port, $desc) {

         require 'vendor/autoload.php';
        $ec2Client = new Aws\Ec2\Ec2Client([
            'region' => 'eu-west-1',
            'version' => '2016-11-15',
            'profile' => 'default'
        ]);

        $result = $ec2Client->authorizeSecurityGroupIngress([
            'GroupId' => $sg_id,
            'IpPermissions' => [
                [
                    'FromPort' => $port,
                    'IpProtocol' => $protocol,
                    'IpRanges' => [
                        [
                            'CidrIp' => $ip,
                            'Description' => $desc,
                        ],
                    ],
                    'ToPort' => $port,
                ],
            ],
        ]);
        return $result;   
}

function revokeIngressRule($sg_id, $protocol, $ip, $port, $desc) {

        require 'vendor/autoload.php';
        $ec2Client = new Aws\Ec2\Ec2Client([
            'region' => 'eu-west-1',
            'version' => '2016-11-15',
            'profile' => 'default'
        ]);

        $result = $ec2Client->revokeSecurityGroupIngress([
            'GroupId' => $sg_id,
            'IpPermissions' => [
                [
                    'FromPort' => $port,
                    'IpProtocol' => $protocol,
                    'IpRanges' => [
                        [
                            'CidrIp' => "$ip",
                            'Description' => $desc,
                        ],
                    ],
                    'ToPort' => $port,
                ],
            ],
        ]);
        return $result;   
}


function changeDNS($zone_id,$dns,$target,$type){
    
        require 'vendor/autoload.php';
        $ec2Client = new Aws\Route53\Route53Client([
            'region' => 'eu-west-1',
            'version' => '2016-11-15',
            'profile' => 'default'
        ]);
    $result = $client->changeResourceRecordSets(array(
    // HostedZoneId is required
    'HostedZoneId' => $zone_id,
    // ChangeBatch is required
    'ChangeBatch' => array(
        'Comment' => 'Creating domain',
        // Changes is required
        'Changes' => array(
            array(
                // Action is required
                'Action' => 'CREATE',
                // ResourceRecordSet is required
                'ResourceRecordSet' => array(
                    // Name is required
                    'Name' => $dns,
                    // Type is required
                    'Type' => 'A',
                    'TTL' => 600,
                    'ResourceRecords' => array(
                        array(
                            // Value is required
                            'Value' => $target,
                        ),
                    ),
                ),
            ),
        ),
    ),
));
    return $result;  
}