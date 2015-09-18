<?php
define('RELATIVE_PATH', '../..');
define('PHYSICAL_PATH', str_ireplace('default.php','',realpath(__FILE__)));
define("CACHE_PATH", "cache");
mb_internal_encoding('utf8');
date_default_timezone_set('Asia/Shanghai'); //'Asia/Shanghai' 亚洲/上海
//////////////////step 1 include all files///////////////////
require RELATIVE_PATH . "/FrameWork1/include_all.php";

include_all();

$result = "";
$alipay = new Alipay();
$siteId = GetSiteIdByDomain();
if($siteId>0){
    $alipayConfig = $alipay->Init($siteId);
    $result = $alipay->ReturnUrl(
        $alipayConfig
    );
}
//echo $result;



function GetSiteIdByDomain() {
    $host = strtolower($_SERVER['HTTP_HOST']);
    $host = str_ireplace("http://", "", $host);
    if ($host === "localhost" || $host === "127.0.0.1") {
        $siteId = 1;
    } else {

        //先查绑定的一级域名
        $domain = Control::GetDomain(strtolower($_SERVER['HTTP_HOST']));
        $sitePublicData = new SitePublicData();
        $siteId = $sitePublicData->GetSiteIdByBindDomain($domain, true);

        if($siteId<=0){
            //查子域名
            $arrSubDomain = explode(".", $host);
            if (count($arrSubDomain) > 0) {
                $subDomain = $arrSubDomain[0];

                if (strlen($subDomain) > 0) {

                    $siteId = $sitePublicData->GetSiteId($subDomain, true);
                }
            }
        }

    }
    return $siteId;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>支付结果</title>
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
    <link href="/images/common_css.css" rel="stylesheet" type="text/css"/>
    <script type="text/javascript" src="/system_js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="/system_js/common.js"></script>
    <script type="text/javascript" src="/system_js/jquery_ui/jquery-ui-1.8.2.custom.min.js"></script>
    <script type="text/javascript" src="/system_js/json.js"></script>
    <script type="text/javascript">

        $(function () {
            $("#btn_submit1").click(function () {
                window.location.href = "/";
            });

            $("#btn_submit2").click(function () {
                window.location.href = "/default.php?mod=user_order&a=list";
            });
        });
    </script>
</head>

<body>

<div class="wrapper">
    <div class="logo"><a href="/"><img src="/images/mylogo.png" border="0" /></a></div>
    <div class="step">
        <div class="step1"><img src="/images/wdgwc.png" width="49" height="49"/></div>
        <div class="steptext"><font class="grey">第一步</font><br/><font class="green">我的购物车</font></div>
        <div class="angle"></div>
        <div class="step1"><img src="/images/txhddd.png" width="49" height="49"/></div>
        <div class="steptext"><font class="grey">第二步</font><br/><font class="orange">填写核对订单</font></div>
        <div class="angle"></div>
        <div class="step1"><img src="/images/tjcg.png" width="49" height="49"/></div>
        <div class="steptext"><font class="grey">第三步</font><br/><font class="grey2">提交成功</font></div>
    </div>
</div>

</div>
<div class="clean"></div>
<div class="mainbav">
    <div class="wrapper">
        <div id="leftmenu"><ul><li><span><a href="/default.php?mod=user&a=homepage">会员中心</a></span></li></ul></div>
        <div class="column1"><a href="/">首页</a></div>
        <div class="column2"><a href="/discount.html">特价量贩</a></div>
        <!--
        <div class="column2"><a href="">团购</a></div>
        <div class="column2"><a href="">最新预售</a></div>
        <div class="new"><img src="/images/icon_new.png" width="29" height="30"/></div>
        -->
    </div>
</div>

<div class="wrapper3">
    <div class="new_buy">

        <div class="new_con">
            <div class="new_con_tit">支付<br/>结果</div>
            <div class="new_con_box">
                <div class="new_con_arw"><img src="/images/cart_n_arrowl.gif" width="11" height="21" alt=""/></div>
                <div class="new_commodity">

                    <div id="pay_method_2">

                        <h2 style="line-height:300%;font-size:24px;">您的订单信息已经成功付款，我们会及时给您发货，请耐心等待！</h2>

                        <input id="btn_submit1" style="cursor:pointer;" type="button" class="btn_submit" value="转到首页"/>
                        <br /><br />
                        <input id="btn_submit2" style="cursor:pointer;" type="button" class="btn_submit" value="转到我的订单"/>

                    </div>


                </div>
                <div class="clean"></div>
            </div>
        </div>


        <div class="new_all_submit" style="text-align:left;">

            <div class="red">温馨提示！</div>
            <div style="padding-top:15px;">

            </div>
        </div>

    </div>
</div>
<div class="clean"></div>

<div class="foot">
    <div class="footerline"></div>
    <div class="wrapper">
        <div class="footerleft">
            <div class="cont">
                <div><img src="/images/footergwzn.png" width="79" height="79" /></div>
                <b>交易条款</b><br />
                <a href="/h/4/1.html" target="_blank">购物流程</a><br />
                <a href="/h/4/2.html" target="_blank">发票制度</a><br />
                <a href="/h/4/3.html" target="_blank">会员等级</a><br />
                <a href="/h/4/4.html" target="_blank">积分制度</a><br /><br />
            </div>
        </div>
        <div class="footerleft">
            <div class="cont">
                <div><img src="/images/footerpsfw.png" width="79" height="79" /></div>
                <b>配送服务</b><br />
                <a href="/h/4/5.html" target="_blank">配送说明</a><br />
                <a href="/h/4/6.html" target="_blank">配送范围</a><br />
                <a href="/h/4/7.html" target="_blank">配送状态查询</a><br /><br /><br />
            </div>
        </div>
        <div class="footerleft">
            <div class="cont">
                <div><img src="/images/footerzffs.png" width="79" height="79" /></div>
                <b>支付方式</b><br />
                支付宝支付<br />
                银联在线支付<br />
                货到付款<br /><br /><br />
            </div>
        </div>
        <div class="footerleft">
            <div class="cont">
                <div><img src="/images/footershfw.png" width="79" height="79" /></div>
                <b>售后服务</b><br />
                <a href="/h/4/11.html" target="_blank">服务承诺</a><br />
                <a href="/h/4/12.html" target="_blank">退换货政策</a><br />
                <a href="/h/4/13.html" target="_blank">退换货流程</a><br /><br /><br />
            </div>
        </div>
        <div class="footerright" style="padding-left:50px;">
            手机客户端下载
            <div><img src="/images/weixin.png" width="104" height="104" /></div>
        </div>
        <div class="footerright" style="padding-right:50px;">
            手机客户端下载
            <div><img src="/images/weixin.png" width="104" height="104" /></div>
        </div>
    </div>
</div>
</body>
</html>
