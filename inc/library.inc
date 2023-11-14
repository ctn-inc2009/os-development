<?php
########################################################################
# データベース接続処理関数
########################################################################
function db_construct(){
    /* PDO関数 */
	// データベース接続設定読み込み
	require dirname(__FILE__)."/db_config.inc";

	/* ドライバ呼び出しを使用して ODBC データベースに接続する */
	$dsn = $config['db_type'].':dbname='.$config['db_name'].';host='.$config['db_host'];
	$user = $config['db_user'];
	$password = $config['db_pass'];

	try {
	    $dbh = new PDO($dsn, $user, $password, array( PDO::ATTR_PERSISTENT => true ) );
	}
	catch (PDOException $e) {
	    echo 'Connection failed: ' . $e->getMessage();
	}

	$sql = "SET NAMES utf8";
	//$res = $dbh->query($sql);

	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	return $dbh;
}

########################################################################
# 検索用SQL文WHERE句生成処理（前後あいまい検索用）
# sql_where_str_name()
#       引数    ：  [IN]$str_field      テーブル上のフィールド名
#                   [IN]$val            フォームの値
#                   [IN/OUT]$where      WHERE句
#                   [IN]$int_flg        検索「繋ぎ」妥当性フラグ
#       戻り値  ：  成功 -> 接続されたWHERE句、失敗 -> false
#       機能    ：  WHERE句を生成
#       説明    ：  文字列用のWHERE句生成
#       更新    ：  First Create [2002/05/28]
#       作成者  ：  Koji Yamamoto
########################################################################
function sql_where_str_name($str_field,$val,&$where,$int_flg){
    if($val == ""){
        return false;
    } else {
        $where .= ($int_flg == 1) ? 'AND ' : '';
        $where = $where . $str_field . " LIKE '" . "%" . $val . "%' ";
        return $where;
    }
}

########################################################################
# 検索用SQL文WHERE句生成処理（後あいまい検索用）
# sql_where_str()
#       引数    ：  [IN]$str_field      テーブル上のフィールド名
#                   [IN]$val            フォームの値
#                   [IN/OUT]$where      WHERE句
#                   [IN]$int_flg        検索「繋ぎ」妥当性フラグ
#       戻り値  ：  成功 -> 接続されたWHERE句、失敗 -> false
#       機能    ：  WHERE句を生成
#       説明    ：  文字列用のWHERE句生成
#       更新    ：  First Create [2002/05/28]
#       作成者  ：  Koji Yamamoto
########################################################################
function sql_where_str($str_field,$val,&$where,$int_flg){
    if($val == ""){
        return false;
    } else {
        $where .= ($int_flg == 1) ? 'AND ' : '';
        $where = $where . $str_field . " LIKE '" . $val . "%' ";
        return $where;
    }
}
########################################################################
# 検索用SQL文WHERE句生成処理（完全一致検索用）
# sql_where_str()
#       引数    ：  [IN]$str_field      テーブル上のフィールド名
#                   [IN]$val            フォームの値
#                   [IN/OUT]$where      WHERE句
#                   [IN]$int_flg        検索「繋ぎ」妥当性フラグ
#       戻り値  ：  成功 -> 接続されたWHERE句、失敗 -> false
#       機能    ：  WHERE句を生成
#       説明    ：  文字列用のWHERE句生成
#       更新    ：  First Create [2002/05/28]
#       作成者  ：  Koji Yamamoto
########################################################################
function sql_where_str_m($str_field,$val,&$where,$int_flg){
    if($val == ""){
        return false;
    } else {
        $where .= ($int_flg == 1) ? 'AND ' : '';
        if($val=="nasi"){
            $val ="";
        }

        $where = $where . $str_field . " = '" . $val . "' ";
        return $where;
    }
}

########################################################################
# 検索用SQL文WHERE句生成処理　＞
# sql_where_chk()
#       引数    ：  [IN]$str_field      テーブル上のフィールド名
#                   [IN]$val            フォームの値
#                   [IN/OUT]$where      WHERE句
#                   [IN]$int_flg        検索「繋ぎ」妥当性フラグ
#       戻り値  ：  成功 -> 接続されたWHERE句、失敗 -> false
#       機能    ：  WHERE句を生成
#       説明    ：  チェックボタン用のWHERE句生成
#       更新    ：  First Create [2002/05/28]
#       作成者  ：  Koji Yamamoto
########################################################################
function sql_where_chku($str_field,$val,&$where,$int_flg, $null = ""){
    if($val == ""){
        return false;
    } else {
        $where .= ($int_flg == 1) ? 'AND ' : '';
        if ($null == 1) {
            $where .= "({$str_field} >= {$val} OR {$str_field} is NULL)";
        } else {
            $where = $where . $str_field . " >= '" . $val . "' ";
        }
        return $where;
    }
}

