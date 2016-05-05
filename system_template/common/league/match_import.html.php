<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}
    <script type="text/javascript" src="/system_js/jquery_ui/jquery-ui-timepicker-addon.js"></script>
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


        #container{float:left;width:20%;text-align: right}
        .into{float:left;width:10%;overflow: hidden}
        .into ul li{height:30px;line-height:30px;overflow: hidden;box-shadow: 0px 0px 5px 0.1px rgb(59, 59, 59);
            margin: 13px;
            padding: 0 5px;
            text-align: left;}
        #container{float:left;width:60%;overflow: hidden}
        #container ul li{height:30px;line-height:30px;overflow: hidden;box-shadow: 0px 0px 5px 0.1px rgb(59, 59, 59);
            margin: 13px;
            padding: 0 5px;
            text-align: left;}
        #into{float:left;width:30%;overflow: hidden}
        #into ul li{height:30px;line-height:30px;overflow: hidden;box-shadow: 0px 0px 5px 0.1px rgb(59, 59, 59);
            margin: 13px;
            padding: 0 5px;
            text-align: left;}

        #text{width:80%;height:500px}
        #jsonStr{width:80%;height:500px;display: none;}
        #check{display:none}

        td.spe_line2{box-shadow: 0 0 1px .1px #555;
            padding: 0 15px;}
        td.btn{box-shadow: 0 0 1px .1px #555;
            padding: 0 15px;}

        #pre_show {width:100%;display:none;text-align: center}
        #pre_show table{margin:0 auto;margin-bottom:10px}

        .submit{display: none}
    </style>

    <script type="text/javascript" src="/system_js/manage/league/league_import.js"></script>
    <script type="text/javascript">

        function GetJson(){
            var row=$("#container").find("li");
            var newSort=new Array();
            var i;
            for(i=0;i<row.length;++i){//取得字段名
                newSort.push(parseInt($(row[i]).attr("idvalue")));
            }


            var into=$("#into").find("li");

            var jsonStr='[';
            for(i=0;i<container.length;++i){
                var a=i;

                jsonStr+='{';
                for(var j=0;j<into.length;++j){
                    var value=container[i][newSort[j]];
                    if(value!=undefined){
                        jsonStr+='"'+$(into[j]).attr("idvalue")+'":"'+value+'"';
                    }else{
                        jsonStr+='"'+$(into[j]).attr("idvalue")+'":""';
                    }
                    if(j!=into.length-1){
                        jsonStr+=","
                    }
                }
                jsonStr+='}';
                if(i!=container.length-1){
                    jsonStr+=","
                }
            }
            jsonStr+=']';


            var jsonObj=$.parseJSON(jsonStr);

            //生成预览
            importTitle='<div style="margin:0 auto;text-align: center;">(深色为唯一约束字段，点击可进行唯一性检查）</div><table><tr class="grid_item">';
            for(i=0;i<into.length;++i){
                var fieldName=$(into[i]).attr("idvalue");
                var queryFieldName=$(into[i]).attr("title");
                var fieldIdName=$(into[i]).attr("id_name");
                var mod=$(into[i]).attr("mod");
                var fieldShowName=$(into[i]).html();
                var fieldClass=$(into[i]).attr("class");
                if(fieldClass=="btn"){
                    importTitle+='<td class="btn" onclick="CheckRepeat(\''+fieldName+'\')">'+fieldShowName+'</td>';
                }else if(fieldClass=="btn2"){
                    importTitle+='<td class="btn2" onclick="GetIdsOfField(\''+fieldName+'\',\''+queryFieldName+'\',\''+fieldIdName+'\',\''+mod+'\')">'+fieldShowName+'</td>';
                }else{
                    importTitle+='<td class="spe_line2">'+fieldShowName+'</td>';
                }
            }
            importTitle+='</tr>';
            //$("#import_title").html(importTitle);



            preView("for_import", jsonObj,importTitle)
            $("#jsonStr").val(jsonStr);

            $("#text").hide();
            $("#check").hide();
            $("#pre_show").show();
        }

        function CheckRepeat(fieldName){
            var importShowTitle=importTitle; //取全局的table title
            var jsonStr=$("#jsonStr").val();
            var jsonObj=JSON.parse(jsonStr);
            if(fieldName!=""&&jsonStr!=""){
                $.ajax({
                    type: "post",
                    url: "/default.php?secu=manage&mod=match&m=async_check_repeat&site_id={SiteId}",
                    data: {
                        field_name: fieldName,
                        import_json: jsonStr
                    },
                    dataType: "jsonp",
                    jsonp: "jsonpcallback",
                    success: function(data) {
                        var result=parseInt(data["result"]);
                        if(result>0){
                            var repeatObj=(data["repeat"]);
                            var checkedJsonObj=[];
                            var repeatJsonObj=[];
                            var repeated=0;
                            $.each(jsonObj,function(i, v){
                                repeated=0;
                                $.each(repeatObj,function(j,vj){
                                    if(v[fieldName]==vj[fieldName]){
                                        repeatJsonObj[repeatJsonObj.length]=jsonObj[i];
                                        repeated=1;
                                    }
                                });
                                if(repeated==0){
                                    checkedJsonObj[checkedJsonObj.length]=jsonObj[i];
                                }
                            });

                            //本地重复检查
                            jsonObj=[];
                            var repeatLocal=[];
                            var hash={};
                            for (var i = 0, elem; (checkedJsonObj[i]) != undefined; i++) {
                                elem = checkedJsonObj[i][fieldName];
                                if (!hash[elem]) {
                                    jsonObj.push(checkedJsonObj[i]);
                                    hash[elem] = true;
                                }else{
                                    repeatLocal.push(checkedJsonObj[i]);
                                }
                            }

                            $("#jsonStr").val(JSON.stringify(jsonObj));
                            preView("for_import", jsonObj,importShowTitle);
                            preView("repeated", repeatJsonObj,importShowTitle);
                            preView("repeated_local", repeatLocal,importShowTitle);

                        }else if(result==0){
                            alert("无重复项，可放心导入");
                        }else{
                            alert("检查出错！")
                        }
                    }
                });
            }
        }




        function GetIdsOfField(fieldName,queryFieldName,fieldIdName,mod) {

            var importShowTitle=importTitle; //取全局的table title
            var jsonStr=$("#jsonStr").val();
            var jsonObj=JSON.parse(jsonStr);
            if(fieldName!=""&&jsonStr!=""){
                $.ajax({
                    type: "post",
                    url: "/default.php?secu=manage&mod="+mod+"&m=async_get_json_with_ids&site_id={SiteId}",
                    data: {
                        field_name: fieldName,
                        import_json: jsonStr,
                        query_field_name: queryFieldName,
                        field_id_name: fieldIdName
                    },
                    dataType: "jsonp",
                    jsonp: "jsonpcallback",
                    success: function(data) {
                        alert("获取id成功");
                        var result=parseInt(data["result"]);
                        if(result>0){
                            var dataObj=(data["data"]);


                            $("#jsonStr").val(JSON.stringify(dataObj));
                            preView("for_import", dataObj,importShowTitle);

                        }else if(result==0){
                            alert("获取id失败");
                        }else{
                            alert("检查出错！")
                        }
                    }
                });
            }
        }


        function submitForm(continueCreate) {
            var submit=1;
            var jsonStr=$('#jsonStr').val();
            jsonStr=jsonStr.replace(/ |　/g, "");
            if (jsonStr == '') {
                $("#dialog_box").dialog({width: 300, height: 100});
                $("#dialog_content").html("请导入数据");
                submit=0;
            }
            if(jsonStr.indexOf('"f_HomeTeamId":""')>=0||jsonStr.indexOf('"f_HomeTeamId":"0"')>=0
            ||jsonStr.indexOf('"f_HomeTeamId":""')>=0||jsonStr.indexOf('"f_HomeTeamId":"0"')>=0){
                alert("有队伍没有id");
                submit=0;
            }
            if(submit==1) {
                if (continueCreate == 1) {
                    $("#CloseTab").val("0");
                } else {
                    $("#CloseTab").val("1");
                }
                $('#main_form').submit();
            }
        }
    </script>
