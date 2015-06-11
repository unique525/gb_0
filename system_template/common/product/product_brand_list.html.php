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
{common_head}
<script type="text/javascript" src="/system_js/ztree/jquery.ztree.core-3.0.min.js"></script>
<script type="text/javascript" src="/system_js/ztree/jquery.ztree.excheck-3.0.min.js"></script>
<script type="text/javascript" src="/system_js/ztree/jquery.ztree.exedit-3.0.min.js"></script>
<script type="text/javascript" src="/system_js/xheditor-1.1.14/xheditor-1.1.14-zh-cn.min.js"></script>
<script type="text/javascript" src="/system_js/ajax_file_upload.js"></script>
<script type="text/javascript" src="/system_js/upload_file.js"></script>
<script type="text/javascript" LANGUAGE="javascript">
<!--
//加载品牌简介文本编辑框
var editor;
var tableType = window.UPLOAD_TABLE_TYPE_PRODUCT_BRAND_INTRO;
var tableId = parseInt('{SiteId}');

$(function(){
    if ($.browser.msie) {
        $('input:checkbox').click(function () {
            this.blur();
            this.focus();
        });
    };

    var editorHeight = $(window).height() - 220;
    editorHeight = parseInt(editorHeight);

    var f_ProductBrandIntro = $('#f_ProductBrandIntro');

    editor = f_ProductBrandIntro.xheditor({
        tools:'full',
        height:editorHeight,
        upImgUrl:"",
        upImgExt:"jpg,jpeg,gif,png",
        localUrlTest:/^https?:\/\/[^\/]*?({manage_domain_rex})\//i,
        remoteImgSaveUrl:''
    });

    var btnUploadToContent = $("#btnUploadToContent");
    btnUploadToContent.click(function(){

        var fileElementId = 'file_upload_to_content';
        var attachWatermark = 0;
        var loadingImageId = null;
        var uploadFileId = 0;
        AjaxFileUpload(
            fileElementId,
            tableType,
            tableId,
            loadingImageId,
            $(this),
            attachWatermark,
            uploadFileId
        );
    });

});
//加载品牌树
var zTree1;
var setting;

var zNodes =[{ id:0, pId:-1,name:"品牌",rank:0,open:true},{treeNodes}];
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

var rMenu;
$(document).ready(function(){
    var zTreeHeight = $(window).height() - 80;
    zTreeHeight = parseInt(zTreeHeight);
    $("#treeDemo").css("height",zTreeHeight);
    refreshTree();
    rMenu = document.getElementById("rMenu");
    $("body").bind("mousedown",
        function(event){
            if (!(event.target.id == "rMenu" || $(event.target).parents("#rMenu").length>0)) {
                rMenu.style.visibility = "hidden";
            }
        });
});

function refreshTree() {
    zTree1=$.fn.zTree.init($("#treeDemo"), setting, zNodes);
}

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
            $("#f_ProductBrandIntro").val(data['ProductBrandIntro']);
            $("#f_Sort").val(data['Sort']);
            $("#f_CreateDate").val(data['CreateDate']);
            var file = $("#file_pic");
            file.after(file.clone().val(''));//清空file类型控件
            file.remove();
            //赋于预览控件fileId值
            $("#preview_title_pic").attr("idvalue",data['LogoUploadFileId']);
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
    var rank=selectNode.rank;
    var parentName = selectNode.name;
    $("#s_ParentName").text(parentName);
    $("#f_ParentId").val(parentId);
    $("#s_ProductBrandId").text("");
    $("#f_Rank").val(parseInt(rank)+1);
    $("#f_ProductBrandName").val("");
    $("#f_ProductBrandIntro").val("");
    $("#f_Sort").val('0');
    var file = $("#file_pic");
    file.after(file.clone().val(''));//清空file类型控件
    file.remove();
    $("#preview_title_pic").attr("idvalue","");//清空预览控件fileId值
    var today = new Date();
    var createDate = formatDate(today,"yyyy-MM-dd HH:mm:ss");
    $("#f_CreateDate").val(createDate);
    var btnSubmit = $("#btnSubmit");
    btnSubmit.attr('value', '新增');
    btnSubmit.css("display","");
}

//用于新增返回时调用同步树节点
function AddNodeById(sourceId,targetId,name,rank)
{
    var parentNode = zTree1.getNodeByParam("id", targetId);
    zTree1.addNodes(parentNode,[{ id:""+sourceId,pid:""+targetId,name:""+name,rank:""+rank}]);
}

