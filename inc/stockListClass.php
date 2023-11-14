<?php
//osusumeリスト
class dataList {

  var $limit = 99; //表示件数
  var $page = 1; //ページ
  var $style = 'list'; //見せ方
  var $order = 'no ASC'; //表示順
  var $where = ''; //検索関連

  //表示件数の設定
  function setLimit($val) { $this->limit = $val; }
  //始まりのページの設定
  function setPage($val) { $this->page = $val; }
  //見せ方の設定
  function setStyle($val) { $this->style = $val; }
  //表示順の設定
  function setOrder($val) { $this->order = $val; }
  //検索関連
  function setSqlWhere($val) { $this->where = $val; }

  function getdataList(){
    global $_maker;
    global $_image;
    if (empty($_maker)) include incPATH.'/define.inc';
    if (empty($_image)) include incPATH.'/image.inc';
    try {
      // データベースの接続
      $dbh = !empty($dbh) ? $dbh : db_construct();

      unset($res);
      $limit = $this->limit;
      $style = $this->style;
      $where = $this->where;

      $tbName = $dir = 'car';
      $column = '*';

      $sql = "SELECT {$column} FROM {$tbName}";
      $sql .= " WHERE `state` < 80 AND `販売価格` > 0 ".$where;
      $sql .= " ORDER BY ".$this->order;
      $sql .= " LIMIT ".$limit;

      unset($res);
      $res = $dbh->query($sql);
      $rows = $res->fetchAll(PDO::FETCH_ASSOC);

      $data = '';
      foreach ( $rows as $row ) {
        $no = $row['no'];
        $rno = $row['在庫車両管理番号'];
        $color = !empty($row['車体色']) ? $row['車体色'] : $row['系統色'];
        $cname = $row['車種名称'];
        $color = !empty($row['color1']) ? $row['color1'] : '';
        $nensiki = formatYM($row['年式'], 'g-y', array('年式'));
        if ($ua != 'pc'){
          $kensa = formatYM($row['車検満期日'], 'g-yn', array('/', ''));
        } else {
          $kensa = formatYM($row['車検満期日'], 'G-yn', array('年', '月'));
        }
        $soukou = number_format($row['走行距離']).$row['走行距離単位'];

        if ($row['state'] > 10) {
          $price = '<span class="red">'.$_state[$row['state']].'</span>';
        } else {
          $price = '価格<span class="red"><em>'.getManYen($row['販売価格'], 1, '').'</em>万円</span>';
          //$price .= $row['total_price'] > 0 ? '<br><span class="small">支払総額：'.$row['total_price'].'万円</span>' : '';
        }

        $image = '<img src="/img/noimage.svg" alt="" class="sp_img" />';
        $ino = $_image[$rno];
        $filename = "/files/image/{$ino}_1s.jpg";
        if (file_exists(PATH.$filename)) {
          $image = "<img src=\"{$filename}\" border=\"0\" width=\"220\" alt=\"{$cname}\" class=\"sp_img\">";
        }

        $data .= <<< EOD
        <li><a href="/stock/{$no}.php">
          <p class="name"><em>{$cname} {$grade}</em></p>
          <figure>{$image}</figure>
          <dl>
            <dt>年式</dt><dd>{$nensiki}</dd>
            <dt>走行</dt><dd>{$soukou}</dd>
          </dl>
          <p class="price">{$price}</p>
        </a></li>\n
EOD;
      }
    } catch (PDOException $e){
      //echo $sql.$where.'<br>';
      echo $e->getMessage();
      die('ERROR!');
    }

    return array('data' => $data, 'max' => $row_max);
  }
}
?>