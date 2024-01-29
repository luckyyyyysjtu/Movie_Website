<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Register</title>
<meta name="keywords" content=""/>
<meta name="description" content=""/>	
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
<link href="css/bootstrap.css" rel='stylesheet' type='text/css' />
<link href="css/style.css" rel='stylesheet' type='text/css' />
</head>
	
<body>
<?php include('head.php'); ?>
<section id="main" class="services">
	<h1 class="regtit">Register</h1>
	<div class="userform">
		<form class="form-horizontal" role="form" action="dologin.php?a=register" method="post">
		  <div class="form-group">
		    <label for="username" class="col-sm-2 control-label">username</label>
		    <div class="col-sm-10">
		      <input type="text" class="form-control" name="username">
		    </div>
		  </div>
		  <div class="form-group">
		    <label for="nicename" class="col-sm-2 control-label">nicename</label>
		    <div class="col-sm-10">
		      <input type="text" class="form-control" name="nicename">
		    </div>
		  </div>
		  <div class="form-group">
		    <label for="password" class="col-sm-2 control-label">password</label>
		    <div class="col-sm-10">
		      <input type="password" class="form-control" name="password">
		    </div>
		  </div>
		  <div class="form-group">
		    <label for="repassword" class="col-sm-2 control-label">repassword</label>
		    <div class="col-sm-10">
		      <input type="password" class="form-control" name="repassword">
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