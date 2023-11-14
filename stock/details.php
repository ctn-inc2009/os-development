<?php
// ライブラリ読込み
require "../inc/option.inc";
require incPATH."/library.inc";
require incPATH."/define.inc";

$sno = !empty($_GET['sno']) ? $_GET['sno'] : 0;
try {
  // データベースの接続
  $dbh = db_construct();

  $sql = "SELECT * FROM `car`
          WHERE price > 0 AND stock_number = '{$sno}'";
  $stmt = $dbh->prepare($sql);
  $stmt->bindValue(1, $no, PDO::PARAM_STR);
  $stmt->execute();
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
  if (!empty($rows)) {
    $row = $rows[0];
    $no = $row['no'];
    $cname = $row['car_name'];
  } else {
    header("HTTP/1.1 410 Gone");
    header("Location:index.php");
  }
} catch (PDOException $e) {
  echo "失敗しました。" . $e->getMessage();
  die();
}

$metaDescription = "";

$conTitle = '在庫車情報｜西日本最大級のレクサス専門店株式会社OS';
$pageTitle = "{$cname}｜{$conTitle}｜{$siteTitle}";
$metaDescription = "西日本で最大級のレクサス専門店株式会社OSの最新在庫車情報からお気に入りの一台を見つけてください！レクサスの厳選した中古車を多数在庫しております。西日本でレクサス中古車を買うなら地域最大級株式会社OSのにお越しください。";

//レイアウト読み込み
$_cssList2[] = '/css/stock.css';
require incPATH."/layout.inc";
?><!DOCTYPE html>
<html lang="ja">
<head>
<?= $meta; ?>
<link href="../js/slick.css" rel="stylesheet">
<?= $css; ?>
<?= $js; ?>
</head>

<body id="stock">
<?= $gtm; ?>

<div id="wrapper">
<?= $header; ?>

<div id="contents">
  <div class="pagettl">
    <img src="../img/stock_h2.jpg" class="ttl_bg">
    <h2>在庫車情報<span class="en">Stock</span></h2>
  </div><!-- .pagettl -->

  <ul id="pan" class="inner">
    <li><a href="/">ホーム</a></li>
    <li><a href="./">車を探す</a></li>
    <li><?= $cname; ?></li>
  </ul>

<article class="inner">

<div class="salecoupon_bnr">
<a href="https://docs.google.com/forms/d/e/1FAIpQLSfQi-5F4BN3ApGXM5qZ_cr7fdVV2xZ1HQzJlc8OXGrqmTDqlA/viewform" target="_brank">
  <img src="/img/top/salecoupon_bnr.jpg?20220218"></div>
</a>
  
<section>
<div id="zaikoDetail">
<?/*= $tokuten; */?>
<div id="data"></div>

<div class="reasonBox">
  <h2>OSが選ばれる理由</h2>
  <ul class="flex j-between">

    <li><a href="/reason/#reason01" class="flex j-between">
      <h3>西日本最大級の在庫量</h3>
      <p>レクサスの特選中古車を主に取り扱う西日本最大のレクサス中古車専門店です。<br>レクサス特選中古車は150台以上を展示しております。</p>
      <figure><img src="/img/stock/reason01.jpg"></figure>
    </a></li>

    <li><a href="/reason/#reason02" class="flex j-between">
      <h3>高品質レクサスが驚きの販売価格</h3>
      <p>弊社では全車修復歴なしのレクサス車しか販売しておりません。<br>販売価格設定は他社価格のリサーチを重ねたうえで地域最安値の販売価格を設定しております。</p>
      <figure><img src="/img/stock/reason02.jpg"></figure>
    </a></li>

    <li><a href="/reason/#reason03" class="flex j-between">
      <h3>レクサス専門店としての使命</h3>
      <p>TOYOTAブランド最上級のレクサスのみを取扱う専門店として、<br>商品知識はもちろんのことディーラー出身整備士が1台1台のお車を確かな技術で仕上げております。</p>
      <figure><img src="/img/stock/reason03.jpg"></figure>
    </a></li>

    <li><a href="/reason/#reason04" class="flex j-between">
      <h3>保証もトラブルも安心</h3>
      <p>プロフェッショナルな専門スタッフが、各車の状態に関しては専門知識で対応いたしますので、<br>万が一の故障や修理時に余分なコストをかけずに、修理も短期間で完了させることが出来ます。</p>
      <figure><img src="/img/stock/reason04.jpg"></figure>
    </a></li>

    <li><a href="/reason/#reason05" class="flex j-between">
      <h3>メーター保証</h3>
      <p>レクサス専門店OSはメーターを改ざんした車両は販売しません。<br>OSでは、厳しい基準をクリアした車のみを仕入れ全ての車両の品質情報を公開することで、どなたでも安心してクルマ探しができる環境を整えています。</p>
      <figure><img src="/img/stock/reason05.jpg"></figure>
    </a></li>

    <li><a href="/reason/#reason06" class="flex j-between">
      <h3>第三者機関の査定・鑑定</h3>
      <p>入庫した全ての在庫車輌に対し第三者機関であるAIS査定士がチェックを行い、<br>併せて外部査定機関のAISに鑑定を依頼しております。</p>
      <figure><img src="/img/stock/reason06.png"></figure>
    </a></li>

  </ul>
