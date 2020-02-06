<?php
echo "request";
$data[]=$_POST;
$FILE="requests.json";
print_r($_POST);

if (@$_POST['request']=="vps" && isset($_POST['size'])){
    
    include 'conf.php';   
    $dbname = "users_requests";

    // Create 
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $user   = $_POST['user'];   
    $tag    = $_POST['tag']; 
    $key    = $_POST['key']; 
    $size   = $_POST['size']; 
    $iam    = $_POST['image']; 
    $region = $_POST['region'];
    $string = $_POST['string'];
    $type   = $_POST['request'];

    $sql = "INSERT INTO request_by_user (user_id, user_key, request_type, request_string, request_tag, request_region, request_size, request_image, request_date, domain, type, done) VALUES ('$user', '$key', 'create', '$string', '$tag', '$region', '$size', '$iam', NOW(), '','$type','waiting');";

    if (mysqli_query($conn, $sql)) {
	    echo "New record created successfully";
	    $ReqJSON = json_encode($data);
            include_once '/var/www/html/pages/backend/functions.php';
            createVPS($region, $user, $iam, $size, $string, $key, $type);
	//exec('/var/www/html/pages/backend/create.sh '.$user.' '.$string." > log.txt");
	if (file_exists($FILE)) {
		echo "The file $FILE exists";
		$current = file_get_contents($FILE);
		$json_a = json_decode($current, true);
		$merge=array_merge($json_a,$data);
		echo "<pre>";
		print_r($merge);
		echo "</pre>";
		$final = json_encode($merge);
		file_put_contents($FILE, $final);
	} else {
		echo "The file $FILE does not exists";
		$ReqFile = fopen($FILE, "w") or die("Unable to open file!");
		fwrite($ReqFile, $ReqJSON);
		fclose($ReqFile);
	}
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    mysqli_close($conn);
}


///////////////////////APPS///////////////////////////


if (@$_POST['request']=="app" && isset($_POST['size'])){

       
    include_once 'conf.php';
    
    
    $dbname = "users_requests";

    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $user   = $_POST['user'];   
    $tag    = $_POST['tag']; 
    $key    = "id_rsa"; 
    $size   = $_POST['size']; 
    $iam    = $_POST['image'];
    $dns    = $_POST['dns']; 
    $region = $_POST['region'];
    $string = $_POST['string'];
    $type   = $_POST['request'];

    $sql = "INSERT INTO request_by_user (user_id, user_key, request_type, request_string, request_tag, request_region, request_size, request_image, request_date, domain, type, done) VALUES ('$user', '', 'create', '$string', '$tag', '$region', '$size', '$iam', NOW(), '$dns','$type','waiting');";

    if (mysqli_query($conn, $sql)) {
    #if('1'=='1') {

	    echo "New record created successfully";
	    $ReqJSON = json_encode($data);
        header("Location: https://user.pjhosting.org.uk/index.php?page=apps_overview");
           // echo createWP($user,$tag,); 
            //createVPS($region, $user, $iam, $size, $string, $key, $type);
	//exec('/var/www/html/pages/backend/create.sh '.$user.' '.$string." > log.txt");
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    mysqli_close($conn);
    
}

if (@$_POST['time_frame']=="Save changes" && isset($_POST['from']) && isset($_POST['till'])){

    include_once 'conf.php';
    
    
    $dbname = "users_cron";

    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    else {
        $from=$_POST['from'];
        $till=$_POST['till'];
        $i_id=json_encode($_POST['instance_id']);
        $u_id=$_POST['user_id'];
        if ($_POST['confirmed'] == true) {
            $sql = "INSERT INTO vps_cron (`id`, `user_id`, `instance_ids`, `from`, `till`) VALUES('', '$u_id', '$i_id', '$from', '$till');";
            if (mysqli_query($conn, $sql)) {
                echo "New record created successfully";
                header("Location: ".$servername."/index.php?page=vps_overview");
                die();
            }
            else{
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }

            
        }
        else {
            echo "you are trying to turn off the cron";
        
            }

    }





}

if(isset($_POST['update_rule'])){
    
}


if(isset($_POST['delete_rule'])){
    
}


if(isset($_POST['groupid'])){
    
    
    $ip=$_POST['source'];
    $port=$_POST['port'];
    $desc=$_POST['desc'];
    $sg_id=$_POST['groupid'];
    $protocol=$_POST['protocol'];

    echo 'function';
    if($_POST['add_rule']=="Add"){
        print_r(AddIngressRule($sg_id, $protocol, $ip, $port, $desc));
    }
    if(isset($_POST['delete_rule'])){
        print_r(revokeIngressRule($sg_id, $protocol, $ip, $port, $desc));
    }   
    

    
}

if(isset($_POST['hosted_zone']) && isset($_POST['add_domain'])) {

    $dns=$_POST['domain_name'];
    $type=$_POST['domain_type'];
    $ttl=$_POST['domain_ttl'];
    $target=$_POST['domain_target'];
    $zone=$_POST['hosted_zone'];
    changeDNS($zone, $dns, $target, $type);

}


if(isset($_POST['new_user']) && isset($_POST['email'])){
    echo "lets go";

    include_once 'conf.php';
    $dbname = "user_registration";

    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    mysqli_query($conn,"SET NAMES utf8");
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    else {
        $username=mysqli_real_escape_string($conn, $_POST['first']);
        $lastname=mysqli_real_escape_string($conn, $_POST['last']);
        $email=mysqli_real_escape_string($conn, $_POST['email']);
        $phone=mysqli_real_escape_string($conn, $_POST['phone']);
        $password=mysqli_real_escape_string($conn, $_POST['psw']);
        $password_hash=password_hash($password, PASSWORD_DEFAULT);

        $bytes = 20;
        $result = bin2hex(random_bytes($bytes));
        

        //Check if user exist

        $sql_check_user=mysqli_query("SELECT user_email FROM user_registration.users WHERE user_email='".$email."';");
        echo $sql_check_user;

        if(mysqli_num_rows($sql_check_user) > 0) {
            echo "User already exists";
        }

        else {
            
            $sql_add_user="INSERT INTO user_registration.users (user_firstname, user_surname, phone, user_email, password, verify_url, verified, enabled) 
            VALUES('".$username."', '".$lastname."', '".$phone."', '".$email."', '".$password_hash."', '".$result."', 'false', 'false');";


            if (mysqli_query($conn, $sql_add_user)) {
                echo "New record created successfully";
                $recipient_emails = [$email];
                $user = $username;
                include_once 'mail.php';
                
            } else {
                echo "Error: " . $sql_add_user . "<br>" . mysqli_error($conn);
            }

        }








    }







}


?> 
