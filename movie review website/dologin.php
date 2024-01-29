<?php
	header("Content-type: text/html; charset=utf-8");
	/* Verify username and password*/
	@session_start();
	

	// Verify the accounts and password in the database
	include('dbconfig.php');
	$link = @mysqli_connect(HOST,USER,PASS,DBNAME);
	if (!$link) {
		echo "<script>alert(\"'Failure of database connection:".mysqli_connect_error()."\");location.href='login.php'</script>";
	}
	mysqli_set_charset($link,'utf8');

	switch ($_GET['a']) {
		case 'login'://Sign in
			//Acceptance-judgment of the validity of parameters
			$username = $_POST['username'];
			$password  = $_POST['password'];

			$sql = "select username,nicename,password from users where username='{$username}'";
			$result = mysqli_query($link,$sql);
			
			if($result && mysqli_num_rows($result)>0){//User presence
				$row = mysqli_fetch_assoc($result);
				if($password != $row['password']){//Judging password
					echo "<script>alert('Password error, please re-enter！');history.back()</script>";
					exit;
				}

				$_SESSION['users'] = $row['username'];
				$_SESSION['nicename'] = $row['nicename'];

				if (isset($_GET['u']) && $_GET['u']!='') {
					echo "<script>alert('Successful login！');location.href='".$_GET['u'].".php'</script>";
				}else{
					echo "<script>alert('Successful login！');location.href='index.php'</script>";
				}
			}else{//user name does not exist
			    echo "<script>alert('User name does not exist, please re-enter！');history.back()</script>";
			}
			break;

		case 'edit'://Change Password
			//Acceptance-judgment of the validity of parameters
			$username = $_SESSION['users'];
			$oldpwd  = $_POST['oldpwd'];
			$password  = $_POST['password'];
			$repassword  = $_POST['repassword'];

	        if(!preg_match("/^[a-zA-Z0-9]{4,16}$/", $password)){
				echo "<script>alert('Please enter a new password of 4-16 bits！');history.back()</script>";
				exit;
			 }
			 if(!preg_match("/^[a-zA-Z0-9]{4,16}$/", $repassword)){
				echo "<script>alert('Please enter an 4-16-bit confirmation password！');history.back()</script>";
				exit;
			}
			if ($password != $repassword) {
				echo "<script>alert('Two password entries are inconsistent, please re-enter！');history.back()</script>";
				exit;
			}

			$sql = "select username,password from users where username='{$username}'";
			$result = mysqli_query($link,$sql);
			if($result && mysqli_num_rows($result)>0){//User presence
				$row = mysqli_fetch_assoc($result);
				if($oldpwd != $row['password']){//Judging password
					echo "<script>alert('Password error, please re-enter！');history.back()</script>";
					exit;
				}
				
		        $insSql = "update users set password='".$password."' where username = '{$username}'";
				$result =  mysqli_query($link,$insSql);
				if (mysqli_affected_rows($link)>0) {
					echo "<script>alert('Successful modification.');location.href='regedit.php'</script>";
				}else{
					echo "<script>alert('Modification failed.');history.back()</script>";
				}
			}else{
			    echo "<script>alert('User name does not exist, please re-enter.');history.back()</script>";
			}
			break;	
		
		case 'register'://Register

			//Acceptance-judgment of the validity of parameters
			$username = $_POST['username'];
			$password  = $_POST['password'];
			$repassword = $_POST['repassword'];
			$nicename = $_POST['nicename'];
	
		    if(!preg_match("/^[a-zA-Z0-9]{4,16}$/", $password)){
				echo "<script>alert('Please enter a password of 4-16 bits！');history.back()</script>";
				exit;
			}
			if(!preg_match("/^[a-zA-Z0-9]{4,16}$/", $repassword)){
				echo "<script>alert('Please enter an 4-16-bit confirmation password！');history.back()</script>";
				exit;
			}
			if ($password != $repassword) {
				echo "<script>alert('Two password entries are inconsistent, please re-enter！');history.back()</script>";
				exit;
			}
			
		
		    $selSql = "select id from users where username='{$username}'";
			$selResult = mysqli_query($link,$selSql);

			if($selResult && mysqli_num_rows($selResult)>0){// User presence
				echo "<script>alert('The username already exists. Please change the new username！');lhistory.back()</script>";
				exit;
			}

	        $insSql = "insert into users (username,password,nicename) ";
	        $insSql .=" values('$username','$password','$nicename')";
			$result =  mysqli_query($link,$insSql);

			if (mysqli_error($link)) {
				echo "<script>alert(\"".mysqli_error($link)."\");location.href='message.php'</script>";
			}

			if (mysqli_insert_id($link)>0) {
				echo "<script>alert('Register successfully, please login！');location.href='login.php'</script>";
			}else{
				echo "<script>alert('Registration failed, please try again！');history.back()</script>";
			}
			break;
		default: 
			echo "<script>alert('Parameter error, please go back to home page and retry！');location.href='index.php'</script>";
		break;
	}


	// Close the database 
	mysqli_close($link);