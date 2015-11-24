<?php

/**
 * MySQL数据库操作类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataBase
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
    private $dbHost = 'localhost';

    /**
     * 默认数据库主机端口
     * @var int
     */
    private $dbPort = 3306;

    /**
     * 默认数据库名
     * @var string
     */
    private $dbName = '';

    /**
     * 默认数据库用户名
     * @var string
     */
    private $dbUser = '';

    /**
     * 默认数据库密码
     * @var string
     */
    private $dbPass = '';

    /**
     * PDO对象
     * @var object
     */
    private $pdo = null;

    /**
     * 数据库版本
     * @var int
     */
    private $version = 0;
    /**
     * 输出执行SQL和时间
     * @var int
     */
    private $debugExecuteTime = 0;


    private $openLog = false;

    /**
     * 初始化数据库连接
     */
    private function __construct() {
        $databaseInfo = explode('|',DATABASE_INFO);
        if (!empty($databaseInfo)) {
            $this->dbHost = $databaseInfo[0];
            $this->dbPort = $databaseInfo[1];
            $this->dbName = $databaseInfo[2];
            $this->dbUser = $databaseInfo[3];
            $this->dbPass = $databaseInfo[4];
            if(isset($databaseInfo[5])){
                $this->debugExecuteTime = intval($databaseInfo[5]);
            }
            $this->connect();

            $this->openLog = true;

        }
        unset($databaseInfo);
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
    public function GetInt($sql, DataProperty $dataProperty = null) {
        $result = $this->GetString($sql, $dataProperty);
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
    public function GetFloat($sql, DataProperty $dataProperty = null) {
        $result = $this->GetString($sql, $dataProperty);
        return floatval($result);
    }

    /**
     * 查询SQL，返回第一列第一行的STRING型值
     * @param string $sql 要查询的SQL语句
     * @param DataProperty $dataProperty 数据对象
     * @return string 返回第一列第一行的STRING型值
     */
    public function GetString($sql, DataProperty $dataProperty = null) {
        $timeStart = Control::GetMicroTime();
        $stmt = $this->pdo->prepare($sql);
        if ($dataProperty != null) {
            $this->BindStmt($stmt, $dataProperty);
        }
        $stmt->execute();
        $result = $stmt->fetchColumn();

        $stmt = null;
        $timeEnd = Control::GetMicroTime();
        if($this->debugExecuteTime>0){
            echo "TIME:".($timeEnd - $timeStart)." [".$sql."]<br />";
        }

        if($this->openLog){
            $logSql = str_ireplace("\n",'',$sql);
            $logSql = str_ireplace("\r",'',$logSql);

            $param = '';
            //if($dataProperty!= null && is_array($dataProperty->ArrayField) && !empty($dataProperty->ArrayField)){
                //$param = implode(',',$dataProperty->ArrayField);
            //}

            FileObject::Append('log.txt',"TIME:".($timeEnd - $timeStart).
                " [".str_ireplace("\n",'',$logSql)."]\n".$param."\n----------------------------------------\n");
        }
        return $result;
    }

    /**
     * 查询SQL，返回多行数组
     * @param string $sql 要查询的SQL语句
     * @param DataProperty $dataProperty 数据对象
     * @param int $type PDO的fetch类型
     * @return array 返回二维数组结果集
     */
    public function GetArrayList($sql, DataProperty $dataProperty = null, $type = PDO::FETCH_ASSOC) {

        $timeStart = Control::GetMicroTime();


        $stmt = $this->pdo->prepare($sql);
        if ($dataProperty != null && !empty($dataProperty->ArrayField)) {
            $this->BindStmt($stmt, $dataProperty);
        }
        $stmt->execute();
        $result = $stmt->fetchAll($type);
        $stmt = null;

        $timeEnd = Control::GetMicroTime();
        if($this->debugExecuteTime>0){
            echo "TIME:".($timeEnd - $timeStart)." [".str_ireplace("\n",'',$sql)."]<br />";
        }

        if($this->openLog){
            $logSql = str_ireplace("\n",'',$sql);
            $logSql = str_ireplace("\r",'',$logSql);

            $param = '';
            //if($dataProperty!= null && is_array($dataProperty->ArrayField) && !empty($dataProperty->ArrayField)){
                //$param = implode(',',$dataProperty->ArrayField);
            //}

            FileObject::Append('log.txt',"TIME:".($timeEnd - $timeStart).
                " [".str_ireplace("\n",'',$logSql)."]\n".$param."\n----------------------------------------\n");}
        return $result;
    }

    /**
     * 查询SQL，返回单行数组
     * @param string $sql 要查询的SQL语句
     * @param DataProperty $dataProperty 数据对象
     * @param int $type PDO的fetch类型
     * @return array 返回一维数组结果集
     */
    public function GetArray($sql, DataProperty $dataProperty = null, $type = PDO::FETCH_ASSOC) {
        $timeStart = Control::GetMicroTime();
        $stmt = $this->pdo->prepare($sql);
        if ($dataProperty != null) {
            $this->BindStmt($stmt, $dataProperty);
        }
        $stmt->execute();
        $result = $stmt->fetch($type);
        $stmt = null;
        $timeEnd = Control::GetMicroTime();
        if($this->debugExecuteTime>0){
            echo "TIME:".($timeEnd - $timeStart)." [".str_ireplace("\n",'',$sql)."]<br />";
        }
        if($this->openLog){
            $logSql = str_ireplace("\n",'',$sql);
            $logSql = str_ireplace("\r",'',$logSql);
            $param = '';
            //if($dataProperty!= null && is_array($dataProperty->ArrayField) && !empty($dataProperty->ArrayField)){
                //$param = implode(',',$dataProperty->ArrayField);
            //}

            FileObject::Append('log.txt',"TIME:".($timeEnd - $timeStart).
                " [".str_ireplace("\n",'',$logSql)."]\n".$param."\n----------------------------------------\n");
        }

        return $result;
    }

    /**
     * 查询SQL，返回LOB对象
     * @param string $sql 要查询的SQL语句
     * @param DataProperty $dataProperty 数据对象
     * @return object 返回lob对象
     */
    public function GetLob($sql, DataProperty $dataProperty = null) {
        $timeStart = Control::GetMicroTime();
        $stmt = $this->pdo->prepare($sql);
        if ($dataProperty != null) {
            $this->BindStmt($stmt, $dataProperty);
        }
        $lob = null;
        $stmt->execute();
        $stmt->bindColumn(1, $lob, PDO::PARAM_LOB);
        $stmt->fetch(PDO::FETCH_BOUND);

        $timeEnd = Control::GetMicroTime();
        if($this->debugExecuteTime>0){
            echo "TIME:".($timeEnd - $timeStart)." [".str_ireplace("\n",'',$sql)."]<br />";
        }
        if($this->openLog){
            $logSql = str_ireplace("\n",'',$sql);
            $logSql = str_ireplace("\r",'',$logSql);

            $param = '';
            //if($dataProperty!= null && is_array($dataProperty->ArrayField) && !empty($dataProperty->ArrayField)){
                //$param = implode(',',$dataProperty->ArrayField);
            //}

            FileObject::Append('log.txt',"TIME:".($timeEnd - $timeStart).
                " [".str_ireplace("\n",'',$logSql)."]\n".$param."\n----------------------------------------\n");
        }
        return $lob;
    }

    /**
     * 执行SQL，返回影响行数
     * @param string $sql 要查询的SQL语句
     * @param DataProperty $dataProperty 数据对象
     * @return int 执行结果
     */
    public function Execute($sql, DataProperty $dataProperty = null) {
        $timeStart = Control::GetMicroTime();
        $stmt = $this->pdo->prepare($sql);
        if ($dataProperty != null) {
            $this->BindStmt($stmt, $dataProperty);
        }
        $this->pdo->beginTransaction();
        $result = $stmt->execute();
        $this->pdo->commit();
        $stmt = null;
        $timeEnd = Control::GetMicroTime();
        if($this->debugExecuteTime>0){
            echo "TIME:".($timeEnd - $timeStart)." [".str_ireplace("\n",'',$sql)."]<br />";
        }
        if($this->openLog){
            $logSql = str_ireplace("\n",'',$sql);
            $logSql = str_ireplace("\r",'',$logSql);

            $param = '';
            //if($dataProperty!= null && is_array($dataProperty->ArrayField) && !empty($dataProperty->ArrayField)){
                //$param = implode(',',$dataProperty->ArrayField);
            //}

            FileObject::Append('log.txt',"TIME:".($timeEnd - $timeStart).
                " [".str_ireplace("\n",'',$logSql)."]\n".$param."\n----------------------------------------\n");
        }
        return $result;
    }

    /**
     * 批量执行SQL
     * @param array $sqlList 要查询的SQL语句数组
     * @param array $arrDataProperty 数据对象的数组
     * @return int 批量执行结果
     */
    public function ExecuteBatch($sqlList, $arrDataProperty) {

        try {
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE,  PDO::ERRMODE_EXCEPTION);//开启异常处理


            $result = -1;
            $this->pdo->beginTransaction();
            for ($i = 0; $i < count($sqlList); $i++) {
                $sql = $sqlList[$i];

                if (strlen($sql) > 0) {
                    $timeStart = Control::GetMicroTime();
                    $stmt = $this->pdo->prepare($sql);
                    $dataProperty = $arrDataProperty[$i];
                    if ($dataProperty != null) {
                        $this->BindStmt($stmt, $dataProperty);
                    }
                    $result = $stmt->execute();
                    if(intval($result)<=0){
                        throw new PDOException("affected_rows<=0");//抛出异常
                    }

                    $timeEnd = Control::GetMicroTime();
                    if($this->debugExecuteTime>0){
                        echo "TIME:".($timeEnd - $timeStart)." [".implode("|",str_ireplace("\n",'',$sqlList))."]<br />";
                    }
                    if($this->openLog){
                        $logSql = str_ireplace("\n",'',$sqlList);
                        $logSql = str_ireplace("\r",'',$logSql);

                        $param = '';
                        //if($dataProperty!= null && is_array($dataProperty->ArrayField) && !empty($dataProperty->ArrayField)){
                            //$param = implode(',',$dataProperty->ArrayField);
                        //}

                        FileObject::Append('log.txt',"TIME:".($timeEnd - $timeStart).
                            " [".str_ireplace("\n",'',$logSql)."]\n".$param."\n----------------------------------------\n");
                    }
                }
            }

            $this->pdo->commit();
            $stmt = null;



            return $result;
        } catch (PDOException $e) {
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

            if (is_int($field)){
                $stmt->bindValue(":" . $key, intval($field), PDO::PARAM_INT);
            }elseif (is_float($field)){
                $stmt->bindValue(":" . $key, floatval($field), PDO::PARAM_STR);
            }else{
                $stmt->bindValue(":" . $key, $field, PDO::PARAM_STR);
            }

            /**
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
             */
        }
    }
}

?>