<?php
// ライブラリ読込み
require "../inc/option.inc";
require incPATH . "/library.inc";
require incPATH . "/define.inc";

$sno = !empty($_GET['sno']) ? $_GET['sno'] : 0;
// try {
//   // データベースの接続
//   $dbh = db_construct();

//   $sql = "SELECT * FROM `car`
//           WHERE price > 0 AND stock_number = '{$sno}'";
//   $stmt = $dbh->prepare($sql);
//   $stmt->bindValue(1, $no, PDO::PARAM_STR);
//   $stmt->execute();
//   $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
//   if (!empty($rows)) {
//     $row = $rows[0];
//     $no = $row['no'];
//     $cname = $row['car_name'];
//   } else {
//     header("HTTP/1.1 410 Gone");
//     header("Location:index.php");
//   }
// } catch (PDOException $e) {
//   echo "失敗しました。" . $e->getMessage();
//   die();
// }

$metaDescription = "";

$conTitle = '在庫車情報｜西日本最大級のレクサス専門店株式会社OS';
$pageTitle = "{$cname}｜{$conTitle}｜{$siteTitle}";
$metaDescription = "西日本で最大級のレクサス専門店株式会社OSの最新在庫車情報からお気に入りの一台を見つけてください！レクサスの厳選した中古車を多数在庫しております。西日本でレクサス中古車を買うなら地域最大級株式会社OSのにお越しください。";

