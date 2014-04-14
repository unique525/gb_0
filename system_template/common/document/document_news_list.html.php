<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link id="css_font" type="text/css" href="/system_template/{templatename}/images/font14.css" rel="stylesheet" />
        <link type="text/css" href="/system_template/{templatename}/images/common.css" rel="stylesheet" />
        <link type="text/css" href="/system_template/{templatename}/images/jqueryui/jquery-ui.min.css" rel="stylesheet" />
        <link type="text/css" href="/system_template/{templatename}/images/manage/default.css" rel="stylesheet" />
        <script type="text/javascript" src="/system_js/jquery-1.9.1.min.js"></script>
        <script type="text/javascript" src="/system_js/common.js"></script>
        <script type="text/javascript" src="/system_js/jquery.cookie.js"></script>
        <script type="text/javascript" src="/system_js/jquery_ui/jquery-ui.min.js"></script>
        <script type="text/javascript" src="/system_js/manage/document_news/document_news.js"></script>
    </head>
    <body>
        <div id="rightbtns">
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td id="td_main_btn">
                        <input id="btnadddoc" class="btn1" value="新建文档" title="在本频道新建资讯类的文档" type="button" />
                        <input id="btnmovedoc" class="btn4" value="移动" title="移动本频道文档至其它频道，请先在下面文档中勾选需要移动的文档" type="button" />
                        <input id="btncopydoc" class="btn5" value="复制" title="复制本频道文档至其它频道，请先在下面文档中勾选需要复制的文档" type="button" />
                    </td>
                    <td style="text-align: right; margin-right: 8px;">
                        <div id="searchbox">
                            <select name="searchtype_box" id="searchtype_box" style="display: none">
                                <option value="default">基本搜索</option>
                                <option value="source">来源搜索</option>
                            </select>
                            <input type="text" id="searchkey" name="searchkey" class="inputbox" />
                            <input id="searchbtn" class="btn3" value="查 询" type="button" />
                            <span id="searchtype" style="display: none"></span>
                            <input id="viewselfbtn" class="btn6" value="只看本人" title="只查看当前登录的管理员的文档" type="button" />
                            <input id="viewallbtn" class="btn6" value="查看全部" title="查看全部的文档" type="button" />
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div id="doclist">
            <table class="docgrid" cellpadding="0" cellspacing="0">
                <tr class="gridtitle">
                    <td style="width: 30px; text-align: center; cursor: pointer;" id="selectall">全</td>
                    <td style="width: 40px; text-align: center;">编辑</td>
                    <td style="width: 40px; text-align: right;">状态</td>
                    <td style="width: 40px;"></td>
                    <td>标题</td>
                    <td style="width: 20px;"></td>
                    <td style="width: 20px;"></td>
                    <td style="width: 36px;"></td>
                    <td style="width: 50px; text-align: center;">排序</td>
                    <td style="width: 50px; text-align: center;">推荐</td>
                    <td style="width: 50px; text-align: center;">点击</td>
                    <td style="width: 180px;">创建时间</td>
                    <td style="width: 100px;">发稿人</td>
                    <td style="width: 40px;"></td>
                </tr>
            </table>
            <ul id="sortgrid" style="list-style: none;">
                <cscms id="documentnewslist" type="list">
                    <item>
                        <![CDATA[
                        <li id="docnewssort_{f_documentnewsid}">
                            <table class="docgrid" cellpadding="0" cellspacing="0">
                                <tr class="griditem">
                                    <td class="speline2" style="width:30px;text-align:center;"><input class="docinput" type="checkbox" name="docinput" value="{f_documentnewsid}" /></td>
                                    <td class="speline2" style="width:40px;text-align:center;"><img class="edit_doc systemimage" src="/system_template/{templatename}/images/manage/edit.gif" idvalue="{f_documentnewsid}" alt="编辑" /></td>
                                    <td class="speline2" style="width:40px;text-align:right;"><span id="spanstate_{f_documentnewsid}">{f_state}</span></td>
                                    <td class="speline2" style="width:40px;text-align:left;">
                                        <img class="imgchangestate systemimage" style="cursor: pointer" src="/system_template/{templatename}/images/manage/changestate.gif" idvalue="{f_documentnewsid}" title="改变文档状态" alt="改变状态" />
                                        <div class="docnewsstatebox" id="divstate_{f_documentnewsid}" idvalue="{f_documentnewsid}" title="">
                                            <div style="float:right;"><img class="span_closebox systemimage" idvalue="{f_documentnewsid}" title="关闭" src="/system_template/{templatename}/images/manage/close3.gif" /></div>
                                            <div style="clear:both;">
                                                <div style="{CanCreate}" class="docnewssetstate" statevalue="0" idvalue="{f_documentnewsid}">新稿</div>
                                                <div style="{CanModify}" class="docnewssetstate" statevalue="1" idvalue="{f_documentnewsid}">已编</div>
                                                <div style="{CanRework}" class="docnewssetstate" statevalue="2" idvalue="{f_documentnewsid}">返工</div>
                                                <div style="{CanAudit1}" class="docnewssetstate" statevalue="11" idvalue="{f_documentnewsid}">一审</div>
                                                <div style="{CanAudit2}" class="docnewssetstate" statevalue="12" idvalue="{f_documentnewsid}">二审</div>
                                                <div style="{CanAudit3}" class="docnewssetstate" statevalue="13" idvalue="{f_documentnewsid}">三审</div>
                                                <div style="{CanAudit4}" class="docnewssetstate" statevalue="14" idvalue="{f_documentnewsid}">终审</div>
                                                <div style="{CanRefused}" class="docnewssetstate" statevalue="20" idvalue="{f_documentnewsid}">已否</div>
                                                <div class="spe"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="speline2"><a target="_blank" href="{siteurl}/h/{f_documentchannelid}/{f_year}{f_month}{f_day}/{f_documentnewsid}.html"><span style="color:{f_documentnewstitlecolor};font-weight:{f_documentnewstitlebold};">{f_documentnewstitle}</span></a></td>
                                    <td class="speline2" style="width:20px;"><img class="preview_doc systemimage" style="cursor: pointer" src="/system_template/{templatename}/images/manage/docpreview.gif" idvalue="{f_documentnewsid}" alt="预览" title="预览文档" /></td>
                                    <td class="speline2" style="width:20px;"><img class="imgpublish systemimage" style="cursor: pointer" src="/system_template/{templatename}/images/manage/publish.gif" idvalue="{f_documentnewsid}" title="发布文档" alt="发布" /></td>
                                    <td class="speline2" style="width:36px;"><img class="imgup systemimage" style="cursor: pointer" src="/system_template/{templatename}/images/manage/arr_up.gif" idvalue="{f_documentnewsid}" title="向上移动" alt="向上" /><img class="imgdown systemimage" style="cursor: pointer" src="/system_template/{templatename}/images/manage/arr_down.gif" idvalue="{f_documentnewsid}" title="向下移动" alt="向下" /></td>
                                    <td class="speline2" style="width:50px;text-align:center;" title="文档的排序数字，越大越靠前">{f_sort}</td>
                                    <td class="speline2" style="width:50px;text-align:center;" title="文档的推荐级别，用在特定的模板中">{f_reclevel}</td>
                                    <td class="speline2" style="width:50px;text-align:center;">{f_hit}</td>
                                    <td class="speline2" style="width:180px;" title="文档创建时间">{f_createdate}</td>
                                    <td class="speline2" style="width:100px;" title="发稿人：{f_adminusername}">{f_adminusername}</td>
                                    <td class="speline2" style="width:40px;"><img class="pic_manage systemimage" style="cursor: pointer" src="/system_template/{templatename}/images/manage/pic.gif" idvalue="{f_documentnewsid}" alt="图片管理" title="文档中上传的图片管理" /> <img class="comment_manage systemimage" style="cursor: pointer" src="/system_template/{templatename}/images/manage/comment.gif" idvalue="{f_documentnewsid}" alt="评论管理" title="文档的评论管理" /></td>
                                </tr>
                            </table>
                        </li>
                        ]]>
                    </item>
                </cscms>
            </ul>
            <div>{pagerbutton}</div>
        </div>
<div id="dialog_docchannelpub" title="发布频道" style="display:none;">
            <div id="pubtable">
                <table>
                    <tr>
                        <td><img src="/system_images/manage/spinner2.gif" /></td>
                        <td>正在发布，请稍候！</td>
                    </tr></table>
            </div>
        </div>
        <div id="dialog_docnewsdelete" title="撤掉文档" style="display:none;">
            <div id="deletetable">
                <table>
                    <tr>
                        <td><img src="/system_images/manage/spinner2.gif" /></td>
                        <td>正在撤掉文档，请稍候！</td>
                    </tr></table>
            </div>
        </div>
    </body>
</html>
