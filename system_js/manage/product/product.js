
/**
 * 处理站点列表页的js
 */
$(function () {

    $("#btn_select_all").click(function(event) {
        event.preventDefault();
        var inputSelect = $("[name='input_select']");
        if (inputSelect.prop("checked")) {
            inputSelect.prop("checked", false);//取消全选
        } else {
            inputSelect.prop("checked", true);//全选
        }
    });

    var btnCreate = $("#btn_create");
    btnCreate.css("cursor", "pointer");
    btnCreate.click(function(event) {
        event.preventDefault();
        var pageIndex = parseInt(Request["p"]);
        if (pageIndex <= 0) {
            pageIndex = 1;
        }
        parent.G_TabUrl = '/default.php?secu=manage&mod=product&m=create&p=' + pageIndex + '&channel_id=' + parent.G_SelectedChannelId;
        parent.G_TabTitle = parent.G_SelectedChannelName + '-新增产品';
        parent.addTab();
    });

    var btnModify = $(".btn_modify");
    btnModify.css("cursor", "pointer");
    btnModify.click(function(event) {
        var productId = $(this).attr('idvalue');
        event.preventDefault();
        var pageIndex = parseInt(Request["p"]);
        if (pageIndex <= 0) {
            pageIndex = 1;
        }
        parent.G_TabUrl = '/default.php?secu=manage&mod=product&m=modify&product_id=' + productId + '&p=' + pageIndex + '&channel_id=' + parent.G_SelectedChannelId;
        parent.G_TabTitle = parent.G_SelectedChannelName + '-编辑产品';
        parent.addTab();
    });

    //格式化站点状态
    $(".span_state").each(function(){
        $(this).text(FormatProductState($(this).text()));
    });

    //开启站点
    $(".img_open_site").click(function(){
        var siteId = parseInt($(this).attr("idvalue"));
        var state = 0; //开启状态
        if(siteId>0){
            $.ajax({
                type: "get",
                url: "default.php?secu=manage&mod=site&m=async_modify_state",
                data: {
                    site_id: siteId,
                    state:state
                },
                dataType: "jsonp",
                jsonp: "jsonpcallback",
                success: function(data) {
                    alert(data);
                }
            });
        }
    });


    //打开产品组图列表
    var btnManagePic = $(".btn_manage_pic");
    btnManagePic.css("cursor", "pointer");
    btnManagePic.click(function(event) {
        event.preventDefault();
        var productId=$(this).attr('idvalue');
        var productName=$(this).attr('alt');
        parent.G_TabUrl = '/default.php?secu=manage&mod=product_pic&m=list&product_id=' + productId;
        parent.G_TabTitle = productName + '-图片管理';
        parent.addTab();
    });

});

/**
 * 格式化产品状态值
 * @return {string}
 */
function FormatProductState(state){
    switch (state){
        case "0":
            return "正常";
            break;
        case "100":
            return "<"+"span style='color:#990000'>停用<"+"/span>";
            break;
        default :
            return "未知";
            break;
    }
}