########################################################################
# 検索用SQL文WHERE句生成処理　＜
# sql_where_chk()
#       引数    ：  [IN]$str_field      テーブル上のフィールド名
#                   [IN]$val            フォームの値
#                   [IN/OUT]$where      WHERE句
#                   [IN]$int_flg        検索「繋ぎ」妥当性フラグ
#       戻り値  ：  成功 -> 接続されたWHERE句、失敗 -> false
#       機能    ：  WHERE句を生成
#       説明    ：  チェックボタン用のWHERE句生成
#       更新    ：  First Create [2002/05/28]
#       作成者  ：  Koji Yamamoto
########################################################################
function sql_where_chkd($str_field,$val,&$where,$int_flg, $null = ""){
    if($val == ""){
        return false;
    } else {
        $where .= ($int_flg == 1) ? 'AND ' : '';
        if ($null == 1) {
            $where .= "({$str_field} <= {$val} OR {$str_field} is NULL)";
        } else {
            $where = $where . $str_field . " <= '" . $val . "' ";
        }
        return $where;
    }
}

########################################################################
# 範囲検索処理
# sql_where_kikan_j()
#       引数    ：  [IN]$str_field      テーブル上のフィールド名
#                   [IN]$hoge       値
#                   [IN]$kikan_type     From or To
#                   [IN]$where          WHERE句
#                   [IN]$int_flg        検索「繋ぎ」妥当性フラグ
#       戻り値  ：
#       機能    ：  範囲検索用（要素１つ） SQL 文の生成
#       説明    ：
#       更新    ：  First Create [2002/05/22]
#       作成者  ：  Tsuyoshi MAritani
########################################################################
function sql_where_kikan_j($str_field,$hoge,$kikan_type,&$where,$int_flg){

    if($hoge == ""){
        return false;
    } else {
        $where .= ($int_flg == 1) ? 'AND ' : '';

        if($kikan_type == "From"){
            $sign = " >= ";
        } elseif($kikan_type == "To"){
            $sign = " <= ";
        }

        $where = $where . $str_field . $sign . "'" . $hoge . "' ";

    }

    return $where;
}

########################################################################
# 検索用SQL文WHERE句生成処理
# sql_where_in()
#       引数    ：  [IN]$str_field      テーブル上のフィールド名
#                   [IN]$array          値(配列)
#                   [IN]$where          WHERE句
#                   [IN]$int_flg        検索「繋ぎ」妥当性フラグ
#       戻り値  ：  成功 -> 接続されたWHERE句、失敗 -> false
#       機能    ：  IN演算子WHERE句を生成
#       更新    ：  First Create [2007/12/06]
#       作成者  ：  Yuko Tsuda
########################################################################
function sql_where_in($str_field,$array,&$where,$int_flg, $no_flag = ''){
    if(count($array) == 0 or $array == ""){
        return false;
    } else {
        $where .= ($int_flg == 1) ? 'AND ' : '';
        $i = 0;
        $where .= !empty($no_flag) ? " NOT " : '';
        $where .= $str_field." IN (";
        foreach( $array as $k => $v ){
            if($v != ""){
                if($i>0){
                    $where .= ",";
                }
                $where .= "'" . $v . "'";
                $i++;
            }
        }
        $where .= ") ";

    }
    return $where;
}

########################################################################
# 検索用SQL文WHERE句生成処理
# sql_where_multiple()
#       引数    ：  [IN]$str_field      テーブル上のフィールド名
#                   [IN]$array          値(配列)
#                   [IN]$where          WHERE句
#                   [IN]$int_flg        検索「繋ぎ」妥当性フラグ
#       戻り値  ：  成功 -> 接続されたWHERE句、失敗 -> false
#       機能    ：  IN演算子WHERE句を生成
#       更新    ：  First Create [2015/04/27]
#       作成者  ：  Yuko Tsuda
########################################################################
function sql_where_multiple($str_field,$array,&$where,$int_flg){
    if(count($array) == 0 or $array == ""){
        return false;
    } else {
        $where .= ($int_flg == 1) ? 'AND ' : '';
        $i = 0;
        $where .= " (";
        foreach( $array as $k => $v ){
            if($v != ""){
                if($i>0){
                    $where .= " OR ";
                }
                $where .= "(".$str_field." LIKE '%" . $v . "%')";
                $i++;
            }
        }
        $where .= ") ";

    }
    return $where;
}


########################################################################
# SQL(DML文)生成処理
# create_dml_sql()
#       引数    ：  [IN]$table_name     テーブル名
#                   [IN]$fields_values  値(配列)
#                   [IN]$mode           insert,update,delete
#                   [IN]$where          WHERE句
#       戻り値  ：  成功 -> 作成されたSQL文、失敗 -> false
#       機能    ：  SQL(DML文)を生成
#       更新    ：  First Create [2010/05/11]
#       作成者  ：  Yuko Tsuda
########################################################################
function create_dml_sql($table_name,$fields_values,$mode,$where = null){
    $mode = strtolower($mode);
    switch($mode){
      case "insert":
        $i = 0; $key = ''; $val = '';
        foreach( $fields_values as $k => $v ){
          $key .= $i > 0 ? ",`{$k}`" : "`{$k}`";
          $val .= $i > 0 ? ",'{$v}'" : "'{$v}'";
          $i++;
        }
        $sql = "INSERT INTO {$table_name} ({$key}) VALUES ({$val})";
        return $sql;
        break;
      case "update":
        $i = 0; $tmp = '';;
        foreach( $fields_values as $k => $v ){
          $tmp .= $i > 0 ? ',' : '';
          $tmp .= !empty($v) ? "`{$k}` = '{$v}'" : "`{$k}` = NULL";
          $i++;
        }
        $sql = !empty($where) ? "UPDATE {$table_name} SET {$tmp} WHERE {$where}" : false;
        return $sql;
        break;
      case "delete":
        $sql = !empty($where) ? "DELETE FROM {$table_name} WHERE {$where}" : false;
        return $sql;
        break;
      default:
        return false;
    }
}


