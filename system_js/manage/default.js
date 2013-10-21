/*框架初始化JS*/

$(function() {
    $("#sitemanage").attr("src", "/system_template/" + G_TemplateName + "/images/manage/lsite.jpg");
    $("#btngosite").attr("src", "/system_template/" + G_TemplateName + "/images/manage/go.jpg");

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
    //$("#accord1").tooltip();
    //$(".btnsettemplate").tooltip();
    
    $("#divselectsite").click(function(){
        if($(this).attr("class") === "divselectsite_normal"){
            $(this).attr("class","divselectsite_clicked");
            var itemHeight = parseInt($(this).css("height"));
            var siteCount = $(".sitecount").attr("idvalue");
            //alert(siteCount);
            var newHeight = itemHeight * siteCount;
            $(this).css("height",newHeight+"px");
        }else{
            $(this).attr("class","divselectsite_normal");
            //$(this).css("height","20px");
        }
    });
    
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
                    $("#lefttree").html("<img src='/system_template/" + G_TemplateName + "/images/manage/spinner.gif' />");
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

    if($("#showsitelist").length > 0){
        $("#showsitelist").append($("#divselectsite"));
    }

    var objAccord1 = jQuery('#accord1');
    if (objAccord1.length > 0) {
        $("#accord1").accordion({
            header: 'h3',
            heightStyle: "content"
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

    $(".btnsettemplate").click(function() {
        var templateName = $(this).attr("idvalue");
        $.post("/default.php?secu=manage&mod=settemplate&tn="+templateName, {
            resultbox: $(this).html()
        }, function(xml) {
            window.location.href = window.location.href;
            if (parseInt(xml) > 0) {

            } else if (parseInt(xml) == -2) {
                //alert("设置失败");
            }
            else {
                //alert("设置失败");
            }
        });
    });
});
    