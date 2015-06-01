function FormatUserState(state){
    var result;
    switch(state){
        case "0":
            result = '开启';
            break;
        case "10":
            result = '未激活';
            break;
        case "100":
            result = '停用';
            break;
    }
    return result;
}

$(document).ready(function() {
    //load user manage
    GetManageMenuOfUser();


    //按用户名进行查找
    $("#SearchSub").click(function () {
        var userNameStr = $("#UserName").val();
        if (userNameStr.length < 1) {
            alert("用户名长度需大于或等于2！");
        } else {
            userNameStr = encodeURIComponent(userNameStr);
            $.ajax({
                url: "default.php",
                data: {secu: "manage", mod: "user", m: "searchForJson", site_id: window.G_NowSiteId, user_name: userNameStr},
                dataType: "jsonp",
                jsonp: "jsonpcallback",
                success: function (data) {
                    var listContent = '';
                    $.each(data, function (i, v) {
                        if (parseInt(v["UserId"]) > 0) {
                            listContent = listContent + '<span class="span_css" onclick=changeParent("' + v["UserId"] + '","' + v["UserName"] + '")>' + v["UserName"] + ' </span><br>';
                        }
                    });
                    $("#ParentIdList").html(listContent);
                }
            });
        }
    });

    $(".span_state").each(function(){
        var state = $(this).attr("idvalue");
        $(this).html(FormatOrderState(state));
    });

    //确认并关闭
    $("#btn_ConfirmClose").click(function() {
        var confirmId = $(this).attr('idvalue');
        submitForm(confirmId);
    });

    //确认并继续
    $("#btn_ConfirmGoOn").click(function() {
        var confirmId = $(this).attr('idvalue');
        submitForm(confirmId);
    });

    //取消
    $("#btn_Remove").click(function() {
        closeTab();
    });

    //确认并关闭
    $("#btn_ConfirmCloseTwo").click(function() {
        var confirmId = $(this).attr('idvalue');
        submitForm(confirmId);
    });

    //确认并继续
    $("#btn_ConfirmGoOnTwo").click(function() {
        var confirmId = $(this).attr('idvalue');
        submitForm(confirmId);
    });

    //取消
    $("#btn_RemoveTwo").click(function() {
        closeTab();
    });

});


//显示用户名查找内容
//function editParent() {
//    $("#searchParent").css("display", "inline");
//}

//更改用户名推荐人
//function changeParent(userId, userName) {
//    //alert(userId+"@@"+userName);
//    $('#ParentName').val(userName);
//    $('#f_ParentId').val(userId);
//    $('#ParentName').attr("disabled");
//    $('#ParentName').removeAttr("disabled");
//
//}


/***
 * 获取站点下会员管理项目
 * @constructor
 */
function GetManageMenuOfUser(){
    $.ajax({
        url: "/default.php",
        data: {
            secu: "manage",
            mod: "manage_menu_of_user",
            m: "async_list",
            site_id: window.G_NowSiteId
        },
        dataType: "jsonp",
        jsonp: "jsonpcallback",
        success: function (data) {
            if (data !== undefined) {
                var aw = $(window).height() - 108 - 35;
                var size = aw / 28;
                window.G_PageSize = parseInt(size) - 1;

                var aa = "";
                $.each(data, function (i, v) {
                    aa = aa + '<div class="line" id="btn' + v["ManageMenuOfUserTagName"] + '">' + v["ManageMenuOfUserName"] + '</div>';
                });
                $("#div_user_manage").html(aa);

                //会员管理
                $("#btnUserExplore").click(function (event) {
                    event.preventDefault();
                    window.G_TabTitle = '会员管理';
                    window.G_TabUrl = '/default.php?secu=manage&mod=user&m=list&site_id=' + window.G_NowSiteId;
                    addTab();
                });
                //会员组管理
                $("#btnUserGroupExplore").click(function (event) {
                    event.preventDefault();
                    window.G_TabTitle = '会员组管理';
                    window.G_TabUrl = '/default.php?secu=manage&mod=user_group&m=list&site_id=' + window.G_NowSiteId;
                    addTab();
                });
                //会员等级管理
                $("#btnUserLevelExplore").click(function (event) {
                    event.preventDefault();
                    window.G_TabTitle = '会员等级管理';
                    window.G_TabUrl = '/default.php?secu=manage&mod=user_level&m=list&site_id=' + window.G_NowSiteId;
                    addTab();
                });
                //会员相册管理
                $("#btnUserAlbumExplore").click(function (event) {
                    event.preventDefault();
                    window.G_TabTitle = '会员相册管理';
                    window.G_TabUrl = '/default.php?secu=manage&mod=user_album&m=list&site_id=' + window.G_NowSiteId;
                    addTab();
                });
                //会员相册分类管理
                $("#btnUserAlbumTypeExplore").click(function (event) {
                    event.preventDefault();
                    window.G_TabTitle = '会员相册分类管理';
                    window.G_TabUrl = '/default.php?secu=manage&mod=user_album_type&m=list&site_id=' + window.G_NowSiteId;
                    addTab();
                });
                //会员订单管理
                $("#btnUserOrderExplore").click(function (event) {
                    event.preventDefault();
                    window.G_TabTitle = '会员订单管理';
                    window.G_TabUrl = '/default.php?secu=manage&mod=user_order&m=list&site_id=' + window.G_NowSiteId;
                    addTab();
                });
                //会员站点角色管理
                $("#btnUserRoleExplore").click(function (event) {
                    event.preventDefault();
                    window.G_TabTitle = '会员角色管理';
                    window.G_TabUrl = '/default.php?secu=manage&mod=user_role&m=list&site_id=' + window.G_NowSiteId;
                    addTab();
                });
                //会员收藏管理
                $("#btnUserFavoriteExplore").click(function (event) {
                    event.preventDefault();
                    window.G_TabTitle = '会员收藏管理';
                    window.G_TabUrl = '/default.php?secu=manage&mod=user_favorite&m=list&site_id=' + window.G_NowSiteId;
                    addTab();
                });
                //会员站点配置
                $("#btnUserSiteConfig").click(function (event) {
                    event.preventDefault();
                    window.G_TabTitle = '会员参数设置';
                    window.G_TabUrl = '/default.php?secu=manage&mod=site_config&m=set&type=2&site_id=' + window.G_NowSiteId + "&tab_index=" + parent.G_TabIndex + "";
                    addTab();
                });
            }
        }
    });
}