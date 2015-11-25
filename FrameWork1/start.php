<?php

/**
 * Framework 入口文件
 * @category iCMS
 * @package iCMS_FrameWork1
 * @author zhangchi
 */

ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);

mb_internal_encoding('utf8');
date_default_timezone_set('Asia/Shanghai'); //'Asia/Shanghai' 亚洲/上海
//////////////////step 1 include all files///////////////////
require RELATIVE_PATH . "/FrameWork1/include_all.php";


$security = Control::GetRequest("secu", "");
$client = Control::GetRequest("client", "");
if ($security === "manage") {
    $manageUserId = Control::GetManageUserId();
    if ($manageUserId <= 0) {
        die("<script>window.location.href='" . RELATIVE_PATH . "/default.php?mod=manage&a=login';</script>");
    } else {
        echo getManageOutput(new DefaultManageGen());
    }
}
elseif(strlen($client)>0){
    echo getClientOutput(new DefaultClientGen());
}
else {
    echo getOutput(new DefaultPublicGen());
}

/**
 * 利用接口生成对应的前台输出代码
 * @param IBasePublicGen $gen
 * @return string 返回输出代码
 */
function getOutput(IBasePublicGen $gen) {
    header("Content-type: text/html; charset=utf-8");
    return $gen->GenPublic();
}

/**
 * 利用接口生成对应的后台输出代码
 * @param IBaseManageGen $gen
 * @return string 返回输出代码
 */
function getManageOutput(IBaseManageGen $gen) {
    header("Content-type: text/html; charset=utf-8");
    return $gen->Gen();
}

/**
 * 利用接口生成对应的客户端输出代码
 * @param IBaseClientGen $gen
 * @return string 返回输出代码
 */
function getClientOutput(IBaseClientGen $gen) {
    header("Content-type: text/html; charset=utf-8");
    return $gen->GenClient();
}

?>
