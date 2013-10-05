<?php
/**
 * Framework 入口文件
 * @category iCMS
 * @package iCMS_FrameWork1
 * @author zhangchi
 */
mb_internal_encoding('utf8');
date_default_timezone_set('Asia/Shanghai'); //'Asia/Shanghai' 亚洲/上海
//////////////////step 1 include all files///////////////////
require ROOTPATH . "/FrameWork1/include_all.php";

$security = Control::GetRequest("secu", "");
if ($security === "manage") {
    $adminUserId = Control::GetAdminUserId();
    if ($adminUserId <= 0) {
        Control::GoUrl(ROOTPATH . "/default.php?mod=manage&a=login");
    }else{
        echo getManageHtml(new DefaultManageGen());
    }
} else {    
    echo getHtml(new DefaultFrontGen());
}

/**
 * 利用接口生成对应的前台HTML代码
 * @param IBaseFrontGen $gen
 * @return string 返回HTML结果
 */
function getHtml(IBaseFrontGen $gen) {
    header("Content-type: text/html; charset=utf-8");
    return $gen->GenFront();
}

/**
 * 利用接口生成对应的后台HTML代码
 * @param IBaseManageGen $gen
 * @return string 返回HTML结果
 */
function getManageHtml(IBaseManageGen $gen) {
    header("Content-type: text/html; charset=utf-8");
    return $gen->Gen();
}

?>
