<?php
//検索関連
include_once __DIR__ ."/carData.inc";

$searchjs = <<< EOD
<script type="text/javascript">
$(function(){
  var pd2 = $("#cname option").clone();
  var val1 = $("select[name=maker]").val();
  var val2 = $("#cname").val();

  $("select[name=maker]").change(function () {
    var lv1Val = $(this).val();
    if (lv1Val != "") {
      $("#cname option").remove();
      $(pd2).appendTo("#cname");
      $("#cname option[class != m"+lv1Val+"]").remove();

      var ck = (lv1Val != val1 || val2 == '') ? ' selected="selected"' : '';
      $("#cname").prepend('<option value=""'+ck+'>選択してください</option>');
    }
  }).change();
});
</script>
EOD;


$select['maker'] = create_select2("maker", $_maker, $_SESSION['search']['maker'], "up", "選択してください");
$select['price'] = create_select2("price", $_price, $_SESSION['search']['price'], "up", "選択してください");

$select['cname'] = '<select name="cname" id="cname"><option value="">選択してください</option>';
foreach( $array['car'] as $k => $a ){
  foreach( $a as $k2 => $v2 ){
    $ck = $v2 == $_SESSION['search']['cname'] ? $selected : '';
    $select['cname'] .=  "<option value=\"{$v2}\" class=\"m{$k}\"{$ck}>{$v2}</option>";
  }
}
$select['cname'] .= '</select>';

$searchBox = <<< EOD
<div class="searchBox">
<form method="get" action="index.php">
  <div class="flex j-between">
    <!--<fieldset>
      <legend>メーカー</legend>
      {$select['maker']}
    </fieldset>-->
    <fieldset>
      <legend>車種</legend>
      {$select['cname']}
    </fieldset>
    <fieldset>
      <legend>価格帯</legend>
      {$select['price']}
    </fieldset>
    <input type="submit" name="submit" value="検索する" class="searchBtn">
  </div>
</form>
</div>

EOD;
?>