//レイアウト読み込み
$_cssList2[] = '/os-check/css/stock.css';
require incPATH . "/layout.inc";
?>
<!DOCTYPE html>
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
            <img src="../img/top/salecoupon_bnr.jpg?20220218">
        </div>
        </a>

        <section>
          <div id="zaikoDetail">
            <?/*= $tokuten; */ ?>
            <div id="data"></div>

            <div class="reasonBox">
              <h2>OSが選ばれる理由</h2>
              <ul class="flex j-between">

                <li><a href="/os-check/reason/#reason01" class="flex j-between">
                    <h3>西日本最大級の在庫量</h3>
                    <p>レクサスの特選中古車を主に取り扱う西日本最大のレクサス中古車専門店です。<br>レクサス特選中古車は150台以上を展示しております。</p>
                    <figure><img src="../img/stock/reason01.jpg"></figure>
                  </a></li>

                <li><a href="/os-check/reason/#reason02" class="flex j-between">
                    <h3>高品質レクサスが驚きの販売価格</h3>
                    <p>弊社では全車修復歴なしのレクサス車しか販売しておりません。<br>販売価格設定は他社価格のリサーチを重ねたうえで地域最安値の販売価格を設定しております。</p>
                    <figure><img src="../img/stock/reason02.jpg"></figure>
                  </a></li>

                <li><a href="/os-check/reason/#reason03" class="flex j-between">
                    <h3>レクサス専門店としての使命</h3>
                    <p>TOYOTAブランド最上級のレクサスのみを取扱う専門店として、<br>商品知識はもちろんのことディーラー出身整備士が1台1台のお車を確かな技術で仕上げております。</p>
                    <figure><img src="../img/stock/reason03.jpg"></figure>
                  </a></li>

                <li><a href="/os-check/reason/#reason04" class="flex j-between">
                    <h3>保証もトラブルも安心</h3>
                    <p>プロフェッショナルな専門スタッフが、各車の状態に関しては専門知識で対応いたしますので、<br>万が一の故障や修理時に余分なコストをかけずに、修理も短期間で完了させることが出来ます。</p>
                    <figure><img src="../img/stock/reason04.jpg"></figure>
                  </a></li>

                <li><a href="/os-check/reason/#reason05" class="flex j-between">
                    <h3>メーター保証</h3>
                    <p>レクサス専門店OSはメーターを改ざんした車両は販売しません。<br>OSでは、厳しい基準をクリアした車のみを仕入れ全ての車両の品質情報を公開することで、どなたでも安心してクルマ探しができる環境を整えています。</p>
                    <figure><img src="../img/stock/reason05.jpg"></figure>
                  </a></li>

                <li><a href="/os-check/reason/#reason06" class="flex j-between">
                    <h3>第三者機関の査定・鑑定</h3>
                    <p>入庫した全ての在庫車輌に対し第三者機関であるAIS査定士がチェックを行い、<br>併せて外部査定機関のAISに鑑定を依頼しております。</p>
                    <figure><img src="../img/stock/reason06.png"></figure>
                  </a></li>

              </ul>
            </div>
            <div class="otherBox sec_txt5">
              <h4 class=""></h4>
              <ul class="otherList flex"></ul>
            </div>
          </div><!--/#zaikoDetail-->
        </section>

      </article><!-- /.inner -->
    </div><!--#contents-->

    <?= $footer; ?>
  </div><!--#wrapper-->

  <script type="text/javascript" src="../js/slick.min.js"></script>
  <script type="text/javascript">
    $(function() {
      let sno = "<?= $sno; ?>";
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "./details.json.php",
        data: {
          sno: sno
        }
      }).done(function(res) {
        let car_name = res.cname;
        let maker = res.maker;
        if (res) {
          $('#data').html(res.data);
        }
        let slider = "#sliderInner ul"; // スライダー
        let thumbnailItem = "#bx-pager ul li"; // サムネイル画像アイテム

        // サムネイル画像アイテムに data-index でindex番号を付与
        $(thumbnailItem).each(function() {
          let index = $(thumbnailItem).index(this);
          $(this).attr("data-index", index);
        });

        // スライダー初期化後、カレントのサムネイル画像にクラス「thumbnail-current」を付ける
        // 「slickスライダー作成」の前にこの記述は書いてください。
        $(slider).on('init', function(slick) {
          let index = $(".slide-item.slick-slide.slick-current").attr("data-slick-index");
          $(thumbnailItem + '[data-index="' + index + '"]').addClass("thumbnail-current");
        });

        //slickスライダー初期化
        $('#sliderInner ul').slick({
          autoplay: true,
          autoplaySpeed: 5000,
          speed: 1000,
          arrows: false,
          fade: true,
          infinite: false, //これはつけましょう。
          appendArrows: $('#arrow')
        });
        //サムネイル画像アイテムをクリックしたときにスライダー切り替え
        $(thumbnailItem).on('click', function() {
          let index = $(this).attr("data-index");
          $(slider).slick("slickGoTo", index, false);
        });

        //サムネイル画像のカレントを切り替え
        $(slider).on('beforeChange', function(event, slick, currentSlide, nextSlide) {
          $(thumbnailItem).each(function() {
            $(this).removeClass("thumbnail-current");
          });
          $(thumbnailItem + '[data-index="' + nextSlide + '"]').addClass("thumbnail-current");
        });

        anothersShow(car_name, maker);
      });

      function anothersShow(cname, maker) {
        let itemsPerPage = 500;
        let searchCname = cname;
        let searchMaker = maker;
        $.ajax({
          type: "POST",
          dataType: "json",
          url: "./check_api.php",
          data: {
            itemsPerPage,
            searchCname: encodeURIComponent(searchCname),
            searchMaker: encodeURIComponent(searchMaker)
          },
        }).done(function(res) {
          let htmls = '';
          if (res.data.length > 0) {
            $(".fleft em").text(res.totalItems);
            let anothers = [];
            res.data.map(item => {
              console.log(sno);
              console.log(item.sno);
              if (sno !== item.sno) {
                anothers.push(item);
              }
            });
            console.log(anothers);
            if (anothers.length > 0) {
              anothers.sort(() => Math.random() - 0.5);
              let randomFour = anothers.slice(0, 4);
              let htmls = "";
              randomFour.forEach(item => {
                console.log(item);
                htmls += `
                  <li><a href="details.php?sno=${item.sno}">
                      <figure><img src="${item.img_url}" alt="${item.maker +' '+ item.cname}" /></figure>
                      <table>
                          <tr><th>年式</th><td>${item.year_jpn}</td></tr>
                          <tr><th>走行距離</th><td>${item.distance}</td></tr>
                          <tr><th>検査期限</th><td>${item.inspection}</td></tr>
                          <tr><td colspan="2" class="price">価格:<span>${item.price}</span></td></tr>
                      </table>
                      <p class="detailBtn arrow">詳細はこちら</p></a>
                  </li>`;
              });
              $(".otherBox h4").text(`他の[${cname}]ラインアップ`);
              $(".otherList").html(htmls);
            }
          }
        });
      }
    });
  </script>
</body>

</html>