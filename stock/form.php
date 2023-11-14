<?php
//ライブラリ読み込み
require "../inc/option.inc";
require incPATH."/library.inc";
require incPATH."/define.inc";
require incPATH."/pref.inc";

$conTitle = '在庫車お問合わせ';
$pageTitle = '';
$metaDescription = '';

$_SESSION['form']['store'] = !empty($_FORM['store']) ? $_FORM['store'] : $_SESSION['form']['store'];
$_SESSION['form']['cname'] = !empty($_FORM['cname']) ? $_FORM['cname'] : $_SESSION['form']['cname'];

$file = basename($_SERVER['PHP_SELF']);
$formCat = 'zaiko';
$e_flag = 0;
$errorMsg = '';

if (!empty($_POST)) {
  // 全角文字を半角に変換
  $_FORM['tel'] = mb_convert_kana($_FORM['tel'],'rn');
  $_FORM['email'] = mb_convert_kana($_FORM['email'],'rn');

  if((!empty($_POST['confirm']) || !empty($_POST['zaiko']))){
    $_SESSION['form'] = $_FORM;
  }

  // PHPでチェック
  $msg = "";
  $e_flag = 0;
  if($_SESSION['form']['name'] == ""){
    $msg .= "お名前が入力されていません。<br>";
    $e_flag = 1;
  }
  if($_SESSION['form']['tel'] == ""){
    $msg .= "電話番号が入力されていません。<br>";
    $e_flag = 1;
  }
  if($_SESSION['form']['email']==""){
    $e_flag = 1;
    $msg .= "メールアドレスが入力されていません。<br>";
  }else{
    // メールアドレスが入力されていれば妥当性のチェック
    if(! checkEmail($_SESSION['form']['email'])){
      $e_flag = 1;
      $msg .= "メールアドレスが不正です。<br>";
    }
  }
  if($e_flag == 1){ $errormsg = '<p style="color:#F00">'.$msg."</p>"; }
}

if(!empty($_POST['submit']) and $e_flag == 0){
    // 設定読み込み
    include incPATH."/mail.inc";

    $s = $_SESSION['form']['store'];
    // メッセージ生成
    $msgData = <<< EOD
お問合わせ車種：{$_SESSION['form']['cname']}
お名前：{$_SESSION['form']['name']}
電話番号：{$_SESSION['form']['tel']}
メールアドレス：{$_SESSION['form']['email']}
その他お問合わせ：
{$_SESSION['form']['comment']}
EOD;

  $msg = str_replace("%msgData%", $msgData, $_mailData[$formCat]['msg']);
  $msg = str_replace("%name%", $_SESSION['form']['name'], $msg);

  mb_language("uni");
  mb_internal_encoding("UTF-8");

  $msg = html_entity_decode($msg);
	$title = html_entity_decode($_mailData[$formCat]['subject']);
	$from = mb_encode_mimeheader(html_entity_decode($_mailData[$formCat]['fromName'])).' <'.$_mailData[$formCat]['fromEmail'].'>';

  mb_send_mail($_SESSION['form']['email'], $title, $msg, "From:".$from."\n", "-f{$_mailData[$formCat]['fromEmail']}");

  $toMail = strpos($_SESSION['form']['email'], '@tratto-brain.jp') !== FALSE ? $_SESSION['form']['email'] : $_mailData[$formCat]['toEmail'];
	$title = html_entity_decode($_mailData[$formCat]['subjectC']);
	mb_send_mail($toMail, $title, $msg, "From:".$from."\n", "-f{$_mailData[$formCat]['fromEmail']}");

  $fp = fopen("log.csv","a");
	fwrite($fp, '"'.date("Y-m-d H:i").'","'.mb_convert_encoding($msgData,"SJIS").'"');
	fwrite($fp,"\n");
	fclose($fp);

  $_SESSION['form'] = array();

  header("Location: thanks.php");
}

//レイアウト読み込み
require (incPATH."/layout.inc");
?><!DOCTYPE html>
<html lang="ja">
<head>
<?= $meta ?>
<?= $css ?>
<link rel="stylesheet" href="../js/jquery.validate.css">
<?= $js ?>
<script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.0/dist/jquery.validate.min.js"></script>
<script src="../js/messages_ja.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    $("#myForm").validate({
        rules: { email2: { equalTo: "#email" } },
        messages: { email2: { equalTo: "メールアドレスが一致しません" } }
    });
});
</script>
</head>

<body id="contact">
<?= $gtm ?>

<div id="wrapper">
<?= $header ?>

