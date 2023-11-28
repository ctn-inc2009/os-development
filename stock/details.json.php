<?php
// ライブラリ読込み
require "../inc/option.inc";
require incPATH . "/library.inc";
require incPATH . "/define.inc";

$ua = 'pc';
$tmp = $_SERVER['HTTP_USER_AGENT'];
if (empty($_SESSION['sp'])) {
  if ((strpos($tmp, 'iPhone') !== false) || (strpos($tmp, 'iPod') !== false) || (strpos($tmp, 'Android') !== false) && (strpos($tmp, 'Mobile') !== false)) {
    $ua = 'sp';
  }
}

$sno = isset($_POST['sno']) ? $_POST['sno'] : '';

$url = "https://auto.jocar.jp/HPWebservice/GetCarDetail.aspx?KEY_ID={$key_id}&HNCD={$hncd}&GM={$sno}";

// APIからデータを取得
$contents = file_get_contents($url);

// API呼び出しエラーを確認
if ($contents === false) {
  http_response_code(500);
  echo 'APIからデータを取得中にエラーが発生しました';
  exit;
}

// SimpleXMLElementに変換
$xml = simplexml_load_string($contents);
if ($xml === false) {
  http_response_code(500);
  echo 'XMLデータの変換中にエラーが発生しました';
  exit;
}

$detailsData = $xml->stock_info;
$error = $xml->error->message ? true : false;

