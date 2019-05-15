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
			$shop['shopList'] = Db::table('shop')
				->where(['is_show' => 1,'is_del' => 1])
				->order('is_top desc,sort asc,id desc')
				->select(); //商品列表
			$shop['tionList'] = Db::table('shop_tion')
				->where(['is_show' => 1 , 'is_del' => 1])
				->order('sort asc')
				->select(); //分类列表
			return json($shop);
		}
	}
}




















































































































































































