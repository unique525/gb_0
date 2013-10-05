<?php

/**
 * 提供语方包相关的工具方法
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Tools
 * @author zhangchi
 */
class Language {

    /**
     * 使用simplexml_load_file读取XML语言包内容
     * @param string $docName 语言包文件名
     * @param int $moduleId XML内的对应id
     * @return string 返回item内容
     */
    public static function Load($docName, $moduleId) {
        $lan = 'zh';
        $url = '/language/' . $lan . '/' . $docName . '.xml';
        if (file_exists(ROOTPATH . $url)) {

            $xml = simplexml_load_file(ROOTPATH . $url);

            $result = $xml->xpath("//module[@id='" . $moduleId . "']");
            if ($result) {
                foreach ($result[0]->children() as $key => $val) {
                    return $val;
                }
            } else {
                die("读取语言包出错");
            }
        }
    }

}

?>
