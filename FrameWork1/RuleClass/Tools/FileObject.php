<?php

/**
 * 提供文件操作相关的工具方法
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Tools
 * @author zhangchi
 */
class FileObject
{


    /**
     * 文件写入结果：未操作
     */
    const FILE_WRITE_RESULT_NO_ACTION = -101;
    /**
     * 文件写入结果：文件不可写，发布失败，请联系技术人员处理！
     */
    const FILE_WRITE_RESULT_CAN_NOT_WRITE = -102;
    /**
     * 文件写入结果：文件路径为空
     */
    const FILE_WRITE_RESULT_FILE_PATH_IS_EMPTY = -103;



    /**
     * 文件写入结果：未操作
     */
    const FILE_WRITE_QUEUE_RESULT_NO_ACTION = -105;
    /**
     * 文件写入结果：文件不可写，发布失败，请联系技术人员处理！
     */
    const FILE_WRITE_QUEUE_RESULT_FINISHED = 106;
    /**
     * 文件写入结果：文件路径为空
     */
    const FILE_WRITE_QUEUE_RESULT_FILE_ARRAY_EMPTY = -107;
    /**
     * 文件写入结果：成功
     */
    const FILE_WRITE_QUEUE_RESULT_SUCCESS = 105;

    /**
     * 文件移动结果：来源文件路径为空
     */
    const FILE_MOVE_RESULT_SOURCE_PATH_EMPTY = -110;
    /**
     * 文件移动结果：目标文件路径为空
     */
    const FILE_MOVE_RESULT_DESTINATION_PATH_EMPTY = -110;
    /**
     * 文件移动结果：不是文件
     */
    const FILE_MOVE_RESULT_IS_NOT_FILE = -111;
    /**
     * 文件移动结果：目标路径和来源路径相同
     */
    const FILE_MOVE_RESULT_SAME_SOURCE_PATH_AND_DESTINATION_PATH = -112;

    /**
     * 创建文件夹
     * @param string $dirPath 要创建的文件夹路径
     * @param int $accessMode 访问权限(默认777权限)
     */
    public static function CreateDir($dirPath, $accessMode = 0777)
    {
        if (!file_exists($dirPath)) {
            $arrSeparatorDirPath = explode(DIRECTORY_SEPARATOR, $dirPath);
            $separatorDirPath = "";
            for ($i = 0; $i < count($arrSeparatorDirPath); $i++) {
                $separatorDirPath .= $arrSeparatorDirPath[$i] . DIRECTORY_SEPARATOR;
                $separatorDirPath = str_replace("//", "/", $separatorDirPath);
                @mkdir($separatorDirPath, $accessMode);
            }
        }
    }

    /**
     * 写入文件
     * @param string $filePath 文件路径
     * @param mixed $fileContent 要写入文件的数据。可以是字符串、数组或数据流。
     * @param int $mode 模式 FILE_APPEND等
     * @return int 写入结果
     */
    public static function Write($filePath, $fileContent, $mode = null)
    {
        $filePath = PHYSICAL_PATH . '/' . $filePath; //加上系统路径
        $filePath = str_ireplace('/',DIRECTORY_SEPARATOR,$filePath); //适应windows平台和unix平台
        //$result = self::FILE_WRITE_ERROR_CAN_NO_ACTION;
        if (!empty($filePath)) {
            //目录处理
            $dir = dirname($filePath);
            FileObject::CreateDir($dir);
            $fp = fopen($filePath, "w+"); //打开文件指针，创建文件

            if (flock($fp,LOCK_EX)){  //独占锁
                if (!is_writable($filePath)) {
                    $result = DefineCode::FILE_OBJECT + self::FILE_WRITE_RESULT_CAN_NOT_WRITE;
                }else{
                    if($mode!=null){
                        $result = file_put_contents($filePath, $fileContent, FILE_APPEND); //该函数将返回写入到文件内数据的字节数
                    }else{
                        $result = file_put_contents($filePath, $fileContent); //该函数将返回写入到文件内数据的字节数
                    }

                }
            }else{
                $result = DefineCode::FILE_OBJECT + self::FILE_WRITE_RESULT_CAN_NOT_WRITE;
            }


            fclose($fp);
        } else {
            $result = DefineCode::FILE_OBJECT + self::FILE_WRITE_RESULT_FILE_PATH_IS_EMPTY;
        }
        return $result;
    }

    public static function Append($filePath, $fileContent){
        //$fullFilePath = PHYSICAL_PATH . '/' . $filePath; //加上系统路径
        //$fullFilePath = str_ireplace('/',DIRECTORY_SEPARATOR,$fullFilePath); //适应windows平台和unix平台

        //$oldContent = '';
        //if (file_exists($fullFilePath)) {
        //    $oldContent = file_get_contents($fullFilePath);
        //}
        //$newContent = $oldContent.$fileContent;

        self::Write($filePath,$fileContent,FILE_APPEND);
        //file_put_contents($filePath, $fileContent, FILE_APPEND);
    }