</div>

<?php
//他の車リスト
unset($a);
$a[] = array('title' => "{$cname}", 'where' => " AND maker = '{$row['maker']}' AND car_name = '{$cname}'");
//$a[] = array('title' => "{$row['type']}", 'where' => " AND type = '{$row['type']}'");

foreach( $a as $a2 ){
  $column = '*';
  $sql = "SELECT {$column} FROM car
          WHERE no <> '{$no}' {$a2['where']}
          ORDER BY RAND() LIMIT 4";
  unset($res);
  $res = $dbh->query($sql);
  $rows = $res->fetchAll(PDO::FETCH_ASSOC);
  if (!empty($rows)) {
    if(!empty($a2['title'])) {
      echo '<div class="otherBox sec_txt5">';
      echo '<h4 class="">他の['.$a2['title'].']ラインアップ</h4>';
    // } else {
    //   echo '<h4 class="conttl1"><span class="en">LINEUP</span><br>その他のラインアップ</h4>';
    // }

      echo '<ul class="otherList flex">';
      foreach( $rows as $row3 ){
        $alt = $_maker[$row3['maker']].' '.$row3['car_name'];
        $a = explode(';', $row3['images']);
        $filename = !empty($a[0]) ? str_replace('http:','',$a[0]) : "/img/noimage.jpg";
        $image = '<figure><img src="'.$filename.'" alt="'.$alt.'" /></figure>';
        $price2 = $row3['price'];
        $count = strlen($price2);
        if($count <= 8) {
          $price2_min = $price2 / 10000;
        }
        $price2 = ($price2 < 0) ? 'SOLD OUT' : $price2_min.'万円';
        $soukou = $row3['distance'];
        $nensiki = $row3['year'];
        $kensa = $row3['inspection'];
        echo <<< EOD
      <li><a href="{$row3['stock_number']}.php">
        {$image}
        <table>
          <tr><th>年式</th><td>{$nensiki}</td></tr>
          <tr><th>走行距離</th><td>{$soukou}</td></tr>
          <tr><th>検査期限</th><td>{$kensa}</td></tr>
          <tr><td colspan="2" class="price">価格:<span>{$price2}</span></td></tr>
        </table>
        <p class="detailBtn arrow">詳細はこちら</p></a>
      </li>\n
EOD;
      }
      echo "  </ul></div>\n";
    }//同車種のみ表示
  }
}
?>
</div><!--/#zaikoDetail-->
</section>

</article><!-- /.inner -->
</div><!--#contents-->

<?= $footer; ?>
</div><!--#wrapper-->

<script type="text/javascript" src="../js/slick.min.js"></script>
<script type="text/javascript">

$(function(){

  var sno = "<?= $sno; ?>";
  $.ajax({
    type: "POST",
    dataType: "json",
    url: "/stock/details.json.php",
    data: { sno: sno}
  }).done(function(data) {
    $('#data').html(data);
    var slider = "#sliderInner ul"; // スライダー
    var thumbnailItem = "#bx-pager ul li"; // サムネイル画像アイテム

    // サムネイル画像アイテムに data-index でindex番号を付与
    $(thumbnailItem).each(function(){
     var index = $(thumbnailItem).index(this);
     $(this).attr("data-index",index);
    });

    // スライダー初期化後、カレントのサムネイル画像にクラス「thumbnail-current」を付ける
    // 「slickスライダー作成」の前にこの記述は書いてください。
    $(slider).on('init',function(slick){
     var index = $(".slide-item.slick-slide.slick-current").attr("data-slick-index");
     $(thumbnailItem+'[data-index="'+index+'"]').addClass("thumbnail-current");
    });

    //slickスライダー初期化
    $('#sliderInner ul').slick({
      autoplay: true,
      autoplaySpeed:5000,
      speed:1000,
      arrows: false,
      fade: true,
      infinite: false, //これはつけましょう。
      appendArrows: $('#arrow')
    });
    //サムネイル画像アイテムをクリックしたときにスライダー切り替え
    $(thumbnailItem).on('click',function(){
      var index = $(this).attr("data-index");
      $(slider).slick("slickGoTo",index,false);
    });

    //サムネイル画像のカレントを切り替え
    $(slider).on('beforeChange',function(event,slick, currentSlide,nextSlide){
      $(thumbnailItem).each(function(){
        $(this).removeClass("thumbnail-current");
      });
      $(thumbnailItem+'[data-index="'+nextSlide+'"]').addClass("thumbnail-current");
    });
  });

});
</script>
</body>
</html>