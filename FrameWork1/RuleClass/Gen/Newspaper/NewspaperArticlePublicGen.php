<?php
/**
 * 公开访问 电子报文章 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Newspaper
 * @author zhangchi
 */
class NewspaperArticlePublicGen extends BasePublicGen {

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenPublic() {
        $result = "";
        $action = Control::GetRequest("a", "");
        switch ($action) {
            case "import":
                $result = self::Import();
                break;
            case "detail":
                $result = self::GenDetail();
                break;
            case "list":
                $result = self::GenList();
                break;
        }
        return $result;
    }

    private function Import(){
        $removeXSS = false;
        $newspaperArticleId = -1;
        $authorityCode = str_ireplace("\r\n","",Control::PostRequest("AuthorityCode", "", $removeXSS));
        $newspaperPageId = intval(str_ireplace("\r\n","",Control::PostRequest("NewspaperPageId",0, $removeXSS)));
        $newspaperArticleTitle = str_ireplace("\r\n","",Control::PostRequest("NewspaperArticleTitle","", $removeXSS));
        $newspaperArticleContent = str_ireplace("\r\n","",Control::PostRequest("NewspaperArticleContent","", $removeXSS));
        $newspaperArticleSubTitle = str_ireplace("\r\n","",Control::PostRequest("NewspaperArticleSubTitle","", $removeXSS));
        $newspaperArticleCiteTitle = str_ireplace("\r\n","",Control::PostRequest("NewspaperArticleCiteTitle","", $removeXSS));
        $publishType = str_ireplace("\r\n","",Control::PostRequest("PublishType","", $removeXSS));
        $published = str_ireplace("\r\n","",Control::PostRequest("Published","", $removeXSS));
        $informationId = str_ireplace("\r\n","",Control::PostRequest("InformationId","", $removeXSS));
        $source = str_ireplace("\r\n","",Control::PostRequest("Source","", $removeXSS));
        $author = str_ireplace("\r\n","",Control::PostRequest("Author","", $removeXSS));
        $column = str_ireplace("\r\n","",Control::PostRequest("Column","", $removeXSS));
        $picRemark = str_ireplace("\r\n","",Control::PostRequest("PicRemark","", $removeXSS));
        $next = str_ireplace("\r\n","",Control::PostRequest("Next","", $removeXSS));
        $previous = str_ireplace("\r\n","",Control::PostRequest("Previous","", $removeXSS));
        $no = str_ireplace("\r\n","",Control::PostRequest("No","", $removeXSS));
        $className = str_ireplace("\r\n","",Control::PostRequest("ClassName","", $removeXSS));
        $genre = str_ireplace("\r\n","",Control::PostRequest("Genre","", $removeXSS));
        $reprint = str_ireplace("\r\n","",Control::PostRequest("Reprint","", $removeXSS));
        $fileName = str_ireplace("\r\n","",Control::PostRequest("FileName","", $removeXSS));
        $abstractInfo = str_ireplace("\r\n","",Control::PostRequest("AbstractInfo","", $removeXSS));
        $wordCount = str_ireplace("\r\n","",Control::PostRequest("WordCount","", $removeXSS));
        $picMapping = str_ireplace("\r\n","",Control::PostRequest("PicMapping","", $removeXSS));

        if(
            $authorityCode == "C_S_W_B_E_P_A_P_E_R_I_M_P_O_R_T" &&
            $newspaperPageId>0 &&
            strlen($newspaperArticleTitle)>0
        )
        {
            $newspaperArticlePublicData = new NewspaperArticlePublicData();

            $newspaperArticleId = $newspaperArticlePublicData->CreateForImport(
                $newspaperPageId,
                $newspaperArticleTitle,
                $newspaperArticleContent,
                $newspaperArticleSubTitle,
                $newspaperArticleCiteTitle,
                $publishType,
                $published,
                $informationId,
                $source,
                $author,
                $column,
                $picRemark,
                $next,
                $previous,
                $no,
                $className,
                $genre,
                $reprint,
                $fileName,
                $abstractInfo,
                $wordCount,
                $picMapping
            );
        }

        return $newspaperArticleId;
    }

    private function GenList() {
        $newspaperPageId = Control::GetRequest("newspaper_page_id", 0);
        $templateContent = "";

        if($newspaperPageId>0){
            $templateFileUrl = "newspaper/newspaper_article_list.html";
            $templateName = "default";
            $templatePath = "front_template";
            $templateContent = Template::Load($templateFileUrl, $templateName, $templatePath);
            $templateContent = str_ireplace("{NewspaperPageId}", $newspaperPageId, $templateContent);
            parent::ReplaceFirst($templateContent);


            $templateContent = parent::ReplaceTemplate($templateContent);


            parent::ReplaceEnd($templateContent);

        }
        return $templateContent;
    }

    private function GenDetail(){
        $newspaperArticleId = Control::GetRequest("newspaper_article_id", 0);
        $templateContent = "";

        if($newspaperArticleId>0){
            $templateFileUrl = "newspaper/newspaper_article_detail.html";
            $templateName = "default";
            $templatePath = "front_template";
            $templateContent = Template::Load($templateFileUrl, $templateName, $templatePath);
            $templateContent = str_ireplace("{NewspaperArticleId}", $newspaperArticleId, $templateContent);
            parent::ReplaceFirst($templateContent);

            $templateContent = parent::ReplaceTemplate($templateContent);


            $newspaperArticlePublicData = new NewspaperArticlePublicData();
            $arrOne = $newspaperArticlePublicData->GetOne($newspaperArticleId);

            $arrOne["NewspaperArticleContent"] =
                str_ireplace("\n","<br /><br />", $arrOne["NewspaperArticleContent"]);
            $arrOne["NewspaperArticleContent"] =
                str_ireplace("&lt;","<", $arrOne["NewspaperArticleContent"]);
            $arrOne["NewspaperArticleContent"] =
                str_ireplace("&gt;",">", $arrOne["NewspaperArticleContent"]);


            Template::ReplaceOne($templateContent, $arrOne);

            $templateContent = parent::ReplaceTemplate($templateContent);


            parent::ReplaceEnd($templateContent);

        }
        return $templateContent;
    }
} 