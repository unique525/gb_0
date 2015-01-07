<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>订单确认</title>
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


            if(Request["pay_method"] == "2"){
                $("#pay_method_2").css("display","block");
            }

        });
    </script>
</head>

<body>
<div class="wrapper">
    <div class="logo"><a href=""><img src="/images/mylogo.png" width="320" height="103"/></a></div>
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
<pre_temp id="12"></pre_temp>

<div class="wrapper3">
    <div class="new_buy">

        <div class="new_con">
            <div class="new_con_tit">支付<br/>结果</div>
            <div class="new_con_box">
                <div class="new_con_arw"><img src="/images/cart_n_arrowl.gif" width="11" height="21" alt=""/></div>
                <div class="new_commodity">

                    <div id="pay_method_2" style="display:none;">

                        <h2 style="line-height:300%;font-size:24px;">您的订单我们已经收到，我们将尽快为发出，预计您将于2天内收到货物。</h2>

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

<pre_temp id="8"></pre_temp>
</body>
</html>
