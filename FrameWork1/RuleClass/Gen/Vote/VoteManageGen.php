<?php

/**
 * 投票调查后台管理生成类
 * @category iCMS
 * @package iCMS_Rules_Gen_Vote
 * @author yanjiuyuan
 */
class VoteManageGen extends BaseManageGen implements IBaseManageGen
{

    /**
     * 牵引生成方法(继承接口)
     * @return string 返回输出的HTML内容
     */
    public function Gen()
    {
        $result = "";
        $method = Control::GetRequest("m", "");
        switch ($method) {
            case "create":
                $result = self::GenCreate();
                break;
            case "remove_to_bin":
                $result = self::GenRemoveToBin();
                break;
            case "modify":
                $result = self::GenModify();
                break;
            case "list":
                $result = self::GenList();
                break;
        }

        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }

    /**
     * 生成投票调查管理新增页面
     * @return mixed|string
     */
    private function GenCreate()
    {
        $manageUserId = Control::GetManageUserId();
        $siteId = Control::GetRequest("site_id", 0);
        $channelId = Control::GetRequest("channel_id", 0);
        $pageIndex = Control::GetRequest("p", 0);
        $tempContent = Template::Load("vote/vote_deal.html", "common");
        if ($manageUserId > 0) {
            parent::ReplaceFirst($tempContent);
            $voteManageData = new VoteManageData();
            if (!empty($_POST)) {
                $httpPostData = $_POST;
                $voteId = $voteManageData->Create($httpPostData);

                //加入操作日志
                $operateContent = 'Create Vote,POST FORM:' . implode('|', $_POST) . ';\r\nResult:voteId:' . $voteId;
                self::CreateManageUserLog($operateContent);

                if ($voteId > 0) {
                    //javascript 处理
                    Control::ShowMessage(Language::Load('vote', 1));
                    $jsCode = 'parent.location.href=parent.location.href';
                    Control::RunJavascript($jsCode);
                } else {
                    Control::ShowMessage(Language::Load('vote', 2));
                }
                return "";
            }
            $tempContent = str_ireplace("{PageIndex}", $pageIndex, $tempContent);
            $tempContent = str_ireplace("{CreateDate}",date("Y-m-d H:i:s"), $tempContent);
            $tempContent = str_ireplace("{ManageUserId}", strval($manageUserId), $tempContent);
            $tempContent = str_ireplace("{SiteId}", strval($siteId), $tempContent);
            $tempContent = str_ireplace("{ChannelId}", strval($channelId), $tempContent);
            $tempContent = str_ireplace("{IpMaxCount}", "10", $tempContent);
            $fieldsOfVote = $voteManageData->GetFields();
            parent::ReplaceWhenCreate($tempContent, $fieldsOfVote);

            $patterns = '/\{s_(.*?)\}/';
            $tempContent = preg_replace($patterns, "", $tempContent);
            parent::ReplaceEnd($tempContent);

        }
        return $tempContent;
    }

    /**
     * 停用投票
     * @return string 生成HTML
     */
    private function GenRemoveToBin()
    {
        /**
        $voteId = Control::GetRequest("vote_id", 0);
        $pageIndex = Control::GetRequest("p", 0);

        if ($voteId > 0) {
            $voteData = new VoteManageData();
            $result = $voteData->RemoveToBin($voteId);

            //加入操作日志
            $operateContent = 'RemoveToBin Vote,Get FORM:'.implode('|',$_GET).';\r\nResult:voteId:'.$voteId;
            self::CreateManageUserLog($operateContent);

            if ($result > 0) {
                Control::ShowMessage(Language::Load('vote', 17));
                $jsCode = 'self.parent.loadvotelist(' . $pageIndex . ',"");self.parent.tb_remove();';
                Control::RunJS($jsCode);
                return "";
            } else {
                Control::ShowMessage(Language::Load('vote', 18));
                $jsCode = 'javascript:history.go(-1);';
                Control::RunJS($jsCode);
                return "";
            }
        } else {
            Control::ShowMessage(Language::Load('vote', 3));
            $jsCode = 'javascript:history.go(-1);';
            Control::RunJS($jsCode);
            return "";
        }*/
    }

