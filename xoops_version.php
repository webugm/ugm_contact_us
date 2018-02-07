<?php
//  ------------------------------------------------------------------------ //
// 本模組由 ugm 製作
// 製作日期：2009-02-28
// $Id:$
// ------------------------------------------------------------------------- //

$modversion = array();

//---模組基本資訊---//
$modversion['name'] = _MI_UGMCONTACUS_NAME;
$modversion['version'] = '2.6';
$modversion['description'] = _MI_UGMCONTACUS_DESC;
$modversion['author'] = _MI_UGMCONTACUS_AUTHOR;
$modversion['credits'] = _MI_UGMCONTACUS_CREDITS;
$modversion['help'] = 'page=help';
$modversion['license'] = 'GPL see LICENSE';
$modversion['license_url'] = 'www.ugm.com.tw';
$modversion['image'] = "images/logo.png";
$modversion['dirname'] = basename(dirname(__FILE__));

//---模組狀態資訊---//
$modversion['release_date'] = '2017-01-14';
$modversion['module_website_url'] = 'http://www.ugm.com.tw/';
$modversion['module_website_name'] = 'UGM';
$modversion['module_status'] = 'RC';
$modversion['author_website_url'] = 'http://www.ugm.com.tw/';
$modversion['author_website_name'] = 'UGM';
$modversion['min_php'] = 5.6;
$modversion['min_xoops'] = '2.57';
$modversion['min_db'] = array('mysql' => '5.3', 'mysqli' => '5.3');

//---paypal資訊---//
$modversion['paypal'] = array();
$modversion['paypal']['business'] = 'tawan158@gmail.com';
$modversion['paypal']['item_name'] = 'Donation : ' . _MI_UGMCONTACUS_NAME;
$modversion['paypal']['amount'] = 0;
$modversion['paypal']['currency_code'] = 'USD';

//---啟動後台管理界面選單---//
$modversion['system_menu'] = 1;

//---資料表架構---//
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";
$modversion['tables'][1] = "ugm_contact_us";
$modversion['tables'][2] = "ugm_cu_service";
$modversion['tables'][3] = "ugm_cu_solution";
$modversion['tables'][4] = "ugm_contact_unit";

//---管理介面設定---//
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";

//---使用者主選單設定---//
$modversion['hasMain'] = 1;
//---樣板設定---//
$modversion['templates'] = array();
$i = 1;
$modversion['templates'][$i]['file'] = 'ugm_contact_us_index_tpl.html';
$modversion['templates'][$i]['description'] = 'ugm_contact_us_index_tpl.html';
$i++;
$modversion['templates'][$i]['file'] = 'ugm_contact_us_adm_main_tpl.html';
$modversion['templates'][$i]['description'] = 'ugm_contact_us_adm_main_tpl.html';
$i++;
$modversion['templates'][$i]['file'] = 'ugm_contact_us_adm_service_tpl.html';
$modversion['templates'][$i]['description'] = 'ugm_contact_us_adm_service_tpl.html';
$i++;
$modversion['templates'][$i]['file'] = 'ugm_contact_us_adm_unit_tpl.html';
$modversion['templates'][$i]['description'] = 'ugm_contact_us_adm_unit_tpl.html';

//---區塊設定---//
/*
$modversion['blocks'][1]['file'] = "ugm_contact_us_b1.php";
$modversion['blocks'][1]['name'] = _MI_UGMCONTACUS_BNAME1;
$modversion['blocks'][1]['description'] = _MI_UGMCONTACUS_BDESC1;
$modversion['blocks'][1]['show_func'] = "ugm_contact_us_b1";
$modversion['blocks'][1]['template'] = "ugm_contact_us_b1.html";
$modversion['blocks'][1]['edit_func'] = "ugm_contact_us_b1_edit";
$modversion['blocks'][1]['options'] = "";
 */

//---偏好設定---//
$modversion['config'][1]['name'] = 'ugm_contact_us_mailTo';
$modversion['config'][1]['title'] = '_MI_MAILTO';
$modversion['config'][1]['description'] = '_MI_MAILTO_DESC';
$modversion['config'][1]['formtype'] = 'textbox';
$modversion['config'][1]['valuetype'] = 'text';
$modversion['config'][1]['default'] = $xoopsConfig['adminmail'];

$modversion['config'][2]['name'] = 'ugm_contact_us_info';
$modversion['config'][2]['title'] = '_MI_UGMCONTACTUS_INFO';
$modversion['config'][2]['description'] = '_MI_UGMCONTACTUS_INFO_DESC';
$modversion['config'][2]['formtype'] = 'textarea';
$modversion['config'][2]['valuetype'] = 'text';
$modversion['config'][2]['default'] = "";

$modversion['config'][3]['name'] = 'ugm_contact_us_subject';
$modversion['config'][3]['title'] = '_MI_UGMCONTACTUS_SUBJECT';
$modversion['config'][3]['description'] = '_MI_UGMCONTACTUS_SUBJECT_DESC';
$modversion['config'][3]['formtype'] = 'textbox';
$modversion['config'][3]['valuetype'] = 'text';
$modversion['config'][3]['default'] = "ugm_contact_us_subject";

$i=4;
$modversion['config'][$i]['name'] = 'ugm_contact_us_recaptcha';
$modversion['config'][$i]['title'] = '_MI_UGMCONTACTUS_RECAPTCHA_ENABLE';
$modversion['config'][$i]['description'] = '_MI_UGMCONTACTUS_RECAPTCHA_ENABLE_DESC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = "";

$i=5;
$modversion['config'][$i]['name'] = 'ugm_contact_us_sitekey';
$modversion['config'][$i]['title'] = '_MI_UGMCONTACTUS_RECAPTCHA_SITEKEY';
$modversion['config'][$i]['description'] = '_MI_UGMCONTACTUS_RECAPTCHA_SITEKEY_DESC';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = "";

$i=6;
$modversion['config'][$i]['name'] = 'ugm_contact_us_secretkey';
$modversion['config'][$i]['title'] = '_MI_UGMCONTACTUS_RECAPTCHA_SECRETKEY';
$modversion['config'][$i]['description'] = '_MI_UGMCONTACTUS_RECAPTCHA_SECRETKEY_DESC';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = "";



//---模組自動功能---//
#$modversion['onInstall'] = "include/onInstall.php";
$modversion['onUpdate'] = "include/onUpdate.php";
#$modversion['onUninstall'] = "include/onUninstall.php";