    /**
     * 根据数组数据写入文件
     * @param array $arrFileContent 写入数据信息数组 {DestinationPath,SourcePath,Content}
     * @return int 写入结果
     */
    public static function WriteQueue(&$arrFileContent = null)
    {
        if (empty($arrFileContent)) {
            $result = DefineCode::FILE_OBJECT + self::FILE_WRITE_QUEUE_RESULT_FILE_ARRAY_EMPTY; //上传数组为空
        } else {
            for ($i = 0; $i < count($arrFileContent); $i++) {
                $destinationPath = PHYSICAL_PATH . DIRECTORY_SEPARATOR . $arrFileContent[$i]["DestinationPath"];


                $destinationPath = str_ireplace("/", DIRECTORY_SEPARATOR, $destinationPath);
                $destinationPath = str_ireplace("\\", DIRECTORY_SEPARATOR, $destinationPath);
                $sourcePath = PHYSICAL_PATH . DIRECTORY_SEPARATOR . $arrFileContent[$i]["SourcePath"];

                $sourcePath = str_ireplace("/", DIRECTORY_SEPARATOR, $sourcePath);
                $sourcePath = str_ireplace("\\", DIRECTORY_SEPARATOR, $sourcePath);
                $sourceContent = $arrFileContent[$i]["Content"];

                if ($destinationPath != $sourcePath) { //写本地文件时，目标路径和来源路径一致时，不进行操作
                    if (strlen($sourceContent) > 0) { //内容
                        $writeResult = FileObject::Write($destinationPath, $sourceContent);
                    } else { //附件文件
                        $writeResult = FileObject::Move($sourcePath, $destinationPath);
                    }

                    if ($writeResult > 0) {
                        $arrFileContent[$i]["result"] = abs(DefineCode::FILE_OBJECT) + self::FILE_WRITE_QUEUE_RESULT_SUCCESS;
                    } else {
                        $arrFileContent[$i]["result"] = $writeResult;
                    }
                }
            }
            $result = abs(DefineCode::FILE_OBJECT) + self::FILE_WRITE_QUEUE_RESULT_FINISHED; //操作完成
        }
        return $result;
    }

    /**
     * 根据来源文件写入目标文件
     * @param string $destinationPath 目标文件路径
     * @param string $sourcePath 来源文件路径
     * @param string $sourceContent 写入内容
     * @return int 返回写入结果
     */
    public static function WriteSingle($destinationPath, $sourcePath, $sourceContent = '')
    {
        if (empty($sourcePath) && empty($sourceContent)) {
            $result = DefineCode::FILE_OBJECT + self::FILE_WRITE_RESULT_FILE_PATH_IS_EMPTY; //来源为空
        } else {
            $destinationPath = PHYSICAL_PATH . DIRECTORY_SEPARATOR . $destinationPath;
            $destinationPath = str_ireplace("/", DIRECTORY_SEPARATOR, $destinationPath);
            $destinationPath = str_ireplace("\\", DIRECTORY_SEPARATOR, $destinationPath);

            $sourcePath = PHYSICAL_PATH . DIRECTORY_SEPARATOR . $sourcePath;
            $sourcePath = str_ireplace("/", DIRECTORY_SEPARATOR, $sourcePath);
            $sourcePath = str_ireplace("\\", DIRECTORY_SEPARATOR, $sourcePath);

            if (strlen($sourceContent) > 0) { //内容
                $writeResult = FileObject::Write($destinationPath, $sourceContent);
            } else {
                $writeResult = FileObject::Move($sourcePath, $destinationPath);
            }

            if($writeResult > 0){
                $result = abs(DefineCode::FILE_OBJECT) + self::FILE_WRITE_QUEUE_RESULT_SUCCESS;
            }else{
                $result = $writeResult;
            }

        }
        return $result;
    }

    /**
     * 移动并重命名文件
     * @param string $sourceFilePath 来源文件路径
     * @param string $destinationFilePath 目标文件路径
     * @return boolean 返回逻辑值
     */
    public static function Move($sourceFilePath, $destinationFilePath)
    {
        if(empty($sourceFilePath)){
            return DefineCode::FILE_OBJECT + self::FILE_MOVE_RESULT_SOURCE_PATH_EMPTY;
        }
        if(empty($destinationFilePath)){
            return DefineCode::FILE_OBJECT + self::FILE_MOVE_RESULT_SOURCE_PATH_EMPTY;
        }
        if(!is_file($sourceFilePath)){
            return DefineCode::FILE_OBJECT + self::FILE_MOVE_RESULT_IS_NOT_FILE;
        }
        if($sourceFilePath == $destinationFilePath){
            return DefineCode::FILE_OBJECT + self::FILE_MOVE_RESULT_SAME_SOURCE_PATH_AND_DESTINATION_PATH;
        }
        $sourceFilePath = str_ireplace("//", "/", $sourceFilePath);
        $destinationFilePath = str_ireplace("//", "/", $destinationFilePath);
        return rename($sourceFilePath, $destinationFilePath);
    }