<div id="contents">
  <div class="pagettl">
    <img src="../img/stock_h2.jpg" class="ttl_bg">
    <h2><?= $conTitle ?><span class="en">Stock</span></h2>
  </div><!-- .pagettl -->

  <ul id="pan" class="inner">
    <li><a href="/">ホーム</a></li>
    <li><?= $conTitle ?></li>
  </ul>

<article class="inner">
<section>
<form method="post" action="<?= $file ?>" id="myForm">
<input type="hidden" name="store" value="<?= $_SESSION['form']['store'] ?>" />
<input type="hidden" name="cname" value="<?= $_SESSION['form']['cname'] ?>" />
<?php
if ($e_flag==0 && (!empty($_POST['confirm']) || !empty($_POST['zaiko']))) {
//確認画面
?>
<p class="txt center">下記の確認内容でよろしければ、送信ボタンを押して下さい。</p>
<table class="tbl tbl1">
  <tr>
    <th>お問合わせ車種</th>
    <td><?= $_SESSION['form']['cname'] ?></td>
  </tr>
  <tr>
    <th>お名前</th>
    <td><?= $_SESSION['form']['name'] ?></td>
  </tr>
  <tr>
    <th>電話番号</th>
    <td><?= $_SESSION['form']['tel'] ?></td>
  </tr>
  <tr>
    <th>メールアドレス</th>
    <td><?= $_SESSION['form']['email'] ?></td>
  </tr>
  <tr>
    <th>その他お問合わせ</th>
    <td><?= nl2br($_SESSION['form']['comment']) ?></td>
  </tr>
</table>
<div class="submit clearfix">
  <input type="submit" name="submit" class="btn_submit fright" value="送信" />
  <input type="button" value="戻る" class="btn_submit fleft" onClick="location.href='<?= $file ?>'" />
</div>

<?php
}else{
//入力フォーム
?>

<div class="border_box">
  <p class="txt center">お問合わせは以下のフォームの空欄をうめて、「確認」ボタンを押して下さい。<br>
  必須項目は、記入漏れがありますとエラーになりますのでご注意下さい。</p>
</div><!--.txt_box-->


<?= $errormsg ?>
<table class="tbl tbl1">
  <tr>
    <th>お問合わせ車種</th>
    <td><?= $_SESSION['form']['cname'] ?></td>
  </tr>
  <tr>
    <th>お名前<span class="red">*</span></th>
    <td><input type="text" name="name" value="<?= $_SESSION['form']['name'] ?>" class="required"></td>
  </tr>
  <tr>
    <th>電話番号<span class="red">*</span></th>
    <td><input type="text" name="tel" value="<?= $_SESSION['form']['tel'] ?>" class="s required"></td>
  </tr>
  <tr>
    <th>メールアドレス<span class="red">*</span></th>
    <td><input type="text" name="email" id="email" value="<?= $_SESSION['form']['email'] ?>" class="email required"></td>
  </tr>
  <tr>
    <th>その他お問合わせ</th>
    <td><textarea name="comment" rows="5" class="m"><?= $_SESSION['form']['comment'] ?></textarea></td>
  </tr>
</table>

<div class="submit center">
  <input type="submit" name="confirm" class="btn_submit" value="確認画面へ" />
</div>
<?php } ?>
</form>
<?php
/*
<div id="ssl_seal" class="clearfix">
  <p>※このページの入力フォームはSSLで暗号化されており、<br class="sp_none">お客様にご入力いただいた情報は、安全な形で送信されます。</p>
  <table width="135" border="0" cellpadding="2" cellspacing="0" title="このマークは、SSL/TLSで通信を保護している証です。">
  <tr>
  <td width="135" align="center" valign="top">
  <!-- GeoTrust QuickSSL [tm] Smart  Icon tag. Do not edit. --> <SCRIPT LANGUAGE="JavaScript"  TYPE="text/javascript" SRC="//smarticon.geotrust.com/si.js"></SCRIPT>
  <!-- end  GeoTrust Smart Icon tag -->
  <a href="https://www.geotrust.co.jp/ssl-certificate/" target="_blank"  style="color:#000000; text-decoration:none; font:bold 12px 'ＭＳ ゴシック',sans-serif; letter-spacing:.5px; text-align:center; margin:0px; padding:0px;">SSLとは？</a></td>
  </tr>
  </table>
</div><!--/#ssl_seal-->
*/
?>
</section>

</article><!-- /.inner -->
</div><!--#contents-->
<?= $footer ?>
</div><!--#wrapper-->

</body>
</html>
