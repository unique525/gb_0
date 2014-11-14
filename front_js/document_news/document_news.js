
var documentNewsObject = new Object();
DocumentNewsId
SiteId
ChannelId
DocumentNewsTitle
DocumentNewsSubTitle
DocumentNewsCiteTitle
DocumentNewsShortTitle
DocumentNewsIntro
CreateDate
ManageUserId
ManageUserName
UserId
UserName
Author
State
DocumentNewsType
DirectUrl
ShowDate
SourceName
DocumentNewsMainTag
DocumentNewsTag
Sort
TitlePic1UploadFileId
TitlePic2UploadFileId
TitlePic3UploadFileId
DocumentNewsTitleColor
DocumentNewsTitleBold
OpenComment
ShowHour
ShowMinute
ShowSecond
IsHot
RecLevel
ShowPicMethod
ClosePosition
Hit
PublishDate
TitlePic1Path


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
            //alert(data);
            //alert(data["result_list"]);
            //var object = eval(data);
            var documentNewsCollection = eval(object.result_list);
            //alert(documentNewsObject[1].DocumentNewsId);
            //alert(object.pager_button);

            //window.getDocumentNewsListCallBack(data);
        }
    });




}
