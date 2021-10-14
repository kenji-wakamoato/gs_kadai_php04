<?php
require_once('funcs.php');

session_start();
if($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_SESSION['EMAIL'])) {
        redirect('index.php');
        exit;
    }
} else if($_SERVER['REQUEST_METHOD'] === 'POST') {
    // POST リクエストの場合の処理
    //POSTのvalidate
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $error_meeage = '入力された値が不正です。';
    }
    //DB内でPOSTされたメールアドレスを検索
    try {
        $pdo = db_conn();
        $stmt = $pdo->prepare('select * from userDeta where email = ?');
        $stmt->execute([$_POST['email']]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (\Exception $e) {
        echo $e->getMessage() . PHP_EOL;
    }
    $error_meeage = "";
    //emailがDB内に存在しているか確認
    if (!isset($row['email'])) {
        $error_meeage = 'メールアドレス又はパスワードが間違っています。';
    }
    //パスワード確認後sessionにメールアドレスを渡す
    if (password_verify($_POST['password'], $row['password'])) {
        session_regenerate_id(true); //session_idを新しく生成し、置き換える
        $_SESSION['EMAIL'] = $row['email'];
        redirect('index.php');
        exit();
    } else {
        $error_meeage = 'メールアドレス又はパスワードが間違っています。';
    }

}
?>
<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>Login</title>
</head>
<body>
    <div id="login">
        <h3 class="text-center text-white pt-5">Login form</h3>
        <div class="container">
            <div id="login-row" class="row justify-content-center align-items-center">
                <div id="login-column" class="col-md-6">
                    <div id="login-box" class="col-md-12">
                        <form id="login-form" class="form" action="login.php" method="post">
                            <h3 class="text-center text-info">ログイン</h3>
                            <div class="form-group">
                                <label for="email" class="text-info">メールアドレス:</label><br>
                                <input type="text" name="email" id="email" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="password" class="text-info">パスワード:</label><br>
                                <input type="text" name="password" id="password" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="remember-me" class="text-info"><span>IDを記憶しておく。</span> <span><input id="remember-me" name="remember-me" type="checkbox"></span></label><br>
                                <input type="submit" name="submit" class="btn btn-info btn-md" value="ログイン">
                            </div>
                            <div class="form-group">
                                <div class="error_message"><?= $error_meeage ?></div>
                            <div id="register-link" class="text-right">
                                <a href="signup.php" class="text-info">ユーザー登録</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
 </body>
</html>
