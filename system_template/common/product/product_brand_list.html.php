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
var zNodes =[{treeNodes}];
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
        showRenameBtn:false,
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
        url:"default.php?secu=manage&mod=product_brand",
        data:{m:"async_one",product_brand_id:id},
        dataType:"jsonp",
        jsonp:"jsonpcallback",
        success:function(data){
            $("#f_ParentId").val(data['ParentId']);
            $("#s_ProductBrandId").text(data['ProductBrandId']);
            $("#f_Rank").val(data['Rank']);
            $("#f_ProductBrandName").val(data['ProductBrandName']);
            $("#f_Sort").val(data['Sort']);
            $("#f_CreateDate").val(data['CreateDate']);
            $("#img_titlepic").val("");
            var btnSubmit=$("#btnSubmit");
            btnSubmit.attr('value', '修改');
            btnSubmit.css("display","");
        }
    });
    return true;
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
        //showRMenu("root", event.clientX, event.clientY);
    } else if (treeNode && !treeNode.noR) {
        zTree1.selectNode(treeNode);
        showRMenu("node", event.clientX, event.clientY);
    }
}

function addTreeNode() {
    hideRMenu();
    var selectNode = zTree1.getSelectedNodes()[0];
    var parentId=selectNode.id;
    var parentName = selectNode.name;
    $("#s_ParentName").text(parentName);
    $("#f_ParentId").val(parentId);
    $("#s_ProductBrandId").text("");
    $("#f_Rank").val("0");
    $("#f_ProductBrandName").val("");
    $("#f_Sort").val('0');
    var file = $("#img_titlepic");
    file.after(file.clone().val(''));//清空file类型控件
    file.remove();
    var today = new Date();
    var createDate = formatDate(today,"yyyy-MM-dd HH:mm:ss");
    $("#f_CreateDate").val(createDate);
    var btnSubmit = $("#btnSubmit");
    btnSubmit.attr('value', '新增');
    btnSubmit.css("display","");
}

//用于新增返回时调用同步树节点
function AddNodeById(sourceId,targetId,name,eName)
{
    var parentNode = zTree1.getNodeByParam("id", targetId);
    zTree1.addNodes(parentNode,[{ id:""+sourceId,pid:""+targetId,name:""+name,valueType:""+eName}]);
}

//用于修改时调用同步树节点
function EditNodeById(sourceId,targetId,name,eName)
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
    var product_brand_id=treeNode.id;
    if (treeNode) {
        if (treeNode.childs && treeNode.childs.length > 0) {
            var msg = "要删除的节点是父节点，只允许删除叶节点";
            alert(msg);
        } else {
            $.ajax({
                url:"/default.php?secu=manage&mod=product_brand",
                async: false,
                data:{m:"delete",product_brand_id:product_brand_id},
                dataType:"jsonp",
                jsonp:"jsonpcallback",
                success:function(data){
                    zTree1.removeNode(treeNode);
                    alert("删除结点成功");
                }
            });
        }
    }
}

function zTreeBeforeDrop(treeId, treeNodes, targetNode, moveType) {
    var id = treeNodes[0].id;
    var pId="";
    if(moveType=="inner") pId=targetNode.id; else pId=targetNode.pId;
    $.ajax({
        url:"/default.php?secu=manage&mod=product_brand",
        async: false,
        data:{m:"drag",product_brand_id:id,parent_id:pId,rank:1},
        dataType:"jsonp",
        jsonp:"jsonpcallback",
        success:function(data){
            //根据ajax结果自定义拖拽处理
            if(data['result']>0)
            {
                var parentNode = zTree1.getNodeByParam("id", pId);
                zTree1.removeNode(treeNodes[0]);
                zTree1.addNodes(parentNode,treeNodes[0]);
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
    if($('#f_ProductBrandName').val() == ''){
        message +="请输入品牌名称\r\n";
    }
    if(message!="") alert(message);
    else{
        var productBrandId=$('#s_ProductBrandId').text();
        var methodName=$("#btnSubmit").attr('value');
        var actionUrl="";
        if(methodName=="修改") actionUrl="/default.php?secu=manage&mod=product_brand&m=modify&product_brand_id="+productBrandId;
        else if(methodName=="新增") actionUrl="/default.php?secu=manage&mod=product_brand&m=create";
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
                            <td class="spe_line" ><label for="f_ProductBrandName">名称：</label></td>
                            <td class="spe_line">
                                <input type="text" class="input_box" id="f_ProductBrandName" name="f_ProductBrandName" value="" style=" width: 200px;font-size:14px;" maxlength="50" /><label id="s_ProductBrandId" ></label>
                                <input type="hidden" id="f_SiteId" name="f_SiteId" value="{SiteId}" />
                                <input type="hidden" id="f_CreateDate" name="f_CreateDate" value="" />
                            </td>
                        </tr>
                        <tr>
                            <td class="spe_line">题图：</td>
                            <td class="spe_line">
                                <input id="file_pic" name="file_pic" type="file" class="input_box"
                                       style="width:400px;background:#ffffff;margin-top:3px;"/>
                                <span id="preview_title_pic" class="show_title_pic" idvalue="{LogoUploadFileId}" style="cursor:pointer">[预览]</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="spe_line" ><label for="f_Rank">类型：</label></td>
                            <td class="spe_line">
                                <select id="f_Rank" name="f_Rank">
                                    <option value="0">默认</option>
                                    <option value="6">选项</option>
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