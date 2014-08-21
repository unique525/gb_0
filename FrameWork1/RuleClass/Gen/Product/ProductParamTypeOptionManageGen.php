<?php

/**
 * 后台管理 产品参数类型选项 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Product
 * @author hy
 */
class ProductParamTypeOptionManageGen extends BaseManageGen implements IBaseManageGen
{

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function Gen() {
        $result = "";
        $method = Control::GetRequest("m", "");
        switch ($method) {
            case "create":
                $result = self::GenCreate();
                break;
            case "delete":
                $result = self::GenDelete();
                break;
            case "modify":
                $result = self::GenModify();
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
     * 新增产品参数类型选项
     * @return mixed|string
     */
    public function GenCreate()
    {
        $tempContent = "";
        $parentId = Control::PostRequest("f_ParentId", "");
        $productParamTypeId = Control::PostRequest("f_ProductParamTypeId", "");
        $name = Control::PostRequest("f_OptionName", "");
        $eName = Control::PostRequest("f_OptionName2", "");

        $manageUserId = Control::GetManageUserId();

        if ($parentId >= 0 && $productParamTypeId>0 && $manageUserId > 0) {

            if (!empty($_POST)) {
                $httpPostData = $_POST;
                $productParamTypeOptionManageData = new ProductParamTypeOptionManageData();
                //title_pic
                $fileElementName = "file_title_pic";
                $tableType = UploadFileData::UPLOAD_TABLE_TYPE_PRODUCT_PARAM_TYPE_OPTION; //productParamType
                $tableId = 0;
                $uploadResult = new UploadResult();
                $titlePicUploadFileId = 0;
                self::Upload(
                    $fileElementName,
                    $tableType,
                    $tableId,
                    $uploadResult,
                    $titlePicUploadFileId
                );

                sleep(1);

                $productParamTypeOptionId = $productParamTypeOptionManageData->Create($httpPostData, $titlePicUploadFileId);
                //加入操作日志
                $operateContent = 'Create ProductParamTypeOption,POST FORM:' . implode('|', $_POST) . ';\r\nResult:productParamTypeOptionId:' . $productParamTypeOptionId;
                self::CreateManageUserLog($operateContent);

                if ($productParamTypeOptionId > 0) {
                    $uploadFileData = new UploadFileData();
                    //修改题图的TableId
                    $uploadFileData->ModifyTableId($titlePicUploadFileId, $productParamTypeOptionId);

                    Control::ShowMessage(Language::Load('document', 1));
                    $jsCode = 'parent.AddNodeById("' . $productParamTypeOptionId . '","' . $parentId . '","' . $productParamTypeId . '","' . $name . '","' . $eName . '");';
                    Control::RunJavascript($jsCode);
                } else {
                    Control::ShowMessage(Language::Load('document', 2));
                }
            }
        }
        return $tempContent;
    }

    /**
     * 删除产品参数类型选项(软删除)
     * @return string 删除结果
     */
    public function GenDelete()
    {
        $state=100;
        $productParamTypeOptionId = Control::GetRequest("product_param_type_option_id", 0);
        if ($productParamTypeOptionId > 0) {
            $productParamTypeOptionManageData = new ProductParamTypeOptionManageData();
            $result = $productParamTypeOptionManageData->ModifyState($productParamTypeOptionId,$state);
            return Control::GetRequest("jsonpcallback","") . '({"result":"' . $result . '"})';
        } else {
            return Control::GetRequest("jsonpcallback","") . '({"result":"0"})';
        }
    }

    /**
     * 修改产品参数类型选项
     * @return mixed|string
     */
    public function GenModify()
    {
        $tempContent = "";
        $parentId = Control::PostRequest("f_ParentId", "");
        $productParamTypeId = Control::PostRequest("f_ProductParamTypeId", "");
        $name = Control::PostRequest("f_OptionName", "");
        $eName = Control::PostRequest("f_OptionName2", "");
        $productParamTypeOptionId = Control::PostRequest("f_ProductParamTypeOptionId", "");

        $manageUserId = Control::GetManageUserId();

        if ($productParamTypeOptionId > 0 && $manageUserId > 0) {
            if (!empty($_POST)) {
                $httpPostData = $_POST;
                $productParamTypeOptionManageData = new ProductParamTypeOptionManageData();

                //title_pic
                $fileElementName = "file_title_pic";
                $tableType = UploadFileData::UPLOAD_TABLE_TYPE_PRODUCT_PARAM_TYPE_OPTION; //productParamType
                $tableId = 0;
                $uploadResult = new UploadResult();
                $titlePicUploadFileId = 0;
                self::Upload(
                    $fileElementName,
                    $tableType,
                    $tableId,
                    $uploadResult,
                    $titlePicUploadFileId
                );

                sleep(1);

                $result = $productParamTypeOptionManageData->Modify($httpPostData,$productParamTypeOptionId,$titlePicUploadFileId);
                //加入操作日志
                $operateContent = 'Modify ProductParamTypeOption,POST FORM:'.implode('|',$_POST).';\r\nResult:productParamTypeOptionId:'.$productParamTypeOptionId;
                self::CreateManageUserLog($operateContent);
                if ($result > 0) {

                    $uploadFileData = new UploadFileData();
                    //修改题图的TableId
                    $uploadFileData->ModifyTableId($titlePicUploadFileId, $productParamTypeOptionId);

                    Control::ShowMessage(Language::Load('document', 3));
                    $jsCode = 'parent.EditNodeById("' . $productParamTypeOptionId . '","' . $parentId . '","' . $productParamTypeId . '","' . $name . '","' . $eName . '");';
                    Control::RunJavascript($jsCode);
                } else {
                    Control::ShowMessage(Language::Load('document', 4));
                }
            }
        }
        return $tempContent;
    }

    /**
     * 修改产品参数类型选项状态
     * @return string 修改结果
     */
    public function AsyncModifyState() {
        $productParamTypeOptionId = Control::GetRequest("id", 0);
        $state = Control::GetRequest("state", -1);
        if ($productParamTypeOptionId > 0 && $state >= 0) {
            $productParamTypeOptionManageData = new ProductParamTypeOptionManageData();
            $result = $productParamTypeOptionManageData->ModifyState($productParamTypeOptionId, $state);
            return $result;
        } else {
            return -1;
        }
    }

    /**
     * 产品参数类型选项管理树页面
     * @return string 返回zTree的JSON数据结构
     */
    public function GenListForManageTree()
    {

        $tempContent = Template::Load("product/product_param_type_option_list.html", "common");
        $product_param_type_id = Control::GetRequest("product_param_type_id", 0);
        $productParamTypeId = Control::GetRequest("product_param_type_id", 0);
        $adminUserId = Control::GetManageUserId();
        if ($product_param_type_id > 0 && $adminUserId > 0) {
            $productParamTypeOptionManageData = new ProductParamTypeOptionManageData();
            $arrList = $productParamTypeOptionManageData->GetList($productParamTypeId, "", -1);
            $treeNodes = '{ id:0, pId:-1,productParamTypeId:-1,name:"根节点", eName:"根节点",open:true},';
            for ($i = 0; $i < count($arrList); $i++) {
                $treeNodes = $treeNodes . '{ id:' . $arrList[$i]["ProductParamTypeOptionId"] . ', pId:' . $arrList[$i]["ParentId"] . ', productParamTypeId:' . $arrList[$i]["ProductParamTypeId"] . ', name:"' . $arrList[$i]["OptionName"] . '", eName:"' . $arrList[$i]["OptionName2"] . '"},';
            }
            $treeNodes = substr($treeNodes, 0, strlen($treeNodes) - 1);
            $tempContent = str_ireplace("{treeNodes}", $treeNodes, $tempContent);
            parent::ReplaceEnd($tempContent);
            $result = $tempContent;
            return $result;
        } else return "";
    }


    /**
     * 获取一行产品参数类型选项信息
     * @return array 产品参数类型选项一维数组
     */
    public function AsyncOne() {
        $productParamTypeOptionId = Control::GetRequest("product_param_type_option_id", -2);
        if ($productParamTypeOptionId > 0) {
            $productParamTypeOptionManageData = new ProductParamTypeOptionManageData();
            $result = $productParamTypeOptionManageData->GetOne($productParamTypeOptionId);
            $result = json_encode($result);
            return Control::GetRequest("jsonpcallback","") . "(" . $result . ")";
        } else {
            return Control::GetRequest("jsonpcallback","") . '({"result":"0"})';
        }
    }

    /**
     * 拖动产品参数类型选项到指定结点
     * @return string 拖动结果
     */
    public function AsyncDrag() {
        $productParamTypeOptionId = Control::GetRequest("product_param_type_option_id", 0);
        $parentId = Control::GetRequest("parent_id", 0);
        if ($productParamTypeOptionId > 0 && $parentId >= 0) {
            $productParamTypeOptionManageData = new ProductParamTypeOptionManageData();
            $result = $productParamTypeOptionManageData->Drag($productParamTypeOptionId, $parentId);
            //$result=json_encode($result);
            return Control::GetRequest("jsonpcallback","") . '({"result":"' . $result . '"})';
        } else {
            return Control::GetRequest("jsonpcallback","") . '({"result":"0"})';
        }
    }

}

?>
