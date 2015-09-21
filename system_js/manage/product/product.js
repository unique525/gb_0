
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
        if (pageIndex == undefined || isNaN(pageIndex) || pageIndex <= 0) {
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
        if (pageIndex == undefined || isNaN(pageIndex) || pageIndex <= 0) {
            pageIndex = 1;
        }
        parent.G_TabUrl = '/default.php?secu=manage&mod=product&m=modify&product_id=' + productId + '&p=' + pageIndex + '&channel_id=' + parent.G_SelectedChannelId;
        parent.G_TabTitle = parent.G_SelectedChannelName + '-编辑产品';
        parent.addTab();
    });

    //格式化状态
    $(".span_state").each(function(){
        $(this).html(FormatProductState($(this).html()));
    });



    //改变向上移动（排序）
    $(".btn_up").click(function(event) {
        var productId = $(this).attr('idvalue');
        event.preventDefault();
        $.post("/default.php?secu=manage&mod=product&m=async_modify_sort&product_id="+productId + "&sort=1", {
            resultbox: $(this).html()
        }, function(xml) {
            window.location.href = window.location.href;
        });
    });

    //改变向下移动（排序）
    $(".btn_down").click(function(event) {
        var productId = $(this).attr('idvalue');
        event.preventDefault();
        $.post("/default.php?secu=manage&mod=product&m=async_modify_sort&product_id=" + productId + "&sort=-1", {
            resultbox: $(this).html()
        }, function(xml) {
            window.location.href = window.location.href;
        });
    });

    //拖动排序变化
    var sortGrid = $("#sort_grid");
    sortGrid.sortable();
    sortGrid.bind("sortstop", function(event, ui) {
        var sortList = $("#sort_grid").sortable("serialize");
        $.post("/default.php?secu=manage&mod=product&m=async_modify_sort_by_drag&" + sortList, {
            resultbox: $(this).html()
        }, function() {
            //操作完成后触发的命令
        });
    });
    sortGrid.disableSelection();






    /****************** 顶部排序按钮 ***************************/

    var gridCanSort = $(".grid_can_sort");
    gridCanSort.css("cursor", "pointer");
    gridCanSort.mouseover(function(){
        $(this).attr("class","grid_can_sort_selected");
    });
    gridCanSort.mouseleave(function(){
        $(this).attr("class","grid_can_sort");
    });

    var btnSortDown = $("#btn_sort_down");
    var btnSortUp = $("#btn_sort_up");
    if(Request["sort"] == "up"){
        btnSortDown.css("display","none");
        btnSortUp.css("display","");
    }else{
        btnSortDown.css("display","");
        btnSortUp.css("display","none");
    }

    $("#btn_sort").click(function(){
        var url = window.location.href;
        url = url.replaceAll("&sort=up","");
        url = url.replaceAll("&sort=down","");
        url = url.replaceAll("&sale_count=up","");
        url = url.replaceAll("&sale_count=down","");

        var btnSortDown = $("#btn_sort_down");
        var btnSortUp = $("#btn_sort_up");
        if(btnSortDown.css("display") != "none"){ //当前是降序,改成升序
            btnSortDown.css("display","none");
            btnSortUp.css("display","");
            window.location.href = url + "&sort=up";
        }else{
            btnSortDown.css("display","");
            btnSortUp.css("display","none");
            window.location.href = url + "&sort=down";
        }
    });


    var btnSortBySaleCountDown = $("#btn_sort_by_sale_count_down");
    var btnSortBySaleCountUp = $("#btn_sort_by_sale_count_up");
    if(Request["sale_count"] == "up"){
        btnSortBySaleCountDown.css("display","none");
        btnSortBySaleCountUp.css("display","");
    }else{
        btnSortBySaleCountDown.css("display","");
        btnSortBySaleCountUp.css("display","none");
    }

    $("#btn_sort_by_sale_count").click(function(){

        var url = window.location.href;
        url = url.replaceAll("&sort=up","");
        url = url.replaceAll("&sort=down","");
        url = url.replaceAll("&sale_count=up","");
        url = url.replaceAll("&sale_count=down","");

        var btnSortBySaleCountDown = $("#btn_sort_by_sale_count_down");
        var btnSortBySaleCountUp = $("#btn_sort_by_sale_count_up");
        if(btnSortBySaleCountDown.css("display") != "none"){ //当前是降序,改成升序
            btnSortBySaleCountDown.css("display","none");
            btnSortBySaleCountUp.css("display","");
            window.location.href = url + "&sale_count=up";
        }else{
            btnSortBySaleCountDown.css("display","");
            btnSortBySaleCountUp.css("display","none");
            window.location.href = url + "&sale_count=down";
        }
    });
    /*********************************************/


    //打开产品组图列表
    var btnManagePic = $(".btn_manage_pic");
    btnManagePic.css("cursor", "pointer");
    btnManagePic.click(function(event) {
        event.preventDefault();
        var productId=$(this).attr('idvalue');
        var productName=$(this).attr('alt');
        var channelId=parent.G_SelectedChannelId;
        parent.G_TabUrl = '/default.php?secu=manage&mod=product_pic&m=list&channel_id='+channelId+'&product_id=' + productId + '&ps=8';
        parent.G_TabTitle = productName + '-图片管理';
        parent.addTab();
    });

    /**
     * 移动产品结点
     * **/
    $("#btn_move").click(function (event) {
        event.preventDefault();
        var channelId=$(this).attr("idvalue");
        var docIdString = "";
        var w = 500;
        var h = 520;

        $('input[name=input_select]').each(function (i) {
            if (this.checked) {
                docIdString = docIdString + ',' + $(this).val();
            }
        });

        docIdString=docIdString.substr(1);
        if (docIdString.length <= 0) {
            alert("请先选择要操作的产品");
        } else {

            var url='/default.php?secu=manage&mod=product&m=move&channel_id='+channelId+'&doc_id_string='+docIdString;
            $("#dialog_frame").attr("src",url);
            $("#dialog_resultbox").dialog({
                hide:true,    //点击关闭是隐藏,如果不加这项,关闭弹窗后再点就会出错.
                autoOpen:true,
                height:h,
                width:w,
                modal:true, //蒙层（弹出会影响页面大小）
                title:'移动',
                overlay: {opacity: 0.5, background: "black" ,overflow:'auto'}
            });
        }
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
