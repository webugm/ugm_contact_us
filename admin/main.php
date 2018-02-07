<?php
//  ------------------------------------------------------------------------ //
// 本模組由 ugm 製作
// 製作日期：2009-02-28
// $Id:$
// ------------------------------------------------------------------------- //

/*-----------引入檔案區--------------*/
$xoopsOption['template_main'] = "ugm_contact_us_adm_main_tpl.html";
include_once "header.php";
include_once "../function.php";

/*-----------執行動作判斷區----------*/
$op = (!isset($_REQUEST['op']))? "main":$_REQUEST['op'];
$cu_sn=(!isset($_REQUEST['cu_sn'])) ? 0:intval($_REQUEST['cu_sn']);
switch($op){
  //更新資料
  case "update_ugm_contact_us":
  update_ugm_contact_us($cu_sn);
  header("location: {$_SERVER['PHP_SELF']}");
  break;


  //新增資料
  case "insert_ugm_contact_us":
  insert_ugm_contact_us();
  header("location: {$_SERVER['PHP_SELF']}");
  break;


  case "show_one_ugm_contact_us":
  $main=show_one_ugm_contact_us($cu_sn);
  break;

  //新增資料solution
  case "insert_ugm_cu_solution":
  insert_ugm_cu_solution();
  update_ugm_contact_us($cu_sn);
  show_one_ugm_contact_us($cu_sn);
  break;

  //刪除資料
  case "delete_ugm_contact_us":
  delete_ugm_contact_us($cu_sn);
  header("location: {$_SERVER['PHP_SELF']}");
  break;

  //預設動作
  default:
  if(empty($_GET['cu_sn'])){
    $main=list_ugm_contact_us();
  }else{
    $main=show_one_ugm_contact_us($cu_sn);
  }
  break;

}

/*-----------秀出結果區--------------*/
include_once "footer.php";




/*-----------function區--------------*/

//列出所有ugm_contact_us資料
function list_ugm_contact_us(){
  global $xoopsDB,$xoopsModule,$xoopsTpl;
  $sql = "select * from ".$xoopsDB->prefix("ugm_contact_us")." order by `cu_post_date` desc,`cu_condition`";
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
    //以下會產生這些變數： $cu_sn,$cu_condition,$cu_name,$cu_mail,$cu_tel,$cu_mobile,$cu_time,$cu_service,$cu_content,$cu_completion_date,$cu_post_date
    foreach($all as $k=>$v){
      $$k=$v;
    }

    # ---- 聯絡單位 -----------
    $cu_unit_title=get_cu_unit_title($cu_unit_sn);
    # -------------------------
    $cu_condition=show_cu_condition($cu_condition);


    $all_content[$i]['cu_condition']=$cu_condition;
    $all_content[$i]['cu_name']=$cu_name;
    $all_content[$i]['cu_time']=$cu_time;
    $all_content[$i]['cu_service']=$cu_service;
    $all_content[$i]['cu_unit_title']=$cu_unit_title;
    $all_content[$i]['cu_completion_date']=$cu_completion_date;
    $all_content[$i]['cu_post_date']=$cu_post_date;
    $all_content[$i]['cu_sn']=$cu_sn;
    $i++;
  }

  //--------------------- 引入jquery -------------------------------------
  if(!file_exists(XOOPS_ROOT_PATH."/modules/tadtools/jquery.php")){
    redirect_header("index.php",3, _TAD_NEED_TADTOOLS);
  }
  include_once XOOPS_ROOT_PATH."/modules/tadtools/jquery.php";
  $jquery_path = get_jquery(); //一般只要此行即可

  $xoopsTpl->assign('now_op','list_ugm_contact_us');
  $xoopsTpl->assign('jquery_path',$jquery_path);
  $xoopsTpl->assign('all_content',$all_content);
  $xoopsTpl->assign('bar',$bar);
}



function update_ugm_contact_us($cu_sn=""){
  global $xoopsDB;

  if($_REQUEST['cu_condition']==_MA_COMPLETE){
   $cu_condition=2;
  }elseif($_REQUEST['cu_condition']==_MA_SEND){
   $cu_condition=1;
  }else{
    $cu_condition=0;
  }

  $sql = "update ".$xoopsDB->prefix("ugm_contact_us")." set  `cu_condition` = $cu_condition where cu_sn='$cu_sn'";
  $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  return ;
}

