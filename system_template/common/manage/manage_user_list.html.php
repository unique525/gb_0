<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}


    <script type="text/javascript">
        $("document").ready(function () {

            $("#btn_create").click(function (event) {
                event.preventDefault();
                parent.G_TabUrl = '/default.php?secu=manage&mod=manage_user&m=create';
                parent.G_TabTitle =  '新增管理员';
                parent.addTab();
            });

            $(".btn_modify").click(function (event) {
                event.preventDefault();
                var manageUserId=$(this).attr("idvalue");
                var manageUserName=$(this).attr("title");
                parent.G_TabUrl = '/default.php?secu=manage&mod=manage_user&m=modify' + '&manage_user_id=' + manageUserId + '';
                parent.G_TabTitle = manageUserName + '-编辑';
                parent.addTab();
            });

            //格式化状态
            $(".span_state").each(function(){
                $(this).html(formatState($(this).text()));
            });

            $(".span_open_public_login").each(function(){
                $(this).html(formatOpenPublicLogin($(this).text()));
            });

            $(".span_sms_verify_login").each(function(){
                $(this).html(formatSmsVerifyLogin($(this).text()));
            });

            $(".span_otp_verify_login").each(function(){
                $(this).html(formatOtpVerifyLogin($(this).text()));
            });

            $(".span_end_date").each(function(){
                $(this).html(formatEndDate($(this).text()));
            });

            //开启
            $(".img_open").click(function(){
                var manageUserId = parseInt($(this).attr("idvalue"));
                var state = 0; //开启状态
                modifyState(manageUserId, state);
            });
            //停用
            $(".img_close").click(function(){
                var manageUserId = parseInt($(this).attr("idvalue"));
                var state = 100; //开启状态
                modifyState(manageUserId, state);
            });




            var btnSearch = $("#btn_search");
            btnSearch.css("cursor", "pointer");
            btnSearch.click(function(event) {
                event.preventDefault();
                var pageIndex = parseInt(Request["p"]);
                if (pageIndex == undefined || isNaN(pageIndex) || pageIndex <= 0) {
                    pageIndex = 1;
                }
                var searchKey = encodeURIComponent($("#search_key").val());
                if(searchKey.length<=0){
                    alert("请输入查询关键字");
                }else{
                    window.location.href = '/default.php?secu=manage' +
                        '&mod=manage_user&m=list&search_key='+searchKey+'&tab_index='+ parent.G_TabIndex +'&p=' + pageIndex;
                }


            });

        });



        function modifyState(manageUserId,state){
            if(manageUserId>0){
                $.ajax({
                    type: "get",
                    url: "/default.php?secu=manage&mod=manage_user&m=async_modify_state",
                    data: {
                        manage_user_id: manageUserId,
                        state:state
                    },
                    dataType: "jsonp",
                    jsonp: "jsonpcallback",
                    success: function(data) {
                        $("#span_state_"+manageUserId).html(formatState(state));
                    }
                });
            }
        }

        /**
         * 格式化状态值
         * @return {string}
         */
        function formatState(state){
            state = state.toString();
            switch (state){
                case "0":
                    return "启用";
                    break;
                case "100":
                    return "<"+"span style='color:#990000'>停用<"+"/span>";
                    break;
                default :
                    return "未知";
                    break;
            }
        }


        /**
         * 格式化公网登录
         * @return {string}
         */
        function formatOpenPublicLogin(value){
            value = value.toString();
            switch (value){
                case "1":
                    return "启用";
                    break;
                case "0":
                    return "<"+"span style='color:#990000'>禁用<"+"/span>";
                    break;
                default :
                    return "未知";
                    break;
            }
        }

        /**
         * 格式化短信认证
         * @return {string}
         */
        function formatSmsVerifyLogin(value){
            value = value.toString();
            switch (value){
                case "1":
                    return "启用";
                    break;
                case "0":
                    return "<"+"span style='color:#990000'>禁用<"+"/span>";
                    break;
                default :
                    return "未知";
                    break;
            }
        }

        /**
         * 格式化口令牌认证
         * @return {string}
         */
        function formatOtpVerifyLogin(value){
            value = value.toString();
            switch (value){
                case "1":
                    return "启用";
                    break;
                case "0":
                    return "<"+"span style='color:#990000'>禁用<"+"/span>";
                    break;
                default :
                    return "未知";
                    break;
            }
        }


        /**
         * 格式化口令牌认证
         * @return {string}
         */
        function formatEndDate(value){
            value = value.toString();

            var valueOfDate = value.replace(/-/g,"/");
            var endDateSpan = Date.parse(new Date(valueOfDate));

            var currentDateSpan = Date.parse(new Date());

            if(currentDateSpan>endDateSpan){
                return "<"+"span style='color:#990000'>"+value+"<"+"/span>";
            }else{
                return value;
            }
        }

    </script>
