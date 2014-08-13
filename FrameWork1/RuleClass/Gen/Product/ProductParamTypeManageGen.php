<?php

/**
 * 后台管理 产品参数类型 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Product
 * @author hy
 */
class ProductParamTypeManageGen extends BaseManageGen implements IBaseManageGen
{

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function Gen()
    {
        $result = "";
        $method = Control::GetRequest("m", "");
        switch ($method) {
            case "create":
                $result = self::GenCreate();
                break;
            case "delete":
                $result = self::AsyncDelete();
                break;
            case "modify":
                $result = self::GenEdit();
                break;
            case "modify_state":
                $result = self::AsyncModifyState();
                break;
            case "list_for_manage_tree":
                $result = self::GenListForManageTree();
                break;
            case "drag":
                $result = self::AsyncDrag();
                break;
            case "one":
                $result = self::AsyncOne();
                break;
        }

        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }

    /**
     * 新增产品参数类型
     * @return mixed|string
     */
    public function GenCreate()
    {
        $tempContent = "";
        $parentId = Control::PostRequest("f_ParentId", "");
        $paramTypeName = Control::PostRequest("f_ParamTypeName", "");
        $paramValueType = Control::PostRequest("f_ParamValueType", "");

        $manageUserId = Control::GetManageUserId();

        if ($parentId > 0 && $manageUserId > 0) {
            if (!empty($_POST)) {
                $httpPostData = $_POST;
                $productParamTypeData = new ProductParamTypeData();
                //title_pic
                $fileElementName = "file_title_pic";
                $tableType = UploadFileData::UPLOAD_TABLE_TYPE_PRODUCT_PARAM_OPTION; //productParamType
                $tableId = 0;
                $uploadResult = new UploadResult();
                $uploadFileId = 0;
                self::Upload(
                    $fileElementName,
                    $tableType,
                    $tableId,
                    $uploadResult,
                    $uploadFileId
                );

                sleep(1);

                $productParamTypeId = $productParamTypeData->Create($httpPostData,$uploadFileId);
                //加入操作日志
                $operateContent = 'Create ProductParamType,POST FORM:'.implode('|',$_POST).';\r\nResult:productParamTypeId:'.$productParamTypeId;
                self::CreateManageUserLog($operateContent);

                if ($productParamTypeId > 0) {
                    $uploadFileData = new UploadFileData();
                    //修改题图的TableId
                    $uploadFileData->ModifyTableID($uploadFileId, $productParamTypeId);

                    Control::ShowMessage(Language::Load('document', 1));
                    $jsCode = 'parent.AddNodeByID("' . $productParamTypeId . '","' . $parentId . '","' . $paramTypeName . '","' . $paramValueType . '");';
                    Control::RunJavascript($jsCode);
                } else {
                    Control::ShowMessage(Language::Load('document', 2));
                }
            }
        }
        return $tempContent;
    }

    /**
     * 删除产品参数类型
     * @return string 删除结果
     */
    public function AsyncDelete()
    {
        $productParamTypeId = Control::GetRequest("product_param_type_id", 0);
        $manageUserId = Control::GetManageUserId();
        if ($productParamTypeId > 0 && $manageUserId > 0) {
            $productParamTypeData = new ProductParamTypeData();
            $result = $productParamTypeData->Delete($productParamTypeId);
            //加入操作日志
            $operateContent = 'Delete ProductParamType,Get FORM:' . implode('|', $_GET) . ';\r\nResult:productParamTypeId:' . $productParamTypeId;
            self::CreateManageUserLog($operateContent);
            return $_GET['jsonpcallback'] . '({"result":"' . $result . '"})';
        } else {
            return $_GET['jsonpcallback'] . '({"result":"-1"})';
        }
    }