//用于修改时调用同步树节点
function EditNodeById(sourceId,targetId,name,rank)
{
    var sourceNode = zTree1.getNodeByParam("id", sourceId);
    sourceNode.id=sourceId;
    sourceNode.pId=targetId;
    sourceNode.name=name;
    sourceNode.rank=rank;
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
                data:{m:"async_modify_state",product_brand_id:product_brand_id,state:100},
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
    var rank=0;
    if(moveType=="inner")
    {
        pId=targetNode.id;rank=parseInt(targetNode.rank)+1;
    }
    else {
        pId=targetNode.pId;rank=targetNode.rank;
    }
    $.ajax({
        url:"/default.php?secu=manage&mod=product_brand",
        async: false,
        data:{m:"async_drag",product_brand_id:id,parent_id:pId,rank:rank},
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

//新增和修改返回时回调此方法填充uploadFileId到预览控件上
function FillUploadFileId(uploadFileId)
{
   $("#preview_title_pic").attr("idvalue",uploadFileId);
}

//-->
</script>
</head>
<body>
{common_body_deal}
<div id="rMenu">
    <ul id="m_add" onclick="addTreeNode();"><li>增加节点</li></ul>
    <ul id="m_del" onclick="removeTreeNode();"><li>删除节点</li></ul>
</div>
<form id="mainForm" name="mainForm" action="" method="post"  enctype="multipart/form-data"  target='hidden_frame' >
    <div style="margin:8px;">
        <table border=0 class="tb1" width="100%">
            <tr>
                <td width=380px align=center valign=top>
                    <div style="text-align:left;">
                        <ul id="treeDemo" class="ztree" style="border: 1px #cccccc solid;width:380px;height:100px"></ul>
                    </div>
                </td>
                <td  align=left valign=top style="padding:0 0 0 20px;">
                    <table width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <td class="spe_line" >上级节点名称：</td>
                            <td class="spe_line">
                                <label id="s_ParentName" style=" width: 200px;font-size:14px;"></label>
                                <input type="hidden" id="f_ParentId" name="f_ParentId" value="" />
                                <input type="hidden" id="f_Rank" name="f_Rank" value="" />
                            </td>
                        </tr>
                        <tr>
                            <td class="spe_line" ><label for="f_ProductBrandName">名称：</label></td>
                            <td class="spe_line">
                                <input type="text" class="input_box" id="f_ProductBrandName" name="f_ProductBrandName" value="" style=" width: 200px;font-size:14px;" maxlength="50" /><label id="s_ProductBrandId" style="visibility:hidden"></label>
                                <input type="hidden" id="f_SiteId" name="f_SiteId" value="{SiteId}" />
                                <input type="hidden" id="f_CreateDate" name="f_CreateDate" value="" />
                            </td>
                        </tr>
                        <tr>
                            <td class="spe_line">题图：</td>
                            <td class="spe_line">
                                <input id="file_pic" name="file_pic" type="file" class="input_box"
                                       style="width:400px;background:#ffffff;margin-top:3px;"/>
                                <span id="preview_title_pic" class="show_title_pic" idvalue="" style="cursor:pointer">[预览]</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="spe_line"><label for="f_Sort">排序数字：</label></td>
                            <td class="spe_line"><input type="text" class="input_number" id="f_Sort" name="f_Sort" value="0" style=" width: 100px;font-size:14px;" maxlength="10" /> (输入数字，越大越靠前)</td>
                        </tr>
                        <tr>
                            <td style=" vertical-align:middle"><label for="f_ProductBrandIntro">简介：</label></td>
                            <td>
                                <textarea class="mceEditor" id="f_ProductBrandIntro" name="f_ProductBrandIntro" style=" width: 100%;"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td class="spe_line"></td>
                            <td class="spe_line" align="left">
                                <input id="file_upload_to_content" name="file_upload_to_content" type="file" class="input_box" size="7" style="width:60%; background: #ffffff;" /> <img id="loading" src="/system_template/common/images/loading1.gif" style="display:none;" /><input id="btnUploadToContent" type="button" value="上传" />
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" align="center" style="padding-top:10px">
                                <input type="button" class="btn"  id="btnSubmit" name="btnSubmit" tabindex="0" onclick="sub();" style="display:none" value="" accesskey="s" />
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