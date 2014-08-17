<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" href="/system_template/default/images/ztree/ztreestyle.css" type="text/css"/>
<link rel="stylesheet" href="/system_template/default/images/ztree/demo.css" type="text/css"/>
<style type="text/css">
    .spe_line{border-bottom:dashed 1px #d5d5d5;}
    /* ------------- 右键菜单 -----------------  */
    div#rMenu {position:absolute; visibility:hidden; top:0; background-color: #555;text-align: left;padding: 2px;}
    div#rMenu ul li{
        margin: 1px 0;
        padding: 0 5px;
        cursor: pointer;
        list-style: none outside none;
        background-color: #DFDFDF;
    }

</style>
<script type="text/javascript" src="/system_js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="/system_js/common.js"></script>
<script type="text/javascript" src="/system_js/ztree/jquery.ztree.core-3.0.min.js"></script>
<script type="text/javascript" src="/system_js/ztree/jquery.ztree.excheck-3.0.min.js"></script>
<script type="text/javascript" src="/system_js/ztree/jquery.ztree.exedit-3.0.min.js"></script>
<script type="text/javascript" LANGUAGE="javascript">
<!--
var zTree1;
var setting;
var zNodes =[{ id:0, pId:-1,name:"根节点", valueType:0,open:true},{ id:83, pId:1, name:"生产公司", valueType:"6"},{ id:82, pId:83, name:"品牌", valueType:"6"},{ id:81, pId:1, name:"经销商", valueType:"6"},{ id:76, pId:75, name:"ABS(刹车防抱死制动系统)", valueType:"0"},{ id:60, pId:58, name:"CD", valueType:"0"},{ id:61, pId:58, name:"CD碟数", valueType:"0"},{ id:68, pId:65, name:"GPS电子导航", valueType:"0"},{ id:59, pId:58, name:"MP3", valueType:"0"},{ id:80, pId:75, name:"安全带限力功能", valueType:"0"},{ id:79, pId:75, name:"安全带预收紧功能", valueType:"0"},{ id:75, pId:0, name:"安全配置", valueType:"0"},{ id:65, pId:0, name:"便利功能", valueType:"0"},{ id:29, pId:26, name:"变速器型式", valueType:"0"},{ id:13, pId:12, name:"长", valueType:"0"},{ id:5, pId:1, name:"厂家", valueType:"0"},{ id:3, pId:1, name:"厂商指导价", valueType:"0"},{ id:38, pId:37, name:"车窗", valueType:"0"},{ id:69, pId:65, name:"倒车雷达", valueType:"0"},{ id:26, pId:0, name:"底盘操控", valueType:"0"},{ id:39, pId:37, name:"电动窗防夹功能", valueType:"0"},{ id:67, pId:65, name:"定速巡航系统", valueType:"0"},{ id:73, pId:65, name:"多功能方向盘", valueType:"0"},{ id:20, pId:0, name:"发动机", valueType:"0"},{ id:47, pId:46, name:"方向盘调节方向", valueType:"0"},{ id:48, pId:46, name:"方向盘换档", valueType:"0"},{ id:78, pId:75, name:"副驾驶位安全气囊", valueType:"0"},{ id:55, pId:46, name:"副驾驶座椅调节方式", valueType:"0"},{ id:56, pId:46, name:"副驾驶座椅调节方向", valueType:"0"},{ id:15, pId:12, name:"高", valueType:"0"},{ id:36, pId:26, name:"后轮胎规格", valueType:"0"},{ id:64, pId:58, name:"后排出风口", valueType:"0"},{ id:34, pId:26, name:"后悬挂类型", valueType:"0"},{ id:32, pId:26, name:"后制动类型", valueType:"0"},{ id:57, pId:46, name:"后座中央扶手", valueType:"0"},{ id:1, pId:0, name:"基本信息", valueType:"0"},{ id:7, pId:1, name:"级别", valueType:"0"},{ id:77, pId:75, name:"驾驶位安全气囊", valueType:"0"},{ id:52, pId:46, name:"驾驶座腰部支撑调节", valueType:"0"},{ id:53, pId:46, name:"驾驶座座椅调节方式", valueType:"0"},{ id:54, pId:46, name:"驾驶座座椅调节方向", valueType:"0"},{ id:62, pId:58, name:"空调控制方式", valueType:"0"},{ id:14, pId:12, name:"宽", valueType:"0"},{ id:72, pId:65, name:"蓝牙系统", valueType:"0"},{ id:41, pId:37, name:"内后视镜防眩目功能", valueType:"0"},{ id:46, pId:0, name:"内饰", valueType:"0"},{ id:11, pId:1, name:"年款", valueType:"0"},{ id:6, pId:1, name:"排量", valueType:"0"},{ id:21, pId:20, name:"排量", valueType:"0"},{ id:45, pId:37, name:"前大灯自动开闭", valueType:"0"},{ id:35, pId:26, name:"前轮胎规格", valueType:"0"},{ id:33, pId:26, name:"前悬挂类型", valueType:"0"},{ id:44, pId:37, name:"前照灯类型", valueType:"0"},{ id:31, pId:26, name:"前制动类型", valueType:"0"},{ id:17, pId:0, name:"燃油", valueType:"0"},{ id:19, pId:17, name:"燃油标号", valueType:"0"},{ id:18, pId:17, name:"燃油箱容积", valueType:"0"},{ id:10, pId:1, name:"上市时间", valueType:"0"},{ id:2, pId:1, name:"市场报价", valueType:"0"},{ id:28, pId:26, name:"随速助力转向调节(EPS)", valueType:"0"},{ id:71, pId:65, name:"胎压检测装置", valueType:"0"},{ id:12, pId:0, name:"外部尺寸", valueType:"0"},{ id:37, pId:0, name:"外部配置", valueType:"0"},{ id:42, pId:37, name:"外后视镜电动调节", valueType:"0"},{ id:43, pId:37, name:"外后视镜加热功能", valueType:"0"},{ id:63, pId:58, name:"温区个数", valueType:"0"},{ id:74, pId:65, name:"无钥匙点火系统", valueType:"0"},{ id:8, pId:1, name:"厢式", valueType:"0"},{ id:49, pId:46, name:"行车电脑", valueType:"0"},{ id:58, pId:0, name:"影音空调", valueType:"0"},{ id:40, pId:37, name:"雨刷传感器", valueType:"0"},{ id:9, pId:1, name:"质保", valueType:"0"},{ id:70, pId:65, name:"中控门锁", valueType:"0"},{ id:16, pId:12, name:"轴距", valueType:"0"},{ id:50, pId:46, name:"转速表", valueType:"0"},{ id:27, pId:26, name:"转向助力", valueType:"0"},{ id:22, pId:20, name:"最大功率-功率值", valueType:"0"},{ id:23, pId:20, name:"最大功率-转速", valueType:"0"},{ id:24, pId:20, name:"最大扭矩-扭矩值", valueType:"0"},{ id:25, pId:20, name:"最大扭矩-转速", valueType:"0"},{ id:51, pId:46, name:"座椅面料", valueType:"0"}];
setting = {
    data: {
        simpleData: {
            enable: true,
            idKey:"id",
            pIdKey:"pId",
            rootPid:"-1"
        }
    },
    edit: {
        enable:true,
        showRemoveBtn:false,
        showReNameBtn:false,
        drag:{
            isCopy:true,
            isMove:true
        }
    },
    callback:{
        onClick:zTreeOnClick,
        beforeDrop:zTreeBeforeDrop,
        onRightClick:zTreeOnRightClick
    }
};

