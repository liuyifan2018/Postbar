<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/22
 * Time: 10:15
 */

namespace app\admin\model;
use think\Db;
use think\Model;

class PassModel extends Model
{
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
     * PassModel constructor.
     * @param $data
     * @throws \Exception
     * 初始化
     */
    public function __construct($data)
    {
        parent::__construct();
        if( empty($data) ) throw new \Exception('用户未登录!');
        $this->data = $data;
    }
    /**
     * @param $user
     * @throws \Exception
     * 验证账号
     */
    public function type($user){
        if(empty($user)) {
            throw new \Exception('{"code":"0" , "msg": "请填写用户名!"}');
        }
        $msg = Db::table('user')->where(array('username' => $user))->find();
        if($msg == null){
            throw new \Exception('{"code":"0" , "msg": "用户名有误,请重新输入!"}');
        }else{
            throw new \Exception('{"code":"1" , "msg": "用户名正确!"}');
        }
    }

    /**
     * @param $username
     * @return mixed
     * 选择方式
     */
    public function tion($username){
        $problem = Db::table('forum_problem')->where(['username' => $username])->select();
        return $problem;
    }

    /**
     * @param $msg
     * @throws \Exception
     * 密保问题
     */
    public function ProblemPass($msg){
        switch ($msg){
            case empty($msg['username']):throw new \Exception('{"code":"0" , "msg":"用户登录失效!"}');break;
            case $msg['problem'] == "": throw new \Exception('{"code":"0" , "msg":"请选择问题!"}');break;
            case $msg['answer'] == "":throw new \Exception('{"code":"0" , "msg":"请输入答案!"}');break;
        }
       $problem = Db::table('forum_problem')->where($msg)->find();
       if($problem == null){
           throw new \Exception('{"code":"0" , "msg":"答案错误!"}');
        }else{
           throw new \Exception('{"code":"1" , "msg":"答案正确!"}');
        }
    }
    /**
     * @param $msg
     * @throws \Exception
     * 修改密码
     */
    public function upPass($msg){
        if( empty($msg) ){
            throw new \Exception('{"code":"0" , "msg":"修改失败,请重试!"}');
        }
        if($msg['password'] == ""){
            throw new \Exception('{"code":"0" , "msg":"请填写密码!"}');
        }elseif ($msg['password2'] != $msg['password']){
            throw new \Exception('{"code":"0" , "msg":"两次密码不一致!"}');
        }else{
            Db::table('user')
                ->where(array('username' => $msg['username']))
                ->update(array('password' => $msg['password']));
            throw new \Exception('{"code":"1" , "msg":"修改成功!"}');
        }
    }
    /**
     * @param $msg
     * @throws \Exception
     * 申诉提交
     */
    public function Appeal($msg){
        if( empty($msg) ){
            throw new \Exception('{"code":"0" , "msg":"信息提交失败!"}');
        }
        $arr = ['username','password','number','email','name'];
        for($i = 0 ; $i <= count($arr); $i++){
            if($msg[$arr[$i]] == ""){
                throw new \Exception('{"code":"0" , "msg":"请完善信息,利于你更好的找回账号!"}');
            }
        }
        Db::table('forum_appeal')->insert($msg);
        throw new \Exception('{"code":"1" , "msg":"信息已提交,请耐心等待回复!"}');
    }
}