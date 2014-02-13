/**框架初始化JS**/
$(function () {
    var nowTemplateName = "default";
    if (G_TemplateName != undefined) {
        nowTemplateName = G_TemplateName;
    }
    $("#btn_site_manage").attr("src", "/system_template/" + nowTemplateName + "/images/manage/site_manage.jpg");
    $("#img_go_site_url").attr("src", "/system_template/" + nowTemplateName + "/images/manage/go.jpg");

    var divMainContent = jQuery('#div_main_content');
    if (divMainContent.length > 0) {
        $("#div_main_content").css("height", $(window).height() - 40);
        $("#div_main_content").splitter({
            type: "v",
            minLeft: 180,
            sizeLeft: 180,
            cookie: "vsplitter",
            accessKey: 'I'
        });
    }
    //$("#accord1").tooltip();
    //$(".btnsettemplate").tooltip();
    var divSelectSite = $("#div_select_site");
    divSelectSite.click(function () {
        if ($(this).attr("class") === "select_site_normal") {
            $(this).attr("class", "select_site_clicked");
            var itemHeight = parseInt($(this).css("height"));
            var siteCount = $(".site_count").attr("idvalue");
            var newHeight = itemHeight * siteCount;
            $(this).css("height", newHeight + "px");
        } else {
            $(this).attr("class", "select_site_normal");
        }
    });
    var divDefaultSite = $("#div_default_site");
    var siteId = divDefaultSite.attr("idvalue");
    var siteName = divDefaultSite.html();
    var siteUrl = divDefaultSite.attr("title");
    if (siteId.length > 0) {
        LoadSite(siteId, siteName, siteUrl);
    }
    //select site
    $(".select_site_item").click(function () {
        siteId = $(this).attr("idvalue");
        siteName = $(this).html();
        siteUrl = $(this).attr("title");
        LoadSite(siteId, siteName, siteUrl);
    });

    //site name
    //$(".site name").html($("#sel_site").find("option:selected").text());
    //$(".site name").attr("title",$("#sel_site").val());

    if ($("#div_show_site_list").length > 0) {
        $("#div_show_site_list").append($("#div_select_site"));
    }

    var objAccord = jQuery('#div_left_accordion');
    if (objAccord.length > 0) {
        $("#div_left_accordion").accordion({
            header: 'h3',
            heightStyle: "content"
        });
    }
    $(".div_accordion_item").click(function () {
        $(".site_name").html($(this).html());
        //channel name
        $(".channel_name").html("");
    });

    $(".btn_set_template").click(function () {
        var templateName = $(this).attr("idvalue");
        $.post("/default.php?secu=manage&mod=set_template&tn=" + templateName, {
            resultbox: $(this).html()
        }, function (xml) {
            var nowUrl = window.location.href;
            window.location.href = nowUrl;
            if (parseInt(xml) > 0) {

            } else if (parseInt(xml) === -2) {
                //alert("设置失败");
            }
            else {
                //alert("设置失败");
            }
        });
    });
});

function LoadSite(siteId, siteName, siteUrl) {

    G_NowSiteId = parseInt(siteId);
    G_SelectedChannelId = 0;

    if (G_NowSiteId > 0) {
        $("#div_manage_menu_of_column").html("<img style='margin:10px;' src='/system_template/common/images/spinner2.gif' />");
        LoadChannelListForManage(siteId);
        //site name
        $(".site_name").html(siteName);
        if (siteUrl.length > 1) {
            $("#btn_go_site_url").attr("href", siteUrl);
        }
    } else {
        $("#div_manage_menu_of_column").html("请先增加一个站点");
    }


    $.ajax({
        url: "/default.php",
        data: {
            secu: "manage",
            mod: "manage_menu_of_user",
            m: "async_list",
            siteid: siteId
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
            }
        }
    });

}  