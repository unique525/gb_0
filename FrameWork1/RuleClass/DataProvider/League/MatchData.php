<?php
/**
 * Created by PhpStorm.
 * User: 525
 * Date: 2016/3/19
 * Time: 14:16
 */
class MatchData {
    /** 比赛状态定义 */

    /**
     * 未开始 0
     */
    const STATE_PRE = 0;


    /**
     * 已开始 1
     */
    const STATE_GOING = 1;


    /**
     * 已结束 2
     */
    const STATE_END = 2;


    /**
     * 停用 100
     */
    const STATE_REMOVED = 100;


    /**
     * 主队胜
     */
    const RESULT_HOME_WIN = 1;
    /**
     * 客队胜
     */
    const RESULT_GUEST_WIN = 2;
    /**
     * 平
     */
    const RESULT_TIE = 3;
    /**
     * 主队点球大战胜
     */
    const RESULT_HOME_WIN_PENALTY = 4;
    /**
     * 客队点球大战胜
     */
    const RESULT_GUEST_WIN_PENALTY = 5;

}