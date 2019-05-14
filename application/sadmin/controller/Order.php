<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/5/14
 * Time: 11:44
 */
namespace app\sadmin\controller;

use think\Controller;

class Order extends Controller{

	public function order_list(){
		return view('order_list');
	}
}