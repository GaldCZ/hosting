<header class="w3-container w3-indigo w3-padding-64 w3-centere" style="padding:64px 32px">
  <h1 class="w3-xxxlarge w3-center w3-padding-16">LOGIN</h1>
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
          <input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="Email" name="email" value="<?php echo @$_GET['email']; ?>" required>
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
include_once 'backend/conf.php';
if(isset($_POST['email']) && isset($_POST['psw'])){
  
  $sql_login="SELECT * FROM user_registration.users WHERE user_email='".$_POST['email']."' AND verified='true' AND enabled='true' LIMIT 1;";
  $result_login = mysqli_query($conn, $sql_login);
  if (mysqli_num_rows($result_login) > 0) {
    //print_r($result_login);
    $row = mysqli_fetch_assoc($result_login);
    //print_r($row);
    #Successful login
    if (password_verify($_POST['psw'], $row['password']) && $_POST['email'] == $row['user_email']) {
      echo 'Password is valid!';
      #todo session expiration https://solutionfactor.net/blog/2014/02/08/implementing-session-timeout-with-php/
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
  else{
    echo "Wrong email";
  }
}
?>