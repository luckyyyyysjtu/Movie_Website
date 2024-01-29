<!DOCTYPE html>
<html>
<head>
<meta chrset="UTF-8">
<title>Review Add</title>
 <meta content="width=device-width, initial-scale=1.0" name="viewport">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="css/bootstrap.css">
<link rel="stylesheet" href="css/style.css">
<style>
	.login{display: block;padding: 0px 15px;height: 25px;line-height: 25px;border-right: 1px solid #eee}
</style>
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
<section id="main" class="services">	
	<h1 class="regtit">Review Add</h1>
	<div class="col-xs-4 col-sm-6 col-lg-12">
		<div class="addform">
		<form class="form-horizontal" role="form" action="action.php?a=add" method="post">
		  <div class="form-group">
		    <label for="firstname" class="col-sm-3 control-label">Movie Name: </label>
		    <div class="col-sm-9">
		      <input class="form-control" name="title" required>
		    </div>
	    	</div>
		  <div class="form-group">
		    <label for="firstname" class="col-sm-3 control-label">Review Content: </label>
		    <div class="col-sm-9">
		      <textarea class="form-control" name="content" rows="5" required></textarea>
		    </div>
		  </div>
		  <div class="form-group">
		    <div class="col-sm-offset-2 col-sm-10 text-right">
		      <button type="reset" class="btn btn-danger">Reset</button>
		      <button type="submit" class="btn btn-primary">Submit</button>
		    </div>
		  </div>
		</form>
		</div>
	</div>		
</section>
<!-- footer -->
<?php include('foot.php'); ?>
<!-- footer -->
</body>
</html>