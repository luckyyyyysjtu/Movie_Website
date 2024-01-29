<!DOCTYPE HTML>
<html>
<head>
<title>Movie Achievement</title>
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
<link href="css/bootstrap.css" rel='stylesheet' type='text/css' />
<link href="css/style.css" rel='stylesheet' type='text/css' />
</head>
<body>
<?php 
  include('head.php'); 
?>
<div class="container">
  <div class="row">
    <div class="col-12 col-md-12 text-center">
      <h1> Movie Achievement </h1>
    </div>
  </div>
  <div class="row">
    <div class="col-12 col-md-6">
        <h3>Task List</h3>
        <strong>Task 1. Website registration.</strong>
        <p>Number of completed: 
          <strong><?php echo mysqli_num_rows(mysqli_query($conn, "SELECT id FROM users")); ?></strong>
        </p>

        <strong>Task 2. Post 5 movie reviews.</strong>
        <p>Number of completed: 
          <strong><?php echo mysqli_num_rows(mysqli_query($conn, "SELECT COUNT(`review_id`) AS num FROM `reviews` GROUP BY `username` HAVING num >= 5")); ?></strong>
        </p>

        <strong>Task 3. Like 5 movie reviews.</strong>
        <p>Number of completed: 
          <strong><?php echo mysqli_num_rows(mysqli_query($conn, "SELECT COUNT(`review_id`) AS num FROM `likes` GROUP BY `username` HAVING num >= 5")); ?></strong>
        </p>

        <strong>Task 4. Collect 5 movie reviews.</strong>
        <p>Number of completed: 
          <strong><?php echo mysqli_num_rows(mysqli_query($conn, "SELECT COUNT(`review_id`) AS num FROM `collects` GROUP BY `username` HAVING num >= 5")); ?></strong>
        </p>

        <strong>Task 5. Tag 5 movie reviews.</strong>
        <p>Number of completed: 
          <strong><?php echo mysqli_num_rows(mysqli_query($conn, "SELECT COUNT(DISTINCT `review_id`) AS num FROM `tags` GROUP BY `username` HAVING num >= 5")); ?></strong>
        </p>
    </div>
    <div class="col-12 col-md-6">
        <h3>Personal Completion Status:</h3>

        <strong>Task 1. Website registration.</strong>
        <p>
        <?php 
          if(isset($_SESSION['users'])){ 
            echo "Completed.";
          } else {
            echo "View after login.";
          }
        ?>
        </p>

        <strong>Task 2. Post 5 movie reviews.</strong>
        <p>
        <?php 
          if(isset($_SESSION['users'])){ 
            $reviewNum = mysqli_num_rows(mysqli_query($conn, "SELECT `review_id` FROM `reviews` WHERE `username`='{$_SESSION['users']}'"));
            echo $reviewNum >=5 ? 'Completed' : 'Incomplete';
          } else {
            echo "View after login.";
          }
        ?>
        </p>

        <strong>Task 3. Like 5 movie reviews.</strong>
        <p>
        <?php 
          if(isset($_SESSION['users'])){ 
            $likeNum = mysqli_num_rows(mysqli_query($conn, "SELECT DISTINCT `review_id` FROM `likes` WHERE `username`='{$_SESSION['users']}'"));
            echo $likeNum >=5 ? 'Completed' : 'Incomplete';
          } else {
            echo "View after login.";
          }
        ?>
        </p>

        <strong>Task 4. Collect 5 movie reviews.</strong>
        <p>
        <?php 
          if(isset($_SESSION['users'])){ 
            $collectNum = mysqli_num_rows(mysqli_query($conn, "SELECT DISTINCT `review_id` FROM `collects` WHERE `username`='{$_SESSION['users']}'"));
            echo $collectNum >=5 ? 'Completed' : 'Incomplete';
          } else {
            echo "View after login.";
          }
        ?>
        </p>

        <strong>Task 5. Tag 5 movie reviews.</strong>
        <p>
        <?php 
          if(isset($_SESSION['users'])){ 
            $reviewNum = mysqli_num_rows(mysqli_query($conn, "SELECT DISTINCT `review_id` FROM `tags` WHERE `username`='{$_SESSION['users']}'"));
            echo $reviewNum >=5 ? 'Completed' : 'Incomplete';
          } else {
            echo "View after login.";
          }
        ?>
        </p>
    </div>
  </div>
</div>
<!-- footer -->
<?php include('foot.php'); ?>
<!-- footer -->
</body>
</html>