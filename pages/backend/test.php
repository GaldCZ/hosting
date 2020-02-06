<?php
require 'vendor/autoload.php';
use Aws\Ec2\Ec2Client;

$ec2Client = new Aws\Ec2\Ec2Client([
    'region' => 'eu-west-1',
    'version' => '2016-11-15',
    'profile' => 'default'
]);



////////////////////////

// Launch an instance with the key pair and security group
//$launchInstance = $ec2Client->runInstances(array(
//    'ImageId'        => 'ami-077a5b1762a2dde35',
//    'MinCount'       => 1,
//    'MaxCount'       => 1,
//    'InstanceType'   => 't2.micro',
//    'KeyName'        => 'id_rsa',
//    'SecurityGroups' => array('user'),
//        'TagSpecifications' => [
//        [
//			'ResourceType' => 'instance',
//            'Tags' => [
//                [
//                    'Key' => 'Name',
//                    'Value' => 'Client',
//                ],
//                // ...
//            ],
//        ],
//        // ...
//    ],
//));
////return instance id
//$output=$launchInstance;
//
//echo "<pre>";
//print_r($output);
//echo "</pre>";
//echo $output['Instances'][0]['InstanceId'];
// Wait until the instance is launched
// $ec2Client->waitUntil('InstanceRunning', ['InstanceIds' => array($instanceIds)]); 
// // Describe the now-running instance to get the public URL
// $result = $ec2Client->describeInstances(array(
//     'InstanceIds' => $instanceIds,
// ));
// echo current($result->getPath('Reservations/*/Instances/*/PublicDnsName'));




//$result = $ec2Client->describeInstances([
//
//    'InstanceIds' => ['i-08bb85c34cd782ca3'],
//
//]);
//echo "xxx".$result->getPath('Reservations/*/Instances/*/PublicIpAddress');
////$result2 = $ec2Client->get('PublicIpAddress');
//$result2=$result['Reservations'][0]['Instances'][0]['State']['Name'];
//echo '<pre>';
//echo $result;
//echo '</pre>';
//


$result = $ec2Client->getConsoleOutput([
    'DryRun' => false,
    'InstanceId' => 'i-038753114df0a2045', // REQUIRED
    'Latest' => true,
]);

echo '<pre>';
echo $result;
echo '</pre>';