<!DOCTYPE HTML>

<?php
require_once('funcs.php');
ini_set('display_errors', "On");
$message ="";
if($_SERVER['REQUEST_METHOD'] === 'POST') {

    // (1) 更新するデータを用意
    //1. POSTデータ取得
    $id = $_POST['id'];
    $tanka = $_POST['tanka'];
    try {
        // (2) データベースに接続
        $pdo = db_conn();
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
                $message ="ErrorMessage:" . $error[2];
                exit("ErrorMessage:" . $error[2]);
            }
        }
    } catch (PDOException $e) {

        $message = 'DBConnectError' . $e->getMessage();
        exit('DBConnectError' . $e->getMessage());
    } finally{
        // (6) データベースの接続解除
        $pdo = null;
        $message = "見積もりを作成しました。";
    }
}

//1.  DB接続します
try {
    //Password:MAMP='root',XAMPP=''
    $pdo = db_conn();
    //２．データ取得SQL作成
    $stmt = $pdo->prepare("SELECT * FROM kadai_an_table");
    $status = $stmt->execute();

} catch (PDOException $e) {
    exit('DBConnectError' . $e->getMessage());
}

//３．データ表示
$view = "";
$anken = "";
$tanto = "";
if ($status == false) {
    //execute（SQL実行時にエラーがある場合）
    $error = $stmt->errorInfo();
    exit("ErrorQuery:" . $error[2]);
} else {
    $sum = 0;
    $count = 0;
    while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $anken = h($result['anken']);
        $tanto = h($result['tanto']);
        $sum += h($result['suuryou']) * h($result['tanka']);
        $view .= "<tr>";
        $view .= "<td>"."#" . $count++ . "</td>";
        $view .= '<td><input type="text" class="form-control item" readonly="readonly" name="koumoku[]" value="' . h($result['koumoku']) . '"></td>';
        $view .= '<td><input type="text" class="form-control quantity" readonly="readonly" name="koumoku[]" data-format="number" value="' . h($result['suuryou']) . '"></td>';
        $view .= '<td><input type="text" class="form-control price" name="tanka[]" data-format="number" value="' . h($result['tanka']) . '"><input type="hidden" name="id[]" value="' . h($result['id']) . '"></td>';
        $view .= '<td><input type="text" class="form-control sum_detail" readonly="readonly" name="koumoku[]" data-format="number" value="' .  h($result['suuryou']) * h($result['tanka']) . '"></td>';
        $view .= "</tr>";

    }

}
?>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>見積もり入力フォーム・サンプル</title>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="./css/style.css">
        <style type="text/css">
            body {
                color: #ffa500;
            }
            .form-horizontal table caption {
                background-color: #ffa500;
            }
            .btn-primary{
            background-color: #ffa500;
            border-color: #ffa500;
            }
        </style>
    </head>

    <?php
    include('./header.php');
    ?>
    <body>
        <div class="wrapper">
            <div class="content-wrapper">
                <section class="content">
                    <form action="update.php" method="post" class="form-horizontal" class="estimate">

                        <h2 class="form-header">見積依頼が来ました</h2>

                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="client-name" class="col-sm-2 control-label">案件名</label>
                                    <div class="col-sm-10">
                                    <input type="text" name="anken" class="form-control" id="client-name" readonly="readonly" value="<?=h($anken);?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="client-name" class="col-sm-2 control-label">担当</label>
                                    <div class="col-sm-10">
                                    <input type="text" name="tanto" class="form-control"  readonly="readonly" value="<?=h($tanto);?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4"><div class="col-sm-10 h3 text-danger"><?= $message; ?></div></div>
                        </div>

                        <table class="table">

                            <caption>明細</caption>

                            <col width="40" />
                            <col width="240" />
                            <col width="180" />
                            <col width="" />
                            <col width="" />

                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>名称</th>
                                    <th>数量</th>
                                    <th>単価</th>
                                    <th>計</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th>
                                        <div class="input-group">
                                            <span class="input-group-addon" id="basic-addon1">税抜</span>
                                            <input type="text" class="form-control" id="total-ex-tax" readonly="readonly" data-format="number">
                                        </div>
                                    </th>
                                    <th>
                                        <div class="input-group">
                                            <span class="input-group-addon" id="basic-addon1">税込</span>
                                            <input type="text" class="form-control" id="total" readonly="readonly" data-format="number">
                                        </div>
                                    </th>
                                </tr>
                                <tr>
                                    <th colspan="2">
                                        <button class="btn btn-primary">更新</button>
                                    </th>
                                    <th colspan="4">
                                        <div class="message"><?= $message; ?></div>
                                    </th>
                                </tr>
                            </tfoot>
                            <tbody>
                            <?= $view ?>
                            </tbody>
                        </table>
                    </form>
                </section>
            </div>
        </div>
        <script src="//code.jquery.com/jquery-3.1.1.min.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="jquery.number-format.js"></script>
        <script>
            $(function () {
                // 明細1行の計算
                var calc_row = function (i) {
                    if ($('tbody tr').length === 0) {
                        return 0;
                    }

                    var quantity = $('tbody tr').eq(i).find('.quantity');
                    var q = (quantity.attr('data-value')) ? quantity.attr('data-value') : 0;

                    var price = $('tbody tr').eq(i).find('.price');
                    var p = (price.attr('data-value')) ? price.attr('data-value') : 0;

                    var sum = q * p;
                    return sum;
                };

                var calc_tax = function (num) {
                    return Math.floor(1.1 * num);
                };
                var calc_rows = function () {
                    var i = 0;
                    var sum = 0;
                    var sum_t = 0;
                    while (i < $('tbody tr').length) {
                        var d = 0;
                        var dt = 0;
                        d = calc_row(i);
                        dt = calc_tax(calc_row(i));
                        d = calc_row(i);
                        dt = calc_tax(calc_row(i));
                        sum_t += dt;
                        sum += d;
                        $('tbody tr').eq(i).find('.sum_detail')
                                .val(number_format(d));
                        i++;
                    }
                    $('#total-ex-tax').val(number_format(sum));
                    $('#total').val(number_format( sum_t));
                };


                var number_format = function (num) {
                    var val = num.toString().replace(',', '');
                    var formattedVal = val.replace(/(\d)(?=(?:\d{3}){2,}(?:\.|$))|(\d)(\d{3}(?:\.\d*)?$)/g
                            , '$1$2,$3');
                    return formattedVal;
                };

                // カンマ区切
                $('[data-format=number]')
                        .numberformat()
                        .on('change', function () {
                            calc_rows();
                });
                calc_rows();
            });
        </script>


    </body>
</html>