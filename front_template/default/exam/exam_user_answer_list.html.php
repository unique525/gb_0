<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <script type="text/javascript" src="/system_js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript">
        $().ready(function() {

            //格式化答案
            $(".span_answer").each(function(){

                var examUserAnswerId = parseInt($(this).attr("title"));
                var examQuestionId = parseInt($(this).attr("idvalue"));

                $(this).html(formatAnswer($(this).text(),examQuestionId,examUserAnswerId));
            });
            $(".answer_radio").change(function(){
                var examUserAnswerId = parseInt($(this).attr("title"));
                var answerValue = $(this).attr("value");
                $.ajax({
                    url: "default.php",
                    data: {mod: "exam_user_answer",a: "sub_answer",exam_user_answer_id:examUserAnswerId,answer_value: answerValue},
                    dataType: "jsonp"
                });
            });

        /**
         * 格式化状态值
         * @return {string}
         */
        function formatAnswer(answer,examQuestionId,examUserAnswerId){

            answer = answer.toString();
            var arrAnswer = answer.split("|#|");
            var result = "";

            for(var i=0;i<arrAnswer.length;i++){
                var answerValue = arrAnswer[i].substring(0,1);
                result += '<input type="radio" title="'+examUserAnswerId+'" idvalue="'+examQuestionId+'" value="'+answerValue+'" name="input_radio_answer_'+examQuestionId+'" class="answer_radio" />' + arrAnswer[i] + '<br />';
            }

            return result;
        }

        $("#subPaper").click(function(){
            var examUserPaperId = Request["exam_user_paper_id"];
            window.location.href = "/default.php?mod=exam_user_paper&a=paper_score&exam_user_paper_id="+examUserPaperId;
        }

        });
    </script>
</head>
<body>
<div align="left">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td style="text-align: center;">“三严三实”专题教育及党章知识竞赛题库</td>
        </tr>
    </table>
    <ul>
        <icms id="exam_user_answer_list" type="list">
            <item>
                <![CDATA[
                <li>
                    <table width="100%" cellpadding="0" cellspacing="0">
                        <tr>
                            <td style="width:300px;text-align:left;">{f_ExamQuestionTitle}</td>
                        </tr>
                        <tr>
                            <td style="width:150px;text-align:left;"><span id="span_answer_{f_ExamQuestionId}" title="{f_ExamUserAnswerId}" class="span_answer" idvalue="{f_ExamQuestionId}">{f_SelectItem}</span></td>
                        </tr>
                    </table>
                </li>
                ]]>
            </item>
        </icms>
    </ul>
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center"><input type="button" idvalue="" value="交卷" id="subPaper"></in></td>
        </tr>
    </table>
</div>
</body>
</html>