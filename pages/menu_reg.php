<nav class="w3-sidebar w3-collapse w3-white w3-animate-left" style="z-index:3;width:300px;" id="mySidebar"><br>

 
  <div class="w3-container">
    <h5>Menu</h5>
  </div>

  <div class="w3-bar-block">
    <a href="#" class="w3-bar-item w3-button w3-padding-16 w3-hide-large w3-dark-grey w3-hover-black" onclick="w3_close()" title="close menu"><i class="fa fa-remove fa-fw"></i>Close Menu</a>
    <a href="index.php" class="w3-bar-item w3-button w3-padding <?php if (@$_GET['page']=="") {echo "w3-blue";} ?>"><i class="fa fa-users fa-fw"></i>  Home</a>  
    <a href="index.php?page=registration" class="w3-bar-item w3-button w3-padding <?php if (@$_GET['page']=="registration") {echo "w3-blue";} ?>"><i class="fa fa-map-signs fa-fw"></i>  Registration</a>
    <a href="#" class="w3-bar-item w3-button w3-padding <?php if (@$_GET['page']=="billing") {echo "w3-blue";} ?>"><i class="fa fa-diamond fa-fw"></i>  About</a>
    <a href="#" class="w3-bar-item w3-button w3-padding <?php if (@$_GET['page']=="support") {echo "w3-blue";} ?>"><i class="fa fa-bell fa-fw"></i>  Pricing</a>
    <a href="#" class="w3-bar-item w3-button w3-padding <?php if (@$_GET['page']=="dbs") {echo "w3-blue";} ?>"><i class="fa fa-database fa-fw"></i>  Contact</a>
  
  </div>
<hr>
<div class="w3-container" style="margin-bottom: 16px !important;">
  <a href="index.php?page=login" class="w3-button w3-hover-black">Login</a>
  

  <?php 
  if(@$_GET['logout']==1) {
    session_destroy();
    header('Location: https://user.pjhosting.org.uk/index.php');
  } 
  if(isset($_SESSION['logged_in'])){
    echo '<a class="w3-button w3-hover-black" href="?logout=1">Logout</a>';
  }
  ?>

</div>
</nav>
