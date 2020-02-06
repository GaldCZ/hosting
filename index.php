 <!DOCTYPE html>
<html>
<title>User Panel | pjhosting.org.uk</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
html,body,h1,h2,h3,h4,h5 {font-family: "Raleway", sans-serif}
</style>
<body class="w3-light-grey">

<?php
session_start();

include_once 'pages/backend/functions.php';

@$loggedin=$_SESSION['logged_id'];
$user=$loggedin;
?>
<!-- Top container -->
<div class="w3-bar w3-top w3-black w3-large" style="z-index:4">
  <button class="w3-bar-item w3-button w3-hide-large w3-hover-none w3-hover-text-light-grey" onclick="w3_open();"><i class="fa fa-bars"></i>  Menu</button>
  <span class="w3-bar-item w3-right">Logo</span>
</div>

<!-- Sidebar/menu -->
<?php

if(@$_SESSION['logged_in']!=true){
    include_once 'pages/menu_reg.php';
}
if(@$_SESSION['logged_in']==true){
    include_once 'pages/menu.php';
}


?>


<!-- Overlay effect when opening sidebar on small screens -->
<div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:300px;margin-top:43px;">
    <!-- Header -->
<?php

if($loggedin==true){
    
    echo ' <header class="w3-container" style="padding-top:22px">
    <h5><b><i class="fa fa-dashboard"></i> My Dashboard</b></h5>
  </header>

  <div class="w3-row-padding w3-margin-bottom">
    <div class="w3-quarter">
      <div class="w3-container w3-hover-shadow w3-red w3-padding-16">
        <div class="w3-left"><i class="fa fa-comment w3-xxxlarge"></i></div>
        <div class="w3-right">
          <h3>'.getQcount($user).'</h3>
        </div>
        <div class="w3-clear"></div>
        <h4>In Queue</h4>
      </div>
    </div>
    <div class="w3-quarter">
    <a href="index.php?page=vps_overview" style="text-decoration: none;"> 
      <div class="w3-container w3-hover-shadow w3-blue w3-padding-16">
        <div class="w3-left"><i class="fa fa-laptop w3-xxxlarge"></i></div>
        <div class="w3-right">
          <h3> '.getVPScount($user).'</h3>
        </div>
        <div class="w3-clear"></div>
        <h4>VPS</h4>
      </div>
    </a>    
    </div>
    <div class="w3-quarter">
      <div class="w3-container w3-hover-shadow w3-teal w3-padding-16">
        <div class="w3-left"><i class="fa fa-share-alt w3-xxxlarge"></i></div>
        <div class="w3-right">
          <h3>'.getAPPcount($user).'</h3>
        </div>
        <div class="w3-clear"></div>
        <h4>WEB pages</h4>
      </div>
    </div>
    <div class="w3-quarter">
    <a href="index.php?page=domains" style="text-decoration: none;"> 
      <div class="w3-container w3-orange w3-text-white w3-hover-shadow w3-padding-16">
        <div class="w3-left"><i class="fa fa-map-signs w3-xxxlarge"></i></div>
        <div class="w3-right">
          <h3>50</h3>
        </div>
        <div class="w3-clear"></div>
        <h4>Domains</h4>
      </div>
    </div>
    </a> 
  </div>';
    
}

else {
    
    
}
?>    
 
<?php
    include_once 'content.php';
?>
 

  <!-- End page content -->
</div>

<script>
// Get the Sidebar
var mySidebar = document.getElementById("mySidebar");

// Get the DIV with overlay effect
var overlayBg = document.getElementById("myOverlay");

// Toggle between showing and hiding the sidebar, and add overlay effect
function w3_open() {
  if (mySidebar.style.display === 'block') {
    mySidebar.style.display = 'none';
    overlayBg.style.display = "none";
  } else {
    mySidebar.style.display = 'block';
    overlayBg.style.display = "block";
  }
}

// Close the sidebar with the close button
function w3_close() {
  mySidebar.style.display = "none";
  overlayBg.style.display = "none";
}

// Accordions
function myAccordion(id) {
  var x = document.getElementById(id);
  if (x.className.indexOf("w3-show") == -1) {
    x.className += " w3-show";
    x.previousElementSibling.className += " w3-theme";
  } else { 
    x.className = x.className.replace("w3-show", "");
    x.previousElementSibling.className = 
    x.previousElementSibling.className.replace(" w3-theme", "");
  }
}
</script>

</body>
</html>