    /**
     * 生成投票调查修改页面
     * @return mixed|string
     */
    private function GenModify()
    {
        $tempContent = Template::Load("vote/vote_deal.html", "common");
        $voteId = Control::GetRequest("vote_id", 0);
        $pageIndex = Control::GetRequest("p", 1);
        parent::ReplaceFirst($tempContent);
        $voteManageData = new VoteManageData();
        if ($voteId > 0) {
            if (!empty($_POST)) {
                $httpPostData = $_POST;
                $result = $voteManageData->Modify($httpPostData, $voteId);

                //加入操作日志
                $operateContent = 'Modify Vote,POST FORM:' . implode('|', $_POST) . ';\r\nResult:voteId:' . $voteId;
                self::CreateManageUserLog($operateContent);

                if ($result > 0) {
                    //javascript 处理
                    Control::ShowMessage(Language::Load('vote', 3));
                    $jsCode = 'parent.location.href=parent.location.href';
                    Control::RunJavascript($jsCode);
                } else {
                    Control::ShowMessage(Language::Load('vote', 4));
                }
            }
            $arrList = $voteManageData->GetOne($voteId);
            Template::ReplaceOne($tempContent, $arrList, false, false);
            $tempContent = str_ireplace("{PageIndex}", strval($pageIndex), $tempContent);
        }
        //替换掉{s XXX}的内容
        $patterns = '/\{s_(.*?)\}/';
        $tempContent = preg_replace($patterns, "", $tempContent);
        parent::ReplaceEnd($tempContent);
        return $tempContent;
    }

    /**
     * 投票调查管理列表页面
     * @return mixed|string
     */
    private function GenList()
    {

        $tempContent = Template::Load("vote/vote_list.html", "common");

        $siteId = Control::GetRequest("site_id", 0);
        $channelId = Control::GetRequest("channel_id", 0);
        $pageSize = Control::GetRequest("ps", 20);
        $pageIndex = Control::GetRequest("p", 1);
        $searchKey = Control::GetRequest("search_key", "");
        $searchKey = urldecode($searchKey);

        if ($pageIndex > 0) {
            $pageBegin = ($pageIndex - 1) * $pageSize;
            $tagId = "vote_list";
            $allCount = 0;
            $voteManageData = new VoteManageData();
            $arrList = $voteManageData->GetListForPager($siteId, $channelId, $pageBegin, $pageSize, $allCount, $searchKey);
            if (count($arrList) > 0) {
                Template::ReplaceList($tempContent, $arrList, $tagId);
                /**
                 * $pagerTemplate = Template::Load("pager_js.html");
                 * $isJs = true;
                 * $jsFunctionName = "loadvotelist";
                 * $jsParamList = ",'" . urlencode($searchKey) . "'";
                 * $pagerButton = Pager::ShowPageButton($pagerTemplate, "", $allCount, $pageSize, $pageIndex, $isJs, $jsFunctionName, $jsParamList); //填入
                 * $replaceArr = array(
                 * "{pagerbutton}" => $pagerButton
                 * );
                 * $tempContent = strtr($tempContent, $replaceArr);
                 * parent::ReplaceEnd($tempContent);
                 * $result = $tempContent;
                 */
            } else {
                Template::RemoveCustomTag($tempContent, $tagId);
                $tempContent = str_ireplace("{pager_button}", Language::Load("vote", 101), $tempContent);
            }
        }
        parent::ReplaceEnd($tempContent);
        return $tempContent;
    }

    /**
     * 替换模板中的投票调查标记生成投票列表
     * @param string $tempContent 模板字符串
     */
    public function SubGen(&$tempContent)
    {
        //把文档内容中的vote标记替换为标准形式
        $tagName = "icms_vote";
        $pattern = "/{" . $tagName . "(.*?)}/ims";
        //调用VoteReplace回调方法处理
        $tempContent = preg_replace_callback($pattern, array(&$this, 'VoteReplace'), $tempContent);
        $arr = Template::GetAllCustomTag($tempContent, $tagName);
        if (isset($arr)) {
            if (count($arr) > 1) {
                if (!empty($arr[1])) {
                    $voteManageGen = new VoteManageGen();
                    $arr2 = $arr[1];
                    foreach ($arr2 as $value) {
                        $docContent = '<' . $tagName . '' . $value . '</' . $tagName . '>';
                        $voteId = Template::GetParamValue($docContent, "id", $tagName);
                        $type = Template::GetParamValue($docContent, "type", $tagName);
                        $itemWidth = Template::GetParamValue($docContent, "ItemWidth", $tagName); //选项宽
                        $itemHeight = Template::GetParamValue($docContent, "ItemHeight", $tagName); //选项高
                        $itemMarginLeft = Template::GetParamValue($docContent, "ItemMarginLeft", $tagName); //左边距
                        $itemTitleDisplay = Template::GetParamValue($docContent, "ItemTitleDisplay", $tagName); //题目标题显示方式
                        $btnDisplay = Template::GetParamValue($docContent, "btnDisplay", $tagName); //是否显示投票按钮
                        //传递选项外观属性参数
                        $arr_par = array(
                            "{ItemWidth}" => $itemWidth,
                            "{ItemHeight}" => $itemHeight,
                            "{ItemMarginLeft}" => $itemMarginLeft,
                            "{ItemTitleDisplay}" => $itemTitleDisplay,
                            "{btnDisplay}" => $btnDisplay
                        );
                        if ($voteId != "") {
                            $docContent = $voteManageGen->GetVoteHtml($voteId, $type, $arr_par);
                        } else {
                            $docContent = "";
                        }
                        //把对应ID的题目标记替换成指定内容
                        $tempContent = Template::ReplaceCustomTag($tempContent, $voteId, $docContent, $tagName);
                    }
                    parent::ReplaceEnd($tempContent);
                }
            }
        }
    }


