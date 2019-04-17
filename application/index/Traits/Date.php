<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/4/1
 * Time: 19:58
 */
namespace app\index\Traits;
trait Date{
    /**
     * @return int
     * 获取当前时间戳
     */
    public static function getNowTime(){
        return time();
    }
    /**
     * @return false|string
     * 获取当前时间格式
     */
    public static function getNowDate(){
        return date('Y-m-d H:i:s',time());
    }
    /**
     * @return int
     * 获取当月有多少天
     */
    public static function getNowMonth(){
        $month = cal_days_in_month(CAL_GREGORIAN,date('m'),date('Y'));
        return $month;
    }
    /**
     * @return false|int
     * 获取当天开始时间戳
     */
    public static function getNowStartTime(){
        $start_time = strtotime(date('Y-m-d',time()));
        return $start_time;
    }
    /**
     * @return false|int
     * 获取当天结束时间戳
     */
    public static function getNowEndTime(){
        $end_time = self::getNowStartTime() + 86399;
        return $end_time;
    }
    /**
     * @param $date
     * @return false|string
     * 返回时间
     */
    public static function getDate( $date ){
        return date('Y-m-d H:i:s',$date);
    }
    /**
     * @return false|int
     * 返回本周开始时间戳
     */
    public static function getBeginWeek(){
        $w=date('w')? date('w'):7;
        return mktime(0,0,0,date('m'),date('d')-$w+1,date('Y'));
    }
    /**
     * @return false|int
     * 返回本周结束时间戳
     */
    public static function getEndWeek(){
        $w=date('w')? date('w'):7;
        return mktime(0,0,0,date('m'),date('d')-$w+8,date('Y'));
    }
    /**
     * @return false|int
     * 返回本月开始时间戳
     */
    public static function getBeginMonth(){
        return strtotime(date('Y-m', time()) . '-01 00:00:00');
    }
    /**
     * @return false|int
     * 返回本月结束时间戳
     */
    public static function getEndMonth(){
        return strtotime(date('Y-m', time()) . '-' . date('t', time()) . ' 23:59:59');
    }
    /**
     * @return false|int
     * 返回今年开始时间戳
     */
    public static function getBeginYear(){
        return mktime(0, 0,0, 1, 1, date('Y', time()));
    }
    /**
     * @return false|int
     * 返回今年结束时间戳
     */
    public static function getEndYear(){
        return mktime(0, 0, 0, 12, 31, date('Y', time()));
    }
}