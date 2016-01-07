<?php

/**
 * 提供分页按钮相关的工具方法
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Tools
 * @author zhangchi
 */
class Pager {
    
    /**
     * 显示分页按钮
     * @param string $templateContent 分页模板
     * @param string $navUrl 指向地址，需要包含{0}来指向当前页
     * @param int $allCount 记录总数
     * @param int $pageSize 页数
     * @param int $pageIndex 当前页码
     * @param int $styleNumber 模板风格编号
     * @param boolean $isJs 是否是JS分页
     * @param string $jsFunctionName JS方法的名称
     * @param string $jsParamList JS方法的参数字符串
     * @param string $pageIndexName 页码参数名称，默认 p
     * @param string $pageSizeName 页大小参数名称，默认 pz
     * @param bool $showGoTo 是否显示转向框，默认显示
     * @param bool $isFront 是否前台使用
     * @param string $pagerContentTemplate 前台使用时，要传入列表按钮模板
     * @return string 返回分页HTML代码
     */
    public static function ShowPageButton(
        $templateContent,
        $navUrl,
        $allCount,
        $pageSize,
        $pageIndex ,
        $styleNumber = 1,
        $isJs = false,
        $jsFunctionName = "" ,
        $jsParamList = "",
        $pageIndexName = "p",
        $pageSizeName = "pz",
        $showGoTo = TRUE,
        $isFront = false,
        $pagerContentTemplate = ""
    ) {
        $templateContent = trim($templateContent);

        if (strlen($templateContent) <= 0) {
            $templateContent = self::GetPagerDefaultTempContent();
            $temp = self::GetPagerDefaultListTemp();
        } else {
            if($isFront){
                $temp = $pagerContentTemplate;
            }else{
                if ($isJs) {
                    $temp = Template::Load("pager/pager_content_js_style$styleNumber.html", "common");
                } else {
                    $temp = Template::Load("pager/pager_content_style$styleNumber.html", "common");
                }
            }
        }

        $outHtml = "";
        if ($pageSize > 0 && $pageSize < $allCount) {
            $outHtml = $templateContent;

            if ($allCount % $pageSize == 0) {
                $allBtnCount = (int) ($allCount / $pageSize);
            } else {
                $allBtnCount = (int) ($allCount / $pageSize + 1);
            }


            $sbIndex = "";

            if ($pageIndex <= 5) {

                $upCount1 = 10;
                if ($allBtnCount < $upCount1) {
                    $upCount1 = $allBtnCount;
                }

                for ($i = 1; $i <= $upCount1; $i++) {
                    if ($i > 0) {

                        $temp1 = str_ireplace("{IndexContent}", strval($i), $temp);

                        $temp1 = str_ireplace("{Index}", str_ireplace("{0}", strval($i), $navUrl), $temp1);

                        if ($i == $pageIndex) {
                            $temp1 = str_ireplace("{BoxStyle}", "pb2", $temp1);
                        }
                        $sbIndex = $sbIndex . $temp1;
                    }
                }
            } else {
                if ($allBtnCount - $pageIndex > 5) {
                    $upCount = 5;
                    $downCount = 5;
                } else {
                    $upCount = 10 - ($allBtnCount - $pageIndex);
                    $downCount = $upCount;
                }

                for ($i = $pageIndex - $upCount; $i < $pageIndex + $downCount; $i++) {
                    if ($allBtnCount >= $i) {
                        if ($i > 0) {
                            $temp1 = str_ireplace("{IndexContent}", strval($i), $temp);

                            $temp1 = str_ireplace("{Index}", str_ireplace("{0}", strval($i), $navUrl), $temp1);

                            if ($i == $pageIndex) {
                                $temp1 = str_ireplace("{BoxStyle}", "pb2", $temp1);
                            }
                            $sbIndex = $sbIndex . $temp1;
                        }
                    }
                }
            }

            if ($pageIndex < $allBtnCount) {
                $outHtml = str_ireplace("{ShowNext}", "", $outHtml);
            } else {
                $outHtml = str_ireplace("{ShowNext}", "style=\"display:none\"", $outHtml);
            }
            $outHtml = str_ireplace("{NextIndex}", str_ireplace("{0}", strval($pageIndex + 1), $navUrl), $outHtml);
            $outHtml = str_ireplace("{EndIndex}", str_ireplace("{0}", strval($allBtnCount), $navUrl), $outHtml);
            $outHtml = str_ireplace("{all_content_url}", str_ireplace("{0}", "0", $navUrl), $outHtml);
            $outHtml = str_ireplace("{NextIndexC}", $pageIndex + 1, $outHtml);
            $outHtml = str_ireplace("{EndIndexC}", $allBtnCount, $outHtml);
            $outHtml = str_ireplace("{NowIndex}", strval($pageIndex), $outHtml);
            $outHtml = str_ireplace("{AllIndex}", strval($allBtnCount), $outHtml);
            $outHtml = str_ireplace("{AllCount}", strval($allCount), $outHtml);
            $outHtml = str_ireplace("{PageSize}", strval($pageSize), $outHtml);
            $outHtml = str_ireplace("{PageList}", strval($sbIndex), $outHtml);
            $outHtml = str_ireplace("{PagerList}", strval($sbIndex), $outHtml);
            $outHtml = str_ireplace("{JsFunctionName}", $jsFunctionName, $outHtml);
            $outHtml = str_ireplace("{ParamList}", $jsParamList, $outHtml);


            if ($pageIndex > 1) {
                $outHtml = str_ireplace("{ShowPre}", "", $outHtml);
            } else {
                $outHtml = str_ireplace("{ShowPre}", "style=\"display:none\"", $outHtml);
            }
            $outHtml = str_ireplace("{PreIndex}", str_ireplace("{0}", strval($pageIndex - 1), $navUrl), $outHtml);
            $outHtml = str_ireplace("{FirstIndex}", str_ireplace("{0}", "1", $navUrl), $outHtml);
            $outHtml = str_ireplace("{PreIndexC}", $pageIndex - 1, $outHtml);
            $outHtml = str_ireplace("{FirstIndexC}", "1", $outHtml);
            $outHtml = str_ireplace("{Rd}", str_ireplace("&$pageIndexName={0}", "", $navUrl), $outHtml);
            $outHtml = str_ireplace("{PageIndexName}", $pageIndexName, $outHtml);
            $outHtml = str_ireplace("{Url}&p=", "", $outHtml);
            $outHtml = str_ireplace("{BoxStyle}", "pb1", $outHtml);
            $outHtml = str_ireplace("{PageSizeName}", $pageSizeName, $outHtml);
            if($showGoTo){
                $outHtml = str_ireplace("{ShowGoTo}", "", $outHtml);
            }else{
                $outHtml = str_ireplace("{ShowGoTo}", "none", $outHtml);
            }
        }

        return $outHtml;
    }

