<?php 
  header("Content-type: text/html; charset=utf-8");// Setting Character Set
  session_start();
  require 'dbconfig.php'; // Import database profile
  $conn = @mysqli_connect(HOST,USER,PASS,DBNAME); // C.onection database
  if (!$conn) {
    echo 'Failure of database connection:'.mysqli_connect_error();
    exit;
  }

  function isError($conn){
    if (mysqli_error($conn)) {
      echo mysqli_error($conn);
      exit;
    }
  }
?>
<div id="header">
  <div class="container">
  <div class="logo">
   <a href="index.php">Movie Review Website</a>
  </div>
  <div class="r_info">
    <ul>
      <?php
				    // If logged in, display the logon status
					if(isset($_SESSION['users'])){
            $islogin = 1;
				       echo "<li><a class='login'>Hello, ".$_SESSION['nicename']."</a></li>
					           <li><a href='regedit.php?u=regedit'>[change Password]</a></li>
					  	       <li><a href='logout.php'>[logout]</a></li>";
				    }
					// Not logged in, showing the login and registration page
					else{
            $islogin = '';
				    echo "<li><a href='reg.php' >register</a></li>
						      <li><a href='login.php'>sign in</a></li>";
				    }
				?>
    </ul>
  
    <div class="tinfo"> </div>
  </div></div>
</div>
<div class="clear"></div>
<div id="nav">
  <div class="container">
    <ul>
      <li><a href="index.php">Home</a></li>
      <li><a href="recommends.php">Recommend</a></li>
      <li><a href="achievement.php">Achievement</a></li>
    </ul>
  </div>
</div>