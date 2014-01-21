<?php

/**
 * 后台站点配置生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Site
 * @author zhangchi
 */
class SiteConfigManageGen extends BaseManageGen implements IBaseManageGen {

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function Gen() {
        $result = "";
        $action = Control::GetRequest("a", "");
        switch ($action) {
            case "set":
                $result = self::GenSet();
                break;
        }
        $replace_arr = array(
            "{action}" => $action
        );
        $result = strtr($result, $replace_arr);
        return $result;
    }

    /**
     * 新增站点
     * @return <type>
     */
    private function GenSet() {
        $siteId = Control::GetRequest("sid", 0);
        $type = intval(Control::GetRequest("type", 0));        
        if ($type == 1) { //forum
            $tempContent = Template::Load("site/siteconfig_deal_forum.html", "common");
        } else {
            $tempContent = Template::Load("site/siteconfig_deal.html", "common");
        }
        $nowAdminUserId = Control::GetManageUserId();
        parent::ReplaceFirst($tempContent);
        $siteConfigManageData = new SiteConfigManageData($siteId);
        if ($siteId > 0) {
            if (!empty($_POST)) {
                //读取表单
                foreach ($_POST as $key => $value) {
                    if (strpos($key, "cfg_") === 0) { //
                        $arr = Format::ToSplit($key, '_');
                        if (count($arr) >= 2) {
                            $siteConfigName = $arr[1];
                            if (isset($arr[2]) && !empty($arr[2])) {
                                $siteConfigType = $arr[2];
                            } else {
                                $siteConfigType = "0";
                            }
                            //为数组则转化为逗号分割字符串,对应checkbox应用
                            if (is_array($value)) {
                                $value = implode(",", $value);
                            }
                            $value = stripslashes($value);
                            $siteConfigManageData->SetValue($siteId, $siteConfigName, $value, $siteConfigType);
                        }
                    }
                }
                if ($type == 1) { //forum
                    Control::ShowMessage(Language::Load("siteconfig", 1));
                } else {
                    Control::ShowMessage(Language::Load("siteconfig", 1));
                    $jscode = 'self.parent.loadsitelist(1);self.parent.$("#tabs").tabs("select","#tabs-1");';
                    if ($tab_index > 0) {
                        $jscode = $jscode . 'self.parent.$("#tabs").tabs("remove",' . ($tab_index - 1) . ');';
                    }
                    Control::RunJS($jscode);
                }
            }

            //加载数据
            $arrSiteConfigOne = $siteConfigManageData->GetList($siteId);
            for ($i = 0; $i < count($arrSiteConfigOne); $i++) {
                $siteConfigName = $arrSiteConfigOne[$i]["SiteConfigName"];
                $stringNorValue = $arrSiteConfigOne[$i]["StringNorValue"];
                $stringMidValue = $arrSiteConfigOne[$i]["StringMidValue"];
                $textValue = $arrSiteConfigOne[$i]["TextValue"];
                $intValue = $arrSiteConfigOne[$i]["IntValue"];
                $numValue = $arrSiteConfigOne[$i]["NumValue"];
                $siteConfigType = intval($arrSiteConfigOne[$i]["SiteConfigType"]);
                switch ($siteConfigType) {
                    case 0:
                        $tempContent = str_ireplace("{cfg_" . $siteConfigName . "_" . $siteConfigType . "}", $stringNorValue, $tempContent);
                        Template::ReplaceSelectControl($tempContent, $siteConfigName, $stringNorValue);
                        break;
                    case 1:
                        $tempContent = str_ireplace("{cfg_" . $siteConfigName . "_" . $siteConfigType . "}", $stringMidValue, $tempContent);
                        Template::ReplaceSelectControl($tempContent, $siteConfigName, $stringMidValue);
                        break;
                    case 2:
                        $tempContent = str_ireplace("{cfg_" . $siteConfigName . "_" . $siteConfigType . "}", $textValue, $tempContent);
                        Template::ReplaceSelectControl($tempContent, $siteConfigName, $textValue);
                        break;
                    case 3:
                        $tempContent = str_ireplace("{cfg_" . $siteConfigName . "_" . $siteConfigType . "}", $intValue, $tempContent);
                        Template::ReplaceSelectControl($tempContent, $siteConfigName, $intValue);
                        break;
                    case 4:
                        $tempContent = str_ireplace("{cfg_" . $siteConfigName . "_" . $siteConfigType . "}", $numValue, $tempContent);
                        Template::ReplaceSelectControl($tempContent, $siteConfigName, $numValue);
                        break;
                    default:
                        $tempContent = str_ireplace("{cfg_" . $siteConfigName . "_" . $siteConfigType . "}", $stringNorValue, $tempContent);
                        Template::ReplaceSelectControl($tempContent, $siteConfigName, $stringNorValue);
                        break;
                }
            }
            $replace_arr = array(
                "{siteid}" => $siteId,
            );
            $tempContent = strtr($tempContent, $replace_arr);


            //去掉select开头的标记 {sel_xxx_xxx}
            $patterns = "/\{sel_(.*?)\}/";
            $TempContent = preg_replace($patterns, "", $TempContent);
            //去掉checkbox开头的标记 {cb_xxx}
            $patterns = "/\{cb_(.*?)\}/";
            $TempContent = preg_replace($patterns, "", $TempContent);
            //去掉radio开头的标记 {rd_xxx_xxx}
            $patterns = "/\{rd_(.*?)\}/";
            $TempContent = preg_replace($patterns, "", $TempContent);
            //替换掉{cfg XXX}的内容
            $patterns = "/\{cfg_(.*?)\}/";
            $tempContent = preg_replace($patterns, "", $tempContent);
        }
        parent::ReplaceEnd($tempContent);
        return $tempContent;
    }

