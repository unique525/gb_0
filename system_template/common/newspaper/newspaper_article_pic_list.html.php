<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title></title>
    {common_head}
    <script type="text/javascript">
        <!--
        $(function () {

            $("#btn_create").click(function (event) {
                event.preventDefault();
                window.location.href = '/default.php?secu=manage&mod=newspaper_article_pic&m=create&newspaper_article_id={NewspaperArticleId}&tab_index={TabIndex}'+ '&site_id=' + parent.G_NowSiteId;

            });

            $(".btn_modify").click(function (event) {
                event.preventDefault();
                var newspaperArticlePicId=$(this).attr("idvalue");
                window.location.href = '/default.php?secu=manage&mod=newspaper_article_pic&m=modify' + '&newspaper_article_id={NewspaperArticleId}&&tab_index={TabIndex}&newspaper_article_pic_id=' + newspaperArticlePicId + '&site_id=' + parent.G_NowSiteId;
            });


            //格式化状态
            $(".span_state").each(function(){
                $(this).html(formatState($(this).text()));
            });

            //格式化题图按钮
            $(".btn_update_title_pic").each(function(){
                $(this).html(IsTitlePic($(this).attr("idvalue")));
            });

            //开启
            $(".img_open").click(function(){
                var newspaperArticlePicId = parseInt($(this).attr("idvalue"));
                var state = 0; //开启状态
                modifyState(newspaperArticlePicId, state);
            });
            //停用
            $(".img_close").click(function(){
                var newspaperArticlePicId = parseInt($(this).attr("idvalue"));
                var state = 100; //开启状态
                modifyState(newspaperArticlePicId, state);
            });
        });


        function modifyState(newspaperArticlePicId,state){
            if(newspaperArticlePicId>0){
                $.ajax({
                    type: "get",
                    url: "/default.php?secu=manage&mod=newspaper_article_pic&m=async_modify_state",
                    data: {
                        newspaper_article_pic_id: newspaperArticlePicId,
                        state:state
                    },
                    dataType: "jsonp",
                    jsonp: "jsonpcallback",
                    success: function(data) {
                        $(".btn_update_title_pic").each(function(){
                            $(this).html(IsTitlePic($(this).attr("idvalue")));
                        });
                    }
                });
            }
        }

        /**
         * 格式化状态值
         * @return {string}
         */
        function formatState(state){
            switch (state){
                case "0":
                    return "启用";
                    break;
                case "100":
                    return "<"+"span style='color:#990000'>停用<"+"/span>";
                    break;
                default :
                    return "未知";
                    break;
            }
        }

        /**
         * 格式化题图按钮
         * @return {string}
         */
        function IsTitlePic(uploadFileId){
            var titlePicId="{TitlePic1UploadFileId}";
            if(uploadFileId==titlePicId){
                return "<"+"span style='color:green'>当前题图<"+"/span>"
            }else{
                return "设为题图";
            }
        }



        function AjaxUpdateTitlePic1(UploadFileId){
            if(UploadFileId>0){
                $.ajax({
                    type: "get",
                    url: "/default.php?secu=manage&mod=newspaper_article&m=async_modify_tp1",
                    data: {
                        newspaper_article_id: {NewspaperArticleId},
                        title_pic_1_upload_file_id: UploadFileId
                    },
                    dataType: "jsonp",
                    jsonp: "jsonpcallback",
                    success: function(data) {
                        if(data["result"]>0){
                            window.location.reload();
                        }else{
                            alert("修改失败");
                        }
                    }
                });
            }
        }
        -->
    </script>
    <style>

        /***** 图片管理css  *****/
        .li_pic_img_item{
            width:276px;
            float: left;
            margin: 10px;
            box-shadow: 0 0 5px #666;
            padding-right: 4px;
            background: none repeat scroll 0 0 #EFEFEF;
            position: relative;
        }
        .notice {width:100%;height:100%;position: absolute;background-color: rgba(3, 3, 3, 0.7);z-index: 5;display: none}
        .pic_img_container {width:280px;height:190px;background-color: rgb(253, 253, 253)}
        .pic_img_container img{max-width:280px;max-height:190px;display: block}
        .pic_img_title{padding:3px 5px;width:266px;height:22px;position:relative}
        .pic_img_title input{width:265px;height:15px;position: absolute;margin-top:3px;z-index: 10}
        .pic_img_state{padding:3px 5px;position: relative}
        .pic_img_state span{float:left;}
        .pic_img_state img{vertical-align:middle;cursor: pointer}
        .btn_update_title_pic {float:right;cursor: pointer;border: 1px solid #B0B0B0;padding:0 5px;right:0;top: 3px;background: #eee;z-index: 10;position: absolute}
    </style>
</head>
<body>
<div class="div_list">

    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td id="td_main_btn" width="105">
                <a class="btn2" style="padding:3px 10px" href="/default.php?secu=manage&mod=newspaper_article&m=list&newspaper_page_id={NewspaperPageId}" idvalue="{NewspaperPageId}" title="返回文档管理">返回文档管理</a>
            </td>
            <td id="td_main_btn">
                <a class="btn2" id="btn_create" style="padding:3px 10px;cursor: pointer"  idvalue="{NewspaperArticleId}" title="新增">新增</a>
            </td>
            <td style="text-align: right; margin-right: 8px;">
                <div id="search_box">
                    <label for="search_type_box"></label>
                    <select name="search_type_box" id="search_type_box" style="display: none">
                        <option value="default">基本搜索</option>
                    </select>
                    <label for="search_key"></label><input type="text" id="search_key" name="search_key" class="input_box"/>
                    <input id="btn_search" class="btn2" value="查 询" type="button"/>
                    <span id="search_type" style="display: none"></span>
                </div>
            </td>
        </tr>
    </table>

        <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td>

                    <div id="tabs-5">
                        <div class="div_list" id="">
                            <ul id="old_pic_list">

                                <icms id="newspaper_article_pic_list" type="list">
                                    <item>
                                        <![CDATA[
                                        <li class="li_pic_img_item" id="UploadFileId_{f_UploadFileId}" >
                                            <div class="notice" id="notice_{f_UploadFileId}"></div>

                                            <table class="pic_img_container" cellspacing="0"><tr><td align="center" valign="center"><img class="pic_slider_img" onclick="showOriImg('{f_UploadFilePath}')" idvalue="{f_UploadFileId}" src="{f_UploadFilePath}" style="cursor:pointer;" title="点击查看原始图片"/></td></tr></table>
                                            <div class="pic_img_state" style="padding:3px 5px;">
                                                <span id="span_state_{f_NewspaperArticlePicId}" class="span_state" idvalue="{f_NewspaperArticlePicId}">{f_State}</span>
                                                <img class="img_open" idvalue="{f_NewspaperArticlePicId}" title="启用" src="/system_template/{template_name}/images/manage/start.jpg"
                                                     alt="{f_UploadFileId}"/>
                                                <img class="img_close" idvalue="{f_NewspaperArticlePicId}" title="停用" src="/system_template/{template_name}/images/manage/stop.jpg"
                                                     alt="{f_UploadFileId}"/>
                                                <img class="btn_modify" src="/system_template/{template_name}/images/manage/edit.gif" idvalue="{f_NewspaperArticlePicId}" title="编辑" />
                                                <div class="btn_update_title_pic" idvalue="{f_UploadFileId}" title="{f_ShowInPicSlider}" id="update_pic_title_{f_UploadFileId}" onclick="AjaxUpdateTitlePic1({f_UploadFileId})" ></div>
                                            </div>


                                        </li>

                                        ]]>
                                    </item>
                                </icms>
                                <li style="clear:left;"></li>
                            </ul>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    {pager_button}
</div>

</body>
</html>
