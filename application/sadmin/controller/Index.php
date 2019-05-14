<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/5/14
 * Time: 10:45
 */

namespace app\sadmin\controller;

use think\Controller;

class Index extends Controller{

	public function index(){
		return view('index');
	}
	public function welcome(){
		return view('welcome');
	}
}