<?php
//  ------------------------------------------------------------------------ //
// ���Ҳե� ugm �s�@
// �s�@����G2009-02-28
// $Id:$
// ------------------------------------------------------------------------- //
//�ޤJTadTools���禡�w
if(!file_exists(XOOPS_ROOT_PATH."/modules/tadtools/tad_function.php")){
 redirect_header("http://www.tad0616.net/modules/tad_uploader/index.php?of_cat_sn=50",3, _TAD_NEED_TADTOOLS);
}
include_once XOOPS_ROOT_PATH."/modules/tadtools/tad_function.php";
include_once "block_function.php";


###############################################################################
#  �o������D
#  get_cu_unit_title
#
#
###############################################################################
function get_cu_unit_title($default=""){
	global $xoopsDB;
  $sql="select title
        from ".$xoopsDB->prefix("ugm_contact_unit")."
        where sn='{$default}'
        ";// die($sql);
	$result=$xoopsDB->query($sql);
	list($title)=$xoopsDB->fetchRow($result);

  return $title;
}




?>