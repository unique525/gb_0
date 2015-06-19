<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    {common_head}
    <script type="text/javascript">

        $(function() {
            //$(document).tooltip();

            $("#btn_select_all").click(function(event) {
                event.preventDefault();
                var docInput = $("[name='doc_input']");
                if (docInput.prop("checked")) {
                    docInput.prop("checked", false);//取消全选
                } else {
                    docInput.prop("checked", true);//全选
                }
            });

            var btnCreate = $("#btn_create");
            btnCreate.css("cursor", "pointer");
            btnCreate.click(function(event) {
                event.preventDefault();
                window.location.href = '/default.php?secu=manage&mod=exam_question_class&m=create&rank=0&site_id=' + parent.G_NowSiteId +
                    '&channel_id=' + parent.G_SelectedChannelId;
            });

            var btnAllExamQuestion = $("#btn_all_exam_question");
            btnAllExamQuestion.css("cursor", "pointer");
            btnAllExamQuestion.click(function(event) {
                event.preventDefault();
                parent.G_TabUrl = '/default.php?secu=manage&mod=exam_question&m=list&site_id=' + parent.G_NowSiteId +
                    '&channel_id=' + parent.G_SelectedChannelId;;
                parent.G_TabTitle = '全部试题';
                parent.addTab();
            });





            //格式化站点状态
            $(".span_state").each(function(){
                $(this).text(FormatExamQuestionClassState($(this).text()));
            });


            //选中时的样式变化
            $('.grid_item').click(function() {
                if ($(this).hasClass('grid_item_selected')) {
                    $(this).removeClass('grid_item_selected');
                } else {
                    $(this).addClass('grid_item_selected');
                }
            });
        });

        /**
         * 格式化状态值
         * @return {string}
         */
        function FormatExamQuestionClassState(state) {
            state = parseInt(state);
            switch (state) {
                case 0:
                    return "正常";
                    break;
                case 100:
                    return "停用";
                    break;
                default:
                    return "未知状态";
                    break;
            }
        }


    </script>