########################################################################
# 最大ページ数取得関数
# get_maxpage()
#       引数    ：  [IN]$records        取得したレコード数
#               ：  [IN]$limit          最大表示件数
#       戻り値  ：  成功->最大ページ数、失敗->false;
#       機能    ：  取得したレコード数から、最大ページ数を算出する
#       説明    ：
#       更新    ：  First Create [2002/05/22]
#       作成者  ：  Koji Yamamoto
########################################################################
function get_maxpage($records,$limit){
    if($records % $limit != 0){
        $max_page = sprintf("%d",$records / $limit + 1);
    } else {
        $max_page = $records / $limit;
    }

    if($max_page == 0){
        $max_page = 1;
    }
    return $max_page;
}

########################################################################
# 元号・西暦変換処理関数 library.incから移動
# gengo_from_ad()
#   引数  ： [IN]$year   年
#         ： [IN]$month    月
#         ： [IN]$type
#   戻り値 ： 成功->西暦、失敗->空文字列
#   機能  ： 元号から西暦を計算し変換する。
#   説明  ：
#   更新  ： First Create [2019/04/05]
#   作成者 ： Yuko Tsuda
########################################################################
function gengo_from_ad($year, $month, $type = "", $nen = ""){
  if(intval($month) == 0){
    $month = $year == 2019 ? 5 : 1;
  }
  $month = sprintf('%02d', $month);

  $data = $year.$month;
  if (201905 <= $data) {
    $gengo = $type == 'a' ? "R" : "令和";
    $y = $year - 2018;
  } else if (198901 <= $data && 201904 >= $data) {
    $gengo = $type == 'a' ? "H" : "平成";
    $y = $year - 1988;
  } else if (192701 <= $data && 198812 >= $data) {
    $gengo = $type == 'a' ? "S" : "昭和";
    $y = $year - 1925;
  } else if (191209 <= $data && 192612 >= $data) {
    $gengo = $type == 'a' ? "T" : "大正";
    $y = $year - 1911;
  } else if (186801 <= $data &&  191209 >= $data) {
    $gengo = $type == 'a' ? "M" : "明治";
    $y = $year - 1867;
  } else {
    $gengo = "";
    $y = $year;
  }

  $y = $y != 1 ? $y : (empty($nen) ? '元' : $y);
  switch ($type) {
    case 'era':
      $data = $gengo;
      break;
    case 'none':
      $data = $y;
      break;
    default:
      $data = $gengo.$y;
      break;
  }
  return $data;
}

########################################################################
#       セレクトを作る
########################################################################
function create_select1($name, $data, $selected, $kara, $kara2 = ""){
    //値が一個だけ
    $select="<select name=\"".$name."\">\n";
    if($kara=="up"){ $select.="<option value=\"\">$kara2</option>"; }
    foreach ( $data as $key ){
        $check="";
        if($key==$selected and $selected!=""){ $check=" selected"; }
        $select.="  <option value=\"".$key."\"".$check.">$key</option>\n";
    }
    if($kara=="down"){ $select.="<option value=\"\">$kara2</option>"; }
    $select.="</select>";
    return $select;
}
function create_select2($name, $data, $selected, $kara, $kara2 = ""){
    //値二つ　valueと表示が違う
    $select="<select name=\"".$name."\">\n";
    if($kara=="up"){ $select.="<option value=\"\">$kara2</option>"; }
    foreach ( $data as $key => $value ){
        $check="";
        if($key==$selected and $selected!=""){ $check=" selected"; }
        $select.="  <option value=\"".$key."\"".$check.">$value</option>\n";
    }
    if($kara=="down"){ $select.="<option value=\"\">$kara2</option>"; }
    $select.="</select>";
    return $select;
}
function create_select4($name, $start, $end, $selected, $kara, $kara2 = "", $keta = ""){
    //start～endまでのセレクトを作る
    $select="<select name=\"".$name."\">\n";
    if($kara=="up"){ $select.="<option value=\"\">$kara2</option>"; }
    if($start <= $end){
        for ( $i=$start; $i<=$end; $i++ ){
            $check = ($i==$selected and $selected!="") ? $check=" selected" : "";
            $j = $i;
            if($keta!=""){
                $j = STR_PAD($i,$keta,'0',STR_PAD_LEFT);
            }
            $select.="  <option value=\"".$j."\"".$check.">$j</option>\n";
        }
    }else{
        for ( $i=$start; $i>=$end; $i-- ){
            $check = ($i==$selected and $selected!="") ? $check=" selected" : "";
            $j = $i;
            if($keta!=""){
                $j = STR_PAD($i,$keta,'0',STR_PAD_LEFT);
            }
            $select.="  <option value=\"".$j."\"".$check.">$j</option>\n";
        }
    }
    if($kara=="down"){ $select.="<option value=\"\">$kara2</option>"; }
    $select.="</select>";
    return $select;
}

