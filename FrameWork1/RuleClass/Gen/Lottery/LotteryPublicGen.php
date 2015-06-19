<?php

/**
 * 抽奖 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Lottery
 * @author 525
 */
class LotteryPublicGen extends BasePublicGen implements IBasePublicGen {

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenPublic() {
        $result = "";
        $action = Control::GetRequest("a", "");
        switch ($action) {
            case "async_run_lottery":
                $result = self::AsyncRunLottery();
                break;

        }
        return $result;
    }

    private function AsyncRunLottery(){
        $result="";

        $userName="name";
        $userMobile=13333333333;
        $lotteryId=1;

        $nowDateTime=date("Y-m-d H:i:s", time());

        /**添加进等待抽奖表**/
        $lotteryUserPublicData=new LotteryUserPublicData();
        $isAdded=$lotteryUserPublicData->CheckRepeat($lotteryId,$userMobile);
        if(count($isAdded)>0&&$isAdded!=null){
            $lotteryUserId=$isAdded["LotteryUserId"];  //已存在手机号码
        }else{
            $lotteryUserId=$lotteryUserPublicData->Create($lotteryId,$userName,$userMobile,$nowDateTime); //新增
        }


        /**获取该次抽奖内 所有设置的奖项**/
        $lotterySetPublicData=new LotterySetPublicData();
        $arrayLotterySet=$lotterySetPublicData->GetList($lotteryId);

        /**开始处理抽奖**/
        if(count($arrayLotterySet)>0&&$arrayLotterySet!=null){
            $randomResult=rand(1,100); //roll 100
            $awardBelow=0; //几率值，roll得点数小于该值则中奖
            $awardLotterySetId=0; //若中奖，奖项的id
            $awardLotterySet=null; //若中奖，奖项设置的array
            for($i=0;$i<count($arrayLotterySet);$i++){  //循环所有设置的奖项  判断是否中了哪个奖
                if($arrayLotterySet[$i]["OddsType"]==0){  //按几率抽  (OddsType抽奖类型，0:纯按几率，1:按当前参与人数的比率)
                    $awardBelow+=$arrayLotterySet[$i]["Odds"];  //Odds:中奖几率 / 比率
                    if($randomResult<=$awardBelow){  //中奖
                        $awardLotterySetId=$arrayLotterySet[$i]["LotterySetId"];
                        $awardLotterySet=$arrayLotterySet[$i];
                        break;
                    }
                }else{  //按比率抽

                }
            }


            if($awardLotterySetId>0&&$awardLotterySet!=null){
                $lotteryAwardUserPublicData=new LotteryAwardUserPublicData();
                $countAward=$lotteryAwardUserPublicData->GetCountOfOneLotterySet($awardLotterySetId);  //取得目前为止的获得该奖的人数

                echo "目前为止的获得该奖的人数:".$countAward;

                /**检查总获奖限额**/
                $totalLimit=$awardLotterySet["TotalLimit"]; //总获奖限额
                if($countAward>=$totalLimit){
                    $result="这个奖已经被抽完了";
                    return $result;
                }

                /**检查日期获奖限额 （当前持续天数*每日获奖限额）**/
                $dayCount=1+($nowDateTime-$awardLotterySet["BeginTime"])/86400;  //现在是开始抽奖的第几天
                $nowDateLimit=$dayCount*$awardLotterySet["DayLimit"];  //到目前的天数允许的最大获奖人数
                echo "开始抽奖的天数:".$dayCount."<br>";
                echo "目前的天数允许的最大获奖人数:".$nowDateLimit." < ". $nowDateLimit."<br>";
                echo "目前为止的获得该奖的人数:".$countAward." --- ". $nowDateLimit.":目前的天数允许的最大获奖人数<br>";
                if($countAward>=$nowDateLimit){
                    $result="今天这个奖已经抽完了，明天再试";
                    return $result;
                }


                /**检查同一个人的获奖限额**/
                $lotterySetGroup=$awardLotterySet["LotterySetGroup"]; //奖项组 (例:1、2、3等奖只能拿一个,纪念奖可以拿多次，则1、2、3等奖是一个组,纪念奖是一个组)
                $wonTimes=$lotteryAwardUserPublicData->GetCountOfOneUser($lotteryId,$lotterySetGroup,$lotteryUserId);
                $oneUserLimit=$awardLotterySet["OneUserLimit"]; //同一人获奖限额
                echo "这个人获得该类奖的次数:".$wonTimes." < ". $oneUserLimit.":同一人获得同类奖的限额<br>";
                if($wonTimes>=$oneUserLimit&&$oneUserLimit!=-1){ //若同一人限额为-1 即表示不做限制
                    $result="你已经中了奖，不能重复中奖";
                    return $result;
                }


                //再判断一次保险
                if($countAward<$nowDateLimit&&
                    $countAward<$totalLimit&&
                    ($wonTimes<$oneUserLimit||$oneUserLimit==-1)){
                    /**添加进获奖表**/
                    $newAwardId=$lotteryAwardUserPublicData->Create($lotteryId,$awardLotterySetId,$lotterySetGroup,$lotteryUserId,$nowDateTime);
                    if($newAwardId>0){
                        $result=$awardLotterySet["LotterySetName"];//获奖结果
                    }else{
                        $result="未知错误 中奖失败";
                        //未知错误 中奖失败
                    }
                }else{
                    $result="奖项已发完 或者已经中过某奖 不允许再中奖";
                    //奖项已发完 或者已经中过某奖 不允许再中奖
                }




            }else{
                $result="没中任何奖";
                //没中任何奖
            }
        }else{
            $result="未找到奖项设置";
            //未找到奖项设置
        }






        return $result;
    }
} 