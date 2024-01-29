<?php
	session_start();
	include('dbconfig.php');
	// Connect to the database
	$link = @mysqli_connect(HOST,USER,PASS,DBNAME);
	if (!$link) {
		echo "<script>alert(\"'Failure of database connection:".mysqli_connect_error()."\");location.href='login.php'</script>";
		exit;
	}
	mysqli_set_charset($link,'utf8');
    
     // If session does not exist, jump to the login page
	if (!isset($_SESSION) || empty($_SESSION)) {
		echo "<script>alert('Please login first.');location.href='login.php'</script>";
        exit;
	}
	
	$username = $_SESSION['users']; //get username

	switch ($_GET['a']) {
		case 'add':// add review
			// Get the post parameter
			$title = mysqli_real_escape_string($link, $_POST['title']);
			$content = mysqli_real_escape_string($link, $_POST['content']);
			$addTime = date('Y-m-d H:i:s');

		    // Storage
		    $sql = "insert into reviews (username,title,content,addTime) values('$username','$title','$content','$addTime')";
			$result =  mysqli_query($link,$sql);
			if (mysqli_error($link)) {
				echo "<script>alert(\"".mysqli_error($link)."\");history.back()</script>";
				exit;
			}

			if (mysqli_insert_id($link)>0) {
				echo "<script>alert('Successful.');location.href='index.php'</script>";
			}else{
				echo "<script>alert('Failed, please try again.');history.back()</script>";
			}
		break;

		case 'like':// add like
			$review_id = $_GET['review_id'];
			$back = 'index'; //from page
			if (!empty($_GET['url'])) {
				$back = $_GET['url'];
			}

			//Likes Data Table Record Data
			mysqli_query($link,"insert into likes (review_id,username) values('$review_id','$username')");
			if (mysqli_error($link)) {
				echo "<script>alert(\"".mysqli_error($link)."\");location.href='".$back.".php'</script>";
				exit;
			}

			//update likeNum
			mysqli_query($link,"update reviews set likeNum=likeNum+1 where review_id=" . $review_id);
			if (mysqli_error($link)) {
				echo "<script>alert(\"".mysqli_error($link)."\");location.href='".$back.".php'</script>";
				exit;
			}

			echo "<script>alert('Like Successful.');location.href='".$back.".php'</script>";
		break;

		case 'likeCancel':// cancel like
			$review_id = $_GET['review_id'];
			$back = 'index'; //from page
			if (!empty($_GET['url'])) {
				$back = $_GET['url'];
			}

			//Likes Data Table Record Data
			mysqli_query($link,"delete from likes where username='$username' and review_id='$review_id'");
			if (mysqli_error($link)) {
				echo "<script>alert(\"".mysqli_error($link)."\");location.href='".$back.".php'</script>";
				exit;
			}

			//update likeNum
			mysqli_query($link,"update reviews set likeNum=likeNum-1 where review_id=" . $review_id);
			if (mysqli_error($link)) {
				echo "<script>alert(\"".mysqli_error($link)."\");location.href='".$back.".php'</script>";
				exit;
			}

			echo "<script>alert('Cancel Successful.');location.href='".$back.".php'</script>";
		break;

		case 'collect':// add collect
			//Collects Data Table Delete Record Data
			$review_id = $_GET['review_id'];
			$back = 'index'; //from page
			if (!empty($_GET['url'])) {
				$back = $_GET['url'];
			}

			mysqli_query($link,"insert into collects (review_id,username) values('$review_id','$username')");
			if (mysqli_error($link)) {
				echo "<script>alert(\"".mysqli_error($link)."\");location.href='".$back.".php'</script>";
				exit;
			}

			//update collectNum
			mysqli_query($link,"update reviews set collectNum=collectNum+1 where review_id=" . $review_id);
			if (mysqli_error($link)) {
				echo "<script>alert(\"".mysqli_error($link)."\");location.href='".$back.".php'</script>";
				exit;
			}

			echo "<script>alert('Collection Successful.');location.href='".$back.".php'</script>";
		break;

		case 'collectCancel':// cancel collect
			$review_id = $_GET['review_id'];
			$back = 'index'; //from page
			if (!empty($_GET['url'])) {
				$back = $_GET['url'];
			}

			//Collects Data Table Delete Record Data
			mysqli_query($link,"delete from collects where username='$username' and review_id='$review_id'");
			if (mysqli_error($link)) {
				echo "<script>alert(\"".mysqli_error($link)."\");location.href='".$back.".php'</script>";
				exit;
			}

			//update collectNum
			mysqli_query($link,"update reviews set collectNum=collectNum-1 where review_id=" . $review_id);
			if (mysqli_error($link)) {
				echo "<script>alert(\"".mysqli_error($link)."\");location.href='".$back.".php'</script>";
				exit;
			}

			echo "<script>alert('Cancel Successful.');location.href='".$back.".php'</script>";
		break;

		case 'tag':// add tag
			$review_id = $_GET['review_id'];
			$back = 'index'; //from page
			if (!empty($_GET['url'])) {
				$back = $_GET['url'];
			}

			$tag = mysqli_real_escape_string($link, trim($_GET['tag']));
			if ($tag == '') {
				echo "<script>alert('Tag cannot be empty.');location.href='".$back.".php'</script>";
				exit;
			}

			//Check if the tag already exists
			$result = mysqli_query($link,"select tag_id from tags where tag='$tag' and review_id='$review_id'");
			if($result && mysqli_num_rows($result)>0){
				echo "<script>alert('This tag already exists.');location.href='".$back.".php'</script>";
				exit;
			}

			//Tag Data Table Record Data
			mysqli_query($link,"insert into tags (review_id,username,tag) values('$review_id','$username','$tag')");
			if (mysqli_error($link)) {
				echo "<script>alert(\"".mysqli_error($link)."\");location.href='".$back.".php'</script>";
				exit;
			}

			//update tagNum
			mysqli_query($link,"update reviews set tagNum=tagNum+1 where review_id=" . $review_id);
			if (mysqli_error($link)) {
				echo "<script>alert(\"".mysqli_error($link)."\");location.href='".$back.".php'</script>";
				exit;
			}

			echo "<script>alert('Add Tag Successful.');location.href='".$back.".php'</script>";
		break;

		default: 
			echo "<script>alert('Parameter error, please go back to home page and retry.');location.href='index.php'</script>";
		break;
	}

// Close the database
mysqli_close($link);