########################################################################
#       ラジオボタンを作る
########################################################################
function create_radio1($name, $data, $checked, $def, $tunagi="", $encode="", $num=""){
  //値が一個だけ
  $radio = '';
  $i = 0; $tmp = ' checked = "checked"';
  foreach ( $data as $k => $v ){
    $radio .= $num!="" && $num == $i ? "<br />" : '';
    $ck = $v == $checked ? $tmp : ($v == $def ? $tmp : '');
    if ($encode != "") { $v = mb_convert_encoding($v, $encode); }
    $id = str_replace( array('[', ']'), '', $name.$k);

    $radio .= "<label><input type=\"radio\" name=\"{$name}\" id=\"{$id}\" value=\"{$v}\"".$ck." />".$v."</label>".$tunagi."\n";
    $i++;
  }
  return $radio;
}
function create_radio2($name, $data, $checked, $def, $tunagi="", $encode="", $num=""){
  //値二つ　valueと表示が違う
  $radio = '';
  $i = 0; $tmp = ' checked = "checked"';
  foreach ( $data as $k => $v ){
    $radio .= $num!="" && $num == $i ? "<br />" : '';
    $ck = $k == $checked ? $tmp : ($checked == '' && $k == $def ? $tmp : '');
    if ($encode != "") { $v = mb_convert_encoding($v, $encode); }
    $id = str_replace( array('[', ']'), '', $name.$k);

    $radio .= "<label><input type=\"radio\" name=\"{$name}\" id=\"{$id}\" value=\"{$k}\"".$ck." />".$v."</label>".$tunagi."\n";
    $i++;
  }
  return $radio;
}

########################################################################
#       画像の左右反転 
########################################################################
function image_flop($image){
    // 画像の幅を取得
    $w = imagesx($image);
    // 画像の高さを取得
    $h = imagesy($image);
    // 変換後の画像の生成（元の画像と同じサイズ）
    $destImage = @imagecreatetruecolor($w,$h);
    // 逆側から色を取得
    for($i=($w-1);$i>=0;$i--){
        for($j=0;$j<$h;$j++){
            $color_index = imagecolorat($image,$i,$j);
            $colors = imagecolorsforindex($image,$color_index);
            imagesetpixel($destImage,abs($i-$w+1),$j,imagecolorallocate($destImage,$colors["red"],$colors["green"],$colors["blue"]));
        }
    }
    return $destImage;
}

########################################################################
#       画像の上下反転 
########################################################################
function image_flip($image){
    // 画像の幅を取得
    $w = imagesx($image);
    // 画像の高さを取得
    $h = imagesy($image);
    // 変換後の画像の生成（元の画像と同じサイズ）
    $destImage = @imagecreatetruecolor($w,$h);
    // 逆側から色を取得
    for($i=0;$i<$w;$i++){
        for($j=($h-1);$j>=0;$j--){
            $color_index = imagecolorat($image,$i,$j);
            $colors = imagecolorsforindex($image,$color_index);
            imagesetpixel($destImage,$i,abs($j-$h+1),imagecolorallocate($destImage,$colors["red"],$colors["green"],$colors["blue"]));
        }
    }
    return $destImage;
}
########################################################################
#       画像を回転 
########################################################################
function image_rotate($image, $angle, $bgd_color){
     return imagerotate($image, $angle, $bgd_color, 0);
}

########################################################################
#       画像の方向を正す 
########################################################################
function orientationFixedImage($input, $type){
    switch($type){
        case ".gif";
            $image = imagecreatefromgif($input);
            break;
        case ".png";
            $image = imagecreatefrompng($input);
            break;
        default:
            $image = imagecreatefromjpeg($input);
    }
    $exif_datas = @exif_read_data($input);
    if(isset($exif_datas['Orientation'])){
          $orientation = $exif_datas['Orientation'];
          if($image){
                  // 未定義
                  if($orientation == 0){
                  // 通常
                  }else if($orientation == 1){
                  // 左右反転
                  }else if($orientation == 2){
                        image_flop($image);
                  // 180°回転
                  }else if($orientation == 3){
                        image_rotate($image,180, 0);
                  // 上下反転
                  }else if($orientation == 4){
                        image_Flip($image);
                  // 反時計回りに90°回転 上下反転
                  }else if($orientation == 5){
                        image_rotate($image,270, 0);
                        image_flip($image);
                  // 時計回りに90°回転
                  }else if($orientation == 6){
                        image_rotate($image,90, 0);
                  // 時計回りに90°回転 上下反転
                  }else if($orientation == 7){
                        image_rotate($image,90, 0);
                        image_flip($image);
                  // 反時計回りに90°回転
                  }else if($orientation == 8){
                        image_rotate($image,270, 0);
                  }
          }
    }
    return $image;
}

