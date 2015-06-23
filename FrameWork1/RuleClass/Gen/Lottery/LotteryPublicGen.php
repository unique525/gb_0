<?php

/**
 * 抽奖 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Lottery
 * @author 525
 */
class LotteryPublicGen extends BasePublicGen implements IBasePublicGen {


    /**
     * 抽奖id错误
     */
    const LOTTERY_FALSE_LOTTERY_ID = -1;
    /**
     * 用户id错误
     */
    const LOTTERY_FALSE_USER_ID = -2;
    /**
     * 未达到table id检测值
     */
    const LOTTERY_SCORE_NOE_ENOUGH = -3;
    /**
     * 未找到可用的奖项设置
     */
    const LOTTERY_LOTTERY_SET_NOT_FOUND = -4;
    /**
     * 加入获奖表错误
     */
    const LOTTERY_ERROR_WHEN_ADD_TO_AWARD_USER_TABLE = -5;
    /**
     * table id相关数据未找到
     */
    const LOTTERY_ERROR_ON_TABLE_ID = -6;
    /**
     * table type相关数据未找到
     */
    const LOTTERY_ERROR_ON_TABLE_TYPE = -7;
    /**
     * table type参与次数超过限制
     */
    const LOTTERY_TABLE_ID_LIMIT_REACHED = -8;





    /**
     * 已达到奖项设置的每日限制
     */
    const LOTTERY_DAY_LIMIT_REACHED = 1;
    /**
     * 已达到奖项设置的总限制
     */
    const LOTTERY_TOTAL_LIMIT_REACHED = 2;
    /**
     * 已达到奖项设置的同一用户次数限制
     */
    const LOTTERY_ONE_USER_LIMIT_REACHED = 3;
    /**
     * 中奖
     */
    const LOTTERY_AWARD = 4;
    /**
     * 没中奖
     */
    const LOTTERY_MISSED = 5;


    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenPublic() {
        $result = "";
        $action = Control::GetRequest("a", "");
        switch ($action) {
            case "default":
                $result= self::GenDefault();
                break;
            case "async_run_lottery":
                $result = self::AsyncRunLottery();
                break;

        }
        return $result;
    }

    /**
     * 动态模板页
     * @return mixed|string
     */
    private function GenDefault() {

        $siteId = parent::GetSiteIdByDomain();
        $tempContent = parent::GetDynamicTemplateContent();
        parent::ReplaceFirst($tempContent);

        $channelId = Control::GetRequest("channel_id",0);
        $templateTag=Control::GetRequest("temp","");
        $pageIndex = Control::GetRequest("p",0);
        $pageSize = Control::GetRequest("ps",0);
        if($pageIndex>1&&$pageSize>0){
            $tagTopCount = ($pageIndex-1)*$pageSize.",".$pageSize;
        }else{
            $tagTopCount="";
        }

        $channelPublicData = new ChannelPublicData();
        $currentChannelName = $channelPublicData->GetChannelName($channelId,true);
        $tempContent = str_ireplace("{CurrentChannelName}", $currentChannelName, $tempContent);


        $tempContent =  parent::ReplaceTemplate($tempContent,$tagTopCount);




//parent::ReplaceSiteConfig($siteId, $tempContent);


//分页
        $documentNewsPublicData=new DocumentNewsPublicData();
        $allCount=$documentNewsPublicData->GetCountInChannel($channelId);

        if($pageSize<=0){
            $pageSize=10;
        }
        if($pageIndex<=0){
            $pageIndex=1;
        }


        $navUrl = "/default.php?mod=lottery&a=default&temp=$templateTag&channel_id=$channelId&p={0}&ps=$pageSize";
        $pagerTemplate = parent::GetDynamicTemplateContent("",$siteId,"pager");
        $pagerButton = Pager::ShowPageButton($pagerTemplate, $navUrl, $allCount, $pageSize, $pageIndex);

        $tempContent = str_ireplace("{dynamic_pager_button}", $pagerButton, $tempContent);


        parent::ReplaceSiteInfo($siteId, $tempContent);
        parent::ReplaceChannelInfo($channelId, $tempContent);
        parent::ReplaceEnd($tempContent);
        return $tempContent;
    }



