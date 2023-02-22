<?php
declare (strict_types = 1);

namespace app\controller;

use app\BaseController;
use think\facade\Db;
use think\Request;
use think\facade\View;

class Url extends BaseController
{

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create(): string
    {
        $vars = ['page_title' => '创建'];
        return View::fetch('',$vars);
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {

    }

    /**
     *
     * 显示指定的资源
     *
     * @author LittleJake
     * @param string $route
     * @return string
     * @throws \Exception
     */

    public function read()
    {
        $vars = ['page_title' => '查询'];
        return View::fetch('',$vars);
    }

    /**
     *
     * 删除指定资源
     *
     * @author LittleJake
     * @param string $route
     * @throws \think\db\exception\DbException
     */
    public function delete(string $route)
    {
        Db::name('url')->where('route', $route)->delete();
        $this->success('成功');
    }

    public function redirect(string $route){
        try{
            $query = Db::name('url') -> where('route', $route)->cache(true,7200)->findOrFail();
            Db::name('url') -> where('route', $route)->inc('times', 1)->update();
        } catch(\Exception $e) {
            $this->error('未找到对应路由，'.$e->getMessage(),'/');
        }

        $this -> success("正在跳转至 $query[url]", $query['url'],null,5);
    }
}
