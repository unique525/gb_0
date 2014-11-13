


function getDocumentNewsList(channelId,pageIndex,pageSize,searchKey,pagerStyle){

    $.ajax({
        url: "/default.php?mod=document_news&a=async_get_list",
        data: {
            channel_id: channelId,
            p: pageIndex,
            ps: pageSize,
            search_key:searchKey,
            ptt:pagerStyle
        },
        dataType: "jsonp",
        jsonp: "jsonpcallback",
        success: function (data) {
            alert(data);
            alert(data["result_list"]);
            //var object = eval(data);
            //alert(object[0].DocumentNewsId);
            //window.getDocumentNewsListCallBack(data);
        }
    });




}
