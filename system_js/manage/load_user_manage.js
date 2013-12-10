$(document).ready(function() {
    //会员管理
    $("#btnUserExplore").click(function (event) {
        $("#td_main_btn img").css("display", "none");
        var pageindex = 1;
        //show searchbox
        $("#btnbatchuser").css("display", "inline");
        $('#searchtype').text("user");
        $('#searchbox').css("display", "inline");
        $("#tabs").tabs("select", "#tabs-1");
        loaduserlist(pageindex, "");
    });
                
    //国外投稿作者 中摄协用
    $("#btnForeignUserExplore").click(function (event) {
        $("#td_main_btn img").css("display", "none");
        var pageindex = 1;
        //show searchbox
        $("#btnbatchuser").css("display", "inline");
        $('#searchtype').text("user");
        $('#searchbox').css("display", "inline");
        $("#tabs").tabs("select", "#tabs-1");
        loadforeignuserlist(pageindex, "");
    });
                
    //国外投稿作者 中摄协用
    $("#btnDomesticUserExplore").click(function (event) {
        $("#td_main_btn img").css("display", "none");
        var pageindex = 1;
        //show searchbox
        $("#btnbatchuser").css("display", "inline");
        $('#searchtype').text("user");
        $('#searchbox').css("display", "inline");
        $("#tabs").tabs("select", "#tabs-1");
        loaddomesticuserlist(pageindex, "");
    });

    //会员组管理
    $("#btnUserGroupExplore").click(function (event) {
        $("#td_main_btn img").css("display", "none");
        $("#btnaddusergroup").css("display", "inline");
        var pageindex = 1;
        var groupid = 0;
        //close searchbox
        $('#searchtype').text("usergroup");
        $('#searchbox').css("display", "block");
        $("#tabs").tabs("select", "#tabs-1");
        loadusergrouplist(pageindex, groupid, '');
    });
    //会员等级管理
    $("#btnUserLevelExplore").click(function (event) {
        $("#td_main_btn img").css("display", "none");
        $("#btnadduserlevel").css("display", "inline");
        var pageindex = 1;
        var levelid = 0;
        //close searchbox
        $('#searchtype').text("userlevel");
        $('#searchbox').css("display", "block");
        $("#tabs").tabs("select", "#tabs-1");
        loaduserlevellist(pageindex, levelid, '');
    });
    //会员相册管理
    $("#btnUserAlbumExplore").click(function (event) {
        $("#td_main_btn img").css("display", "none");
        $("#selectorderstate").css("display", "inline");
        $('#searchtype').text("useralbum");
        $('#searchbox').css("display", "none");
        $('#searchbox #viewselfbtn').css("display", "none");
        $('#searchbox #viewallbtn').css("display", "none");
        var pageindex = 1;
        var state = 100;
        var data = undefined;
        $("#tabs").tabs("select", "#tabs-1");
        loaduseralbumlist(pageindex, "", data, state);
    });
    //国外相册管理 给中摄协用的
    $("#btnForeignUserAlbumExpl").click(function (event) {
        $("#td_main_btn img").css("display", "none");
        $("#selectorderstate").css("display", "inline");
        $('#searchtype').text("useralbum");
        $('#searchbox').css("display", "none");
        $('#searchbox #viewselfbtn').css("display", "none");
        $('#searchbox #viewallbtn').css("display", "none");
        var pageindex = 1;
        var state = 100;
        var data = undefined;
        $("#tabs").tabs("select", "#tabs-1");
        loadforeignuseralbumlist(pageindex, "", data, state);
    });
    //会员订单管理
    $("#btnUserOrderExplore").click(function (event) {
        $("#td_main_btn img").css("display", "none");
        $("#selectorderstate").css("display", "inline");
        $('#searchtype').text("userorder");
        $('#searchbox').css("display", "inline");
        $('#searchbox #viewselfbtn').css("display", "none");
        $('#searchbox #viewallbtn').css("display", "none");
        var pageindex = 1;
        var state = 0;
        $("#tabs").tabs("select", "#tabs-1");
        loaduserorderlist(pageindex, "", state);
    });
    //会员站点角色管理
    $("#btnUserRoleExplore").click(function (event) {
        $("#td_main_btn img").css("display", "none");
        var pageindex = 1;
        //show searchbox
        $("#btnaddrole").css("display", "inline");
        $('#searchtype').text("userrole");
        $('#searchbox').css("display", "inline");
        $("#tabs").tabs("select", "#tabs-1");
        loaduserrolelist(pageindex, "");
    });   

    //安康卡订购申请书
    $("#btnUserInsuranceExplore").click(function (event) {
        $("#td_main_btn img").css("display", "none");
        var pageindex = 1;
        var userid = 0;
        var customertype = 0;
        var state = -1;
        //close searchbox
        $('#searchtype').text("userinsurance");
        $('#searchbox').css("display", "block");
        loaduserinsurancelist(pageindex, '', userid, customertype, state);
    }); 
    //保险职业管理
    $("#btnUserProfessionExplor").click(function (event) {
        $("#td_main_btn img").css("display", "none");
        $('#btnaddprofession').css("display", "inline");

        var pageindex = 1;
        var professiontype = -1;
        var professionlevel = -1;
        var parentid = -1;
        //close searchbox
        $('#searchtype').text("userprofession");
        $('#searchbox').css("display", "block");
        loaduserprofessionlist(pageindex, '', professiontype, professionlevel, parentid);
    }); 
    //保险会员地区管理
    $("#btnUserRegionExplor").click(function (event) {
        $("#td_main_btn img").css("display", "none");
        $('#btnaddregion').css("display", "inline");

        var pageindex = 1;
        var regiontype = -1;
        //close searchbox
        $('#searchtype').text("userregion");
        $('#searchbox').css("display", "block");
        loaduserregionlist(pageindex, '', regiontype);
    });
    //加盟会员审核 
    $("#btnUserStateExplore").click(function (event) {
        $("#td_main_btn img").css("display", "none");
        var pageindex = 1;
        //show searchbox
        $("#btnbatchuser").css("display", "inline");
        $('#searchtype').text("user");
        $('#searchbox').css("display", "inline");
        $("#tabs").tabs("select", "#tabs-1");
        loaduserlist(pageindex, "");
    }); 
    //安康卡订购确认函
    $("#btnUserUnderwritingExplore").click(function (event) {
        $("#td_main_btn img").css("display", "none");
        var pageindex = 1;
        //show searchbox
        $("#btnbatchuser").css("display", "inline");
        $('#searchtype').text("userunderwriting");
        $('#searchbox').css("display", "inline");
        $("#tabs").tabs("select", "#tabs-1");
        loaduserunderwritinglist(pageindex, "");
    });
    //其他保险业务推荐函信息列表
    $("#btnUserOthreInsuranceExplore").click(function (event) {
        $("#td_main_btn img").css("display", "none");
        var pageindex = 1;
        var userid = 0;
        var customertype = 2;
        var state = -1;
        //close searchbox
        $('#searchtype').text("userinsurance");
        $('#searchbox').css("display", "block");
        loaduserinsurancelist(pageindex, '', userid, customertype, state);
    });

    //其他保险业务成交确认函信息列表
    $("#btnUserOthreInsuranceTwoExplore").click(function (event) {
        $("#td_main_btn img").css("display", "none");
        var pageindex = 1;
        var customertype = 2;   //0:安康卡(默认)  1:福佑卡 2:其他业务
        //show searchbox
        $("#btnbatchuser").css("display", "inline");
        $('#searchtype').text("userothreinsurance");
        $('#searchbox').css("display", "inline");
        $("#tabs").tabs("select", "#tabs-1");
        loaduserothreinsurancelist(pageindex, customertype, "");
    });

    //安康卡业绩确认函信息管理列表类信息
    $("#btnUserRewardExplore").click(function (event) {
        $("#td_main_btn img").css("display", "none");
        var pageindex = 1;
        var state = -1;
        var customertype = 0;   //0:安康卡(默认)  1:福佑卡 2:其他业务
        //show searchbox
        $("#btnbatchuser").css("display", "inline");
        $('#searchtype').text("userreward");
        $('#searchbox').css("display", "inline");
        $("#tabs").tabs("select", "#tabs-1");
        loaduserrewardlist(pageindex, state, "",customertype);
    });

    //其他保险业务业绩确认函信息管理列表类信息
    $("#btnUserOthreRewardExplore").click(function (event) {
        $("#td_main_btn img").css("display", "none");
        var pageindex = 1;
        var state = -1;
        var customertype = 2;   //0:安康卡(默认)  1:福佑卡 2:其他业务
        //show searchbox
        $("#btnbatchuser").css("display", "inline");
        $('#searchtype').text("userreward");
        $('#searchbox').css("display", "inline");
        $("#tabs").tabs("select", "#tabs-1");
        loaduserrewardlist(pageindex, state, "", customertype);
    });
                
    //加盟会员统计管理
    $("#btnUserCountExplore").click(function (event) {
        $("#td_main_btn img").css("display", "none");
        $('#btnusercountsearch').css("display", "inline");

        var pageindex = 1;
        var userid = -1;
        var rank = -1;
        var count = 0;
        //close searchbox
        $('#searchtype').text("usercount");
        $('#searchbox').css("display", "block");
        loadusercountlist(pageindex,"",userid,rank,count);
    });

    //会员业绩统计管理
    $("#btnUserRewardCountExplore").click(function (event) {
        $("#td_main_btn img").css("display", "none");
        $('#btnuserrewardcountsearch').css("display", "inline");

        var pageindex = 1;
        var userid = -1;
        var rank = -1;
        var count = 0;
        //close searchbox
        $('#searchtype').text("userrewardcount");
        $('#searchbox').css("display", "block");
        loaduserrewardcountlist(pageindex,"",userid,rank,count);
    });
                
    //频道搜索
    $("#btnChannelSearch").click(function (event) {
        $("#td_main_btn img").css("display", "none");
        $("#selectorderstate").css("display", "inline");
        $('#searchtype').text("useralbum");
        $('#searchbox').css("display", "none");
        $('#searchbox #viewselfbtn').css("display", "none");
        $('#searchbox #viewallbtn').css("display", "none");
        var pageindex = 1;
        var state = 100;
        var data = undefined;
        $("#tabs").tabs("select", "#tabs-1");
        loadchannelsearch();
    });
});

