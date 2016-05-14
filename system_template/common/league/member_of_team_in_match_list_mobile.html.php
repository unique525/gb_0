<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <title>Goal ball</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content=""/>
    <meta name="description" content="" />
    <script type="text/javascript" src="/system_js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="/system_js/jquery.mobile-1.4.5/jquery.mobile-1.4.5.min.js"></script>

    <link rel="shortcut icon" href="">
    <link rel="stylesheet" href="/system_js/jquery.mobile-1.4.5/jquery.mobile-1.4.5.min.css">
    <link rel="stylesheet" href="/images_gb/css.css">


    <script type="text/javascript">
        $().ready(function(){
            var startingMemberCount=parseInt("{StartingMemberCount}");


            $(".starting input[type='checkbox'],.alternate input[type='checkbox']").each(function(){
                var id=$(this).attr("id");
                $(".all #dis_"+id).parent().remove();
            });


            $(".btn_change").click(function(){
                var className=$(this).attr("idvalue");
                var container=$("."+className);
                var stop=0;
                $("input[type='checkbox']").each(function(){
                    if($(this).is(':checked')&&stop==0 ){
                        $(this).attr("checked",false);
                        if(className=="starting"){
                            var count=$(".starting input").length;
                            if(count==startingMemberCount){
                                alert("首发已满");
                                stop=1;
                            }
                        }

                        if(stop==0){
                            $(this).parent().appendTo(container);
//                            switch(className){
//                                case "all":
//                                    if($(this).attr("idvalue")!="all"){
//                                        var id=$(this).attr("id");
//                                        $(this).parent().remove();
//                                        $("#dis_"+id).removeAttr("disabled");
//                                        $("#dis_"+id).parent().removeClass("ui-state-disabled");
//                                    }
//                                    break;
//                                default:
//                                    if($(this).attr("idvalue")=="all"){
//                                        var name=$(this).attr("name").replace("dis_","");
//                                        var temp=$(this).parent().clone(true);
//                                        temp.children("input").attr("idvalue",className);
//                                        temp.children("input").attr("name",name);
//                                        temp.children("input").attr("id",name);
//                                        temp.children("label").attr("for",name);
//                                        temp.appendTo(container)
//                                        $(this).attr("disabled","disabled");
//                                        $(this).parent().addClass("ui-state-disabled");
//                                    }else{
//                                        $(this).parent().appendTo(container);
//                                    }
//                                    break;
//                            }
                        }
                    }
                });
                $("input[type='checkbox']").attr("checked",false);
                $("label").removeClass("ui-checkbox-on");
                $("label").addClass("ui-checkbox-off");

            });
            //$("label[for='"+$(this).attr("id")+"']").removeClass("ui-checkbox-on");
            //$("label[for='"+$(this).attr("id")+"']").addClass("ui-checkbox-off");

            $("#btn_add").click(function(){
                var tempUrl=window.location.href;
                var oriUrl=encodeURIComponent(tempUrl);
                window.location.href="/default.php?secu=manage&mod=member&m=mobile_create&team_id={TeamId}&ori_url="+oriUrl;
               });
        });

        function submit(){
            var startingIds='';
            var alternate_ids='';
            $(".starting input").each(function(){
                var temp=$(this).attr("id").replace("dis_","");
               startingIds+=','+temp.replace("member_","");
            });
            $(".alter input").each(function(){
                var temp=$(this).attr("id").replace("dis_","");
                alternate_ids+=','+temp.replace("member_","");
            });
            $("#starting_ids").val(startingIds.substr(1));
            $("#alternate_ids").val(alternate_ids.substr(1));
            $("#main_form").submit();
        }

    </script>
    <style>
        .all{width:60%;float:left;height:20em;overflow-y: scroll}
        .button{width:40%;float:left;height:20em}
        .btn_container{padding:1em}
        .starting{width:50%;float:left;margin-top:1em}
        .alter{width:50%;float:left;margin-top:1em}

    </style>
</head>

<body>
<div data-role="page" class="jqm-demos" data-quicklinks="true">
    <div data-role="header" data-position="fixed">
        <a  data-rel="back" class="ui-btn-left ui-alt-icon ui-nodisc-icon ui-btn ui-icon-carat-l ui-btn-icon-notext ui-corner-all" data-role="button" role="button">Back</a>
        <h1>{LeagueName}</h1>
    </div>
    <form data-ajax="false" id="main_form" action="/default.php?secu=manage&mod=member&m=mobile_list_of_team_in_match&match_id={MatchId}&team_id={TeamId}" method="post">
        <input name="starting_ids" id="starting_ids" value="" type="hidden"/>
        <input name="alternate_ids" id="alternate_ids" value="" type="hidden"/>
        <div class="all">
            <div style="color: #fff;background: #278667;height: 2em;line-height: 2em;text-shadow: 0 0 0 #000;text-align: center;">所有队员</div>
            <icms id="member_list" type="list">
                <item><![CDATA[
                    <input idvalue="all" name="dis_member_{f_MemberId}" id="dis_member_{f_MemberId}" class="dis_member_{f_MemberId}" type="checkbox">
                    <label for="dis_member_{f_MemberId}">{f_MemberName}({f_Number})</label>
                    ]]></item>
            </icms>
        </div>
        <div class="button">
            <div class="btn_container" data-ajax="false">
                <ul style="padding-left: 0">
                    <li class="ui-btn btn_change" idvalue="all">剔除</li>
                    <li class="ui-btn btn_change" idvalue="starting">进首发</li>
                    <li class="ui-btn btn_change" idvalue="alter">进替补</li>
                    <li class="ui-btn btn_change" data-ajax="false" id="btn_add" idvalue="add">新增队员</li>
                    <li class="ui-btn btn_change" idvalue="submit" onclick="submit();">确认提交</li>
                </ul>
            </div>
        </div>
        <div class="starting">
            <div style="color: #fff;background: #278667;height: 2em;line-height: 2em;text-shadow: 0 0 0 #000;text-align: center;">首发</div>

            <icms id="starting_list" type="list">
                <item><![CDATA[
                    <input idvalue="starting" name="member_{f_MemberId}" id="member_{f_MemberId}" type="checkbox">
                    <label for="member_{f_MemberId}">{f_MemberName}({f_Number})</label>
                    ]]></item>
            </icms>
        </div>
        <div class="alter">
            <div style="color: #fff;background: #278667;height: 2em;line-height: 2em;text-shadow: 0 0 0 #000;text-align: center;">替补</div>

            <icms id="alternate_list" type="list">
                <item><![CDATA[
                    <input idvalue="alternate" name="member_{f_MemberId}" id="member_{f_MemberId}" type="checkbox">
                    <label for="member_{f_MemberId}">{f_MemberName}({f_Number})</label>
                    ]]></item>
            </icms>
        </div>
    </form>
</div>
</body>
</html>