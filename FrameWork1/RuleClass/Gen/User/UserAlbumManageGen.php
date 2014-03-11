<?php

/**
 * Created by PhpStorm.
 * User: zcmzc
 * Date: 14-1-21
 * Time: 下午12:12
 */
class UserAlbumManageGen {

    /**
     * 引导方法123
     * @return string 返回执行结果
     */
    public function Gen() {
        $result = "";
        $method = Control::GetRequest("m", "");
        switch ($method) {
            case "create":
                $result = self::GenCreate();
                break;
            case "modify":
                $result = self::GenModify();
                break;
            case "listformanage":
                $result = self::GenListForManage();
                break;
            case "removebin":
                $result = self::GenRemoveBin();
                break;
            case "foreignlist":
                $result = self::GenForeignList();
                break;
            case "lookalbum":
                $result = self::GenLookAlbum();
                break;
            case "createmainpic":
                $result = self::GenCreateMainPic();
                break;
            case "export":
                $result = self::GenExport();
                break;
            case "statistics":
                $result = self::GenStatistics();
                break;
//            可能需要改模板路径  比如这里要加个 这样更加符合框架
//            case "useralbumpic":
//                break;
        }
        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }

    private function GenCreate() {
        
    }

    private function GenModify() {
        
    }

    private function GenListForManage() {
        
    }

    private function GenRemoveBin() {
        
    }

    private function GenForeignList() {
        
    }

    private function GenLookAlbum() {
        
    }

    private function GenCreateMainPic() {
        
    }

    private function GenExport() {
        
    }

    private function GenStatistics() {
        
    }

}

