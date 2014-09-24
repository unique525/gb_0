<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>选择来源</title>
    {common_head}
    <script type="text/javascript">

        $(function(){

            $('#tabs').tabs();

            $('#search_key').keyup(function() {
                var searchKey = $('#search_key').val();

                if(searchKey.length>0){
                    $.post("/default.php?mod=source&m=search&search_key=" + searchKey + "", {
                        search_result: $(this).html()
                    }, function(xml) {
                        $("#search_result").css("display","block");
                        $("#search_result").html(xml);
                    });
                }
                else{

                }

            });

            var btnSelect = $(".btn_select");
            btnSelect.click(function() {
                var sourceName = $(this).attr("title");
                parent.setSourceName(sourceName);
                parent.closeAbstractBox();
            });

        });

    </script>
    <style type="text/css">
        body{font-size: 12px;}
    </style>
</head>

<body>
<div class="source_select" style="height:30px; padding: 3px 3px 0;">
    <div>
        <label for="search_key"></label><input type="text" class="input_box" id="search_key" name="search_key" /> <input type="button" class="btn4" value="查询" id="search_btn" name="search_btn" />
    </div>
    <div id="search_result" style="
    display:none; position:absolute;
    line-height:200%; z-index:100;
    background: #ffffff; border: solid 1px #C2CBE0; padding: 8px; width: 95%;">

    </div>
</div>

<div id="tabs">
    <ul>
        <li><a href="#tabs-1">全部来源</a></li>
        <li><a href="#tabs-2">本地稿源</a></li>
        <li><a href="#tabs-3">中央重点网站</a></li>
        <li><a href="#tabs-4">中央新闻单位</a></li>
        <li><a href="#tabs-5">地方新闻网站</a></li>
        <li><a href="#tabs-6">城市网盟</a></li>
        <li><a href="#tabs-7">地方重点报刊</a></li>
        <li><a href="#tabs-8">频道常用稿源</a></li>
    </ul>
    <div id="tabs-1" style="height:403px; overflow:scroll;line-height:200%;">
        <div style="text-align: center;"></div>
        <div style="">
            <icms id="source_all" type="list">
                <item>
                    <![CDATA[
                    <div style="width:20%;float:left;">
                        <span style="cursor: pointer;" class="btn_select" title="{f_SourceName}">{f_SourceName}</span>
                    </div>
                    ]]>
                </item>
            </icms>
        </div>
    </div>
    <div id="tabs-2" style="height:403px; overflow:scroll;line-height:200%;">
        <icms id="source_1" type="list">
            <item>
                <![CDATA[
                <div style="width:20%;float:left;">
                    <span style="cursor: pointer;" class="btn_select" title="{f_SourceName}">{f_SourceName}</span>
                </div>
                ]]>
            </item>
        </icms>
    </div>
    <div id="tabs-3" style="height:403px; overflow:scroll;line-height:200%;">
        <icms id="source_2" type="list">
            <item>
                <![CDATA[
                <div style="width:20%;float:left;">
                    <span style="cursor: pointer;" class="btn_select" title="{f_SourceName}">{f_SourceName}</span>
                </div>
                ]]>
            </item>
        </icms>
    </div>
    <div id="tabs-4" style="height:403px; overflow:scroll;line-height:200%;">
        <icms id="source_3" type="list">
            <item>
                <![CDATA[
                <div style="width:20%;float:left;">
                    <span style="cursor: pointer;" class="btn_select" title="{f_SourceName}">{f_SourceName}</span>
                </div>
                ]]>
            </item>
        </icms>
    </div>
    <div id="tabs-5" style="height:403px; overflow:scroll;line-height:200%;">
        <icms id="source_4" type="list">
            <item>
                <![CDATA[
                <div style="width:20%;float:left;">
                    <span style="cursor: pointer;" class="btn_select" title="{f_SourceName}">{f_SourceName}</span>
                </div>
                ]]>
            </item>
        </icms>
    </div>
    <div id="tabs-6" style="height:403px; overflow:scroll;line-height:200%;">
        <icms id="source_5" type="list">
            <item>
                <![CDATA[
                <div style="width:20%;float:left;">
                    <span style="cursor: pointer;" class="btn_select" title="{f_SourceName}">{f_SourceName}</span>
                </div>
                ]]>
            </item>
        </icms>
    </div>
    <div id="tabs-7" style="height:403px; overflow:scroll;line-height:200%;">
        <icms id="source_6" type="list">
            <item>
                <![CDATA[
                <div style="width:20%;float:left;">
                    <span style="cursor: pointer;" class="btn_select" title="{f_SourceName}">{f_SourceName}</span>
                </div>
                ]]>
            </item>
        </icms>
    </div>
    <div id="tabs-8" style="height:403px; overflow:scroll;line-height:200%;">
        <icms id="source_7" type="list">
            <item>
                <![CDATA[
                <div style="width:20%;float:left;">
                    <span style="cursor: pointer;" class="btn_select" title="{f_SourceName}">{f_SourceName}</span>
                </div>
                ]]>
            </item>
        </icms>
    </div>
</div>
</body>
</html>
