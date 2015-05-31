<style type="text/css">
.votelist.votelist{voteid} .voteitem {} 
.votelist.votelist{voteid} .voteitem ul {width: 100%; clear:both;margin-left:0px;padding-left:0px} 
.votelist.votelist{voteid} .voteitem li {width: 99%;line-height: {itemheight};list-style-type: none;text-align: left;float: left;} 
.votelist.votelist{voteid} .voteitem li.voteitemtitle {line-height:60px;color:#81511c;font-size:14px;width:100%;font-weight:bold;float:none;}
#vote_check_code_class{voteid} {float:left;}
#vote_check_code_class{voteid} ul {text-align:center;margin-left:0px;padding-left:0px}
#vote_check_code_class{voteid} li {list-style-type: none;text-align: left;float:left;}
#votebtnclass{voteid} {display:{btndisplay};float:left;}
#votebtnclass{voteid} ul {text-align:center;list-style:none;}
#votebtnclass{voteid} li {list-style-type: none;text-align: left;float:left;}
</style>
<div class="votelist votelist{voteid}">
    <form id="voteform{voteid}" name="voteform{voteid}" method="get">
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
                    <li><input type="{f_voteitemtypename}" name="voteselectitem{f_voteitemid}[]"  value="{f_voteselectitemid}" /> {f_voteselectitemtitle} <span id="voteselectitemresult{f_voteselectitemid}">0票 0%</span></li>
                    </li>
                    ]]>
                </child>
            </icms>
        </div>
        <div style="lint-height:0px;height:0px;clear:both;font-size:1px"></div>
                <div id="vote_check_code_class{voteid}">
            <script>
                function newimg{voteid}()
                {
                    return "<img id=\"checkimg{voteid}\"  style=\"cursor:pointer\" src=\"{funcdomain}/common/votecheckcode.php?sn=votecheckcode{voteid}&n="+Math.random()+"\" onclick=\"$(this).replaceWith(newimg{voteid}());\" title=\"换一个\" alt=\"换一个\" />"
                }
            </script>
            <ul><li>验证码：<input type="text" style="width:60px" maxlength="6" id="checkcode{voteid}" name="checkcode{voteid}" />&nbsp;&nbsp;</li>
                <li style="width:60px"><img id="checkimg{voteid}" src="{funcdomain}/common/votecheckcode.php?sn=votecheckcode{voteid}" style="cursor:pointer" title="换一个" alt="换一个" onclick="$(this).replaceWith(newimg{voteid}());"/></li>
            </ul>
        </div>
        <div id="votebtnclass{voteid}">
            <ul>
                <li><input type="button" name="votebtn{voteid}" id="votebtn{voteid}" value="提交" onclick="ajaxSubmit{voteid}()" /></li>
            </ul>
        </div>
        <div style="lint-height:0px;height:0px;clear:both;font-size:1px"></div>
    </form>
</div>  
<script type="text/javascript">
//判断函数是否存在
function isFunction( fn ) {
return !!fn && !fn.nodeName && fn.constructor != String && fn.constructor != RegExp && fn.constructor != Array && /function/i.test( fn + "" ); 
}
function ajaxSubmit{voteid}()
    {
        //判断是否有附加验证函数		
        if ($.isFunction(window.checkvalue{voteid}))
        {if(!checkvalue{voteid}()) return;}
        var param = $("#voteform{voteid}").serialize();
        var url = "/index.php?a=vote&m=add&voteid={voteid}&sn=votecheckcode{voteid}";
        $.ajax({
            url:url,
            dataType: "jsonp",
            jsonp: "jsonpcallback",
            data: param,
            success: function (data) {
                                //判断验证码图片是否存在，存在则刷新图片
                                if ( $("#checkimg{voteid}").length > 0 )
                                $("#checkimg{voteid}").trigger('onclick');//再次刷新图片
                                var result=data["result"];
				var maxipnum = data["maxipnum"];
                                var voterecordid = data["voterecordid"];
				if(result==1) {
                                //判断是否有附加验证函数		
        			if ($.isFunction(window.hdSubmit{voteid}))
        			{hdSubmit{voteid}(voterecordid)}
                                //显示票数
                                var itemlist = new Array();
				itemlist = data["itemlist"];
				$.each(itemlist,function(i,v){
                                    var targetid="voteselectitemresult"+v["VoteSelectItemId"];
                                    $("#"+targetid).html(v["cvsirecordcount"]+"票 "+v["voteselectitemper"]+"%");
                                });
                                }
				else if(result==-1) {alert("一个IP地址一天投票不能超过"+maxipnum+"票！");}
                                else if(result==-2) {alert("至少需要选择一项才能投票！");}
                                else if(result==-3) {alert("投票已经停止！");}
                                else if(result==-4) {alert("投票不在有效时间段内！");}
                                else if(result==-5) {alert("验证码错误！");}
                                else if(result==-8) {alert("一个用户一天投票不能超过"+maxusernum+"票！");}
                                else if(result==-9) {alert("您还没有登陆，请先登陆再投票！");}
				else {alert("投票失败！");}
                },
            error: function (data, status, e){
                alert(data.readyState);
                if ( $("#checkimg{voteid}").length > 0 )
                                $("#checkimg{voteid}").trigger('onclick');//再次刷新图片
                }
            });
    }
    function getitemList{voteid}(voteid){
		$.ajax({
			url:"/index.php",
			data:{a:"vote",m:"selectitemlist",voteid:voteid},
			dataType:"jsonp",
			jsonp:"jsonpcallback",
			success:function(data){
				var result = new Array();
				result = data["result"];
				$.each(result,function(i,v){
                                    var targetid="voteselectitemresult"+v["VoteSelectItemId"];
                                    $("#"+targetid).html(v["cvsirecordcount"]+"票 "+v["voteselectitemper"]+"%");
                                });
			}
		});
	}
        $().ready(function () {
		getitemList{voteid}({voteid});
        });
</script>