if ($detailsData) {
  foreach ($detailsData as $row) {
    $maker = isset($row->maker) ? $row->maker : '';
    $cname = isset($row->car_name) ? mb_convert_kana($row->car_name, 'KV') : '';
    $price = isset($row->price) ? $row->price : '';
    //変換
    if (strpos($row->status, '売却') !== false) {
      $price = '<span class="red">SOLD OUT</span>';
    } else {
      if (!empty($price)) {
        $price_str = str_replace(',', '', $price);
        if (is_numeric($price_str)) {
          $count = strlen($price_str);
          $price = intval($price_str);
          if ($count < 8) {
            $price_val = $price / 10000;
          } else {
            $price_val = number_format($price / 10000, 4, '.', ',');
          }
        } else {
          $price_val = $price_str;
        }
      }
      $price = '本体価格<em class="red">' . $price_val . '</em>万円(税込)';
    }
    $year_style = isset($row->year) ? add_gengo_jcm($row->year, 'en') : '';
    $inspection = isset($row->inspection) ? $row->inspection : '';
    $grade = isset($row->grade) ? $row->grade :  '';
    //色（ホワイトパールクリスタルシャイン）など
    $color = isset($row->exterior_color) ? $row->exterior_color : '';
    //在庫状況 使わない
    $status = !empty($row->status) ? $row->status : '-';
    //商談件数 使わない
    $business_count = $row->business_count > 0 ? $row->business_count : '-';
    //型式
    $model_number = isset($row->model_number) ? $row->model_number : '';
    //車台ナンバー
    $frame_number = isset($row->frame_number) ? $row->frame_number : '';
    //内装色
    $interior_color = isset($row->interior_color) ?: $row->interior_color;
    //走行距離
    $distance = '-';
    if (!empty($row->distance)) {
      $tmp = str_replace([',', 'km'], '', $row->distance) ;
      if (is_numeric($tmp)) {
        $tmp = $tmp / 10000;
        $distance = '<em>' . $tmp . '万</em>km';
      }
    }
    //ミッション名
    $mission = isset($row->mission) ? $row->mission : '';
    //駆動
    $drive = isset($row->drive) ? $row->drive : '';
    //低燃費排出ガス
    $gas = isset($row->gas) ? $row->gas : '';
    //タイプ
    $type = isset($row->type) ? $row->type : '';
    //形状
    $config = isset($row->config) ? $row->config : '';
    //排気量
    $displacement = !empty($row->displacement) ? $row->displacement : '-';
    //エンジン区分
    $engine_type = isset($row->engine_type) ? $row->engine_type : '';
    //乗車定員
    $capacity = isset($row->capacity) ? $row->capacity : '';
    //ドア数
    $door = isset($row->door) ? $row->door : '';
    //車検日
    $inspection = isset($row->inspection) ? $row->inspection : '';
    //修復歴
    $repair = isset($row->repair) ? $row->repair : '';
    //リサイクル状況
    $recycle = isset($row->recycle) ? $row->recycle  : '';
    //保証_1
    $warranty1 = isset($row->warranty1) ? $row->warranty1 : '';
    //保証_2
    $warranty2 = isset($row->warranty2) ? $row->warranty2 : '';
    //定期点検整備
    $maintenance = isset($row->maintenance) ? $row->maintenance : '';
    //整備費用
    $maintenance_cost = isset($row->maintenance_cost) ? $row->maintenance_cost : '';
    //整備手帳
    $maintenance_note = isset($row->maintenance_note) ? $row->maintenance_note : '';
    //車歴
    $history = isset($row->history) ? $row->history : '';

    //お問い合わせ
    $link = '管理番号' . $sno . '　' . $cname . '　' . $grade . '　' . $color;

    //スライド用クーポン
    // for($i = 1; $i <= 10; $i++){
    //   $filename = "../files/image/1_{$i}.jpg";
    //   if (file_exists($filename)) {
    //     $img = '<img src="'.$filename.'" alt="'.$cname.$j.'枚目">';
    //     $txt = !empty($_coupon[$i]) ? '<p>'.nl2br($_coupon[$i]).'</p>' : '';
    //     $slide .= "<li>{$img}{$txt}</li>";
    //     $pager .= "<a data-slide-index=\"{$j}\" href=\"#\">{$img}</a>";
    //     if ($i == 1) { $pager .= "<a data-slide-index=\"{$j}\" href=\"#\" class=\"coupon\">クーポンを見る</a>"; }
    //     $j++;
    //   }
    // }

    //画像
    $slide = $pager = '';
    $j = 0;
    if (count($row->img) > 0) {
      foreach ($row->img as $k => $v) {
        $j++;
        foreach ($v as $k2 => $v2) {
          if ($k2 == 'img_comment') {
            $comment = !empty($v2) ? '<p>' . $v2 . '</p>' : '';
          }
          if ($k2 == 'img_url') {
            //https警告回避 httpから画像取る場合はhttp:を消す
            $img_url = strpos($v2, 'http:') !== false ? str_replace('http:', '', $v2) : $v2;
            $img = '<img src="' . $img_url . '" alt="' . $cname . '">';
          }
        }
        $slide .= '<li><div class="img">' . $img . '</div>' . $comment . '</li>';
        $pager .= '<li>' . $img . '</li>';
      }
      $cnt = $j;
    }

    //装備情報
    $equip = '';
    if (count($row->equip) > 0) {
      foreach ($row->equip as $k => $v) {
        foreach ($v as $k2 => $v2) {
          if ($k2 == 'equipment') {
            $tmp = mb_convert_kana($v2, 'ASKV');
            $equip .= '<li>' . $tmp . '</li>';
          }
        }
      }
    }

    //その他装備
    $equipment_etc = isset($row->equipment_etc) ? $row->equipment_etc : '';

    //PRコメント
    $pr_comment = '';
    if (count($row->pr) > 0) {
      foreach ($row->pr as $k => $v) {
        foreach ($v as $k2 => $v2) {
          if ($k2 == 'pr_comment') {
            $pr_comment .= nl2br($v2);
          }
        }
      }
    }
  }
}

//画像
$slider = '';
if (!empty($slide)) {
  if ($ua != 'pc') {
    $slider .= '<div class="slide_cap">スライドで画像を確認できます。<span>全' . $cnt . '枚</span></div>';
    $slider_txt = '<p class="slide_notes">画像をクリックすると大きく表示できます。</p>';
  }
  $slider .= <<< EOD
  <div id="slider">
    <div id="sliderInner">
      <ul>{$slide}</ul>
      <div id="arrow"></div>
    </div><!--/#sliderInner -->
    {$slider_txt}
    <div id="bx-pager">
      <ul>{$pager}</ul>
    </div>
  </div><!--/#slider -->\n
EOD;
}
//装備情報
if (!empty($equip)) {
  $equipData = <<< EOD
  <h4 class="conttl4">装備情報</h4>
  <ul class="equip flex">
    {$equip}
  </ul>\n
EOD;
}
//PRコメント
if (!empty($pr_comment)) {
  $pr_comment = <<< EOD
    <h4 class="conttl4">PRコメント</h4>
    <div>
      <p>{$pr_comment}</p>
    </div>\n
EOD;
}

