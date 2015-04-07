<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}

    <script type="text/javascript">

        $(function () {


            $('#tabs').tabs();

            //日期控件
            var beginDate="{BeginDate}";
            if(beginDate==""){
                beginDate=new Date();
            }
            var endDate="{EndDate}";
            if(endDate==""){
                endDate=new Date();
            }
            $("#BeginDate").datepicker({
                dateFormat: 'yy-mm-dd',
                numberOfMonths: 3,
                showButtonPanel: true,
                maxDate: endDate,
                onSelect:function(dateText,inst){
                    $("#EndDate").datepicker("option","minDate",dateText);
                }
            });
            $("#EndDate").datepicker({
                dateFormat: 'yy-mm-dd',
                numberOfMonths: 3,
                showButtonPanel: true,
                minDate: beginDate,
                onSelect:function(dateText,inst){
                    $("#BeginDate").datepicker("option","maxDate",dateText);
                }
            });
        });


        //提交
        function submitForm() {
            var submit=1;
            var dialogContent="";
            if ($("#sel_site").val() < 0) {
                dialogContent+="请选择站点<br>";
            }
            if ($("#BeginDate").val() == "") {
                dialogContent+="请选择开始日期<br>";
            }
            if ($("#EndDate").val() == "") {
                dialogContent+="请选择结束日期<br>";
            }

            if(dialogContent!=""){
                $("#dialog_box").dialog({width: 300, height: 130});
                $("#dialog_content").html(dialogContent);
                submit=0;
            }
            if(submit==1) {
                $('#main_form').submit();
            }
        }

    </script>
    <style>
        .statistician_list {width:88%;margin:0 auto;padding:0 30px;box-shadow: 0px 1px 12px rgba(0, 0, 0, 0.2)}
    </style>
</head>
<body>
{common_body_deal}
<div class="div_deal">
    <form id="main_form" enctype="multipart/form-data"
          action="/default.php?secu=manage&mod=task&m={method}&tab_index={TabIndex}"
          method="post">
        <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="spe_line" height="40" align="right">
                    <input class="btn" value="确 认" type="button" onclick="submitForm()"/>
                    <input class="btn" value="取 消" type="button" onclick="closeTab()"/>
                </td>
            </tr>
        </table>
        <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">

            <tr>
                <script type="text/javascript">
                    $("#sel_group").find("option[value='{ManageUserGroupId}']").attr("selected",true);
                </script>
                <td class="spe_line" style=" height: 40px; font-size: 14px; font-weight: bold; width:250px">
                    <label for="sel_site">选择一个站点： </label>
                    <select id="sel_site" name="SiteId">
                        <option value="-1">请选择一个站点</option>
                        <icms id="site_list" type="list">
                            <item>
                                <![CDATA[
                                <option value="{f_SiteId}">{f_SiteName}</option>
                                ]]>
                            </item>
                        </icms>
                        <option value="0">所有站点</option>
                    </select>
                </td>
                <script type="text/javascript">
                    $("#sel_site").find("option[value='{SiteId}']").attr("selected",true);
                </script>
                <td class="spe_line" style=" height: 40px; font-size: 14px; font-weight: bold; width:280px">
                    <label for="BeginDate">开始时间：(当日00:00)</label>
                    <input type="text" class="GetDate" id="BeginDate" name="BeginDate" value="{BeginDate}"  style=" width: 85px;font-size:14px;" maxlength="20"  />
                </td>
                <td class="spe_line" style=" height: 40px; font-size: 14px; font-weight: bold; width:280px">
                    <label for="EndDate">结束时间：(当日00:00)</label>
                    <input type="text" class="GetDate" id="EndDate" name="EndDate" value="{EndDate}"  style=" width: 85px;font-size:14px;" maxlength="20"  />
                </td>
                <td class="spe_line" style=" height: 40px; font-size: 14px; font-weight: bold; ">
                    <label for="Count">取前</label>
                    <input type="text" id="Count" name="Count" value="20"  style=" width: 35px;font-size:14px;" maxlength="20"  />条(设0取所有）
                </td>
            </tr>
        </table>
        <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td height="60" align="center">
                    <input class="btn" value="确 认" type="button" onclick="submitForm()"/>
                    <input class="btn" value="取 消" type="button" onclick="closeTab()"/>
                </td>
            </tr>
        </table>
    </form>
    <table width="99%">
        <tr>
            <td>
                <div class="statistician_list">

                    <icms id="newspaper_article_list" type="list">
                        <header>
                            <![CDATA[
                            <div class="grid_item" style="padding-top:30px">
                                标题：{f_NewspaperArticleTitle}：<br />
                                点击：{f_HitCount}
                            </div>
                            ]]>
                        </header>
                        <item>
                            <![CDATA[
                            <div class="grid_item" style="padding-top:7px">
                                标题：{f_NewspaperArticleTitle}：<br />
                                点击：{f_HitCount}
                            </div>
                            ]]>
                        </item>
                        <footer>
                            <![CDATA[
                            <div class="grid_item" style="padding-bottom:30px">
                                标题：{f_NewspaperArticleTitle}：<br />
                                点击：{f_HitCount}
                            </div>
                            ]]>
                        </footer>
                    </icms>


                </div>
            </td>
        </tr>
    </table>
</div>
</body>
</html>
