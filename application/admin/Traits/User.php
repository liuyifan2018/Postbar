<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/4/2
 * Time: 17:55
 */
namespace app\admin\Traits;
use think\Db;
trait User{
    /**
     * @return array
     * 获取用户信息
     */
    public static function username(){
        $data = Session('data');
        $username = ['username' => $data['username']];
        return $username;
    }
    /**
     * @throws \Exception
     * 用户升级
     */
    public static function ExpUp(){
        $data = Session('data');
        if( empty( $data ) ) exit;
        $ExpUp = false;
        if($data['exp'] >= $data['target']){
            $ExpUp = Db::table('user')->where(['id' => $data['id']])->setInc('lv',1);
        }
        if($ExpUp){
            throw new \Exception('恭喜你,已升级!');
        }else{
            exit;
        }
    }
}