<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>长沙晚报</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/system_js/jquery.mobile-1.4.5/jquery.mobile-1.4.5.min.css" />
    <script src="/system_js/jquery-1.9.1.min.js"></script>
    <script src="/system_js/jquery.mobile-1.4.5/jquery.mobile-1.4.5.min.js"></script>
    <script type="text/javascript">
        $(document).on("pageinit","#pageone",function(){
            /**
            $(".img_title").each(function(){
                var imgSrc = $(this).attr("src");
                if(imgSrc.length<=0){
                    $(this).remove();
                }
            });
            */
        });
    </script>
</head>
<body>

<div data-role="page" id="pageone">
    <div data-role="content">

        <ul data-role="listview">
            <icms id="newspaper_page_{NewspaperPageId}" type="newspaper_article_list" top="100">
                <item>
                    <![CDATA[
                    <li><a href="/default.php?mod=newspaper_article&a=detail&newspaper_article_id={f_NewspaperArticleId}">

                            <h2>{f_NewspaperArticleTitle}</h2>
                            <p>{f_NewspaperArticleSubTitle}</p>
                        </a>
                    </li>
                    ]]>
                </item>
            </icms>

        </ul>
    </div>
</div>

</body>
</html>