//以流水號秀出某筆ugm_contact_us資料內容
function show_one_ugm_contact_us($cu_sn=""){
  global $xoopsDB,$xoopsModule,$xoopsTpl;
  if(empty($cu_sn))return;


  $cu_sn=intval($cu_sn);
  $sql = "select * from ".$xoopsDB->prefix("ugm_contact_us")." where cu_sn='{$cu_sn}'";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  $all=$xoopsDB->fetchArray($result);

  //以下會產生這些變數： $cu_sn,$cu_condition,$cu_name,$cu_mail,$cu_tel,$cu_mobile,$cu_time,$cu_service,$cu_content,$cu_completion_date,$cu_post_date
  foreach($all as $k=>$v){
    $$k=$v;
    $xoopsTpl->assign($k,$v);
  }
  //預設值設定
  $cu_condition=show_cu_condition($cu_condition);
  $cu_content=nl2br($cu_content);

  //--------------------- 引入jquery -------------------------------------
  if(!file_exists(XOOPS_ROOT_PATH."/modules/tadtools/jquery.php")){
    redirect_header("index.php",3, _TAD_NEED_TADTOOLS);
  }
  include_once XOOPS_ROOT_PATH."/modules/tadtools/jquery.php";
  $jquery_path = get_jquery(); //一般只要此行即可
  # ------------------------------------------------

  $sql = "select * from ".$xoopsDB->prefix("ugm_cu_solution ")." where cu_sn='{$cu_sn}' order by solution_date DESC";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  //$all=$xoopsDB->fetchArray($result);
  $rows = $xoopsDB->getRowsNum($result);

  $all_content="";
  if(!$rows==""){
    $i=0;
    while($all=$xoopsDB->fetchArray($result)){
      //以下會產生這些變數： $cu_sn,$solution_title,$solution_date
      foreach($all as $k=>$v){
        $$k=$v;
      }
      $all_content[$i]['solution_date']=$solution_date;
      $all_content[$i]['solution_title']=$solution_title;
      $i++;
    }

  }

  $xoopsTpl->assign('jquery_path',$jquery_path);
  $xoopsTpl->assign('cu_content',$cu_content);
  $xoopsTpl->assign('cu_condition',$cu_condition);
  $xoopsTpl->assign('all_content',$all_content);
  $xoopsTpl->assign('now_op','show_one_ugm_contact_us');


}
//以流水號取得某筆ugm_contact_us資料
function get_ugm_contact_us($cu_sn=""){
  global $xoopsDB;
  if(empty($cu_sn))return;
  $sql = "select * from ".$xoopsDB->prefix("ugm_contact_us")." where cu_sn='$cu_sn'";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  $data=$xoopsDB->fetchArray($result);
  return $data;
}
//新增資料到ugm_cu_solution中
function insert_ugm_cu_solution(){
  global $xoopsDB;
//die(var_export($_REQUEST));
  # ----過濾表單
  $myts =& MyTextSanitizer::getInstance();
  //-----------------資料過濾
  $cu_sn=intval($_REQUEST['cu_sn']);
  if($_REQUEST['cu_condition']==_MA_COMPLETE){
    $solution_title=_MA_COMPLETE;
  }else{
    $solution_title=$myts->addSlashes($_POST['solution_title']);
  }
  //-----------------------------
  $sql = "insert into ".$xoopsDB->prefix("ugm_cu_solution")." (`cu_sn`,`solution_title`,`solution_date`) values('{$cu_sn}','{$solution_title}',now())";
  //die( $sql);
  $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  return ;
}



//轉換處理狀態
function show_cu_condition($cu_condition=""){
  if($cu_condition==2){
    $cu_condition="<div class='label label-success'>"._MA_UGMCONTACUS_CU_CONDITION_2."</div>";
  }
  elseif($cu_condition==1){
    $cu_condition="<div class='label label-info'>"._MA_UGMCONTACUS_CU_CONDITION_1."</div>";
  }else{
    $cu_condition="<div class='label label-warning'>"._MA_UGMCONTACUS_CU_CONDITION_0."</div>";
  }
  return $cu_condition;

}

//刪除ugm_contact_us某筆資料資料
function delete_ugm_contact_us($cu_sn=""){
  global $xoopsDB;
  $sql = "delete from ".$xoopsDB->prefix("ugm_contact_us")." where cu_sn='$cu_sn'";
  $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  $sql = "delete from ".$xoopsDB->prefix("ugm_cu_solution")." where cu_sn='$cu_sn'";
  $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());

}

?>
