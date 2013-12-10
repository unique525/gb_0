<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>{systemname}</title>
        <link type="text/css" href="/system_template/common/images/common.css" rel="stylesheet" />
        <link id="css_font" type="text/css" href="/system_template/{templatename}/images/font14.css" rel="stylesheet" />
        <link type="text/css" href="/system_template/{templatename}/images/common.css" rel="stylesheet" />
        <link type="text/css" href="/system_template/{templatename}/images/jqueryui/jquery-ui.min.css" rel="stylesheet" />
        <link type="text/css" href="/system_template/{templatename}/images/manage/default.css" rel="stylesheet" />
        <link type="text/css" href="/system_template/{templatename}/images/ztree/ztreestyle.css" rel="stylesheet" />
        <script type="text/javascript" src="{rootpath}/system_js/manage/define_const.js"></script>
        <script type="text/javascript" src="{rootpath}/system_js/jquery-1.9.1.min.js"></script>
        <script type="text/javascript" src="{rootpath}/system_js/common.js"></script>
        <script type="text/javascript" src="{rootpath}/system_js/jquery.cookie.js"></script>
        <script type="text/javascript" src="{rootpath}/system_js/jqueryui/jquery-ui.min.js"></script>
        <script type="text/javascript" src="{rootpath}/system_js/manage/splitter.js"></script>
        <script type="text/javascript" src="{rootpath}/system_js/ztree/jquery.ztree.core-3.0.min.js"></script>
        <script type="text/javascript" src="{rootpath}/system_js/manage/load_tabs.js"></script>
        <script type="text/javascript" src="{rootpath}/system_js/manage/channel.js"></script>
        <script type="text/javascript" src="{rootpath}/system_js/manage/default.js"></script>
        <script type="text/javascript">
            var G_TemplateName = "{templatename}";
        </script>
    </head>
    <body>
        <div id="top">
            <div class="topleft"><img class="systemimage" src="/system_template/{templatename}/images/manage/top4.jpg" alt="" /><img class="systemimage" src="/system_template/{templatename}/images/manage/top5.jpg" alt="" /></div>
            <div class="topright">字体：<span id="setfont14">大</span> <span id="setfont12">小</span> | 欢迎您：<a title="您的IP：{clientip}" href="#">{adminusername}</a> <span id="span_modpass" style="cursor:pointer">[修改密码]</span> <a href="{rootpath}/default.php?mod=manage&a=logout">[退出]</a></div>
            <div class="speall"></div>
        </div>
        <div id="progress">
        </div>
        <div id="maincontent">
            <div id="leftpane">
                <div id="divselectsite" class="divselectsite_normal">
                    <cscms id="select_site" type="list">
                        <header>
                            <![CDATA[
                            <div id="divdefaultsite" title="{f_siteurl}" idvalue="{f_siteid}" style="display:none;height:0;padding:0;width:0;margin:0;">{f_sitename}</div>
                            <div class="divselectsite_item" idvalue="{f_siteid}" title="{f_siteurl}">{f_sitename}</div>
                            ]]>
                        </header>
                        <item>
                            <![CDATA[
                            <div class="divselectsite_item" idvalue="{f_siteid}" title="{f_siteurl}">{f_sitename}</div>
                            ]]>
                        </item>
                        <footer>
                            <![CDATA[
                            <div class="divselectsite_item" idvalue="{f_siteid}" title="{f_siteurl}">{f_sitename}</div>                            
                            <div class="sitecount" style="display:none;height:0;padding:0;width:0;margin:0;" idvalue="{c_allcount}"></div>
                            ]]>
                        </footer>
                    </cscms>
                </div>
                <div class="aheader" id="accord1">
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
