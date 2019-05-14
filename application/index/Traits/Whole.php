<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/16
 * Time: 14:24
 */
namespace app\index\Traits;
use think\Db;
use think\facade\Session;
trait Whole{
    /**
     * 验证登录
     */
    public function isUser(){
        $data = Session::get('data');
        if(empty($data)){   //未登录跳入登录页
        	header('Location: http://localhost/Postbar/Public/Index/User/login');
        }
    }
    /**
     * @return mixed
     * 头部分类
     */
    public static function classify(){
        $map = array();
        $map['is_show'] = 1;
        $classify = Db::table('forum_classify')
            ->where($map)
            ->limit(7)
            ->order('date desc')
            ->select();
        return $classify;
    }
    /**
     * 验证用户会员是否到期
     */
    public static function isOutInsider(){
        $data = Session::get('data');
        if(time() + 86400 > $data['last_insider']){
            $code = 1;
        }elseif(time() > $data['last_insider']){ //用户会员已到期
            Db::table('user')->where(array('username' => $data['username']))->update(array('insider' => '普通用户'));
            $code = 2;
        }else{
            $code = 3;
        }
        return $code;
    }
    /**
     * 用户信息
     */
    public static function dataInfo(){
        $data = Session::get('data');
        $data = Db::table('user')->where(array('id' => $data['id']))->find();  //我的信息
        return $data;
    }
    /**
     * 最热帖子
     */
    public static function hotNote(){
        $map = array();
        $map['is_show'] = 1;
        $map['state'] = 1;
        $hotNote = Db::table('forum_note')
            ->where($map)
            ->limit(6)
            ->order('num','desc')
            ->select();   //最热的6条帖子(因目前帖子少,还未改成每星期的最热的6条帖子);
        return $hotNote;
    }

	/**
	 * @return mixed
	 * 今日最热帖子
	 */
    public static function todayNote(){
        $note = Db::table('forum_note')->order('num desc')->find();
        return $note;
    }
}