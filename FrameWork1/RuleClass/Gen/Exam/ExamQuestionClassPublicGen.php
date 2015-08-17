<?php


class ExamQuestionClassPublicGen extends BasePublicGen implements IBasePublicGen{
    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenPublic()
    {
        $result = "";
        $method = Control::GetRequest("m", "");
        switch ($method) {

        }

        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }
} 