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
            case "default":
                $result = self::GenDefault();
                break;
            case "list":
                $result = self::GenList();
                break;
        }

        $result = str_ireplace("{action}", $action, $result);

        return $result;
    }

    private function GenDefault() {

        $siteId = parent::GetSiteIdByDomain();
        $tempContent = parent::GetDynamicTemplateContent();
        parent::ReplaceFirst($tempContent);

        $channelId = Control::GetRequest("channel_id",0);
        $channelPublicData = new ChannelPublicData();
        $currentChannelName = $channelPublicData->GetChannelName($channelId,true);
        $tempContent = str_ireplace("{CurrentChannelName}", $currentChannelName, $tempContent);


        $tempContent =  parent::ReplaceTemplate($tempContent);
        //parent::ReplaceSiteConfig($siteId, $tempContent);
        parent::ReplaceSiteInfo($siteId, $tempContent);
        parent::ReplaceEnd($tempContent);
        return $tempContent;
    }

    private function GenList() {
        $siteId = parent::GetSiteIdByDomain();
        $channelId = Control::GetRequest("channel_id",0);
        $tempContent = parent::GetDynamicTemplateContent();
        $tempContent = str_ireplace("{ChannelId}", $channelId, $tempContent);
        parent::ReplaceFirst($tempContent);

        $channelPublicData = new ChannelPublicData();
        $currentChannelName = $channelPublicData->GetChannelName($channelId,true);
        $tempContent = str_ireplace("{CurrentChannelName}", $currentChannelName, $tempContent);
        $tempContent = str_ireplace("{ChannelName}", $currentChannelName, $tempContent);

        $tempContent =  parent::ReplaceTemplate($tempContent);

        parent::ReplaceSiteInfo($siteId, $tempContent);
        parent::ReplaceEnd($tempContent);
        return $tempContent;
    }
}
?>
