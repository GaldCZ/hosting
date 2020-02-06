<?php
  @$content=$_GET['page'];
  if($content){
    $f=$content.".php";
    include('pages/'.$f);
  }                    
  else{

	if($loggedin==true){
    	include('pages/overview.php');
  	}
 	
 	else{	
		include('pages/intro.php');
	}
 }
  ?>