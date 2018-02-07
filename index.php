<?php
//  ------------------------------------------------------------------------ //
// 本模組由 ugm 製作(www.ugm.com.tw)(tawan158@gmail.com)
// 製作日期：2009-02-28
// 修改日期：1.2->2009-08-21
// $Id:$
// ------------------------------------------------------------------------- //

/*-----------引入檔案區----------------------------------------------------- */
include "header.php";
$xoopsOption['template_main'] = "ugm_contact_us_index_tpl.html";
include_once XOOPS_ROOT_PATH."/header.php";
//============================================================================
/*-----------執行動作判斷區----------*/
$op = (!isset($_REQUEST['op']))? "main":$_REQUEST['op'];

switch($op){
  #---- 表單驗證
  case "captcha":
    echo captcha();
    exit;
	//新增資料
	case "op_insert":
	  $main=op_insert();
	  redirect_header(XOOPS_URL, 3, _MD_SEND_MESSAGE);
	break;

	//預設動作
	default:
	  op_form();
	break;
}

/*-----------秀出結果區--------------*/
$xoopsTpl->assign( "toolbar" , toolbar_bootstrap($interface_menu)) ;
$xoopsTpl->assign( "bootstrap" , get_bootstrap()) ;
$xoopsTpl->assign( "jquery" , get_jquery()) ;
$xoopsTpl->assign( "isAdmin" , $isAdmin) ;
$xoopsTpl->assign( "css" , "<link rel='stylesheet' type='text/css' media='screen' href='".XOOPS_URL."/modules/ugm_contact_us/css/module.css' />") ;
include_once XOOPS_ROOT_PATH.'/footer.php';

