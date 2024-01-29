<!DOCTYPE HTML>
<html>
<head>
<title>Recommends List</title>
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
<div class="container">
  <div class="row">
    <div class="col-12 col-md-12 text-center">
      <h1> Recommends List </h1>
    </div>
  </div>
  <div class="row">
    <div class="col-12 col-md-12">
    <?php 
     // Paging settings
      $page = !empty($_GET['p']) ? $_GET['p'] : 1;//Current page number
      $pageSize = 3;//Number of displayed items per page
      $totalRow = 0; //How many pieces of data are there in total
      $totalPage = 0;//How many pages in total
      $where = '';
      $url = '';

      //Query self labeled labels
      $tagArr = array();
      $tagRes = mysqli_query($conn, "SELECT tag FROM tags WHERE username='{$_SESSION['users']}'");
      isError($conn);
      while($tagRow = mysqli_fetch_assoc($tagRes)){
        $tagArr = array_merge($tagArr, explode(' ', $tagRow['tag']));
      }
      if (count($tagArr) > 0) {
        $tagArr = array_unique($tagArr);
        
        //Query review_id that match tag data
        $idFromTag = array();
        foreach ($tagArr as $k => $v) {
          $tagRes = mysqli_query($conn, "SELECT review_id FROM tags WHERE tag like '%$v%'");
          if (mysqli_num_rows($tagRes) > 0) {
            while($tagIds = mysqli_fetch_assoc($tagRes)){
              $idFromTag[] = $tagIds['review_id'];
            }
          }
        }

        if (count($idFromTag) > 0) {
          $ids = array_unique($idFromTag); //review_id duplicate removal
          $ids = implode(',', $ids);
          $where = " where review_id in($ids)";
          // echo $where;
        }
      } else {
        // Random acquisition bar
        $tagRes = mysqli_query($conn, "SELECT review_id FROM reviews ORDER BY RAND() LIMIT 10");
        isError($conn);
        $ids = array();
        while($tagIds = mysqli_fetch_assoc($tagRes)){
          $ids[] = $tagIds['review_id'];
        }
        $ids = implode(',', $ids);
        $where = " where review_id in($ids)";
      }


      $sql = "SELECT * FROM reviews " . $where . " ORDER BY likeNum DESC";
      $result = mysqli_query($conn, $sql);
      isError($conn);

      $totalRow = mysqli_num_rows($result);

      //Calculate the total number of pages, CEIL rounding function
      $totalPage = ceil($totalRow / $pageSize); 

      //If the transmitted page is greater than the maximum page number, the maximum page number will be taken
      if ($page > $totalPage) {
          $page = $totalPage;
      }

      //If the transmitted page is less than 1, take page 1
      if ($page < 1) {
          $page = 1;
      }

      $sql = "SELECT * FROM reviews " . $where . " ORDER BY likeNum DESC LIMIT " . ($page - 1) * $pageSize . "," . $pageSize;
      $result = mysqli_query($conn, $sql);
      isError($conn);
      while($row = mysqli_fetch_assoc($result)){
     ?>
      <blockquote>
        <strong><?php echo $row['title'];?></strong>
        <p><?php echo $row['content'];?></p>
        <p class="buttons">
          <?php 
            $tagResult = mysqli_query($conn, "SELECT * FROM tags WHERE review_id={$row['review_id']}");
            isError($conn);
            while($tagRow = mysqli_fetch_assoc($tagResult)){
              echo '<span class="tag">'.$tagRow['tag'].'</span>';
            }
          ?>
        </p>
        <p>
        <?php 
          if(isset($_SESSION['users'])){
            //Check if it has been collected
            $collectSql = "SELECT collect_id FROM collects WHERE username='{$_SESSION['users']}' AND review_id={$row['review_id']}";
            $collectResult = mysqli_query($conn, $collectSql);
            isError($conn);
            if($collectResult && mysqli_num_rows($collectResult)>0){
              echo '<a href="action.php?url=recommends&a=collectCancel&review_id='.$row['review_id'].'">
                    <img src="./images/collected.png" alt=""></a>';
            } else {
              echo '<a href="action.php?url=recommends&a=collect&review_id='.$row['review_id'].'">
                    <img src="./images/collect.png" alt=""></a>';    
            }

            //Check if it has been liked
            $likeSql = "SELECT like_id FROM likes WHERE username='{$_SESSION['users']}' AND review_id={$row['review_id']}";
            $likeResult = mysqli_query($conn, $likeSql);
            isError($conn);
            if($likeResult && mysqli_num_rows($likeResult)>0){
              echo '<a href="action.php?url=recommends&a=likeCancel&review_id='.$row['review_id'].'">
                    <img src="./images/liked.png" alt=""></a>';
            }else{
              echo '<a href="action.php?url=recommends&a=like&review_id='.$row['review_id'].'">
                    <img src="./images/like.png" alt=""></a>';
            }
          } else {
              echo '<a href="action.php?url=recommends&a=collect&review_id='.$row['review_id'].'">
                    <img src="./images/collect.png" alt=""></a>
                    <a href="action.php?url=recommends&a=like&review_id='.$row['review_id'].'">
                    <img src="./images/like.png" alt=""></a>';
          } 
        ?>
          <button class="btn btn-primary btn-xs" onclick="addTag(<?php echo $row['review_id'];?>)">Add Tag</button>
          </p>
      </blockquote>
      <?php } ?>
      <?php
        include('./page.class.php'); //Introducing Paging Classes
        //If the total number of records exceeds the number displayed on each page, pagination will be displayed
        if ($totalRow > $pageSize) {
            if ($url) {
                $rurl = "?p={page}&" . $url;
            } else {
                $rurl = "?p={page}";
            }

            $page = new page($totalRow, $pageSize, $page, $rurl, 2);
            echo $page->myde_write();
        }
      ?>
    </div>
  </div>
</div>
<!-- footer -->
<?php include('foot.php'); ?>
<!-- footer -->
<script>
function addTag(review_id) {
  var islogin = '<?php echo $islogin ?>';
  console.log(islogin);
   if (islogin) {
    var tag = prompt("Please enter tag content");
    if (tag!=null && tag!=""){
      window.location.href='action.php?url=recommends&a=tag&review_id='+review_id+'&tag='+tag;
    }
  }else{
    alert('Please login first！');
    window.location.href='login.php';
  }
}
</script>
</body>
</html>