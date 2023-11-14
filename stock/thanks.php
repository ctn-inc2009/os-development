<?php
//ライブラリ読み込み
require "../inc/option.inc";
require incPATH."/define.inc";

$conTitle = '在庫車お問合わせ';
$pageTitle = '';
$metaDescription = '西日本で最大級のレクサス専門店株式会社OSの最新在庫車情報からお気に入りの一台を見つけてください！レクサスの厳選した中古車を多数在庫しております。西日本でレクサス中古車を買うなら地域最大級株式会社OSのにお越しください。';

//レイアウト読み込み
require incPATH."/layout.inc";
?><!DOCTYPE html>
<html lang="ja">
<head>
<?php echo $meta; ?>
<?php echo $css; ?>
<?php echo $js; ?>
</head>

<body id="contact" class="thanks">
<?= $gtm; ?>

<div id="wrapper">
<?php echo $header; ?>

<div id="contents">
  <div class="pagettl">
    <img src="../img/stock_h2.jpg" class="ttl_bg">
    <h2><?php echo $conTitle; ?><span class="en">Stock</span></h2>
  </div><!-- .pagettl -->

  <ul id="pan" class="inner">
    <li><a href="/">ホーム</a></li>
    <li><?php echo $conTitle; ?></li>
    <li><?php echo $conTitle; ?>完了</li>
  </ul>

<article class="inner">
<section class="txtBox">
  <p class="center big">お問い合わせありがとうございました。<br>自動返信メールが送信されます。<br class="pc_none tb_none">内容をご確認下さい。</p>

  <p class="center">メールが届かない場合は以下の可能性がありますのでご確認下さい。</p>

  <ul class="disc">
    <li>メールアドレスの入力ミスの可能性（全角・半角の違い／大文字・小文字の違い）</li>
    <li>フリーメール(Yahoo・Hotmail・Goo)やメーラーの関係で「迷惑フォルダ」に移動されている可能性（念のためご確認をお願いいたします）</li>
  </ul>

  <p class="center">メールが届かない場合はお手数ですがお電話でのご確認をお願いいたします。</p>
  <div class="info bg_gy">
    <p><span class="name">株式会社OS</span><br>〒583-0011 大阪府藤井寺市沢田2丁目1ー31<br>
      TEL：<span class="tel">072-959-6025</span><br>
    営業時間 / 9:30～19:00</p>
  </div><!-- .info -->

  <p class="center btn2"><a href="/">トップページへ</a></p>

</section>
</article>

</div><!--#contents-->

<?php echo $footer; ?>
</div><!--#wrapper-->

</body>
</html>
