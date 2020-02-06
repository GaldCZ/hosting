
<div class="w3-container">
  <hr>
  <div class="w3-center">
    <h2>Your APPS</h2>
    <p w3-class="w3-large">Don't worry. W3.CSS takes care of your tables.</p>
  </div>
<div class="w3-responsive w3-card-4">
<table class="w3-table w3-striped w3-bordered">
<thead>
<tr class="w3-theme">
  <th>ID</th>
  <th>Region</th>
  <th>DNS</th>
  <th>Size</th>
  <th>State</th>
  <th>Password</th>
  <th>Action</th>
</tr>
</thead>
<tbody>

<?php
//echo $user;
$results=getAPPSover($user);
print_r($result);

if (mysqli_num_rows($results) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($results)) {

        $result=getServerStatus($row["instance_id"]);
        //$status=$result['Reservations'][0]['Instances'][0]['State']['Name'];
        #$status=shell_exec("kubectl get pods -n client --server=https://18.203.2.118 --insecure-skip-tls-verify --user=admin --password=rcuGGDU1jEOfccUdolE36jrEHyumzVar");
        //@$publicIP=$result['Reservations'][0]['Instances'][0]['PublicIpAddress'];

        echo "<tr>";
        echo "<td>".$row["id"]."</td>";
        echo "<td>".$row["instance_region"]."</td>";
        echo "<td><a target='_blank' href='http://".getAppDNS($row["request_id"])[0]."'>".getAppDNS($row["request_id"])[0]."</a></td>";
        echo "<td>".$row["instance_size"]."</td>";
        echo "<td>".$status."</td>";
        echo "<td>".getAppPWD($row["request_id"])[0]."</td>";
        echo '<td>
               <form action="pages/backend/vps_action.php" method="POST"> 
               <input type="hidden" name="request_id" value="'.$row["request_id"].'">
               <input type="hidden" name="user_id" value="'.$user.'">
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
              echo '<td><button type="submit" name="actionform" value="app" class="w3-button w3-theme"><i class="fa fa-check-circle"></i></button>
                </form></td>';
        echo ' </tr>';
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
    <h2>Your VPS in queue</h2>
    <p w3-class="w3-large">Don't worry. W3.CSS takes care of your tables.</p>
  </div>
 <div class="w3-responsive w3-card-4">   
<table class="w3-table w3-striped w3-bordered">
<thead>
<tr class="w3-theme">
  <th>Request</th>
  <th>Region</th>
  <th>DNS</th>
  <th>Size</th>
  <th>Date</th>
  <th>State</th>

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
        echo "<td><a target='_blank' href='http://".getAppDNS($rowq["r_id"])[0]."'>".getAppDNS($rowq["r_id"])[0]."</a></td>";
        echo "<td>".$rowq["request_size"]."</td>";
        echo "<td>".$rowq["request_date"]."</td>";
        echo "<td>waiting</td>";
        echo "</tr>";
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
</div>
<script type="text/javascript">
  function checkServerStatus()
{
    setServerStatus("unknown");
    var img = document.body.appendChild(document.createElement("img"));
    img.onload = function()
    {
        setServerStatus("online");
    };
    img.onerror = function()
    {
        setServerStatus("offline");
    };
    img.src = "http://spat.pjhosting.org.uk/wp-admin/images/wordpress-logo.svg";
}

</script>