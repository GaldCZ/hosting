<?php
use Aws\Ec2\Ec2Client;

print_r($_POST);
if (isset($_POST['request_id']) && $_POST['actionform']=="app" && $_POST['action']=="TERMINATE"){
    echo "Delete in progress";
    include_once 'functions.php';
    echo updateAPP($_POST['request_id'],"delete",$_POST['user_id']);
    header("Location: https://user.pjhosting.org.uk/index.php?page=apps_overview");

}




if ($_POST['actionform']=="true" && isset($_POST['instanceId'])) {

require 'vendor/autoload.php';

$action = $_POST['action'];
$inId = $_POST['instanceId'];
$inRegion = $_POST['instanceRegion'];

$ec2Client = new Aws\Ec2\Ec2Client([
    'region' => $inRegion,
    'version' => '2016-11-15',
    'profile' => 'default'
]);




$instanceIds = array($inId);

if ($action == 'START') {
    $result = $ec2Client->startInstances(array(
        'InstanceIds' => $instanceIds,
    ));
} 
if ($action == 'STOP'){
    $result = $ec2Client->stopInstances(array(
        'InstanceIds' => $instanceIds,
    ));
}
if ($action == 'REBOOT'){
    $result = $ec2Client->rebootInstances(array(
        'InstanceIds' => $instanceIds,
    ));
}
if ($action == 'TERMINATE'){
    $result = $ec2Client->terminateInstances(array(
        'InstanceIds' => $instanceIds,
    ));
}
else {
    echo "Action error";
}

var_dump($result);

}