<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/22
 * Time: 9:57
 */

namespace app\admin\model;
use app\admin\Traits\Date;
use app\admin\Traits\User;
use think\Db;
use think\Model;

class NoteModel extends Model
{
    /**
     * @var string
     * 数据库表名
     */
    protected $table = 'note';
    /**
     * @var $data
     * 用户信息
     */
    protected $data;

    /**
     * NoteModel constructor.
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
     * @param $tion
     * @param $note
     * @return mixed
     * 帖子列表
     */
    public function note($tion,$note){
        if(empty($note)){
            if($tion == 2){
                $noteList['data'] = Db::table('forum_note')
                    ->paginate(10,false,['query' => request()->param()]);    //帖子列表
                $noteList['count'] = count($noteList['data']);
            }else{
                $noteList['data'] = Db::table('forum_note')
                    ->where(array('state' => $tion))
                    ->paginate(10,false,['query' => request()->param()]);    //已被拒绝的帖子
                $noteList['count'] = count($noteList['data']);
            }

        }else{
            $noteList['data'] = Db::table('forum_note')
                ->where('title','like',$note.'%')
                ->paginate(10,false,['query' => ['tion' => $tion]]);  //搜索帖子
            $noteList['count'] = count($noteList['data']);
        }
        $noteList['items'] = $noteList['data']->items();
	    foreach ($noteList['items'] as $k => $v){
		    $noteList['items'][$k]['tion'] = Db::table('forum_classify')->where(['id' => $v['tion']])->value('tion');
	    }
        return $noteList;
    }
    /**
     * @param $msg
     * @throws \Exception
     * 审核帖子
     */
    public function is_Note($msg){
//	    $info = [
//		    User::username(),
//		    'date'  =>  Date::getNowTime()
//	    ];
//	    Db::table('forum_state')->insert($info);
	    if( empty($msg) ){
            throw new \Exception('{"code":"0" , "msg":"审核失败,请重试!"}');
        }
        $start_time = Date::getNowStartTime();
        $end_time = Date::getNowEndTime();
        $noteState = Db::table( 'forum_state')
            ->where(User::username())
            ->whereTime('date','between',array($start_time , $end_time))
            ->select();
        if(count($noteState) >= 5 && $this->data['ad'] != 1){   //超管无限审核,管理员一天最多审核5条
            throw new \Exception('{"code":"0" , "msg":"今日审核上限!"}');
        }

        Db::table('note')->where(['id' => $msg['id']])->update(['state' => $msg['state']]);   //审核帖子(拒绝或通过!)
        throw new \Exception('{"code":"1" , "msg":"审核成功!"}');

    }

    /**
     * @param $data
     * @throws \Exception
     * 批量删除
     */
    public function deleteAll($data){
        $data = json_decode($data,true);
        foreach ($data as $k => $v){
            Db::table('forum_note')->where(['id' => $v])->delete();
        }
        throw new \Exception('{"code":"0","msg":"批量删除成功!"}');
    }

}