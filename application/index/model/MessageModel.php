<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/26
 * Time: 10:08
 */

namespace app\index\model;

use app\index\Traits\User;
use think\Db;
use think\Model;
class MessageModel extends Model
{
    /**
     * @var $data
     * 用户资料
     */
    protected $data;

    /**
     * MessageModel constructor.
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
     * @return mixed
     * 用户消息
     */
    public function message(){
        $message['fsg'] = Db::table('forum_friend')
            ->where(['friend' => $this->data['username'],'is_fd' => 2,'my' => 0])
            ->select(); //tion(1)
        $message['csg'] = Db::table('forum_content')
            ->where(['is_show' => 1])
            ->whereOr(User::username())
            ->select();//tion(3)
        $message['rsg'] = Db::table('forum_friend')
            ->where(['friend' => $this->data['username'],'my' => 1])
            ->where('is_fd','<>',3)
	        ->select();  //tion(4)
        foreach ($message['fsg'] as &$item){ //用户添加我的信息
            $item['name']    =   Db::table('user')
	            ->where(['username' => $item['username']])
	            ->value('name');
        }
        foreach ($message['csg'] as &$item){ //用户评论我帖子的信息
            $user = Db::table('user')
	            ->where(['username' => $item['username']])
	            ->field('name,username')
	            ->find();
            $item['name'] = $user['name'];
            $item['username'] = $user['username'];
            $item['title'] = Db::table('forum_note')
	            ->where(['id' => $item['nid']])
	            ->value('title');   //帖子标题
        }
        foreach ($message['rsg'] as &$item){ //用户拒绝我的请求信息
            $item['name']    =   Db::table('user')
	            ->where(['username' => $item['username']])
	            ->value('name');
        }
        return $message;
    }

    /**
     * @param $msg
     * @param $code
     * @throws \Exception
     * 好友验证
     */
    public function is_friend( $msg , $code){
        if(!empty($msg['type'])){
            Db::table('forum_friend')
                ->where(['friend' => $this->data['username'],'username' => $msg['user']])
                ->update($code); //通过验证加为好友
            Db::table('forum_friend')
                ->where(['username' => $this->data['username'],'friend' => $msg['user']])
                ->update($code); //通过验证加为好友
            if($msg['type'] == 1){
                throw new \Exception('{"code":"1" , "msg":"添加成功!"}');
            }elseif($msg['type'] == 2){
                throw new \Exception('{"code":"1" , "msg":"已拒绝!"}');
            }elseif($msg['type'] == 3){ //忽略的信息对方不会收到,忽略也会计算当天添加次数
                throw new \Exception('{"code":"1" , "msg":"已忽略!"}');
            }
        }else{
            throw new \Exception('{"code":"0" , "msg":"网络异常!"}');
        }
    }
    /**
     * @param $id
     * @throws \Exception
     */
    public function deleteCon( $id ){
        if(empty($id)){
            throw new \Exception('{"code":"0" , "msg":"信息不存在!"}');
        }else{
            Db::table('forum_content')
                ->where(['id' => $id])
                ->update(['is_show' => 2]);  //删除信息
            throw new \Exception('{"code":"1" , "msg":"删除成功!"}');
        }
    }

	/**
	 * @param $username
	 * @throws \Exception
	 * 删除所有信息
	 */
    public function delAllMsg( $username ){
        if(empty($username)){
            throw new \Exception('{"code":"0" , "msg":"您当前还未登录!"}');
        }else{
            Db::table('forum_content')
	            ->where(User::username())
	            ->update(['is_show' => 2]);  //删除所有信息(此方法不可逆);
            throw new \Exception('{"code":"1" , "msg":"删除成功!"}');
        }
    }

}