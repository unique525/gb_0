<?php

/**
 * 公开访问 频道 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Channel
 * @author zhangchi
 */
class ChannelPublicGen extends BasePublicGen implements IBasePublicGen {
        
    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenPublic() {
        $result = "";

        $action = Control::GetRequest("a", "");
        switch ($action) {
            case "channel":
                $result = self::GenList();
                break;
        }

        $result = str_ireplace("{action}", $action, $result);

        return $result;
    }
    private function GenList() {
        $siteId = parent::GetSiteIdByDomain();
        $channelTemplateTag = Control::GetRequest("channel_id", 0);
        $channelTemplatePublicData = new ChannelTemplatePublicData();
        $tempContent = $channelTemplatePublicData->GetChannelTemplateContentForDynamic($siteId,$channelTemplateTag);
        $tempContent =  parent::ReplaceTemplate($tempContent);
        parent::ReplaceSiteConfig($siteId, $tempContent);
        parent::ReplaceFirst($tempContent);
        parent::ReplaceEnd($tempContent);
        return $tempContent;
    }
}

?>
