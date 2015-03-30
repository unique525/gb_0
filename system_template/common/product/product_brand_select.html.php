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
    callback:{
        onClick:zTreeOnClick
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
    if(!treeNode.isParent)
    {
    var id=treeNode.id;
    var name=treeNode.name;
        window.parent.$("#s_ProductBrandName").text(name);
        window.parent.$("#f_ProductBrandId").val(id);
        window.parent.$('#dialog_product_brand_select_box').dialog('close');
        return true;
    }
    else{
        return false;
    }
}
//-->
</script>
</head>
<body>
{common_body_deal}
    <div style="margin:8px;">
        <table border=0 class="tb1" width="100%">
            <tr>
                <td width=380px align=center valign=top>
                    <div style="text-align:left;">
                        <ul id="treeDemo" class="ztree" style="border: 1px #cccccc solid;width:380px;height:100px"></ul>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>