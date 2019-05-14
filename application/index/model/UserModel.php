<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/7
 * Time: 13:43
 */
namespace app\index\Model;
use app\index\Traits\Date;
use think\Model;
use think\Db;
use think\facade\Session;
use think\facade\Cookie;
class UserModel extends Model{
    /**
     * 去除前缀的表名
     * @var string
     */
    protected $table = "yf_user";
    /**
     * @var $data
     * 用户信息
     */
    protected $data;

    /**
     * UserModel constructor.
     * @param $data
     * @throws \Exception
     * 初始化
     */
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * @param $data
     * @throws \Exception
     * 登录
     */
    public function login($data){
        if(isset($data['inPass'])){
            Cookie::set('user', [
                'inPass'    =>  $data['inPass'],
                'username'  =>  $data['username'],
                'password'  =>  $data['password']
            ], 86400); //记住密码
        }else{
            Cookie::set('user',null); //删除之前保留密码标记
        }
        $start_time = Date::getNowStartTime();
        $end_time = Date::getNowEndTime();
        if($data['username'] == ""){
            throw new \Exception('{"code":"0" , "msg":"账号是必填的!"}');
        }
        $user = Db::table('user')->where(array('username' => $data['username']))->find();
        $log = Db::table('forum_log')
            ->where(['username' => $data['username'],'log' => '密码错误'])
            ->whereTime('date','between',array($start_time , $end_time))
            ->count();//查询日志报错次数
        $msg = [
            'username' => $user['username'],
            'login_type'    =>  2,
            'date'  =>  Date::getNowTime(),
            'is_show'   =>  1
        ];//登录日志信息
//	    if(Session::get('captcha') != $data['checkNum']){
//		    throw new \Exception('{"code":"0" , "msg":"验证码不正确!"}');
//	    }
        if($log > 5){  //一个账号登录密码连续错5次以上,无法登录
            throw new \Exception('{"code":"0" , "msg":"发现异常登录,请稍后重试!"}');
        }elseif($user == null){
            throw new \Exception('{"code":"0" , "msg":"账号不存在!"}');
        }elseif($data['password'] != $user['password']){
            Db::table('forum_log')->data($msg)->insert(['log' => '密码错误']);
            throw new \Exception('{"code":"0" , "msg":"密码错误!"}');
        }else{
            Session::set('data',$user);
            Db::table('forum_log')->data($msg)->insert(['log' => '登录成功']);
            Db::table('user')->where(['username' => $data['username']])->update(['state' => '在线']);
            throw new \Exception('{"code":"1" , "msg":"登录成功!"}');
        }
    }

    /**
     * @param $data
     * @throws \Exception
     * 注册
     */
    public function register($data){
        if($data['password'] != $data['password2']){
            throw new \Exception('{"code":"0" , "msg":"两次密码不一致!"}');
        }elseif(!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $data['email'])){
            throw new \Exception('{"code":"0" , "msg":"邮箱格式错误!"}');
        }else{
            $insert = Db::table('user')->strict(false)->insert($data);
            if($insert){
                throw new \Exception('{"code":"1" , "msg":"注册成功!"}');
            }else{
                throw new \Exception('{"code":"0" , "msg":"注册失败!"}');
            }
        }
    }
}