function refreshTree() {
    zTree1=$.fn.zTree.init($("#treeDemo"), setting, zNodes);
}

var rMenu;
$(document).ready(function(){
    refreshTree();
    rMenu = document.getElementById("rMenu");
    $("body").bind("mousedown",
            function(event){
                if (!(event.target.id == "rMenu" || $(event.target).parents("#rMenu").length>0)) {
                    rMenu.style.visibility = "hidden";
                }
            });
    var img = $("#img_titlepic");
    var theImage = new Image();
    theImage.src = img.attr("src");
    var tp = '{titlepic}';
    $("#preview_titlepic").click(function() {
        if(tp != ''){

            $("#dialog_titlepic").dialog({
                width : theImage.width+30,
                height : theImage.height+50
            });

        }
        else{
            alert('还没有上传题图');
        }
    });
});

function zTreeOnClick(event, treeId, treeNode) {
    var id=treeNode.id;
    var parentId=treeNode.pId;
    if(parentId==-1) {return false;}//根结点不予选中
    var parentName = zTree1.getNodeByParam("id", parentId).name;
    $("#s_ParentName").text(parentName);
    $.ajax({
        url:"/product/index.php?a=product_param_type",
        data:{m:"view",param_type_id:id},
        dataType:"jsonp",
        jsonp:"jsonpcallback",
        success:function(data){
            $("#f_channelId").val(data['DocumentChannelID']);
            $("#f_ParentId").val(data['ParentID']);
            $("#f_ProductParamTypeId").val(data['ProductParamTypeID']);
            $("#s_ProductParamTypeId").text(data['ProductParamTypeID']);
            $("#f_ParamValueType").val(data['ParamValueType']);
            $("#f_paramTypeName").val(data['ParamTypeName']);
            $("#f_Sort").val(data['Sort']);
            $("#f_createdate").val(data['CreateDate']);
            $("#img_titlepic").val("");
            var btnSubmit=$("#btnSubmit");
            btnSubmit.attr('value', '修改');
            btnSubmit.css("display","");
            var optionHref=$("#optionHref");            
            if(data['ParamValueType']==6)
            {
                optionHref.css("display","");
                optionHref.attr("href","/product/index.php?a=product_param_type_option&m=list&channel_id="+data['DocumentChannelID']+"&param_type_id="+data['ProductParamTypeID']+"&parentId="+data['ParentID']);
            }
            else
            {
                optionHref.css("display","none");
                optionHref.attr("href","#");
            }
        }
    });
}

