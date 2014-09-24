<?php

/**
 * 后台管理 来源 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Source
 * @author zhangchi
 */
class SourceManageGen extends BaseManageGen implements IBaseManageGen {
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
            case "modify":
                $result = self::GenModify();
                break;
            case "remove_to_bin":
                $result = self::GenRemoveToBin();
                break;
            case "list":
                $result = self::GenList();
                break;
            case "select":
                $result = self::GenSelect();
                break;
            case "search":
                $result = self::GenSearch();
                break;
            case "async_modify_state":
                $result = self::AsyncModifyState();
                break;
        }

        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }

    /**
     * 选择来源
     */
    private function GenSelect() {
        $tempContent = Template::Load("source/source_select.html", "common");
        parent::ReplaceFirst($tempContent);

        $sourceManageData = new SourceManageData();

        //source_all
        $arrList = $sourceManageData->GetListForSelect();
        $listName = "source_all";
        if (count($arrList) > 0) {
            Template::ReplaceList($tempContent, $arrList, $listName);
        }
        //source_1
        $listName = "source_1";
        $arrList = $sourceManageData->GetListForSelect("本地稿源");
        if (count($arrList) > 0) {
            Template::ReplaceList($tempContent, $arrList, $listName);
        }
        //source_2
        $listName = "source_2";
        $arrList = $sourceManageData->GetListForSelect("中央重点网站");
        if (count($arrList) > 0) {
            Template::ReplaceList($tempContent, $arrList, $listName);
        }
        //source_3
        $listName = "source_3";
        $arrList = $sourceManageData->GetListForSelect("中央新闻单位");
        if (count($arrList) > 0) {
            Template::ReplaceList($tempContent, $arrList, $listName);
        }
        //source_4
        $listName = "source_4";
        $arrList = $sourceManageData->GetListForSelect("地方新闻网站");
        if (count($arrList) > 0) {
            Template::ReplaceList($tempContent, $arrList, $listName);
        }
        //source_5
        $listName = "source_5";
        $arrList = $sourceManageData->GetListForSelect("城市网盟");
        if (count($arrList) > 0) {
            Template::ReplaceList($tempContent, $arrList, $listName);
        }
        //source_6
        $listName = "source_6";
        $arrList = $sourceManageData->GetListForSelect("地方重点报刊");
        if (count($arrList) > 0) {
            Template::ReplaceList($tempContent, $arrList, $listName);
        }
        //source_7
        $listName = "source_7";
        $arrList = $sourceManageData->GetListForSelect("频道常用稿源");
        if (count($arrList) > 0) {
            Template::ReplaceList($tempContent, $arrList, $listName);
        }

        parent::ReplaceEnd($tempContent);
        return $tempContent;
    }

    private function GenSearch(){
        $searchKey = Control::GetRequest("search_key", "");

        if(strlen($searchKey)>0){

            $sourceData = new SourceData();
            $arrList = $sourceData->GetListBySearchkey($searchKey);
            if(count($arrList)>0){
                for($i=0;$i<count($arrList);$i++){
                    $result = $result.'<div style="float:left;width:25%;"><span style="cursor: pointer;" onclick="self.parent.setsourcename(\''.$arrList[$i]['SourceName'].'\');self.parent.tb_remove();">'.$arrList[$i]['SourceName'].'</span></div>';
                }
            }
        }
        return $result;
    }
} 