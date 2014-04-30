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
                    window.G_TabTitle = '会员管理';
                    window.G_TabUrl = '/default.php?secu=manage&mod=user&m=list&site_id=' + window.G_NowSiteId;
                    addTab();
                });
                //会员组管理
                $("#btnUserGroupExplore").click(function (event) {
                    window.G_TabTitle = '会员管理';
                    window.G_TabUrl = '/default.php?secu=manage&mod=user_group&m=list&site_id=' + window.G_NowSiteId;
                    addTab();
                });
                //会员等级管理
                $("#btnUserLevelExplore").click(function (event) {
                    window.G_TabTitle = '会员管理';
                    window.G_TabUrl = '/default.php?secu=manage&mod=user_level&m=list&site_id=' + window.G_NowSiteId;
                    addTab();
                });
                //会员相册管理
                $("#btnUserAlbumExplore").click(function (event) {
                    window.G_TabTitle = '会员相册管理';
                    window.G_TabUrl = '/default.php?secu=manage&mod=user_album&m=list_for_manage&site_id=' + window.G_NowSiteId;
                    addTab();
                });
                //会员订单管理
                $("#btnUserOrderExplore").click(function (event) {
                    window.G_TabTitle = '会员管理';
                    window.G_TabUrl = '/default.php?secu=manage&mod=user_order&m=list&site_id=' + window.G_NowSiteId;
                    addTab();
                });
                //会员站点角色管理
                $("#btnUserRoleExplore").click(function (event) {
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

});

