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
require RELATIVE_PATH . "/FrameWork1/include_all.php";
$security = Control::GetRequest("secu", "");
if ($security === "manage") {
    $adminUserId = Control::GetManageUserId();
    if ($adminUserId <= 0) {
        die("<script>window.location.href='" . RELATIVE_PATH . "/default.php?mod=manage&a=login';</script>");
    } else {
        echo getManageHtml(new DefaultManageGen());
    }
} else {
    echo getHtml(new DefaultPublicGen());
}

/**
 * 利用接口生成对应的前台HTML代码
 * @param IBasePublicGen $gen
 * @return string 返回HTML结果
 */
function getHtml(IBasePublicGen $gen) {
    header("Content-type: text/html; charset=utf-8");
    return $gen->GenPublic();
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
