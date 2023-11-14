<?php
//ライブラリ読み込み
//require "../os-check/inc/option.inc";
require_once ('../inc/option.inc');
require incPATH."/library.inc";
require incPATH."/define.inc";
require incPATH."/pagination.class.php";

$conTitle = '在庫車情報';
$pageTitle = '';
$metaDescription = '西日本で最大級のレクサス専門店株式会社OSの最新在庫車情報からお気に入りの一台を見つけてください！レクサスの厳選した中古車を多数在庫しております。西日本でレクサス中古車を買うなら地域最大級株式会社OSのにお越しください。';

$pan = '車を探す';

if (!empty($_FORM['submit'])) {
  $_SESSION['search'] = $_FORM;
} else {
  unset($_SESSION['search']);
}

//レイアウト読み込み
$_cssList2[] = '/os-check/css/stock.css';
require incPATH."/layout.inc";
require incPATH."/search.inc";

$_order[1] = 'price DESC';
$_order[2] = 'price ASC';
$order = 'price ASC'; $current = array();
if (!empty($_order[$_FORM['order']])) {
  $order = $_order[$_FORM['order']];
  $current[$_FORM['order']] = ' class="current"';
}

// try {
//   // データベースの接続
//   $dbh = db_construct();

//   $page = !empty($_FORM['page']) ? $_FORM['page'] : 1;
//   $limit = 12; //1ページあたりの表示件数

//   $column = "*";
//   $sql = "SELECT SQL_CALC_FOUND_ROWS {$column} FROM `car`
//           WHERE car_name != '' AND price > 0 ";
//   sql_where_str_m("maker",$_FORM['maker'],$sql,1);
//   sql_where_str_name("car_name",$_FORM['cname'],$sql,1);
//   sql_where_str_name("type",$_FORM['type'],$sql,1);
//   //$sql .= " AND car_name LIKE '%{$_FORM['cname']}' ";
//   if (!empty($_FORM['price'])) {
//     $a = explode('-', $_FORM['price']);
//     $tmp = $a[0] * 10000;
//     $tmp2 = $a[1] * 10000;
//     sql_where_chku("price",$tmp,$sql,1);
//     sql_where_chkd("price",$tmp2,$sql,1);
//   }
//   //sql_where_str_m("store",$_FORM['store'],$sql,1);

//   $pointer = ($page-1)*$limit;
//   $sql .= " ORDER BY {$order}";
//   $sql .= " LIMIT ".$pointer.",".$limit;

//   //echo $sql;
//   unset($res);
//   $res = $dbh->query($sql);
//   $rows = $res->fetchAll(PDO::FETCH_ASSOC);

//   unset($res);
//   $res = $dbh->query("SELECT FOUND_ROWS()");
//   $row_max = $res->fetchColumn();
//   $maxpage = get_maxpage($row_max,$limit);

//   $data = '';
//   foreach ( $rows as $row ) {
//     $no = $row['no'];
//     $sno = $row['stock_number'];
//     $color = !empty($row['color']) ? $row['color'] : '';
//     $maker = $_maker[$row['maker']];
//     $cname = $row['car_name'];
//     $price = $row['price'];
//     $status = $row['status'];
//     $frame_number = substr($row['frame_number'],-3);

//     $filename = '/img/noimage.jpg';
//     $a = explode(';', $row['images']);
//     if (!empty($a[0])) $filename = str_replace('http:','',$a[0]);//https警告回避$
//     $image = '<img src="'.$filename.'" alt="'.$cname.'" class="sp_img" />';

//     $detal_btn = '';
//     $detal_link = '';

//     $link = urlencode('管理番号'.$sno.'　'.$maker.'　'.$cname.'　'.$row['grade'].'　'.$color);

//     if (strpos($status,'売却') !== false) {
//       $price = '<span class="red">SOLD OUT</span>';
//       $detal_link = '<figure class="image">'.$image.'</figure>';
//     } else {
//       $count = strlen($price);
//       if($count <= 8) {
//         $price_min = $price / 10000;
//       }
//       $price = '<span class="red">'.$price_min.'</span>万円';
//       $detal_link = <<< EOD
//       <figure class="image"><a href="{$sno}.php">
//         {$image}
//       </a></figure>
// EOD;
//       $detal_btn = <<< EOD
//       <li class="contactBtn"><a href="form.php?store={$s}&cname={$link}">在庫お問い合わせ</a></li>
//       <li class="detailBtn"><a href="{$sno}.php">詳細はこちら</a></li>
// EOD;
//     }
//     $nensiki = add_gengo_jcm($row['year'], "en");

