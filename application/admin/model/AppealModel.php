<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/21
 * Time: 12:00
 */
namespace app\admin\model;
use think\Exception;
use think\Model;
use think\Db;
class AppealModel extends Model{
    /**
     * @var string
     * 数据库表名
     */
    protected $table = 'appeal';
    /**
     * @var $data
     * 用户信息
     */
    protected $data;

    /**
     * AppealModel constructor.
     * @param $data
     * @throws Exception
     */
    public function __construct($data)
    {
        parent::__construct();
        if( empty($data) ) throw new Exception('用户未登录!');
        $this->data = $data;
    }

    /**
     * @param $tion
     * @return mixed
     * @throws \Exception
     * 申诉列表
     */
    public function Appeal( $tion ){
        if( empty( $tion ) ) {
            throw new \Exception('访问错误!');
        }
        $map = array();
        $map['type'] = $tion;
        $appeal['data'] = Db::table('forum_appeal')->where($map)->paginate(10,false ,['query' => request()->param()]);
        $appeal['count'] = count($appeal['data']);
        return $appeal;
    }
    /**
     * @param $msg
     * @throws \Exception
     * 提交申诉
     */
    public function addAppeal($msg){
        if(empty($msg)){
            throw new \Exception('{"code": "0" , "msg" : "信息提交失败"}');
        }
        $msg['type'] = 2;
        $arr = ['password','number','email','name'];
        for($i = 0 ; $i <= count($arr); $i++){
            if($msg[$arr[$i]] == ""){
                throw new \Exception('{"code": "0" , "msg" : "请完善信息,利于你更好的找回账号!"}');
            }
        }
        Db::table('forum_appeal')->insert($msg);
        throw new \Exception('{"code": "0" , "msg" : "信息已提交,请耐心等待回复!"}');
    }


    /**
     * @param $msg
     * @throws \Exception
     * 是否通过
     */
    public function is_Appeal($msg){
        if(empty($msg)){
            throw new \Exception('{"code": "0" , "msg" : "审核失败,请重试!"}');
        }
        $filed = 'password,number,email,name';
        $appeal = Db::table('forum_appeal')->where(['id' => $msg['id']])->field($filed)->find();
        $data = Db::table('user')->where(['username' => $msg['username']])->field($filed)->find();
        $arr = ['password','number','email','name'];
        $Testing = 0;
        for ($i = 0; $i < count($arr); $i++){
            if($appeal[$arr[$i]] == $data[$arr[$i]]){
                $Testing = $Testing + 25;
            }
        }
        if($Testing < 50){
            throw new \Exception('{"code": "0" , "msg" : "系统不建议通过!"}');
        }elseif($msg['id'] > 0){
            Db::table('forum_appeal')->where(['id' => $msg['id']])->update(array('type' => $msg['type']));
            throw new \Exception('{"code": "0" , "msg" : "已审核!"}');
        }else{
            throw new\Exception('{"code": "0" , "msg" : "参数错误!"}');
        }
    }
}