/*---------- function區 ------------*/
###############################################################################
#  phpAjax驗證
###############################################################################
function captcha() {
  global $xoopsModuleConfig;
  #---- 過濾讀出的變數值 ----
  $myts = MyTextSanitizer::getInstance();

  $_SESSION['g-recaptcha'] = false;

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $response = $_POST['g-recaptcha-response'];   

    if (!empty($response)) {

      include_once XOOPS_ROOT_PATH . "/modules/ugm_contact_us/GoogleRecaptcha.php";

      #我不是機器人
      $secretkey = $myts->htmlSpecialChars($xoopsModuleConfig['ugm_contact_us_secretkey']);
      $cap = new GoogleRecaptcha($secretkey);
      $verified = $cap->VerifyCaptcha($response);

      if ($verified) {
        //驗證成功
        $_SESSION['g-recaptcha'] = true;        
      }

    }
  }
  return $_SESSION['g-recaptcha'];
}
###############################################################################
#  聯絡表單
#  op_form
#
#
###############################################################################
function op_form(){
  global $xoopsDB,$xoopsUser,$isAdmin,$xoopsModuleConfig,$xoopsTpl;  
  #---- 過濾讀出的變數值 ----
  $myts = MyTextSanitizer::getInstance();

  include_once(XOOPS_ROOT_PATH."/class/xoopsformloader.php");

  $cu_name           =($_SESSION['ugm_contact_us_form'])?$_SESSION['ugm_contact_us_form']['cu_name']:"";
  $cu_mail           =($_SESSION['ugm_contact_us_form'])?$_SESSION['ugm_contact_us_form']['cu_mail']:"";
  $cu_tel            =($_SESSION['ugm_contact_us_form'])?$_SESSION['ugm_contact_us_form']['cu_tel']:"";
  $cu_mobile         =($_SESSION['ugm_contact_us_form'])?$_SESSION['ugm_contact_us_form']['cu_mobile']:"";
  $cu_time           =($_SESSION['ugm_contact_us_form'])?$_SESSION['ugm_contact_us_form']['cu_time']:"";
  $cu_service        =($_SESSION['ugm_contact_us_form'])?$_SESSION['ugm_contact_us_form']['cu_service']:"";
  $cu_content        =($_SESSION['ugm_contact_us_form'])?$_SESSION['ugm_contact_us_form']['cu_content']:"";
  $cu_completion_date=($_SESSION['ugm_contact_us_form'])?$_SESSION['ugm_contact_us_form']['cu_completion_date']:date("Y-m-d" , xoops_getUserTimestamp(strtotime("+7 days")));
  $cu_unit_sn        =($_SESSION['ugm_contact_us_form'])?$_SESSION['ugm_contact_us_form']['cu_unit_sn']:"";

  # ----  聯絡我們資料 -----
  //$ugm_contact_us_info=nl2br($xoopsModuleConfig['ugm_contact_us_info']);
  $DBV['ugm_contact_us_info']=nl2br($xoopsModuleConfig['ugm_contact_us_info']);


  $DBV['ugm_contact_us_recaptcha']=intval($xoopsModuleConfig['ugm_contact_us_recaptcha']);
  $DBV['ugm_contact_us_sitekey']=$myts->htmlSpecialChars($xoopsModuleConfig['ugm_contact_us_sitekey']);
  //$DBV['ugm_contact_us_secretkey']=$myts->htmlSpecialChars($xoopsModuleConfig['ugm_contact_us_secretkey']);


  #---- 我不是機器人  
  $DBV['RecaptchaJs'] = "";
  if ($DBV['ugm_contact_us_recaptcha']) {
    include_once XOOPS_ROOT_PATH . "/modules/ugm_contact_us/GoogleRecaptcha.php";
    $language = "zh-TW";
    $GoogleRecaptchaJs = new GoogleRecaptchaJs($DBV['ugm_contact_us_sitekey'], $language);
    $url = XOOPS_URL."/modules/ugm_contact_us/index.php";
    $DBV['RecaptchaJs'] = $GoogleRecaptchaJs->render($url);
  }
  $_SESSION['g-recaptcha'] = false;
  #----------------------------------------------------------

  # ---- 需要服務項目 ----
  $get_ugm_cu_service=get_ugm_cu_service($cu_service);

  # ----------------------
  # ---- 聯絡單位選項 ----
  $get_ugm_contact_unit_option=get_ugm_contact_unit_option($cu_unit_sn);


  # ---- 聯絡單位 form-----------------
  if($get_ugm_contact_unit_option){
    $get_ugm_contact_unit="
      <!-- 聯絡單位 -->
      <tr class='alt'>
        <th>"._MD_UGMCONTACUS_ADMENU3."</th>
        <td>
          <select name='cu_unit_sn'>
           $get_ugm_contact_unit_option
          </select>
        </td>
      </tr>
    ";
  }
  // # ---- 管理員不用 驗證及填個人資料同意書 -----
  // if($isAdmin){
  //   $form_Captcha="";
  // }else{
  //   # ---- 驗證碼 -----
  //   $configs="";
  //   $Captcha=new XoopsFormCaptcha ("", 'xoopscaptcha', false, $configs);
  //   # ---- 輸出HTML碼 ------------------------------------------
  //   $DBV['form_Captcha'] = $Captcha->render();
  //   # ----------------------------------------------------------
  // }

  # ---- 驗證碼 ----
  if(!file_exists(TADTOOLS_PATH."/formValidator.php")){
   redirect_header("index.php",3, _MD_NEED_TADTOOLS);
  }
  include_once TADTOOLS_PATH."/formValidator.php";
  $formValidator= new formValidator("#myForm",true);
  $formValidator_code=$formValidator->render();
  # ----------------

  # ---- 表單Token -------------------------------------------
  $Token = new XoopsFormHiddenToken();
  $DBV['form_Token'] = $Token->render();
  # ----------------------------------------------------------

  $DBV['js_code']=$formValidator_code."
           <script type='text/javascript' src='".TADTOOLS_URL."/My97DatePicker/WdatePicker.js'></script>";

  #FORMHEAD
  $DBV['form_head']=array(
    'action'  => $_SERVER['PHP_SELF'],
    'method'  => 'post',
    'id'      => 'myForm',
    'class'   => '',
    'enctype' => '',
    'title'   => _MD_UGMCONTACUS_ADMENU1
  );

  #FORMBODY，以列為單位
  $DBV['form_body'][]=array(
    #---- 姓名 ----
    array(
      'name'      =>'cu_name',
      'title'     =>"* "._MD_UGMCONTACUS_CU_NAME,
      'desc'      =>'',
      'type'      =>'text',
      'value'     =>$cu_name,
      'validator' =>'validate[required]',
      'placeholder'  => _MD_UGMCONTACUS_CU_NAME ,
      'width'     =>'3'
    ),
    #---- 日期 ----
    array(
      'name'      =>'cu_completion_date',
      'title'      =>_MD_UGMCONTACUS_CU_COMPLETION_DATE,
      'desc'      =>'',
      'type'      => 'Wdate',# ---- 日期下拉選單 ----
      'value'     =>$cu_completion_date,
      'validator' =>'validate[required]',
      'placeholder'  => _MD_UGMCONTACUS_CU_COMPLETION_DATE ,
      'width'     =>'3',
      'isShowClear' => 'false',//是否可以清除
      'readOnly'   => 'true'  //是否唯讀
    ),
    # ---- e-mail ----
    array(
      'name'      =>'cu_mail',
      'title'      =>"* "._MD_UGMCONTACUS_CU_MAIL,
      'desc'      =>'',
      'type'      => 'text',
      'value'     =>$cu_mail,
      'validator' =>'validate[required ,custom[email]]',
      'placeholder'  => _MD_UGMCONTACUS_CU_MAIL ,
      'width'     =>'6'
    )
  );

  $DBV['form_body'][]=array(
    # ---- 聯絡電話 ----
    array(
      'name'        =>'cu_tel',
      'title'       =>"* "._MD_UGMCONTACUS_CU_TEL,
      'desc'        =>'',
      'type'        => 'text',
      'value'       =>$cu_tel,
      'validator'   =>'validate[required]',
      'placeholder' =>_MD_UGMCONTACUS_CU_TEL ,
      'width'       =>'4'
    ),
    # ---- 行動電話 ----
    array(
      'name'        =>'cu_mobile',
      'title'       =>_MD_UGMCONTACUS_CU_MOBILE,
      'desc'        =>'',
      'type'        => 'text',
      'value'       =>$cu_mobile,
      'validator'   =>'',
      'placeholder' => _MD_UGMCONTACUS_CU_MOBILE ,
      'width'       =>'4'
    ),
    # ---- 適合聯絡時間 ----
    array(
      'name'      =>'cu_time',
      'title'      =>_MD_UGMCONTACUS_CU_TIME,
      'desc'      =>'',
      'type'      => 'text',
      'value'     =>$cu_time,
      'validator' =>'',
      'placeholder' =>_MD_UGMCONTACUS_CU_TIME ,
      'width'     =>'4'
    )
  );


  if($get_ugm_contact_unit_option and $get_ugm_cu_service){

    $DBV['form_body'][] =array(
      /*
       select
       [0] => Array ( [title] => 人事部 [value] => 1 [selected] =>
      */
      # ---- 聯絡單位 ----
      array(
        'name'      =>'cu_unit_sn',
        'title'      =>_MD_UGMCONTACUS_ADMENU3,
        'desc'      =>'',
        'type'      => 'select',
        'value'     =>$get_ugm_contact_unit_option,
        'validator' =>'',
        'placeholder' =>'',
        'width'     =>'4'
      ),
      /*
        checkbox
        [0] => Array ( [label] => cu_service_0 [value] => aaa [checked] =>
      */
      # ---- 需要服務項目 ----
      array(
       'name'        =>'cu_service[]',
       'title'       =>_MD_UGMCONTACUS_CU_SERVICE,
       'desc'        =>'',
       'type'        =>'checkbox',
       'value'       =>$get_ugm_cu_service,
       'validator'   =>'',
       'placeholder' =>'',
       'width'     =>'8'
      )
    );

  }elseif($get_ugm_contact_unit_option){

    $DBV['form_body'][] =array(
      /*
       select
       [0] => Array ( [title] => 人事部 [value] => 1 [selected] =>
      */
      # ---- 聯絡單位 ----
      array(
        'name'      =>'cu_unit_sn',
        'title'      =>_MD_UGMCONTACUS_ADMENU3,
        'desc'      =>'',
        'type'      => 'select',
        'value'     =>$get_ugm_contact_unit_option,
        'validator' =>'',
        'placeholder' =>'',
        'width'     =>'4'
      )
    );

  }elseif($get_ugm_cu_service){
    $DBV['form_body'][] =array(
      /*
        checkbox
        [0] => Array ( [label] => cu_service_0 [value] => aaa [checked] =>
      */
      # ---- 需要服務項目 ----
      array(
       'name'        =>'cu_service[]',
       'title'       =>_MD_UGMCONTACUS_CU_SERVICE,
       'desc'        =>'',
       'type'        =>'checkbox',
       'value'       =>$get_ugm_cu_service,
       'validator'   =>'',
       'placeholder' =>'',
       'width'     =>'8'
      )
    );

  }

  $DBV['form_body'][]=array(
    # ---- 詳細內容 ----
    array(
      'name'      =>'cu_content',
      'title'      =>"* "._MD_UGMCONTACUS_CU_CONTENT,
      'desc'      =>'',
      'type'      => 'textarea',
      'value'     =>$cu_content,
      'validator' =>'validate[required]]',
      'width'     =>'12'
    )
  );

  $DBV['form_body']['hidden'] = array(
    # ---- op ----
    array(
      'name'        =>'op',
      'title'       =>'',
      'desc'        =>'',
      'type'        =>'hidden',
      'value'       =>"op_insert",
      'validator'   =>'',
      'placeholder' =>'' ,
      'width' =>''
    ),
    # ---- 流水號 ----
    array(
      'name'        =>'cu_sn',
      'title'       =>'',
      'desc'        =>'',
      'type'        => 'hidden',
      'value'       =>"",
      'validator'   =>'',
      'placeholder' =>'',
      'width' =>''
    )
  );


  # 將資料傳給樣板
  $xoopsTpl->assign('DBV',$DBV);

  return;


}
###############################################################################
#  得到單位信箱
#  get_cu_unit_email
#
#
###############################################################################
function get_cu_unit_email($default=""){
	global $xoopsDB;
  $sql="select email
        from ".$xoopsDB->prefix("ugm_contact_unit")."
        where sn='{$default}'
        ";// die($sql);
	$result=$xoopsDB->query($sql);
	list($email)=$xoopsDB->fetchRow($result);
  return $email;
}
###############################################################################
#  立即寄出
#
#
#
###############################################################################
function ugm_contact_us_send_now($emails, $subject,$content){
	global $xoopsConfig,$xoopsDB;
	//sendMail($email, $subject, $body, $headers)
	$xoopsMailer =& getMailer();
	$xoopsMailer->multimailer->ContentType="text/html";
	$xoopsMailer->addHeaders("MIME-Version: 1.0");

	$email_arr=explode(";",$emails);
	foreach($email_arr as $email){
    $email=trim($email);
  	if(!empty($email)){
  	  $xoopsMailer->sendMail($email,$subject, $content,$headers);
    }
  }

}
###############################################################################
#  聯絡單位選擇選項
#  op_insert
#
#
###############################################################################
function op_insert(){
	global $xoopsDB,$xoopsConfig,$isAdmin,$xoopsModuleConfig;
  $myts =& MyTextSanitizer::getInstance();
  $cu_completion_date = $myts->addSlashes($_POST['cu_completion_date']);

  # ---- 檢查Token -----------------------------------------------------
  if (!$GLOBALS['xoopsSecurity']->check()) {
    $error=implode('<br />', $GLOBALS['xoopsSecurity']->getErrors());
    redirect_header(XOOPS_URL, 3, $error);
  }
  #-------------------------------------我不是機器人驗證
  
  $xoopsModuleConfig['ugm_contact_us_recaptcha'] = intval($xoopsModuleConfig['ugm_contact_us_recaptcha']);

  #後台驗證
  if (!$_SESSION['g-recaptcha'] and $xoopsModuleConfig['ugm_contact_us_recaptcha']) {
    redirect_header(XOOPS_URL, 3, "驗證錯誤！！");
  }
  $_SESSION['g-recaptcha'] = false;
  #---------------------------------------------------------------

  # ----解除搜尋 $_SESSION
  unset($_SESSION['ugm_contact_us_form']);
  # ---------------------------

  # ----過濾表單
	$myts =& MyTextSanitizer::getInstance();

  $cu_service=$myts->addSlashes(implode(",",$_POST['cu_service']));
  $cu_name=$myts->addSlashes($_POST['cu_name']);
  $cu_mail=$myts->addSlashes($_POST['cu_mail']);
  $cu_tel=$myts->addSlashes($_POST['cu_tel']);
  $cu_mobile=$myts->addSlashes($_POST['cu_mobile']) ;
  $cu_time=$myts->addSlashes($_POST['cu_time']);
  $cu_unit_sn=intval($_POST['cu_unit_sn']);
  $cu_content=$myts->addSlashes($_POST['cu_content']);
  $cu_completion_date=$myts->addSlashes($_POST['cu_completion_date']);
  //--------------------------------------------------------------------------
  # ---- 獲得填報者ip ----
  if (!empty($_SERVER["HTTP_CLIENT_IP"])){
    $ip = $_SERVER["HTTP_CLIENT_IP"];
  }elseif(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
      $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
  }else{
      $ip = $_SERVER["REMOTE_ADDR"];
  }
  # ---------------------------------------------------------------------------

	$sql = "insert into ".$xoopsDB->prefix("ugm_contact_us")."
          (`cu_name`,`cu_mail`,`cu_tel`,`cu_mobile`,`cu_time`,`cu_service`,`cu_content`,`cu_completion_date`,`cu_post_date`,`cu_unit_sn`,`ip`)
          values
          ('{$cu_name}','{$cu_mail}','{$cu_tel}','{$cu_mobile}','{$cu_time}','{$cu_service}','{$cu_content}','{$cu_completion_date}',now(),'{$cu_unit_sn}','{$ip}')";
	$xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  # ---- 得到流水號 -----
	$cu_sn=$xoopsDB->getInsertId();

  # ====  處理寄信 ============================================================

  # ---- 聯絡單位 -----------
  $cu_unit_title=get_cu_unit_title($cu_unit_sn);
  # -------------------------

  # ---- 管理員 -----
  $mail_admin=$xoopsModuleConfig['ugm_contact_us_mailTo']?$xoopsModuleConfig['ugm_contact_us_mailTo']:$xoopsConfig[adminmail];
  # ----------------

  # ---- 標題 -----
  $subject = (empty($xoopsModuleConfig['ugm_contact_us_subject']))? "ugm_contact_us_subject":$xoopsModuleConfig['ugm_contact_us_subject'];
  $subject = $cu_unit_sn?$subject."_".$cu_unit_title:$subject;
  # ---- 內容 --------
  $path=XOOPS_URL."/modules/ugm_contact_us/admin/main.php?op=show_one_ugm_contact_us&cu_sn={$cu_sn}";
  $admin_form=$isAdmin?"<tr><th colspan=2><a href='{$path}'>"._MD_UGMCONTACUS_MANAGEMENT."</a></th></tr>":"";
  $cu_content=nl2br($cu_content);
  $main="
     <table border=1 align='center' cellspacing='0' style='width:600px'>
      <tr><th style='width:150px'>"._MD_UGMCONTACUS_CU_NAME."</th><td >{$cu_name}&nbsp;</td></tr>
      <tr><th>"._MD_UGMCONTACUS_CU_MAIL."</th><td>{$cu_mail}&nbsp;</td></tr>
      <tr><th>"._MD_UGMCONTACUS_CU_TEL."</th><td>{$cu_tel}&nbsp;</td></tr>
      <tr><th>"._MD_UGMCONTACUS_CU_MOBILE."</th><td>{$cu_mobile}&nbsp;</td></tr>
      <tr><th>"._MD_UGMCONTACUS_CU_TIME."</th><td>{$cu_time}&nbsp;</td></tr>
      <tr><th>"._MD_UGMCONTACUS_CU_SERVICE."</th><td>{$cu_service}&nbsp;</td></tr>
      <tr><th>"._MD_UGMCONTACUS_ADMENU3."</th><td>{$cu_unit_title}&nbsp;</td></tr>
      <tr><th>"._MD_UGMCONTACUS_CU_CONTENT."</th><td>{$cu_content}</td></tr>
      <tr><th>"._MD_UGMCONTACUS_CU_COMPLETION_DATE."</th><td>$cu_completion_date</td></tr>
      <tr><th>"._MD_UGMCONTACUS_CU_POST_DATE."</th><td>".date("Y-m-d")."&nbsp;</td></tr>
      <tr><th>FROM IP</th><td>{$ip}</td></tr>
      $admin_form
     </table>";
  if($cu_unit_sn){
    # ---- 聯絡單位e-main -----------
    $cu_unit_email=get_cu_unit_email($cu_unit_sn);
    # -------------------------
    # ---寄信給單位管理員 ----
    ugm_contact_us_send_now($cu_unit_email, $subject,$main);
  }
  # ---寄信給管理員 ----
  ugm_contact_us_send_now($mail_admin, $subject,$main);
  # ---寄信給填報者 ----
  ugm_contact_us_send_now($cu_mail, $subject,$main);
}
###############################################################################
# get_ugm_cu_service()
# 得到服務項目選單
#
#
##############################################################################
function get_ugm_cu_service($cu_service){
	global $xoopsDB;
  $sql = "select `service_name`
          from ".$xoopsDB->prefix("ugm_cu_service");
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  $i=0;
  while($all=$xoopsDB->fetchArray($result)){
	  foreach($all as $k=>$v){
      $$k=$v;
    }
    $checked=in_array($v,$cu_service)?" checked":"";
		$option[]=array(
      "label"   =>"cu_service_{$i}",
      "value"   =>$v,
      "checked" =>$checked
    );
    $i++;
	}
	return $option;
}
###############################################################################
#  聯絡單位選擇選項
#  get_ugm_contact_unit_option
#
#
###############################################################################
function get_ugm_contact_unit_option($cu_unit_sn=""){
	global $xoopsDB;
  $sql="select sn,title
        from ".$xoopsDB->prefix("ugm_contact_unit");
	$result=$xoopsDB->query($sql);
	while(list($sn,$title)=$xoopsDB->fetchRow($result)){
    $selected=($cu_unit_sn==$sn)?" selected":"";
    //$option.="<option value='{$sn}' {$selected}>{$title}</option>";
		$option[]=array(
      "title"   =>$title,
      "value"   =>$sn,
      "selected" =>$selected
    );
  }
  return $option;
}
###############################################################################
# get_ugm_cu_service()
# 得到服務項目選單
#
#
##############################################################################
function get_ugm_cu_service1($cu_service){
	global $xoopsDB;
  $sql = "select `service_name`
          from ".$xoopsDB->prefix("ugm_cu_service");
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  $i=0;
  while($all=$xoopsDB->fetchArray($result)){
	  foreach($all as $k=>$v){
      $$k=$v;
    }
    $checked=in_array($v,$cu_service)?" checked":"";
		$option.="<label for='cu_service_{$i}'><input type='checkbox' name='cu_service[]'  value={$v}  id='cu_service_{$i}' {$checked}>&nbsp;{$v} </label><br />";
    $i++;
	}
	return $option;
}
###############################################################################
#  聯絡單位選擇選項
#  get_ugm_contact_unit_option
#
#
###############################################################################
function get_ugm_contact_unit_option1($cu_unit_sn=""){
	global $xoopsDB;
  $sql="select sn,title
        from ".$xoopsDB->prefix("ugm_contact_unit");
	$result=$xoopsDB->query($sql);
	while(list($sn,$title)=$xoopsDB->fetchRow($result)){
    $selected=($cu_unit_sn==$sn)?" selected":"";
    $option.="<option value='{$sn}' {$selected}>{$title}</option>";
  }
  return $option;
}
?>
