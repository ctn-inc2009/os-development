<?php
// ライブラリ読込み
require '../inc/option.inc';
require incPATH . '/library.inc';
require incPATH . '/define.inc';
require incPATH . "/pagination.class.php";


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

// SimpleXMLElementに変換
$xml = simplexml_load_string($contents);
if ($xml === false) {
  http_response_code(500);
  echo 'XMLデータの変換中にエラーが発生しました';
  exit;
}

$stockData = $xml->stock_info;
$totalItems = isset($xml->total_hit_count) ? strval($xml->total_hit_count) : 0;
$dataForPage = [];
if ($stockData) {
  foreach ($stockData as $row) {
    $items = [
        'sno' => isset($row->stock_number) ? strval($row->stock_number) : '',
        'grade' => isset($row->grade) ? strval($row->grade) : '',
        'color' => isset($row->color) ? strval($row->color) : '',
        'maker' => isset($row->maker) ? strval($row->maker) : '',
        'cname' => isset($row->car_name) ? strval(mb_convert_kana($row->car_name,'KV')) : '',
        'price' => isset($row->price) ? strval($row->price) : '',
        'status' => isset($row->status) ? strval($row->status) : '',
        'frame_number' => isset($row->frame_number) ? substr(strval($row->frame_number), -3) : '',
        'rec' => isset($row->rec) ? strval($row->rec) : '',
        'inspection' => isset($row->inspection) ? strval($row->inspection) : '',
        'year' => isset($row->year) ? strval($row->year) : '',
        'year_style' => isset($row->year) ? strval(add_gengo_jcm($row->year, "en")) : '',
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
            $count = strlen($price_str);
            if ($count < 8 ) {
              $items['price'] = strval($price / 10000);
            } else {
              $items['price'] =  strval(number_format($price / 10000 , 4, '.', ','));
            }
        } else {
            $items['price'] = strval($price_str);
        }
      }
    }
    $dataForPage[] = $items;
  }
}

$qs = $_FORM;
unset($qs['page']);
$qs2 = $qs; //表示順
$qs = !empty($qs) ? http_build_query($qs) : '';
unset($qs2['order']);
$qs2 = !empty($qs2) ? http_build_query($qs2) : '';

$p = new pagination;
$p->items($totalItems);
$p->limit($itemsPerPage);
if (!empty($qs)) {
  $p->target("index.php?" . $qs);
}
$p->currentPage($page);
$pagination = $p->show();

// ページングされたデータをJSON形式で返す
header('Content-Type: application/json');
$response = [
  'data' => $dataForPage,
  'totalItems' => $totalItems,
  'pagination' => $pagination,
];
echo json_encode($response);