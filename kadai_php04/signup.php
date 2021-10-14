<?php

ini_set('display_errors', "On");
require_once('funcs.php');

session_start();
$error_meeage = '';


if($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_SESSION['EMAIL'])) {
        redirect('index.php');
        exit;
    }
} else if($_SERVER['REQUEST_METHOD'] === 'POST') {
    //データベースへ接続、テーブルがない場合は作成
    try {
    $pdo = db_conn();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("create table if not exists userDeta(
        id int not null auto_increment primary key,
        email varchar(255) unique,
        password varchar(255) ,
        created timestamp not null default current_timestamp
        )");
    } catch (Exception $e) {
    echo $e->getMessage() . PHP_EOL;
    }
    //POSTのValidate。
    if (!$email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    echo '入力された値が不正です。';
    return false;
    }
    //パスワードの正規表現
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    //登録処理
    try {
        $stmt = $pdo->prepare("insert into userDeta(email, password) value(?, ?)");
        $stmt->execute([$email, $password]);
        session_regenerate_id(true); //session_idを新しく生成し、置き換える
        $_SESSION['EMAIL'] = $email;
        redirect('index.php');
    } catch (\Exception $e) {
        $error_meeage = '登録済みのメールアドレスです。';
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
        <title>SignUp</title>
    </head>
    <body>

        <div id="login">
            <h3 class="text-center text-white pt-5">登録フォーム</h3>
            <div class="container">
                <div id="login-row" class="row justify-content-center align-items-center">
                    <div id="login-column" class="col-md-6">
                        <div id="login-box" class="col-md-12">
                            <form id="login-form" class="form" action="signup.php" method="post">
                                <h3 class="text-center text-info">ユーザー登録</h3>
                                <div class="form-group">
                                    <label for="email" class="text-info">メールアドレス:</label><br>
                                    <input type="text" name="email" id="email" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="password" class="text-info">パスワード:</label><br>
                                    <input type="text" name="password" id="password" class="form-control">
                                </div>
                                <div class="form-group">
                                    <input type="submit" name="submit" class="btn btn-info btn-md" value="登録する">
                                </div>
                                <div class="form-group">
                                    <div class="error_message"><?= $error_meeage ?></div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
