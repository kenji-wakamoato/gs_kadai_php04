<?php
ini_set('display_errors', "On");

// (1) 更新するデータを用意
//1. POSTデータ取得
$id = $_POST['id'];
$tanka = $_POST['tanka'];

// (2) データベースに接続
$pdo = new PDO('mysql:dbname=kadai_db01;charset=utf8;host=localhost', 'root', 'root');
// (3) SQL作成
$stmt = $pdo->prepare("UPDATE kadai_an_table SET tanka = :tanka WHERE id = :id");
// (4) 登録するデータをセット

for ($i = 0; $i < count($_POST['tanka']); $i++) {
    // バインド変数をループ処理
    $stmt->bindParam(':id', $id[$i], PDO::PARAM_INT); //Integer（数値の場合 PDO::PARAM_INT)
    $stmt->bindParam(':tanka', $tanka[$i], PDO::PARAM_INT); //Integer（数値の場合 PDO::PARAM_INT)
    //  3. 実行
    $status = $stmt->execute();

    //４．データ登録処理後
    if ($status == false) {
        //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
        $error = $stmt->errorInfo();
        exit("ErrorMessage:" . $error[2]);
    }
}

// (6) データベースの接続解除
$pdo = null;

echo "更新しました";
