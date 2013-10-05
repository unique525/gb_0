<?php

/**
 * MySQL数据库操作类
 * @category iCMS
 * @package iCMS_Rules_DataBase
 * @author zhangchi
 */
class DbOperator {

    /**
     * 单例数据库实例
     * @var object
     */
    private static $instance = null;

    /**
     * 默认数据库主机名
     * @var string
     */
    public $dbHost = 'localhost';

    /**
     * 默认数据库主机端口
     * @var int
     */
    public $dbPort = 3306;

    /**
     * 默认数据库名
     * @var string
     */
    public $dbName = '';

    /**
     * 默认数据库用户名
     * @var string
     */
    public $dbUser = '';

    /**
     * 默认数据库密码
     * @var string
     */
    public $dbPass = '';

    /**
     * 数据库查询次数
     * @var int
     */
    public $queryCount = 0;

    /**
     * stmt 对象
     * @var object
     */
    public $stmt = null;

    /**
     * PDO对象
     * @var object
     */
    public $pdo = null;

    /**
     * 数据库版本
     * @var int
     */
    public $version = 0;

    /**
     * 是否调试模式
     * @var int
     */
    public $debug = 0;

    /**
     * 初始化数据库连接
     */
    private function __construct() {
        $dbi = null;
        require_once ROOTPATH . '/inc/config.inc.php';
        //require_once ROOTPATH . '/FrameWork1/SystemInc/config.inc.php';
        $this->dbHost = $dbi['dbhost'];
        $this->dbPort = $dbi['dbport'];
        $this->dbName = $dbi['dbname'];
        $this->dbUser = $dbi['dbuser'];
        $this->dbPass = $dbi['dbpass'];
        $this->connect();
        unset($dbi);
    }

