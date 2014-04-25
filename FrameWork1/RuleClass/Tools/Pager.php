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
     * @param string $tempContent 分页模板
     * @param string $navUrl 指向地址，需要包含{0}来指向当前页
     * @param int $allCount 记录总数
     * @param int $pageSize 页数
     * @param int $pageIndex 当前页码
     * @param int $styleNumber 模板风格编号
     * @param boolean $isJs 是否是JS分页
     * @param string $jsFunctionName JS方法的名称
     * @param string $jsParamList JS方法的参数字符串
     * @return string 返回分页HTML代码
     */
    public static function ShowPageButton($tempContent, $navUrl, $allCount, $pageSize, $pageIndex ,$styleNumber = 1, $isJs = false, $jsFunctionName = "" , $jsParamList = "") {
        $tempContent = trim($tempContent);

        if (strlen($tempContent) <= 0) {
            $tempContent = self::GetPagerDefaultTempContent();
            $temp = self::GetPagerDefaultListTemp();
        } else {
            if ($isJs) {
                $temp = Template::Load("pager/pager_content_js_style$styleNumber.html", "common");
            } else {
                $temp = Template::Load("pager/pager_content_style$styleNumber.html", "common");
            }
        }
        $outHtml = "";
        if ($pageSize > 0 && $pageSize < $allCount) {
            $outHtml = $tempContent;

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
            $outHtml = str_ireplace("{NextIndexC}", $pageIndex + 1, $outHtml);
            $outHtml = str_ireplace("{EndIndexC}", $allBtnCount, $outHtml);
            $outHtml = str_ireplace("{NowIndex}", strval($pageIndex), $outHtml);
            $outHtml = str_ireplace("{AllIndex}", strval($allBtnCount), $outHtml);
            $outHtml = str_ireplace("{AllCount}", strval($allCount), $outHtml);
            $outHtml = str_ireplace("{PageSize}", strval($pageSize), $outHtml);
            $outHtml = str_ireplace("{PageList}", strval($sbIndex), $outHtml);
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
            $outHtml = str_ireplace("{Rd}", str_ireplace("&p={0}", "", $navUrl), $outHtml);
            $outHtml = str_ireplace("{Url}&p=", "", $outHtml);
            $outHtml = str_ireplace("{BoxStyle}", "pb1", $outHtml);
        }

        return $outHtml;
    }

    /**
     * 默认的分页模板HTML
     * @return string 返回默认的分页模板HTML
     */
    public static function GetPagerDefaultTempContent() {
        return '<a class="webdings-red" href="{url}&p={firstindex}"><font face="webdings">9</font></a>
                <a {showpre} href="{url}&p={preindex}">上一页</a>
                {pagerlist}
                <a {shownext} title="点击查看下一页记录" href="{url}&p={nextindex}">下一页</a>
                <a class="webdings-red" href="{url}&p={endindex}"><font face="webdings" title="最后一页">:</font></a>
                &nbsp;&nbsp;&nbsp;{nowindex}/{allindex}页&nbsp;&nbsp;&nbsp;总数{allcount}&nbsp;&nbsp;&nbsp;每页{pagesize}&nbsp;条';
    }

    /**
     * 默认的分页按钮HTML
     * @return string 返回默认的分页按钮HTML
     */
    public static function GetPagerDefaultListTemp() {
        return '&nbsp;<a title="跳转到第{index}页" href="{url}&p={index}">{indexcontent}</a>';
    }

}

?>
