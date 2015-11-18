<?php


//require_once("lib/apipay_notify.class.php");
//require_once("lib/apipay_notify_wap.class.php");
//require_once("lib/apipay_submit.class.php");

/**
 * 支付宝
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Plugins_Alipay
 * @author zhangchi
 */
class Alipay
{


    public function Init($siteId)
    {
        $alipayConfig = array();
        $siteConfigData = new SiteConfigData($siteId);

        if (
            strlen($siteConfigData->PayAlipayPartnerId) > 0 &&
            strlen($siteConfigData->PayAlipayKey) > 0
        ) {
            //合作身份者id，以2088开头的16位纯数字
            $alipayConfig['partner'] = $siteConfigData->PayAlipayPartnerId;
            //安全检验码，以数字和字母组成的32位字符
            $alipayConfig['key'] = $siteConfigData->PayAlipayKey;
            //签名方式 不需修改
            $alipayConfig['sign_type'] = strtoupper('MD5');
            //字符编码格式 目前支持 gbk 或 utf-8
            $alipayConfig['input_charset'] = strtolower('utf-8');
            //ca证书路径地址，用于curl中ssl校验
            //请保证cacert.pem文件在当前文件夹目录中
            $alipayConfig['cacert'] = PHYSICAL_PATH . "/FrameWork1/RuleClass/Plugins/Alipay/cacert.pem";
            //访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
            $alipayConfig['transport'] = 'http';
        }

        return $alipayConfig;

    }

    public function InitWap($siteId){
        $alipayConfig = array();
        $siteConfigData = new SiteConfigData($siteId);

        if (
            strlen($siteConfigData->PayAlipayPartnerId) > 0 &&
            strlen($siteConfigData->PayAlipayKey) > 0
        ) {
            //合作身份者id，以2088开头的16位纯数字
            $alipayConfig['partner'] = $siteConfigData->PayAlipayPartnerId;
            //商户的私钥（后缀是.pen）文件相对路径
            $alipayConfig['private_key_path']	=  '../../FrameWork1/RuleClass/Plugins/Alipay/key/rsa_private_key.pem';
            //支付宝公钥（后缀是.pen）文件相对路径
            $alipayConfig['ali_public_key_path'] = '../../FrameWork1/RuleClass/Plugins/Alipay/key/alipay_public_key.pem';
            //签名方式 不需修改
            $alipayConfig['sign_type']    = strtoupper('RSA');

            //字符编码格式 目前支持 gbk 或 utf-8
            $alipayConfig['input_charset']= strtolower('utf-8');

            //ca证书路径地址，用于curl中ssl校验
            //请保证cacert.pem文件在当前文件夹目录中
            $alipayConfig['cacert'] = "../../FrameWork1/RuleClass/Plugins/Alipay/cacert.pem";
            //访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
            $alipayConfig['transport']    = 'http';
        }

        return $alipayConfig;
    }

    public function Submit(
        $siteId,
        $alipayConfig,
        $userOrderNumber,
        $userOrderName,
        $totalFee,
        $userOrderIntro,
        $userOrderProductUrl
    )
    {
        //$sitePublicData = new SitePublicData();
        $siteUrl = $_SERVER['HTTP_HOST'];//$sitePublicData->GetSiteUrl($siteId, true);

        $siteConfigData = new SiteConfigData($siteId);
        //建立请求
        $alipaySubmit = new AlipaySubmit($alipayConfig);
        //支付类型
        $payment_type = "1";
        //必填，不能修改
        //服务器异步通知页面路径
        $notify_url = "http://$siteUrl/pay/alipay/notify.php";
        //需http://格式的完整路径，不能加?id=123这类自定义参数        //页面跳转同步通知页面路径
        $return_url = "http://$siteUrl/pay/alipay/return.php";
        //需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/        //卖家支付宝帐户
        $seller_email = $siteConfigData->PayAlipaySellerEmail;
        //必填        //商户订单号
        $out_trade_no = $userOrderNumber;
        //商户网站订单系统中唯一订单号，必填        //订单名称
        $subject = $userOrderName;



        //必填        //付款金额
        $total_fee = $totalFee;
        //必填        //订单描述
        $body = $userOrderIntro;
        //商品展示地址
        $show_url = $userOrderProductUrl;
        //需以http://开头的完整路径，例如：http://www.商户网址.com/myorder.html

        //若要使用请调用类文件submit中的query_timestamp函数        //客户端的IP地址
        $exter_invoke_ip = "";
        //非局域网的外网IP地址，如：221.0.0.1
        //防钓鱼时间戳
        $anti_phishing_key = $alipaySubmit->query_timestamp();//

        //构造要请求的参数数组，无需改动
        $parameter = array(
            "service" => "create_direct_pay_by_user",
            "partner" => trim($alipayConfig['partner']),
            "payment_type" => $payment_type,
            "notify_url" => $notify_url,
            "return_url" => $return_url,
            "seller_email" => $seller_email,
            "out_trade_no" => $out_trade_no,
            "subject" => $subject,
            "total_fee" => $total_fee,
            "body" => $body,
            "show_url" => $show_url,
            "anti_phishing_key" => $anti_phishing_key,
            "exter_invoke_ip" => $exter_invoke_ip,
            "_input_charset" => trim(strtolower($alipayConfig['input_charset']))
        );



        $html_text = $alipaySubmit->buildRequestForm($parameter, "get", "确认");

        return $html_text;
    }


