<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/20
 * Time: 16:18
 */
namespace app\admin\Traits;
use think\facade\Session;
use think\Db;
trait Whole{
    /**
     * 验证登录
     */
    public function isUser(){
        $data = Session::get('data');
        if(empty($data)){
            $this->redirect('user/login');  //用户未登录跳入登录页
            exit;
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
     * 用户信息
     */
    public static function dataInfo(){
        $data = Session::get('data');
        $data = Db::table('user')->where(array('id' => $data['id']))->find();  //我的信息
        return $data;
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
}