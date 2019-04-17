<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/26
 * Time: 10:50
 */

namespace app\index\model;

use app\index\Traits\Date;
use app\index\Traits\User;
use think\facade\Cache;
use app\index\Traits\Whole;
use think\Db;
use think\Model;

class NoteModel extends Model
{
    /**
     * @var $data
     * 用户信息
     */
    protected $data;

    /**
     * NoteModel constructor.
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
     * @param $id
     * @return mixed
     * 帖子详情
     */
    public function noteInfo( $id ){
        if(empty($id) || $id < 1){
            $this->error('帖子不存在!');
        }
        $note = Db::table('note')
	        ->where(['id' => $id])
	        ->find();   //帖子信息
        return $note;
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     * 帖子详细信息
     */
    public function note( $id ){
        if( empty( $id ) ) throw new \Exception('帖子不存在!');
        $note = $this->noteInfo( $id );
        $note['tion'] = Db::table('classify')
            ->where(array('id' => $note['tion']))
            ->value('tion');
        $map = array();
        $map['id']  =   $id;
        Db::table('note')->where(array('id' => $id))->setInc('num');    //增加浏览量
        $note['coll'] = Db::table('coll')
	        ->where(['id' => $id,'username' => $this->data['username'],'coll' => 1])
	        ->find();   //检查是否已经收藏过
        $note['user']   =   Db::table('user')
	        ->where(array('username' => $note['username']))
	        ->field('img,name,insider')
	        ->find();    //帖子发布人信息
        $note['good']   =   Db::table('good')->where(['nid' => $id])->count(); //帖子总赞数
        $note['count']   =   Db::table('content')->where($map)->count(); //帖子总评论数
        $note['hot'] = Whole::hotNote();    //热帖
        $note['comment'] = Db::table("content")
	        ->where(['nid' => $id])
	        ->select();  //评论信息
        foreach($note['comment'] as &$item){
            $user = Db::table('user')
	            ->where(['username' => $item['username']])
	            ->field('name,img,insider')
	            ->find();
            $item['name'] = $user['name'];
            $item['img'] = $user['img'];
            $item['insider'] = $user['insider'];
        }
        return $note;
    }

    /**
     * @param $msg
     * @throws \Exception
     * 新增帖子
     */
    public function addNote($msg){
//	    if(Cache::get(md5(User::username().'article'))){ //检测缓存是否存在
//            throw new \Exception('{"code":"0" , "msg":"60s后才能重新发布!"}');
//        }
	    if($msg['answer'] != $msg['ploper']){   //防止恶意添加帖子
            throw new \Exception('{"code":"0" , "msg":"答案错误!"}');
        }
        Db::table('note')->strict(false)->insert($msg);  //添加帖子
//        Cache::set(md5(User::username().'article'),1,60);
	    throw new \Exception('{"code":"1" , "msg":"发布成功!"}');
    }
    /**
     * @param $msg
     * @throws \Exception
     * 发布评论
     */
    public function content($msg){
//        if(time() > $note['date'] + 63072000){  //无法对大于两年的帖子评论
//            throw new \Exception('{"code":0 , "msg":"帖子时间大于两年,不能评论!"}');
//        }
        if($msg['content'] == ""){
            throw new \Exception('{"code":0 , "msg":"评论不能为空!"}');
        }elseif(empty($msg['nid'])){
            throw new \Exception('{"code":0 , "msg":"帖子不存在!"}');
        }else{
            Db::table('content')->strict(false)->insert($msg);
            throw new \Exception('{"code":1 , "msg":"评论成功!"}');
        }
    }

    /**
     * @param $msg
     * @throws \Exception
     * 帖子点赞
     */
    public function good( $msg ){
        $map = array(
            'username' => $msg['username'],
            'nid'   =>  $msg['nid'],
        );
        $zan = Db::table('good')->where($map)->find();
        if($zan == null){
            Db::table('good')->strict(false)->insert($msg); //帖子点赞
            throw new \Exception('{"code":1 , "msg":"点赞成功!"}');
        }else{
            throw new \Exception('{"code":0 , "msg":"不能重复点赞!"}');
        }
    }

    /**
     * @param $info
     * @param $msg
     * @throws \Exception
     * 收藏帖子
     */
    public function collTion( $info, $msg ){
        $note = Db::table('coll')
	        ->where(['username' => $this->data['username'], 'nid' => $info['id']])
	        ->find();  //查询帖子信息
        if ($note == null) {
            Db::table('coll')->insert($msg);    //第一次收藏帖子
            throw new \Exception('{"code":1 , "msg":"收藏成功!"}');
        }
        Db::table('coll')
	        ->where(['username' => $this->data['username'], 'nid' => $info['id']])
	        ->update(['coll' => $info['type']]);
        if($info['type'] == 1){
            throw new \Exception('{"code":1 , "msg":"收藏成功!"}');
        }elseif($info['type'] == 2){
            throw new \Exception('{"code":1 , "msg":"取消收藏成功!"}');
        }else{
            throw new \Exception('{"code":0 , "msg":"操作失败!"}');
        }
    }
    /**
     * @param $msg
     * @param $code
     * @throws \Exception
     * 举报帖子
     */
    public function Report($msg , $code){
        $msg['type'] = 1;
        $report = Db::table('report')->where($code)->find();
        if($msg['notename'] == $this->data['username']){
            throw new \Exception('{"code":1 , "msg":"不能举报自己!"}');
        }
        if(empty($report)){
            Db::table('report')->insert($msg);
            throw new \Exception('{"code":1 , "msg":"举报成功,请等待处理!"}');
        }else{
            throw new \Exception('{"code":0 , "msg":"不要重复举报!"}');
        }
    }

}