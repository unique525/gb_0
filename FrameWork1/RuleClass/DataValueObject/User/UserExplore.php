<?php
/**
 * 会员浏览记录对象类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataValueObject_UploadFile
 * @property int $TableId
 * @property int $TableType
 * @property int $UserId
 * @property string $Url
 * @property string $Title
 * @property string $TitlePic
 * @property float $Price
 * @author zhangchi
 */
class UserExplore {

    private $TableId;
    private $TableType;
    private $UserId;
    private $Url;
    private $Title;
    private $TitlePic;
    private $Price;

    /**
     * @param mixed $Price
     */
    public function setPrice($Price)
    {
        $this->Price = $Price;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->Price;
    }

    /**
     * @param mixed $TableId
     */
    public function setTableId($TableId)
    {
        $this->TableId = $TableId;
    }

    /**
     * @return mixed
     */
    public function getTableId()
    {
        return $this->TableId;
    }

    /**
     * @param mixed $TableType
     */
    public function setTableType($TableType)
    {
        $this->TableType = $TableType;
    }

    /**
     * @return mixed
     */
    public function getTableType()
    {
        return $this->TableType;
    }

    /**
     * @param mixed $Title
     */
    public function setTitle($Title)
    {
        $this->Title = $Title;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->Title;
    }

    /**
     * @param mixed $TitlePic
     */
    public function setTitlePic($TitlePic)
    {
        $this->TitlePic = $TitlePic;
    }

    /**
     * @return mixed
     */
    public function getTitlePic()
    {
        return $this->TitlePic;
    }

    /**
     * @param mixed $Url
     */
    public function setUrl($Url)
    {
        $this->Url = $Url;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->Url;
    }

    /**
     * @param mixed $UserId
     */
    public function setUserId($UserId)
    {
        $this->UserId = $UserId;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->UserId;
    }
    /**
     * get 配置值
     * @param string $fieldName 配置名称
     * @return string 配置值
     */
    public function __get($fieldName){
        $funcName = "get$fieldName";
        if(function_exists($funcName)){
            return $this->$funcName();
        }else{
            return null;
        }
    }
    /**
     * 设置配置值
     * @param string $fieldName 配置名称
     * @param string $fieldValue 配置值
     */
    public function __set($fieldName,$fieldValue){
        $funcName = "set$fieldName";
        if(function_exists($funcName)){
            $this->$funcName($fieldValue);
        }
    }


    public function ConvertToArray(){

        $array = array(
            "TableId" => $this->TableId,
            "TableType" => $this->TableType,
            "UserId" => $this->UserId,
            "Url" => $this->Url,
            "Title" => $this->Title,
            "TitlePic" => $this->TitlePic,
            "Price" => $this->Price
        );

        return $array;
    }
} 