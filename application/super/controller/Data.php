<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/4/16
 * Time: 17:21
 */
namespace app\super\controller;

use think\Controller;

class Data extends Controller{

	public function user(){
		return view('user');
	}
}