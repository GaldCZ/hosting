
<div class="w3-container">
  <hr>
  <div class="w3-center">
    <h2>Your VPS</h2>
    <p w3-class="w3-large">Don't worry. W3.CSS takes care of your tables.</p>
  </div>
<div class="w3-responsive w3-card-4">
<table class="w3-table w3-striped w3-bordered">
<thead>
<tr class="w3-theme">
  <th>ID</th>
  <th>Region</th>
  <th>IP</th>
  <th>Size</th>
  <th>State</th>
  <th>Action</th>
</tr>
</thead>
<tbody>

<?php
//echo $user;
$result=getVPSover($user);


if (mysqli_num_rows($result) > 0) {
    $i=0;
    while($row = mysqli_fetch_assoc($result)) {
        $resultx=getVPSstatus($row["instance_id"], $row["instance_region"]);
        $status=$resultx['Reservations'][0]['Instances'][0]['State']['Name'];
        @$publicIP=$resultx['Reservations'][0]['Instances'][0]['PublicIpAddress'];
        if($i%2==0){
          echo '<tr class="w3-white">';  
        }
        else {
          echo "<tr>";  
        }
        echo "<td>".$row["id"]."</td>";
        echo "<td>".$row["instance_region"]."</td>";
        echo "<td>".$publicIP."</td>";
        echo "<td>".$row["instance_size"]."</td>";
        echo "<td>".$status."</td>";
        echo '<td>
               <form action="pages/backend/vps_action.php" method="POST"> 
               <input type="hidden" name="instanceId" value="'.$row["instance_id"].'">
               <input type="hidden" name="instanceRegion" value="'.$row["instance_region"].'">
               <select class="w3-select w3-theme" name="action">
                  <option value="" disabled selected>Select</option>
                  <option value="STOP"> Stop</option>
                  <option value="START"> Start</option>
                  <option value="REBOOT"> Reboot</option>
                  <option value="TERMINATE"> Delete</option>
                  <option value="CONNECT"> Connect</option>
                </select>
               
              </td>';
              echo '<td><button type="submit" name="actionform" value="true" class="w3-button w3-theme"><i class="fa fa-check-circle"></i></button>
                </form></td>';
        echo ' </tr>';
        $i++;
    }
} else {
    echo "0 results";
}

?>    
    
<!--<tr class="w3-white">-->

</tbody>
</table>
 </div>


<div class="w3-container">
      
    <div class="w3-center">
      <h2>Let your VPS sleep</h2>
      <p w3-class="w3-large">And save your credit.</p>
    </div>
  <div class="w3-responsive w3-card-4">   
  <table class="w3-table w3-striped w3-bordered">
  <thead>
  <tr class="w3-theme">
    <th>Time frame</th>
    <th>Instances</th>
    <th>Active</th>
    <th>Action</th>
  </tr>
  </thead>
  <tbody>

<?php

$result=getVPSover($user);
$crons=getCrons($user);
if ($crons->num_rows > 0) {
    // output data of each row
    while($row = $crons->fetch_assoc()) {
     
        
echo "<td>".
     'from: <input type="time" id="appt" name="from" value="'.$row["from"].'" required>'
     .'till: <input type="time" id="appt" name="till" value="'. $row["till"].'" required>'
     ."</td>";        
      echo "<td>"; 
        echo " - Name: " . $row["instance_ids"];
      echo "</td><td>"
        . "<input type='checkbox' name='instance_id[]' value='".$row["instance_id"]."' checked></td>";  
    }
}

echo '<form action="pages/backend/request.php"  method="POST" value="1">';
echo "<tr>";  
 
echo "<td>".
     'from: <input type="time" id="appt" name="from" value="21:00" required>'
     .'till: <input type="time" id="appt" name="till" value="06:00" required>'
     ."</td><td>";

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<input type='checkbox' name='instance_id[]' value=".$row["instance_id"].">".$row["id"];
    }
}
else {
  echo "no instance";
}


echo "</td><td><input type='checkbox' name='confirmed' value='true'></td>";
echo "<td><input type='submit' name='time_frame' value='Save changes'></td>";
echo "</tr><input type='hidden' name='user_id' value='".$user."'>"
. "</form>";


?>    
    
<!--<tr class="w3-white">-->

</tbody>
</table>    
 </div>
</div>

  
  
<div class="w3-container">
      
    <div class="w3-center">
      <h2>Your VPS in queue</h2>
      <p w3-class="w3-large">Don't worry. W3.CSS takes care of your tables.</p>
    </div>
  <div class="w3-responsive w3-card-4">   
  <table class="w3-table w3-striped w3-bordered">
  <thead>
  <tr class="w3-theme">
    <th>ID</th>
    <th>Region</th>
    <th>Size</th>
    <th>Date</th>
    <th>State</th>
    <th>Action</th>
  </tr>
  </thead>
  <tbody>

<?php

$resultQ=getQlist($user);

if (mysqli_num_rows($resultQ) > 0) {
    // output data of each row
    $i=0;
    while($rowq = mysqli_fetch_assoc($resultQ)) {
        
        if($i%2==0){
          echo '<tr class="w3-white">';  
        }
        else {
          echo "<tr>";  
        }
        echo "<td>".$rowq["r_id"]."</td>";
        echo "<td>".$rowq["request_region"]."</td>";
        echo "<td>".$rowq["request_size"]."</td>";
        echo "<td>".$rowq["request_date"]."</td>";
        echo "<td>running</td>";
        echo "<td>stop</td>";
        echo "</tr>";
        $i++;
    }
} else {
    echo '<tr><td>0 results</td></tr>';
}

?>    
    
<!--<tr class="w3-white">-->

</tbody>
</table>    
 </div>
</div>
