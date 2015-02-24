<?php
/**
 * 访问量统计基类 后台
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Visit
 * @author hy
 */
class VisitManageGen extends BaseManageGen implements IBaseManageGen{

    public function Gen()
    {
        $result = "";
        $method = Control::GetRequest("m","");

        switch($method){
            case "statistics":
                $result = self::GenStatistics();
                break;
        }

        return $result;
    }

    private function GenStatistics(){
        $templateContent = Template::Load("visit/visit_statistics.html","common");
        parent::ReplaceFirst($templateContent);
        parent::ReplaceEnd($templateContent);
        return $templateContent;
    }
}