<?php

/**
 * �ͻ��� �ͻ�����ַת�� ������
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Client
 * @author zhangchi
 */
class ClientDirectUrlClientGen extends BaseClientGen implements IBaseClientGen {

    /**
     * ��������
     * @return string ����ִ�н��
     */
    public function GenClient(){
        $result = "";
        $function = Control::GetRequest("f", "");

        switch ($function) {
            /**
             * ת��
             */
            case "direct":
                self::GenDirect();
                break;

        }
        $result = str_ireplace("{function}", $function, $result);
        return $result;
    }

    private function GenDirect(){


        


    }

}