    /**
     * 单例模式构建操作类
     * @return object
     */
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new DbOperator();
        }
        return self::$instance;
    }

    /**
     * 建立连接
     */
    private function connect() {
        $this->pdo = new PDO('mysql:host=' . $this->dbHost . ';port=' . $this->dbPort . ';dbname=' . $this->dbName, $this->dbUser, $this->dbPass);
        if ($this->pdo) {
            //$this->pdo->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
            $this->version = $this->pdo->getAttribute(PDO::ATTR_SERVER_VERSION);
            if ($this->version > '4.1') {
                $this->pdo->exec("SET NAMES 'UTF8'");
            }
            if ($this->version > '5.0.1') {
                $this->pdo->exec("SET sql_mode=''");
            }
        } else {
            echo 'Can not connect MySQL Server or DataBase.';
            //self::halt('Can not connect MySQL Server or DataBase.');
        }
    }

    /**
     * 查询SQL，返回第一列第一行的INT型值
     * @param string $sql 要查询的SQL语句
     * @param DataProperty $dataProperty 数据对象
     * @return int 返回第一列第一行的INT型值
     */
    public function ReturnInt($sql, DataProperty $dataProperty = null) {
        $result = $this->ReturnString($sql, $dataProperty);
        if (strlen($result) <= 0) {
            return -1;
        } else {
            return intval($result);
        }
    }

    /**
     * 查询SQL，返回第一列第一行的FLOAT型值
     * @param string $sql 要查询的SQL语句
     * @param DataProperty $dataProperty 数据对象
     * @return double 返回第一列第一行的FLOAT型值
     */
    public function ReturnFloat($sql, DataProperty $dataProperty = null) {
        $result = $this->ReturnString($sql, $dataProperty);
        return floatval($result);
    }

    /**
     * 查询SQL，返回第一列第一行的STRING型值
     * @param string $sql 要查询的SQL语句
     * @param DataProperty $dataProperty 数据对象
     * @return string 返回第一列第一行的STRING型值
     */
    public function ReturnString($sql, DataProperty $dataProperty = null) {

        $stmt = $this->pdo->prepare($sql);
        if ($dataProperty != null) {
            $this->BindStmt($stmt, $dataProperty);
        }
        $stmt->execute();
        $result = $stmt->fetchColumn();
        $stmt = null;
        return $result;
    }

    /**
     * 查询SQL，返回多行数组
     * @param string $sql 要查询的SQL语句
     * @param DataProperty $dataProperty 数据对象
     * @param int $type PDO的fetch类型
     * @return array 返回二维数组结果集
     */
    public function ReturnArray($sql, DataProperty $dataProperty = null, $type = PDO::FETCH_ASSOC) {
        $stmt = $this->pdo->prepare($sql);
        if ($dataProperty != null && !empty($dataProperty->ArrayField)) {
            $this->BindStmt($stmt, $dataProperty);
        }
        $stmt->execute();
        $result = $stmt->fetchAll($type);
        $stmt = null;
        return $result;
    }

    /**
     * 查询SQL，返回单行数组
     * @param string $sql 要查询的SQL语句
     * @param DataProperty $dataProperty 数据对象
     * @param int $type PDO的fetch类型
     * @return array 返回一维数组结果集
     */
    public function ReturnRow($sql, DataProperty $dataProperty = null, $type = PDO::FETCH_ASSOC) {
        $stmt = $this->pdo->prepare($sql);
        if ($dataProperty != null) {
            $this->BindStmt($stmt, $dataProperty);
        }
        $stmt->execute();
        $result = $stmt->fetch($type);
        $stmt = null;
        return $result;
    }

    /**
     * 查询SQL，返回LOB对象
     * @param string $sql 要查询的SQL语句
     * @param DataProperty $dataProperty 数据对象
     * @return object 返回lob对象
     */
    public function ReturnLob($sql, DataProperty $dataProperty = null) {
        $stmt = $this->pdo->prepare($sql);
        if ($dataProperty != null) {
            $this->BindStmt($stmt, $dataProperty);
        }
        $lob = null;
        $stmt->execute();
        $stmt->bindColumn(1, $lob, PDO::PARAM_LOB);
        $stmt->fetch(PDO::FETCH_BOUND);
        return $lob;
    }

    /**
     * 执行SQL，返回影响行数
     * @param string $sql 要查询的SQL语句
     * @param DataProperty $dataProperty 数据对象
     * @return int 执行结果
     */
    public function Execute($sql, DataProperty $dataProperty = null) {
        $stmt = $this->pdo->prepare($sql);
        if ($dataProperty != null) {
            $this->BindStmt($stmt, $dataProperty);
        }
        $this->pdo->beginTransaction();
        $result = $stmt->execute();
        $this->pdo->commit();
        $stmt = null;
        return $result;
    }

    /**
     * 批量执行SQL
     * @param array $arrsql 要查询的SQL语句数组
     * @param array $arrDataProperty 数据对象的数组
     * @return int 批量执行结果 
     */
    public function ExecuteBatch($arrsql, $arrDataProperty) {
        try {
            $this->pdo->beginTransaction();
            for ($i = 0; $i < count($arrsql); $i++) {
                $sql = $arrsql[$i];

                if (strlen($sql) > 0) {
                    $stmt = $this->pdo->prepare($sql);
                    $dataProperty = $arrDataProperty[$i];
                    if ($dataProperty != null) {
                        $this->BindStmt($stmt, $dataProperty);
                    }
                    $result = $stmt->execute();
                }
            }

            $this->pdo->commit();
            $stmt = null;
            return $result;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return -1;
        }
    }

    /**
     * 执行SQL，返回insert后的新的id
     * @param string $sql 要查询的SQL语句
     * @param DataProperty $dataProperty 数据对象
     * @return int insert后的新的id
     */
    public function LastInsertId($sql, DataProperty $dataProperty = null) {
        $stmt = $this->pdo->prepare($sql);
        if ($dataProperty != null) {
            $this->BindStmt($stmt, $dataProperty);
        }
        $stmt->execute();
        $result = $this->pdo->lastInsertId();
        $stmt = null;
        return $result;
    }

    /**
     * 转换 DataProperty 到 $stmt
     * @param object $stmt statement对象
     * @param DataProperty $dataProperty 数据对象
     */
    private function BindStmt(&$stmt, DataProperty $dataProperty) {
        $fields = $dataProperty->ArrayField;
        foreach ($fields as $key => $field) {
            if (!is_string($field)) {
                if (is_float($field)) {
                    $stmt->bindValue(":" . $key, floatval($field), PDO::PARAM_STR);
                } elseif (is_numeric($field)) {
                    $stmt->bindValue(":" . $key, $field, PDO::PARAM_STR);
                } else {
                    $stmt->bindValue(":" . $key, intval($field), PDO::PARAM_INT);
                }
            } else {
                $stmt->bindValue(":" . $key, $field, PDO::PARAM_STR);
            }
        }
    }

}

?>