</head>
<body>
<div class="div_list">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td id="td_main_btn">
                <input id="btn_create" class="btn2" value="新增管理员" title="新增管理员" type="button"/>
            </td>
            <td style="text-align: right; margin-right: 8px;">
                <div id="search_box">
                    <label for="search_type_box"></label>
                    <select name="search_type_box" id="search_type_box" style="display: none">
                        <option value="default">基本搜索</option>
                    </select>
                    <label for="search_key"></label><input type="text" id="search_key" name="search_key" class="input_box"/>
                    <input id="btn_search" class="btn2" value="查 询" type="button"/>
                    <span id="search_type" style="display: none"></span>
                </div>
            </td>
        </tr>
    </table>
    <table class="grid" width="100%" cellpadding="0" cellspacing="0">
        <tr class="grid_title">
            <td style="width: 30px; text-align: center; cursor: pointer;" id="btn_select_all">全</td>
            <td style="width: 40px; text-align: center;">编辑</td>
            <td>管理员帐号</td>
            <td style="width: 70px; text-align: center;">排序号</td>
            <td style="width: 180px;text-align:center;">管理分组</td>
            <td style="width: 100px;text-align:center;">绑定会员</td>
            <td style="width: 70px;text-align:center;">公网登录</td>
            <td style="width: 70px;text-align:center;">短信认证</td>
            <td style="width: 70px;text-align:center;">口令牌认证</td>
            <td style="width: 160px;text-align:center;">创建时间</td>
            <td style="width: 160px;text-align:center;">到期时间</td>
            <td style="width: 40px; text-align: center;">状态</td>
            <td style="width: 80px;text-align:center;">启用&nbsp;&nbsp;停用</td>
            <td style="width: 100px;text-align:center;">相关管理</td>
        </tr>
    </table>
    <ul id="sort_grid">
        <icms id="manage_user_list" type="list">
            <item>
                <![CDATA[
                <li id="sort_{f_ManageUserId}">
                    <table width="100%" cellpadding="0" cellspacing="0">
                        <tr class="grid_item">
                            <td class="spe_line2" style="width:30px;text-align:center;"><label>
                                    <input class="input_select" type="checkbox" name="input_select"
                                           value="{f_ManageUserId}"/>
                                </label></td>
                            <td class="spe_line2" style="width:40px;text-align:center;"><img class="btn_modify" title="{f_ManageUserName}" style="cursor:pointer;" src="/system_template/{template_name}/images/manage/edit.gif" idvalue="{f_ManageUserId}" alt="编辑"/></td>
                            <td class="spe_line2">{f_ManageUserName}</td>
                            <td class="spe_line2" style="width:70px;text-align:center;" title="文档的排序数字，越大越靠前">{f_Sort}</td>
                            <td class="spe_line2" style="width:180px;text-align:center;" title="管理分组">{f_ManageUserGroupName}</td>
                            <td class="spe_line2" style="width:100px;text-align:center;" title="绑定会员">{f_UserName}</td>
                            <td class="spe_line2" style="width:70px;text-align:center;" title="公网登录"><span class="span_open_public_login" idvalue="{f_OpenPublicLogin}">{f_OpenPublicLogin}</span></td>
                            <td class="spe_line2" style="width:70px;text-align:center;" title="短信认证"><span class="span_sms_verify_login" idvalue="{f_SmsVerifyLogin}">{f_SmsVerifyLogin}</span></td>
                            <td class="spe_line2" style="width:70px;text-align:center;" title="口令牌认证"><span class="span_otp_verify_login" idvalue="{f_OtpVerifyLogin}">{f_OtpVerifyLogin}</span></td>
                            <td class="spe_line2" style="width:160px;text-align:center;" title="创建时间">{f_CreateDate}</td>
                            <td class="spe_line2" style="width:160px;text-align:center;" title="到期时间"><span class="span_end_date" idvalue="{f_EndDate}">{f_EndDate}</span></td>
                            <td class="spe_line2" style="width:40px;text-align:center;"><span id="span_state_{f_ManageUserId}" class="span_state" idvalue="{f_ManageUserId}">{f_State}</span></td>
                            <td class="spe_line2" style="width:80px;text-align:center;">
                                <img class="img_open" idvalue="{f_ManageUserId}" src="/system_template/{template_name}/images/manage/start.jpg" style="cursor:pointer"/>&nbsp;&nbsp;&nbsp;&nbsp;
                                <img class="img_close" idvalue="{f_ManageUserId}" src="/system_template/{template_name}/images/manage/stop.jpg" style="cursor:pointer"/>
                            </td>
                            <td class="spe_line2" style="width:100px;text-align:center;">
                                <a href="/default.php?secu=manage&mod=manage_user_authority&m=set_by_manage_user&manage_user_id={f_ManageUserId}">设置权限</a>
                            </td>
                        </tr>
                    </table>
                </li>
                ]]>
            </item>
        </icms>
    </ul>
    <div>{pager_button}</div>
</div>
</body>
</html>