</head>
<body>
{common_body_deal}
<form id="main_form" action="/default.php?secu=manage&mod=match&m={method}&league_id={LeagueId}&tab_index={TabIndex}" method="post">
    <input id="CloseTab" name="CloseTab" type="hidden" value="0"/>

    <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td class="spe_line" height="40" align="right">
                <input class="btn" value="确认并关闭" type="button" onclick="submitForm(0)"/>
                <input class="btn" value="确认并继续新增" style="display:{display}" type="button" onclick="submitForm(1)"/>
                <input class="btn" value="取 消" type="button" onclick="closeTab()"/>
            </td>
        </tr>
    </table>



    <div class="div_list">
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td id="td_main_btn" width="">
                    <input class="btn2" type="button" onclick="DealRow()" value="解析" />
                    <input class="btn2" type="button" onclick="GetJson()" value="预览"/>
                    <input class="btn2" type="button" onclick='$("#check").hide();$("#text").show();$("#pre_show").hide()' value="重新解析" />
                </td>
                <td id="td_main_btn" align="right">
                </td>
            </tr>
        </table>
        <table class="grid" id="left_tree" width="100%" cellpadding="0" cellspacing="0">

            <tr class="grid_item" id="1">
                <td>
                    <textarea id="text"></textarea>
                    <textarea id="jsonStr" name="import_json"></textarea>

                    <div id="check">
                        <div class="div_list" id="container">
                            <ul id="sort_grid">
                            </ul>
                        </div>

                        <div id="into" class="div_list">
                            <ul >
                                <li idvalue="f_MatchName" class="">比赛名</li>
                                <li idvalue="t_BeginDate" class="">日期</li>
                                <li idvalue="t_BeginTime" class="">时间</li>
                                <li idvalue="f_HomeTeamId" class="">主队ID</li>
                                <li idvalue="HomeTeamName" title="TeamName" id_name="f_HomeTeamId" mod="team" class="btn2">主队</li>
                                <li idvalue="f_GuestTeamId" class="">客队ID</li>
                                <li idvalue="GuestTeamName" title="TeamName" id_name="f_GuestTeamId" mod="team" class="btn2">客队</li>
                                <!--<li idvalue="JudgeId" class="">裁判ID</li>
                                <li idvalue="JudgeName" class="">裁判</li>-->
                                <li idvalue="f_StadiumId" class="">场地ID</li>
                                <li idvalue="StadiumName" title="StadiumName" id_name="f_StadiumId" mod="stadium" class="btn2">场地</li>
                            </ul>
                        </div>
                        <div style="position: relative; height: 50px; float: left;width:100%">(深色为唯一约束字段,浅色点击获取ID）</div>
                    </div>
                </td>

            </tr>


        </table>


    </div>


    <div id="pre_show">
        <div id="import_title"></div>
        <div id="for_import"></div>
        服务器重复：
        <div id="repeated"></div>
        本地重复：
        <div id="repeated_local"></div>
    </div>


    <div id="bot_button">
        <div style="padding-top:3px;">
            <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td colspan="2" height="30" align="center">
                        <input class="btn" value="确认并关闭" type="button" onclick="submitForm(0)"/>
                        <input class="btn" value="确认并继续新增" style="display:{display}" type="button" onclick="submitForm(1)"/>
                        <input class="btn" value="取 消" type="button" onclick="closeTab()"/>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</form>
</body>
</html>