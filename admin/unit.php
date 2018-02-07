<?php
//  ------------------------------------------------------------------------ //
// 本模組由 ugm 製作
// 製作日期：2013-11-06
// 動物類型
// ------------------------------------------------------------------------- //

/*-----------引入檔案區--------------*/
$xoopsOption['template_main'] = "ugm_contact_us_adm_unit_tpl.html";
include_once "header.php";
include_once "../function.php";
/*-----------執行動作判斷區----------*/
$op = empty($_REQUEST['op'])? "":$_REQUEST['op'];
$sn = empty($_REQUEST['sn'])? "":intval($_REQUEST['sn']);

switch($op){

  //新增資料
  case "update_unit":
  $sn=update_unit($sn);
  header("location: {$_SERVER['PHP_SELF']}?sn=$sn");
  break;

  //輸入表格
  case "op_form":
  $main=op_form($sn);
  break;

  //刪除資料
  case "op_delete":
  op_delete($sn);
  header("location: {$_SERVER['PHP_SELF']}");
  break;

  //預設動作
  default:
  op_list();
  break;
  /*---判斷動作請貼在上方---*/
}
/*-----------秀出結果區--------------*/
include_once "footer.php";


/*-----------function區--------------*/
###############################################################################
# ugm_contact_unit編輯表單
#
#
#
###############################################################################
function op_form($sn=""){
  global $xoopsDB,$xoopsTpl;

  # ---- 抓取預設值
  if(!empty($sn)){
    $DBV=get_db_table($sn,"ugm_contact_unit");
    $form_title=sprintf(_MA_UGMCONTACUS_TITLE_EDIT,_MI_UGMCONTACUS_ADMENU3);
  }else{
    $DBV=array();
    $form_title=sprintf(_MA_UGMCONTACUS_TITLE_ADD,_MI_UGMCONTACUS_ADMENU3);
  }
  # ---- 預設值設定

  //設定「sn」欄位預設值
  $sn=(!isset($DBV['sn']))?"":$DBV['sn'];

  //設定「title」欄位預設值
  $title=(!isset($DBV['title']))?"":$DBV['title'];

  //設定「title」欄位預設值
  $email=(!isset($DBV['email']))?"":$DBV['email'];

  if(!file_exists(TADTOOLS_PATH."/formValidator.php")){
    redirect_header("index.php",3, _TAD_NEED_TADTOOLS);
  }
  include_once XOOPS_ROOT_PATH."/modules/tadtools/formValidator.php";
  $formValidator= new formValidator("#myForm",true);
  $formValidator_code=$formValidator->render();

  $xoopsTpl->assign('now_op','op_form');
  $xoopsTpl->assign('formValidator_code',$formValidator_code);
  $xoopsTpl->assign('title',$title);
  $xoopsTpl->assign('email',$email);
  $xoopsTpl->assign('sn',$sn);

}

###############################################################################
#  寫入資料到ugm_contact_unit中
#
#
#
###############################################################################
function update_unit($sn=""){
  global $xoopsDB;

  $myts =& MyTextSanitizer::getInstance();
  $_POST['title']=$myts->addSlashes($_POST['title']);

  if(empty($sn)){
    $sql = "insert into ".$xoopsDB->prefix("ugm_contact_unit")."
    (`title`,`email`)
    values('{$_POST['title']}','{$_POST['email']}')";

    $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
    # ----取得最後新增資料的流水編號
    $sn=$xoopsDB->getInsertId();
  }else{
    $sql =
      "update ".$xoopsDB->prefix("ugm_contact_unit")."
      set
      `title` = '{$_POST['title']}',
      `email` = '{$_POST['email']}'
      where sn='$sn'";

    $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  }
  return $sn;
}

###############################################################################
#  列出所有ugm_contact_unit資料
#
#
#
###############################################################################
function op_list(){
  global $xoopsDB,$xoopsTpl;
  $sql = "select * from ".$xoopsDB->prefix("ugm_contact_unit")."";
  # ---- 分頁 -----------------------------------------------------------------
  //getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
  $count=20;
  $PageBar=getPageBar($sql,$count,10);
  $bar=$PageBar['bar'];
  $sql=$PageBar['sql'];
  $total=$PageBar['total'];
  # ----------------------------------------------------------------------------
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  # ------------ 是否出現分頁 ------------------------------------
  $bar=($total>$count)?"<div>{$bar}</div>":"";
  # ---------------------------------------------------------------
  $all_content="";
  $i=0;
  while($all=$xoopsDB->fetchArray($result)){
    //以下會產生這些變數： $sn , $title
    foreach($all as $k=>$v){
      $$k=$v;
    }

    $all_content[$i]['title']=$title;
    $all_content[$i]['email']=$email;
    $all_content[$i]['sn']=$sn;
    $i++;
  }

  //--------------------- 引入jquery -------------------------------------
  if(!file_exists(XOOPS_ROOT_PATH."/modules/tadtools/jquery.php")){
    redirect_header("index.php",3, _TAD_NEED_TADTOOLS);
  }
  include_once XOOPS_ROOT_PATH."/modules/tadtools/jquery.php";
  $jquery_path = get_jquery(); //一般只要此行即可
  # ------------------------------------------------

  $xoopsTpl->assign("jquery_path", $jquery_path);
  $xoopsTpl->assign("all_content", $all_content);
  $xoopsTpl->assign("bar", $bar);
  $xoopsTpl->assign("now_op","op_list");

  //刪除確認的JS
  $data=$jquery_path.ugm_javascript(1)."
  <script>
    function op_delete_func(sn){
      var sure = window.confirm('"._TAD_DEL_CONFIRM."');
      if (!sure)  return;
      location.href=\"{$_SERVER['PHP_SELF']}?op=op_delete&sn=\" + sn;
    }
  </script>
  <table border='0' cellspacing='3' cellpadding='3' class='ugm_tb' style='word-wrap:break-word; word-break:break-all;table-layout:fixed;'>
    <tr>
      <th style='width:120px;'>"._MA_UGMCONTACUS_UNIT_TITLE."</th>
      <th>"._MA_UGMCONTACUS_UNIT_EMAIL."</th>
      <th style='width:100px;'>"._TAD_FUNCTION."</th>
    </tr>
    $all_content
    $bar
  </table>";

  //raised,corners,inset
  $main=ugm_div("",$add_button.$data,"corners",800);

  return $main;
}


//刪除ugm_contact_unit某筆資料資料
function op_delete($sn=""){
  global $xoopsDB;
  $sql = "delete from ".$xoopsDB->prefix("ugm_contact_unit")." where sn='$sn'";
  $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
}


###############################################################################
#  以流水號取得某筆 db_table 資料
#
#
#
###############################################################################
function get_db_table($sn="",$db_table=""){
  global $xoopsDB;
  if(empty($sn) or empty($db_table))return;
  $sql = "select * from ".$xoopsDB->prefix($db_table)." where sn='$sn'";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());//die($sql);
  $data=$xoopsDB->fetchArray($result);
  return $data;
}
?>