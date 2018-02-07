<?php
function xoops_module_update_ugm_contact_us(&$module, $old_version) {
    GLOBAL $xoopsDB;

    if(!chk_ugm1()) go_ugm1();   # 2.0  -> 增加  ugm_contact_unit
    if(!chk_ugm2()) go_ugm2();   # 2.0  -> 增加  ugm_contact_us ->  cu_unit_sn
    if(!chk_ugm3()) go_ugm3();   # 2.0  -> 增加  ugm_contact_us ->  ip
    return true;
}

# -------------------- ugm --------------------------------------------------*/
# -------1----------------
function chk_ugm1(){
	global $xoopsDB;
	$sql="select count(`sn`) from ".$xoopsDB->prefix("ugm_contact_unit");
	$result=$xoopsDB->query($sql);
	if(empty($result)) return false;
	return true;
}
function go_ugm1(){
	global $xoopsDB;
	$sql="CREATE TABLE ".$xoopsDB->prefix("ugm_contact_unit ")." (
  `sn` smallint(5) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`sn`)
  ) ENGINE=MyISAM;";
  $xoopsDB->query($sql);
}
# -------2 增加欄位----------------
function chk_ugm2(){
	global $xoopsDB;
	$sql="select count(`cu_unit_sn`) from ".$xoopsDB->prefix("ugm_contact_us");
	$result=$xoopsDB->query($sql);
	if(empty($result)) return false;
	return true;
}
function go_ugm2(){
	global $xoopsDB;
	$sql="ALTER TABLE ".$xoopsDB->prefix("ugm_contact_us")." ADD `cu_unit_sn` smallint(5) unsigned NOT NULL default '0' ";
	$xoopsDB->queryF($sql) or redirect_header(XOOPS_URL,3,  mysql_error());
}
# -------3----------------
function chk_ugm3(){
	global $xoopsDB;
	$sql="select count(`ip`) from ".$xoopsDB->prefix("ugm_contact_us");
	$result=$xoopsDB->query($sql);
	if(empty($result)) return false;
	return true;
}
function go_ugm3(){
	global $xoopsDB;
	$sql="ALTER TABLE ".$xoopsDB->prefix("ugm_contact_us")." ADD `ip` varchar(255) NOT NULL default ''  ";
	$xoopsDB->queryF($sql) or redirect_header(XOOPS_URL,3,  mysql_error());
}
?>
