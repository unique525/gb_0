/**
 * 评论表的TableType
 */
window.COMMENT_TABLE_TYPE_USER_ALBUM = 1;
/**
 * 评论表的TableType
 */
window.COMMENT_TABLE_TYPE_DOCUMENT_NEWS = 7;
/**
 * 评论表的TableType
 */
window.COMMENT_TABLE_TYPE_NEWSPAPER_ARTICLE = 8;

/**
 * 评论的开放状态 不允许评论
 */
window.COMMENT_OPEN_COMMENT_NON_COMMENT=0;


String.prototype.replaceAll = function (s1, s2) {
    return this.replace(new RegExp(s1, "gm"), s2);
};

var comment_count = 0;
function sub_comment() {
    if ($(".comment_content").val() == "") {
        alert("评论请不要空着。");
    } else {
        $('#mainForm').submit();
    }
}

function sub_comment_replay(id) {
    if ($("#reply_" + id + " .comment_content").val() == "") {
        alert("评论请不要空着。");
    } else {
        $('#replyform_' + id).submit();
    }
}

function AsyncGetOpenComment(table_id, table_type) {
    var openComment = 0;
    $.ajax({
        async:false,
        url:"/default.php?mod=comment&a=async_get_open_comment",
        data:{table_id:table_id,table_type:table_type},
        dataType:"jsonp",
        jsonp:"jsonpcallback",
        success:function(data){
            openComment = data["result"];
        }
    });
    return openComment;
}

function getURL() {
    var url = encodeURIComponent(location.href);
    return url;
}
function CreateLongComment(table_id, table_type, channel_id, is_callback) {
    $.ajax({
        url: "/default.php?mod=user&a=async_get_one",
        async: false,
        data: {},
        dataType: "jsonp",
        jsonp: "jsonpcallback",
        success: function (data) {
            if (!is_callback) {
                var tableId = parseInt(table_id);
                var tableType = parseInt(table_type);
                var channelId = parseInt(channel_id);
                var re_url = window.location.href;
                var username = "";
                var result = data["result"];
                var name = '<span class="guest" style="text-align:left">您还未<a href="/default.php?mod=user&a=login&re_url="' + re_url + ' style="font-weight:bold">登录</a>,目前的身份是游客</span>';
                if (result != "") {
                    if (result["NickName"] != "") {
                        username = result["NickName"];
                    } else {
                        username = result["UserName"];
                    }

                    if (username != undefined && username != "" && username != null) {
                        name = '<span class="username" style="text-align:left">' + username + '</span>';
                    }
                }
                if (tableId > 0) {
                    $('#comment').append('<form id="mainForm" action="/default.php?mod=comment&a=create" data-ajax="false" method="post">'
                        + '<table width="95%" class="">'
                        + '<tr>'
                        + '<td height="35px" width="15%" align="center"><span>用&nbsp;户&nbsp;名:</span></td>'
                        + '<td  align="left"><span style="float:right">已经有<span id="count">0</span>人评论</span>'
                        + name + '</td>'
                        + '</tr>'
                        + '<tr>'
                        + '<td valign="top" align="center"><span>评论内容:</span></td>'
                        + '<td><textarea name="content" style="width:99%" rows="5" class="comment_content"></textarea>'
                        + '<input type="hidden" value="' + tableId + '" name="table_id"/>'
                        + '<input type="hidden" value="' + tableType + '" name="table_type"/>'
                        + '<input type="hidden" name="channel_id" value="' + channelId + '"/>'
                        + '<input type="hidden" id="url" name="url" value="' + getURL() + '"/></td>'
                        + '</tr>'
                        + '<tr><td colspan="2" align="right"><input onclick="sub_comment()" class="publish" type="button" value="发表评论"/></td></tr>'
                        + '</table></form>');
                }
            } else {
                window.CreateLongCommentCallback(data,table_id,table_type,channel_id);
            }
        }
    });
}

