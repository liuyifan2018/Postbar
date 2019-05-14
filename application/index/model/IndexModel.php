<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/25
 * Time: 19:05
 */

namespace app\index\model;

use app\index\Traits\Whole;
use think\Db;
use think\Model;
use think\facade\Cookie;

class IndexModel extends Model
{
    /**
     * @var $data
     * 用户信息
     */
    protected $data;

    /**
     * IndexModel constructor.
     * @param $data
     * @throws \Exception
     */
    public function __construct($data)
    {
        parent::__construct();
        if( empty($data) ) {
	        throw new \Exception('用户不存在!');
        }
        $this->data = $data;
    }

    /**
     * @param $tion
     * @return mixed
     * 首页
     */
    public function index( $tion ){
        $map = array();
        $map['is_show'] = 1;
        $map['state'] = 1;
        $item = Db::table('forum_config')->where(['id' => 1])->value('items');
        if(empty($tion)){
            $note['note'] = Db::table('forum_note')
                ->where($map)
                ->paginate($item,false,['query' => request()->param()]); //已通过未被删除的帖子
        }else{
            if(is_numeric($tion)){
                $note['note'] = Db::table('forum_note')
                    ->where(['is_show' => 1 , 'state' => 1 ,'tion' => $tion])
                    ->paginate($item,false,['query' => request()->param()]);    //分类筛选的帖子
            }else{
                if($tion == 'hot'){
                    $note['note'] = Db::table('forum_note')
                        ->where($map)
                        ->order('num desc')
                        ->paginate($item,false,['query' => request()->param()]);    //分类筛选的帖子
                }elseif ($tion == 'new'){
                    $note['note'] = Db::table('forum_note')
                        ->where($map)
                        ->order('date desc')
                        ->paginate($item,false,['query' => request()->param()]);    //分类筛选的帖子
                }else{
                    $note['note'] = array();
                }
            }
        }
        $note['notice'] = Db::table('forum_notice')->where(['is_show' => 1,'is_del' =>1])->order('sort asc,id desc')->limit(5)->select();
        foreach ($note['notice'] as $k => $v){
	        $note['notice'][$k]['name'] = Db::table('user')->where(['username' => $v['username']])->value('name'); //用户名
        }
        $note['today'] = Whole::todayNote();    //今日最热贴子(目前帖子少,还没修改每天最热);
        $note['hot'] = Whole::hotNote();  //最热的6条帖子(因目前帖子少,还未改成每星期的最热的6条帖子);
        $note['items'] = $note['note']->items();
        foreach ($note['items'] as $k => $v){
            $note['items'][$k]['tion'] = Db::table('forum_classify')
	            ->where(array('id' => $v['tion']))
	            ->value('tion');    //帖子分类名
            $note['items'][$k]['name'] = Db::table('user')
	            ->where(array('username' => $v['username']))
	            ->value('name');    //帖子发布人的昵称
        }
        return $note;
    }

	/**
	 * @param $title
	 * @return mixed
	 * 搜索
	 */
    public function search( $title ){
	    $item = Db::table('forum_config')->where(['id' => 1])->value('items');
	    if(empty($title)){
			$this->error('请输入关键字!');
		}else{
			$note['note'] = Db::table('forum_note')
				->where('title','like',$title.'%')
				->paginate($item,false,['query' => request()->param()]);
		    $note['items'] = $note['note']->items();
		    foreach ($note['items'] as $k => $v){
			    $note['items'][$k]['tion'] = Db::table('forum_classify')
				    ->where(array('id' => $v['tion']))
				    ->value('tion');    //帖子分类名
			    $note['items'][$k]['name'] = Db::table('user')
				    ->where(array('username' => $v['username']))
				    ->value('name');    //帖子发布人的昵称
		    }
		    $note['hot'] = Whole::hotNote();  //最热的6条帖子(因目前帖子少,还未改成每星期的最热的6条帖子);
		    return $note;
		}
    }
}