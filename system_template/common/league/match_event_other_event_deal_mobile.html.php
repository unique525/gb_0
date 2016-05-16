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
    <script>
        $(function(){
            $("#f_MemberId").click(function(){
                if($("#f_Team").val()<=0){
                    alert("请先选择队伍！")
                }
            });

            if($("#f_OtherEventTitle").val()==""){
                $("#f_OtherEventTitle").val("请输入事件标题");
            }
            if($("#f_OtherEventContent").html()==""){
                $("#f_OtherEventContent").html("请输入事件详细");
            }

            if(parseInt($("#f_PrincipalMemberId").val())>0){
                var oriTeamId=$("#f_TeamId").val();
                var oriName=$(".member_option[idvalue='{PrincipalMemberId}']").html();
                var oriAssistorName=$(".assistor_option[idvalue='{SecondaryMemberId}']").html();
                $("#member_of_"+oriTeamId).css("display","block");
                $("#member_of_"+oriTeamId).html(oriName);
                $("#assistor_of_"+oriTeamId).css("display","block");
                $("#assistor_of_"+oriTeamId).html(oriAssistorName);
            }
            //只显示相关的队员表

            $("#f_TeamId").change(function(){

                var teamId=$("#f_TeamId").val();

                $(".member_list_btn").hide();
                $("#member_of_"+teamId).css("display","block");
                $(".assistor_list_btn").hide();
                $("#assistor_of_"+teamId).css("display","block");

                $("#member_of_{HomeTeamId}").html("主要队员");
                $("#member_of_{GuestTeamId}").html("主要队员");
                $("#assistor_of_{HomeTeamId}").html("第二主要队员");
                $("#assistor_of_{GuestTeamId}").html("第二主要队员");
                $("#f_PrincipalMemberId").val("");
                $("#f_SecondaryMemberId").val("");
            });

            //确定下场队员id
            $(".member_option").click(function(){
                var teamId=$(this).attr("team_id");
                var name=$(this).html();
                var id=$(this).attr("idvalue");
                $("#member_of_"+teamId).html(name);
                $("#f_PrincipalMemberId").val(id);
            })
            //确定登场队员id
            $(".assistor_option").click(function(){
                var teamId=$(this).attr("team_id");
                var name=$(this).html();
                var id=$(this).attr("idvalue");
                $("#assistor_of_"+teamId).html(name);
                $("#f_SecondaryMemberId").val(id);
            })
        })


        function submitForm(continueCreate) {
            var submit=1;
            var error=""
            if ($('#f_TeamId').val() == '') {
                error+="请选择队伍！\r\n";
                submit=0;
            }
            if ($('#f_MinuteInMatch').val() == '') {
                error+="请输入时间！\r\n";
                submit=0;
            }
            if ($('#f_OtherEventTitle').val() == '') {
                error+="请输入事件标题！\r\n";
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
        <h1>其他事件</h1>
    </div>
    <div role="main" class="ui-content jqm-content">
        <form data-ajax="false" id="main_form" action="/default.php?secu=manage&mod=other_event&m={method}&match_id={MatchId}&other_event_id={OtherEventId}" method="post">
            <input id="CloseTab" name="CloseTab" type="hidden" value="0"/>
            <input name="f_MatchId" id="f_MatchId" value="{MatchId}" type="hidden"/>
            <input name="f_LeagueId" id="f_LeagueId" value="{LeagueId}" type="hidden"/>
            <input id="f_CreateDate" name="f_CreateDate" value="{CreateDate}" type="hidden" class="input_box" style="width:80px;"/>
            <div class="detail_container">

                <div class="nav_content events_container">
                    <select name="f_TeamId" id="f_TeamId" data-native-menu="false">
                        <option value="">选择事件队伍</option>
                        <option value="{HomeTeamId}">{HomeTeamName}</option>
                        <option value="{GuestTeamId}">{GuestTeamName}</option>
                    </select>
                    {s_TeamId}

                    <div style="height:.3em"></div>


                    <input name="f_PrincipalMemberId" id="f_PrincipalMemberId" value="{PrincipalMemberId}" type="hidden"/>
                    <a href="#leftpanel_member_home" id="member_of_{HomeTeamId}" class="member_list_btn ui-btn ui-icon-carat-d ui-btn-icon-right ui-corner-all ui-shadow">选择主要队员</a>
                    <div data-role="panel" id="leftpanel_member_home" data-position="left" data-display="overlay" data-theme="a" class="ui-panel ui-panel-position-left ui-panel-display-overlay ui-body-a ui-panel-animate ui-panel-open">
                        <ul data-role="listview" data-inset="true" >
                            <li data-role="list-divider">{HomeTeamName}</li>
                            <icms id="home_list" type="member_list">
                                <item><![CDATA[
                                    <li data-rel="close"><a idvalue="{f_MemberId}" team_id="{f_TeamId}" class="member_option">{f_MemberName}({f_Number}号)</a></li>
                                    ]]></item>
                            </icms>
                        </ul>
                    </div>

                    <a href="#leftpanel_member_guest" id="member_of_{GuestTeamId}" class="member_list_btn ui-btn ui-icon-carat-d ui-btn-icon-right ui-corner-all ui-shadow">选择主要队员</a>
                    <div data-role="panel" id="leftpanel_member_guest" data-position="left" data-display="overlay" data-theme="a" class="ui-panel ui-panel-position-left ui-panel-display-overlay ui-body-a ui-panel-animate ui-panel-open">
                        <ul data-role="listview" data-inset="true" >
                            <li data-role="list-divider">{GuestTeamName}</li>
                            <icms id="guest_list" type="member_list">
                                <item><![CDATA[
                                    <li data-rel="close"><a idvalue="{f_MemberId}" team_id="{f_TeamId}" class="member_option">{f_MemberName}({f_Number}号)</a></li>
                                    ]]></item>
                            </icms>
                        </ul>
                    </div>


                    <input name="f_SecondaryMemberId" id="f_SecondaryMemberId" value="{SecondaryMemberId}" type="hidden"/>
                    <a href="#leftpanel_assistor_home" id="assistor_of_{HomeTeamId}" class="member_list_btn ui-btn ui-icon-carat-d ui-btn-icon-right ui-corner-all ui-shadow">选择第二主要队员</a>
                    <div data-role="panel" id="leftpanel_assistor_home" data-position="left" data-display="overlay" data-theme="a" class="ui-panel ui-panel-position-left ui-panel-display-overlay ui-body-a ui-panel-animate ui-panel-open">
                        <ul data-role="listview" data-inset="true" >
                            <li data-role="list-divider">{HomeTeamName}</li>
                            <icms id="home_list_assistor" type="member_list">
                                <item><![CDATA[
                                    <li data-rel="close"><a idvalue="{f_MemberId}" team_id="{f_TeamId}" class="assistor_option">{f_MemberName}({f_Number}号)</a></li>
                                    ]]></item>
                            </icms>
                        </ul>
                    </div>

                    <a href="#leftpanel_assistor_guest" id="assistor_of_{GuestTeamId}" class="member_list_btn ui-btn ui-icon-carat-d ui-btn-icon-right ui-corner-all ui-shadow">选择第二主要队员</a>
                    <div data-role="panel" id="leftpanel_assistor_guest" data-position="left" data-display="overlay" data-theme="a" class="ui-panel ui-panel-position-left ui-panel-display-overlay ui-body-a ui-panel-animate ui-panel-open">
                        <ul data-role="listview" data-inset="true" >
                            <li data-role="list-divider">{GuestTeamName}</li>
                            <icms id="guest_list_assistor" type="member_list">
                                <item><![CDATA[
                                    <li data-rel="close"><a idvalue="{f_MemberId}" team_id="{f_TeamId}" class="assistor_option">{f_MemberName}({f_Number}号)</a></li>
                                    ]]></item>
                            </icms>
                        </ul>
                    </div>



                    <div data-demo-html="true" style="overflow:hidden">
                    <input type="text" name="f_OtherEventTitle" id="f_OtherEventTitle" value="{OtherEventTitle}" placeholder="请输入事件标题">

                    <textarea style="height: 50px;" class="ui-input-text ui-shadow-inset ui-body-inherit ui-corner-all ui-textinput-autogrow" cols="40" rows="8" name="f_OtherEventContent" id="f_OtherEventContent" placeholder="请输入事件详细">{OtherEventContent}</textarea>
                    </div>


                    <div style="height:.3em"></div>


                    <div data-demo-html="true">

                        <label for="f_MinuteInMatch" class="ui-hidden-accessible">时间点：</label>
                        <input type="number" name="f_MinuteInMatch" id="f_MinuteInMatch" placeholder="时间(200=点,500=?)" value="{MinuteInMatch}" data-mini="true">

                        <label for="f_State" class="ui-hidden-accessible" id="flip-8-label">state:</label>
                        <select name="f_State" id="f_State" data-role="slider" data-mini="true" tabindex="-1" class="ui-slider-switch">
                            <option value="100" {select100}>停用</option>
                            <option value="1" {select1}>启用</option>
                        </select>
                    </div>

                    <div style="height:1em"></div>
                    <div data-demo-html="true">
                        <label for="submit-4" class="ui-hidden-accessible">Send:</label>
                        <div class="ui-btn ui-shadow" onclick="submitForm(0)">提交</div>
                        <div class="ui-btn ui-shadow" onclick="submitForm(1)">提交并继续</div>
                        <div class="ui-btn ui-shadow" onclick="window.location.href='/a/match/m_get_one/match_detail/match_id={MatchId}.html'">返回</div>
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