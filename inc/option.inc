<?php
// XSS攻撃を検知させる（検知したら実行させない）。
header("X-XSS-Protection: 1; mode=block");
// IEにコンテンツの内容を解析させない（ファイルの内容からファイルの種類を決定させない）。
header("X-Content-Type-Options: nosniff");
// IEでダウンロードしたファイルを直接開かせない。
header("X-Download-Options: noopen");
// このページを iframe に埋め込ませない
header("X-Frame-Options: DENY");

session_start();
// フォームデータをFORMに配列
if (!empty($_POST) || !empty($_GET)) {
  $data = !empty($_POST) ? $_POST : $_GET;
  $_FORM = hrtrim( $data );
}

function hrtrim( $data ){
  if( is_array($data) ){
    foreach($data as $key => $value){
      $data[$key] = hrtrim($value);
    }
  }else{
    $data = htmlspecialchars($data, ENT_QUOTES);
  }
  return $data;
}

date_default_timezone_set('Asia/Tokyo');
$now_y = date("Y");
$now_m = date("m");
$now_d = date("d");

define("PATH", $_SERVER["DOCUMENT_ROOT"]);
define("incPATH", $_SERVER["DOCUMENT_ROOT"]."/os-check/inc");

$selected = ' selected="selected"';
$checked = ' checked="checked"';

$key_id = 'FzHCR2Tuhh259';
$hncd = '006355';

$_state = array( 0 => '表示', 88 => '非表示' );

?>