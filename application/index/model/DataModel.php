<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/28
 * Time: 17:08
 */
namespace app\index\Model;

use app\index\Traits\Date;
use app\index\Traits\User;
use think\Model;
use think\Db;

class DataModel extends Model{

    /**
     * @var $data
     * 用户信息
     */
    protected $data;

    /**
     * DataModel constructor.
     * @param $data
     * @throws \Exception
     */
    public function __construct($data)
    {
        parent::__construct();
        if ( empty( $data ) ) throw new \Exception('用户未登录!');
        $this->data = $data;
    }

    /**
     * @param $username
     * @return mixed
     * 用户资料
     */
    public function user( $username ){
        if(empty($username)){
            $this->error('此用户不存在!','','',2);
        }
        $user['user'] = Db::table('user')
            ->where(['username' => $username])
            ->find();   //用户信息
        $user['note'] = Db::table('note')
            ->where(['username' => $username,'is_show' => 1])
            ->select();  //用户发布的帖子
	    foreach ($user['note'] as &$item){
		    $item['count'] = Db::table('content')->where(['nid' => $item['id']])->count();
	    }
        $user['content'] = Db::table('content')
	        ->where(['username' => $username])
	        ->select();   //用户评论信息
	    $user['count'] = count($user['content']);
        foreach($user['content'] as &$item){
        	$note = Db::table('note')->where(['id' => $item['nid']])->field('id,title')->find();
	        $item['name'] = Db::table('user')->where(['username' => $item['username']])->value('name');
            $item['note'] = $note['title'];    //帖子标题
            $item['noteid'] = $note['id']; //帖子ID
        }
        return $user;
    }

    /**
     * @param $start_time
     * @param $end_time
     * @param $msg
     * @throws \Exception
     * 用户签到
     */
    public function signed($start_time,$end_time,$msg){
        $signed = Db::table('signed')
            ->where(array('username' => $msg['username']))
            ->whereTime('date','between',array($start_time , $end_time))
            ->select(); //检测今天是否签到
        if($signed == NULL){    //没有签到
            Db::table('signed')
                ->where(array('username' => $msg['username']))
                ->strict(false)
	            ->insert($msg);  //记录签到
            Db::table('user')
                ->where(array('username' => $msg['username']))
                ->setInc('money',$msg['money']);//连续签到增长
            throw new \Exception('{"code":"1" , "msg":"签到成功!"}');
        }else{  //已签到
            throw new \Exception('{"code":"0" , "msg":"你今天已签过!"}');
        }
    }

    /**
     * @param $uid
     * @param $image
     * @throws \Exception
     * 上传图片
     */
    public function upImage($uid,$image){
        if(empty( $uid )) throw new \Exception('参数错误!');
        $upImage = Db::table('user')
            ->where(['id' => $uid])
            ->update(['img' => $image]);
        if($upImage){
            throw new \Exception('上传成功!');
        }else{
            throw new \Exception('上传失败!');
        }
    }
    /**
     * @param $msg
     * @throws \Exception
     * 修改密码
     */
    public function upPass($msg){
        if($msg['pass'] == "") {
            throw new \Exception('{"code":"0" , "msg":"密码是必填的!"}');
        }else if($msg['pass'] != $msg['repass']){
            throw new \Exception('{"code":"0" , "msg":"两次密码不一致!"}');
        }else{
            Db::table('user')
                ->where(User::username())
                ->update(['password' => $msg['pass']]);//修改密码
            throw new \Exception('{"code":"1" , "msg":"修改成功!"}');
        }
    }

    /**
     * 我的帖子
     */
    public function note(){
        $note['note'] = Db::table('note')
            ->where(User::username())
            ->select(); //我发布的帖子
        $note['coll'] = Db::table('coll')
            ->where(['username' => $this->data['username'],'coll' => 1])
            ->select(); //我收藏的帖子
        foreach ($note['coll'] as &$item){
            $item['title'] = Db::table('note')->where(['id' => $item['nid']])->value('title'); //收藏帖子的标题
        }
        foreach ($note['note'] as &$item){
            $item['count'] = Db::table('content')->where(['nid' => $item['id']])->count(); //我的帖子评论总数
        }
    }
    /**
     * @param $data
     * @throws \Exception
     * 帖子状态
     */
    public function is_show( $data ){
        if($data['id'] == null){
            throw new \Exception('{"code":"0" , "msg":"帖子不存在!"}');
        }
        Db::table('note')
            ->where(['id' => $data['id']])
            ->update(['is_show' => $data['type']]);
        if($data['type'] == 1){
            throw new \Exception('{"code":"1" , "msg":"已上架!"}');
        }elseif($data['type'] == 2){
            throw new \Exception('{"code":"1" , "msg":"已下架!"}');
        }else{
            throw new \Exception('{"code":"0" , "msg":"操作失败!"}');
        }
    }

    /**
     * @param $msg
     * @throws \Exception
     * 充值中心
     */
    public function recharge( $msg ){
        if($msg['money'] == ""){
            throw new \Exception('{"code":"0" , "msg":"请填写充值金额!"}');
        }elseif($msg['money'] > 9999) {
            throw new \Exception('{"code":"0" , "msg":"一次充值不能大于9999!"}');
        }elseif(!preg_match("/^[1-9][0-9]*$/",$msg['money'])){
            throw new \Exception('{"code":"0" , "msg":"请填写正确格式!"}');
        }else{
        	$recharge = [
        	    'money' =>  $msg['money'],
		        'username'  =>  $this->data['username'],
		        'date'  =>  Date::getNowTime()
	        ];
        	Db::table('recharge')->strict(false)->insert($recharge);//充值日志
            Db::table('user')
                ->where(User::username())
                ->setInc('money',$msg['money']);    //充值余额
            throw new \Exception('{"code":"1" , "msg":"充值成功!"}');
        }
    }

    /**
     * @param $msg
     * @return \think\response\Json
     * @throws \Exception
     * 开通会员
     */
    public function insider( $msg ){
        if($msg['insider'] == ""){
            throw new \Exception('{"code":"0" , "msg":"请选择会员!"}');
        }else{
            $msg['money'] = $msg['insider'];
            $money = [10,20,50,100];
            $insider = ['普通会员','黄金会员','钻石会员','至尊会员'];
            $map = array(
                'insider' => $insider[$msg['insider']],
                'last_insider' => Date::getNowTime()+2592000
            );
            if($this->data['money'] - $money[$msg['money']] < 0){
	            throw new \Exception('{"code":"0" , "msg":"余额不足!"}');
            }
            Db::table('user')
                ->where(User::username())
                ->update($map); //相对会员相对金额
            Db::table('user')
                ->where(User::username())
                ->setDec('money',$money[$msg['money']]);
            throw new \Exception('{"code":"1" , "msg":"开通成功!"}');
        }
    }

}