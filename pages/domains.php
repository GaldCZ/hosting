<?php
require 'backend/vendor/autoload.php';
use Aws\Route53\Route53Client;


//use Aws\Route53\Route53Client;
//$client = Route53Client::factory(array(
//    'profile' => 'default'
//));
//
//        $result = $client->listResourceRecordSets([
//            'HostedZoneId' => 'ZTXMYNPEUDOQV', // REQUIRED
//        ]);
//	print_r($result);
        
$hostedZone="ZTXMYNPEUDOQV"; #TODO WHAT user is using which zone

$client = Route53Client::factory(array(
    'profile' => 'default',
    'region'  => 'eu-west-2',
    'version' => 'latest',
));
        
        
$result = $client->listResourceRecordSets(array(
    // Id is required
    'HostedZoneId' => $hostedZone,
));

// echo "<pre>";
// print_r($result['ResourceRecordSets']);
// echo "</pre>";
?>

<div class="w3-container">
      
  <div class="w3-center">
    <h2>Your domains</h2>
    <p w3-class="w3-large">Don't worry. W3.CSS takes care of your tables.</p>
  </div>
 <div class="w3-responsive w3-card-4">   
<table class="w3-table w3-striped w3-bordered">
<thead>
<tr class="w3-theme">
  <th>Name</th>
  <th>Type</th>
  <th>TTL</th>
  <th>Records</th>
  <th>State</th>
  <th>Action</th>
</tr>
</thead>
<tbody>

<?php
$result=$result['ResourceRecordSets'];
for($x=2;$x<count($result);$x++){
    
    // output data of each row
  
        
        if($x%2==0){
          echo '<tr class="w3-light-grey">';  
        }
        else {
          echo "<tr>";  
        }
        echo "<td>".$result[$x]["Name"]."</td>";
        echo "<td>".$result[$x]["Type"]."</td>";
        echo "<td>".$result[$x]["TTL"]."</td>";
        echo "<td>";
        echo $result[$x]["ResourceRecords"][0]['Value'];
        echo "</td>";
        echo "<td>expirace</td>";
        echo "<td>X</td>";
        echo "</tr>";
    
    }

?>    
<tr>
<form action="index.php?page=/backend/request" method="POST">  
  <td><input type="text" name="domain_name" placeholder="example.com"></td>
  <td>
    <select name="domain_type">
      <option value="d_type_a">A</option>
      <option value="d_type_cn">CNAME</option>
    </select>
  </td>
  <td>
    <input type="number" name="domain_ttl" value="300">
  </td>
  <td>
    <input type="text" name="domain_target" placeholder="11.22.33.44">
    <input type="hidden" name="hosted_zone" value="<?= $hostedZone ?>">
  </td>  
  <td></td>
  <td>
    <input type="submit" name="add_domain" value="Add domain">
  </td>
</form> 
</tr>    
<!--<tr class="w3-white">-->

</tbody>
</table>    
 </div>
</div>