    public function NotifyUrl($alipayConfig)
    {
        //计算得出通知验证结果
        $alipayNotify = new AlipayNotify($alipayConfig);
        $verify_result = $alipayNotify->verifyNotify();

        if ($verify_result) { //验证成功
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //请在这里加上商户的业务逻辑程序代


            //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——

            //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表

            //商户订单号

            $out_trade_no = $_POST['out_trade_no'];

            //支付宝交易号

            $trade_no = $_POST['trade_no'];

            //交易状态
            $trade_status = $_POST['trade_status'];


            if ($_POST['trade_status'] == 'TRADE_FINISHED') {
                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //如果有做过处理，不执行商户的业务程序

                self::DealUserOrder($out_trade_no, $trade_no, $trade_status);
                //增加付款记录

                //注意：
                //该种交易状态只在两种情况下出现
                //1、开通了普通即时到账，买家付款成功后。
                //2、开通了高级即时到账，从该笔交易成功时间算起，过了签约时的可退款时限（如：三个月以内可退款、一年以内可退款等）后。

                //调试用，写文本函数记录程序运行情况是否正常
                //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
            } else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //如果有做过处理，不执行商户的业务程序
                self::DealUserOrder($out_trade_no, $trade_no, $trade_status);
                //注意：
                //该种交易状态只在一种情况下出现——开通了高级即时到账，买家付款成功后。

                //调试用，写文本函数记录程序运行情况是否正常
                //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
            }

            //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——

            echo "success"; //请不要修改或删除

            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        } else {
            //验证失败
            echo "fail";

            //调试用，写文本函数记录程序运行情况是否正常
            //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
        }
    }

    /**
     * @param $alipayConfig
     * @return string
     */
    public function ReturnUrl($alipayConfig)
    {
        //计算得出通知验证结果
        $alipayNotify = new AlipayNotify($alipayConfig);
        $verify_result = $alipayNotify->verifyReturn();
        if ($verify_result) { //验证成功
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //请在这里加上商户的业务逻辑程序代码

            //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
            //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表

            //商户订单号

            $out_trade_no = $_GET['out_trade_no'];

            //支付宝交易号

            $trade_no = $_GET['trade_no'];

            //交易状态
            $trade_status = $_GET['trade_status'];


            if ($_GET['trade_status'] == 'TRADE_FINISHED' || $_GET['trade_status'] == 'TRADE_SUCCESS') {
                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //如果有做过处理，不执行商户的业务程序
                self::DealUserOrder($out_trade_no, $trade_no, $trade_status);
            } else {
                echo "trade_status=" . $_GET['trade_status'];
            }

            return "success";

            //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——

            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        } else {
            //验证失败
            //如要调试，请看alipay_notify.php页面的verifyReturn函数
            return "fail";
        }


    }

    /**
     * wap notify
     * @param $alipayConfig
     */
    public function NotifyUrlForWap($alipayConfig)
    {
        //计算得出通知验证结果
        $alipayNotifyWap = new AlipayNotifyWap($alipayConfig);
        $verify_result = $alipayNotifyWap->verifyNotify();

        if ($verify_result) { //验证成功
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //请在这里加上商户的业务逻辑程序代


            //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——

            //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表

            //商户订单号

            $out_trade_no = $_POST['out_trade_no'];

            //支付宝交易号

            $trade_no = $_POST['trade_no'];

            //交易状态
            $trade_status = $_POST['trade_status'];


            if ($_POST['trade_status'] == 'TRADE_FINISHED') {
                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //如果有做过处理，不执行商户的业务程序

                self::DealUserOrderForWap($out_trade_no, $trade_no, $trade_status);
                //增加付款记录

                //注意：
                //该种交易状态只在两种情况下出现
                //1、开通了普通即时到账，买家付款成功后。
                //2、开通了高级即时到账，从该笔交易成功时间算起，过了签约时的可退款时限（如：三个月以内可退款、一年以内可退款等）后。

                //调试用，写文本函数记录程序运行情况是否正常
                //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
            } else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //如果有做过处理，不执行商户的业务程序
                self::DealUserOrderForWap($out_trade_no, $trade_no, $trade_status);
                //注意：
                //该种交易状态只在一种情况下出现——开通了高级即时到账，买家付款成功后。

                //调试用，写文本函数记录程序运行情况是否正常
                //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
            }

            //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——

            echo "success"; //请不要修改或删除

            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        } else {
            //验证失败
            echo "fail";

            //调试用，写文本函数记录程序运行情况是否正常
            //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
        }
    }

    /**
     * 处理订单表
     * @param $out_trade_no
     * @param $trade_no
     * @param $trade_status
     */
    private function DealUserOrder($out_trade_no, $trade_no, $trade_status){
        $userOrderPublicData = new UserOrderPublicData();
        $userOrderId = $userOrderPublicData->GetUserOrderIdByUserOrderNumber($out_trade_no, true);
        $allPrice = $userOrderPublicData->GetAllPrice($userOrderId);

        if($userOrderId>0 && $allPrice>0){
            $alipayTradeStatus = $userOrderPublicData->GetAlipayTradeStatus($userOrderId, false);
            if($alipayTradeStatus == 'TRADE_FINISHED'
                || $alipayTradeStatus == 'TRADE_SUCCESS'
            ){

                //如果有做过处理，不执行商户的业务程序

            }else{
                //已付款
                $orderState = UserOrderData::STATE_PAYMENT;
                //改变订单状态
                $userOrderPublicData->ModifyStateWithoutUserId($userOrderId,$orderState);
                $userOrderPublicData->ModifyAlipayTradeNo($userOrderId, $trade_no);
                $userOrderPublicData->ModifyAlipayTradeStatus($userOrderId, $trade_status);

                $date = strval(date('Y-m-d H:i:s', time()));
                $userOrderPublicData->ModifyPayDate($userOrderId,$date);


                //增加订单付款记录
                $userOrderPayPublicData = new UserOrderPayPublicData();
                $userOrderPayPublicData->Create(
                    $userOrderId,
                    $allPrice,
                    "支付宝"
                );
            }

            $userOrderTableType = $userOrderPublicData->GetUserOrderTableType($out_trade_no, true);

            if ($userOrderTableType == UserOrderData::USER_ORDER_TABLE_TYPE_NEWSPAPER){

                $userOrderNewspaperPublicData = new UserOrderNewspaperPublicData();
                $newspaperArticleId = $userOrderNewspaperPublicData->GetNewspaperArticleId($userOrderId, true);
                $newspaperId = $userOrderNewspaperPublicData->GetNewspaperId($userOrderId, true);

                if ($newspaperArticleId>0){

                    Control::GoUrl("/newspaper_article-detail-$newspaperArticleId.html");

                }
                else if ($newspaperId>0){


                    $newspaperPublicData = new NewspaperPublicData();

                    $publicDate = $newspaperPublicData->GetPublishDate($newspaperId, true);
                    $channelId = $newspaperPublicData->GetChannelId($newspaperId, true);

                    Control::GoUrl("/default.php?mod=newspaper&a=gen_one&channel_id=$channelId&publish_date=$publicDate");

                }

            }




        }
    }

    /**
     * 处理订单表
     * @param $out_trade_no
     * @param $trade_no
     * @param $trade_status
     */
    private function DealUserOrderForWap($out_trade_no, $trade_no, $trade_status){
        $userOrderClientData = new UserOrderClientData();
        $userOrderId = $userOrderClientData->GetUserOrderIdByUserOrderNumber($out_trade_no, true);
        $allPrice = $userOrderClientData->GetAllPrice($userOrderId);

        if($userOrderId>0 && $allPrice>0){
                //已付款
            $orderState = UserOrderData::STATE_PAYMENT;
            //改变订单状态
            $userOrderClientData->ModifyStateWithoutUserId($userOrderId,$orderState);
            $userOrderClientData->ModifyAlipayTradeNo($userOrderId, $trade_no);
            $userOrderClientData->ModifyAlipayTradeStatus($userOrderId, $trade_status);


            $date = strval(date('Y-m-d H:i:s', time()));
            $userOrderClientData->ModifyPayDate($userOrderId,$date);

            //增加订单付款记录
            $userOrderPayPublicData = new UserOrderPayPublicData();
            $userOrderPayPublicData->Create(
                $userOrderId,
                $allPrice,
                "支付宝"
            );
        }
    }
}

?>