    /**
     * 替换模板中的投票调查标记为标准形式的回调方法
     * @param string $source 被替换字符串
     * @return mixed|string
     */
    private function VoteReplace($source)
    {
        $result = $source[1];
        //替换掉编辑器中可能的&nbsp;为标准空格形式
        $replace_arr = array("&nbsp;" => " ");
        $result = strtr($result, $replace_arr);
        $result = "<icms_vote" . $result . "></icms_vote>";
        return $result;
    }

    /**
     * 替换模板中的投票调查标记生成题目和题目选项
     * @param int $voteId 投票调查ID号
     * @param int $type 投票调查模板类型
     * @param array $arrPar 选项外观属性参数数组
     * @return mixed|string
     */
    private function GetVoteHtml($voteId, $type, $arrPar)
    {
        $voteManageData = new VoteManageData();
        $result = $voteManageData->GetOne($voteId);
        //如果投票标记没有指定模板，则启用数据库配置的模板
        if ($type == null) {
            $type = $result['TemplateName'];
            //如果数据库没有配置模板，默认启用普通模板
            if ($type == null || $type == '')
                $type = "normal";
        }
        //加载对应类型模板
        $tempContent = Template::Load("vote/vote_front_" . $type . ".html", "common");
        //生成题目
        $tempContent = self::GenSubVoteItem($tempContent, $voteId);
        //生成题目选项
        $tempContent = self::GenSubVoteSelectItem($tempContent);
        //模板参数替换
        if ($arrPar['{ItemMarginLeft}'] == null)
            $arrPar['{ItemMarginLeft}'] = "40px";
        foreach ($arrPar as $key=>$value)
        {
          $tempContent = str_ireplace(strval($key), strval($value), $tempContent);
        }
        $tempContent = str_ireplace("{VoteId}", strval($voteId), $tempContent);
        $tempContent = str_ireplace("{Type}", strval($type), $tempContent);
        //根据是否启用验证码，决定是否显示验证码
        $isCheckCode = $result['IsCheckCode'];
        if ($isCheckCode != 1) {
            $preg = '/\<div id=\"vote_check_code" . $voteId . "\">(.*)\<\/div>/imsU';
            $tempContent = preg_replace($preg, '', $tempContent);
        }
        return $tempContent;
    }

    /**
     * 替换模板中的题目标记生成题目列表
     * @param string $tempContent 模板字符串
     * @param int $voteId 问卷ID号
     * @return mixed|string
     */
    private function GenSubVoteItem($tempContent, $voteId)
    {
        $tagId = "vote_item_content";
        $tagName = "icms_vote_item";
        $voteItemManageData = new VoteItemManageData();
        $arrItem = $voteItemManageData->GetList($voteId, 0); //读取投票调查题目
        Template::ReplaceList($tempContent, $arrItem, $tagId, $tagName);
        //把对应ID的题目标记替换成题目列表
        //替换子循环里的<![CDATA[标记
        $tempContent = str_ireplace("[CDATA]", "<![CDATA[", $tempContent);
        $tempContent = str_ireplace("[/CDATA]", "]]>", $tempContent);
        return $tempContent;
    }

    /**
     * 替换模板中的题目选项标记生成题目选项列表
     * @param string $tempContent 模板字符串
     * @return mixed|string
     */
    private function GenSubVoteSelectItem($tempContent)
    {
        $tagName = "icms_vote_select_item";
        $arr = Template::GetAllCustomTag($tempContent, $tagName);
        if (isset($arr)) {
            if (count($arr) > 1) {
                $voteSelectItemManageData = new VoteSelectItemManageData();
                $arr2 = $arr[1];
                foreach ($arr2 as $value) {
                    $docContent = '<' . $tagName . '' . $value . '</' . $tagName . '>';
                    //题目ID
                    $id = Template::GetParamValue($docContent, "id", $tagName);
                    $voteItemId = $id;
                    $arrSelect = $voteSelectItemManageData->GetSelect($voteItemId, 0);
                    Template::ReplaceList($docContent, $arrSelect, $voteItemId, $tagName);
                    $tempContent = Template::ReplaceCustomTag($tempContent, $voteItemId, $docContent, $tagName);
                }
            }
        }
        return $tempContent;
    }

}

?>
