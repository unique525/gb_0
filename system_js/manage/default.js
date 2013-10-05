/*框架初始化JS*/
$().ready(function() {
    var obj_maincontent = jQuery('#maincontent');
    if (obj_maincontent.length > 0) {
        $("#maincontent").css("height", $(window).height() - 40);
        $("#maincontent").splitter({
            type: "v",
            minLeft: 180,
            sizeLeft: 180,
            cookie: "vsplitter",
            accessKey: 'I'
        });
    }
    //select site
    $("#sel_site").change(function() {
        var sid_url = $("#sel_site").val();
        if (sid_url !== undefined && sid_url.length > 1) {
            var arr_site = sid_url.split("_");
            if (arr_site.length > 0) {
                var sid = arr_site[0];
                G_NowSiteId = parseInt(sid);
                G_SelectedDocumentChannelId = 0;

                if (G_NowSiteId > 0) {
                    $("#lefttree").html("<img src=\"../images/spinner.gif\">");
                    LoadChannelTree(sid);
                    //changedis(G_SelectedDocumentChannelId);
                    //sitename
                    $(".sitename").html($("#sel_site").find("option:selected").text());
                    $(".sitename").attr("title", sid);
                } else {
                    $("#lefttree").html("请先增加一个站点");
                }
            }
            if (arr_site.length > 1) {
                var siteurl = arr_site[1];
                $("#gosite").attr("href", siteurl);
            }
        }
    });

    //sitename
    //$(".sitename").html($("#sel_site").find("option:selected").text());
    //$(".sitename").attr("title",$("#sel_site").val());



    var obj_accord1 = jQuery('#accord1');
    if (obj_accord1.length > 0) {
        $("#accord1").accordion({
            header: 'h3'
        });
    }
    $(".divAccordItem").click(function() {
        $(".sitename").html($(this).html());
        //channelname
        $(".channelname").html("");
    });


    var obj_sel_site = jQuery('#sel_site');
    if (obj_sel_site.length > 0) {
        $("#sel_site").change();
        //changedis(nowselectchannelid);
        //load css
        loadcss('..', 'font14.css');
        //load usermanage
        var siteId = G_NowSiteId;
        /*
        $.ajax({
            url: "/default.php",
            data: {
                secu: "manage",
                mod: "adminleftusermanage",
                m: "async_list",
                siteid: siteId
            },
            dataType: "jsonp",
            jsonp: "jsonpcallback",
            success: function(data) {
                if (data !== undefined) {
                    var aa = "";
                    $.each(data, function(i, v) {
                        aa = aa + '<div class="line" id="btn' + v["AdminPopedomName"] + '">' + v["AdminLeftUserManageName"] + '</div>';
                    });
                    $("#div_usermanage").html(aa);
                }
            }
        });*/
    }
});

function setleftnav_height(leftnavcount) {
    $("#lefttree").css("height", $(window).height() - 95 - 29 * (leftnavcount - 1));
}