function showRMenu(type, x, y) {
    var rMenu=$("#rMenu");
    rMenu.show();
    if (type=="root") {
        $("#m_del").hide();
        $("#m_check").hide();
        $("#m_unCheck").hide();
    }
    rMenu.css({"top":y+"px", "left":x+"px", "visibility":"visible"});
}
function hideRMenu() {
    if (rMenu) rMenu.style.visibility = "hidden";
}

function zTreeOnRightClick(event, treeId, treeNode) {
    if (!treeNode && event.target.tagName.toLowerCase() != "button" && $(event.target).parents("a").length == 0) {
        zTree1.cancelSelectedNode();
        showRMenu("root", event.clientX, event.clientY);
    } else if (treeNode && !treeNode.noR) {
        zTree1.selectNode(treeNode);
        showRMenu("node", event.clientX, event.clientY);
    }
}

function addTreeNode() {
    hideRMenu();
    var selectNode = zTree1.zTree.getSelectedNodes()[0];
    var parentId=selectNode.id;
    var parentName = selectNode.name;
    $("#f_channelId").val(Request['channel_id']);
    $("#s_ParentName").text(parentName);
    $("#f_ParentId").val(parentId);
    $("#f_ProductParamTypeId").val("");
    $("#f_ParamValueType").val("0");
    $("#s_ProductParamTypeId").text("");
    $("#f_paramTypeName").val("");
    $("#f_Sort").val('0');
    var file = $("#img_titlepic");
    file.after(file.clone().val(''));//清空file类型控件
    file.remove();
    var today = new Date();
    var month = today.getMonth()+1;
    var s_date = today.getFullYear()+"-"+month+"-"+today.getDate();
    var s_hour = today.getHours()<10?"0"+today.getHours():today.getHours();
    var s_minute = today.getMinutes()<10?"0"+today.getMinutes():today.getMinutes();
    var s_second = today.getSeconds()<10?"0"+today.getSeconds():today.getSeconds();
    $("#f_createdate").val(s_date + " " + s_hour + ":"+s_minute+":"+s_second );
    var btnSubmit = $("#btnSubmit");
    btnSubmit.attr('value', '新增');
    btnSubmit.css("display","");
}

