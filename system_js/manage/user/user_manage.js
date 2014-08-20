$(document).ready(function() {
    //load user manage
    $.ajax({
        url: "/default.php",
        data: {
            secu: "manage",
            mod: "manage_menu_of_user",
            m: "async_list",
            siteid: window.G_NowSiteId
        },
        dataType: "jsonp",
        jsonp: "jsonpcallback",
        success: function (data) {
            alert(data);
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
                    window.G_TabTitle = '会员管理';
                    window.G_TabUrl = '/default.php?secu=manage&mod=user_group&m=list&site_id=' + window.G_NowSiteId;
                    addTab();
                });
                //会员等级管理
                $("#btnUserLevelExplore").click(function (event) {
                    event.preventDefault();
                    window.G_TabTitle = '会员管理';
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
                    window.G_TabTitle = '会员管理';
                    window.G_TabUrl = '/default.php?secu=manage&mod=user_role&m=list&site_id=' + window.G_NowSiteId;
                    addTab();
                });

            }
        }
    });


    /***会员管理页面js***/
    $(".img_avatar").each(function(){
        var avatar = $(this).attr("src");
        if(avatar == undefined || avatar.length<=0){
            $(this).attr("src", "/front_template/default/skins/gray/no_avatar_small.gif");
        }
    });

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

    //取消
    $("#btn_RemoveTwo").click(function() {
        closeTab();
    });

});


//显示用户名查找内容
function editParent() {
    $("#searchParent").css("display", "inline");
}

//更改用户名推荐人
function changeParent(userId, userName) {
    //alert(userid+"@@"+username);
    $('#ParentName').val(userName);
    $('#f_ParentId').val(userId);
    $('#ParentName').attr("disabled");
    $('#ParentName').removeAttr("disabled");

}
function submitForm(continueCreate) {
    if ($('#f_UserName').val() == '') {
        $("#dialog_box").dialog({width: 300, height: 100});
        $("#dialog_content").html("请输入会员账号");
    } else if ($('#f_UserPass').val().length < 6) {
        $("#dialog_box").dialog({width: 300, height: 100});
        $("#dialog_content").html("会员账号密码不能少于6位");
    } else {
        if (continueCreate == 1) {
            $("#CloseTab").val("0");
        } else {
            $("#CloseTab").val("1");
        }
        $('#mainForm').submit();
    }
}