    /**
     * 默认的分页模板HTML
     * @return string 返回默认的分页模板HTML
     */
    public static function GetPagerDefaultTempContent() {
        return '<a class="webdings-red" href="{firstindex}"><font face="webdings">第一页</font></a>
                <a {showpre} href="{preindex}">上一页</a>
                {pagerlist}
                <a {shownext} title="点击查看下一页记录" href="{nextindex}">下一页</a>
                <a class="webdings-red" href="{endindex}"><font face="webdings" title="最后一页">:</font></a>
                &nbsp;&nbsp;&nbsp;{nowindex}/{allindex}页&nbsp;&nbsp;&nbsp;总数{allcount}&nbsp;&nbsp;&nbsp;每页{pagesize}&nbsp;条';
    }

    /**
     * 默认的分页按钮HTML
     * @return string 返回默认的分页按钮HTML
     */
    public static function GetPagerDefaultListTemp() {
        return '&nbsp;<a title="跳转到第{index}页" href="{index}">{indexcontent}</a>';
    }

    /**
     * @param $pageTemplate string 模版内容
     * @param $error        string 错误信息
     */
    private static function ReplacePageError(&$pageTemplate, $error){
        $pageTemplate = str_ireplace('{pageError}', $error, $pageTemplate);
    }

    /**
     * 单页按钮是指类似 [上一页][1][2][3][4][下一页] 中的数字页码按钮
     * @param $pageTemplate        string   分页模版
     * @param $isFront             bool     是否前台使用
     * @param bool|true $showGoTo  bool     是否显示页码跳转框
     * @param string $styleName    string   后台使用时单页按钮的模版
     * @param $pageIndex           int      页码
     * @param $pageSize            int      每页条数
     * @param $allCount            int      总条数
     * @param $pageButtonList      string   前台使用时单页按钮的模版
     * @param $pageButtonListUrl   int      单页按钮指向的链接
     * @return mixed|string        int      完整的翻页按钮组件
     */
    public static function CreatePageButtons(
        $pageTemplate,
        $isFront,
        $showGoTo = true,
        $styleName = 'default',
        $pageIndex,
        $pageSize,
        $allCount,
        $pageButtonList,
        $pageButtonListUrl
    ){
        /*****加载翻页模版文件*****/
        $templateContent = trim($pageTemplate);
        if(strlen($pageTemplate) <= 0){
            $pageTemplate    = self::GetPagerDefaultTempContent();
            $pageButtonList  = self::GetPagerDefaultListTemp();
        }
        else{

            if(!$isFront){
                $pageButtonList = Template::Load("pager_new/pager_list_style_$styleName.html", "common");
            }
        }


        /*****处理有误的页码*****/
        if($allCount < 0){
            self::ReplacePageError($pageTemplate, '错误的总页码数['.$allCount.'],总页码不能为负');
            return $pageTemplate;
        }

        /*****计算单页按钮的数量*****/
        $allButtonCount = 0;
        if($allCount % $pageSize == 0){
            $allButtonCount = intval(($allCount / $pageSize));
        }
        else{
            $allButtonCount = intval(($allCount / $pageSize) + 1);
        }


        /*****生成单页按钮列表*****/
        $buttonCollection = '';
        if ($pageIndex <= 5){
            $upCount = 10;

            if($allButtonCount < $upCount){
              $upCount = $allButtonCount;
            }

            for($i = 1; $i <= $upCount; $i++){
                $subPageButtonList = $pageButtonList;
                $subPageButtonList = str_ireplace('{pageNumber}', $i, $subPageButtonList);
                $subPageButtonList = str_ireplace('{pageHref}', str_ireplace("{n}", strval($i), $pageButtonListUrl), $subPageButtonList);

                if($i == $pageIndex){
                    $subPageButtonList = str_ireplace("{BoxStyle}", "pb2", $subPageButtonList);
                }

                $buttonCollection = $buttonCollection . $subPageButtonList;
            }

        }
        else{
            if($allButtonCount - $pageIndex > 5){
                $upCount   = 5;
                $downCount = 5;
            }
            else{
                $upCount = 10 - ($allButtonCount - $pageIndex);
                $downCount = $upCount;
            }

            for ($i = $pageIndex - $upCount; $i < $pageIndex + $downCount; $i++) {
                $subPageButtonList = $pageButtonList;
                if($allButtonCount >= $i){
                    if ($i > 0) {
                        $subPageButtonList = str_ireplace('{pageNumber}', $i, $subPageButtonList);
                        $subPageButtonList = str_ireplace('{pageHref}', str_ireplace("{n}", strval($i), $pageButtonListUrl), $subPageButtonList);

                        if($i == $pageIndex){
                            $subPageButtonList = str_ireplace("{BoxStyle}", "pb2", $subPageButtonList);
                        }

                        $buttonCollection = $buttonCollection . $subPageButtonList;
                    }

                }
            }
        }

        /*****在翻到最后一页时,PC版隐藏下一页按钮/移动版改变下一页按钮的文字并将其链接指向空地址*****/
        if($pageIndex < $allButtonCount){
            $pageTemplate = str_ireplace("{ShowNext}", "", $pageTemplate);
            $pageTemplate = str_ireplace("{Mobile_NextIndex}", '下一页', $pageTemplate);

        }
        else{
            $pageTemplate = str_ireplace("{ShowNext}", "style=\"display:none\"", $pageTemplate);
            $pageTemplate = str_ireplace("{Mobile_NextIndex}", '末页', $pageTemplate);
            $pageTemplate = str_ireplace("{Mobile_NextIndexUrl}", '', $pageTemplate);

        }

        $pageTemplate = str_ireplace("{NextIndex}", str_ireplace("{n}", strval($pageIndex + 1), $pageButtonListUrl), $pageTemplate);
        $pageTemplate = str_ireplace("{Mobile_NextIndexUrl}", str_ireplace("{n}", strval($pageIndex + 1), $pageButtonListUrl), $pageTemplate);
        $pageTemplate = str_ireplace("{EndIndex}", str_ireplace("{n}", strval($allButtonCount), $pageButtonListUrl), $pageTemplate);
        $pageTemplate = str_ireplace("{all_content_url}", str_ireplace("{n}", "0", $pageButtonListUrl), $pageTemplate);
        $pageTemplate = str_ireplace("{NextIndexC}", $pageIndex + 1, $pageTemplate);
        $pageTemplate = str_ireplace("{EndIndexC}", $allButtonCount, $pageTemplate);
        $pageTemplate = str_ireplace("{NowIndex}", strval($pageIndex), $pageTemplate);
        $pageTemplate = str_ireplace("{AllIndex}", strval($allButtonCount), $pageTemplate);
        $pageTemplate = str_ireplace("{AllCount}", strval($allCount), $pageTemplate);
        $pageTemplate = str_ireplace("{PageSize}", strval($pageSize), $pageTemplate);

        $pageTemplate = str_ireplace("{PageList}", strval($buttonCollection), $pageTemplate);

        /*****在翻到第一页时进行与翻到最后一页类似的相反的操作*****/
        if ($pageIndex > 1) {
            $pageTemplate = str_ireplace("{ShowPre}", "", $pageTemplate);
            $pageTemplate = str_ireplace("{mobile_preIndex}", '上一页', $pageTemplate);
        } else {
            $pageTemplate = str_ireplace("{ShowPre}", "style=\"display:none\"", $pageTemplate);
            $pageTemplate = str_ireplace("{mobile_preIndex}", '已是第一页', $pageTemplate);
            $pageTemplate = str_ireplace("{mobile_PreIndexUrl}", '', $pageTemplate);
        }

        $pageTemplate = str_ireplace("{PreIndex}", str_ireplace("{n}", strval($pageIndex - 1), $pageButtonListUrl), $pageTemplate);
        $pageTemplate = str_ireplace("{mobile_PreIndexUrl}", str_ireplace("{n}", strval($pageIndex - 1), $pageButtonListUrl), $pageTemplate);
        $pageTemplate = str_ireplace("{FirstIndex}", str_ireplace("{n}", "1", $pageButtonListUrl), $pageTemplate);
        $pageTemplate = str_ireplace("{PreIndexC}", $pageIndex - 1, $pageTemplate);
        $pageTemplate = str_ireplace("{FirstIndexC}", "1", $pageTemplate);
        $pageTemplate = str_ireplace("{Rd}", str_ireplace("&p={n}", "", $pageButtonListUrl), $pageTemplate);
        $pageTemplate = str_ireplace("{PageIndexName}", 'p', $pageTemplate);
        $pageTemplate = str_ireplace("{BoxStyle}", "pb1", $pageTemplate);
        $pageTemplate = str_ireplace("{BoxStyle}", "pb1", $pageTemplate);
        self::ReplacePageError($pageTemplate, '');


        if($showGoTo){
            $pageTemplate = str_ireplace("{ShowGoTo}", "", $pageTemplate);
        }else{
            $pageTemplate = str_ireplace("{ShowGoTo}", "none", $pageTemplate);
        }
        return $pageTemplate;
    }




}
