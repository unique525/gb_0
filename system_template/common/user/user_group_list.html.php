<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}
    <style type="text/css">

    </style>
    <script type="text/javascript">
        function ToState(state){
            var result;
            switch(state){
                case 0:
                    result = '<span>启用</span>';
                    break;
                case 40:
                    result = '<span style="color:red">停用</span>';
                    break;
                default:
                    result = '<span>启用</span>';
                    break;
            }
            return result;
        }

        function ChangeState(idvalue,state){
            $.ajax({
                url:"/default.php?secu=manage&mod=user_group&m=modify_state",
                data:{state:state,user_group_id:idvalue},
                dataType:"jsonp",
                jsonp:"JsonpCallBack",
                success:function(data){
                    if(data["result"] == 0){
                        alert("修改失败，请联系管理员");
                    }else{
                        var state_div = $("#State_"+idvalue);
                        state_div.html(ToState(state));
                    }
                }
            });
        }
        $(document).ready(function(){
            $(".div_start").click(function(){
                var idvalue = $(this).attr("idvalue");
                var state = 0;
                var result = ChangeState(idvalue,state);
                if(result == 1){

                }
            });

            $(".div_stop").click(function(){
                var idvalue = $(this).attr("idvalue");
                var state = 40;
                var result = ChangeState(idvalue,state);
            });
        });
    </script>
</head>
<body>
    <div class="div_list">
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td  id="td_main_btn" style="width:80px;">
                    <div class="btn2" id="add">新增分组</div>
                </td>
                <td></td>
            </tr>
        </table>
        <table class="grid" width="100%" cellpadding="0" cellspacing="0">
            <tr  class="grid_title2">
                <td style="width:50px;text-align: center">ID</td>
                <td style="width:50px;text-align: center">编辑</td>
                <td style="width:100px;text-align: center">会员组名称</td>
                <td style="width:200px;text-align: center">所属站点</td>
                <td style="width:80px;text-align: center">状态</td>
                <td  style="width:50px;text-align: center">启用</td>
                <td  style="width:50px;text-align: center">停用</td>
            </tr>
        </table>
        <ul id="type_list">
            <icms id="user_group_list" type="list">
                <item>
                    <![CDATA[
                    <li>
                        <table width="100%" cellpadding="0" cellspacing="0">
                            <tr class="grid_item2">
                                <td class="spe_line2" style="width:50px;text-align: center">{f_UserGroupId}</td>
                                <td class="spe_line2" style="width:50px;text-align: center">
                                    <a href="/default.php?secu=manage&mod=user_group&m=modify&user_group_id={f_UserGroupId}&site_id={f_SiteId}">
                                        <img src="/system_template/{template_name}/images/manage/edit.gif" style="cursor:pointer" class="modify" idvalue="{f_UserGroupId}"/>
                                    </a>
                                </td>
                                <td class="spe_line2" style="width:100px;text-align: center">
                                    <div class="normal_operation_{f_UserGroupId}">
                                        {f_UserGroupName}
                                    </div>
                                </td>
                                <td class="spe_line2" style="width:200px;text-align: center">

                                </td>
                                <td class="spe_line2" style="width:80px;text-align: center" id="State_{f_UserGroupId}">
                                    <script>document.write(ToState({f_State}));</script>
                                </td>
                                <td class="spe_line2" style="width:50px;text-align: center">
                                    <div class="div_start" idvalue="{f_UserGroupId}"><img src="/system_template/{template_name}/images/manage/start.jpg" style="cursor:pointer"/></div>
                                </td>
                                <td class="spe_line2" style="width:50px;text-align: center">
                                    <div class="div_stop" idvalue="{f_UserGroupId}"><img src="/system_template/{template_name}/images/manage/stop.jpg" style="cursor:pointer"/></div>
                                </td>
                            </tr>
                        </table>
                    </li>
                    ]]>
                </item>
            </icms>
            {pagerButton}
    </div>
</body>
</html>