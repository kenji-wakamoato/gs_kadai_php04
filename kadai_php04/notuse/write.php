<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>

</body>

</html>

<h1>書き込みしました。</h1>
<h2> data.csvを確認してください</h2>

<ul>
  <li><a href="read.php">確認する</a></li>
  <li><a href="post.php">戻る</a></li>
</ul>
<?php
ini_set('display_errors', "On");
/**
 * Undocumented function
 *
 * @param $data_list array
 *
 * @return void
 */
//1. POSTデータ取得
$anken = $_POST['anken'];
$tanto = $_POST['tanto'];
$koumoku = $_POST['koumoku'];
$suuryou = $_POST['suuryou'];
$tanka = $_POST['tanka'];

//2. DB接続します
try {
  //ID:'root', Password: 'root'
  $pdo = new PDO('mysql:dbname=kadai_db01;charset=utf8;host=localhost', 'root', 'root');
} catch (PDOException $e) {
  exit('DBConnectError:' . $e->getMessage());
}
//３．データ登録SQL作成

// 1. SQL文を用意
$stmt = $pdo->prepare("INSERT INTO kadai_an_table(id, anken, tanto, koumoku,suuryou,tanka, date)VALUES(NULL, :anken, :tanto, :koumoku, :suuryou, :tanka, sysdate())");

// 案件と担当者が必須の処理
// if (isset($_POST['anken']) && isset($_POST['tanto'])) {

for ($i = 0; $i < count($_POST['koumoku']); $i++) {
  //          バインド変数をループ処理
  $stmt->bindValue(':anken', $anken, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
  $stmt->bindValue(':tanto', $tanto, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
  $stmt->bindValue(':koumoku', $koumoku[$i], PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
  $stmt->bindValue(':suuryou', $suuryou[$i], PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
  $stmt->bindValue(':tanka', $tanka[$i], PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)

  //  3. 実行
  $status = $stmt->execute();

  //４．データ登録処理後
  if ($status == false) {
    //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
    $error = $stmt->errorInfo();
    exit("ErrorMessage:" . $error[2]);
  }
}

?>
<!-- smallchat -->
<script src="https://embed.small.chat/TF0KTC929C02GT13JBV0.js" async></script>