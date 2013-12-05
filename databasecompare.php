<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<form action="databasecompare.php" method="post">

    数据库1：<br />
    主机：<input name="dbhost1" type="text" value="localhost" /><br />
    端口：<input name="dbport1" type="text" value="3306" /><br />
    数据库名：<input name="dbname1" type="text" /><br />
    帐号：<input name="dbuser1" type="text" value="root" /><br />
    密码：<input name="dbpass1" type="text" /><br />

    =====================================<br />
    数据库2：<br />
    主机：<input name="dbhost2" type="text" value="localhost" /><br />
    端口：<input name="dbport2" type="text" value="3306" /><br />
    数据库名：<input name="dbname2" type="text" /><br />
    帐号：<input name="dbuser2" type="text" value="root" /><br />
    密码：<input name="dbpass2" type="text" /><br />

    <input type="submit" value="比较" />

</form>


<?php
$dbHost1 = $_POST["dbhost1"];
$dbPort1 = $_POST["dbport1"];
$dbName1 = $_POST["dbname1"];
$dbUser1 = $_POST["dbuser1"];
$dbPass1 = $_POST["dbpass1"];

$dbHost2 = $_POST["dbhost2"];
$dbPort2 = $_POST["dbport2"];
$dbName2 = $_POST["dbname2"];
$dbUser2 = $_POST["dbuser2"];
$dbPass2 = $_POST["dbpass2"];


if(!empty($_POST)){
$dbOperator1 = DbOperatorForClient::getInstance($dbHost1, $dbPort1, $dbName1, $dbUser1, $dbPass1);
$dbOperator2 = DbOperatorForClient::getInstance($dbHost2, $dbPort2, $dbName2, $dbUser2, $dbPass2);
$sql = "show tables";
$result1 = $dbOperator1->ReturnArray($sql, null);
$result2 = $dbOperator2->ReturnArray($sql, null);

$arr1 = array();
foreach ($result1 as $key1 => $val1){
    $arr1[] = $val1["Tables_in_$dbName1"];
}
$arr2 = array();
foreach ($result2 as $key2=> $val2){
    
    $arr2[] = $val2["Tables_in_$dbName2"];
}

for($i=0;$i<count($arr1);$i++){
    //echo $arr1[$i]."<br>";
    echo $arr1[$i].":".array_key_exists($arr1[$i], $arr2)."<br>";
}

//print_r(array_diff($result1, $result2));

//print_r($arr2);
//print_r($result2);

}
//$dbOperatorForClient = new DbOperatorForClient();
//$dbOperatorForClient->dbHost = $dbHost1;
//echo $dbOperatorForClient;

/**
 * MySQL数据库操作类
 * @category iCMS
 * @package iCMS_Rules_DataBase
 * @author zhangchi
 */
class DbOperatorForClient {

    /**
     * 单例数据库实例
     * @var object
     */
    private static $instance = null;

    /**
     * 默认数据库主机名
     * @var string
     */
    public $dbHost = '';

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
    private function __construct($dbHost, $dbPort, $dbName, $dbUser, $dbPass) {
        //$dbi = null;
        //require_once ROOTPATH . '/inc/config.inc.php';
        //$this->dbHost = $dbi['dbhost'];
        //$this->dbPort = $dbi['dbport'];
        //$this->dbName = $dbi['dbname'];
        //$this->dbUser = $dbi['dbuser'];
        //$this->dbPass = $dbi['dbpass'];
        $this->dbHost = $dbHost;
        $this->dbPort = $dbPort;
        $this->dbName = $dbName;
        $this->dbUser = $dbUser;
        $this->dbPass = $dbPass;
        $this->connect();
        //unset($dbi);
    }

    /**
     * 单例模式构建操作类
     * @return object
     */
    public static function getInstance($dbHost, $dbPort, $dbName, $dbUser, $dbPass) {
        //if (self::$instance == null) {

            self::$instance = new DbOperatorForClient($dbHost, $dbPort, $dbName, $dbUser, $dbPass);
        //}
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
