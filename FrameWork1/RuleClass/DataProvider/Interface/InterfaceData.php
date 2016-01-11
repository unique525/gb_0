<?php
/**
 * 外部接口 数据类
 *
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Interface
 * @author 525
 */
class InterfaceData extends BaseData{
    /**
     * 获取分页列表
     * @param int $publishApiUrl 外部接口url
     * @param int $jsonType 数据格式（icms ,icmsII）
     * @param int $useOriUrl 是否将原始地址作为直接转向
     * @return array 活动数据集
     */
    public function GetList($publishApiUrl, $jsonType, $useOriUrl) {
        $result=array();

            switch ($jsonType) {
                case "default": //icms 2
                    if (!empty($publishApiUrl)) {
                        $jsonContent = file_get_contents($publishApiUrl);
                        if (substr($jsonContent, 0, 1) == "(") {
                            $jsonContent = substr($jsonContent, 1);
                        }
                        if (substr($jsonContent, strlen($jsonContent) - 1, strlen($jsonContent)) == ")") {
                            $jsonContent = substr($jsonContent, 0, strlen($jsonContent) - 1);
                        }
                        if (preg_match('/^\xEF\xBB\xBF/', $jsonContent)) {
                            $jsonContent = substr($jsonContent, 3);
                        }
                        $result = json_decode($jsonContent, TRUE);
                        if(count($result)>0){
                            foreach($result as $documentNews){
                                $publishDate=Format::DateStringToSimple($documentNews['PublishDate']);
                                $documentNews['OriUrl'] =
                                    '/h/'.$documentNews['ChannelId'].
                                    '/'.$publishDate.
                                    '/'.$documentNews['DocumentNewsId'].'.html';

                                if($useOriUrl>0){
                                    $documentNews['DirectUrl']=$documentNews['OriUrl'];
                                }
                            }

                            $result = $result["result_list"];
                        }
                    }

                    break;


                case "icms1": //icms 1
                    if (!empty($publishApiUrl)) {
                        $jsonContent = file_get_contents($publishApiUrl);
                        $jsonContent=trim($jsonContent);
                        if (substr($jsonContent, 0, 1) == "(") {
                            $jsonContent = substr($jsonContent, 1);
                        }
                        if (substr($jsonContent, strlen($jsonContent) - 1, strlen($jsonContent)) == ")") {
                            $jsonContent = substr($jsonContent, 0, strlen($jsonContent) - 1);
                        }
                        if (preg_match('/^\xEF\xBB\xBF/', $jsonContent)) {
                            $jsonContent = substr($jsonContent, 3);
                        }

                        /** 字段格式化 */

                        $jsonContent = str_ireplace('"documentnewstitle"', '"DocumentNewsTitle"', $jsonContent);
                        $jsonContent = str_ireplace('"documentnewstype"', '"DocumentNewsType"', $jsonContent);
                        $jsonContent = str_ireplace('"publishdate"', '"CreateDate"', $jsonContent);
                        $jsonContent = str_ireplace('"titlepic"', '"TitlePic1UploadFilePath"', $jsonContent);
                        $jsonContent = str_ireplace('"titlepic2"', '"TitlePic2UploadFilePath"', $jsonContent);
                        $jsonContent = str_ireplace('"titlepic3"', '"TitlePic3UploadFilePath"', $jsonContent);
                        $jsonContent = str_ireplace('"showdate"', '"ShowDate"', $jsonContent);
                        $jsonContent = str_ireplace('"documentnewssubtitle"', '"DocumentNewsSubTitle"', $jsonContent);
                        $jsonContent = str_ireplace('"documentnewscitetitle"', '"DocumentNewsCiteTitle"', $jsonContent);
                        $jsonContent = str_ireplace('"documentnewsshorttitle"', '"DocumentNewsShortTitle"', $jsonContent);
                        $jsonContent = str_ireplace('"documentnewsintro"', '"DocumentNewsIntro"', $jsonContent);
                        $jsonContent = str_ireplace('"documentnewstag"', '"DocumentNewsTag"', $jsonContent);
                        $jsonContent = str_ireplace('"showhour"', '"ShowHour"', $jsonContent);
                        $jsonContent = str_ireplace('"showminute"', '"ShowMinute"', $jsonContent);
                        $jsonContent = str_ireplace('"documentnewscontent"', '"DocumentNewsContent"', $jsonContent);
                        if($useOriUrl>0){
                            $jsonContent = str_ireplace('"directurl"', '"cancel"', $jsonContent);
                            $jsonContent = str_ireplace('"oriurl"', '"DirectUrl"', $jsonContent);
                        }else{
                            $jsonContent = str_ireplace('"directurl"', '"DirectUrl"', $jsonContent);
                            $jsonContent = str_ireplace('"oriurl"', '"OriUrl"', $jsonContent);
                        }
                        $jsonContent = str_ireplace('"siteurl"', '"SiteUrl"', $jsonContent);
                        $result = json_decode($jsonContent, TRUE);

                        //转为绝对地址
                        if($result["icms1"][0]["SiteUrl"]){
                            $siteUrl=$result["icms1"][0]["SiteUrl"];
                            foreach($result["icms1"] as $k => & $document ){
                                if(substr($document["TitlePic1UploadFilePath"],0,4)!="http"&&
                                    substr($document["TitlePic1UploadFilePath"],0,4)!=""
                                ){
                                    $document["TitlePic1UploadFilePath"]=$siteUrl.$document["TitlePic1UploadFilePath"];
                                }
                                if(substr($document["TitlePic2UploadFilePath"],0,4)!="http"&&
                                    substr($document["TitlePic1UploadFilePath"],0,4)!=""){
                                    $document["TitlePic2UploadFilePath"]=$siteUrl.$document["TitlePic2UploadFilePath"];
                                }
                                if(substr($document["TitlePic3UploadFilePath"],0,4)!="http"&&
                                    substr($document["TitlePic1UploadFilePath"],0,4)!=""){
                                    $document["TitlePic3UploadFilePath"]=$siteUrl.$document["TitlePic3UploadFilePath"];
                                }
                            }
                        }
                        if(count($result)>0){
                            $result = $result["icms1"];
                        }
                    }
                    break;
            }
        return $result;
    }

}
?>