//用于新增返回时调用同步树节点
function AddNodeByID(sourceId,targetId,name,eName)
{
    var parentNode = zTree1.getNodeByParam("id", targetId);
    zTree1.addNodes(parentNode,[{ id:""+sourceId,pid:""+targetId,name:""+name,valueType:""+eName}]);
}

//用于修改时调用同步树节点
function EditNodeByID(sourceId,targetId,name,eName)
{
    var sourceNode = zTree1.getNodeByParam("id", sourceId);
    sourceNode.id=sourceId;
    sourceNode.pId=targetId;
    sourceNode.name=name;
    sourceNode.eName=eName;
    zTree1.updateNode(sourceNode, true);
}
function removeTreeNode() {
    hideRMenu();
    var treeNode = zTree1.getSelectedNodes()[0];
    var id=treeNode.id;
    var channelId=Request['channel_id'];
    if (treeNode) {
        if (treeNode.nodes && treeNode.nodes.length > 0) {
            var msg = "要删除的节点是父节点，只允许删除叶节点";
            alert(msg);
        } else {
            $.ajax({
                url:"/product/index.php?a=product_param",
                async: false,
                data:{m:"param_count_by_id",channel_id:channelId,product_param_type_id:id},
                dataType:"jsonp",
                jsonp:"jsonpcallback",
                success:function(data){
                    var count=data['result'];
                    if(count<0){
                        msg="节点数据有错，请联系管理员";
                        alert(msg);
                    }
                    else if(count>0){
                        msg="该节点被不止一个参数引用，不能删除";
                        alert(msg);
                    }
                    else  {
                        removeTreeNodeByNode(treeNode);
                    }
                }
            });
        }
    }
}

function removeTreeNodeByNode(treeNode)
{
    var param_type_id=treeNode.id;
    $.ajax({
        url:"/product/index.php?a=product_param_type",
        async: false,
        data:{m:"delete",param_type_id:param_type_id},
        dataType:"jsonp",
        jsonp:"jsonpcallback",
        success:function(data){
            zTree1.removeNode(treeNode);
            alert("删除结点成功");
        }
    });
}

function zTreeBeforeDrop(treeId, treeNode, targetNode, moveType) {
    var id = treeNode.id;
    var pId="";
    if(moveType=="inner") pId=targetNode.id; else pId=targetNode.pId;
    $.ajax({
        url:"/product/index.php?a=product_param_type",
        async: false,
        data:{m:"drag",param_type_id:id,parentId:pId,type:1},
        dataType:"jsonp",
        jsonp:"jsonpcallback",
        success:function(data){
            //根据ajax结果自定义拖拽处理
            if(data['result']>0)
            {
                var parentNode = zTree1.getNodeByParam("id", pId);
                zTree1.removeNode(treeNode);
                zTree1.addNodes(parentNode,treeNode);
            }
            else {alert("节点移动失败")}
        }
    });
    return false;
}

function sub()
{
    var message="";
    var mainForm=$('#mainForm');
    if($('#f_paramTypeName').val() == ''){
        message +="请输入名称\r\n";
    }
    if(message!="") alert(message);
    else{
        var methodName=$("#btnSubmit").attr('value');
        var actionUrl="";
        if(methodName=="修改") actionUrl="../product/index.php?a=product_param_type&m=edit&type=1";
        else if(methodName=="新增") actionUrl="../product/index.php?a=product_param_type&m=new&type=1";
        else {alert("错误的操作类型");}
        mainForm.attr("action",actionUrl);
        mainForm.submit();
    }
}