    public function SubGen($SiteId, &$tempContent) {
        $siteConfigManageData = new SiteConfigManageData($SiteId);
        $arrSiteConfigOne = $siteConfigManageData->GetList($SiteId);
        if (count($arrSiteConfigOne) > 0) {
            for ($i = 0; $i < count($arrSiteConfigOne); $i++) {
                $siteConfigName = $arrSiteConfigOne[$i]["SiteConfigName"];
                $stringNorValue = $arrSiteConfigOne[$i]["StringNorValue"];
                $stringMidValue = $arrSiteConfigOne[$i]["StringMidValue"];
                $textValue = $arrSiteConfigOne[$i]["TextValue"];
                $intValue = $arrSiteConfigOne[$i]["IntValue"];
                $numValue = $arrSiteConfigOne[$i]["NumValue"];
                $siteConfigType = intval($arrSiteConfigOne[$i]["SiteConfigType"]);
                switch ($siteConfigType) {
                    case 0:
                        $tempContent = str_ireplace("{cfg_" . $siteConfigName . "_" . $siteConfigType . "}", $stringNorValue, $tempContent);
                        $tempContent = str_ireplace("{cfg_" . $siteConfigName . "}", $stringNorValue, $tempContent);
                        break;
                    case 1:
                        $tempContent = str_ireplace("{cfg_" . $siteConfigName . "_" . $siteConfigType . "}", $stringMidValue, $tempContent);
                        $tempContent = str_ireplace("{cfg_" . $siteConfigName . "}", $stringMidValue, $tempContent);
                        break;
                    case 2:
                        $tempContent = str_ireplace("{cfg_" . $siteConfigName . "_" . $siteConfigType . "}", $textValue, $tempContent);
                        $tempContent = str_ireplace("{cfg_" . $siteConfigName . "}", $textValue, $tempContent);
                        break;
                    case 3:
                        $tempContent = str_ireplace("{cfg_" . $siteConfigName . "_" . $siteConfigType . "}", $intValue, $tempContent);
                        $tempContent = str_ireplace("{cfg_" . $siteConfigName . "}", $intValue, $tempContent);
                        break;
                    case 4:
                        $tempContent = str_ireplace("{cfg_" . $siteConfigName . "_" . $siteConfigType . "}", $numValue, $tempContent);
                        $tempContent = str_ireplace("{cfg_" . $siteConfigName . "}", $numValue, $tempContent);
                        break;
                    default:
                        $tempContent = str_ireplace("{cfg_" . $siteConfigName . "_" . $siteConfigType . "}", $stringNorValue, $tempContent);
                        $tempContent = str_ireplace("{cfg_" . $siteConfigName . "}", $stringNorValue, $tempContent);
                        break;
                }
            }
        } else {//移除掉标记
            $patterns = "/\{cfg_(.*)\<\/}/imsU";
            $tempContent = preg_replace($patterns, "", $tempContent);
        }
    }

}

?>