########################################################################
# 画像を指定サイズに縮小
# img_resize()
#       引数    ：保存するファイル名
#               ：添付ファイル名
#               ：横幅サイズ
#       戻り値  ：  成功->、失敗->falseエラーメッセージ;
#       機能    ：  画像をリサイズする
#       説明    ：
#       更新    ：  First Create [2007/05/25]
########################################################################
function img_resize($filename, $tmpfilename, $width, $height = "", $imgtype = ""){
    if(!move_uploaded_file($tmpfilename, $filename)){
        // アップロードフォルダへの移動に失敗
        return "アップロードを完了できません。ディレクトリの権限を確認して下さい。<br/>";
        exit;
    }else{
        switch($imgtype){
            case ".gif";
                $src_image = imagecreatefromgif($filename);
                break;
            case ".png";
                $src_image = imagecreatefrompng($filename);
                break;
            default:
                $src_image = imagecreatefromjpeg($filename);
        }

        $src_w = imagesx($src_image);
        $src_h = imagesy($src_image);
        chmod($filename,0644);

        if($src_w >= $width or ($height < $src_h and $height > 0)){
            $dst_w = $width;
            $dst_h = $src_h * ($dst_w / $src_w);
            if($height < $dst_h and $height > 0){
                $dst_h = $height;
                $dst_w = $src_w * ($dst_h / $src_h);
            }
            $dst_image = imagecreatetruecolor($dst_w, $dst_h);
            if(!imagecopyresampled($dst_image,$src_image,0, 0, 0, 0, $dst_w, $dst_h, $src_w, $src_h)){
                return "画像のリサイズを完了できません。<br/>";
                exit;
//          return "srcimage=".$src_image . "  w=" . $w . "  h=" . $h . "  dst_image=". $dst_image ."<br/>";
            }else if(!imagejpeg($dst_image,$filename,90)){
                return "リサイズ後の画像を保存できません。<br/>";
                exit;
            }
        }
      return;
    }
}

function img_resize2($filename,$width, $height = 0){
      $src_image = imagecreatefromjpeg($filename);
      $src_w = imagesx($src_image);
      $src_h = imagesy($src_image);
      if($src_w>$width){
        $dst_w = $width;
        $dst_h = $src_h * ($dst_w / $src_w);
        if($height < $dst_h and $height > 0){
            $dst_h = $height;
            $dst_w = $src_w * ($dst_h / $src_h);
        }
      } else if($height < $src_h and $height > 0){
            $dst_h = $height;
            $dst_w = $src_w * ($dst_h / $src_h);
      }else{
        $dst_w = $src_w;
        $dst_h = $src_h;
      }

      $dst_image = imagecreatetruecolor($dst_w, $dst_h);
      chmod($filename,0644);
      if(!imagecopyresampled($dst_image,$src_image,0, 0, 0, 0, $dst_w, $dst_h, $src_w, $src_h)){
        echo "画像のリサイズを完了できません。<br/>";
        exit;
//        return "srcimage=".$src_image . "  w=" . $w . "  h=" . $h . "  dst_image=". $dst_image ."<br/>";
      }else if(!imagejpeg($dst_image,$filename,90)){
        echo "リサイズ後の画像を保存できません。<br/>";
        exit;
      }
      return;
}


/*
//正方形の画像を作るカット版
function img_resize_square($filename, $size = 80){
      $src_image = imagecreatefromjpeg($filename);
      $src_w = imagesx($src_image);
      $src_h = imagesy($src_image);

      $x = $y = 0;
      if($src_w> $src_h) {
          $x = ceil(($src_w - $src_h) / 2 );
          $src_w = $src_h;
      } elseif($src_h> $src_w) {
          $y = ceil(($src_h - $src_w) / 2);
          $src_h = $src_w;
      }

      $dst_image = imagecreatetruecolor($size, $size);
      chmod($filename,0644);
      if(!imagecopyresampled($dst_image,$src_image,0, 0, $x, $y, $size, $size, $src_w, $src_h)){
        echo "画像のリサイズを完了できません。<br/>";
        exit;
//        return "srcimage=".$src_image . "  w=" . $w . "  h=" . $h . "  dst_image=". $dst_image ."<br/>";
      }else if(!imagejpeg($dst_image,$filename,90)){
        echo "リサイズ後の画像を保存できません。<br/>";
        exit;
      }
      return;
}
*/

########################################################################
#メールアドレスが入力されていれば妥当性のチェック
########################################################################
function checkEmail($email) {
  global $isEAddressCorrect;
  //半角英数字かどうかチェック
  if (preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9\._\-]+)*$/", $email)){
    //@が中ほどにあればtrue,なければfalseをかえします。
    //@が2文字目以降に現れている時
    if(strpos($email , "@")>0){
      //@より右側に文字が3文字以上あるとき
      if(strlen(strstr($email,"@")) >= 3){
        $url = substr(strrchr($email,"@"),1);
        if(checkdnsrr($url, 'MX') || checkdnsrr($url, 'A') || checkdnsrr($url, 'AAAA')){
          $isEAdressCorrect = TRUE;
          return TRUE;
        }
      }
    }
  }
  $isEAderessCorrect = FALSE;
  return FALSE;
}