/*
■在庫店表記がある場合（rBox中）
<p class="store store{$s}">在庫店：{$_store[$s]}</p>
■タグリスト（rBox中在庫店下）
<ul class="tagList clearfix">
  {$new}
  <li class="c{$cls}">{$row['usage']}</li>
</ul>
■いるのか分からないもの（走行距離などのテーブルの下）
<table class="data clear">
  <tr>
    <th>在庫状況</th>
    <th>商談件数</th>
  </tr>
  <tr>
    <td>{$status}</td>
    <td>{$business_count}</td>
  </tr>
</table>
■form在庫店取得（お問い合わせフォーム内）
<input type="hidden" name="store" value="{$s}">
*/

//お問い合わせ
$telNo = '072-289-8070';
$telBox = <<< EOD
<p class="tel-zaiko source">
<a href="tel:{$telNo}"><span class="tel gFont1">{$telNo}</span></a>
</p>\n
EOD;

$carContact = <<< EOD
<ul class="carContactList flex flex-between">
  <li><a href="#toiawase" class="contact"><span class="vh_ctr">ネットで相談<br>在庫お問合わせ</span></a></li>
  <li><a href="tel:{$telNo}" class="tel"><span class="vh_ctr">今すぐ電話で相談<br><i class="fas fa-phone-alt"></i> {$telNo}</span></a></li>
  <li><a href="../reserve/" class="reserve"><span class="vh_ctr">来店予約</span></a></li>
</ul><!--/carContact-->\n
EOD;

$vehicle = <<< EOD
<table class="vehicle" summary="車両の状態">
  <tr><th>管理番号</th><td>{$sno}</td></tr>
  <tr><th>タイプ</th><td>{$type}</td></tr>
  <tr><th>色</th><td>{$color}</td></tr>
  <tr><th>形状</th><td>{$config}</td></tr>
  <tr><th>駆動</th><td>{$drive}</td></tr>
</table>
<table class="vehicle" summary="車両の状態">
  <tr><th>排気量</th><td>{$displacement}</td></tr>
  <tr><th>ミッション名</th><td>{$mission}</td></tr>
  <tr><th>ドア数</th><td>{$door}</td></tr>
  <tr><th>リサイクル状況</th><td>{$recycle}</td><tr>
  <tr><th>車台番号</th><td>{$frame_number}</td></tr>
</table>
EOD;

$zaikoCon = $ua == 'pc' ? $telBox : '';

$details = <<< EOD
<div class="detailsCon flex flex-between">
  <div class="lBox">
    {$slider}
  </div><!--/lBox-->
  <div class="rBox">
    <div class="carBox">
      <h3 class="cname">{$cname}</h3>
      <p class="grade">{$grade}</p>
      <p class="price">{$price}</p>

      <dl class="cdata flex">
        <div><dt>年式</dt><dd><em>{$year_style}</em></dd></div>
        <div><dt>走行距離</dt><dd>{$distance}</dd></div>
        <div><dt>車検</dt><dd><em>{$inspection}</em></dd></div>
        <div><dt>修復歴</dt><dd><em>{$repair}</em></dd></div>
      </dl>
    </div>

    {$carContact}
  </div><!--/rBox-->
</div><!--/detailsCon-->

<div class="tblBox">
  <h4 class="conttl4">車両情報</h4>
  <div class="carInfoTbl flex j-between">
    {$vehicle}
  </div>
  {$equipData}
  {$pr_comment}
</div><!--/tblBox-->
<div id="toiawase" class="carContactBox flex flex_center">
  <p class="zaikoCon center">在庫お問い合わせ</p>
  {$zaikoCon}
  <form action="form.php" method="post">
  <input type="hidden" name="cname" value="{$link}">
  <div class="contactForm">
  <dl>
  <dt>お名前</dt><dd><input type="text" name="name" value="{$_SESSION['name']}" placeholder="例）田中　太郎"></dd>
  <dt>電話番号</dt><dd><input type="text" name="tel" value="{$_SESSION['tel']}" placeholder="例）09012345678"></dd>
  <dt>メールアドレス</dt><dd><input type="text" name="email" value="{$_SESSION['email']}" placeholder="例）example@example.com"></dd>
  </dl>
  <p class="submit"><input type="submit" name="confirm" class="btn_submit" value="確認画面へ" /></p>
  </div>
  <input type="hidden" name="zaikoFlag" value="1">
  </form>
</div><!--/toiawase-->\n
EOD;

header('Content-Type: application/json');
$response = [
  'maker' => $maker,
  'cname' => $cname,
  'data' => $details,
  'error' => $error,
];
echo json_encode($response);