    /**
     * 复制文件到目标路径
     * @param string $sourceFilePath 源文件路径
     * @param string $destinationFilePath 目标文件路径
     */
    public static function Copy($sourceFilePath, $destinationFilePath)
    {
        if (file_exists($sourceFilePath)) { //判断源文件是否存在
            $destinationDirPath = dirname($destinationFilePath); //判断目标文件夹是否存在
            if (!file_exists($destinationDirPath)) { //不存在就创建
                FileObject::CreateDir($destinationDirPath);
            }
            @copy($sourceFilePath, $destinationFilePath);
        }
    }

    /**
     * 将文件夹打包成zip文件
     * @param string $sourcePath 要打包的源文件
     * @param ZipArchive $zip ZipArchive对象
     */
    public static function AddFileToZip($sourcePath, $zip)
    {
        $dirHandler = opendir($sourcePath); //打开当前文件夹由$path指定。
        while (($filename = readdir($dirHandler)) !== false) {
            if ($filename != "." && $filename != "..") { //文件夹文件名字为'.'和‘..’，不要对他们进行操作
                if (is_dir($sourcePath . "/" . $filename)) { // 如果读取的某个对象是文件夹，则递归
                    FileObject::AddFileToZip($sourcePath . "/" . $filename, $zip);
                } else { //将文件加入zip对象
                    $zip->addFile($sourcePath . "/" . $filename);
                }
            }
        }
        closedir($dirHandler);
    }

    /**
     * 递归删除目录
     * @param string $dirPath 要删除的目录路径
     * @param bool $deleteSelf  是否删除的目录本身
     * @return bool 是否成功
     */
    public static function DeleteDir($dirPath,$deleteSelf = true)
    {
        if(!is_dir($dirPath)){ //没有找到文件夹，直接返回成功
            return true;
        }
        $dirHandler = dir($dirPath);
        while (false !== ($childDirPath = $dirHandler->read())) {
            if ($childDirPath != '.' && $childDirPath != '..') {
                if (is_dir($dirPath . '/' . $childDirPath)) {
                    //echo $dirPath . '/' . $childDirPath;
                    FileObject::DeleteDir($dirPath . '/' . $childDirPath);
                } else {
                    unlink($dirPath . '/' . $childDirPath);
                }
            }
        }
        $dirHandler->close();
        if($deleteSelf){
            if (rmdir($dirPath)) {
                return true;
            } else {
                return false;
            }
        }else{
            return true;
        }

    }

    /**
     * 删除文件
     * @param string $fileName 要处理的文件名
     * @return bool 是否成功
     */
    public static function DeleteFile($fileName)
    {
        if (file_exists($fileName)) {
            if (!is_dir($fileName)) {
                if (unlink($fileName)) {
                    return true;
                } else {
                    return false;
                }
            }
        }
        return false;
    }

    /**
     * 取得文件路径
     * @param string $filePath 文件路径+文件名
     * @return mixed 取得文件路径
     */
    public static function GetDirName($filePath)
    {
        return PathInfo($filePath,PATHINFO_DIRNAME);
    }


    /**
     * 根据文件路径取得文件名（不包括扩展名）
     * @param string $filePath 文件路径
     * @return mixed 文件名（不包括扩展名）
     */
    public static function GetName($filePath)
    {
        $fileName = pathinfo($filePath,PATHINFO_BASENAME);
        $extension = pathinfo($filePath,PATHINFO_EXTENSION);
        $fileName = str_ireplace(".".$extension,"",$fileName);
        return $fileName;
    }

    /**
     * 取得文件名+扩展名
     * @param string $filePath 要处理的文件名
     * @return string 文件名+扩展名
     */
    public static function GetNameAndExtension($filePath)
    {
        return pathinfo($filePath,PATHINFO_BASENAME);
    }

    /**
     * 取得文件后缀名
     * @param string $filePath 要处理的文件名
     * @return string 文件后缀名
     */
    public static function GetExtension($filePath)
    {
        return pathinfo($filePath,PATHINFO_EXTENSION);
    }

    /**
     * 取得文件大小
     * @param string $filePath 要处理的文件名
     * @return int 文件大小
     */
    public static function  GetSize($filePath){
        return FileSize($filePath);
    }

}

?>
