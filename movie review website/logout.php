<?php
header("Content-type: text/html; charset=utf-8");
//Clear the values in session
@session_start();//Open session	
unset($_SESSION['users']);
unset($_SESSION['nicename']);
echo "<script>location.href='index.php'</script>";