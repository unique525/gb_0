<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	{common_head}


	<script type="text/javascript">

		activityId = Request["activity_id"];

		$("document").ready(function() {

			reloadUserState();

			//通过申请
			$(".img_open_activityState").each(function() {
				$(this).click(function() {
					var activityUserId = $(this).attr("idvalue");
					var currentStates  = $("#span_state_" + activityUserId).text();
					if (currentStates != "批准" ){
						var userSate = 1;
						modifySiteState(activityId, activityUserId, userSate);
					}
				});
			});

			//撤销或拒绝申请
			$(".img_close_activityState").each(function() {
				$(this).click(function() {
					var activityUserId = $(this).attr("idvalue");
					var currentStates  = $("#span_state_" + activityUserId).text();
					if (currentStates != "拒绝" ){
						var userSate = 2;
						modifySiteState(activityId, activityUserId, userSate);
					}
				});
			});
		});

		//设置用户状态
		function reloadUserState() {
			$(".span_state").each(function() {
				var state = $(this).text();
				if(!isNaN(state)) {
					$(this).html(_getUserState(state));
				}

			});
		}

		/**
		 * reloadUserState()的子方法
		 * 格式化状态值
		 * @return {string}
		 */
		function _getUserState(state) {
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
				default :
					return "未知";
					break;
			}
		}

		function modifySiteState(activityId, activityUserId, userState) {
			if(activityUserId > 0) {
				$.ajax({
					type: "get",
					url: "/default.php?secu=manage&mod=site_tag&m=modify_state",
					data: {
						activity_id: activityId,
						activity_user_id: activityUserId,
						state: userState
					},
					dataType: "jsonp",
					jsonp: "jsonpcallback",
					success: function(data) {
						console.log(data);
						$("#span_state_" + activityUserId).text(userState.toString());
						reloadUserState();

					}
				});
			}
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
			<td style="width: 40px; text-align: center;">ID</td>
			<td style="width: 120px; text-align: center;">用户名称</td>
			<td style="width: 120px; text-align: center;">活动名称</td>
			<td style="width: 180px;text-align:center;">创建时间</td>
			<td style="width: 40px; text-align: center;">状态</td>
			<td style="width: 80px;text-align:center;">通过&nbsp;&nbsp;拒绝</td>
		</tr>
	</table>
	<ul id="sort_grid">
		<icms id="activity_user_list" type="list">
			<item>
				<![CDATA[
				<li id="sort_{f_ActivityUserId}">
					<table width="100%" cellpadding="0" cellspacing="0">
						<tr class="grid_item">
							<td class="spe_line2" style="width:40px;text-align:center;">{f_ActivityUserId}</td>
							<td class="spe_line2" style="width:120px;text-align:center;">{f_UserName}</td>
							<td class="spe_line2" style="width:120px;text-align:center;">{f_ActivityTitle}</td>
							<td class="spe_line2" style="width:180px;text-align:center;" title="站点创建时间">{f_CreateDate}
							</td>
							<td class="spe_line2" style="width:40px;text-align:center;"><span
										id="span_state_{f_ActivityUserId}" class="span_state"
										idvalue="{f_ActivityUserId}">{f_State}</span></td>
							<td class="spe_line2" style="width:80px;text-align:center;">
								<img class="img_open_activityState" idvalue="{f_ActivityUserId}"
								     src="/system_template/{template_name}/images/manage/start.jpg"
								     style="cursor:pointer"/>
								&nbsp;&nbsp;&nbsp;&nbsp;
								<img class="img_close_activityState" idvalue="{f_ActivityUserId}"
								     src="/system_template/{template_name}/images/manage/stop.jpg"
								     style="cursor:pointer"/>
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