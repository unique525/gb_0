<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	{common_head}


    <script type="text/javascript">

        window.activityId = Request["activity_id"];
        $("document").ready(function() {

            reloadUserState();

            //搜索功能
            $("#btn_search").click(function (event) {
                event.preventDefault();
                var searchKey = $("#search_key").val();
                parent.G_TabUrl = '/default.php?secu=manage&mod=activity_user&m=list' + '&activity_id=' + activityId + '&search_key=' + searchKey;
                parent.G_TabTitle = activityId + '-活动搜索';
                parent.addTab();
            });

            //通过申请
            $(".img_open_activityState").click(function() {
                var activityUserId = $(this).attr("idvalue");
                var currentStates  = $("#span_state_" + activityUserId).text();
                if (currentStates != "批准" ){
                    var userSate = 1;
                    modifyUserState(activityId, activityUserId, userSate);
                }
            });

            //撤销或拒绝申请
            $(".img_close_activityState").click(function() {
                var activityUserId = $(this).attr("idvalue");
                var currentStates  = $("#span_state_" + activityUserId).text();
                if (currentStates != "拒绝" ){
                    var userSate = 2;
                    modifyUserState(activityId, activityUserId, userSate);
                }
            });

            //删除用户
            $(".img_delete_activityState").click(function() {
                var activityUserId = $(this).attr("idvalue");
                var currentStates  = $("#span_state_" + activityUserId).text();
                if (currentStates != "批准" ){
                    deleteUser(activityId, activityUserId);
                }
                else{
                    alert("该用户目前已通过审核,如要删除,请先拒绝该用户");
                }
            });


        });

        //设置用户状态
        function reloadUserState() {
            $(".span_state").each(function() {
                var state = $(this).text();
                if(!isNaN(state)) {
                    $(this).html(_setUserState(state));
                }

            });
        }

        //格式化状态值
        function _setUserState(state) {
            state = state.toString();
            switch(state) {
                case "0":
                    return "申请";
                    break;
                case "1":
                    return "<" + "span style='color:#00CC66'>批准<" + "/span>";
                    break;
                case "2":
                    return "<" + "span style='color:#990000'>拒绝<" + "/span>";
                    break;
                case "-1":
                    return "系统错误";
                    break;
                default :
                    return "未知";
                    break;
            }
        }

        function modifyUserState(activityId, activityUserId, userState) {
            if(activityUserId > 0) {
                $.ajax({
                    type: "get",
                    url: "/default.php?secu=manage&mod=activity_user&m=modify_state",
                    data: {
                             activity_id : activityId,
                        activity_user_id : activityUserId,
                                   state : userState
                    },
                    dataType: "jsonp",
                    jsonp: "jsonpcallback",
                    success: function(data) {
                        if (data["result"] == 1){
                            $("#span_state_" + activityUserId).text(userState.toString());
                        }else{
                            $("#span_state_" + activityUserId).text("-1");
                        }
                        reloadUserState();

                    }
                });
            }
        }

        function deleteUser(activityId, activityUserId) {

            if(activityUserId > 0) {
                $.ajax({
                    type: "get",
                    url: "/default.php?secu=manage&mod=activity_user&m=delete_user",
                    data: {
                        activity_id : activityId,
                        activity_user_id : activityUserId
                    },
                    dataType: "jsonp",
                    jsonp: "jsonpcallback",
                    success: function(data) {
                        if (data["result"] == 1){
                            deleteRowWhereUserDeleted(activityUserId);
                        }else{
                            $("#span_state_" + activityUserId).text("-1");
                        }
                        reloadUserState();

                    }
                });
            }
        }

        function deleteRowWhereUserDeleted(activityUserId){
            var row = "#sort_" + activityUserId;
            $(row).css("display","none");
        }


    </script>
</head>
<body>
<div class="div_list">
	<table width="100%" cellpadding="0" cellspacing="0">
		<tr>
			<td style="text-align: right; margin-right: 8px;">
				<div id="search_box">
					<label for="search_key"></label><input type="text" id="search_key" name="search_key"
					                                       class="input_box"/>
					<input id="btn_search" class="btn2" value="查 询" type="button" style="display:{display}"/>
				</div>
			</td>
		</tr>
	</table>
	<table class="grid" width="100%" cellpadding="0" cellspacing="0">
		<tr class="grid_title">
			<td style="width: 80px; text-align: center;">用户活动ID</td>
			<td style="width: 120px; text-align: center;">用户名称</td>
			<td style="text-align: center;">活动名称</td>
			<td style="width: 140px;text-align:center;">报名时间</td>
			<td style="width: 60px; text-align: center;">状态</td>
			<td style="width: 60px;text-align:center;">通过</td>
            <td style="width: 60px;text-align:center;">拒绝</td>
            <td style="width: 60px;text-align:center;">删除</td>
		</tr>
	</table>
	<ul id="sort_grid">
		<icms id="activity_user_list" type="list">
			<item>
				<![CDATA[
				<li id="sort_{f_ActivityUserId}">
					<table width="100%" cellpadding="0" cellspacing="0">
						<tr class="grid_item">

                            <!-----用户ID-------->
							<td class="spe_line2" style="width:80px;text-align:center;">{f_ActivityUserId}</td>

                            <!-----用户名-------->
							<td class="spe_line2" style="width:120px;text-align:center;">{f_UserName}</td>

                            <!-----活动名-------->
							<td class="spe_line2" style="text-align:center;">{f_ActivityTitle}</td>

                            <!-----报名时间-------->
							<td class="spe_line2" style="width:140px;text-align:center;" title="站点创建时间">{f_CreateDate}</td>

                            <!-----状态-------->
							<td class="spe_line2" style="width:60px;text-align:center;">
                                <span id="span_state_{f_ActivityUserId}"
                                      class="span_state"
                                      idvalue="{f_ActivityUserId}">{f_State}</span>
                            </td>

                            <!-----通过按钮-------->
							<td class="spe_line2" style="width:60px;text-align:center;">
								<img class="img_open_activityState"
                                     src="/system_template/{template_name}/images/manage/start.jpg"
                                     style="cursor:pointer"
                                     idvalue="{f_ActivityUserId}"
                                 />
                            </td>

                            <!-----拒绝按钮-------->
                            <td class="spe_line2" style="width:60px;text-align:center;">
                                <img class="img_close_activityState"
                                     src="/system_template/{template_name}/images/manage/stop.jpg"
                                     style="cursor:pointer"
                                     idvalue="{f_ActivityUserId}"
                                />
                            </td>

                            <!-----删除按钮-------->
                            <td class="spe_line2" style="width:60px;text-align:center;">
                                <img class="img_delete_activityState"
                                     src="/system_template/{template_name}/images/manage/delete.jpg"
                                     style="cursor:pointer"
                                     idvalue="{f_ActivityUserId}"
                                />
                            </td>

						</tr>
					</table>
				</li>
				]]>
			</item>
		</icms>
	</ul>
	<div>{PagerButton}</div>
</div>
</body>
</html>