//     $kensa = $row['inspection'];

//     //$rec = $row['isFeatured'] == 1 ? '<li class="rec">オススメ</li>' : '';
//     $rdata = '';
//     $rdata = <<< EOD
//       <tr><th colspan="2" class="price">{$price}</th></tr>
//       <tr><th>年式</th><td>{$nensiki}</td></tr>
//       <tr><th>車検</th><td>{$kensa}</td></tr>
//       <tr><th>色</th><td>{$color}</td></tr>
//       <tr><th>走行距離</th><td>{$row['distance']}</td></tr>
//       <tr><th>車台番号</th><td>{$frame_number}</td></tr>
// EOD;

//   $data .= <<< EOD
// <li>
//   <h3 class="name">{$cname} <span>{$row['grade']}</span></h3>
//   <div class="clearfix">
//     {$detal_link}
//     <div class="rBox">
//       <!-- <p class="store store{$s}">在庫店：{$_store[$s]}</p> -->
//       <ul class="tagList clearfix">
//         {$rec}
//       </ul>
//       <table class="data clear">
// {$rdata}
//       </table>
//       <ul class="carContact">
//         <li class="tel-zaiko"><p class="source"><a href="tel:072-289-8070"><span class="txt">お問い合わせ</span><span class="tel">072-289-8070</span></a></p></li>
//         {$detal_btn}
//       </ul>
//     </div>
//   </div>
// </li>\n
// EOD;
//   }
// } catch (PDOException $e) {
//   echo "失敗しました。" . $e->getMessage();
//   die();
// }
?><!DOCTYPE html>
<html lang="ja">
<head>
<?php echo $meta; ?>
<?php echo $css; ?>
<?php echo $js; ?>
<?php echo $searchjs; ?>
</head>

<body id="stock">
<?= $gtm; ?>

<div id="wrapper">
<?php echo $header; ?>

<div id="contents">
  <div class="pagettl">
    <img src="/os-check/img/stock_h2.jpg" class="ttl_bg">
    <h2><?php echo $conTitle; ?><span class="en">Stock</span></h2>
  </div><!-- .pagettl -->

  <ul id="pan" class="inner">
    <li><a href="/">ホーム</a></li>
    <li><?php echo $conTitle; ?></li>
  </ul>

<article class="inner">

<div class="salecoupon_bnr">
<a href="https://docs.google.com/forms/d/e/1FAIpQLSfQi-5F4BN3ApGXM5qZ_cr7fdVV2xZ1HQzJlc8OXGrqmTDqlA/viewform" target="_brank">
  <img src="/os-check/img/top/salecoupon_bnr.jpg?20230901"></div>
</a>

<section>
<!-- <?php echo $tokuten; ?> -->
<?php echo $searchBox; ?>
<?php
$qs = $_FORM;
unset($qs['page']);
$qs2 = $qs; //表示順
$qs = !empty($qs) ? http_build_query($qs) : '';
unset($qs2['order']);
$qs2 = !empty($qs2) ? http_build_query($qs2) : '';

$p = new pagination;
$p->items($row_max);
$p->limit($limit);
if(!empty($qs)){
  $p->target("index.php?".$qs);
}

$p->currentPage($page);
$p->show();
?>
<div class="clearfix">
<p class="fleft">検索結果：<em class="big red"><?= $row_max; ?></em>件</p>
<p class="order">
  [ 価格： <a href="<?php echo $_SERVER['PHP_SELF']."?".$qs2."&order=1"; ?>"<?php echo $current[1] ?>>高</a>
   ｜  <a href="<?php echo $_SERVER['PHP_SELF']."?".$qs2."&order=2"; ?>"<?php echo $current[2] ?>>安</a>  ]</p>
</div>
<?php
  if(!empty($data)) {
  echo '<ul id="zaikoList" class="flex">'.$data.'</ul>';
    $p->show();
  } else {
  echo '<p class="center">お探しの条件の車の登録がありませんでした。</p>';
  }
?>
</section>

</article><!-- /.inner -->
</div><!--#contents-->
<?php echo $footer; ?>
</div><!--#wrapper-->

</body>
</html>
