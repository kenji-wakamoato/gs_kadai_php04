<?php

session_start();
//ログイン済みの場合
if (!isset($_SESSION['EMAIL'])) {
  header("Location:login.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

  <title>Document</title>
</head>

<?php
include('./header.php');
?>

<body>
  <div id="login">
    <h3 class="text-center text-white pt-5">ユーザーページ</h3>

    <div class="container">
      <div id="login-row" class="row justify-content-center align-items-center">
        <div id="login-column" class="col-md-6">
          <div id="login-box" class="col-md-12">
            <h3 class="text-center text-info">清水建設様</h3>
          </div>
        </div>
    </div>
  </div>
</body>
</html>