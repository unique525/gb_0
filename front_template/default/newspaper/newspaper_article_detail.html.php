<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{NewspaperArticleCiteTitle} - 长沙晚报</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/system_js/jquery.mobile-1.4.5/jquery.mobile-1.4.5.min.css" />
    <script src="/system_js/jquery-1.9.1.min.js"></script>
    <script src="/system_js/jquery.mobile-1.4.5/jquery.mobile-1.4.5.min.js"></script>
    <style>
        body{background:#efefef;font-family:SimSun, verdana,serif;}
        img, object { max-width: 100%;}
        @font-face {
            font-family: 'Son';
            /*src: url('/image_02/simsun.ttf') format('truetype');*/
            font-weight: normal;
            font-style: normal;
        }
    </style>
    <script type="text/javascript">
        $(function () {

        });
        $(document).on("pageinit","#pageone",function(){
            var content = $("#content").html().replaceAll("\n","<br /><br />");

            $("#content").html(content);

        });
        /**
         * 全文搜索替换
         */
        String.prototype.replaceAll = function(s1, s2) {
            return this.replace(new RegExp(s1, "gm"), s2);
        }
    </script>
</head>
<body>

<div data-role="page" id="pageone">
    <div data-role="content">
        <div style="text-align:center;">
            <h4 style='text-align:left;'>{NewspaperArticleCiteTitle}</h4>
            <h1 style='text-align:left; font-family: "Son",sans-serif'>{NewspaperArticleTitle}</h1>
            <h4 style='text-align:left; font-family: "Son",sans-serif'>{NewspaperArticleSubTitle}</h4>
        </div>

        <div>

            <icms id="newspaper_article_{NewspaperArticleId}" type="newspaper_article_pic_list" top="100">
                <item>
                    <![CDATA[
                    <div style="text-align:center;margin-bottom:10px;"><img src="{f_UploadFilePath}" alt="{f_Remark}" /></div>
                    <div style='text-align:center;margin-top:10px;margin-bottom:10px;font-family:宋体,verdana,serif '>{f_Remark}</div>
                    ]]>
                </item>
            </icms>


        </div>

        <div>
            <p style='margin:5px;line-height:150%;font-family:"Son",sans-serif; font-size:120%;' id="content">{NewspaperArticleContent}</p>
        </div>

    </div>
    <div style="padding:10px;">


    </div>


</div>

</body>
</html>