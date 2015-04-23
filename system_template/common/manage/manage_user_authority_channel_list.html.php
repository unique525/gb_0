<div class="children_area" idvalue="{ParentId}" style="width:99%;display:none">
<icms id="children_channel_list" type="list">
    <item>
        <![CDATA[
        <div id="channel_{f_ChannelId}" class="grid_item parent_{f_ParentId}" idvalue="{f_Rank}" style="overflow: hidden">
            <div class="child_title" align="right" width="auto" height="40">
                <span id="channel_manage_{f_ChannelId}" onclick="SelectRow({f_ChannelId})" idvalue="channel_manage_{f_ChannelId}" style="height:20px; cursor: pointer;margin-left: 10px;padding:2px; background: #efefef;">全选</span>
            </div>
            <div class="checkbox_area checkbox_area_{f_ChannelId}" style="border-bottom: 1px dashed #D5D5D5;">
                <div>
                    <ul class="channel_children" >
                        <li class="channel_name child_of_{f_ParentId}" idvalue="{f_ChildrenChannelId}" title="{f_ChannelName}" id="{f_ChannelId}">{f_ChannelName}</li>
                        <li style="display:none"><label for="ChannelId">浏览</label><input type="text" id="ChannelId" name="ChannelId_{f_ChannelId}" value="{f_ChannelId}"/></li>
                        <li class="check_box"><label for="Channel_{f_ChannelId}_ChannelExplore">浏览</label><input idvalue="{f_ParentId}" class="channel_manage_{f_ChannelId} Channel_{f_ParentId}_ChannelExplore" type="checkbox" id="Channel_{f_ChannelId}_ChannelExplore" name="Channel_{f_ChannelId}_ChannelExplore" {c_ChannelExplore}/></li>
                        <li class="check_box"><label for="Channel_{f_ChannelId}_ChannelCreate">新增</label><input idvalue="{f_ParentId}" class="channel_manage_{f_ChannelId} Channel_{f_ParentId}_ChannelCreate" type="checkbox" id="Channel_{f_ChannelId}_ChannelCreate" name="Channel_{f_ChannelId}_ChannelCreate" {c_ChannelCreate}/></li>
                        <li class="check_box"><label for="Channel_{f_ChannelId}_ChannelModify">编辑</label><input idvalue="{f_ParentId}" class="channel_manage_{f_ChannelId} Channel_{f_ParentId}_ChannelModify" type="checkbox" id="Channel_{f_ChannelId}_ChannelModify" name="Channel_{f_ChannelId}_ChannelModify" {c_ChannelModify}/></li>
                        <li class="check_box"><label for="Channel_{f_ChannelId}_ChannelDisabled">停用</label><input idvalue="{f_ParentId}" class="channel_manage_{f_ChannelId} Channel_{f_ParentId}_ChannelDisabled" type="checkbox" id="Channel_{f_ChannelId}_ChannelDisabled" name="Channel_{f_ChannelId}_ChannelDisabled" {c_ChannelDisabled}/></li>
                        <li class="check_box"><label for="Channel_{f_ChannelId}_ChannelDelete">删除</label><input idvalue="{f_ParentId}" class="channel_manage_{f_ChannelId} Channel_{f_ParentId}_ChannelDelete" type="checkbox" id="Channel_{f_ChannelId}_ChannelDelete" name="Channel_{f_ChannelId}_ChannelDelete" {c_ChannelDelete}/></li>
                        <li class="check_box"><label for="Channel_{f_ChannelId}_ChannelSearch">查询</label><input idvalue="{f_ParentId}" class="channel_manage_{f_ChannelId} Channel_{f_ParentId}_ChannelSearch" type="checkbox" id="Channel_{f_ChannelId}_ChannelSearch" name="Channel_{f_ChannelId}_ChannelSearch" {c_ChannelSearch}/></li>
                        <li class="check_box"><label for="Channel_{f_ChannelId}_ChannelRework">返工</label><input idvalue="{f_ParentId}" class="channel_manage_{f_ChannelId} Channel_{f_ParentId}_ChannelRework" type="checkbox" id="Channel_{f_ChannelId}_ChannelRework" name="Channel_{f_ChannelId}_ChannelRework" {c_ChannelRework}/></li>
                        <li class="check_box"><label for="Channel_{f_ChannelId}_ChannelAudit1">一审</label><input idvalue="{f_ParentId}" class="channel_manage_{f_ChannelId} Channel_{f_ParentId}_ChannelAudit1" type="checkbox" id="Channel_{f_ChannelId}_ChannelAudit1" name="Channel_{f_ChannelId}_ChannelAudit1" {c_ChannelAudit1}/></li>
                        <li class="check_box"><label for="Channel_{f_ChannelId}_ChannelAudit2">二审</label><input idvalue="{f_ParentId}" class="channel_manage_{f_ChannelId} Channel_{f_ParentId}_ChannelAudit2" type="checkbox" id="Channel_{f_ChannelId}_ChannelAudit2" name="Channel_{f_ChannelId}_ChannelAudit2" {c_ChannelAudit2}/></li>
                        <li class="check_box"><label for="Channel_{f_ChannelId}_ChannelAudit3">三审</label><input idvalue="{f_ParentId}" class="channel_manage_{f_ChannelId} Channel_{f_ParentId}_ChannelAudit3" type="checkbox" id="Channel_{f_ChannelId}_ChannelAudit3" name="Channel_{f_ChannelId}_ChannelAudit3" {c_ChannelAudit3}/></li>
                        <li class="check_box"><label for="Channel_{f_ChannelId}_ChannelAudit4">终审</label><input idvalue="{f_ParentId}" class="channel_manage_{f_ChannelId} Channel_{f_ParentId}_ChannelAudit4" type="checkbox" id="Channel_{f_ChannelId}_ChannelAudit4" name="Channel_{f_ChannelId}_ChannelAudit4" {c_ChannelAudit4}/></li>
                        <li class="check_box"><label for="Channel_{f_ChannelId}_ChannelRefused">已否</label><input idvalue="{f_ParentId}" class="channel_manage_{f_ChannelId} Channel_{f_ParentId}_ChannelRefused" type="checkbox" id="Channel_{f_ChannelId}_ChannelRefused" name="Channel_{f_ChannelId}_ChannelRefused" {c_ChannelRefused}/></li>
                        <li class="check_box"><label for="Channel_{f_ChannelId}_ChannelPublish">发布</label><input idvalue="{f_ParentId}" class="channel_manage_{f_ChannelId} Channel_{f_ParentId}_ChannelPublish" type="checkbox" id="Channel_{f_ChannelId}_ChannelPublish" name="Channel_{f_ChannelId}_ChannelPublish" {c_ChannelPublish}/></li>
                        <li class="check_box"><label for="Channel_{f_ChannelId}_ChannelDoOthers">操作他人</label><input idvalue="{f_ParentId}" class="channel_manage_{f_ChannelId} Channel_{f_ParentId}_ChannelDoOthers" type="checkbox" id="Channel_{f_ChannelId}_ChannelDoOthers" name="Channel_{f_ChannelId}_ChannelDoOthers" {c_ChannelDoOthers}/></li>
                        <li class="check_box"><label for="Channel_{f_ChannelId}_ChannelDoOthersInSameGroup">操作同组</label><input idvalue="{f_ParentId}" class="channel_manage_{f_ChannelId} Channel_{f_ParentId}_ChannelDoOthersInSameGroup" type="checkbox" id="Channel_{f_ChannelId}_ChannelDoOthersInSameGroup" name="Channel_{f_ChannelId}_ChannelDoOthersInSameGroup" {c_ChannelDoOthersInSameGroup}/></li>
                        <li class="check_box"><label for="Channel_{f_ChannelId}_ChannelManageTemplate">管理模板</label><input idvalue="{f_ParentId}" class="channel_manage_{f_ChannelId} Channel_{f_ParentId}_ChannelManageTemplate" type="checkbox" id="Channel_{f_ChannelId}_ChannelManageTemplate" name="Channel_{f_ChannelId}_ChannelManageTemplate" {c_ChannelManageTemplate}/></li>
                    </ul>
                </div>
            </div>
        </div>
        ]]>
    </item>
</icms>
</div>