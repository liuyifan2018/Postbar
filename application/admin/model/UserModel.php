<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/22
 * Time: 11:49
 */
namespace app\admin\model;
use think\Model;
use think\Db;
use think\facade\Session;
class UserModel extends Model{
    /**
     * @var string
     * 数据库表名
     */
    protected $table = 'user';
    /**
     * @var $data
     * 用户信息
     */
    protected $data;

    /**
     * UserModel constructor.
     * @throws Exception
     * 初始化
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param $data
     * @throws Exception
     * 登录
     */
    public function login($data){
        $start_time = strtotime(date('Y-m-d',time()));
        $end_time = $start_time + 86399;
        $user = Db::table('user')->where(array('username' => $data['username']))->find();
        $log = Db::table('log')
            ->where(array('username' => $data['username'],'log' => '密码错误'))
            ->whereTime('date','between',array($start_time , $end_time))
            ->count();
        if($log > 5){  //一个账号登录密码连续错5次以上,无法登录
            throw new \Exception('{"code":0 , "msg": "发现异常登录,请稍后重试!"}');
        }
        $msg = array(
            'username' => $user['username'],
            'login_type'    =>  1,
            'date'  =>  time(),
            'is_show'   =>  1
        );//登录日志信息
        if($user == null){
            throw new \Exception('{"code":0 , "msg": "账号不存在!"}');
        }elseif($data['password'] != $user['password']){
            Db::table('log')->data($msg)->insert(array('log' => '密码错误'));
            throw new \Exception('{"code":0 , "msg": "密码错误!"}');
        }elseif($user['insider'] == "普通用户") {
            throw new \Exception('{"code":0 , "msg": "非会员不能进入!"}');
        }else {
            Session::set('data',$user);
            Db::table('log')->data($msg)->insert(array('log' => '登录成功'));
            Db::table('user')->where(array('username' => $data['username']))->update(array('state' => '在线'));
            throw new \Exception('{"code":1 , "msg": "登录成功!"}');
        }
    }
}