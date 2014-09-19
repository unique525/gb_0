<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}
    <script type="text/javascript">
        $(function(){
            $(".div_stop").click(function(){
                var user_favorite_id = $(this).attr("idvalue");
                var site_id = parent.G_NowSiteId;
                $.ajax({
                    url:"/default.php?secu=manage&mod=user_favorite&m=async_remove_bin",
                    data:{user_favorite_id:user_favorite_id,site_id:site_id},
                    dataType:"jsonp",
                    jsonp:"jsonpcallback",
                    success:function(data){
                        var result = data["result"];
                        if(result > 0){
                            location.replace(location);
                        }else{
                            alert("删除失败，请联系管理员");
                        }
                    }
                });
            });

            $("#btn_search").click(function(){
                var searchKey = $("#search_key").val();
                location.replace("/default.php?secu=manage&mod=user_favorite&m=list&search_key="
                    +searchKey+"&site_id="+parent.G_NowSiteId+"&ps="+parent.G_PageSize);
            });
        });
    </script>
</head>
<body>
<div class="div_list">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td id="td_main_btn">

            </td>
            <td style="text-align: right; margin-right: 8px;">
                <div id="search_box">
                    <label for="search_key"></label><input type="text" id="search_key" name="search_key" class="input_box"/>
                    <input id="btn_search" class="btn2" value="查 询" type="button"/>
                    <span id="search_type" style="display: none"></span>
                </div>
            </td>
        </tr>
    </table>

    <table class="grid" width="100%" cellpadding="0" cellspacing="0">
        <tr  class="grid_title2">
            <td style="width:40px;text-align: center">ID</td>
            <td style="text-align: left">收藏物品</td>
            <td style="width:260px;text-align: center">收藏人</td>
            <td  style="width:80px;text-align: center">删除</td>
        </tr>
    </table>
    <ul id="type_list">
        <icms id="user_favorite" type="list">
            <item>
                <![CDATA[
                <li>
                    <table width="100%" cellpadding="0" cellspacing="0">
                        <tr class="grid_item2">
                            <td class="spe_line2" style="width:40px;text-align: center">{f_UserFavoriteId}</td>
                            <td class="spe_line2" style="text-align: left">
                                {f_UserFavoriteTitle}
                            </td>
                            <td class="spe_line2" style="width:260px;text-align: center">
                                {f_UserName}
                            </td>
                            <td class="spe_line2" style="width:80px;text-align: center">
                                <img class="div_stop" idvalue="{f_UserFavoriteId}" src="/system_template/{template_name}/images/manage/stop.jpg" style="cursor:pointer"/>
                            </td>
                        </tr>
                    </table>
                </li>
                ]]>
            </item>
        </icms>
    </ul>
    {pagerButton}
</div>
</body>
</html>