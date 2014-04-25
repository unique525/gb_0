<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>{system_name}</title>
        <link type="text/css" href="/system_template/common/images/common.css" rel="stylesheet" />
        <link id="css_font" type="text/css" href="/system_template/{template_name}/images/font14.css" rel="stylesheet" />
        <link type="text/css" href="/system_template/{template_name}/images/common.css" rel="stylesheet" />
        <link type="text/css" href="/system_template/{template_name}/images/jquery_ui/jquery-ui.min.css" rel="stylesheet" />
        <link type="text/css" href="/system_template/{template_name}/images/manage/default.css" rel="stylesheet" />
        <link type="text/css" href="/system_template/{template_name}/images/ztree/ztreestyle.css" rel="stylesheet" />
        <script type="text/javascript" src="/system_js/manage/define_const.js"></script>
        <script type="text/javascript">
            window.G_TemplateName = "{template_name}";
        </script>
        <script type="text/javascript" src="/system_js/jquery-1.9.1.min.js"></script>
        <script type="text/javascript" src="/system_js/common.js"></script>
        <script type="text/javascript" src="/system_js/jquery.cookie.js"></script>
        <script type="text/javascript" src="/system_js/jquery_ui/jquery-ui.min.js"></script>
        <script type="text/javascript" src="/system_js/manage/splitter.js"></script>
        <script type="text/javascript" src="/system_js/ztree/jquery.ztree.core-3.0.min.js"></script>
        <script type="text/javascript" src="/system_js/manage/tabs.js"></script>
        <script type="text/javascript" src="/system_js/manage/channel/site_and_channel.js"></script>
        <script type="text/javascript" src="/system_js/manage/user/user_manage.js"></script>
        <script type="text/javascript" src="/system_js/manage/default.js"></script>
    </head>
    <body>
        <div id="div_top">
            <div class="top_left"><img class="system_image" src="/system_template/{template_name}/images/manage/top4.jpg" alt="" /><img class="system_image" src="/system_template/{template_name}/images/manage/top5.jpg" alt="" /></div>
            <div class="top_right">字体：<span id="set_font14">大</span> <span id="set_font12">小</span> | 欢迎您：<a title="您的IP：{client_ip_address}" href="#">{manage_user_name}</a> <span id="btn_modify_admin_user_pass" style="cursor:pointer">[修改密码]</span> <a href="{relative_path}/default.php?mod=manage&a=logout">[退出]</a></div>
            <div class="spe_all"></div>
        </div>
        <div id="div_progress">
        </div>
        <div id="div_main_content">
            <div id="div_left_panel">
                <div class="header" id="div_left_accordion">
                    <icms_list id="manage_menu_of_column" type="list">
                        <item>
                            <![CDATA[
                            <h3 class="ha"><div class="divAccordItem" style="background:url('/system_template/{template_name}/images/manage/{f_ManageMenuOfColumnIcon}') no-repeat left center;text-indent:30px;">{f_ManageMenuOfColumnName}</div></h3>
                            <div style="overflow:hidden;">{f_ManageMenuOfColumnContent}</div>
                            ]]>
                        </item>
                        <footer>
                            <![CDATA[
                            <h3 class="ha"><div class="divAccordItem" style="background:url('/system_template/{template_name}/images/manage/{f_ManageMenuOfColumnIcon}') no-repeat left center;text-indent:30px;">{f_ManageMenuOfColumnName}</div></h3>
                            <div>{f_ManageMenuOfColumnContent}</div>
                            ]]>
                        </footer>
                    </icms_list>
                    <div id="right_menu">
                        <ul>
                            <li class="cm_pub_channel"><span id="btn_right_pub_channel">发布频道</span></li>
                            <li class="cm_edit_channel"><span id="btn_right_modify_channel">编辑频道</span></li>
                            <li class="cm_add_channel"><span id="btn_right_create_channel">新增子频道</span></li>
                            <li class="cm_bin_channel"><span id="btn_right_view_channel_template">频道模板</span></li>
                            <li class="cm_bin_channel"><span id="btn_right_remove_to_bin_channel">删除频道</span></li>
                            <li class="cm_bin_channel"><span id="btn_right_relative_channel">频道联动</span></li>
                            <li class="cm_pro_channel"><span id="btn_right_view_channel_property">频道属性</span></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div id="div_right_pane">
                <div id="div_right_nav"><div class="site_name"></div><div class="channel_name"></div><div class="select_template">
                        {select_template}
                    </div><div class="spe_all"></div></div>
                <div id="tabs">
                    <ul>
                        <li><a id="tabs_title" href="#tabs-1">欢迎页面</a></li>
                    </ul>
                    <div id="tabs-1">
                        <div style="padding:20px;">请从右边的导航树上选择您想要操作的频道（栏目）</div>
                    </div>
                </div>


            </div>
            <div class="spe"></div>
        </div> 

        <script type="text/javascript">
            var leftNavCount = parseInt('{manage_menu_of_column_count}') - 1;
            var leftTreeHeight = $(window).height() - 98 - 29 * leftNavCount;
            $("#div_manage_menu_of_column").css("height", leftTreeHeight);
            $("#div_user_manage").css("height", leftTreeHeight + 26);
            $("#div_task_manage").css("height", leftTreeHeight + 26);
            $("#div_forum_manage").css("height", leftTreeHeight + 26);
        </script>
    </body>
</html>
