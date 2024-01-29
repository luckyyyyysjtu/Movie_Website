<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Sign in</title>
<meta name="keywords" content=""/>
<meta name="description" content=""/>	
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
<link href="css/bootstrap.css" rel='stylesheet' type='text/css' />
<link href="css/style.css" rel='stylesheet' type='text/css' />
</head>
	
<body>
<?php 
	include('head.php'); 
	// If the visitor has logged in, jump to the home page
    if(isset($_SESSION['users'])){
        echo "<script>alert('You are logged in, no need to log in again！');location.href='./index.php';</script>";
        exit;
    }
?>
<section id="main" class="services">
	<h1 class="regtit">Sign in</h1>
	<div class="userform">
		<form class="form-horizontal" role="form" action="dologin.php?a=login&u=<?php if (isset($_GET['u'])) { echo $_GET['u'];} ?>" method="post">
			  <div class="form-group">
			    <label for="username" class="col-sm-2 control-label">username</label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control" name="username">
			    </div>
			  </div>
			  <div class="form-group">
			    <label for="password" class="col-sm-2 control-label">password</label>
			    <div class="col-sm-10">
			      <input type="password" class="form-control" name="password">
			    </div>
			  </div>
			  <div class="form-group">
			    <div class="col-sm-offset-2 col-sm-10 text-left">
			      <button type="reset" class="btn btn-danger">Reset</button>
			      <button type="submit" class="btn btn-primary">Submit</button>
			    </div>
			  </div>
		</form>
	</div>
</section>

<?php include('foot.php'); ?>
</body>
</html>