<style type="text/css">
.votelist.votelist{VoteId} .voteitem {} 
.votelist.votelist{VoteId} .voteitem ul {width: 100%; clear:both;margin-left:0px;padding-left:0px} 
.votelist.votelist{VoteId} .voteitem li {width: 24.5%;line-height: {itemheight};list-style-type: none;text-align: left;float: left;} 
.votelist.votelist{VoteId} .voteitem li.voteitemtitle {line-height:60px;color:#81511c;font-size:14px;width:100%;font-weight:bold;float:none;}
#vote_check_code_class{VoteId} {float:left;}
#vote_check_code_class{VoteId} ul {text-align:center;margin-left:0px;padding-left:0px}
#vote_check_code_class{VoteId} li {list-style-type: none;text-align: left;float:left;}
#votebtnclass{VoteId} {display:{btndisplay};float:left;}
#votebtnclass{VoteId} ul {text-align:center;list-style:none;}
#votebtnclass{VoteId} li {list-style-type: none;text-align: left;float:left;}
</style>
<div class="votelist votelist{VoteId}">
    <form id="voteform{VoteId}" name="voteform{VoteId}" method="get">
        <div class="voteitem">
            <div class="voteitem">
                <icms id="vote_{VoteId}" type="vote_item_list">
                    <item>
                        <![CDATA[
                        <ul>
                            <li class="voteitemtitle">{f_voteitemtitle}</li>
                            {child}
                        </ul>
                        ]]>
                    </item>
                    <child>
                        <![CDATA[
                        <li><input type="{f_voteitemtypename}" name="voteselectitem{f_voteitemid}[]"  value="{f_voteselectitemid}" /> {f_voteselectitemtitle} <span id="vote_select_item_result{f_voteselectitemid}">0票 0%</span></li>
                        ]]>
                    </child>
                </icms>
            </div>
        </div>
        <div style="lint-height:0px;height:0px;clear:both;font-size:1px"></div>
                <div id="vote_check_code_class{VoteId}">
            <script>
                function newimg{VoteId}()
                {
                    return "<img id=\"checkimg{VoteId}\"  style=\"cursor:pointer\" src=\"/common/votecheckcode.php?sn=votecheckcode{VoteId}&n="+Math.random()+"\" onclick=\"$(this).replaceWith(newimg{VoteId}());\" title=\"换一个\" alt=\"换一个\" />"
                }
            </script>
            <ul><li>验证码：<input type="text" style="width:60px" maxlength="6" id="checkcode{VoteId}" name="checkcode{VoteId}" />&nbsp;&nbsp;</li>
                <li style="width:60px"><img id="checkimg{VoteId}" src="/common/votecheckcode.php?sn=votecheckcode{VoteId}" style="cursor:pointer" title="换一个" alt="换一个" onclick="$(this).replaceWith(newimg{VoteId}());"/></li>
            </ul>
        </div>
        <div id="votebtnclass{VoteId}">
            <ul>
                <li><input type="button" name="votebtn{VoteId}" id="votebtn{VoteId}" value="提交" onclick="ajaxSubmit{VoteId}()" /></li>
            </ul>
        </div>
        <div style="lint-height:0px;height:0px;clear:both;font-size:1px"></div>
    </form>
</div>
<script type="text/javascript">
    //判断函数是否存在
    //判断函数是否存在
    function isFunction( fn ) {
        return !!fn && !fn.nodeName && fn.constructor != String && fn.constructor != RegExp && fn.constructor != Array && /function/i.test( fn + "" );
    }
    function ajaxSubmit{voteId}()
    {
        //判断是否有附加验证函数
        if ($.isFunction(window.checkvalue{VoteId}))
        {if(!checkvalue{VoteId}()) return;}
        var param = $("#voteform{VoteId}").serialize();
        var url = "/default.php?mod=vote&a=vote&vote_id={VoteId}&sn=check_code{VoteId}";
        $.ajax({
            url:url,
            dataType: "jsonp",
            jsonp: "jsonpcallback",
            data: param,
            success: function (data) {
                //判断验证码图片是否存在，存在则刷新图片
                if ( $("#checkimg{VoteId}").length > 0 )
                    $("#checkimg{VoteId}").trigger('onclick');//再次刷新图片
                var result=data["result"];
                var ipMaxCount = data["IpMaxCount"];
                var userMaxCount = data["UserMaxCount"];
                var voteRecordId = data["VoteRecordId"];
                if(result==1) {
                    //判断是否有附加要提交的额外如表单类的信息
                    if ($.isFunction(window.hdSubmit{VoteId}))
                    {hdSubmit{VoteId}(voteRecordId)}
                    alert("投票成功！");
                }
                else if(result==-10) {alert("一个IP地址一天投票不能超过"+ipMaxCount+"票！");}
                else if(result==-2) {alert("至少需要选择一项才能投票！");}
                else if(result==-3) {alert("投票已经停止！");}
                else if(result==-4) {alert("投票不在有效时间段内！");}
                else if(result==-5) {alert("验证码错误！");}
                else if(result==-8) {alert("一个用户一天投票不能超过"+userMaxCount+"票！");}
                else if(result==-9) {alert("您还没有登陆，请先登陆再投票！");}
                else {alert("投票失败！");}
            },
            error: function (data, status, e){
                alert(data.readyState);
                if ( $("#checkimg{VoteId}").length > 0 )
                    $("#checkimg{VoteId}").trigger('onclick');//再次刷新图片
            }
        });
    }
    function getItemList{VoteId}(voteId){
        $.ajax({
            url:"/default.php",
            data:{mod:"vote",a:"select_item_list",vote_id:voteId},
            dataType:"jsonp",
            jsonp:"jsonpcallback",
            success:function(data){
                var result = new Array();
                result = data["result"];
                $.each(result,function(i,v){
                    var targetId="vote_select_item_result"+v["VoteSelectItemId"];
                    $("#"+targetId).html(v["VoteSelectItemRecordCount"]+"票 "+v["VoteSelectItemPer"]+"%");
                });
            }
        });
    }
    $().ready(function () {
        getItemList{VoteId}({VoteId});
    });
</script>