//-->
</script>
</head>
<body>

<div id="tb_window_top">
    <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td width="40%" height="35"></td>
            <td align="right"><img alt="刷新" style="cursor: pointer" onclick="window.location.href=window.location.href;" src="../images/re.gif" /> <img alt="关闭" style="cursor: pointer" onclick="self.parent.tb_remove();" src="../images/close.gif" /></td>
        </tr>
    </table>
</div>

<div id="rMenu">
     <ul id="m_add" onclick="addTreeNode();"><li>增加节点</li></ul>
     <ul id="m_del" onclick="removeTreeNode();"><li>删除节点</li></ul>
</div>
<form id="mainForm" name="mainForm" action="" method="post"  enctype="multipart/form-data"  target='hidden_frame' >
    <div style="margin:8px;">
        <table border=0 class="tb1">
            <tr>
                <td width=380px align=center valign=top>
                    <div style="text-align:left;">
                        <ul id="treeDemo" class="ztree" style="border: 1px #000 solid;height:470px;width:380px"></ul>
                    </div>
                </td>
                <td  align=left valign=top style="padding:0 0 0 60px;">
                    <table width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <td class="spe_line" >上级节点名称：</td>
                            <td class="spe_line">
                                <label id="s_ParentName" style=" width: 200px;font-size:14px;"></label>
                                <input type="hidden" id="f_ParentId" name="f_ParentId" value="" />
                            </td>
                        </tr>
                        <tr>
                            <td class="spe_line" ><label for="f_paramTypeName">名称：</label></td>
                            <td class="spe_line">
                                <input type="text" class="input_box" id="f_paramTypeName" name="f_paramTypeName" value="" style=" width: 200px;font-size:14px;" maxlength="50" /><label id="s_ProductParamTypeId" ></label>
                                <input type="hidden" id="f_ProductParamTypeId" name="f_ProductParamTypeId" value="" />
                                <input type="hidden" id="f_channelId" name="f_channelId" value="" />
                                <input type="hidden" id="f_createdate" name="f_createdate" value="" />
                            </td>
                        </tr>
                        <tr>
                            <td class="spe_line">题图：</td>
                            <td class="spe_line">
                                <input id="titlepic_upload" name="titlepic_upload" type="file" class="input_box" style="width:200px; background: #ffffff; margin-top: 3px;" /> <span id="preview_titlepic">[预览]</span>
                                <div id="dialog_titlepic" title="题图预览（{titlepic}）" style="display:none;">
                                    <div id="pubtable">
                                        <table>
                                            <tr>
                                                <td><img id="img_titlepic" src="{titlepic}" alt="titlepic" /></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="spe_line" >类型：</td>
                            <td class="spe_line">
                                <select id="f_ParamValueType" name="f_ParamValueType">
                                    <option value="0" {s_ParamValueType_0}>默认</option>
                                    <option value="6" {s_ParamValueType_6}>选项</option>
                                </select>
                                &nbsp;&nbsp;<a id="optionHref" href="#" target="_self" style="display:none">修改参数类型选项</a>
                            </td>
                        </tr>
                        <tr>
                            <td class="spe_line"><label for="f_Sort">排序数字：</label></td>
                            <td class="spe_line"><input type="text" class="input_number" id="f_Sort" name="f_Sort" value="0" style=" width: 100px;font-size:14px;" maxlength="10" /> (输入数字，越大越靠前)</td>
                        </tr>
                        <tr>
                            <td colspan="2" align="right" style="padding-top:10px">
                                <input type="button" id="btnSubmit" name="btnSubmit" class="btnSubmit" tabindex="0" onclick="sub();" style="display:none" value="" accesskey="s" />
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</form>
<iframe name='hidden_frame' id="hidden_frame"  style='display:none'></iframe>
</body>
</html>
