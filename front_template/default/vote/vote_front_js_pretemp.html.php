<style type="text/css">#vote_check_code_class{VoteId} {float:left;}
    #vote_check_code_class{VoteId} ul {text-align:center;margin-left:0px;padding-left:0px}
    #vote_check_code_class{VoteId} li {list-style-type: none;text-align: left;float:left;}
</style>
<div style="lint-height:0px;height:0px;clear:both;font-size:1px;position:absolute;z-index: 50;display:none" id="votecheckcodeclass{VoteId}">
    <script>
        function newimg{VoteId}()
        {
            //return '<img id=\"checkimg{VoteId}\"  style=\"cursor:pointer\" src=\"/default.php?mod=common&a=gen_gif_verify_code&sn=votecheckcode{VoteId}&n='+Math.random()+'\" onclick=\"$(this).replaceWith(newimg{VoteId}());\" title=\"换一个\" alt=\"换一个\" />'
        }
    </script>
    <ul><li>验证码：<input type="text" style="width:60px" maxlength="6" id="checkcode{VoteId}" name="checkcode{VoteId}" /><input type="button" style="width:60px"  value="提交" id="checkcode_sub{VoteId}" idvalue1="" idvalue2=""/>   </li>
        <li style="width:60px"><img id="checkimg{VoteId}" src="/default.php?mod=common&a=gen_gif_verify_code&sn=votecheckcode{VoteId}" style="cursor:pointer" title="换一个" alt="换一个" onclick="$(this).replaceWith(newimg{VoteId}());"/></li>
    </ul>
</div>
<script type="text/javascript">

    $().ready(function () {
        getItemList{VoteId}({VoteId});
        //GetRankingList(88952900);
        //GetRankingList(88952901);
        $("#checkcode_sub{VoteId}").click(function(){
            var voteItemId=$(this).attr("idvalue1");
            var voteSelectItemId=$(this).attr("idvalue2");
            $("#votecheckcodeclass{VoteId}").hide();
            SubmitVote{VoteId}(voteItemId,voteSelectItemId);
        });
    });
    //判断函数是否存在
    function isFunction( fn ) {
        return !!fn && !fn.nodeName && fn.constructor != String && fn.constructor != RegExp && fn.constructor != Array && /function/i.test( fn + "" );
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
                    $("#"+targetId).html(v["VoteSelectItemRecordCount"]);
                });
            }
        });
    }
    function submit{VoteId}(voteItemId,voteSelectItemId){
        $("#checkcode_sub{VoteId}").attr("idvalue1",voteItemId);
        $("#checkcode_sub{VoteId}").attr("idvalue2",voteSelectItemId);
        var offSet=$("#"+voteSelectItemId).offset()
        $("#votecheckcodeclass{VoteId}").offset(offSet);
        $("#votecheckcodeclass{VoteId}").show();
    }

    function SubmitVote{VoteId}(voteItemId,voteSelectItemId)
    {
        var div=$("#"+voteSelectItemId);
        //div.html("<img src='/images/loading.gif' />");
        var param="vote_select_item"+voteItemId+"%5B%5D="+voteSelectItemId;
        var url = "/default.php?mod=vote&a=vote&vote_id={VoteId}&sn=votecheckcode{VoteId}&check_code="+$("#checkcode{VoteId}").val();
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
                var maxipnum = data["IpMaxCount"];
                var voterecordid = data["VoteRecordId"];
                if(result==1) {
                    alert("投票成功，谢谢您的参与！");
                    getItemList1075({VoteId});
                }
                else if(result==-1) {alert("已超过每日投票上限");}//{alert("一个IP地址一天投票不能超过"+maxipnum+"票！");}
                else if(result==-2) {alert("至少需要选择一项才能投票！");}
                else if(result==-3) {alert("投票已经停止！");}
                else if(result==-4) {alert("投票不在有效时间段内！");}
                else if(result==-5) {alert("验证码错误！");}
                else if(result==-10) {alert("已超过每日投票上限");} //{alert("一个IP地址一天投票不能超过"+maxipnum +"票！");}
                else {alert("投票失败！");}
                //div.html("投票");
            },
            error: function (data, status, e){
                alert(data.readyState);
                if ( $("#checkimg{VoteId}").length > 0 )
                    $("#checkimg{VoteId}").trigger('onclick');//再次刷新图片
                //div.html("投票");
            }
        });
    }


</script>