</head>
<body>
<div class="div_list">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td id="td_main_btn">
                <input id="btn_create" class="btn2" value="新建试题分类" title="" type="button"/>
                <input id="btn_all_exam_question" class="btn2" value="管理全部试题"  type="button"/>
            </td>
        </tr>
    </table>
    <table class="grid" width="100%" cellpadding="0" cellspacing="0">
        <tr class="grid_title">
            <td style="width: 30px; text-align: center; cursor: pointer;" id="btn_select_all">全</td>
            <td style="width: 40px; text-align: center;">编辑</td>
            <td style="width: 90px; text-align: center;">状态</td>
            <td style="width: 40px;"></td>
            <td>名称</td>
            <td style="width: 50px; text-align: center;">排序</td>
            <td style="width: 120px;">管理试题</td>
        </tr>
    </table>
    <ul id="sort_grid">

        <icms id="exam_question_class_list" type="list">
            <item>
                <![CDATA[

                <li id="exam_question_class_sort_{f_ExamQuestionClassId}" class="rank_one">
                    <table class="grid" width="100%" cellpadding="0" cellspacing="0">
                        <tr class="grid_item">
                            <td class="spe_line2" style="width:30px;text-align:center;"><input class="doc_input"
                                                                                               type="checkbox"
                                                                                               name="doc_input"
                                                                                               value="{f_ExamQuestionClassId}"/>
                            </td>
                            <td class="spe_line2" style="width:40px;text-align:center;"><a
                                    href="/default.php?secu=manage&mod=exam_question_class&m=modify&exam_question_class_id={f_ExamQuestionClassId}&site_id={f_SiteId}&rank={f_Rank}&channel_id={ChannelId}"><img
                                        class="edit_doc system_image"
                                        src="/system_template/{template_name}/images/manage/edit.gif"
                                        idvalue="{f_ExamQuestionClassId}" alt="编辑"/></a></td>
                            <td class="spe_line2" style="width:90px;text-align:center;"><span
                                    id="span_state_{f_ExamQuestionClassId}" class="span_state">{f_State}</span></td>
                            <td class="spe_line2" style="width:40px;text-align:left;">

                            </td>
                            <td class="spe_line2">
                                <span>{f_ExamQuestionClassName}</span>
                                <a href="/default.php?secu=manage&mod=exam_question_class&m=create&parent_id={f_ExamQuestionClassId}&rank=1&site_id={SiteId}&channel_id={ChannelId}">[新增子分类]</a>
                            </td>
                            <td class="spe_line2" style="width:36px;">

                            </td>
                            <td class="spe_line2" style="width:50px;text-align:center;" title="文档的排序数字，越大越靠前">{f_Sort}
                            </td>
                            <td class="spe_line2" style="width:120px;text-align:left;" title="">
                                <a href="/default.php?secu=manage&mod=exam_question&m=list&exam_question_class_id={f_ExamQuestionClassId}"><span>管理试题</span></a>
                            </td>
                        </tr>
                    </table>
                </li>
                {child}
                ]]>
            </item>
            <child>
                <![CDATA[
                <li id="exam_question_class_sort_{f_ExamQuestionClassId}" style="margin-left:20px;">
                    <table class="grid" width="100%" cellpadding="0" cellspacing="0">
                        <tr class="grid_item">
                            <td class="spe_line2" style="width:30px;text-align:center;"><input class="doc_input" type="checkbox" name="docinput" value="{f_ExamQuestionClassId}" /></td>
                            <td class="spe_line2" style="width:40px;text-align:center;">
                                <a href="/default.php?secu=manage&mod=exam_question_class&m=modify&exam_question_class_id={f_ExamQuestionClassId}&site_id={f_SiteId}&channel_id={ChannelId}">
                                    <img class="edit_doc system_image" src="/system_template/{template_name}/images/manage/edit.gif" idvalue="{f_ExamQuestionClassId}" alt="编辑" />
                                </a></td>
                            <td class="spe_line2" style="width:90px;text-align:center;"><span id="span_state_{f_ExamQuestionClassId}" class="span_state">{f_State}</span></td>
                            <td class="spe_line2" style="width:40px;text-align:left;">

                            </td>
                            <td class="spe_line2"><span>{f_ExamQuestionClassName}</span>
                                <a href="/default.php?secu=manage&mod=exam_question_class&m=create&parent_id={f_ExamQuestionClassId}&rank=2&site_id={SiteId}&channel_id={ChannelId}">[新增三级分类]</a>
                            </td>
                            <td class="spe_line2" style="width:36px;">
                            </td>
                            <td class="spe_line2" style="width:50px;text-align:center;" title="文档的排序数字，越大越靠前">{f_Sort}</td>
                            <td class="spe_line2" style="width:120px;text-align:left;" title="">
                                <a href="/default.php?secu=manage&mod=exam_question&m=list&exam_question_class_id={f_ExamQuestionClassId}"><span>管理试题</span></a>
                            </td>
                        </tr>
                    </table>
                </li>
                {third}
                ]]>
            </child>
            <third>
                <![CDATA[
                <li id="exam_question_class_sort_{f_ExamQuestionClassId}" style="margin-left:20px;">
                    <table class="grid" width="100%" cellpadding="0" cellspacing="0">
                        <tr class="grid_item">
                            <td class="spe_line2" style="width:30px;text-align:center;"><input class="doc_input" type="checkbox" name="docinput" value="{f_ExamQuestionClassId}" /></td>
                            <td class="spe_line2" style="width:40px;text-align:center;">
                                <a href="/default.php?secu=manage&mod=exam_question_class&m=modify&exam_question_class_id={f_ExamQuestionClassId}&site_id={f_SiteId}&channel_id={ChannelId}">
                                    <img class="edit_doc system_image" src="/system_template/{template_name}/images/manage/edit.gif" idvalue="{f_ExamQuestionClassId}" alt="编辑" />
                                </a></td>
                            <td class="spe_line2" style="width:90px;text-align:center;"><span id="span_state_{f_ExamQuestionClassId}" class="span_state">{f_State}</span></td>
                            <td class="spe_line2" style="width:40px;text-align:left;">

                            </td>
                            <td class="spe_line2"><span>{f_ExamQuestionClassName}</span></td>
                            <td class="spe_line2" style="width:36px;">
                            </td>
                            <td class="spe_line2" style="width:50px;text-align:center;" title="文档的排序数字，越大越靠前">{f_Sort}</td>
                            <td class="spe_line2" style="width:120px;text-align:left;" title="">
                                <a href="/default.php?secu=manage&mod=exam_question&m=list&exam_question_class_id={f_ExamQuestionClassId}"><span>管理试题</span></a>
                            </td>
                        </tr>
                    </table>
                </li>

                ]]>
            </third>
        </icms>
    </ul>
    <div>{pager_button}</div>
</div>
</body>
</html>
