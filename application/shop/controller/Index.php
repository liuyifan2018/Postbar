<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/4/24
 * Time: 15:26
 */
namespace app\shop\controller;

use think\Controller;
use think\Db;
use think\facade\Request;

class Index extends Controller{
	protected $model;

	public function Index(){
		return view('Index');
	}

	public function type(){
		$data = input('get.');
		if($data['data'] == 1){
			$shopList = Db::table('shop')
				->where(['is_show' => 1,'is_del' => 1])
				->order('is_top desc,sort asc,id desc')
				->select();
			return json($shopList);
		}
	}
}




















































































































































































