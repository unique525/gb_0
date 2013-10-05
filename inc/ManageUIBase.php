<?php
include_once(ROOTPATH."/inc/UIBase.php");
$adminuserid = Control::GetAdminUserID();
if($adminuserid<=0){
    Control::GoUrl(ROOTPATH."/manage/login.php");
}
?>
