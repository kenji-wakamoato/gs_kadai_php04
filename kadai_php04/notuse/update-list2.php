<style type="text/css">
    th,
    td {
        border: solid 1px;
        /* 枠線指定 */
    }

    table {
        border-collapse: collapse;
        /* セルの線を重ねる */
    }
</style>

<?php
ini_set('display_errors', "On");

function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES); //scriptタグを無効化
}
//1.  DB接続します
try {
    //Password:MAMP='root',XAMPP=''
    $pdo = new PDO('mysql:dbname=kadai_db01;charset=utf8;host=localhost', 'root', 'root');
} catch (PDOException $e) {
    exit('DBConnectError' . $e->getMessage());
}

//２．データ取得SQL作成
$stmt = $pdo->prepare("SELECT * FROM kadai_an_table");
$status = $stmt->execute();

//３．データ表示
$view = "";
if ($status == false) {
    //execute（SQL実行時にエラーがある場合）
    $error = $stmt->errorInfo();
    exit("ErrorQuery:" . $error[2]);
} else {
    $sum = 0;
    $view .= '<form action="update.php" method="post" enctype="multipart/form-data">';
    $view .= "<table><tr><th>案件名</th><th>担当者</th><th>項目</th><th>数量</th><th>単価</th><th>金額</th>";

    while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $view .= "<tr>";
        $view .= "<td>" . h($result['anken']) . "</td>";
        $view .= "<td>" . h($result['tanto']) . "</td>";
        $view .= "<td>" . h($result['koumoku']) . "</td>";
        $view .= "<td>" . h($result['suuryou']) . "</td>";
        $view .= '<td><input type="text" name="tanka[]" value="' . h($result['tanka']) . '">';
        $view .= '<input type="hidden" name="id[]" value="' . h($result['id']) . '"></td>';
        $view .= "<td>" . h($result['suuryou']) * h($result['tanka']) . "</td>";
        $view .= "</tr>";
        $sum += h($result['suuryou']) * h($result['tanka']);
    }

    $view .= "<tr>";
    $view .= "<td colspan='5'>合計</td>";
    $view .= "<td>{$sum}</td>";
    $view .= "</tr>";
    $view .= "</table>";
    $view .= '<input type="submit" name="send" value="送信">';
    $view .= "</from>";
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>フリーアンケート表示</title>
    <link rel="stylesheet" href="css/range.css">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        div {
            padding: 10px;
            font-size: 16px;
        }
    </style>
</head>

<body id="main">
    <!-- Head[Start] -->
    <header>
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="index.php">データ登録</a>
                </div>
            </div>
        </nav>
    </header>
    <!-- Head[End] -->

    <!-- Main[Start] -->
    <div>
        <div class="container jumbotron"><?= $view ?></div>
    </div>
    <!-- Main[End] -->

</body>

</html>