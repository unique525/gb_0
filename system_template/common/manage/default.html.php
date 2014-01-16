<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>{system_name}</title>
        <link type="text/css" href="/system_template/common/images/common.css" rel="stylesheet" />
        <link id="css_font" type="text/css" href="/system_template/{template_name}/images/font14.css" rel="stylesheet" />
        <link type="text/css" href="/system_template/{template_name}/images/common.css" rel="stylesheet" />
        <link type="text/css" href="/system_template/{template_name}/images/jqueryui/jquery-ui.min.css" rel="stylesheet" />
        <link type="text/css" href="/system_template/{template_name}/images/manage/default.css" rel="stylesheet" />
        <link type="text/css" href="/system_template/{template_name}/images/ztree/ztreestyle.css" rel="stylesheet" />
        <script type="text/javascript" src="{root_path}/system_js/manage/define_const.js"></script>
        <script type="text/javascript" src="{root_path}/system_js/jquery-1.9.1.min.js"></script>
        <script type="text/javascript" src="{root_path}/system_js/common.js"></script>
        <script type="text/javascript" src="{root_path}/system_js/jquery.cookie.js"></script>
        <script type="text/javascript" src="{root_path}/system_js/jqueryui/jquery-ui.min.js"></script>
        <script type="text/javascript" src="{root_path}/system_js/manage/splitter.js"></script>
        <script type="text/javascript" src="{root_path}/system_js/ztree/jquery.ztree.core-3.0.min.js"></script>
        <script type="text/javascript" src="{root_path}/system_js/manage/load_tabs.js"></script>
        <script type="text/javascript" src="{root_path}/system_js/manage/channel.js"></script>
        <script type="text/javascript" src="{root_path}/system_js/manage/default.js"></script>
        <script type="text/javascript">
            var G_TemplateName = "{template_name}";
        </script>
    </head>
    <body>
        <div id="div_top">
            <div class="top_left"><img class="system_image" src="/system_template/{template_name}/images/manage/top4.jpg" alt="" /><img class="system_image" src="/system_template/{template_name}/images/manage/top5.jpg" alt="" /></div>
            <div class="top_right">字体：<span id="set_font14">大</span> <span id="set_font12">小</span> | 欢迎您：<a title="您的IP：{client_ip_address}" href="#">{admin_username}</a> <span id="btn_modify_admin_user_pass" style="cursor:pointer">[修改密码]</span> <a href="{root_path}/default.php?mod=manage&a=logout">[退出]</a></div>
            <div class="spe_all"></div>
        </div>
        <div id="div_progress">
        </div>
        <div id="div_main_content">
            <div id="div_left_panel">
                <div id="div_select_site" class="select_site_normal">
                    <cscms id="select_site" type="list">
                        <header>
                            <![CDATA[
                            <div id="div_default_site" title="{f_SiteUrl}" idvalue="{f_SiteId}" style="display:none;height:0;padding:0;width:0;margin:0;">{f_SiteName}</div>
                            <div class="select_site_item" idvalue="{f_SiteId}" title="{f_SiteUrl}">{f_SiteName}</div>
                            ]]>
                        </header>
                        <item>
                            <![CDATA[
                            <div class="select_site_item" idvalue="{f_SiteId}" title="{f_SiteUrl}">{f_SiteName}</div>
                            ]]>
                        </item>
                        <footer>
                            <![CDATA[
                            <div class="select_site_item" idvalue="{f_SiteId}" title="{f_SiteUrl}">{f_SiteName}</div>
                            <div class="site_count" style="display:none;height:0;padding:0;width:0;margin:0;" idvalue="{c_all_count}"></div>
                            ]]>
                        </footer>
                    </cscms>
                </div>
                <div class="header" id="div_left_accordion">
                    <cscms id="leftnav" type="list">
                        <item>
                            <![CDATA[
                            <h3 class="ha"><div class="divAccordItem objbg" style="background:url('/system_template/{templatename}/images/manage/{f_adminleftnavicon}') no-repeat left center;text-indent:30px;">{f_adminleftnavname}</div></h3>
                            <div>{f_adminleftnavcontent}</div>
                            ]]>
                        </item>
                        <footer>
                            <![CDATA[
                            <h3 class="ha"><div class="divAccordItem objbg" style="background:url('/system_template/{templatename}/images/manage/{f_adminleftnavicon}') no-repeat left center;text-indent:30px;">{f_adminleftnavname}</div></h3>
                            <div>{f_adminleftnavcontent}</div>
                            ]]>
                        </footer>
                    </cscms>
                    <div id="rMenu">
                        <ul>
                            <li class="cm_pub_quick_channel"><a href="javascript:publish_channel();">发布频道</a></li>
                            <li class="cm_edit_channel"><a href="javascript:edit_channel();">编辑频道</a></li>
                            <li class="cm_add_channel"><a href="javascript:add_channel();">新增子频道</a></li>
                            <li class="cm_bin_channel"><a href="javascript:temp_channel();">频道模板</a></li>
                            <li class="cm_bin_channel"><a href="javascript:bin_channel();">删除频道</a></li>
                            <li class="cm_bin_channel"><a href="javascript:link_channel();">频道联动</a></li>
                            <li class="cm_pro_channel"><a href="javascript:pro_channel();">频道属性</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div id="rightpane">
                <div id="rightnav"><div class="sitename"></div><div class="channelname"></div><div class="selecttemplate">
                        {selecttemplate}
                    </div><div class="speall"></div></div>
                <div id="tabs">
                    <ul>
                        <li><a id="tabs_title" href="#tabs-1">欢迎页面</a></li>
                    </ul>
                    <div id="tabs-1">
                        <div style="padding:20px;">请从右边的导航树上选择您想要操作的频道（栏目）</div>
                        <div id="gridlist" style=" overflow: auto;">
                        </div>
                    </div>
                </div>


            </div>
            <div class="spe"></div>
        </div> 

        <script type="text/javascript">
            var leftNavCount = parseInt('{adminleftnavcount}') - 1;
            var leftTreeHeight = $(window).height() - 98 - 29 * leftNavCount;
            $("#lefttree").css("height", leftTreeHeight);
            $("#forummanage").css("height", leftTreeHeight + 26);
        </script>
    </body>
</html>
