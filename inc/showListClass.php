<?php
// //物件リスト
// class showList {

//   var $mode = 'nousya'; //納車式
//   var $limit = 0; //表示件数
//   var $page = 1; //ページ
//   var $style = 'def'; //見せ方
//   var $where = ''; //検索関連
//   var $test = 0; //テスト表示

//   //テーブル名等
//   function setMode($val) { $this->mode = $val; }
//   //表示件数の設定
//   function setLimit($val) { $this->limit = $val; }
//   //始まりのページの設定
//   function setPage($val) { $this->page = $val; }
//   //見せ方の設定
//   function setStyle($val) { $this->style = $val; }
//   //検索関連
//   function setSqlWhere($val) { $this->where = $val; }
//   //テスト表示
//   function setSqlTest($val) { $this->test = $val; }

//   function getShowList(){
//     try {
//       // データベースの接続
//       $dbh = !empty($dbh) ? $dbh : db_construct();

//       unset($res);
//       $mode = $this->mode;
//       $style = $this->style;
//       $limit = $this->limit;
//       $test = $this->test;

//       $tbName = $dir = $mode;
//       $column = '*';

//       $sql = "SELECT SQL_CALC_FOUND_ROWS {$column} FROM {$tbName}";
//       $where = $this->where;
//       if ($test != 1) {
//         $sql .= " WHERE state = 0 ".$where;
//       } else {
//         if (!empty($where)) {
//           $sql .= " WHERE ".$where;
//         }
//       }

//       if ($style == 'def') {
//         $sql .= " ORDER BY `date` DESC ";

//         if ($limit > 0) {
//           $pointer = ($this->page-1)*$this->limit;
//           $sql .= " LIMIT ".$pointer.",".$this->limit;
//         }
//         $res = $dbh->query($sql);
//         $rows = $res->fetchAll(PDO::FETCH_ASSOC);

//         //件数取得
//         unset($res);
//         $res = $dbh->query("SELECT FOUND_ROWS()");
//         $row_max = $res->fetchColumn();
//       } else {
//         $res = $dbh->query($sql);
//         $rows = $res->fetchAll(PDO::FETCH_ASSOC);
//       }

//       $data = '';
//       foreach ( $rows as $row ) {
//         $no = $row['no'];

//         $user = '';
//         if ($mode == 'nousya') {
//           $nickname = !empty($row['nickname']) ? '<p class="nickname">'.$row['nickname'].'様</p>' : '';
//           $car_name = !empty($row['car_name']) ? '<p class="car_name">購入した車：<span>'.$row['car_name'].'</span></p>' : '';
//           $user = <<< EOD
//           <div class="user">
//             {$nickname}
//             {$car_name}
//           </div>\n
// EOD;
//         }


//         $filename = "/files/{$mode}/{$no}";
//         //$img = '/img/noimage.gif';
//         if (file_exists(PATH.$filename.'s.jpg')) {
//           switch($mode){
//             case "nousya":
//               $date = date("Y/n/j", strtotime($row['date']));
// 							$name = !empty($row['name']) ? ''.nl2br($row['name']).'' : '';
//               $comment = !empty($row['comment']) ? ''.nl2br($row['comment']).'' : '';
//               $data .= '<li><a href="'.$filename.'.jpg" data-lity="data-lity" class="push"><figure class="popImg"><img src="'.$filename.'s.jpg" alt="'.$date.'のお客様"></figure><div class="summary">'.$date.'&emsp;'.$name.'&emsp;'.$comment.'</div></a></li>'."\n";
//               break;
//             case "enquete":
//               global $_staff;
//               $date = '';
//               if ($row['date'] != '0000-00-00') {
//                 $date = '<p class="date">'.date("Y年n月j日", strtotime($row['date'])).'</p>';
//                 $item = !empty($row['item']) ? '<dt class="item">ご購入車種</dt><dd>'.$row['item'].'</dd>' : '';
//                 $staff = !empty($row['staff']) ? '<p class="staff right">担当：'.$row['staff'].'</p>' : '';
//                 $staff_comment = !empty($row['staff_comment']) ? '<p class="staffComment">'.$row['staff_comment'].'</p>' : '';
//               }
//               $data .= <<<EOD
// <li>
// {$user}
// <a href="{$filename}.jpg" data-lity="data-lity">
// <img src="{$filename}s.jpg" alt="" class="enquete_pic">
// <div class="txtBox">
//   {$date}
//   <dl>
//   {$item}
//   </dl>
//   {$staff_comment}
//   {$staff}
// </div>
// </a>
// </li>\n
// EOD;
//               break;
//           }
//         }//画像があることが前提
//       }
//     } catch (PDOException $e){
//       //echo $sql.$where.'<br>';
//       echo $e->getMessage();
//       die('ERROR!');
//     }

//     return array('data' => $data, 'max' => $row_max);
//   }
// }
?>