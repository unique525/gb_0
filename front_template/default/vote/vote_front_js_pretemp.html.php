<style type="text/css">#vote_check_code_class{VoteId} {float:left;}
    #vote_check_code_class{VoteId} ul {text-align:center;margin-left:0px;padding-left:0px}
    #vote_check_code_class{VoteId} li {list-style-type: none;text-align: left;float:left;}
</style>
<div style="lint-height:0px;height:0px;clear:both;font-size:1px;position:absolute;z-index: 50;display:none" id="votecheckcodeclass{VoteId}">
    <script>
        function newimg{VoteId}()
        {
            return '<img id=\"checkimg{VoteId}\"  style=\"cursor:pointer\" src=\"/default.php?mod=common&a=gen_gif_verify_code&sn=votecheckcode{VoteId}&n='+Math.random()+'\" onclick=\"$(this).replaceWith(newimg{VoteId}());\" title=\"换一个\" alt=\"换一个\" />'
        }
    </script>
    <ul><li>验证码：<input type="text" style="width:60px" maxlength="6" id="checkcode{VoteId}" name="checkcode{VoteId}" />
            <input type="button" style="width:60px;display:none"  value="提交" id="checkcode_sub{VoteId}" idvalue1="" idvalue2=""/>
            <input type="button" style="width:60px;display:none"  value="提交" id="checkcode_all_sub{VoteId}" idvalue1="" idvalue2=""/>
            <input type="button" style="width:60px;display:none"  value="提交" id="checkcode_score_sub{VoteId}" idvalue1="" idvalue2=""/>
        </li>
        <li style="width:60px"><img id="checkimg{VoteId}" src="/default.php?mod=common&a=gen_gif_verify_code&sn=votecheckcode{VoteId}" style="cursor:pointer" title="换一个" alt="换一个" onclick="$(this).replaceWith(newimg{VoteId}());"/></li>
    </ul>
</div>
<script type="text/javascript">

    $().ready(function () {
        getItemList{VoteId}({VoteId});
        //GetRankingList(88952900);
        //GetRankingList(88952901);

        //提交一项
        $("#checkcode_sub{VoteId}").click(function(){
            var voteItemId=$(this).attr("idvalue1");
            var voteSelectItemId=$(this).attr("idvalue2");
            $("#votecheckcodeclass{VoteId}").hide();
            SubmitVote{VoteId}(voteItemId,voteSelectItemId);
        });

        //提交所有
        $("#checkcode_all_sub{VoteId}").click(function(){
            var voteItemId=$(this).attr("idvalue1");
            var voteSelectItemId=$(this).attr("idvalue2");
            $("#votecheckcodeclass{VoteId}").hide();
            ajaxSubmit{VoteId}(voteItemId,voteSelectItemId);
        });

        //提交打分
        $("#checkcode_score_sub{VoteId}").click(function(){
            var voteItemId=$(this).attr("idvalue1");
            var voteSelectItemId=$(this).attr("idvalue2");
            $("#votecheckcodeclass{VoteId}").hide();
            ajaxSubmitScore{VoteId}(voteItemId,voteSelectItemId);
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
        var offSet=$("#"+voteSelectItemId).offset();
        $("#votecheckcodeclass{VoteId}").css("top",offSet.top);
        $("#votecheckcodeclass{VoteId}").css("left",offSet.left);
        //$("#votecheckcodeclass{VoteId}").offset(offSet);
        $("#votecheckcodeclass{VoteId}").show();
        $("#checkcode_sub{VoteId}").show();
    }


    function ShowCheckCodeForSubmitAll{VoteId}(tag){
        var offSet=$(tag).offset();
        $("#votecheckcodeclass{VoteId}").css("top",offSet.top);
        $("#votecheckcodeclass{VoteId}").css("left",offSet.left);
        //$("#votecheckcodeclass{VoteId}").offset(offSet);
        $("#votecheckcodeclass{VoteId}").show();
        $("#checkcode_all_sub{VoteId}").show();
    }


    function ShowCheckCodeForSubmitScore{VoteId}(tag){
        var offSet=$(tag).offset();
        $("#votecheckcodeclass{VoteId}").css("top",offSet.top);
        $("#votecheckcodeclass{VoteId}").css("left",offSet.left);
        //$("#votecheckcodeclass{VoteId}").offset(offSet);
        $("#votecheckcodeclass{VoteId}").show();
        $("#checkcode_score_sub{VoteId}").show();
    }

    /**
     每项分别提交
     */
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
                //alert(data.readyState);
                if ( $("#checkimg{VoteId}").length > 0 )
                    $("#checkimg{VoteId}").trigger('onclick');//再次刷新图片
                //div.html("投票");
            }
        });
    }

/**
    一个按钮提交所有(投票)
 */
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
                //alert(data.readyState);
                if ( $("#checkimg{VoteId}").length > 0 )
                    $("#checkimg{VoteId}").trigger('onclick');//再次刷新图片
            }
        });
    }



    /**
     一个按钮提交所有(打分)
     */
    function ajaxSubmitScore{voteId}()
    {
        //判断是否有附加验证函数
        if ($.isFunction(window.checkvalue{VoteId}))
        {if(!checkvalue{VoteId}()) return;}
        var param = $("#voteform{VoteId}").serialize();
        var url = "/default.php?mod=vote&a=score&vote_id={VoteId}&sn=check_code{VoteId}";
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
                //alert(data.readyState);
                if ( $("#checkimg{VoteId}").length > 0 )
                    $("#checkimg{VoteId}").trigger('onclick');//再次刷新图片
            }
        });
    }
</script>