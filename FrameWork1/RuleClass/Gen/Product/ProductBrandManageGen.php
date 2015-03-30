<?php

/**
 * 后台管理 产品品牌 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Product
 * @author hy
 */
class ProductBrandManageGen extends BaseManageGen implements IBaseManageGen
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
                $result = self::GenModify();
                break;
            case "async_modify_state":
                $result = self::AsyncModifyState();
                break;
            case "list_for_manage_tree":
                $result = self::GenListForManageTree();
                break;
            case "list_for_select":
                $result = self::GenListForSelect();
                break;
            case "async_drag":
                $result = self::AsyncDrag();
                break;
            case "async_one":
                $result = self::AsyncOne();
                break;
        }

        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }

    /**
     * 新增产品品牌
     * @return mixed|string
     */
    public function GenCreate()
    {
        $tempContent = "";
        $siteId = Control::PostRequest("f_SiteId", "");
        $parentId = Control::PostRequest("f_ParentId", "");
        $productBrandName = Control::PostRequest("f_ProductBrandName", "");
        $rank = Control::PostRequest("f_Rank", "");
        $manageUserId = Control::GetManageUserId();
        if ($parentId >= 0 && $manageUserId > 0) {
            if (!empty($_POST)) {
                $httpPostData = $_POST;
                $productBrandManageData = new ProductBrandManageData();

                $productBrandId = $productBrandManageData->Create($httpPostData);

                //加入操作日志
                $operateContent = 'Create ProductBrand,POST FORM:'.implode('|',$_POST).';\r\nResult:productBrandId:'.$productBrandId;
                self::CreateManageUserLog($operateContent);

                if ($productBrandId > 0) {
                    $uploadFileId = "";
                    if( !empty($_FILES)){
                        //file pic
                        $fileElementName = "file_pic";
                        $tableType = UploadFileData::UPLOAD_TABLE_TYPE_PRODUCT_BRAND;
                        $tableId = $siteId;
                        $uploadFile = new UploadFile();
                        $uploadFileId = 0;
                        $titlePicResult = self::Upload(
                            $fileElementName,
                            $tableType,
                            $tableId,
                            $uploadFile,
                            $uploadFileId
                        );

                        if (intval($titlePicResult) <=0){
                            //上传出错或没有选择文件上传
                        }else{

                        }
                        if($uploadFileId>0){
                            $productBrandManageData->ModifyLogoUploadFileId($productBrandId, $uploadFileId);
                        }
                    }

                    Control::ShowMessage(Language::Load('product', 1));
                    $jsCode = 'parent.AddNodeById("' . $productBrandId . '","' . $parentId . '","' . $productBrandName . '","' . $rank . '");parent.FillUploadFileId('.$uploadFileId.');';
                    Control::RunJavascript($jsCode);
                } else {
                    Control::ShowMessage(Language::Load('product', 2));
                }
            }
        }
        return $tempContent;
    }

    /**
     * 删除产品品牌(软删除)
     * @return string 删除结果
     */
    public function AsyncDelete()
    {
        $state=100;
        $productBrandId = Control::GetRequest("product_brand_id", 0);
        $manageUserId = Control::GetManageUserId();
        if ($productBrandId > 0 && $manageUserId > 0) {
            $productBrandManageData = new ProductBrandManageData();
            $result = $productBrandManageData->ModifyState($productBrandId,$state);
            //加入操作日志
            $operateContent = 'Delete ProductBrand,Get FORM:' . implode('|', $_GET) . ';\r\nResult:productBrandId:' . $productBrandId;
            self::CreateManageUserLog($operateContent);
            return Control::GetRequest("jsonpcallback","") . '({"result":"' . $result . '"})';
        } else {
            return Control::GetRequest("jsonpcallback","") . '({"result":"-1"})';
        }
    }

    /**
     * 修改产品品牌
     * @return mixed|string
     */
    public function GenModify()
    {
        $tempContent = "";
        $siteId = Control::PostRequest("f_SiteId", "");
        $parentId = Control::PostRequest("f_ParentId", "");
        $productBrandName = Control::PostRequest("f_ProductBrandName", "");
        $rank = Control::PostRequest("f_Rank", "");
        $manageUserId = Control::GetManageUserId();
        $productBrandId = Control::GetRequest("product_brand_id", "");
        if ($productBrandId > 0 && $manageUserId > 0) {
            if (!empty($_POST)) {
                $httpPostData = $_POST;
                $productBrandManageData = new ProductBrandManageData();
                $result = $productBrandManageData->Modify($httpPostData,$productBrandId);

                //加入操作日志
                $operateContent = 'Modify ProductBrand,POST FORM:'.implode('|',$_POST).';\r\nResult:productBrandId:'.$productBrandId;
                self::CreateManageUserLog($operateContent);

                if ($result > 0) {
                    $uploadFileId = "";
                    if( !empty($_FILES)){
                        //file pic
                        $fileElementName = "file_pic";
                        $tableType = UploadFileData::UPLOAD_TABLE_TYPE_PRODUCT_BRAND;
                        $tableId = $siteId;
                        $uploadFile = new UploadFile();
                        $uploadFileId = 0;
                        $titlePic1Result = self::Upload(
                            $fileElementName,
                            $tableType,
                            $tableId,
                            $uploadFile,
                            $uploadFileId
                        );

                        if (intval($titlePic1Result) <=0 && $uploadFileId<=0){
                            //上传出错或没有选择文件上传
                        }else{
                            //删除原有题图
                            $oldUploadFileId = $productBrandManageData->GetLogoUploadFileId($productBrandId, false);
                            parent::DeleteUploadFile($oldUploadFileId);

                            //修改题图
                            $productBrandManageData->ModifyLogoUploadFileId($productBrandId, $uploadFileId);
                        }
                    }
                    //javascript 处理
                    Control::ShowMessage(Language::Load('product', 3));
                    $jsCode = 'parent.EditNodeById("' . $productBrandId . '","' . $parentId . '","' . $productBrandName . '","' . $rank . '");parent.FillUploadFileId('.$uploadFileId.');';
                    Control::RunJavascript($jsCode);
                } else {
                    Control::ShowMessage(Language::Load('product', 4));
                }
            }
        }
        return $tempContent;
    }

    /**
     * 修改产品品牌状态
     * @return string 修改结果
     */
    public function AsyncModifyState()
    {
        $productBrandId = Control::GetRequest("product_brand_id", 0);
        $manageUserId = Control::GetManageUserId();
        $state = Control::GetRequest("state", 100);
        if ($productBrandId > 0 && $manageUserId>0 && $state >= 0) {
            $productBrandManageData = new ProductBrandManageData();
            $result = $productBrandManageData->ModifyState($productBrandId, $state);
            //加入操作日志
            $operateContent = 'ModifyState ProductBrand,Get FORM:' . implode('|', $_GET) . ';\r\nResult:productBrandId:' . $productBrandId;
            self::CreateManageUserLog($operateContent);
            return Control::GetRequest("jsonpcallback","") . '({"result":"' . $result . '"})';
        } else {
            return Control::GetRequest("jsonpcallback","") . '({"result":"-1"})';
        }
    }

    /**
     * 产品品牌管理树页面
     * @return string 返回zTree的JSON数据结构
     */
    public function GenListForManageTree()
    {
        $tempContent = Template::Load("product/product_brand_list.html", "common");
        $siteId = Control::GetRequest("site_id", 0);
        $adminUserId = Control::GetManageUserId();
        if ($siteId > 0 && $adminUserId > 0) {
            $productBrandManageData = new ProductBrandManageData();
            $arrList = $productBrandManageData->GetList($siteId, "", -1);
            $treeNodes = "";
            for ($i = 0; $i < count($arrList); $i++) {
                $treeNodes = $treeNodes . '{ id:' . $arrList[$i]["ProductBrandId"] . ', pId:' . $arrList[$i]["ParentId"] . ', name:"' . $arrList[$i]["ProductBrandName"] . '", rank:"' . $arrList[$i]["Rank"] . '"},';
            }
            $treeNodes = substr($treeNodes, 0, strlen($treeNodes) - 1);
            $tempContent = str_ireplace("{treeNodes}", $treeNodes, $tempContent);
            $tempContent = str_ireplace("{SiteId}", $siteId, $tempContent);
            parent::ReplaceEnd($tempContent);
            $tempContent = str_ireplace("{ResultJavascript}", "", $tempContent);
            return $tempContent;
        } else return "";
    }

    /**
     * 产品品牌选择页面
     * @return string 返回zTree的JSON数据结构
     */
    public function GenListForSelect()
    {
        $tempContent = Template::Load("product/product_brand_select.html", "common");
        $siteId = Control::GetRequest("site_id", 0);
        $adminUserId = Control::GetManageUserId();
        if ($siteId > 0 && $adminUserId > 0) {
            $productBrandManageData = new ProductBrandManageData();
            $arrList = $productBrandManageData->GetList($siteId, "", -1);
            $treeNodes = "";
            for ($i = 0; $i < count($arrList); $i++) {
                $treeNodes = $treeNodes . '{ id:' . $arrList[$i]["ProductBrandId"] . ', pId:' . $arrList[$i]["ParentId"] . ', name:"' . $arrList[$i]["ProductBrandName"] . '", rank:"' . $arrList[$i]["Rank"] . '"},';
            }
            $treeNodes = substr($treeNodes, 0, strlen($treeNodes) - 1);
            $tempContent = str_ireplace("{treeNodes}", $treeNodes, $tempContent);
            $tempContent = str_ireplace("{SiteId}", $siteId, $tempContent);
            parent::ReplaceEnd($tempContent);
            $tempContent = str_ireplace("{ResultJavascript}", "", $tempContent);
            return $tempContent;
        } else return "";
    }

    /**
     * 获取一行产品品牌信息
     * @return array 产品品牌一维数组
     */
    public function AsyncOne()
    {
        $productBrandId = Control::GetRequest("product_brand_id", 0);
        if ($productBrandId > 0) {
            $productBrandManageData = new ProductBrandManageData();
            $result = $productBrandManageData->GetOne($productBrandId);
            $result = json_encode($result);
            return Control::GetRequest("jsonpcallback","") . "(" . $result . ")";
        } else {
            return Control::GetRequest("jsonpcallback","") . '([{"result":"0"}])';
        }
    }

    /**
     * 拖动产品品牌到指定结点
     * @return string 拖动结果
     */
    public function AsyncDrag()
    {
        $productBrandId = Control::GetRequest("product_brand_id", 0);
        $parentId = Control::GetRequest("parent_id", 0);
        $rank= Control::GetRequest("rank", 0);
        if ($productBrandId > 0 && $parentId >= 0) {
            $productBrandManageData = new ProductBrandManageData();
            $result = $productBrandManageData->Drag($productBrandId, $parentId, $rank);
            return Control::GetRequest("jsonpcallback","") . '({"result":"' . $result . '"})';
        } else {
            return Control::GetRequest("jsonpcallback","") . '({"result":"0"})';
        }
    }

}

?>
