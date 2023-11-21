<?php
// //ライブラリ読み込み
// require "/var/www/vhosts/lexus.os-inc.jp/httpdocs/inc/option.inc";
// require "/var/www/vhosts/lexus.os-inc.jp/httpdocs/inc/library.inc";
// require "/var/www/vhosts/lexus.os-inc.jp/httpdocs/inc/entry.inc";

// ライブラリ読込み
require '../inc/option.inc';
require incPATH . '/library.inc';
require incPATH . '/define.inc';

$url = "https://auto.jocar.jp/HPWebservice/GetCarList.aspx?KEY_ID={$key_id}&HNCD={$hncd}";

$itemsPerPage = isset($_POST['itemsPerPage']) ? $_POST['itemsPerPage'] : 24;
$page = isset($_POST['page']) ? $_POST['page'] : 1;

$url .= "&HIT_PER_PAGE={$itemsPerPage}&OFFSET_PAGE={$page}";

// 検索の処理
if (isset($_POST['searchCname'])) {
  $searchCname = mb_convert_encoding($_POST['searchCname'], "Shift_JIS");
  $url .= "&CNM={$searchCname}&";
}

if (isset($_POST['searchMaker'])) {
  $searchMaker = mb_convert_encoding($_POST['searchMaker'], "Shift_JIS");
  $url .= "&MK={$searchMaker}";
}

if ((isset($_POST['searchMinPrice']) && isset($_POST['searchMaxPrice']))) {
  $searchMinPrice = $_POST['searchMinPrice']; 
  $searchMaxPrice = $_POST['searchMaxPrice']; 
  $url .= "SP={$searchMinPrice}&EP={$searchMaxPrice}";
}

// ソートの処理
if (isset($_POST['sort'])) {
  $sort = $_POST['sort']; 
  $url .= "&sort={$sort}";
}

// APIからデータを取得
$contents = file_get_contents($url);

// API呼び出しエラーを確認
if ($contents === false) {
  http_response_code(500);
  echo 'APIからデータを取得中にエラーが発生しました';
  exit;
}

// XMLデータを配列に変換
$xml = simplexml_load_string($contents);
if ($xml === false) {
  http_response_code(500);
  echo 'XMLデータの変換中にエラーが発生しました';
  exit;
} else {
  $data = json_decode(json_encode($xml), true);
}

$stockData = $xml->stock_info;
$totalItems = isset($data['total_hit_count']) ? $data['total_hit_count'] : '';
$_SESSION['total_items'] = $totalItems;
// $_SESSION['items_per_page'] = $itemsPerPage;

$dataForPage = [];
if ($stockData) {
  foreach ($stockData as $row) {
    $items = [
        'sno' => isset($row->stock_number) ? strval($row->stock_number) : '',
        'grade' => isset($row->grade) ? strval($row->grade) : '',
        'color' => isset($row->color) ? strval($row->color) : '',
        'maker' => isset($row->maker) ? strval($row->maker) : '',
        'cname' => isset($row->car_name) ? mb_convert_kana(strval($row->car_name), 'KV') : '',
        'price' => isset($row->price) ? strval($row->price) : '',
        'status' => isset($row->status) ? strval($row->status) : '',
        'frame_number' => isset($row->frame_number) ? substr(strval($row->frame_number), -3) : '',
        'rec' => isset($row->rec) ? strval($row->rec) : '',
        'inspection' => isset($row->inspection) ? strval($row->inspection) : '',
        'year_jpn' => isset($row->year_jpn) ? strval($row->year_jpn) : '',
        'distance' => isset($row->distance) ? strval($row->distance) : '',
    ];

    if (isset($row->img[0])) {
      $firstImage = $row->img[0];
      $items['img_url'] = strval($firstImage->img_url);
    } else {
      $items['img_url'] = '/os-check/img/noimage.png';
    }

    $items['link']  = urlencode('管理番号' . $items['sno'] . '　' . $items['maker'] . '　' . $items['cname'] . '　' . $items['grade'] . '　' . $items['color']);

    if (strpos($row->status, '売却') !== false) {
      $items['price'] = 'SOLD OUT';
    } else {
      if (!empty($items['price'])) {
        $price_str = str_replace(',', '', $items['price']);
        if (is_numeric($price_str)) {
            $price = intval($price_str);
            $items['price'] = strval($price / 10000);
        } else {
            $items['price'] = strval($price_str);
          }
      }
    }

    $dataForPage[] = $items;
  }
}

// ページングされたデータをJSON形式で返す
header('Content-Type: application/json');
$response = [
  'data' => $dataForPage,
  'totalItems' => $totalItems,
];
echo json_encode($response);