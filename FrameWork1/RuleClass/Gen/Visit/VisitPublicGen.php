<?php

/**
 * 访问量统计基类 前台
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Visit
 * @author hy
 */
class VisitGen extends BasePublicGen implements IBasePublicGen
{

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenPublic()
    {
    }

    /**
     * 主生成方法(继承接口)
     * @return string 返回输出的HTML内容
     */
    public function GenFront()
    {
        $result = "";
        $method = Control::GetRequest("m", "");
        switch ($method) {
            case "create": //统计访问量
                $result = self::GenCreate();
                break;
        }

        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }

    private function GenCreate()
    {
        if (!empty($_GET)) {
            $siteId = intval(Control::GetRequest("site_id", "0"));
            $channelId = intval(Control::GetRequest("channel_id", "0"));
            $tableType = Control::GetRequest("table_type", "0");
            $tableId = Control::GetRequest("table_id", "0");
            $visitTitle = urldecode(Control::GetRequest("title", ""));
            $visitTag = urldecode(Control::GetRequest("tag", ""));
            $visitUrl = urldecode(Control::GetRequest("url", ""));
            $refUrl = urldecode(Control::GetRequest("ref_url", ""));

            $createDate = date("Y-m-d H:i:s", time());
            $ipAddress = Control::GetIP();
            $agent = Control::GetOS();
            $agent = $agent . "与" . Control::GetBrowser();
            if (strlen($refUrl) < 5) {
                $refUrl = $_SERVER['HTTP_REFERER']; //来路url
            }
            $refDomain = strtolower(preg_replace('/https?:\/\/([^\:\/]+).*/i', "\\1", $refUrl)); //来路域名
            $visitPublicData = new VisitPublicData();
            $insertId = $visitPublicData->Create($siteId, $channelId, $tableType, $tableId, $createDate, $visitTitle, $visitTag, $visitUrl, $ipAddress, $agent, $refDomain, $refUrl);
            if ($insertId > 0) {
                $reCommon = 1;
            } else {
                $reCommon = -1;
            }
            if (isset($_GET)) {
                echo Control::GetRequest("jsonpcallback", "") . '([{reCommon:"' . $reCommon . '"}])';
                return "";
            }
        }
        return "";
    }

}

?>
