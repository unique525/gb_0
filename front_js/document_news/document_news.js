/**
 * 取得资讯列表通用方法
 * @param {int} channelId
 * @param {int} pageIndex
 * @param {int} pageSize
 * @param {string} searchKey
 * @param {int} pagerStyle
 */
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
            var object = eval(data);
            var documentNewsCollection = eval(object.result_list);

            //var pagerButton = decodeURIComponent(object.pager_button);
            var pagerButton = object.pager_button;

            if(documentNewsCollection != undefined && pagerButton != undefined){
                window.getDocumentNewsListCallBack(documentNewsCollection,pagerButton);
            }
        }
    });




}
