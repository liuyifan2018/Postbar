<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/5/9
 * Time: 11:55
 */
namespace app\index\model;

use think\Db;
use think\Model;

class NoticeModel extends Model{

	/**
	 * @var $data
	 * 用户资料
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
	 * @param $id
	 * @return mixed
	 * @throws \Exception
	 * 公告详情
	 */
	public function Notice( $id ){
		if(empty($id)) throw new \Exception('公告不存在!');
		$notice = Db::table('forum_notice')->where(['id' => $id])->find();
		$notice['num'] = substr_count($notice['num'],',');
		return $notice;
	}

	/**
	 * @param $id
	 * @throws \Exception
	 */
	public function num( $id ){
		if(empty($id)) throw new \Exception('公告不存在!');
		$num = Db::table('forum_notice')->where(['id' => $id])->value('num');
		if($num != ""){
			$userList = explode(",",$num);
			if(!in_array($this->data['username'],$userList)){
				Db::table('forum_notice')->where(['id' => $id])->update(['num' => $num.$this->data['username'].',']);
			}
		}else{
			Db::table('forum_notice')->where(['id' => $id])->update(['num' => $this->data['username'].',']);//第一个阅读的人
		}

	}


}