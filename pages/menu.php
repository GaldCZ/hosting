<nav class="w3-sidebar w3-collapse w3-white w3-animate-left" style="z-index:3;width:300px;" id="mySidebar"><br>
  <div class="w3-container w3-row">
    <div class="w3-col s4">
      <img src="/images/avatar2.png" class="w3-circle w3-margin-right" style="width:46px">
    </div>
    <div class="w3-col s8 w3-bar">
      <span>Welcome, <strong><?php echo $_SESSION['logged_username'];?></strong></span><br>
      <a href="#" class="w3-bar-item w3-button"><i class="fa fa-envelope"></i></a>
      <a href="#" class="w3-bar-item w3-button"><i class="fa fa-user"></i></a>
      <a href="#" class="w3-bar-item w3-button"><i class="fa fa-cog"></i></a>
    </div>
  </div>
  <hr>
  <div class="w3-container">
    <h5>Menu</h5>
  </div>
  <div class="w3-bar-block">
    <a href="#" class="w3-bar-item w3-button w3-padding-16 w3-hide-large w3-dark-grey w3-hover-black" onclick="w3_close()" title="close menu"><i class="fa fa-remove fa-fw"></i>  Close Menu</a>
    <a href="index.php" class="w3-bar-item w3-button w3-padding <?php if (@$_GET['page']=="") {echo "w3-blue";} ?>"><i class="fa fa-users fa-fw"></i>  Home</a>
    <a  onclick="myAccordion('demo')" href="javascript:void(0)" class="w3-bar-item w3-button w3-padding <?php if (@$_GET['page']=="vps" or @$_GET['page']=="vps_overview") {echo "w3-blue";} ?>"><i class="fa fa-desktop fa-fw"></i> VPS</a>
    <div id="demo" class="w3-hide">
      <a class="w3-bar-item w3-button" href="index.php?page=vps_overview"> Show</a>
      <a class="w3-bar-item w3-button" href="index.php?page=vps"> Request</a>
    </div>
    <a onclick="myAccordion('apps')" href="javascript:void(0)" href="" class="w3-bar-item w3-button w3-padding <?php if (@$_GET['page']=="apps") {echo "w3-blue";} ?>"><i class="fa fa-wordpress fa-fw"></i>  APPs</a>
    <div id="apps" class="w3-hide">
      <a class="w3-bar-item w3-button" href="index.php?page=apps_overview"> Show</a>
      <a class="w3-bar-item w3-button" href="index.php?page=apps"> Request</a>
    </div>
    <a href="index.php?page=domains" class="w3-bar-item w3-button w3-padding <?php if (@$_GET['page']=="domains") {echo "w3-blue";} ?>"><i class="fa fa-map-signs fa-fw"></i>  Domains</a>
    <a href="#" class="w3-bar-item w3-button w3-padding <?php if (@$_GET['page']=="billing") {echo "w3-blue";} ?>"><i class="fa fa-diamond fa-fw"></i>  Billing</a>
    <a href="#" class="w3-bar-item w3-button w3-padding <?php if (@$_GET['page']=="support") {echo "w3-blue";} ?>"><i class="fa fa-bell fa-fw"></i>  Support</a>
    <a href="#" class="w3-bar-item w3-button w3-padding <?php if (@$_GET['page']=="dbs") {echo "w3-blue";} ?>"><i class="fa fa-database fa-fw"></i>  DB's</a>
    <a href="index.php?page=firewall" class="w3-bar-item w3-button w3-padding <?php if (@$_GET['page']=="firewall") {echo "w3-blue";} ?>"><i class="fa fa-magnet fa-fw"></i>  Firewall</a>
    <a href="#" class="w3-bar-item w3-button w3-padding <?php if (@$_GET['page']=="account") {echo "w3-blue";} ?>"><i class="fa fa-cog fa-fw"></i>  Your account</a><br><br>
  



  
  </div>
  <hr>
<div class="w3-container" style="margin-bottom: 16px !important;">
  
  

  <?php 
  if (empty($_SESSION)) {
    echo '<a href="index.php?page=login" class="w3-button w3-hover-black">Login</a>';
  }


  if(isset($_SESSION) && @$_GET['logout']==1) {
    session_destroy();
    header('Location: https://user.pjhosting.org.uk/index.php');
  } 
  if(isset($_SESSION)){
    echo '<a class="w3-button w3-hover-black" href="?logout=1">Logout</a>';
  }
  ?>

</div>
</nav>