########################################################################
# PHP5：fgetcsvで文字コードが違ったり、囲いこみ文字がなく
# 正常にデータが取れないのを補正する
########################################################################
    /**
     * ファイルポインタから行を取得し、CSVフィールドを処理する
     * @param resource handle
     * @param int length
     * @param string delimiter
     * @param string enclosure
     * @return ファイルの終端に達した場合を含み、エラー時にFALSEを返します。
     */
    function fgetcsv_reg (&$handle, $length = null, $d = ',', $e = '"') {
        $d = preg_quote($d);
        $e = preg_quote($e);
        $_line = "";
        while (($eof != true)and(!feof($handle))) {
            $_line .= (empty($length) ? fgets($handle) : fgets($handle, $length));
            $itemcnt = preg_match_all('/'.$e.'/', $_line, $dummy);
            if ($itemcnt % 2 == 0) $eof = true;
        }
        $_csv_line = preg_replace('/(?:\r\n|[\r\n])?$/', $d, trim($_line));
        $_csv_pattern = '/('.$e.'[^'.$e.']*(?:'.$e.$e.'[^'.$e.']*)*'.$e.'|[^'.$d.']*)'.$d.'/';
        preg_match_all($_csv_pattern, $_csv_line, $_csv_matches);
        $_csv_data = $_csv_matches[1];
        for($_csv_i=0;$_csv_i<count($_csv_data);$_csv_i++){
            $_csv_data[$_csv_i]=preg_replace('/^'.$e.'(.*)'.$e.'$/s','$1',$_csv_data[$_csv_i]);
            $_csv_data[$_csv_i]=str_replace($e.$e, $e, $_csv_data[$_csv_i]);
        }
        return empty($_line) ? false : $_csv_data;
    }

########################################################################
#日付を得る
########################################################################
function getDay($day){
    return mktime(date("H"),date("i"),date("s"),date("m"),date("d")+$day,date("Y"));
}

########################################################################
#エラーを表示する
########################################################################
function displayContents($data){
    echo "<pre>";
    print_r($data);
    echo "</pre>";
}

########################################################################
# 表示順最大データを得る
# getNextorder()
#       引数    ：
#       戻り値  ：  最大データ＋10
#       説明    ：
########################################################################
function getNextorder($dbh, $table_name, $orderfield, $where){
    $max_order = '';
    try {
        $sql = "SELECT MAX(".$orderfield.") AS max_order FROM ".$table_name." WHERE ".$where;
        $res = $dbh->query($sql);
        $rows = $res->fetchAll();
        $max_order = $rows[0][0];
        unset($res);
        return  $max_order + 10;
    }catch (PDOException $e){
      print('Error:'.$e->getMessage());
      return false;
    }
}

########################################################################
# 表示順番号を変更
# renumber_order()
#       引数    ：
#       戻り値  ：
#       説明    ：
########################################################################
function renumber_order($dbh, $table_name, $idfield, $orderfield, $where){
    try {
        $sql = "SELECT $idfield FROM $table_name WHERE $idfield > 0 AND ".$where;
        $sql .= " ORDER BY $orderfield ASC";
        $res = $dbh->query($sql);
        $rows = $res->fetchAll(PDO::FETCH_ASSOC);
        $i = 10;

        foreach ( $rows as $row ) {
            $sql2 = "UPDATE $table_name SET $orderfield = $i WHERE $idfield = " . $row[$idfield];
            $res2 = $dbh->exec($sql2);
            $i += 10;
        }
    }catch (PDOException $e){
      print('Error:'.$e->getMessage());
      return false;
    }
}

########################################################################
#ディレクトリごと削除する
#       引数    ：ディレクトリパス
########################################################################
function remove_directory($dir) {
  if ($handle = opendir("$dir")) {
   while (false !== ($item = readdir($handle))) {
     if ($item != "." && $item != "..") {
       if (is_dir("$dir/$item")) {
         remove_directory("$dir/$item");
       } else {
         unlink("$dir/$item");
         //echo " removing $dir/$item<br>\n";
       }
     }
   }
   closedir($handle);
   rmdir($dir);
   //echo "removing $dir<br>\n";
  }
}

########################################################################
#削除製品の画像削除
#       引数    ：ディレクトリパス
#       引数    ：商品番号等一括削除
########################################################################
function remove_file($dir, $no) {
  if ($handle = opendir("$dir")) {
    while (false !== ($item = readdir($handle))) {
      if (preg_match("/^({$no})+([a-zA-Z0-9\._-])+$/", $item)) {
         unlink("$dir/$item");
         //echo " removing $dir/$item<br>\n";
      }
    }
  }
  closedir($handle);
}

########################################################################
#削除製品の画像削除
#       引数    ：ディレクトリパス
#       引数    ：ファイル名（1で10枚目以降削除されてしまう修正版）
########################################################################
function remove_file2($dir, $filename) {
  if ($handle = opendir("$dir")) {
    while (false !== ($item = readdir($handle))) {
      if (preg_match("/^({$filename})+([a-zA-Z\._-])+$/", $item)) {
         unlink("$dir/$item");
         //echo " removing $dir/$item<br>\n";
      }
    }
  }
  closedir($handle);
}