function createshortComment(domain, tid, ttype, siteid) {
    if (tid > 0) {
        $('.comment').append('<form id="mainForm" action="' + domain + '/index.php?a=comment&f=add" method="post">'
            + '<table width="99%" align="center" border="0" cellspacing="0" cellpadding="0"><tr><td>'
            + '<input class="commentcontentshort" maxlength="8" type="text" name="f_documentnewscommentcontent" value=""/>'
            + '<input onclick="sub()" class="publish" type="button" value="发表"/></td></tr></table>'
            + '<input type="hidden" value="' + tid + '" name="f_tableid"/>'
            + '<input type="hidden" value="' + ttype + '" name="f_tabletype"/>'
            + '<input type="hidden" name="f_createdate" value="' + getDate() + '"/>'
            + '<input type="hidden" id="url" name="url" value="' + getURL() + '"/></td>'
            + '</form>'
        );
    }
}

function CommentShow(p, table_id, table_type, default_avatar, is_callback) {
    //修正补丁，用来分页按钮初始化
    if (p == 0) {
        p = 1;
    }
    $.ajax({
        async: false,
        url: "/default.php?mod=comment&a=list",
        data: {
            p: p,
            ps: "10",
            table_id: table_id,
            table_type: table_type
        },
        dataType: "jsonp",
        jsonp: "jsonpcallback",
        success: function (data) {
            if (!is_callback) {
                var listContent = '<style>.commentcontent div{border:1px solid #D1D5DB;background:#FFFEF5;padding:5px;} .commentcontent span{color:black;display:block}</style>';
                if (data != null) {
                    var pagerButton = data["page_button"];
                    comment_count = data["count"];
                    var result = data["result"];
                    $.each(result, function (i, v) {
                        var username = "";
                        var avatar = default_avatar;
                        if (v["Avatar"] != null && v["Avatar"].length > 1) {
                            avatar = v["Avatar"];
                        }

                        var showUserUrl = '<img src="' + avatar + '" style="width:50px;height:50px;" />';

                        if (v["NickName"] != "" && v["NickName"] != null) {
                            username = v["NickName"];
                        } else {
                            if (v["UserName"] == "" && v["UserName"] != null) {
                                username = "游客";
                                showUserUrl = '<img src="' + default_avatar + '" style="width:50px;height:50px;" />';
                            } else {
                                username = v["UserName"];
                            }
                        }
                        listContent = listContent + '<div id="' + v["CommentId"] + '" style="border-bottom:2px dashed #CCC; width:98%; margin:8px 4px;">' +
                            '<table width="99%" cellpadding="0" cellspacing="0">' +
                            '<tr><td width="60" valign="top" style="padding:5px;">' + showUserUrl + '</td>' +
                            '<td style="padding:5px;">' +
                            '<div style="text-align:left;line-height:180%;">' + username + '&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#666666;font-size:10px;">' + v["CreateDate"] + '</span>&nbsp;&nbsp;&nbsp;&nbsp;<span style="cursor:pointer;display:none" onclick="comment_reply(' + v["CommentId"] + ');">[回复]</span></div>' +
                            '<div class="commentcontent" style="color:#666;text-align:left;line-height:180%;font-size:14px;"><table width="100%" style="table-layout:fixed"><tr><td style="word-wrap:break-word">' + v["Content"] + '</td></tr></table></div>' +

                            '</td></tr></table>' +

                            '<div class="reply" id="reply_' + v["CommentId"] + '" style="margin-left:70px;width:85%;display:none">' +
                            '<form id="replyform_' + v["CommentId"] + '" action="/index.php?a=comment&f=add&citeid=' + v["CommentId"] + '" method="post">' +
                            '<table width="95%">' +
                            '<tr>' +
                            '<td valign="top" align="center" width="15%"><span id="reply_label_user_name">留言人:</span></td>' +
                            '<td width="85%"><input type="text" name="f_GuestName" />' +
                            '</tr>' +
                            '<tr>' +
                            '<td valign="top" align="center" width="15%"><span id="reply_label__content_name">留言内容:</span></td>' +
                            '<td width="85%"><textarea name="f_content" style="width:99%" rows="5" class="commentcontent"></textarea>' +
                            '<input type="hidden" value="' + table_id + '" name="f_tableid"/>' +
                            '<input type="hidden" value="' + table_type + '" name="f_tabletype"/>' +
                            '<input type="hidden" name="f_createdate" value="' + v["CreateDate"] + '"/>' +
                            '<input type="hidden" name="f_siteid" value="' + v["siteid"] + '"/>' +
                            '<input type="hidden" name="f_documentchannelid" value="' + v["documentchannelid"] + '"/>' +
                            '<input type="hidden" id="url" name="url" value="' + getURL() + '"/></td>' +
                            '</tr>' +
                            '<tr><td colspan="2" align="right"><input onclick="subcomment_replay(' + v["commentid"] + ')" class="publish" type="button" value="回复评论"/>' +
                            '&nbsp;&nbsp;&nbsp;&nbsp;<input onclick="$(\'.reply\').css(\'display\',\'none\');" class="publish" type="button" value="取消"/></td></tr>' +
                            '</table></form>' +
                            '</div>' +
                            '</div>';
                    });


                } else {
                    listContent = listContent + '目前还没有评论，赶快来评论吧。';
                }

                listContent = listContent.replaceAll("{box}", "<div>");
                listContent = listContent.replaceAll("{/box}", "</div>");
                listContent = listContent.replaceAll("{span}", "<span>");
                listContent = listContent.replaceAll("{/span}", "</span>");

                $("#commentmessage").html(listContent);
                $("#comment_pagerbutton").html(pagerButton);
                $("#count").html(comment_count);
            } else {
                if(data["result"] != undefined && data["result"] != ""){
                    window.CommentShowCallBack(data);
                }

            }
        }
    });
}

