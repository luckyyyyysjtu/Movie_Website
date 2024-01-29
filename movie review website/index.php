<!DOCTYPE HTML>
<html>
<head>
<title>Movie Review Website</title>
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
      <h1> Movie Review List </h1>
      <form action="">
        <input type="search" name="keyword" value="<?php if(!empty($_GET['keyword'])) echo $_GET['keyword']; ?>" placeholder="Please enter search keywords...">
        <input type="submit" value="Search" class="btn btn-primary btn-sm">
        <a href="reviewAdd.php" class="btn btn-default btn-sm">Add Review</a>
      </form>
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
      if (!empty($_GET['keyword'])) {
        //Split keywords
        $keywords = explode(' ', $_GET['keyword']);
        $idFromTag = array();
        $idFromreview = array();
        foreach ($keywords as $k => $v) {
          //Query review_id that match tag data
          $tagRes = mysqli_query($conn, "SELECT review_id FROM tags WHERE tag like '%$v%'");
          if (mysqli_num_rows($tagRes) > 0) {
            while($tagIds = mysqli_fetch_assoc($tagRes)){
              $idFromTag[] = $tagIds['review_id'];
            }
          }

          //Query review_id that match reviews data
          $reviewRes = mysqli_query($conn, "SELECT review_id FROM reviews WHERE title like '%$v%' or content like '%$v%'");
          if (mysqli_num_rows($reviewRes) > 0) {
            while($reviewIds = mysqli_fetch_assoc($reviewRes)){
              $idFromreview[] = $reviewIds['review_id'];
            }
          }
        }

        $ids = array_merge($idFromTag, $idFromreview); //review_id merge
        if (count($ids) > 0) {
          $ids = array_unique($ids); //review_id duplicate removal
          $ids = implode(',', $ids);
          $where = " where review_id in($ids)";
          $url = 'keyword=' . $_GET['keyword'];
          // echo $where;
        }
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
              echo '<a href="action.php?a=collectCancel&review_id='.$row['review_id'].'">
                    <img src="./images/collected.png" alt=""></a>';
            } else {
              echo '<a href="action.php?a=collect&review_id='.$row['review_id'].'">
                    <img src="./images/collect.png" alt=""></a>';    
            }

            //Check if it has been liked
            $likeSql = "SELECT like_id FROM likes WHERE username='{$_SESSION['users']}' AND review_id={$row['review_id']}";
            $likeResult = mysqli_query($conn, $likeSql);
            isError($conn);
            if($likeResult && mysqli_num_rows($likeResult)>0){
              echo '<a href="action.php?a=likeCancel&review_id='.$row['review_id'].'">
                    <img src="./images/liked.png" alt=""></a>';
            }else{
              echo '<a href="action.php?a=like&review_id='.$row['review_id'].'">
                    <img src="./images/like.png" alt=""></a>';
            }
          } else {
              echo '<a href="action.php?a=collect&review_id='.$row['review_id'].'">
                    <img src="./images/collect.png" alt=""></a>
                    <a href="action.php?a=like&review_id='.$row['review_id'].'">
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
      window.location.href='action.php?a=tag&review_id='+review_id+'&tag='+tag;
    }
  }else{
    alert('Please login first！');
    window.location.href='login.php';
  }
}
</script>
</body>
</html>