########################################################################
# ページ移動（POST）
#		引数	：現在のページ
#				：全体のページ数
########################################################################
function post_pagination($page, $maxpage, $limit)
{
    $movelist = '';
    if ($page != 1) {
        $prevpage = $page-1;
        $movelist .= "<input type=\"submit\" name=\"prev\" value=\"←前の".$limit."件\" />　";
    }
    $p_st = 1;
    if($maxpage <= 10){
        $p_end = $maxpage;
    }else{
        if($page > 5){ $p_st = $page-4; $p_end = $page+4; }else{ $p_end = 10; }
        if($p_end >= $maxpage){ $p_st = $maxpage-9; $p_end = $maxpage; }
    }
    if($p_st>1){ $movelist .= "<span class=\"none\">...</span>"; }
    for ($m = $p_st; $m <= $p_end; $m++) {
        if ($m != $page) {
            $movelist .= '<input type="submit" name="p" value="'.$m.'" />　';
        }else{
            $movelist .= "<span class=\"current\">".$m."</span>　";
        }
    }
    if ($p_end < $maxpage) {
        $movelist .= '<span class="none">...</span>　<input type="submit" name="p" value="'.$maxpage.'">　';
    }
    if ($page != $maxpage) {
        $nextpage = $page+1;
        $movelist .= "<input type=\"submit\" name=\"next\" value=\"次の".$limit."件→\" />";;
    }
    return $movelist;
}


########################################################################
# GoogleMaps API GeoCoder を用いて住所・駅名の緯度・経度を求める
# @param	string $query 検索キーワード
# @param	array $items 情報を格納する配列
########################################################################
function getGeocoding($query) {
  $data = '';
  if(!empty($query)) {
    $query = urlencode($query);
    $url = "http://www.geocoding.jp/api/";
    $url.= "?v=1.1&q=".$query;

    $fp = fopen($url, "r");
    while(!feof($fp)) {
      $line.= fgets($fp);
    }
    fclose($fp);

    $xml = simplexml_load_string($line);
    $data['lat'] = (string)$xml->coordinate->lat;
    $data['lng'] = (string)$xml->coordinate->lng;
  }
  return $data;
}

########################################################################
# 坪数計算
# @param	string $area 面積
########################################################################
function getTubo ($area) {
    $tubo = round($area * 0.3025, 2);
	return $tubo;
}

########################################################################
# 会員ログ
# @param	string $cno 会員番号
# @param	string $type ログタイプ
# @param	string $logtime 入力日
# @param	string $bno 物件No
# @param	string $text テキスト
########################################################################
function insertUserLog ($dbh, $cno, $type, $logtime, $bno, $text = "") {
  try {
    $sql = "INSERT INTO cLog (
            cno,type,logtime,bno,text
            ) VALUES (
            {$cno},{$type},{$logtime},'{$bno}','{$text}'
            )";
    $dbh->exec($sql);
  }catch (PDOException $e){
    print('Error:'.$e->getMessage());
    return false;
  }
}

########################################################################
# 暗号化
# @param	string $input 文字列
# @param	string $key   暗号キー
########################################################################
function encryptedParam($input,$key = KEY_DEFAULT)
{
    $key = md5($key);

    //暗号化モジュール使用開始
    $td  = mcrypt_module_open('tripledes', '', 'ecb', '');
    $key = substr($key, 0, mcrypt_enc_get_key_size($td));
    $iv  = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);

    if (mcrypt_generic_init($td, $key, $iv) < 0) {
      exit('error.');
    }

    $encrypted_data = base64_encode(mcrypt_generic($td, $input));
    //print $encrypted_data;
    mcrypt_generic_deinit($td);
    mcrypt_module_close($td);

    return $encrypted_data;
}
########################################################################
# 複合化
# @param	string $input 文字列
# @param	string $key   暗号キー
########################################################################
function decryptedParam($input,$key = KEY_DEFAULT)
{

    //暗号化＆復号化キー
    $key = md5($key);

    //暗号化モジュール使用開始
    $td  = mcrypt_module_open('tripledes', '', 'ecb', '');
    $key = substr($key, 0, mcrypt_enc_get_key_size($td));
    $iv  = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);

    //暗号化モジュール初期化
    if (mcrypt_generic_init($td, $key, $iv) < 0) {
      exit('error.');
    }

    //データを復号化
    $encrypted_data = rtrim(mdecrypt_generic($td, base64_decode($input)));

    //暗号化モジュール使用終了
    mcrypt_generic_deinit($td);
    mcrypt_module_close($td);

    return $encrypted_data;

}

/***********************************************************
 * ランダム文字列生成 (英数字)
 * $length: 生成する文字数
 * http://qiita.com/TetsuTaka/items/bb020642e75458217b8a
 ************************************************************/