function agreeOrdisagree(id, agree, disagree, type, domain) {
    switch (type) {
        case 1:
            $.ajax({
                url: domain + "/index.php",
                data: {
                    a: "comment",
                    f: "agreeordisagree",
                    commentid: id,
                    agree: agree + 1,
                    disagree: disagree
                },
                dataType: "jsonp",
                jsonp: "jsonpcallback",
                success: function (data) {
                    var agree = 0;
                    var disagree = 0
                    // $.each(data, function (i, v) {
                    agree = data["agree"];
                    disagree = data["disagree"];
                    $('.' + id + ' .agree').html(agree);
                    $('.' + id + ' .disagree').html(disagree);
                    //});
                }
            });
            break;
        case 0:
            $.ajax({
                url: domain + "/index.php",
                data: {
                    a: "comment",
                    f: "agreeordisagree",
                    documentcommentid: id,
                    agree: agree,
                    disagree: disagree + 1
                },
                dataType: "jsonp",
                jsonp: "jsonpcallback",
                success: function (data) {
                    var agree = 0;
                    var disagree = 0
                    // $.each(data, function (i, v) {
                    agree = data["agree"];
                    disagree = data["disagree"];
                    $('.' + id + ' .agree').html(agree);
                    $('.' + id + ' .disagree').html(disagree);
                    //});
                }
            });
            break;
    }
}
function support(tableid, tabletype, siteid, documentchannelid, domain) {//支持或者投票
    $.ajax({
        url: domain + "/index.php",
        data: {
            a: "comment",
            f: "addsupport",
            tid: tableid,
            ttype: tabletype,
            siteid: siteid,
            documentchannelid: documentchannelid
        },
        dataType: "jsonp",
        jsonp: "jsonpcallback",
        success: function (data) {
            var result = data["result"];
            if (result == 1) {
                getSupportList(tableid, tabletype, domain);
                alert("感谢支持");
            }
            if (result == 2) {
                alert("投票失败,请稍后再试");
            }
            if (result == 0) {
                alert("您已经投过票了");
            }
            if (result == 3) {
                alert("您还未登陆,不能投票");
            }
        }
    });
}

