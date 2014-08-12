<?php

/**
 * 后台管理 产品 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Product
 * @author zhangchi
 */
class ProductManageGen extends BaseManageGen implements IBaseManageGen
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
            case "modify":
                $result = self::GenModify();
                break;
            case "remove_to_bin":
                $result = self::GenRemoveToBin();
                break;
        }

        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }

    /**
     * 新增
     * @return string 模板内容页面
     */
    private function GenCreate()
    {
        $tempContent = "";
        $siteId = Control::GetRequest("site_id", 0);
        $manageUserId = Control::GetManageUserId();

        if ($siteId > 0 && $manageUserId > 0) {
            $tempContent = Template::Load("product/product_deal.html", "common");
            parent::ReplaceFirst($tempContent);
            $productManageData = new ProductManageData();

            if (!empty($_POST)) {
                $httpPostData = $_POST;

                //title pic
                $fileElementName = "file_title_pic";
                $tableType = 20; //channel
                $tableId = 0;
                $returnType = 1;
                $uploadFileId1 = 0;
                $titlePicPath = $this->Upload(
                    $fileElementName,
                    $tableType,
                    $tableId,
                    $returnType,
                    $uploadFileId1
                );
                $titlePicPath = str_ireplace("..", "", $titlePicPath);
                if (!empty($titlePicPath)) {
                    sleep(1);
                }

                $productId = $productManageData->Create(
                    $httpPostData,
                    $titlePicPath, $titlePicPath2, $titlePicPath3);
                //加入操作日志
                $operateContent = 'Create Product,POST FORM:'
                    . implode('|', $_POST) . ';\r\nResult:productId:' . $productId;
                self::CreateManageUserLog($operateContent);

                if ($productId > 0) {
                    //授权给创建人
                    if ($manageUserId > 1) { //只有非ADMIN的要授权
                        $manageUserAuthorityManageData = new ManageUserAuthorityManageData();
                        $manageUserAuthorityManageData->CreateForChannel($siteId, $productId, $manageUserId);
                    }

                    //删除缓冲
                    DataCache::RemoveDir(CACHE_PATH . '/product_data');

                    $uploadFileManageData = new UploadFileManageData();
                    //修改题图1的TableId
                    $uploadFileManageData->ModifyTableId($uploadFileId1, $productId);

                    //javascript 处理

                    $closeTab = Control::PostRequest("CloseTab", 0);
                    if ($closeTab == 1) {
                        Control::CloseTab();
                    } else {
                        Control::GoUrl($_SERVER["PHP_SELF"]);
                    }

                } else {
                    Control::ShowMessage(Language::Load('document', 2));
                }

            }

            $tempContent = str_ireplace("{CreateDate}", strval(date('Y-m-d', time())), $tempContent);
            $tempContent = str_ireplace("{SiteId}", strval($siteId), $tempContent);

            $fieldsOfChannel = $productManageData->GetFields();
            parent::ReplaceWhenCreate($tempContent, $fieldsOfChannel);

            $patterns = '/\{s_(.*?)\}/';
            $tempContent = preg_replace($patterns, "", $tempContent);
            parent::ReplaceEnd($tempContent);
        }

        return $tempContent;
    }
} 