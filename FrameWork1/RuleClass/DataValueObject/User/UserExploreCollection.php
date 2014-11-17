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
     * 取得属性值
     * @param string $name 属性字段名称
     * @return mixed 属性值
     */
    public function __get($name)
    {
        if (method_exists($this, ($method = 'get'.$name)))
        {
            return $this->$method();
        }
        else return NULL;
    }

    /**
     * 设置属性值
     * @param string $name 属性字段名称
     * @param string $value 属性值
     */
    public function __set($name, $value)
    {
        if (method_exists($this, ($method = 'set'.$name)))
        {
            $this->$method($value);
        }
    }

    /**
     * 给数据对象增加字段值
     * @param mixed $fieldValue 字段值
     */
    public function AddField($fieldValue)
    {

        $doAdd = true;
        if(isset($this->UserExplores) &&
            is_array($this->UserExplores) &&
            count($this->UserExplores)>0 &&
            !empty($this->UserExplores)
        ){
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