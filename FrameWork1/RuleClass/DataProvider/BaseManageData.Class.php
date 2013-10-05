<?php

/**
 * 后台数据业务层基类 
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider
 * @author zhangchi
 */
class BaseManageData {
    /**
     * 数据库操作对象的实例
     * @var DbOperator 返回数据库操作对象 
     */
    protected $dbOperator = null;
    function __construct() {
        $this->dbOperator = DbOperator::getInstance();
    }

}

?>
