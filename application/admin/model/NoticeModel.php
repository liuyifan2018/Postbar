<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/5/8
 * Time: 10:37
 */
namespace app\admin\model;

use app\admin\Traits\Date;
use think\Db;
use think\Model;

class NoticeModel extends Model{
	/**
	 * 公告模型
	 */

	/**
	 * @var $data
	 */
	protected $data;

	/**
	 * NoticeModel constructor.
	 * @param $data
	 * @throws \Exception
	 */
	public function __construct($data)
	{
		parent::__construct();
		if(empty($data)) throw new \Exception('用户未登录!');
		$this->data = $data;
	}

	/**
	 * @return mixed
	 * 公告列表
	 */
	public function noticeList(){
		$notice['data'] = Db::table('forum_notice')->where(['is_del' => 1])
			->paginate(10,false,['query' => request()->param()]);
		$notice['count'] = count($notice['data']);
		$notice['list'] = $notice['data']->items();
		return $notice;
	}

	/**
	 * @param $data
	 * @throws \Exception
	 * 新增公告
	 */
	public function addNotice($data){
		$data['is_del'] = 1;
		$data['is_show'] = 1;
		$data['date'] = Date::getNowTime();
		$data['username'] = $this->data['username'];
		if($data['sort'] == "" || !is_numeric($data['sort'])){
			$data['sort'] = 0;
		}
		Db::table('forum_notice')->insert($data);
		throw new \Exception('{"code":"1","msg":"发布成功!"}');
	}

	/**
	 * @param $id
	 * @param $value
	 * @return mixed
	 * @throws \Exception
	 * 公告信息
	 */
	public function infoNotice($id,$value){
		if(empty($id)){
			throw new \Exception('{"code":"0","msg":"公告不存在!"}');
		}
		$key = Db::table('forum_notice')->find(); //随便查找一条数据，为了获取字段
		$keys = array_keys($key);   //获取全部字段
		unset($key);
		if($value == ""){
			unset($value);
			$notice = Db::table('forum_notice')->where(['id' => $id])->find();
		}else{
			if(!in_array($value,$keys)){    //检测字段名是否正确
				throw new \Exception('{"code":"0","msg":"没有字段名!"}');
			}
			$notice = Db::table('forum_notice')->where(['id' => $id])->value($value);
		}
		return $notice;
	}

	/**
	 * @param $data
	 * @throws \Exception
	 * 编辑公告
	 */
	public function editNotice( $data ){
		if($data['username'] != $this->data['username']){
			throw new \Exception('{"code":"0","msg":"没有权限修改别人公告！"}');
		}elseif($data['sort'] == "" || !is_numeric($data['sort'])){
			$data['sort'] = 0;
		}elseif($data['title'] == ""){
			throw new \Exception('{"code":"0","msg":"标题不能为空!"}');
		}elseif($data['content'] == ""){
			throw new \Exception('{"code":"0","msg":"内容不能为空!"}');
		}
		Db::table('forum_notice')->where(['id' => $data['id']])->update($data);
		throw new \Exception('{"code":"1","msg":"修改成功!"}');
	}

	/**
	 * @param $data
	 * @param $notice
	 * @throws \Exception
	 * 上架或下架
	 */
	public function is_show( $data , $notice){
		if(empty($data['id'])){
			throw new \Exception('{"code":"0","msg":"公告不存在!"}');
		}
		if($this->data['username'] != $notice){
			throw new \Exception('{"code":"0","msg":"没有权限修改别人公告！"}');
		}
		Db::table('forum_notice')->where(['id' => $data['id']])->update(['is_show' => $data['type']]);
		if($data['type'] == 1){
			throw new \Exception('{"code":"1","msg":"已上架!"}');
		}elseif ($data['type'] == 2){
			throw new \Exception('{"code":"1","msg":"已下架!"}');
		}
	}

	/**
	 * @param $data
	 * @param $notice
	 * @throws \Exception
	 * 删除公告
	 */
	public function del($data , $notice){
		if(empty($data)){
			throw new \Exception('{"code":"0","msg":"公告不存在!"}');
		}
		if($this->data['username'] != $notice){
			throw new \Exception('{"code":"0","msg":"没有权限删除别人公告！"}');
		}
		Db::table('forum_notice')->where(['id' => $data['id']])->update(['is_del' => 2]);
		throw new \Exception('{"code":"1","msg":"删除成功!"}');
	}

}