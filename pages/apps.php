
<form action="pages/backend/request.php" class="w3-container w3-card-4 w3-light-grey w3-text-blue w3-margin" method="POST">
 <input type="hidden" name="request" value="app">
 
 
<?php
if(@$_SESSION['logged_in']!=true){
    exit;
}

$string = base64_encode(random_bytes(10));
echo '<input type="hidden" name="string" value="'.$string.'">';
echo '<input type="hidden" name="user" value="'.$user.'">';
?>   
<h2 class="w3-center">Request App</h2>
 
<div class="w3-row w3-section">
  <div class="w3-col" style="width:50px"><i class="w3-xxlarge fa fa-id-card"></i></div>
    <div class="w3-rest">
      <input class="w3-input w3-border" name="tag" type="text" placeholder="App name">
    </div>
</div>

<div class="w3-row w3-section">
  <div class="w3-col" style="width:50px"><i class="w3-xxlarge fa fa-share-alt"></i></div>
    <div class="w3-rest">
      <input class="w3-input w3-border" name="dns" type="text" placeholder="pjhosting.org.uk">
    </div>
</div>


<!--<div class="w3-row w3-section">
  <div class="w3-col" style="width:50px"><i class="w3-xxlarge fa fa-key"></i></div>
    <div class="w3-rest">
        <input class="w3-input w3-border" placeholder="" type="file" name="key">Your PUB key?</input>
    </div>
</div>-->

<div class="w3-row w3-section">
  <div class="w3-col" style="width:50px"><i class="w3-xxlarge fa fa-wrench"></i></div>
    <div class="w3-rest">
  <select class="w3-select w3-border" name="size">
    <option value="" disabled selected>Choose size</option>
    <option value="1">Nano</option>
    <option value="2">Micro</option>   
    <option value="3">Medium</option>
  </select>
    </div>
</div>

<div class="w3-row w3-section">
  <div class="w3-col" style="width:50px"><i class="w3-xxlarge fa fa-cog"></i></div>
    <div class="w3-rest">
  <select class="w3-select w3-border" name="image">
    <option value="" disabled selected>Choose app</option>
    <option value="stable/wordpress">Wordpress</option>
    <option value="ami-" disabled>Tomcat</option>
    <option value="ami-" disabled>Apache</option>
  </select>
    </div>
</div>

<div class="w3-row w3-section">
  <div class="w3-col" style="width:50px"><i class="w3-xxlarge fa fa-globe"></i></div>
    <div class="w3-rest">
    <select class="w3-select w3-border" name="region">
    <option value="" disabled selected>Choose region</option>
    <option value="eu-west-1">EU west</option>
    <option value="eu-west-1" disabled>EU east</option>
  </select>
    </div>
</div>

<button class="w3-button w3-block w3-section w3-blue w3-ripple w3-padding">Send</button>

</form>