function getSupportList(tableid, tabletype, domain) {
    $.ajax({
        url: domain + "/index.php",
        data: {
            a: "comment",
            f: "supportlist",
            tid: tableid,
            ttype: tabletype
        },
        dataType: "jsonp",
        jsonp: "jsonpcallback",
        success: function (data) {
            var result = new Array();
            result = data["result"];
            var listcontent = '';
            $.each(result, function (i, v) {
                //v["avatar"] = "/images/user/avatar_test.jpg";
                if (v["avatarsmall"].length < 10 || v["avatarsmall"] == "") {
                    v["avatarsmall"] = "/upload/user/default.gif";
                }
                var username = "";
                if (v["nickname"] != "") {
                    username = v["nickname"];
                } else {
                    username = v["username"];
                }
                listcontent = listcontent
                    + '<div style="margin:3px;width:73px;height:75px; float:left;overflow:hidden;text-align:center;">'
                    + '<a href="' + domain + '/user/showuser.html?uid=' + v["userid"] + '" target="_blank">'
                    + '<img src="' + domain + v["avatarsmall"] + '" alt="" style="padding:1px; border:1px solid #d8d8d8;width:48px; height:48px;"/>'
                    + '</a></br>'
                    + '<a href="' + domain + '/user/showuser.html?uid=' + v["userid"] + '" target="_blank">' + username + '</a>'
                    + '</div>';
            });
            listcontent = listcontent + '<div style="clear:left"></div>';
            $("#supportlist").html(listcontent);
        }
    });
}
//tableid : 传过来的ID 通过getby和tabletype来判断为什么类型的ID
//tabletype:用来判断在数据库中的tabletype类型 1:相册,2:照片,3:活动
//getby:    用于判断tableid的类型 比如 "user","site","channel"之类的
//top:      要替换多少行
//replaceid:往这个ID的DIV里面写入
//template: 要替换的模板
//domain:   域名
function getCommentListForEveryType(tableid, tabletype, getby, top, replaceid, template, domain) {
    $.ajax({
        url: domain + "/index.php",
        data: {
            a: "comment",
            f: "getbylist",
            getby: getby,
            tid: tableid,
            ttype: tabletype,
            top: top
        },
        dataType: "jsonp",
        jsonp: "jsonpcallback",
        success: function (data) {
            var result = new Array();
            result = data["result"];
            var listcontent = '';
            $.each(result, function (i, v) {
                if (v["avatarsmall"] == "") {
                    v["avatarsmall"] = "/upload/user/default.gif";
                }
                var username = "";
                if (v["NickName"] != "" && v["NickName"] != null) {
                    username = v["NickName"];
                } else {
                    if (v["UserName"] == "" && v["UserName"] != null) {
                        username = "游客";
                    } else {
                        username = v["UserName"];
                    }
                }
                listcontent = listcontent + '<div style="border-bottom:2px dashed #CCC; height:80px;width:98%; margin:8px 4px;overflow:hidden">'
                    + '<div style="float:left;width:60px;padding:5px">'
                    + '<div><img src="' + v["avatarsmall"] + '" width="50" height="50"></div>'
                    + '</div>'
                    + '<div style="float:left;">'
                    + '<div style="text-align:left;line-height:180%;">' + username + ':  </div>'
                    + '</div>'
                    + '<div style="color:#666;text-align:left;line-height:180%;font-size:14px;">'
                    + '<a href="' + domain + '/index.php?a=comment&f=gourl&id=' + v["CommentID"] + '" style="color:#666">' + v["Content"] + '</a></dd>'
                    + '</div>'
                    + '</div>'
                    + '<div style="clear:left"></div>';
            });
            listcontent = listcontent.replaceAll("{box}", "<div>");
            listcontent = listcontent.replaceAll("{/box}", "</div>");
            listcontent = listcontent.replaceAll("{span}", "<span>");
            listcontent = listcontent.replaceAll("{/span}", "</span>");
            $("#" + replaceid).html(listcontent);
        }
    });
}

function comment_reply(id) {
    $(".reply").css("display", "none");
    $("#reply_" + id).css("display", "block");
}


