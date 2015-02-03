<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}

    <script type="text/javascript">

        $(function () {


            $('#tabs').tabs();

            $(".select_all").click(function(){
                var type=$(this).attr("idvalue");
                var checkboxes=$("."+type+"[type='checkbox']");

                if (checkboxes.prop("checked")) {
                    checkboxes.prop("checked", false);//取消全选
                    $(this).html("全选");
                } else {
                    checkboxes.prop("checked", true);//全选
                    $(this).html("取消");
                }

            });

            $("#get_root_children").click(function(){   //展开节点授权后，禁用站点授权
                $(this).attr("onclick","");
                $(".channel_manage_all").prop("checked", false);
                $("#all_site_table").slideUp("fast");

            });

            $("#sel_site").change(function() {
                var siteId = parseInt($("#sel_site").val());
                if(siteId>0){
                    window.location.href="/default.php?secu=manage&mod=manage_user_authority&m=set_by_manage_user_group&manage_user_group_id={ManageUserGroupId}&manage_user_group_name={ManageUserGroupName}&site_id="+siteId;
                }
                $("#selectall").html("全选");
            });
        });


        /**
         * 全选一行
         **/
        function SelectRow(channelId){
            var checkboxes=$(".channel_manage_"+channelId+"[type='checkbox']");

            if (checkboxes.prop("checked")) {
                checkboxes.prop("checked", false);//取消全选
                $("#channel_manage_"+channelId).html("全选");
            } else {
                checkboxes.prop("checked", true);//全选
                $("#channel_manage_"+channelId).html("取消");
            }

        }

        /**
         * 全选所有子节点列
         **/
        function SelectCol(checkboxId){
            var parentBox=$("#"+checkboxId+"[type='checkbox']");

            var checkboxes=$("."+checkboxId+"[type='checkbox']");

            if(checkboxes.length>0){
                checkboxes.each(function(){
                    if (parentBox.prop("checked")) {
                        checkboxes.prop("checked", true);//全选
                    } else {
                        checkboxes.prop("checked", false);//取消全选
                    }
                    var channelId=$(this).attr("id");
                    SelectCol(channelId);
                });
            }

        }

        /**
         * ajax取子频道数据
         **/
        function GetChildren(parentId,rank){
            $("#get_children_"+parentId).attr("onclick","HideAndShowChildren("+parentId+")");  //修改ajax调用按钮方法为  隐藏/展开
            $("#get_children_"+parentId).html("-");
            $("#get_children_"+parentId).css("padding","0 4px");

            if(parentId>0){
                $.ajax({
                    url: "/default.php?secu=manage&mod=manage_user_authority&m=get_child_channel_list&type=manage_user_group",
                    data: {ParentId: parentId,ManageUserGroupId: {ManageUserGroupId}},
                    type: "POST",
                    dataType: 'json',
                    success: function(result){
                        if(rank==0){                                    //子频道集合div块 直接加在父频道后面
                            $("#channel_list").append(result);
                            $(".children_area[idvalue='"+parentId+"']").slideDown("fast");
                        }else{
                            $("#channel_"+parentId).append(result);
                            $(".children_area[idvalue='"+parentId+"']").slideDown("fast");
                        }

                        $(".parent_"+parentId).each(function(){     //处理缩进
                            var distance=(parseInt($(this).attr("idvalue")))*5;
                            $(this).css("margin-left",distance+"px");
                        });


                        $("li.child_of_"+parentId).each(function(){   //给有子频道的节点添加展开按钮
                            var channelId=$(this).attr("id");
                            if($(this).attr("idvalue")!=""){
                                $(this).prepend('<span class="get_children" id="get_children_'+channelId+'" onclick="GetChildren('+channelId+','+(rank+1)+')" idvalue="'+channelId+'" style="font-size:10px;height:20px; cursor: pointer;padding:0 2px; border: solid 1px #cccccc; background: #efefef;">+</span>');
                            }
                        });

                        $(".channel_manage_"+parentId+"[type='checkbox']").each(function(){   //给有父频道的节点所有checkbox添加全选事件
                            var id=$(this).attr("id");
                            $(this).attr("onclick","SelectCol('"+id+"')");
                        });




                    }
                });
            }
        }

        /**
         * 隐藏/展开子频道
         **/
        function HideAndShowChildren(channelId) {
            if($(".children_area[idvalue='"+channelId+"']").css("display")=="none"){
                $(".children_area[idvalue='"+channelId+"']").slideDown("fast");
                $("#get_children_"+channelId).html("-");
                $("#get_children_"+channelId).css("padding","0 4px");
                $("#channel_"+channelId).removeClass("grid_item_selected");

            }else{
                $(".children_area[idvalue='"+channelId+"']").slideUp("fast",function(){$("#channel_"+channelId).addClass("grid_item_selected")});
                $("#get_children_"+channelId).html("+");
                $("#get_children_"+channelId).css("padding","0 2px");
                //$("#channel_"+channelId).addClass("grid_item_selected");
            }

        }

        function submitForm(closeTab) {

            if (closeTab == 1) {
                $("#CloseTab").val("1");
            } else {
                $("#CloseTab").val("0");
            }
            var siteId= parseInt($("#sel_site").val());
            $("#mainForm").attr("action", "/default.php?secu=manage&mod=manage_user_authority&m=set_by_manage_user_group&manage_user_group_id={ManageUserGroupId}&site_id="+siteId+"&tab_index=" + parent.G_TabIndex + "");
            $('#mainForm').submit();

        }

    </script>
    <style>
        div.one_child_line{}
        .child_title{float:left;line-height: 39px;height:40px}
        div.checkbox_area{height:39px;line-height: 39px;border-bottom: 1px dashed #D5D5D5;}
        .channel_children li{height:40px;line-height: 40px;text-align: left}
        #channel_list{margin-top:20px;width:99%}
        .check_box{float:left;margin-left:20px}
        .channel_name{width:100px;overflow:hidden;float:left;margin-left:5px;}
    </style>
</head>
<body>
{common_body_deal}
<div class="div_deal">
<form id="mainForm" enctype="multipart/form-data" method="post">
<table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td class="spe_line" height="40" align="right">
            <input class="btn" value="确认并关闭" type="button" onclick="submitForm(1)"/>
            <input class="btn" value="确认并继续" type="button" onclick="submitForm(0)"/>
            <input class="btn" value="取 消" type="button" onclick="closeTab()"/>
        </td>
    </tr>
</table>
<table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">

<tr>
    <td class="spe_line" style=" height: 40px; font-size: 14px; font-weight: bold;">
        <label for="sel_site">选择一个站点： </label>
        <select id="sel_site" name="f_SiteId">
            <option value="-1">请选择一个站点</option>
            <icms id="site_list" type="list">
                <item>
                    <![CDATA[
                    <option value="{f_SiteId}">{f_SiteName}</option>
                    ]]>
                </item>
            </icms>
            <option value="0">仅系统权限</option>
        </select><!--，为<span style="color:red;"> {ManageUserGroupName} </span>授权-->
    </td>
    <script type="text/javascript">
        $("#sel_site").find("option[value='{SiteId}']").attr("selected",true);
    </script>
</tr>
<tr>
    <td>
        <div id="tabs">
            <ul>
                <li><a href="#tabs-1">站点权限</a></li>
                <li><a href="#tabs-2">频道权限</a></li>
                <li><a href="#tabs-3">会员权限</a></li>
                <li><a href="#tabs-4">系统权限</a></li>
            </ul>
            <div id="tabs-1">
                <div style="">
                    <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
                        <tr>

                            <td width="100" class="spe_line" height="40" align="right">
                                <span class="select_all" idvalue="site_manage" style="height:20px; cursor: pointer;margin-left: 10px;padding:2px; border: solid 2px #cccccc; background: #efefef;">全选</span>

                            </td>
                        </tr>
                        <tr>
                            <td width="100" class="spe_line" height="40" align="right">
                                <label for="auth_ManageSite">管理站点：</label></td>
                            <td class="spe_line">
                                <input id="auth_ManageSite" name="auth_ManageSite" type="checkbox" class="site_manage" {c_ManageSite}/>
                            </td>
                        </tr>
                        <tr>
                            <td width="100" class="spe_line" height="40" align="right">
                                <label for="auth_ManageComment">管理评论：</label></td>
                            <td class="spe_line">
                                <input id="auth_ManageComment" name="auth_ManageComment" type="checkbox" class="site_manage" {c_ManageComment}/>
                            </td>
                        </tr>
                        <tr>
                            <td width="100" class="spe_line" height="40" align="right">
                                <label for="auth_ManageTemplateLibrary">管理模板：</label></td>
                            <td class="spe_line">
                                <input id="auth_ManageTemplateLibrary" name="auth_ManageTemplateLibrary" type="checkbox" class="site_manage" {c_ManageTemplateLibrary}/>
                            </td>
                        </tr>
                        <tr>
                            <td width="100" class="spe_line" height="40" align="right">
                                <label for="auth_ManageFilter">管理过滤：</label></td>
                            <td class="spe_line">
                                <input id="auth_ManageFilter" name="auth_ManageFilter" type="checkbox" class="site_manage" {c_ManageFilter}/>
                            </td>
                        </tr>
                        <tr>
                            <td width="100" class="spe_line" height="40" align="right">
                                <label for="auth_ManageFtp">管理FTP：</label></td>
                            <td class="spe_line">
                                <input id="auth_ManageFtp" name="auth_ManageFtp" type="checkbox" class="site_manage" {c_ManageFtp}/>
                            </td>
                        </tr>

                        <tr>
                            <td width="100" class="spe_line" height="40" align="right">
                                <label for="auth_ManageAd">管理广告：</label></td>
                            <td class="spe_line">
                                <input id="auth_ManageAd" name="auth_ManageAd" type="checkbox" class="site_manage" {c_ManageAd}/>
                            </td>
                        </tr>
                        <tr>
                            <td width="100" class="spe_line" height="40" align="right">
                                <label for="auth_ManageDocumentTag">管理文档标签：</label></td>
                            <td class="spe_line">
                                <input id="auth_ManageDocumentTag" name="auth_ManageDocumentTag" type="checkbox" class="site_manage" {c_ManageDocumentTag}/>
                            </td>
                        </tr>
                        <tr>
                            <td width="100" class="spe_line" height="40" align="right">
                                <label for="auth_ManageConfig">管理配置：</label></td>
                            <td class="spe_line">
                                <input id="auth_ManageConfig" name="auth_ManageConfig" type="checkbox" class="site_manage" {c_ManageConfig}/>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>


            <div id="tabs-2">
                <div>
                    <table id="all_site_table" width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
                        <tr id="channel_{RootChannelId}">
                            <td class="spe_line" height="40" align="right">全站点：
                                <span class="get_children" id="get_root_children" onclick="GetChildren({RootChannelId},0)" idvalue="{RootChannelId}" style="height:20px; cursor: pointer;margin-left: 10px;padding:2px; border: solid 2px #cccccc; background: #efefef;">展开</span>
                            </td>
                            <td width="100" class="spe_line" height="40" align="right">
                                <span class="select_all" idvalue="channel_manage_all" style="height:20px; cursor: pointer;margin-left: 10px;padding:2px; border: solid 2px #cccccc; background: #efefef;">全选</span>

                            </td>
                            <td class="spe_line" height="40">
                                <div>
                                    <ul>
                                        <li class="check_box"><label for="f_ChannelExplore">浏览</label><input class="channel_manage_all" type="checkbox" id="f_ChannelExplore" name="f_ChannelExplore" {c_ChannelExplore} /></li>
                                        <li class="check_box"><label for="f_ChannelCreate">新增</label><input class="channel_manage_all" type="checkbox" id="f_ChannelCreate" name="f_ChannelCreate" {c_ChannelCreate}/></li>
                                        <li class="check_box"><label for="f_ChannelModify">编辑</label><input class="channel_manage_all" type="checkbox" id="f_ChannelModify" name="f_ChannelModify" {c_ChannelModify}/></li>
                                        <li class="check_box"><label for="f_ChannelDisabled">停用</label><input class="channel_manage_all" type="checkbox" id="f_ChannelDisabled" name="f_ChannelDisabled" {c_ChannelDisabled}/></li>
                                        <li class="check_box"><label for="f_ChannelDelete">删除</label><input class="channel_manage_all" type="checkbox" id="f_ChannelDelete" name="f_ChannelDelete" {c_ChannelDelete}/></li>
                                        <li class="check_box"><label for="f_ChannelSearch">查询</label><input class="channel_manage_all" type="checkbox" id="f_ChannelSearch" name="f_ChannelSearch" {c_ChannelSearch}/></li>
                                        <li class="check_box"><label for="f_ChannelRework">返工</label><input class="channel_manage_all" type="checkbox" id="f_ChannelRework" name="f_ChannelRework" {c_ChannelRework}/></li>
                                        <li class="check_box"><label for="f_ChannelAudit1">一审</label><input class="channel_manage_all" type="checkbox" id="f_ChannelAudit1" name="f_ChannelAudit1" {c_ChannelAudit1}/></li>
                                        <li class="check_box"><label for="f_ChannelAudit2">二审</label><input class="channel_manage_all" type="checkbox" id="f_ChannelAudit2" name="f_ChannelAudit2" {c_ChannelAudit2}/></li>
                                        <li class="check_box"><label for="f_ChannelAudit3">三审</label><input class="channel_manage_all" type="checkbox" id="f_ChannelAudit3" name="f_ChannelAudit3" {c_ChannelAudit3}/></li>
                                        <li class="check_box"><label for="f_ChannelAudit4">终审</label><input class="channel_manage_all" type="checkbox" id="f_ChannelAudit4" name="f_ChannelAudit4" {c_ChannelAudit4}/></li>
                                        <li class="check_box"><label for="f_ChannelRefused">已否</label><input class="channel_manage_all" type="checkbox" id="f_ChannelRefused" name="f_ChannelRefused" {c_ChannelRefused}/></li>
                                        <li class="check_box"><label for="f_ChannelPublish">发布</label><input class="channel_manage_all" type="checkbox" id="f_ChannelPublish" name="f_ChannelPublish" {c_ChannelPublish}/></li>
                                        <li class="check_box"><label for="f_ChannelDoOthers">操作他人</label><input class="channel_manage_all" type="checkbox" id="f_ChannelDoOthers" name="f_ChannelDoOthers" {c_ChannelDoOthers}/></li>
                                        <li class="check_box"><label for="f_ChannelDoOthersInSameGroup">操作同组</label><input class="channel_manage_all" type="checkbox" id="f_ChannelDoOthersInSameGroup" name="f_ChannelDoOthersInSameGroup" {c_ChannelDoOthersInSameGroup}/></li>
                                        <li class="check_box"><label for="f_ChannelManageTemplate">管理模板</label><input class="channel_manage_all" type="checkbox" id="f_ChannelManageTemplate" name="f_ChannelManageTemplate" {c_ChannelManageTemplate}/></li>
                                    </ul>
                                </div>

                            </td>
                        </tr>
                    </table>
                    <div id="channel_list" align="center">
                    </div>
                </div>
            </div>

            <div id="tabs-3">
                <div>

                    <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td class="spe_line" height="40" align="right">本版所有：</td>
                            <td width="100" class="spe_line" height="40" align="right">
                                <span class="select_all" idvalue="user_manage" style="height:20px; cursor: pointer;margin-left: 10px;padding:2px; border: solid 2px #cccccc; background: #efefef;">全选</span>

                            </td>
                        </tr>
                        <tr>
                            <td class="spe_line" height="40" align="right">会员：</td>
                            <td width="100" class="spe_line" height="40" align="right">
                                <span class="select_all" idvalue="user_basic_manage" style="height:20px; cursor: pointer;margin-left: 10px;padding:2px; border: solid 2px #cccccc; background: #efefef;">全选</span>

                            </td>
                            <td class="spe_line" height="40">
                                <div>
                                    <ul>
                                        <li class="check_box"><label for="f_UserExplore">浏览会员</label><input class="user_basic_manage user_manage" type="checkbox" id="f_UserExplore" name="f_UserExplore" {c_UserExplore}/></li>
                                        <li class="check_box"><label for="f_UserAdd">新增会员</label><input class="user_basic_manage user_manage" type="checkbox" id="f_UserAdd" name="f_UserAdd" {c_UserAdd}/></li>
                                        <li class="check_box"><label for="f_UserEdit">编辑会员</label><input class="user_basic_manage user_manage" type="checkbox" id="f_UserEdit" name="f_UserEdit" {c_UserEdit}/></li>
                                        <li class="check_box"><label for="f_UserDelete">删除会员</label><input class="user_basic_manage user_manage" type="checkbox" id="f_UserDelete" name="f_UserDelete" {c_UserDelete}/></li>
                                    </ul>
                                </div>

                            </td>
                        </tr>
                        <tr>
                            <td class="spe_line" height="40" align="right">会员权限：</td>
                            <td width="100" class="spe_line" height="40" align="right">
                                <span class="select_all" idvalue="user_role_manage" style="height:20px; cursor: pointer;margin-left: 10px;padding:2px; border: solid 2px #cccccc; background: #efefef;">全选</span>

                            </td>
                            <td class="spe_line" height="40">
                                <div>
                                    <ul>
                                        <li class="check_box"><label for="f_UserRoleExplore">会员权限浏览</label><input class="user_role_manage user_manage" type="checkbox" id="f_UserRoleExplore" name="f_UserRoleExplore" {c_UserRoleExplore}/></li>
                                        <li class="check_box"><label for="f_UserRoleAdd">会员权限新增</label><input class="user_role_manage user_manage" type="checkbox" id="f_UserRoleAdd" name="f_UserRoleAdd" {c_UserRoleAdd}/></li>
                                        <li class="check_box"><label for="f_UserRoleEdit">会员权限编辑</label><input class="user_role_manage user_manage" type="checkbox" id="f_UserRoleEdit" name="f_UserRoleEdit" {c_UserRoleEdit}/></li>
                                        <li class="check_box"><label for="f_UserRoleDelete">会员权限删除</label><input class="user_role_manage user_manage" type="checkbox" id="f_UserRoleDelete" name="f_UserRoleDelete" {c_UserRoleDelete}/></li>
                                    </ul>
                                </div>

                            </td>
                        </tr>
                        <tr>
                            <td class="spe_line" height="40" align="right">会员相册：</td>
                            <td width="100" class="spe_line" height="40" align="right">
                                <span class="select_all" idvalue="user_album_manage" style="height:20px; cursor: pointer;margin-left: 10px;padding:2px; border: solid 2px #cccccc; background: #efefef;">全选</span>

                            </td>
                            <td class="spe_line" height="40">
                                <div>
                                    <ul>
                                        <li class="check_box"><label for="f_UserAlbumExplore">会员相册浏览</label><input class="user_album_manage user_manage" type="checkbox" id="f_UserAlbumExplore" name="f_UserAlbumExplore" {c_UserAlbumExplore}/></li>
                                        <li class="check_box"><label for="f_UserAlbumAdd">会员相册新增</label><input class="user_album_manage user_manage" type="checkbox" id="f_UserAlbumAdd" name="f_UserAlbumAdd" {c_UserAlbumAdd}/></li>
                                        <li class="check_box"><label for="f_UserAlbumEdit">会员相册编辑</label><input class="user_album_manage user_manage" type="checkbox" id="f_UserAlbumEdit" name="f_UserAlbumEdit" {c_UserAlbumEdit}/></li>
                                        <li class="check_box"><label for="f_UserAlbumDelete">会员相册删除</label><input class="user_album_manage user_manage" type="checkbox" id="f_UserAlbumDelete" name="f_UserAlbumDelete" {c_UserAlbumDelete}/></li>
                                    </ul>
                                </div>

                            </td>
                        </tr>
                        <tr>
                            <td class="spe_line" height="40" align="right">其他：</td>
                            <td width="100" class="spe_line" height="40" align="right">
                                <span class="select_all" idvalue="user_other_manage" style="height:20px; cursor: pointer;margin-left: 10px;padding:2px; border: solid 2px #cccccc; background: #efefef;">全选</span>

                            </td>
                            <td class="spe_line" height="40">
                                <div>
                                    <ul>
                                        <li class="check_box"><label for="f_UserGroupExplore">会员组浏览</label><input   class="user_other_manage user_manage" type="checkbox" id="f_UserGroupExplore" name="f_UserGroupExplore"  {c_UserGroupExplore}/></li>
                                        <li class="check_box"><label for="f_UserLevelExplore">会员等级浏览</label><input class="user_other_manage user_manage" type="checkbox" id="f_UserLevelExplore" name="f_UserLevelExplore" {c_UserLevelExplore}/></li>
                                        <li class="check_box"><label for="f_UserOrderExplore">会员订单浏览</label><input class="user_other_manage user_manage" type="checkbox" id="f_UserOrderExplore" name="f_UserOrderExplore"  {c_UserOrderExplore}/></li>
                                        </ul>
                                </div>

                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div id="tabs-4">
                <div>

                    <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
                        <tr>

                            <td width="100" class="spe_line" height="40" align="right">
                                <span class="select_all" idvalue="system_manage" style="height:20px; cursor: pointer;margin-left: 10px;padding:2px; border: solid 2px #cccccc; background: #efefef;">全选</span>

                            </td>
                        </tr>
                        <tr>
                            <td width="150" class="spe_line" height="40" align="right">
                                <label for="f_ManageUserTaskManageState">修改管理员任务：</label></td>
                            <td class="spe_line">
                                <input id="f_ManageUserTaskManageState" name="f_ManageUserTaskManageState" type="checkbox" class="system_manage" {c_ManageUserTaskManageState}/>
                            </td>
                        </tr>
                        <tr>
                            <td width="150" class="spe_line" height="40" align="right">
                                <label for="f_ManageUserTaskViewAll">查看所有管理员任务：</label></td>
                            <td class="spe_line">
                                <input id="f_ManageUserTaskViewAll" name="f_ManageUserTaskViewAll" type="checkbox" class="system_manage" {c_ManageUserTaskViewAll}/>
                            </td>
                        </tr>
                        <tr>
                            <td width="150" class="spe_line" height="40" align="right">
                                <label for="f_ManageUserTaskViewSameGroup">查看同组管理员任务：</label></td>
                            <td class="spe_line">
                                <input id="f_ManageUserTaskViewSameGroup" name="f_ManageUserTaskViewSameGroup" type="checkbox" class="system_manage" {c_ManageUserTaskViewSameGroup}/>
                            </td>
                        </tr>
                        <tr>
                            <td width="150" class="spe_line" height="40" align="right">
                                <label for="f_ManageUserExplore">查看管理员：</label></td>
                            <td class="spe_line">
                                <input id="f_ManageUserExplore" name="f_ManageUserExplore" type="checkbox" class="system_manage" {c_ManageUserExplore}/>
                            </td>
                        </tr>
                        <tr>
                            <td width="150" class="spe_line" height="40" align="right">
                                <label for="f_ManageUserCreate">新增管理员：</label></td>
                            <td class="spe_line">
                                <input id="f_ManageUserCreate" name="f_ManageUserCreate" type="checkbox" class="system_manage" {c_ManageUserCreate}/>
                            </td>
                        </tr>
                        <tr>
                            <td width="150" class="spe_line" height="40" align="right">
                                <label for="f_ManageUserModify">编辑管理员信息：</label></td>
                            <td class="spe_line">
                                <input id="f_ManageUserModify" name="f_ManageUserModify" type="checkbox" class="system_manage" {c_ManageUserModify}/>
                            </td>
                        </tr>
                        <tr>
                            <td width="150" class="spe_line" height="40" align="right">
                                <label for="f_ManageUserGroupExplore">查看管理员组：</label></td>
                            <td class="spe_line">
                                <input id="f_ManageUserGroupExplore" name="f_ManageUserGroupExplore" type="checkbox" class="system_manage" {c_ManageUserGroupExplore}/>
                            </td>
                        </tr>
                        <tr>
                            <td width="150" class="spe_line" height="40" align="right">
                                <label for="f_ManageUserGroupCreate">新增管理员组：</label></td>
                            <td class="spe_line">
                                <input id="f_ManageUserGroupCreate" name="f_ManageUserGroupCreate" type="checkbox" class="system_manage" {c_ManageUserGroupCreate}/>
                            </td>
                        </tr>
                        <tr>
                            <td width="150" class="spe_line" height="40" align="right">
                                <label for="f_ManageUserGroupModify">修改管理员组：</label></td>
                            <td class="spe_line">
                                <input id="f_ManageUserGroupModify" name="f_ManageUserGroupModify" type="checkbox" class="system_manage" {c_ManageUserGroupModify}/>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </td>
</tr>
</table>
<table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td height="60" align="center">
            <input class="btn" value="确认并关闭" type="button" onclick="submitForm(1)"/>
            <input class="btn" value="确认并继续" type="button" onclick="submitForm(0)"/>
            <input class="btn" value="取 消" type="button" onclick="closeTab()"/>
        </td>
    </tr>
</table>
</form>
</div>
</body>
</html>