    /**
     * 抽奖
     * @return string
     */
    private function AsyncRunLottery(){
        $result_array=array(
            "code" => -1,
            "result" => ""
        );
        $userId=Control::GetUserId();
        $lotteryId=Control::PostOrGetRequest("lottery_id",0);

        $nowDateTime=date("Y-m-d H:i:s", time());


        if($userId<=0){
            $result_array["code"]=DefineCode::LOTTERY_PUBLIC+self::LOTTERY_FALSE_USER_ID; //用户id错误
            return Control::GetRequest("jsonpcallback","") . '('.json_encode($result_array).')';
        }

        if($lotteryId>0){

            $lotteryPublicData=new LotteryPublicData();
            $lotteryUserPublicData=new LotteryUserPublicData();
            //
            $tableType = $lotteryPublicData->GetTableType($lotteryId, true);
            $tableId=0;
            $canLottery=0;

            switch($tableType){

                case LotteryData::TABLE_TYPE_EXAM:

                    $examUserPaperId = Control::GetRequest("exam_user_paper_id", 0);

                    if($examUserPaperId<=0){
                        $result_array["code"]=DefineCode::LOTTERY_PUBLIC+self::LOTTERY_ERROR_ON_TABLE_ID; //table id相关数据错误
                        return Control::GetRequest("jsonpcallback","") . '('.json_encode($result_array).')';
                    }

                    /**get score**/
                    $examUserPaperPublicData = new ExamUserPaperPublicData();
                    $score=$examUserPaperPublicData->GetScore($userId, $examUserPaperId,false);

                    $limitContent=intval($lotteryPublicData->GetLimitContent($lotteryId, true));
                    if($score>=$limitContent){
                        /** 查看table id是否已抽过奖 **/
                        $lotteryTimesCount=$lotteryUserPublicData->GetLotteryTimeCount($userId,$tableType,$examUserPaperId,false);
                        if($lotteryTimesCount==0){
                            $canLottery=1;
                            $tableId=$examUserPaperId;
                        }else{
                            $result_array["code"]=DefineCode::LOTTERY_PUBLIC+self::LOTTERY_TABLE_ID_LIMIT_REACHED;//table id参与次数超过限制
                            return Control::GetRequest("jsonpcallback","") . '('.json_encode($result_array).')';
                        }
                    }else{
                        $result_array["code"]=DefineCode::LOTTERY_PUBLIC+self::LOTTERY_SCORE_NOE_ENOUGH;//未达到table id检测值
                        return Control::GetRequest("jsonpcallback","") . '('.json_encode($result_array).')';
                    }

                    break;

                default :
                        $result_array["code"]=DefineCode::LOTTERY_PUBLIC+self::LOTTERY_ERROR_ON_TABLE_TYPE;//table type错误
                        return Control::GetRequest("jsonpcallback","") . '('.json_encode($result_array).')';
                    break;



            }

            if($canLottery<=0){
                $result_array["code"]=DefineCode::LOTTERY_PUBLIC+self::LOTTERY_ERROR_ON_TABLE_TYPE;//table type错误
                return Control::GetRequest("jsonpcallback","") . '('.json_encode($result_array).')';
            }




            /**获取该次抽奖内 所有设置的奖项**/
            $lotterySetPublicData=new LotterySetPublicData();
            $state=0; //启用
            $arrayLotterySet=$lotterySetPublicData->GetList($lotteryId,$state);



            /**开始处理抽奖**/
            if(count($arrayLotterySet)>0&&$arrayLotterySet!=null){

                $awardLotterySetId=0; //若中奖，奖项的id
                $awardLotterySet=null; //若中奖，奖项设置的array
                $lotteryOddsType=$lotteryPublicData->GetOddsType($lotteryId,true);
                switch($lotteryOddsType){//(OddsType抽奖类型，0:纯按几率，1:按当前参与人数的比率)
                    case 0:  //按几率抽

                        /**添加进已参与抽奖表**/
                        $isAdded=$lotteryUserPublicData->CheckRepeat($lotteryId,$userId);
                        if(count($isAdded)>0&&$isAdded!=null){
                            $lotteryUserId=$isAdded["LotteryUserId"];  //已存在手机号码
                        }else{
                            $lotteryUserId=$lotteryUserPublicData->Create($lotteryId,$userId,$tableType,$tableId,$nowDateTime); //新增
                        }

                        /** roll **/
                        $randomResult=rand(1,100); //roll 100
                        $awardIfBelow=0; //几率值，roll得点数小于该值则中奖
                        for($i=0;$i<count($arrayLotterySet);$i++){  //循环所有设置的奖项  判断是否中了哪个奖
                            $awardIfBelow+=$arrayLotterySet[$i]["Odds"];  //Odds:中奖几率
                            if($randomResult<=$awardIfBelow){  //中奖
                                $awardLotterySetId=$arrayLotterySet[$i]["LotterySetId"];
                                $awardLotterySet=$arrayLotterySet[$i];
                                break;
                            }
                        }
                        break;

                    case 1: //按比率抽

                        ///**添加进已参与抽奖表**/
                        //$lotteryUserPublicData=new LotteryUserPublicData();
                        //$isAdded=$lotteryUserPublicData->CheckRepeat($lotteryId,$userId);
                        //if(count($isAdded)>0&&$isAdded!=null){
                        //    $lotteryUserId=$isAdded["LotteryUserId"];  //已存在手机号码
                        //}else{
                        //    $lotteryUserId=$lotteryUserPublicData->Create($lotteryId,$userId,$nowDateTime); //新增
                        //}
                        //比率抽奖 （暂空缺）


                        break;
                    default:

                        break;

                }



                if($awardLotterySetId>0&&$awardLotterySet!=null){
                    $lotteryAwardUserPublicData=new LotteryAwardUserPublicData();
                    $countAward=$lotteryAwardUserPublicData->GetCountOfOneLotterySet($awardLotterySetId);  //取得目前为止的获得该奖的人数






                    /**检查总获奖限额**/
                    $totalLimit=$awardLotterySet["TotalLimit"]; //总获奖限额
                    if($countAward>=$totalLimit){




                        $result_array["code"]=abs(DefineCode::LOTTERY_PUBLIC)+self::LOTTERY_TOTAL_LIMIT_REACHED; //已达到奖项设置的总限制
                        return Control::GetRequest("jsonpcallback","") . '('.json_encode($result_array).')';
                    }

                    /**检查日期获奖限额 （当前持续天数*每日获奖限额）**/
                    $dayCount=1+(int)((strtotime($nowDateTime)-strtotime($awardLotterySet["BeginTime"]))/86400);  //现在是开始抽奖的第几天
                    $nowDateLimit=$dayCount*$awardLotterySet["DayLimit"];  //到目前的天数允许的最大获奖人数



//echo "开始抽奖的天数:1+".$nowDateTime." 到 ".$awardLotterySet["BeginTime"]."=".$dayCount."<br>";
//echo "目前的天数允许的最大获奖人数:".$nowDateLimit."<br>";
//echo "目前为止的获得该奖的人数:".$countAward."<br>";



                    if($countAward>=$nowDateLimit){





                        $result_array["code"]=abs(DefineCode::LOTTERY_PUBLIC)+self::LOTTERY_DAY_LIMIT_REACHED; //已达到奖项设置的日限制
                        return Control::GetRequest("jsonpcallback","") . '('.json_encode($result_array).')';
                    }


                    /**检查同一个人的获奖限额**/
                    $lotterySetGroup=$awardLotterySet["LotterySetGroup"]; //奖项组 (例:1、2、3等奖只能拿一个,纪念奖可以拿多次，则1、2、3等奖是一个组,纪念奖是一个组)
                    $wonTimes=$lotteryAwardUserPublicData->GetCountOfOneUser($lotteryId,$lotterySetGroup,$userId);
                    $oneUserLimit=$awardLotterySet["OneUserLimit"]; //同一人获奖限额


//echo "这个人获得该类奖的次数:".$wonTimes." < ". $oneUserLimit.":同一人获得同类奖的限额<br>";


                    if($wonTimes>=0&&$wonTimes>=$oneUserLimit&&$oneUserLimit!=-1){ //若同一人限额为-1 即表示不做限制




                        $result_array["code"]=abs(DefineCode::LOTTERY_PUBLIC)+self::LOTTERY_ONE_USER_LIMIT_REACHED; //已达到同一用户允许获奖的限制
                        return Control::GetRequest("jsonpcallback","") . '('.json_encode($result_array).')';
                    }


                    /**添加进获奖表**/
                    $newAwardId=$lotteryAwardUserPublicData->Create($lotteryId,$awardLotterySetId,$lotterySetGroup,$userId,$nowDateTime);
                    if($newAwardId>0){
                        $result_array["code"]=abs(DefineCode::LOTTERY_PUBLIC)+self::LOTTERY_AWARD;//中奖
                        $result_array["result"]=$awardLotterySet["LotterySetName"];//获奖结果
                    }else{
                        $result_array["code"]=DefineCode::LOTTERY_PUBLIC+self::LOTTERY_ERROR_WHEN_ADD_TO_AWARD_USER_TABLE; //加入中奖表错误 中奖失败
                        //未知错误 中奖失败
                    }




                }else{
                    $result_array["code"]=abs(DefineCode::LOTTERY_PUBLIC)+self::LOTTERY_MISSED;//没中任何奖
                }
            }else{
                $result_array["code"]=DefineCode::LOTTERY_PUBLIC+self::LOTTERY_LOTTERY_SET_NOT_FOUND;//未找到可用的奖项设置
            }

        }else{
            $result_array["code"]=DefineCode::LOTTERY_PUBLIC+self::LOTTERY_FALSE_LOTTERY_ID;//抽奖id错误
        }







        return Control::GetRequest("jsonpcallback","") . '('.json_encode($result_array).')';
    }
} 