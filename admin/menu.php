<?php
//  ------------------------------------------------------------------------ //
// ���Ҳե� ugm �s�@
// �s�@����G2009-02-28
// $Id:$
// ------------------------------------------------------------------------- //
$adminmenu = array();
$icon_dir=substr(XOOPS_VERSION,6,3)=='2.6'?"":"images/";

$i = 1;
$adminmenu[$i]['title'] = _MI_TAD_ADMIN_HOME ;
$adminmenu[$i]['link'] = 'admin/index.php' ;
$adminmenu[$i]['desc'] = _MI_TAD_ADMIN_HOME_DESC ;
$adminmenu[$i]['icon'] = 'images/admin/home.png' ;

$i++;
$adminmenu[$i]['title'] = _MI_UGMCONTACUS_ADMENU1;
$adminmenu[$i]['link']  = "admin/main.php";
$adminmenu[$i]['desc']  = _MI_UGMCONTACUS_ADMENU1 ;
$adminmenu[$i]['icon']  = "images/admin/main.png";

$i++;
$adminmenu[$i]['title'] = _MI_UGMCONTACUS_ADMENU2;
$adminmenu[$i]['link']  = "admin/service.php";
$adminmenu[$i]['desc']  = _MI_UGMCONTACUS_ADMENU2 ;
$adminmenu[$i]['icon']  = "images/admin/service.png";

$i++;
$adminmenu[$i]['title'] = _MI_UGMCONTACUS_ADMENU3;
$adminmenu[$i]['link']  = "admin/unit.php";
$adminmenu[$i]['desc']  = _MI_UGMCONTACUS_ADMENU3 ;
$adminmenu[$i]['icon']  = "images/admin/unit.png";

$i++;
$adminmenu[$i]['title'] = _MI_TAD_ADMIN_ABOUT;
$adminmenu[$i]['link'] = 'admin/about.php';
$adminmenu[$i]['desc'] = _MI_TAD_ADMIN_ABOUT_DESC;
$adminmenu[$i]['icon'] = 'images/admin/about.png';

?>