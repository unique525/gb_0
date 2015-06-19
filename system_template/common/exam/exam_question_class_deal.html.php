<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title></title>
    {common_head}
    <script type="text/javascript">
        $(function () {


        });

        function submitForm(closeTab) {
            if ($('#f_ExamQuestionClassName').val() == '') {
                $("#dialog_box").dialog({width: 300, height: 100});
                $("#dialog_content").html("请输入分类名称");
            } else {
                if(closeTab == 1){
                    $("#CloseTab").val("1");
                }else{
                    $("#CloseTab").val("0");
                }

                $("#mainForm").attr("action","/default.php?secu=manage&mod=exam_question_class&m={method}&site_id={SiteId}&rank={Rank}&parent_id={ParentId}&exam_question_class_id={ExamQuestionClassId}&channel_id={ChannelId}&tab_index="+parent.G_TabIndex+"");
                $('#mainForm').submit();
            }
        }
    </script>
</head>
<body>
{common_body_deal}
<form id="mainForm" enctype="multipart/form-data" method="post">
    <div>
        <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="spe_line" height="40" align="right">
                    <input class="btn" value="确认并关闭" type="button" onclick="submitForm(1)"/> <input class="btn" value="确认并继续新增" type="button" onclick="submitForm(0)"/> <input class="btn" value="取 消" type="button" onclick="closeTab()"/>
                </td>
            </tr>

        </table>
        <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
            <tr class="rank_1">
                <td class="spe_line" height="30" align="right">上级分类：</td>
                <td class="spe_line">{ParentName}
                    <input name="f_ParentId" type="hidden" value="{ParentId}"/>
                    <input name="f_SiteId" type="hidden" value="{SiteId}"/>
                    <input name="f_ChannelId" type="hidden" value="{ChannelId}"/>
                    <input name="f_Rank" type="hidden" value="{Rank}"/>
                    <input id="CloseTab" name="CloseTab" type="hidden" value="0"/>
                </td>
            </tr>
            <tr>
                <td class="spe_line" width="20%" height="30" align="right"><label for="f_ExamQuestionClassName">分类名称：</label></td>
                <td class="spe_line"><input name="f_ExamQuestionClassName" id="f_ExamQuestionClassName" value="{ExamQuestionClassName}" type="text" class="input_box" style="width:300px;"/></td>
            </tr>



            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_SelectType0Count">填空题 随机抽取数量（非必抽题）：</label></td>
                <td class="spe_line">
                    <input id="f_SelectType0Count" name="f_SelectType0Count" type="text" value="{SelectType0Count}" maxlength="5" class="input_number"/>
                </td>
            </tr>

            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_MustSelectType0Count">填空题 随机抽取数量（必抽题）：</label></td>
                <td class="spe_line">
                    <input id="f_MustSelectType0Count" name="f_MustSelectType0Count" type="text" value="{MustSelectType0Count}" maxlength="5" class="input_number"/>
                </td>
            </tr>

            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_TypeScore0">填空题分数：</label></td>
                <td class="spe_line">
                    <input id="f_TypeScore0" name="f_TypeScore0" type="text" value="{TypeScore0}" maxlength="5" class="input_price"/>
                </td>
            </tr>

            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_SelectType1Count">单选题 随机抽取数量（非必抽题）：</label></td>
                <td class="spe_line">
                    <input id="f_SelectType1Count" name="f_SelectType1Count" type="text" value="{SelectType1Count}" maxlength="5" class="input_number"/>
                </td>
            </tr>

            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_MustSelectType1Count">单选题 随机抽取数量（必抽题）：</label></td>
                <td class="spe_line">
                    <input id="f_MustSelectType1Count" name="f_MustSelectType1Count" type="text" value="{MustSelectType1Count}" maxlength="5" class="input_number"/>
                </td>
            </tr>

            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_TypeScore1">单选题 分数：</label></td>
                <td class="spe_line">
                    <input id="f_TypeScore1" name="f_TypeScore1" type="text" value="{TypeScore1}" maxlength="5" class="input_price"/>
                </td>
            </tr>

            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_SelectType2Count">多选题 随机抽取数量（非必抽题）：</label></td>
                <td class="spe_line">
                    <input id="f_SelectType2Count" name="f_SelectType2Count" type="text" value="{SelectType2Count}" maxlength="5" class="input_number"/>
                </td>
            </tr>

            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_MustSelectType2Count">多选题 随机抽取数量（必抽题）：</label></td>
                <td class="spe_line">
                    <input id="f_MustSelectType2Count" name="f_MustSelectType2Count" type="text" value="{MustSelectType2Count}" maxlength="5" class="input_number"/>
                </td>
            </tr>

            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_TypeScore2">多选题 分数：</label></td>
                <td class="spe_line">
                    <input id="f_TypeScore2" name="f_TypeScore2" type="text" value="{TypeScore2}" maxlength="5" class="input_price"/>
                </td>
            </tr>

            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_SelectType3Count">判断题 随机抽取数量（非必抽题）：</label></td>
                <td class="spe_line">
                    <input id="f_SelectType3Count" name="f_SelectType3Count" type="text" value="{SelectType3Count}" maxlength="5" class="input_number"/>
                </td>
            </tr>

            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_MustSelectType3Count">判断题 随机抽取数量（必抽题）：</label></td>
                <td class="spe_line">
                    <input id="f_MustSelectType3Count" name="f_MustSelectType3Count" type="text" value="{MustSelectType3Count}" maxlength="5" class="input_number"/>
                </td>
            </tr>

            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_TypeScore3">判断题 分数：</label></td>
                <td class="spe_line">
                    <input id="f_TypeScore3" name="f_TypeScore3" type="text" value="{TypeScore3}" maxlength="5" class="input_price"/>
                </td>
            </tr>

            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_Sort">排序号：</label></td>
                <td class="spe_line">
                    <input id="f_Sort" name="f_Sort" type="text" value="{Sort}" maxlength="10" class="input_number"/>
                </td>
            </tr>

        </table>
        <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td height="60" align="center">
                    <input class="btn" value="确认并关闭" type="button" onclick="submitForm(1)"/> <input class="btn" value="确认并继续新增" type="button" onclick="submitForm(0)"/> <input class="btn" value="取 消" type="button" onclick="closeTab()"/>
                </td>
            </tr>
        </table>
    </div>
</form>
</body>
</html>