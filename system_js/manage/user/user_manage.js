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
                var aa = "";
                $.each(data, function (i, v) {
                    aa = aa + '<div class="line" id="btn' + v["ManageMenuOfUserTagName"] + '">' + v["ManageMenuOfUserName"] + '</div>';
                });
                $("#div_user_manage").html(aa);

                //会员管理
                $("#btnUserExplore").click(function (event) {

                });
                //会员组管理
                $("#btnUserGroupExplore").click(function (event) {

                });
                //会员等级管理
                $("#btnUserLevelExplore").click(function (event) {

                });
                //会员相册管理
                $("#btnUserAlbumExplore").click(function (event) {
                    window.G_TabUrl = '/default.php?secu=manage&mod=user_album&m=list&site_id=' + window.G_NowSiteId;
                    addTab();
                });
                //会员订单管理
                $("#btnUserOrderExplore").click(function (event) {

                });
                //会员站点角色管理
                $("#btnUserRoleExplore").click(function (event) {

                });

            }
        }
    });

});

