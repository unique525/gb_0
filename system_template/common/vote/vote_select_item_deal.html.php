<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link type="text/css" href="{rootpath}/images/common.css" rel="stylesheet" />
        <link type="text/css" href="{rootpath}/images/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
        <script type="text/javascript" src="{rootpath}/js/jq.js"></script>
        <script type="text/javascript" src="{rootpath}/js/jq1.4.2.js"></script>
        <script type="text/javascript" src="{rootpath}/js/jquery.cookie.js"></script>
        <script type="text/javascript" src="{rootpath}/js/common.js"></script>
        <script type="text/javascript" src="{rootpath}/js/jquery-ui-1.8.2.custom.min.js"></script>
        <script type="text/javascript" src="{rootpath}/js/ajaxfileupload_v2.1.js"></script>
        <script type="text/javascript">
            $(function(){
                $("#tbwindow_top").hide();
                var window_h = Request["height"];
                if(!window_h || window_h<=0){
                    window_h = 600;
                }
                var img = $("#img_titlepic");
                var theimage = new Image();
                theimage.src = img.attr("src");
                var tp = '{titlepic}';
                $("#preview_titlepic").click(function() {
                    if(tp != ''){

                        $("#dialog_titlepic").dialog({
                            width : theimage.width+30,
                            height : theimage.height+50
                        });

                    }
                    else{
                        alert('还没有上传题图');
                    }
                });                
            });
            function sub()
            {
                if($('#f_voteiselecttemtitle').val() == ''){
                    alert('请定义问题！');
                }
                else if($('#f_voteselectitemtitle').val().length>500){
                    alert('定义标题过长请不要超过500个字节！');
                }
                else {$('#mainform').submit();}
            }
        </script>
    </head>
    <body>
        <form id="mainform" enctype="multipart/form-data"  action="/vote/index.php?a=voteselectitemmanage&m={method}&voteid={voteid}&voteitemid={voteitemid}&voteselectitemid={voteselectitemid}&p={pageindex}" method="post">
            {tbwindow_top}
            <div style="margin: 0 auto;margin-left: 10px;">                
                <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
                    <tr style="display: none">
                        <td class="speline" height="30" align="right">ftpid：</td>
                        <td class="speline">
                            <input type="hidden" id="f_voteselectitemid" name="f_voteselectitemid" value="{voteselectitemid}" />
                            <input type="hidden" id="f_voteitemid" name="f_voteitemid" value="{voteitemid}" />
                        </td>
                    </tr>
                    <tr>
                        <td class="speline" height="30" align="right">选项名称：</td>
                         <td class="speline" title="{voteselectitemtitle}"><input name="f_voteselectitemtitle" id="f_voteselectitemtitle" value="{voteselectitemtitle}" type="text" class="inputbox" style=" width: 300px;" /></td>
                    </tr>
                    <tr>
                        <td class="speline" style="width:200px;height:35px;text-align: right;">题图：</td>
                        <td class="speline" style="text-align: left">
                            <input id="titlepic_upload" name="titlepic_upload" type="file" class="inputbox" style="width:300px; background: #ffffff; margin-top: 3px;" /> <span id="preview_titlepic" style="cursor:pointer">[预览]</span>
                            <div id="dialog_titlepic" title="题图预览（{titlepic}）" style="display:none;">
                                <div id="pubtable">
                                    <table>
                                        <tr>
                                            <td><img id="img_titlepic" src="{titlepic}" alt="titlepic" /></td>
                                        </tr></table>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="speline" style="width:200px;height:35px;text-align: right;">直接转向网址：</td>
                        <td class="speline" style="text-align: left"><input type="text" class="inputbox" id="f_directurl" name="f_directurl" value="{directurl}" style=" width: 600px;font-size:14px;" maxlength="200" /> (设置直接转向网址后，文档将直接转向到该网址)</td>
                    </tr>
                    <tr>
                        <td class="speline" height="30" align="right">排序：</td>
                        <td class="speline"><input name="f_sort" id="f_sort" value="{sort}" type="text" class="inputnumber" style=" width: 60px;" />(注:输入数字,数值越大越靠前)</td>
                    </tr>
                    
                    <tr>
                        <td class="speline" height="30" align="right">状态：</td>
                        <td class="speline">
                            <select id="f_state" name="f_state">
                                <option value="0" {s_state_0}>启用</option>
                                <option value="100" {s_state_100}>停用</option>
                            </select>
                        </td>
                    </tr>    
                    <tr>
                       <td class="speline" height="30" align="right">票数：</td>
                       <td class="speline">{recordcount}</td>
                    </tr>
                    <tr>
                       <td class="speline" height="30" align="right">加票数：</td>
                        <td class="speline"><input name="f_addcount" id="f_addcount" value="{addcount}" type="text" class="inputnumber" style=" width: 60px;" /></td>
                    
                    </tr>
                    <tr>
                        <td colspan="2" height="30" align="center">
                            <input class="btn" value="确 认" type="button" onclick="sub()" />
                        </td>
                    </tr>
                </table>
            </div>
        </form>
    </body>
</html>
