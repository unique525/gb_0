<?php
/**
 * Created by PhpStorm.
 * User: 525
 * Date: 14-6-5
 * Time: 下午1:34
 */
class SiteAdContentPublicGen extends BasePublicGen implements IBasePublicGen {



    /**
     * 虚拟点击记录缓冲文件路径
     */
    const VIRTUAL_CLICK_CACHE_FILE_PATH = "/cache/site_ad_virtual_click/";



    /**
     * 广告点击记录添加：成功
     */
    const INSERT_AD_LOG_DATA_SUCCESS = 1;
    /**
     * 广告点击记录添加：失败
     */
    const INSERT_AD_LOG_DATA_FAILURE = -1;
    /**
     * 广告 site_content_id 错误
     */
    const FALSE_SITE_AD_CONTENT_ID = -2;
    /**
     * 广告点击统计未开启
     */
    const OPEN_COUNT_IS_OFF = -3;
    /**
     * 广告点击统计未开启
     */
    const GET_INFO_OF_VIRTUAL_CLICK_FAILURE = -4;


    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenPublic() {
        $result = "";
        $method = Control::GetRequest("m", "");
        switch ($method) {
            case "site_ad_click":
                $result = self::GenAddSiteAdClick();
                break;
            case "site_ad_virtual_click":
                $result = self::GenAddSiteAdVirtualClick();
                break;
        }
        $replace_arr = array(
            "{method}" => $method
        );
        $result = strtr($result, $replace_arr);
        return $result;
    }

    /**
     * 记录广告点击
     * @return string
     */
    private function GenAddSiteAdClick() {
        $result="";
        if (!empty($_GET)) {
            $siteAdContentId = intval(Control::GetRequest("id", "0"));
            if ($siteAdContentId > 0) {
                $siteAdContentPublicData=new SiteAdContentPublicData();
                $openCount = $siteAdContentPublicData->GetOpenCount($siteAdContentId);
                if($openCount>0){
                    $createDate = date("Y-m-d H:i:s", time());
                    $ip = Control::GetIP();
                    $agent = Control::GetOS();
                    $agent = $agent . "与" . Control::GetBrowser();
                    $referenceUrl = $_SERVER['HTTP_REFERER'];                                                                 //来路url
                    $referenceDomain = strtolower(preg_replace("/https?:\/\/([^\:\/]+).*/i", "\\1", $referenceUrl));                //来路域名
                    $isVirtualClick = 0;  //1为虚拟点击,其他为正常点击
                    $adLogPublicData = new SiteAdLogPublicData();
                    $insertId = $adLogPublicData->InsertData($siteAdContentId, $createDate, $ip, $agent, $referenceDomain, $referenceUrl, $isVirtualClick);
                    if ($insertId > 0) {
                        $result.=abs(DefineCode::SITE_AD_LOG_PUBLIC)+self::INSERT_AD_LOG_DATA_SUCCESS;//广告点击记录添加：成功
                    } else {
                        $result.=DefineCode::SITE_AD_LOG_PUBLIC+self::INSERT_AD_LOG_DATA_FAILURE;//广告点击记录添加：失败
                    }
                }else{
                    $result.=DefineCode::SITE_AD_LOG_PUBLIC+self::OPEN_COUNT_IS_OFF;//广告点击统计未开启
                }
            }else{
                $result.=DefineCode::SITE_AD_LOG_PUBLIC+self::FALSE_SITE_AD_CONTENT_ID;//广告 site_content_id 错误
            }
            if (isset($_GET['jsonpcallback'])) {
                echo Control::GetRequest("jsonpcallback","") . '([{ReCommon:"' . $result . '"}])';
            }
        }
        return $result;
    }

    /**
     * 统计虚拟点击
     * @return string
     */
    private function GenAddSiteAdVirtualClick() {
        $result = "";
        if (!empty($_GET)){

            $siteAdContentId = intval(Control::GetRequest("id", "0"));
            if($siteAdContentId > 0) {
                $referenceUrl = $_SERVER['HTTP_REFERER'];                                                                 //来路url
                $referenceDomain = strtolower(preg_replace("/https?:\/\/([^\:\/]+).*/i", "\\1", $referenceUrl));                //来路域名
                $siteAdContentPublicData = new SiteAdContentPublicData();
                $vClickInfo=$siteAdContentPublicData->GetVirtualClickInfo($siteAdContentId,true);
                if(count($vClickInfo)>0&&$vClickInfo!=null){
                    $openVClick=$vClickInfo["OpenVirtualClick"];

                if (intval($openVClick) === 1) {
                    $addVCount=0;  //是否新增虚拟点击纪录  初始为0  取缓冲符合条件后改为1

                    $hourSection = date("H", time());
                    $VClickLimit = $vClickInfo["VirtualClickLimit"];
                    $hourInCache=$hourSection;  //如果没有缓冲或者缓冲为空  设置默认值:当前小时, 0点击
                    $clickInCache=0;
                    $filePath=self::VIRTUAL_CLICK_CACHE_FILE_PATH;
                    $fileName="site_ad_content_virtual_click_log_of_hour_".$siteAdContentId."_";
                    $cacheContent=parent::GetCache($filePath,$fileName);   //取缓冲中虚拟点击状态   小时_点击数
                    if($cacheContent&&$cacheContent!=""){
                        $arrayOfVClickState=mb_split("_",$cacheContent);
                        if($arrayOfVClickState){
                            $hourInCache=$arrayOfVClickState[0];
                            $clickInCache=intval($arrayOfVClickState[1]);
                        }
                    }
                    if(intval($hourSection)==intval($hourInCache)){  //小时不对 则取当前小时  点击数为1
                        if($clickInCache<$VClickLimit){
                            $addVCount=1;
                            $clickInCache++;
                        }
                    }else{
                        $addVCount=1;
                        $clickInCache=1;
                    }
                    $newCacheContent=$hourSection."_".strval($clickInCache);
                    parent::AddCache($filePath,$fileName,$newCacheContent,3600);



                    if ($addVCount==1) {   //初始为0，符合条件改为1  新增纪录
                        $createDate = date("Y-m-d H:i:s", time());
                        $ip = Control::GetIP();
                        $agent = Control::GetOS();
                        $agent = $agent . "与" . Control::GetBrowser();
                        $isVirtualClick = 1;  //1为虚拟点击,其他为正常点击
                        $adLogPublicData = new SiteAdLogPublicData();
                        $insertId = $adLogPublicData->InsertData($siteAdContentId, $createDate, $ip, $agent, $referenceDomain, $referenceUrl, $isVirtualClick);
                        if ($insertId > 0) {
                            $result.=abs(DefineCode::SITE_AD_LOG_PUBLIC)+self::INSERT_AD_LOG_DATA_SUCCESS;//广告点击记录添加：成功
                        } else {
                            $result.=DefineCode::SITE_AD_LOG_PUBLIC+self::INSERT_AD_LOG_DATA_FAILURE;//广告点击记录添加：失败
                        }
                    }
                }
                }else{
                    $result.=DefineCode::SITE_AD_LOG_PUBLIC+self::GET_INFO_OF_VIRTUAL_CLICK_FAILURE;//获取虚拟点击信息：失败
                }
            }else{
                $result.=DefineCode::SITE_AD_LOG_PUBLIC+self::FALSE_SITE_AD_CONTENT_ID;//广告 site_content_id 错误
            }
            if (isset($_GET['jsonpcallback'])) {
                return Control::GetRequest("jsonpcallback","") . '([{ReCommon:"' . $result . '"}])';
            }
        }
    }

}

?>