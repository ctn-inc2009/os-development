<?php
//ライブラリ読み込み
//require "../os-check/inc/option.inc";
require_once('../inc/option.inc');
require incPATH . "/library.inc";
require incPATH . "/define.inc";

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
require incPATH . "/layout.inc";
require incPATH . "/search.inc";

$_order[1] = 'price DESC';
$_order[2] = 'price ASC';
$order = 'price ASC';
$current = array();
if (!empty($_order[$_FORM['order']])) {
  $order = $_order[$_FORM['order']];
  $current[$_FORM['order']] = ' class="current"';
}

// 初期化
$itemsPerPage = 24;
$sortPrice = 3;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$sort = isset($_GET['order']) ? $sortPrice . '_'. $_GET['order'] : "3_0";
$cname = isset($_GET['cname']) ? str_replace(' ','', $_GET['cname']) : '';
$price = isset($_GET['price']) ? $_GET['price'] : '';
$priceRange = isset($_GET['price']) ? $_GET['price'] : '';
// 価格帯パラメータ取得の処理
$minPrice = '';
$maxPrice = '';
if (!empty($priceRange)) {
  $priceParts = explode('-', $priceRange);
  if (count($priceParts) == 2) {
    $minPrice = intval($priceParts[0]) * 10000;
    $maxPrice = intval($priceParts[1]) * 10000;
  } else {
    $minPrice = '';
    $maxPrice = '';
  }
} else {
  $minPrice = '';
  $maxPrice = '';
}
?>

<!--************************** htmlの領域 **************************-->
<!DOCTYPE html>
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
            <img src="/os-check/img/top/salecoupon_bnr.jpg?20230901">
        </div>
        </a>

        <section>
          <!-- <?php echo $tokuten; ?> -->
          <?php echo $searchBox; ?>
          <div class="pagination_wrap"></div>
          <div class="clearfix">
            <p class="fleft">検索結果：<em class="big red"></em>件</p>
            <?php
            $qs = $_FORM;
            unset($qs['page']);
            $qs2 = $qs; //表示順
            $qs = !empty($qs) ? http_build_query($qs) : '';
            unset($qs2['order']);
            $qs2 = !empty($qs2) ? http_build_query($qs2) : '';
            ?>
            <p class="order">
              [ 価格： <a href="<?php echo $_SERVER['PHP_SELF'] . "?" . $qs2 . "&order=1"; ?>" <?php echo $current[1] ?>>高</a>
              ｜ <a href="<?php echo $_SERVER['PHP_SELF'] . "?" . $qs2 . "&order=2"; ?>" <?php echo $current[2] ?>>安</a> ]</p>
          </div>
          <ul id="zaikoList" class="flex"></ul>
          <div class="pagination_wrap"></div>
        </section>

      </article><!-- /.inner -->
    </div><!--#contents-->
    <?php echo $footer; ?>
  </div><!--#wrapper-->

  <script>
    $(function() {
      let page = <?php echo $page; ?>;
      let itemsPerPage = <?php echo $itemsPerPage; ?>;
      let sort = '<?php echo $sort; ?>';
      let searchCname = '<?php echo $cname; ?>';
      let searchMaxPrice = '<?php echo $maxPrice; ?>';
      let searchMinPrice = '<?php echo $minPrice; ?>';
      let paginationLink = '<?php echo $ps; ?>'
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "./check_api.php",
        data: {
          page,
          itemsPerPage,
          sort,
          searchCname: searchCname,
          searchMaxPrice,
          searchMinPrice,
          paginationLink,
        },
      }).done(function(res) {
        let htmls = '';
        let telNo = '072-289-8070';
        if (res) {
          $(".fleft em").text(res.totalItems);
          if (res.data.length > 0) {
            $(".pagination_wrap").html(res.pagination);
            res.data.forEach(item => {
              htmls += `<li>
                    <h3 class="name">${item.cname} <span>${item.grade}</span></h3>
                    <div class="clearfix">
                      ${item.status === '売却' ? `
                      <figure class="image">
                        <img src="${item.img_url}" alt="${item.cname}" class="sp_img" />
                      </figure>` : `
                      <figure class="image">
                        <a href="details.php?sno=${item.sno}">
                          <img src="${item.img_url}" alt="${item.cname}" class="sp_img" />
                        </a>
                      </figure>`}
                      <div class="rBox">
                        <ul class="tagList clearfix">
                          ${item.rec}
                        </ul>
                        <table class="data clear">
                          <tr><th colspan="2" class="price">${item.status === '売却' ? `
                            <span class="red">SOLD OUT</span>` : `
                            <span class="red">${item.price}</span>万円`}</th></tr>
                          <tr><th>年式</th><td>${item.year_style}</td></tr>
                          <tr><th>車検</th><td>${item.inspection}</td></tr>
                          <tr><th>色</th><td>${item.color}</td></tr>
                          <tr><th>走行距離</th><td>${item.distance}</td></tr>
                          <tr><th>車台番号</th><td>${item.frame_number}</td></tr>
                        </table>
                        <ul class="carContact">
                          <li class="tel-zaiko"><p class="source"><a href="tel:${telNo}"><span class="txt">お問い合わせ</span><span class="tel">${telNo}</span></a></p></li>
                          <li class="contactBtn"><a href="form.php?store=${item.sno}&cname=${item.link}">在庫お問い合わせ</a></li>
                          <li class="detailBtn"><a href="details.php?sno=${item.sno}">詳細はこちら</a></li>
                        </ul>
                      </div>
                    </div>
                </li>`;
            });
            $("#zaikoList").html(htmls);
          } else {
            $("#zaikoList").after('<p class="center">お探しの条件の車の登録がありませんでした。</p>');
          }
        }
      }).fail(function(jqXHR, textStatus, errorThrown) {
        console.error("リクエストが失敗しました：" + textStatus, errorThrown);
        alert("リクエストが失敗しました。");
      });
    });
  </script>
</body>

</html>