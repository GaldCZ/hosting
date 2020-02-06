<?php
include_once 'conf.php';

session_unset();
$dbname = "user_registration";
//print_r($_GET);
    // Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
    // Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$sql_validation="SELECT user_email, password, verify_url, verified, enabled FROM user_registration.users WHERE user_email='".$_GET['email']."' AND verify_url='".$_GET['code']."' LIMIT 1;";
$result = mysqli_query($conn, $sql_validation);


if (mysqli_num_rows($result) > 0) {
    // output data of each row
	$sql_verifi_update="UPDATE user_registration.users SET verified='true' WHERE user_email='".$_GET['email']."' LIMIT 1;";
	$result = mysqli_query($conn, $sql_verifi_update);

} else {
	echo '<header class="w3-container w3-indigo w3-padding-64 w3-centere" style="padding:64px 32px">
  <h1 class="w3-xxxlarge w3-center w3-padding-16">404</h1>
  </header>';	
   header('HTTP/1.0 404 Not Found', true, 404);
   die;
}

?>
<header class="w3-container w3-indigo w3-padding-64 w3-centere" style="padding:64px 32px">
  <h1 class="w3-xxxlarge w3-center w3-padding-16">ACTIVATE YOUR ACCOUNT</h1>
</header>
<div class="w3-container">
<hr>

  <!-- <button onclick="document.getElementById('id01').style.display='block'" class="w3-button w3-green w3-large">Login</button> -->

  <div id="id01">
    <div class="w3-modal-content w3-card-4" style="max-width:600px">
  
<!--       <div class="w3-center"><br>
        <img src="img_avatar4.png" alt="Avatar" style="width:30%" class="w3-circle w3-margin-top">
      </div> -->

      <form class="w3-container" action="#" method="POST">
        <div class="w3-section">
          <label><b>Email</b></label>
          <input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="Email" name="email" value="<?php echo $_GET['email']; ?>" required>
          <label><b>Password</b></label>
          <input class="w3-input w3-border" type="password" placeholder="Enter Password" name="psw" required>
          <button class="w3-button w3-block w3-green w3-section w3-padding" type="submit">Login</button>
          <input class="w3-check w3-margin-top" type="checkbox" checked="checked"> Remember me
        </div>
      </form>

      <div class="w3-container w3-border-top w3-padding-16 w3-light-grey">
        <button type="button" class="w3-button w3-red">Cancel</button>
        <span class="w3-right w3-padding w3-hide-small">Forgot <a href="#">password?</a></span>
      </div>

    </div>
  </div>
</div>

<?php

if(@$_POST['email'] == $_GET['email'] && isset($_POST['psw'])){
	echo 'login';
	$sql_login="SELECT * FROM user_registration.users WHERE user_email='".$_GET['email']."' AND verify_url='".$_GET['code']."' LIMIT 1;";
	$result_login = mysqli_query($conn, $sql_login);
	if (mysqli_num_rows($result_login) > 0) {
		//print_r($result_login);
    $row = mysqli_fetch_assoc($result_login);
    //print_r($row);
    #Successful login
    if (password_verify($_POST['psw'], $row['password']) && $_GET['code'] == $row['verify_url'] && $_POST['email'] == $row['user_email']) {
      echo 'Password is valid!';
      
      mysqli_query($conn, 'UPDATE user_registration.users SET verified=true, enabled=true WHERE u_id='.$row['u_id'].';');

      $_SESSION['logged_in'] = true;
      $_SESSION['logged_email'] = $row['user_email'];
      $_SESSION['logged_id'] = $row['u_id'];
      $_SESSION['logged_username'] = $row['user_firstname'];

      header("Location: https://user.pjhosting.org.uk/");
      exit();
      //print_r($_SESSION);

    }
    else {
      echo 'Invalid password.';

      }

	}
}  


