<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/20
 * Time: 16:42
 */
namespace app\admin\model;
use app\admin\Traits\Date;
use think\Exception;
use think\Model;
use think\Db;
class AdminModel extends Model{
    /**
     * @var string
     * 数据库表名
     */
    protected $dbName = "user";
    /**
     * @var $data
     * 用户信息
     */
    protected $data;

    /**
     * Admin constructor.
     * @param $username
     * @throws Exception
     */
    public function __construct( $data )
    {
        parent::__construct();
        if( empty($data) ) throw new Exception('用户未登录!');
        $this->data = $data;
    }
    /**
     * @param $tion
     * @return null
     * 用户列表
     */
    public function userList($tion){
        if($tion == 1){
            $userList['data'] = Db::table('user')
                ->where(array('ad' => 0))
                ->paginate(10,false,['query' => request()->param()]);
            $userList['count'] = count($userList['data']);
        }elseif($tion == 2){
            $userList['data'] = Db::table('user')
                ->where(array('ad' => 1))
                ->paginate(10,false,['query' => request()->param()]);
            $userList['count'] = count($userList['data']);
        }else{
            $userList = null;
        }
        return $userList;
    }

    /**
     * @param $data
     * @throws \Exception
     * 添加用户
     */
    public function addUser($data){
        if(empty($data)){
            $error = array('code' => 0 ,'msg' => '信息提交失败!');
            throw new \Exception($error);
        }
        $data['date'] = Date::getNowTime();
        $data['insider'] = "普通用户";
        $data['state'] = "离线";
        $data['ad'] = 0;
        $arr = ['title','password','number','email'];
        for ($i = 0; $i < count($arr); $i++){
            if($data[$arr[$i]] == ""){
                throw new \Exception('{"code": "0" , "msg": "必填项不能为空!"}');
            }
        }
        if($data['password'] != $data['password2']){
            throw new \Exception('{"code": "0","msg": "两次密码不一致!"}');
        }else{
            Db::table($this->table)->insert($data);
            throw new \Exception('{"code": "1" , "msg": "添加成功!"}');
        }
    }

    /**
     * @param $uid
     * @throws \Exception
     * 操作用户权限
     */
    public function is_use($uid){
        if(empty($uid) || $uid < 0){
           $error = array('code' => 0 ,'msg' => '用户不存在!');
            throw new \Exception($error);
        }
        $data = Db::table($this->table)->where(array('id' => $uid))->find();
        if($data['ad'] == 1){
            throw new \Exception('{"code": "0" , "msg": "无法权限超管!"}');
        }elseif($data['ad'] == 0){
            Db::table($this->table)->where(array('id' => $uid))->update(array('ad' => 2));
            throw new \Exception('{"code": "0" , "msg": "已禁用!"}');
        }elseif($data['ad'] == 2){
            Db::table($this->table)->where(array('id' => $uid))->update(array('ad' => 0));
            throw new \Exception('{"code": "0" , "msg": "已启用!"}');
        }
    }
}