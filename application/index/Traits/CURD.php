<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/7
 * Time: 11:39
 */
namespace app\index;
use think\Exception;
use think\Request;
use think\Db;
trait CURD{
    /**
     * 去除前缀的表名
     * @var string
     */
    protected $table = "";

	/**
	 * @param Request $request
	 * @throws Exception
	 * 增加数据
	 */
    protected function add(Request $request){
        $data = $request->param();
        if(!method_exists($data,'method')){
            foreach ($data as $k => $v){
                if(empty($v)) throw new Exception('参数错误');
            }
            $this->table->insert();
        }else{
            exit();
        }
    }
    /**
     * @param Request $request
     * 删除
     */
    protected function delete(Request $request){
        try{
            $data = $request->param();
            if(!method_exists($data,'method')){
                foreach ($data as $k => $v){
                    if(empty($v)) throw new Exception('参数错误');
                    break;
                }
                $this->table->where($data)->delete();
            }
        }catch (Exception $e){
            $this->error($e->getMessage());
        }
    }
    /**
     * @param Request $request
     * 修改状态
     */
    protected function update(Request $request){
        try{
            $data = $request->param();
            if(!method_exists($data,'method')){
                foreach ($data as $k => $v){
                    if(empty($v)) throw new Exception('参数错误');
                    break;
                }
                $this->table->where($data)->update($data);
            }
        }catch (Exception $e){
            $this->error($e->getMessage());
        }
    }
}