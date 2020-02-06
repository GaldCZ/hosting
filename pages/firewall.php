<?php

$ec2Client = new Aws\Ec2\Ec2Client([
    'region' => 'eu-west-1',
    'version' => '2016-11-15',
    'profile' => 'default'
]);

$result = $ec2Client->describeSecurityGroups([
    'Filters' => [
        [
            'Name' => 'tag:User',
            'Values' => ['3',],
        ],

    ],

]);

echo "<pre>";
print_r($result);
echo "</pre>";


?>


<div class="w3-container">
      
  <div class="w3-center">
    <h2>Your firewalls</h2>
    <p w3-class="w3-large">Don't worry. W3.CSS takes care of your tables.</p>
  </div>
 <div class="w3-responsive w3-card-4">   


<?php



$results=$result['SecurityGroups'];
for($x=0;$x<count($results);$x++){
echo '
<table class="w3-table w3-striped w3-bordered">
<h3 class="w3-center"> '.$results[$x]["GroupName"].'</h3>
<thead>
<tr class="w3-theme">
  <th>Protocol</th>
  <th>Port</th>
  <th>Source ip</th>
  <th>Info</th>
  <th>Action</th>

</tr>
</thead>
<tbody>';    
    // output data of each row
  
        

        $rules_count = count($results[$x]["IpPermissions"]);
        
        for($y=0;$y<$rules_count;$y++){
          $protocol=$results[$x]["IpPermissions"][$y]['IpProtocol'];
          @$port=$results[$x]["IpPermissions"][$y]['FromPort'];
          $ip=$results[$x]["IpPermissions"][$y]['IpRanges'][0]['CidrIp'];
          @$info=$results[$x]["IpPermissions"][$y]['IpRanges'][0]['Description'];
          if($protocol=='-1'){
            $protocol="All";
          }
          if(empty($port)){
            $port="All";
          }          


         if($x%2==0){
          echo '<tr class="w3-white">';  
        }
        else {
          echo "<tr>";  
        }           
            echo "
            <form action='index.php?page=/backend/request' method='POST' name='form1'>  
            <td><input type='hidden' name='protocol' value='".$protocol."'>".$protocol."</td>";
            echo "<td><input type='number' value='".$port."' name='port'></td>";
            echo "<td><input type='text' name='source' value='".$ip."' pattern='^((\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])+\/(0|16|24|32)$'></td>"; #TODO vic ip ranges
            echo "<td><input type='text' value='".$info."' name='desc'></td>";
            echo "<td><input type='submit' name='update_rule' value='Update'></input><input type='hidden' name='groupid' value='".$results[$x]["GroupId"]."'></input>
            <input type='submit' name='delete_rule' value='Delete'>
            </td></form>";
        }

        echo "</tr>";

        
echo "<tr>";
echo "<td><form name='new_rule' action='index.php?page=/backend/request' method='POST'>
    
<select name='protocol'>
    <option value='-1'>All</option>
    <option value='tcp'>TCP</option>
    <option value='udp'>UDP</option>
</select></td>";

echo "<td><input type='number' name='port'></td>";
echo '<td><input type="text" name="source" minlength="7" maxlength="15" size="15" pattern="^((\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])+\/(0|16|24|32)$"></td>';
echo "<td><input type='text' name='desc' maxlength='20'><input type='hidden' name='groupid' value='".$results[$x]["GroupId"]."'></input></td>
<td><input type='submit' name='add_rule' value='Add'></td>
</form>";        
    }




?>    
    
<!--<tr class="w3-white">-->

</tbody>
</table>    
 </div>
</div>
