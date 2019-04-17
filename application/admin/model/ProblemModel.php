<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/22
 * Time: 11:03
 */

namespace app\admin\model;

use think\Db;
use think\Model;

class ProblemModel extends Model
{
    /**
     * @var string
     * 数据库表名
     */
    protected $table = 'user';
    /**
     * @var $data
     */
    protected $data;

    /**
     * ProblemModel constructor.
     * @param $data
     * @throws \Exception
     * 初始化
     */
    public function __construct($data)
    {
        parent::__construct();
        if( empty($data) ) throw new \Exception('用户未登录!');
        $this->data = $data;
    }
    /**
     * @return mixed
     * 我的密保
     */
    public function problem(){
        $problem['data'] = Db::table('problem')
            ->where(['username' => $this->data['username'],'is_show' => 1])
            ->select();
        $problem['count'] = count($problem['data']);
        return $problem;
    }

    /**
     * @param $msg
     * @param $code
     * @return \think\response\Json
     * @throws \Exception
     * 添加密保
     */
    public function addProblem($msg,$code){
        if( empty($msg) ){
            throw new \Exception('{"code":0 , "msg":"添加失败,请重试!"}');
        }
        $problem = Db::table('problem')->where(array('username' => $this->data['username']))->select();
        foreach ($problem as $k => $v){
            if($problem[$k]['problem'] == $msg['problem']){
                throw new \Exception('{"code":0 , "msg":"问题重复!"}');
            }
        }
        if(count($problem) >= 3){
            throw new \Exception('{"code":0 , "msg":"最多设置三个密保!"}');
        }elseif($msg['problem'] == ""){
            throw new \Exception('{"code":0 , "msg":"问题不能为空!"}');
        }elseif($msg['answer'] == ""){
            throw new \Exception('{"code":0 , "msg":"答案不能为空!"}');
        }else{
            Db::table('problem')->insert($code);
            throw new \Exception('{"code":1 , "msg":"发布成功!"}');
        }
    }
    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     * 密保详情
     */
    public function infoProblem($id){
        if($id < 1 || empty($id)){
            throw new \Exception('{"code":"0","msg":"参数错误!"}');
        }
        $problem = Db::table('problem')->where(['id' => $id])->find();
        return $problem;
    }
    /**
     * @param $msg
     * @param $problem
     * @throws \Exception
     * 编辑密保
     */
    public function editProblem($msg,$problem){
        if(empty($msg)){
            throw new \Exception('{"code":"0","msg":"编辑失败,请重试!"}');
        }
        if($problem['problem'] == $msg['problem']){
            throw new \Exception('{"code":"0","msg":"问题重复!"}');
        }elseif($msg['problem'] == ""){
            throw new \Exception('{"code":"0","msg":"问题不能为空!"}');
        }elseif($msg['answer'] == ""){
            throw new \Exception('{"code":"0","msg":"答案不能为空!"}');
        }else{
            Db::table('problem')
                ->where(['id' => $msg['id']])
                ->update($msg);
            throw new \Exception('{"code":"1","msg":"修改成功!"}');
        }
    }
    /**
     * @param $id
     * @throws \Exception
     * 删除密保
     */
    public function delProblem($id){
        if($id < 1 || empty($id)){
            throw new \Exception('{"code":"0","msg":"参数错误!"}');
        }else{
            Db::table('problem')
                ->where(['id' => $id])
                ->update(['is_show' => 2]);
            throw new \Exception('{"code":"1","msg":"已删除!"}');
        }
    }
}