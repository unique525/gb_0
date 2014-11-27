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
        $newspaperArticleId = -1;
        $authorityCode = Control::PostRequest("AuthorityCode", "");
        $newspaperPageId = Control::PostRequest("NewspaperPageId",0);
        $newspaperArticleTitle = Control::PostRequest("NewspaperArticleTitle","");
        $newspaperArticleContent = Control::PostRequest("NewspaperArticleContent","");
        $newspaperArticleSubTitle = Control::PostRequest("NewspaperArticleSubTitle","");
        $newspaperArticleCiteTitle = Control::PostRequest("NewspaperArticleCiteTitle","");
        $publishType = Control::PostRequest("PublishType","");
        $published = Control::PostRequest("Published","");
        $informationId = Control::PostRequest("InformationId","");
        $source = Control::PostRequest("Source","");
        $author = Control::PostRequest("Author","");
        $column = Control::PostRequest("Column","");
        $picRemark = Control::PostRequest("PicRemark","");
        $next = Control::PostRequest("Next","");
        $previous = Control::PostRequest("Previous","");
        $no = Control::PostRequest("No","");
        $className = Control::PostRequest("ClassName","");
        $genre = Control::PostRequest("Genre","");
        $reprint = Control::PostRequest("Reprint","");
        $fileName = Control::PostRequest("FileName","");
        $abstractInfo = Control::PostRequest("AbstractInfo","");
        $wordCount = Control::PostRequest("WordCount","");
        $picMapping = Control::PostRequest("PicMapping","");

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


            Template::ReplaceOne($templateContent, $arrOne);

            $templateContent = parent::ReplaceTemplate($templateContent);


            parent::ReplaceEnd($templateContent);

        }
        return $templateContent;
    }
} 