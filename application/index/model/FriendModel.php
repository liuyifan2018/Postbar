<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/25
 * Time: 13:50
 */
namespace app\index\model;
use app\index\Traits\Date;
use app\index\Traits\User;
use think\Db;
use think\Model;

class FriendModel extends Model
{
    /**
     * @var string
     * 数据库表名
     */
    protected $table = 'friend';
    /**
     * @var $data
     * 用户信息
     */
    protected $data;

    /**
     * FriendModel constructor.
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
     * @param $data
     * @return mixed
     * 好友列表
     */
    public function index( $data ){
        $friends = Db::table('forum_friend')
            ->where(['username' => $data['username'],'is_fd' => 1])
            ->select();   //我的好友列表
        foreach ($friends as &$item) {
            $friendInfo = Db::table('user')
                ->where(array('username' => $item['friend']))
                ->field('state,name,insider')
                ->find();//我的好友信息
            $item['state'] = $friendInfo['state'];
            $item['name']  = $friendInfo['name'];
            $item['insider'] = $friendInfo['insider'];
        }
        return $friends;
    }

    /**
     * @param $friend
     * @param $msg
     * @param $fsg
     * @throws \Exception
     * 添加好友
     */
    public function getFriend($friend,$msg,$fsg){
        $start_time = Date::getNowStartTime();
        $end_time = Date::getNowEndTime();
        $myfd = Db::table('forum_friend')
            ->where(['username' => $this->data['username'] , 'friend' => $friend])
            ->find();   //检测此用户是否是我的好友
        $friendList = Db::table('forum_friend')
            ->where(User::username())
            ->whereTime('date','between',array($start_time , $end_time))
            ->select();//检测当天添加好友次数
        if($this->data['username'] == $friend){
            throw new \Exception('{"code":"0" , "msg":"不能添加自己!"}');
        }elseif(count($friendList) > 5){
            throw new \Exception('{"code":"0" , "msg":"今日添加频繁!"}');
        }elseif($myfd['is_fd'] == 1){
            throw new \Exception('{"code":"0" , "msg":"已是好友,请勿重复添加!"}');
        }elseif($myfd['is_fd'] == 2){
            throw new \Exception('{"code":"0" , "msg":"请求已发送,请稍等回复!"}');//你与此用户直接删过,提示的都是这个
        }elseif($myfd == null){
            Db::table('forum_friend')->where(User::username())->insert($msg);//主动加人
            Db::table('forum_friend')->where(User::username())->insert($fsg);//被动(被加人点同意,直接建立双方好友关系!);
            throw new \Exception('{"code":"1" , "msg":"请求已发送,请稍等回复!"}');//发布请求提示的都是这个(如果请求被拒绝,再次请求,会计算当天添加次数)
        }
    }
    /**
     * @param $friend
     * @throws \Exception
     * 删除好友
     */
    public function delFriend( $friend ){
        if(empty($friend)){ //此用户已不再你的列表里
            throw new \Exception('{"code":"0" , "msg":"删除失败,你俩已不是好友!"}');
        }
        $del = Db::table('forum_friend')
            ->where(array('username' => $this->data['username'],'friend' => $friend))
            ->update(array('is_fd' => 2)); //删除好友
        if($del){
            throw new \Exception('{"code":"1" , "msg":"删除成功!"}');
        }else{
            throw new \Exception('{"code":"1" , "msg":"删除失败!"}');
        }
    }

    /**
     * @param $param
     * @throws \Exception
     * 发送信息
     */
    public function message( $param ){
        if($param['message'] == ""){
            throw new \Exception('{"code":"0" , "msg":"信息不能为空!"}');
        }else{
            Db::table('message')->insert($param);
            throw new \Exception('{"code":"1" , "msg":"发送成功!"}');
        }
    }

    /**
     * @param $friend
     * @return mixed
     * 聊天信息
     */
    public function messageInfo( $friend ){
        $message = Db::table('forum_message')
            ->where(['username' => $this->data['username'],'friend' => $friend['username']])
            ->whereOr(['username' => $friend['username'],'friend' => $this->data['username']])
            ->order('date asc')
            ->select();
        foreach ($message as &$item){
            $item['userimg'] = Db::table('user')
	            ->where(User::username())
	            ->value('img');
            $item['friimg'] = Db::table('user')
	            ->where(array('username' => $friend['username']))
	            ->value('img');
        }
        return $message;
    }


}