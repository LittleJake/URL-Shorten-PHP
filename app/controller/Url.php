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
        $vars = [
            'page_title' => '创建'
        ];
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
        $data = $request->post();
        $valid = new \app\validate\Url();
        if(!$valid->check($data))
            $this->error($valid->getError());

        $route = getRandStr(8);
        \app\model\Url::create([
            'url' => $data['url'],
            'route' => $route,
            'update_time' => time(),
        ]);
        $this->success('添加成功', (string)url('url/read', ['route'=> $route]));
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

    public function read(string $route = '')
    {
        if(!empty($route)){
            try{
                $query = Db::name('url')->where('route',$route)->findOrFail();
            } catch (\Exception $e){
                $this->error("查询的代码不存在");
            }

            return $query['url'];
        }

        $vars = [
            'page_title' => '查询'
        ];
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
            $query = Db::name('url') -> where('route', $route)->cache(true,600)->findOrFail();
            Db::name('url') -> where('route', $route)->inc('times', 1)->update();
        } catch(\Exception $e) {
            $this->error('未找到对应路由，'.$e->getMessage(),'/');
        }

        return redirect($query['url'],301);
    }
}
