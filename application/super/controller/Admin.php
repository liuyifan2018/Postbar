<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/4/17
 * Time: 15:08
 */
namespace app\super\controller;

use think\Controller;

class Admin extends Controller{

	public function member(){
		return view('member');
	}

	public function member_add(){
		return view('member_add');
	}

	public function member_edit(){
		return view('member_edit');
	}

	public function member_del(){
		return view('member_del');
	}

	public function member_password(){
		return view('member_password');
	}
}