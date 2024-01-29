<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Change Password</title>
<meta name="keywords" content=""/>
<meta name="description" content=""/>	
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
<link href="css/bootstrap.css" rel='stylesheet' type='text/css' />
<link href="css/style.css" rel='stylesheet' type='text/css' />
</head>
	
<body>
<?php 
	include('head.php'); 
	//If you are not logged in, jump to the login page
    if(!isset($_SESSION['users'])){
		echo "<script>alert('Please login first！')</script>";
        header('Location:login.php');
        exit;
    }
?>
	<div id="main" style="background-color: #f9f9f9;">
		
		<h1 class="regtit">Change Password</h1>
		
		<div class="userform">
			<form class="form-horizontal" role="form" action="dologin.php?a=edit" method="post">
			  <div class="form-group">
			    <label for="username" class="col-sm-4 control-label">username</label>
			    <div class="col-sm-7 text-left">
			    	<span class="form-control"><?php echo $_SESSION['users']; ?></span>
			    </div>
			  </div>

			  <div class="form-group">
			    <label for="oldpwd" class="col-sm-4 control-label">Old password</label>
			    <div class="col-sm-7">
			      <input type="password" class="form-control" name="oldpwd" required>
			    </div>
			  </div>

			  <div class="form-group">
			    <label for="password" class="col-sm-4 control-label">New password</label>
			    <div class="col-sm-7">
			      <input type="password" class="form-control" name="password" required>
			    </div>
			  </div>

			  <div class="form-group">
			    <label for="repassword" class="col-sm-4 control-label">Verify password</label>
			    <div class="col-sm-7">
			      <input type="password" class="form-control" name="repassword" required>
			    </div>
			  </div>

			  <div class="form-group">
			    <div class="col-sm-offset-4 col-sm-7 text-left">
			      <button type="reset" class="btn btn-danger">Reset</button>
			      <button type="submit" class="btn btn-primary">Submit</button>
			    </div>
			  </div>
				
			</form>
		</div>
	</div>
	
<?php include('foot.php'); ?>
</body>
</html>