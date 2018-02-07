<?php
/**
 * 寄信函數
 * @param string $title 信件標題
 * @param string $content 信件內容，可含HTML
 * @param string $to_mode 寄信對象，uid（使用者uid編號）、group（群組編號）、email（單一email）、email_array（Email陣列）
 * @param string $to 寄信對象的值（如uid編號、群組編號或Email...等）
 * @param string $mode 寄發模式，email 或 pm
 * @param string $tpl 樣板檔名稱，需放在語系的mail_template下
 */
function mail_to_user($title,$content="",$to_mode="group",$to="2",$mode="email",$tpl="tpl.html")
{
  global $xoopsConfig , $xoopsModule;

  $dirname=$xoopsModule->dirname();
  $xoopsMailer =& getMailer();

  if($mode=="email"){
    $xoopsMailer->useMail();
  }else{
    $xoopsMailer->usePM();
  }

  $xoopsMailer->setTemplateDir(XOOPS_ROOT_PATH."/modules/{$dirname}/language/{$xoopsConfig['language']}/mail_template/");
  $xoopsMailer->setTemplate($tpl);

  $xoopsMailer->assign('CONTENT', $content);

  if($to_mode=="uid"){
    $xoopsMailer->setToUsers(new XoopsUser($to));
  }elseif($to_mode=="group"){
    $member_handler =& xoops_gethandler('member');
    $xoopsMailer->setToGroups($member_handler->getGroup($to));
  }elseif($to_mode=="email"){
    $xoopsMailer->setToEmails($to);
  }elseif($to_mode=="email_array"){
    $xoopsMailer->setToEmails($to);
  }

  $xoopsMailer->setFromEmail($xoopsConfig['adminmail']);
  $xoopsMailer->setFromName($xoopsConfig['sitename']);
  $xoopsMailer->setSubject($title);
  $xoopsMailer->multimailer->isHTML(true);

  if ( !$xoopsMailer->send(true) ) {
    $error=$xoopsMailer->getErrors(false);
    $error=implode(" ",$error);
    redirect_header('index.php', 3, "送信失敗！{$error}");
  } else {
    redirect_header('index.php', 3, "送信成功！");
  }
}
?>