    /**
     * 修改产品参数类型
     * @return mixed|string
     */
    public function GenEdit()
    {
        $tempContent = "";
        $parentId = Control::PostRequest("f_ParentId", "");
        $paramTypeName = Control::PostRequest("f_ParamTypeName", "");
        $paramValueType = Control::PostRequest("f_ParamValueType", "");
        $manageUserId = Control::GetManageUserId();
        $productParamTypeId = Control::PostRequest("f_ProductParamTypeId", "");
        if ($productParamTypeId > 0 && $manageUserId > 0) {
            if (!empty($_POST)) {
                $httpPostData = $_POST;
                $productParamTypeData = new ProductParamTypeData();
                //title_pic
                $fileElementName = "file_title_pic";
                $tableType = UploadFileData::UPLOAD_TABLE_TYPE_PRODUCT_PARAM_OPTION; //productParamType
                $tableId = 0;
                $uploadResult = new UploadResult();
                $uploadFileId = 0;
                self::Upload(
                    $fileElementName,
                    $tableType,
                    $tableId,
                    $uploadResult,
                    $uploadFileId
                );

                sleep(1);

                $productParamTypeId = $productParamTypeData->Modify($httpPostData,$productParamTypeId,$uploadFileId);
                //加入操作日志
                $operateContent = 'Create ProductParamType,POST FORM:'.implode('|',$_POST).';\r\nResult:productParamTypeId:'.$productParamTypeId;
                self::CreateManageUserLog($operateContent);
                if ($productParamTypeId > 0) {

                    $uploadFileData = new UploadFileData();
                    //修改题图的TableId
                    $uploadFileData->ModifyTableID($uploadFileId, $productParamTypeId);

                    Control::ShowMessage(Language::Load('document', 3));
                    $jsCode = 'parent.EditNodeByID("' . $productParamTypeId . '","' . $parentId . '","' . $paramTypeName . '","' . $paramValueType . '");';
                    Control::RunJavascript($jsCode);
                } else {
                    Control::ShowMessage(Language::Load('document', 4));
                }
            }
        }
        return $tempContent;
    }

    /**
     * 修改产品参数类型状态
     * @return string 删除结果
     */
    public function AsyncModifyState()
    {
        $productParamTypeId = Control::GetRequest("product_param_type_id", 0);
        $manageUserId = Control::GetManageUserId();
        $state = Control::GetRequest("state", -1);
        if ($productParamTypeId > 0 && $manageUserId>0 && $state >= 0) {
            $productParamTypeData = new ProductParamTypeData();
            $result = $productParamTypeData->ModifyState($productParamTypeId, $state);
            //加入操作日志
            $operateContent = 'ModifyState ProductParamType,Get FORM:' . implode('|', $_GET) . ';\r\nResult:productParamTypeId:' . $productParamTypeId;
            self::CreateManageUserLog($operateContent);
            return $_GET['jsonpcallback'] . '({"result":"' . $result . '"})';
        } else {
            return $_GET['jsonpcallback'] . '({"result":"-1"})';
        }
    }

    /**
     * 产品参数类型管理树页面
     * @return string 返回zTree的JSON数据结构
     */
    public function GenListForManageTree()
    {
        $tempContent = Template::Load("product/product_param_type_list.html");
        $documentChannelId = Control::GetRequest("cid", 0);
        $adminUserId = Control::GetManageUserId();
        if ($documentChannelId > 0 && $adminUserId > 0) {
            $productParamTypeData = new ProductParamTypeData();
            $arrList = $productParamTypeData->GetList($documentChannelId, "", -1);
            $treeNodes = '{ id:0, pId:-1,name:"根节点", valueType:0,open:true},';
            for ($i = 0; $i < count($arrList); $i++) {
                $treeNodes = $treeNodes . '{ id:' . $arrList[$i]["ProductParamTypeID"] . ', pId:' . $arrList[$i]["ParentID"] . ', name:"' . $arrList[$i]["ParamTypeName"] . '", valueType:"' . $arrList[$i]["ParamValueType"] . '"},';
            }
            $treeNodes = substr($treeNodes, 0, strlen($treeNodes) - 1);
            $tempContent = str_ireplace("{treeNodes}", $treeNodes, $tempContent);
            parent::ReplaceEnd($tempContent);
            return $tempContent;
        } else return "";
    }

    /**
     * 获取一行产品参数类型信息
     * @return array 产品参数类型一维数组
     */
    public function AsyncOne()
    {
        $productParamTypeId = Control::GetRequest("product_param_type_id", 0);
        if ($productParamTypeId > 0) {
            $productParamTypeData = new ProductParamTypeData();
            $result = $productParamTypeData->GetOne($productParamTypeId);
            $result = json_encode($result);
            return $_GET['jsonpcallback'] . "(" . $result . ")";
        } else {
            return $_GET['jsonpcallback'] . '([{"result":"0"}])';
        }
    }

    /**
     * 拖动产品参数类型到指定结点
     * @return string 拖动结果
     */
    public function AsyncDrag()
    {
        $productParamTypeId = Control::GetRequest("product_param_type_id", 0);
        $parentId = Control::GetRequest("parent_id", -2);
        if ($productParamTypeId > 0 && $parentId >= 0) {
            $productParamTypeData = new ProductParamTypeData();
            $result = $productParamTypeData->Drag($productParamTypeId, $parentId);
            return $_GET['jsonpcallback'] . '({"result":"' . $result . '"})';
        } else {
            return $_GET['jsonpcallback'] . '({"result":"0"})';
        }
    }

}

?>
