/**框架初始化JS**/
$(function () {

    var divMainContent = jQuery('#div_main_content');
    if (divMainContent.length > 0) {
        divMainContent.css("height", $(window).height() - 40);
        divMainContent.splitter({
            type: "v",
            minLeft: 180,
            sizeLeft: 180,
            cookie: "vsplitter",
            accessKey: 'I'
        });
    }

    var objAccord = $('#div_left_accordion');
    if (objAccord.length > 0) {
        objAccord.accordion({
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
            window.location.href = window.location.href;
            if (parseInt(xml) > 0) {

            } else if (parseInt(xml) === -2) {
                //alert("设置失败");
            }
            else {
                //alert("设置失败");
            }
        });
    });

    $("#btn_close_all_tab").click(function () {
        closeAllTab();
        //channel name
        $(".channel_name").html("");
    });

    //修改密码
    $("#btn_modify_manage_user_pass").click(function () {
        var url='/default.php?secu=manage&mod=manage_user&m=modify_password';
        $("#dialog_modify_password_frame").attr("src",url);
        $("#dialog_modify_password_box").dialog({
            hide:true,    //点击关闭时隐藏,如果不加这项,关闭弹窗后再点就会出错.
            autoOpen:true,
            height:300,
            width:480,
            modal:true, //蒙层（弹出会影响页面大小）
            title:'管理员密码修改',
            overlay: {opacity: 0.5, background: "black" ,overflow:'auto'}
        });

    });

    var aw = $(window).height() - 108 - 35;
    var size = aw / 28;
    window.G_PageSize = parseInt(size) - 1;
});