function makeRandStr($length = 8) {
  static $chars = 'abcdefghijklmnpqrstuvwxyzABCDEFGHIJLKMNPQRSTUVWXYZ123456789';
  $str = '';
  for ($i = 0; $i < $length; ++$i) {
    $str .= $chars[mt_rand(0, 61)];
  }
  return $str;
}
/***********************************************************
 * 万円から億を追加する
 ************************************************************/
function number_format_to_str($amount){
  $oku = floor(($amount % 100000000) / 10000);
  $nokori = ($amount % 100000000) % 10000;

  $result = '';

  if ($oku) $result = $oku . '億';
  if ($nokori) $result .= number_format($nokori).'万';
  return $result ;
}

/***********************************************************
 * 万円にする
 ************************************************************/
function getManYen($amount, $point = 0, $unit = ''){
  $man = $amount / 10000;
  if ($point > 0) {
    $tmp = 10 * $point;
    $res = floor($man * $tmp) / $tmp;
  } else {
    $res = floor($man);
  }

  return $res.$unit;
}

/***********************************************************
 * メールアドレスからユーザーエージェントを得る
 ************************************************************/
function getUserAgent($val) {
  if ((preg_match("/^([a-zA-Z0-9\._-])*@(docomo.ne.jp)$/", $val))
      or (preg_match("/^([a-zA-Z0-9@\._-])*(pdx.ne.jp)$/", $val))) {
      $ua = 'DoCoMo';
  } else if (preg_match("/^([a-zA-Z0-9\._-])*@(ezweb.ne.jp)$/", $val)) {
      $ua = 'EZweb';
  } else if ((preg_match("/^([a-zA-Z0-9\._-])*@(softbank.ne.jp)$/", $val))
      or (preg_match("/^([a-zA-Z0-9@\._-])*(vodafone.ne.jp)$/", $val))) {
      $ua = 'SoftBank';
  } else {
      $ua = 'PC';
  }
  return $ua;
}

########################################################################
# 検査期限
# getKensa()
#		引数	：	年もしくは車検切れ＋月
########################################################################
function getKensa($kensa, $year = 0, $month = 0, $mode = ''){
  if ($kensa == '車検あり') {
    $now_y = date("Y");
    if ($year >= $now_y) {
      switch($mode){
        case "s":
        case "master":
          $gtype = 'A';
          break;
        default:
          $gtype = '';
      }
      $mon = ($month > 0) ? $month : 12;
      $y = gengo_from_ad($year,$mon,$gtype);
      if ($mode == 's') {
        $data = ($month > 0) ? $y.'/'.$month : $y;
      } else {
        $data = ($month > 0) ? $y.'年'.$month.'月' : $y.'年';
      }
    } else {
      $data = '車検切れ';
    }
  } else {
    $data = $kensa;
  }
  return  $data;
}

########################################################################
# YYYYMMの表示設定
# formatYM()
# @param	string $ym    YYYYMMM
# @param	string $mode  表示形式
# @param	array  $  表示形式
########################################################################
function formatYM($ym, $mode = '', $unit = array()){
  if (ctype_digit($ym) && $ym > 0) {
    $y = substr($ym, 0, 4);
    $m = substr($ym, 4, 2);

    $a = explode('-', $mode);
    switch($a[0]){
      case "g": //元号アルファベット
        $gengo = gengo_from_ad($y, $m, "A", $unit[0]);
        break;
      case "G": //元号日本語
        $gengo = gengo_from_ad($y, $m, "", $unit[0]);
        break;
      case "gn": //元号年のみ
        $gengo = substr(gengo_from_ad($y, $m, "A", $unit[0]), 1);
        break;
      case "ym":
        $data = $y.$unit[0].$m.$unit[1];
        break;
      case "yn":
        $data = $y.$unit[0].intval($m).$unit[1];
        break;
      case "y":
      default:
        $data = $y.$unit[0];
    }
    if (!empty($a[1])) {
      switch($a[1]){
        case "ym":
          $data = $gengo.$unit[0].$m.$unit[1];
          break;
        case "yn":
          $data = $gengo.$unit[0].intval($m).$unit[1];
          break;
        case "y":
          $data = $gengo.$unit[0];
        default:
          $data = $data;
      }
    }
  } else {
    $data = $ym > 0 ? $ym : '';
  }

  return  $data;
}
/***********************************************************
 * 指定のファイル更新時間の取得
 ************************************************************/
function getFileTime($filename) {
  $time = @filemtime($filename);
  return $time;
}

/***********************************************************
# 元号　年式・車検
# gengo_stock_add()
#       引数  ：   [IN]$day        日付
#       戻り値 ：   成功->20年以上は平成元号追加、失敗->20年以下は令和元号追加
#       更新  ：   First Create [2019/07/18]
#       作成者 ：   Takumi Motokawa
 ************************************************************/
function add_gengo_jcm($day, $type = ""){
  $day2 = mb_substr($day, 0, 2);
  if (ctype_digit($day2)) {
    if ($day2 <= 10) {
      $gengo = ($type == '') ? "令和" : "R";
      $day = $gengo.$day;
    } else {
      $gengo = ($type == '') ? "平成" : "H";
      $day = $gengo.$day;
    }
  }
  return $day;
}

?>