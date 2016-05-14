<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <title>Goal ball</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content=""/>
    <meta name="description" content="" />
    {common_head}
    <script type="text/javascript" src="/system_js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="/system_js/jquery.mobile-1.4.5/jquery.mobile-1.4.5.min.js"></script>
    <script type="text/javascript" src="/system_js/jquery_ui/jquery-ui.min.js"></script>
    <script type="text/javascript" src="/system_js/jquery_ui/jquery-ui-timepicker-addon.js"></script>

    <link rel="shortcut icon" href="">
    <link rel="stylesheet" href="/system_js/jquery.mobile-1.4.5/jquery.mobile-1.4.5.min.css">
    <link rel="stylesheet" href="/images_gb/css.css">

    <style>

        .ui-content {
            border-width: 0;
            overflow: visible;
            overflow-x: hidden;
            padding: 1em;
        }
    </style>
    <style>

        /* 重写jquery css */
        .ui-icon-carat-r::after{background: none}
        .ui-bar-a,
        .ui-page-theme-a .ui-bar-inherit,
        html .ui-bar-a .ui-bar-inherit,
        html .ui-body-a .ui-bar-inherit,
        html body .ui-group-theme-a .ui-bar-inherit {
            background-color: rgb(39, 134, 103);
            border-color: #ddd;
            color: #FFF;
            text-shadow: 0 1px 0 #011a06;
            font-weight: 700;
        }

        .ui-page-theme-a .ui-btn,
        html .ui-bar-a .ui-btn,
        html .ui-body-a .ui-btn,
        html body .ui-group-theme-a .ui-btn,
        html head + body .ui-btn.ui-btn-a,
        .ui-page-theme-a .ui-btn:visited,
        html .ui-bar-a .ui-btn:visited,
        html .ui-body-a .ui-btn:visited,
        html body .ui-group-theme-a .ui-btn:visited,
        html head + body .ui-btn.ui-btn-a:visited {
            color: #0A1A2F;
            text-shadow: 0 1px 0 #fefefe;
        }

        .ui-content{
            margin: 0;
            padding:0;
        }

        .ui-btn-icon-notext.ui-btn-corner-all, .ui-btn-icon-notext.ui-corner-all {
            -webkit-border-radius: .2em;
            border-radius: .2em;
        }

        .ui-page-theme-a .ui-btn.ui-btn-active {
            background-color: #278667;
            border-color: #38c;
            color: #fff;
            text-shadow: 0 1px 0 #059;
        }


        .ui-page-theme-a .ui-slider-track .ui-btn-active{
            background-color: #278667;
            border-color: #38c;
            color: #fff;
            text-shadow: 0 1px 0 #059;}


        .ui-slider-label-b{
            background-color: #aaa;
            border-color: #38c;
            color: #fff;
            text-shadow: 0 1px 0 #059;
        }

        .ui-input-text{
            float:left
        }


        .ui-panel h3{
            padding:1em 0 0 1em;
        }


        .detail_container{padding:2em .5em 0;width:80%;margin:0 auto}

        .member_list_btn{display: none}
        .assistor_list_btn{display: none}
    </style>
    <style>
        /* css for timepicker */
        .ui-timepicker-div .ui-widget-header { margin-bottom: 8px; }
        .ui-timepicker-div dl { text-align: left; }
        .ui-timepicker-div dl dt { float: left; clear:left; padding: 0 0 0 5px; }
        .ui-timepicker-div dl dd { margin: 0 10px 10px 45%; }
        .ui-timepicker-div td { font-size: 90%; }
        .ui-tpicker-grid-label { background: none; border: none; margin: 0; padding: 0; }

        .ui-timepicker-rtl{ direction: rtl; }
        .ui-timepicker-rtl dl { text-align: right; padding: 0 5px 0 0; }
        .ui-timepicker-rtl dl dt{ float: right; clear: right; }
        .ui-timepicker-rtl dl dd { margin: 0 45% 10px 10px; }
    </style>
    <script>
        $(function(){

            $(".GetDate").datepicker({
                showSecond: true,
                dateFormat: 'yy-mm-dd',
                numberOfMonths: 1,
                changeYear :true,
                changeMonth : true
            });
            $(".GetTime").timepicker({
                showSecond: true,
                timeFormat: 'HH:mm:ss',
                stepHour: 1,
                stepMinute: 1,
                stepSecond: 1
            });
        });



        function submitForm(continueCreate) {
            var submit=1;
            var error=""
            if ($('#f_MemberName').val() == '') {
                error+="至少写名字..\r\n";
                submit=0;
            }
            if(submit==1) {
                if (continueCreate == 1) {
                    $("#CloseTab").val("0");
                } else {
                    $("#CloseTab").val("1");
                }
                $('#main_form').submit();
            }else{
                alert(error);
            }
        }
    </script>
</head>

<body>

{common_body_deal}

<div data-role="page" class="jqm-demos" data-quicklinks="true">

    <div data-role="header" data-position="fixed">
        <a href="" data-rel="back" class="ui-btn-left ui-alt-icon ui-nodisc-icon ui-btn ui-icon-carat-l ui-btn-icon-notext ui-corner-all" data-role="button" role="button">Back</a>
        <h1>人员调整</h1>
    </div>
    <div role="main" class="ui-content jqm-content">
        <form data-ajax="false" id="main_form" action="/default.php?secu=manage&mod=member_change&m={method}&match_id={MatchId}&member_change_id={MemberChangeId}" method="post">
            <input id="CloseTab" name="CloseTab" type="hidden" value="0"/>
            <input name="f_TeamId" id="f_TeamId" value="{TeamId}" type="hidden"/>
            <input id="f_CreateDate" name="f_CreateDate" value="{CreateDate}" type="hidden" class="input_box" style="width:80px;"/>
            <div class="detail_container">

                <div class="nav_content events_container">


                    <div style="height:1em"></div>
                    <div style="height:1em"></div>
                    <div style="height:1em"></div>
                    <div style="height:1em"></div>
                    <div style="height:1em"></div>

                    <div data-demo-html="true" style="overflow:hidden">
                        <input type="text" name="f_MemberName" id="f_MemberName" placeholder="姓名" value="{MemberName}" data-mini="true">

                        <input type="number" name="f_Number" id="f_Number" placeholder="号码" value="{Number}" data-mini="true" />

                        <input type="text" class="GetDate" data-role="date" id="f_BirthDay" name="f_BirthDay" placeholder="生日" value="{BirthDay}" data-mini="true"/>

</div>

                    <div style="height:1em"></div>
                    <div data-demo-html="true">
                        <label for="submit-4" class="ui-hidden-accessible">Send:</label>
                        <div class="ui-btn ui-shadow" onclick="submitForm(0)">提交</div>
                        <div class="ui-btn ui-shadow" onclick="submitForm(1)">提交并继续</div>
                        <div class="ui-btn ui-shadow" onclick="window.location.href='{OriUrl}'">返回</div>
                    </div>
                </div>
                <!--<div class="nav_content member_list">
                </div>-->
            </div>
        </form>
    </div>


</div>

</body>
</html>