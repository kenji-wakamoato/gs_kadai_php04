<?php
ini_set('display_errors', "On");
require_once('funcs.php');
$message = "";
if($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_SESSION['EMAIL'])) {
        redirect('index.php');
        exit;
    }
} else if($_SERVER['REQUEST_METHOD'] === 'POST') {

    //1. POSTデータ取得
    $anken = $_POST['anken'];
    $tanto = $_POST['tanto'];
    $koumoku = $_POST['koumoku'];
    $suuryou = $_POST['suuryou'];
    $tanka = $_POST['tanka'];

    //2. DB接続します
    try {
        $pdo = db_conn();
    } catch (PDOException $e) {
    exit('DBConnectError:' . $e->getMessage());
    }
    //３．データ登録SQL作成

    // 1. SQL文を用意
    $stmt = $pdo->prepare("INSERT INTO kadai_an_table(id, anken, tanto, koumoku,suuryou,tanka, date)VALUES(NULL, :anken, :tanto, :koumoku, :suuryou, :tanka, sysdate())");

    // 案件と担当者が必須の処理
    // if (isset($_POST['anken']) && isset($_POST['tanto'])) {
    try {
        for ($i = 0; $i < count($_POST['koumoku']); $i++) {
            if ($anken == "") continue;
            if ($koumoku[$i] == "") continue;
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
    }catch (PDOException $e) {
        $message = $e->getMessage();
        exit('DBConnectError:' . $e->getMessage());
    }finally{
        $message = "登録が完了しました。";
    }
}
?>

<!DOCTYPE HTML>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>見積もり入力フォーム・サンプル</title>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="./css/style.css">
    </head>

<?php
include('./header.php');
?>
    <body>
        <div class="wrapper">
            <div class="content-wrapper">
                <section class="content">
                    <form action="new.php" method="post" class="form-horizontal" class="estimate">

                        <h2 class="form-header">見積作成フォーム</h2>

                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="client-name" class="col-sm-2 control-label">案件名</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="anken" class="form-control" id="client-name" placeholder="案件名">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="client-name" class="col-sm-2 control-label">担当</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="tanto" class="form-control" placeholder="担当">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="client-name" class="col-sm-2 control-label">消費税率</label>
                                    <div class="col-sm-5">
                                        <div class="input-group">
                                            <input type="text" name="tax_rate" class="form-control" data-format="number" readonly="readonly" value="10">
                                            <span class="input-group-addon">
                                                <span class="fa fa-percent"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="client-name" class="col-sm-2 control-label">計算方法</label>
                                    <div class="col-sm-10">
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="tax_calc_type" value="1" >
                                                明細ごとに税金を算出する
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="tax_calc_type" value="2"checked="checked">
                                                総合計に税金を算出する
                                            </label>
                                        </div>
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
                                    <th>施工項目</th>
                                    <th>数量</th>
                                    <th>単価</th>
                                    <th>計</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>&nbsp;</th>
                                    <th>
                                        <button type="button" class="btn btn-default" id="add_field">フィールドの追加</button>
                                    </th>
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
                                        <button class="btn btn-primary">登録</button>
                                    </th>
                                    <th colspan="4">
                                        <div class="message"><?= $message; ?></div>
                                    </th>
                                </tr>
                            </tfoot>
                            <tbody>
                            </tbody>
                        </table>
                    </form>
                </section>
            </div>
        </div>
        <script src="//code.jquery.com/jquery-3.1.1.min.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="jquery.number-format.js"></script>
            <!--small chat  -->
        <script src="https://embed.small.chat/TF0KTC929C02GT13JBV0.js" async></script>
        <script>
            $(function () {

                /*
                 * 明細を追加する
                 */
                var add_row = function () {

                    // 番号
                    var num = $('tbody > tr').length;
                    if (num >= count_max_forms) {
                        return;
                    }

                    // 単位
                    var unit_arr = ['個', '式'];
                    // 明細各行のHTML構造
                    var html_detail = $('<tr>');
                    html_detail.appendTo('tbody');
                    // 列設定
                    var tds = [];
                    tds[0] = $('<td>').text('#' + (num + 1).toString());
                    // 施工項目
                    tds[1] = $('<td>');
                    var input_text_itemname = $('<input>')
                            .prop({
                                'type': 'text'
                                , 'name': 'koumoku[]'
                            })
                            .addClass('form-control item')
                            .attr({
                                'placeholder': '施工項目'
                            })
                            .appendTo(tds[1]);
                    // 数量
                    tds[2] = $('<td>');
                    var input_group_quantity = $('<div>')
                            .addClass('input-group')
                            .appendTo(tds[2]);
                    var input_text_quantity = $('<input>')
                            .prop({
                                'type': 'text'
                                , 'name': 'suuryou[]'
                            })
                            .addClass('form-control quantity')
                            .attr({
                                'data-format': 'number'
                            })
                            .appendTo(input_group_quantity);
                    var input_hidden_unit = $('<input>')
                            .prop({
                                'type': 'hidden'
                                , 'name': 'unit[]'
                            })
                            .addClass('unit')
                            .appendTo(input_group_quantity);
                    var div_input_group_btn = $('<div>')
                            .addClass('input-group-btn')
                            .appendTo(input_group_quantity);
                    var button_toggle = $('<button>')
                            .addClass("btn btn-default dropdown-toggle")
                            .attr({
                                'data-toggle': 'dropdown'
                                , 'aria-haspopup': 'true'
                                , 'aria-expanded': 'false'
                            })
                            .append($('<span>').text('個'))
                            .append('&nbsp;')
                            .appendTo(div_input_group_btn);
                    var span_caret = $('<span>')
                            .addClass('caret')
                            .appendTo(button_toggle);
                    var ul_dropdown_menu = $('<ul>')
                            .addClass('dropdown-menu')
                            .appendTo(div_input_group_btn);
                    var li_unit = [];
                    var li_unit_a = [];
                    $.each(unit_arr, function (i, obj) {
                        li_unit[i] = $('<li>')
                                .appendTo(ul_dropdown_menu);
                        li_unit_a[i] = $('<a>')
                                .text(obj)
                                .appendTo(li_unit[i])
                                .on('click', function () {
                                    input_hidden_unit.val(obj);
                                    button_toggle.find('span:first').text(obj);
                                });
                        ;
                        if (i < unit_arr.length - 1) {
                            $('<li>').attr('role', 'separator')
                                    .addClass('divider')
                                    .appendTo(li_unit[i]);
                        }
                    });


                    // 単価
                    tds[3] = $('<td>');
                    var input_group_price = $('<div>')
                            .addClass('input-group')
                            .appendTo(tds[3]);
                    var span_input_group_addon = $('<span>')
                            .addClass('input-group-addon')
                            .append($('<span>').addClass('fa fa-yen'))
                            .appendTo(input_group_price);
                    var input_text_price = $('<input>')
                            .prop({
                                'type': 'text'
                                , 'name': 'tanka[]'
                            })
                            .attr({
                                'data-format': 'number'
                            })
                            .addClass('form-control price')
                            .appendTo(input_group_price);
                    // 小計
                    tds[4] = $('<td>');
                    var input_group_sum_detail = $('<div>')
                            .addClass('input-group')
                            .append(span_input_group_addon.clone())
                            .appendTo(tds[4]);
                    var input_text_sum_detail = $('<input>')
                            .prop({
                                'type': 'text'
                                , 'name': 'sum_detail[]'
                            })
                            .attr({
                                'data-format': 'number'
                                , 'readonly': true
                            })
                            .addClass('form-control sum_detail')
                            .appendTo(input_group_sum_detail);

                    // td > tr 追加
                    html_detail.append(tds);
                };
                /*
                 * 複数の明細を追加する
                 */
                var add_rows = function (count) {
                    var num = 0;
                    while (num < count && num < count_max_forms) {
                        add_row();
                        num = $('tbody tr').length;
                    }
                    calc_rows();
                };
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
                    return Math.floor(tax_rate * num);
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
                        if (tax_calc_type.toString() === '1') {
                            d = calc_row(i);
                            dt = calc_tax(calc_row(i));
                        } else if (tax_calc_type.toString() === '2') {
                            d = calc_row(i);
                            dt = calc_tax(calc_row(i));
                        }
                        sum_t += dt;
                        sum += d;
                        $('tbody tr').eq(i).find('.sum_detail')
                                .val(number_format((tax_calc_type.toString() === '1') ? dt : d));
                        i++;
                    }
                    $('#total-ex-tax').val(number_format(sum));
                    $('#total').val(number_format((tax_calc_type === '1') ? calc_tax(sum) : sum_t));
                };

                var number_format = function (num) {
                    var val = num.toString().replace(',', '');
                    var formattedVal = val.replace(/(\d)(?=(?:\d{3}){2,}(?:\.|$))|(\d)(\d{3}(?:\.\d*)?$)/g
                            , '$1$2,$3');
                    return formattedVal;
                };

                // 税率
                var tax_rate = $('[name=tax_rate]').val() / 100 + 1;
                // ロード時に表示する明細フォームの行数
                var count_forms = 4;
                // 明細フォールの最大数
                var count_max_forms = 20;
                // 税金計算方法
                var tax_calc_type = 2;
                // 初期表示
                add_rows(count_forms);
                // フィールド追加
                $('#add_field').on('click', function () {
                    add_row();
                });

                // カンマ区切
                $('[data-format=number]')
                        .numberformat()
                        .on('change', function () {
                            calc_rows();
                        });

                $('[name=tax_calc_type]').on('click', function () {
                    // 税金計算方法
                    tax_calc_type = $(this).val();
                    calc_rows();
                });
            }); </script>
    </body>
</html>
