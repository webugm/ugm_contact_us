<?php
//  ------------------------------------------------------------------------ //
// 本模組由 ugm 製作
// 製作日期：2013-11-06
// 動物類型
// ------------------------------------------------------------------------- //

/*-----------引入檔案區--------------*/
$xoopsOption['template_main'] = "ugm_contact_us_adm_service_tpl.html";
include_once "header.php";
include_once "../function.php";

/*-----------執行動作判斷區----------*/
$op = empty($_REQUEST['op'])? "":$_REQUEST['op'];
$service_sn = empty($_REQUEST['service_sn'])? "":intval($_REQUEST['service_sn']);

switch($op){

  //新增資料
  case "update_service":
  $service_sn=update_service($service_sn);
  redirect_header($_SERVER['PHP_SELF'],3, _MA_UGMCONTACUS_SUCCESS);
  break;

  //輸入表格
  case "op_form":
  op_form($service_sn);
  break;

  //刪除資料
  case "op_delete":
  op_delete($service_sn);
  redirect_header($_SERVER['PHP_SELF'],3, _MA_UGMCONTACUS_DEL_SUCCESS);
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
# ugm_cu_service編輯表單
#
#
#
###############################################################################
function op_form($service_sn=""){
  global $xoopsDB,$xoopsTpl;

  # ---- 抓取預設值
  if(!empty($service_sn)){
    $sql = "select * from ".$xoopsDB->prefix("ugm_cu_service")." where `service_sn`='$service_sn'";
    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());//die($sql);
    $DBV=$xoopsDB->fetchArray($result);
    $form_title=sprintf(_MA_UGMCONTACUS_TITLE_EDIT,_MI_UGMCONTACUS_ADMENU2);
  }else{
    $DBV=array();
    $form_title=sprintf(_MA_UGMCONTACUS_TITLE_ADD,_MI_UGMCONTACUS_ADMENU2);
  }
  # ---- 預設值設定

  //設定「service_sn」欄位預設值
  $service_sn=(!isset($DBV['service_sn']))?"":$DBV['service_sn'];

  //設定「service_name」欄位預設值
  $service_name=(!isset($DBV['service_name']))?"":$DBV['service_name'];

  if(!file_exists(TADTOOLS_PATH."/formValidator.php")){
    redirect_header("index.php",3, _TAD_NEED_TADTOOLS);
  }
  include_once XOOPS_ROOT_PATH."/modules/tadtools/formValidator.php";
  $formValidator= new formValidator("#myForm",true);
  $formValidator_code=$formValidator->render();

  $xoopsTpl->assign('now_op','op_form');
  $xoopsTpl->assign('formValidator_code',$formValidator_code);
  $xoopsTpl->assign('service_name',$service_name);
  $xoopsTpl->assign('service_sn',$service_sn);
}

###############################################################################
#  寫入資料到ugm_cu_service中
#
#
#
###############################################################################
function update_service($service_sn=""){
  global $xoopsDB;
  $myts =& MyTextSanitizer::getInstance();
  $_POST['service_name']=$myts->addSlashes($_POST['service_name']);

  if(empty($service_sn)){
    $sql = "insert into
           ".$xoopsDB->prefix("ugm_cu_service")."
           (`service_name`)
           values('{$_POST['service_name']}')";

    $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
    # ----取得最後新增資料的流水編號
    $service_sn=$xoopsDB->getInsertId();
  }else{
    $sql =
      "update ".$xoopsDB->prefix("ugm_cu_service")."
      set
      `service_name` = '{$_POST['service_name']}'
      where `service_sn`='{$service_sn}'"; //die($sql);
    $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  }
  return $service_sn;
}

###############################################################################
#  列出所有ugm_cu_service資料
#
#
#
###############################################################################
function op_list(){
  global $xoopsDB,$xoopsTpl;

  $sql = "select * from ".$xoopsDB->prefix("ugm_cu_service")."";
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
    //以下會產生這些變數： $service_sn , $service_name
    foreach($all as $k=>$v){
      $$k=$v;
    }

    $all_content[$i]['service_name']=$service_name;
    $all_content[$i]['service_sn']=$service_sn;
    $i++;
  }

  //--------------------- 引入jquery -------------------------------------
  if(!file_exists(XOOPS_ROOT_PATH."/modules/tadtools/jquery.php")){
    redirect_header("index.php",3, _TAD_NEED_TADTOOLS);
  }
  include_once XOOPS_ROOT_PATH."/modules/tadtools/jquery.php";
  $jquery_path = get_jquery(); //一般只要此行即可

  $xoopsTpl->assign("jquery_path", $jquery_path);
  $xoopsTpl->assign("all_content", $all_content);
  $xoopsTpl->assign("bar", $bar);
  $xoopsTpl->assign("now_op","op_list");


}


//刪除ugm_cu_service某筆資料資料
function op_delete($service_sn=""){
  global $xoopsDB;
  $sql = "delete from ".$xoopsDB->prefix("ugm_cu_service")." where service_sn='$service_sn'";
  $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
}



?>