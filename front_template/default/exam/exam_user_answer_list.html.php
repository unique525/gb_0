<!DOCTYPE html>
<html>
<head>
    <title>开始答题</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <script type="text/javascript" src="/system_js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="/system_js/common.js"></script>
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
                    data: {mod: "exam_user_answer",a: "async_sub_answer",exam_user_answer_id:examUserAnswerId,answer_value: answerValue},
                    dataType: "jsonp",
                    jsonp:"JsonpCallBack",
                    success:function(data){

                    }
                });
            });

            $("#subPaper").click(function(){
                var examUserPaperId = Request["exam_user_paper_id"];
                window.location.href = "/default.php?mod=exam_user_paper&a=finished&exam_user_paper_id="+examUserPaperId;
            })

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
    </script>

    <style>
        /*--css reset--*/
        html,body,h1,h2,h3,h4,h5,h6,p,ol,ul,li,pre,code,address,variable,form,fieldset,blockquote {
            padding:0;
            margin:0;
            font-size:62.5%;
            font-weight:normal;
            font-family:"Microsoft YaHei";
            color:#172d06;
        }
        ol{margin-left:0; list-style:none;}
        ul{margin-left:0; list-style:none;}

        a{ color:#3e4a61; text-decoration:none;}
        a:hover{ text-decoration:underline;}
        img,input{ vertical-align:middle;}
        .clean{ clear:both;}
        .left{ float:left;}
        .right{ float:right;}
        .hidden{ display:none;}
        img{ border:none}
        /*--css reset over--*/

        body{ background:#fff4e8;}

        .title{ font-size:1.5rem; line-height:6rem; background:#fc7a3e; color:#fff;}
        .q_list{ padding:20px 5%;}
        .q_list li{ font-size:1.5rem; line-height:3rem; border-bottom:1px dashed #333; padding:15px 0;}
        .q_list li input{}
        .submit {padding-bottom:44px; padding-top:20px;}
        .submit input{ width:100px; height:40px; line-height:40px; border:none; background:#fc7a3e; color:#fff;  font-size:17px; font-family:"Microsoft YaHei";}

    </style>

</head>
<body>
<div>
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td class="title"  style="text-align: center;"></td>
        </tr>
    </table>
    <ul class="q_list">

        <icms id="exam_user_answer_list" type="list">
            <item>
                <![CDATA[
                <li>
                    <table width="100%" cellpadding="0" cellspacing="0">
                        <tr>
                            <td style="width:300px;text-align:left; font-size:1.5rem; font-weight:bold;">{f_ExamQuestionTitle}</td>
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
            <td class="submit"  align="center"><input type="button" idvalue="" value="交    卷" id="subPaper"></in></td>
        </tr>
    </table>
</div>
</body>
</html>