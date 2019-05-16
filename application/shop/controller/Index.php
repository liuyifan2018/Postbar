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
		$id = input('get.id');

		return view('Index',[
			'id'    =>  $id
		]);
	}

	public function type(){
		$data = input('get.');
		$map = [
			'is_show'   =>  1,
			'is_del'    =>  1
		];
		if(Request()->isGet()){
			if($data['id'] == 0){
				$shop['shopList'] = Db::table('shop')
					->where($map)
					->order('is_top desc,sort asc,id desc')
					->select(); //商品列表
				$shop['tionList'] = Db::table('shop_tion')
					->where($map)->order('sort asc')->select(); //分类列表
				return json($shop);
			}else{
				$shop['shopList'] = Db::table('shop')
					->where($map)
					->where(['tion' => $data['id']])
					->order('is_top desc,sort asc,id desc')
					->select(); //商品列表
				$shop['tionList'] = Db::table('shop_tion')
					->where($map)->order('sort asc')->select(); //分类列表
				return json($shop);
			}
		}
	}

}




















































































































































































