<?php
/**
 * 访问量统计基类 后台
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Visit
 * @author hy
 */
class VisitManageGen extends BaseManageGen implements IBaseManageGen{

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function Gen() {
        $result = "";
        $method = Control::GetRequest("m", "");
        switch ($method) {
            case "count_by_site":       //按站点统计
                $result = self::GenVisitCountBySite();
                break;
            case "count_part":       //按栏目统计
                $result = self::GenVisitCountPart();
                break;
            case "count_document":       //按文档统计
                $result = self::GenVisitCountDocument();
                break;
            case "count_source":       //按域名来路统计
                $result = self::GenVisitCountSource();
                break;
            case "count_ip":       //按ip地区来路统计
                $result = self::GenVisitCountIp();
                break;
            case "ip_location":       //IP分布解释成地区批量处理
                $result = self::GenIpLocation();
                break;
            case "visit_count_user":       //按账号进行流量考核统计
                $result = self::GenVisitCountUser();
                break;
            case "document_list":       //按文档访问详细统计列表
                $result = self::GenVisitDocumentList();
                break;
            case "visit_count":       //按统计分类引导
                $result = self::GenVisitCountAll();
                break;
        }

        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }

    private function GenVisitCountBySite(){
        $manageUserId = Control::GetManageUserId();
        $siteId = Control::GetRequest("siteid", 0);
        $templateContent = Template::Load("visit/visit_statistics.html","common");
        if($manageUserId > 0 && $siteId > 0){
            parent::ReplaceFirst($templateContent);


            parent::ReplaceEnd($templateContent);
        }
        return $templateContent;
    }
    private function GenStatistics(){
        $templateContent = Template::Load("visit/visit_statistics.html","common");
        parent::ReplaceFirst($templateContent);
        parent::ReplaceEnd($templateContent);
        return $templateContent;
    }
}