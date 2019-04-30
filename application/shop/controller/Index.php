<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/4/24
 * Time: 15:26
 */
namespace app\shop\controller;

use think\Controller;

class Index extends Controller{
	protected $model;

	public function Index(){
		return view('Index');
	}
}