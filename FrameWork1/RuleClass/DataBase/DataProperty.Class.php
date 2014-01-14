<?php

/**
 * 数据库字段集合类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataBase
 * @author zhangchi
 */
class DataProperty {

    /**
     * 对象数组
     * @var array
     */
    public $ArrayField = array();

    /**
     * 给数据对象增加字段和值
     * @param string $fieldName 字段名
     * @param mixed $fieldValue 字段值
     */
    public function AddField($fieldName,$fieldValue)
    {
        $this->ArrayField[$fieldName] = $fieldValue;
    }

    /**
     * 删除字段
     * @param string $fieldName
     */
    public function RemoveField($fieldName)
    {
        unset($this->ArrayField[$fieldName]);
    }

    /**
     * 清空对象数组
     */
    public function Clear()
    {
        unset($this->ArrayField);
    }
}
?>
