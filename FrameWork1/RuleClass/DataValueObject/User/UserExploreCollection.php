<?php

/**
 * 会员浏览记录集合对象类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataValueObject_UploadFile
 * @property array $UserExplores 会员浏览记录集合
 * @author zhangchi
 */
class UserExploreCollection {

    /**
     * 对象数组
     * @var array
     */
    private $UserExplores = array();

    /**
     * @param mixed $UserExplores
     */
    public function setUserExplores($UserExplores)
    {
        $this->UserExplores = $UserExplores;
        if (count($this->UserExplores) > 5) {
            //只保存5条访问记录
            array_shift($this->UserExplores);
        }
    }



    /**
     * @return mixed
     */
    public function getUserExplores()
    {
        return $this->UserExplores;
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



    /**
     * 给数据对象增加字段值
     * @param mixed $fieldValue 字段值
     */
    public function AddField($fieldValue)
    {

        $doAdd = true;
        if(count($this->UserExplores)>0){

            for($i=0;$i<count($this->UserExplores);$i++){

                $userExploreTableId = $this->UserExplores[$i]["TableId"];
                $userExploreTableType = $this->UserExplores[$i]["TableType"];


                if($fieldValue["TableId"] == $userExploreTableId
                    && $fieldValue["TableType"] == $userExploreTableType
                ){
                    $doAdd = false;
                }

            }
        }
        if($doAdd){
            $this->UserExplores[] = $fieldValue;
        }

    }

    /**
     * 删除字段
     * @param string $fieldName
     */
    public function RemoveField($fieldName)
    {
        unset($this->UserExplores[$fieldName]);
    }

    /**
     * 清空对象数组
     */
    public function Clear()
    {
        unset($this->UserExplores);
    }



} 