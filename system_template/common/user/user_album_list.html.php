<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <META HTTP-EQUIV="pragma" CONTENT="no-cache" />
        <META HTTP-EQUIV="Cache-Control" CONTENT="no-cache, must-revalidate" />
        <META HTTP-EQUIV="expires" CONTENT="Wed, 26 Feb 1997 08:21:57 GMT" />
        <script type="text/javascript" src="{rootpath}/system_js/common.js"></script>
        <script type="text/javascript" src="{rootpath}/system_js/jquery-ui-1.8.2.custom.min.js"></script>
        <script type="text/javascript" src="{rootpath}/system_js/jqueryui/jquery-ui.min.js"></script>
        <script type="text/javascript">
            var advanced = false;
            function album_search(){
                $("#search_form").submit();
            }

            function advanced_search(){
                if(advanced){
                    $("#advanced_search").css("display","none");
                    advanced = false;
                }else{
                    $("#advanced_search").css("display","block");
                    advanced = true;
                }
            }
            $(function(){
                $("#begindate").datepicker({
                    dateFormat: 'yy-mm-dd',
                    numberOfMonths: 3,
                    showButtonPanel: true
                });
                $("#enddate").datepicker({
                    dateFormat: 'yy-mm-dd',
                    numberOfMonths: 3,
                    showButtonPanel: true
                });

                $("#selectall").click(function(){
                    if($("[name='albuminput[]']").attr("checked") == true){
                        $("[name='albuminput[]']").removeAttr("checked");//取消全选
                    }else{
                        $("[name='albuminput[]']").attr("checked",'true');//全选
                    }
                });

                $(".mainpic").mouseover(function(){
                    var id = $(this).attr("title");
                    $("#mainpic_"+id).css("display","block");
                });
                $(".mainpic").mouseout(function(){
                    $(".mainpicdiv").css("display","none");
                });

                $(".usermanage").click(function(event) {
                    var id = $(this).attr('title');
                    event.preventDefault();
                    nowactionurl =  '../user/index.php?a=edit&userid=' + id + '&siteid=' + nowsiteid + '';
                    $('#tabs').tabs('add', '#tabs-'+tab_counter, '编辑会员帐号');
                    tab_counter++;
                });
            });
        </script>
    </head>
    <body>
    <div id="selectorderstate">
        <form id="search_form" action="" method="post">
            类别:
            <select name="albumtag" id="albumtag">
                <option value="">全部</option>
                <cscms id="useralbumtaglist" type="useralbumlist">
                    <item><![CDATA[
                        <option value="{f_useralbumtag_value}">{f_useralbumtag_value}</option>
                        ]]>
                    </item>
                </cscms>
            </select>
            <!--

            <span style="cursor: pointer">管理所有评论</span>
            <span>时间:<input type="text" style="width: 50px" name="year" value=""/>年<input type="text" style="width: 50px" name="month" value=""/>月<input type="text" style="width: 50px" value=""/>日</span>
            -->
            <span>作者:<input type="text" id="author" name="author" value=""/></span>
            <span>作品名:<input type="text" id="albumname" name="albumname" value=""/></span>
            <!--
            <span>分类:
                <input type="checkbox" id="albumalbumtag_people" name="albumtag[]" value="人像"/>&nbsp;&nbsp;人像&nbsp;&nbsp;
                <input type="checkbox" id="albumtag_ecology" name="albumtag[]" value="生态"/>&nbsp;&nbsp;生态&nbsp;&nbsp;
                <input type="checkbox" id="albumtag_documentary" name="albumtag[]" value="纪实"/>&nbsp;&nbsp;纪实&nbsp;&nbsp;
                <input type="checkbox" id="albumtag_landscape" name="albumtag[]" value="风景"/>&nbsp;&nbsp;风景&nbsp;&nbsp;
                <input type="checkbox" id="albumtag_trip" name="albumtag[]" value="小品·旅游"/>&nbsp;&nbsp;小品·旅游&nbsp;&nbsp;
            </span>
            -->

            <div id="advanced_search" style="display: none; background:#f4f4f4;padding:5px; margin:5px 0px;">
                <div style="color:#999999; line-height:30px; font-size:12px;">注：如果只填开始时间就查从所填时间开始到现在的作品,如果只填结束时间就查到所填时间为止的作品</div>
                时间段查询:开始时间
                <input type="text" class="inputbox" id="begindate" name="begindate" value="" style="font-size:14px;width:70px" maxlength="11" readonly="readonly" />
                ——结束时间
                <input type="text" class="inputbox" id="enddate" name="enddate" value="" style="font-size:14px;width:70px" maxlength="11" readonly="readonly" />
                是否首页:<select id="indextop" name="indextop">
                    <option value="">全部</option>
                    <option value="0">否</option>
                    <option value="1">是</option>
                </select>
                是否精华:<select id="isbest" name="isbest">
                    <option value="">全部</option>
                    <option value="0">否</option>
                    <option value="1">是</option>
                </select>
                推荐级别:<select id="reclevel" name="reclevel">
                    <option value="">全部</option>
                    <option value="0">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                </select>
                状态:<select id="state" name="state">
                    <option value="">全部</option>
                    <option value="20">审核通过</option>
                    <option value="0">未审核</option>
                    <option value="40">已否</option>
                    <option value="30">已编辑</option>
                    <option value="70">无图片</option>
                    <option value="80">需要审核</option>
                </select>
                <span>设备:<input type="text" id="equipment" name="equipment" value=""/></span><br/>
                时间段查询:开始时间(如果只填开始时间就查从所填时间开始到现在的作品)
                <input type="text" class="input_box" id="begin_date" name="begin_date" value="" style="font-size:14px;width:70px" maxlength="11" readonly="readonly" />
                —结束时间(如果只填结束时间就查到所填时间为止的作品)
                <input type="text" class="inputbox" id="end_date" name="end_date" value="" style="font-size:14px;width:70px" maxlength="11" readonly="readonly" />
            </div>
            <input type="button" id="album_search" value="查询"/>
            <span onclick="advanced_search();" style="font-weight: bold;cursor: pointer">高级查询</span>
        </form>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <span style="font-weight: bold;cursor: pointer"><a href="../user/index.php?a=album&m=statistics" target="_blank">数据统计</a></span>
        <span id="batch_del" style="font-weight: bold;cursor: pointer">批量删除</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <span id="batch_pass" style="font-weight: bold;cursor: pointer">批量审核</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <span id="batch_no_pass" style="font-weight: bold;cursor: pointer">批量撤销</span>
    </div>
    {pager_button}
    <div style="clear:left"></div>
    <div id="doclist">
        <table class="docgrid" cellpadding="0" cellspacing="0">
            <tr class="gridtitle">
                <td style="width:30px;text-align:center;">
                    <span id="selectall" style="cursor:pointer">全</span>
                </td>
                <td style="width:40px;text-align:center;">编辑</td>
                <td style="text-align:left;width:150px">作品名</td>
                <td style="width:100px;text-align:left;">作者</td>
                <td style="width:50px;text-align:left;">类别</td>
                <td style="width:60px;text-align:center;">状态</td>
                <td style="width:50px;text-align:left;">支持</td>
                <td style="width:50px;text-align:left;">点击</td>
                <td style="width:50px;text-align:left;">首页</td>
                <td style="width:50px;text-align:left;">封面</td>
                <td style="width:30px;text-align:center;">审核</td>
                <td style="width:30px;text-align:center;">撤销</td>
                <td style="width:180px;text-align:center;">创建日期</td>
                <td style="width:35px;"></td>
                <td style="width:35px;"></td>
                <td style="width:70px;"></td>
                <td style="width:30px;">评论</td>
            </tr>
            <cscms id="useralbumlist" type="useralbumlist">
                <item>
                    <![CDATA[
                    <tr class="griditem">
                        <td class="speline2" style="width:30px;text-align:center;">
                            <input class="albuminput" type="checkbox" name="albuminput[]" value="{f_useralbumid}" />
                        </td>
                        <td class="speline2" style="text-align:center;">
                            <span id="look_{f_useralbumid}" class="look" title="{f_useralbumname}" idvalue="{f_useralbumid}" style="cursor:pointer">
                                <img class="edit_doc" src="{rootpath}/images/edit.gif" alt="编辑" title="{f_useralbumid}" />
                            </span>
                        </td>
                        <td class="speline2" style="text-align:left;"><div title="有{f_countpic}张图片" style="line-height: 20px; height: 20px; overflow: hidden;"><a href="{funcdomain}/user/showpic.html?aid={f_useralbumid}" target="_blank">{f_useralbumname}</a></div></td>
                        <td class="speline2" style="text-align:left;"><span class="usermanage" title="{f_userid}" style="cursor:pointer">{f_nickname}</span></td>
                        <td class="speline2" style="text-align:left;"><div style="width:48px;line-height: 20px; height: 20px;overflow: hidden;white-space: nowrap;" title="{f_useralbumtag_value}">{f_useralbumtag_value}</div></td>
                        <td class="speline2" style="text-align:center;">
                            <span id="spanstate_{f_useralbumid}">{f_state}</span>
                        </td>
                        <td class="speline2" style="text-align:left;">{f_supportcount}</td>
                        <td class="speline2" style="text-align:left;">{f_hitcount}</td>
                        <td class="speline2" style="text-align:left;">{f_indextop}</td>
                        <td class="speline2" style="text-align:left;">
                            <img class="mainpic" title="{f_useralbumid}" src="{f_useralbumpicthumbnailurl}" width="50" height="20"/>
                            <div class="mainpicdiv" id="mainpic_{f_useralbumid}" style="position: absolute;z-index: 100;display: none">
                                <img src="{f_useralbumpicthumbnailurl}"/>
                            </div>
                        </td>
                        <td class="speline2" style="text-align:center;">
                            <span class="pass" idvalue="{f_useralbumid}"><img style=" cursor: pointer" alt="点击启用或审核该信息" src="{rootpath}/images/start.jpg" /></span>
                        </td>
                        <td class="speline2" style="text-align:center;">
                            <span class="stop" idvalue="{f_useralbumid}"><img style=" cursor: pointer" alt="停用或删除" src="{rootpath}/images/stop.jpg" /></span>
                        </td>
                        <td class="speline2" style="text-align:center;">{f_createdate}</td>
                        <td class="speline2" style="text-align:center;">
                            <span id="match_{f_useralbumid}" class="match" title="{f_useralbumname}" idvalue="{f_useralbumid}" style="cursor:pointer">
                                比赛
                            </span>
                        </td>
                        <td class="speline2" style="text-align:center;">
                            <span id="activity_{f_useralbumid}" class="activity" title="{f_useralbumname}" idvalue="{f_useralbumid}" style="cursor:pointer">
                                活动
                            </span>
                        </td>
                        <td class="speline2" style="text-align:center;">
                            <span id="detail_{f_useralbumid}" class="delete" title="{f_useralbumname}" idvalue="{f_useralbumid}" style="cursor:pointer">
                                删除
                            </span>
                            <span id="oprecode_{f_useralbumid}" class="oprecode" title="{f_useralbumname}" idvalue="{f_useralbumid}" style="cursor:pointer">
                                记录
                            </span>
                        </td>
                        <td class="speline2"><span class="comment_manage" title="{f_useralbumid}"><img class="comment_manage" style="cursor: pointer" src="{rootpath}/images/comment.gif" alt="评论管理" title="{f_useralbumid}" /></td>
                    </tr>
                    ]]>
                </item>
            </cscms>
        </table>
    </div>
    {pagerbutton}
    </body>
</html>
