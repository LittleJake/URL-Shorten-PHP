<?php
declare (strict_types = 1);

namespace app\controller;

use app\middleware\CheckFrequent;
use think\Request;
use app\BaseController;
use app\model\Url;
use think\response\Json;

class Api extends BaseController
{

    protected $middleware = [CheckFrequent::class];
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create(Request $request)
    {
        //
        $data = $request->post();
        $valid = validate('');
        $data = [];
        return json(['code' => 1,'mgs' => 'OK', 'data' => Url::create($data)]);
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        try{
            $data = $request->post();
            $valid = new \app\validate\Url();
            if(!$valid->check($data))
                $this->error($valid->getError());

            $route = getRandStr(8);

            Url::create([
                'url' => $data['url'],
                'route' => $route,
                'update_time' => time(),
            ]);

            return json(['code' => 1, 'msg' => 'OK', 'data' => $request->host().'/'.$route]);
        } catch (\Exception $e){
            return json(['code' => 0, 'msg' => 'Failed']);
        }
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function get($id) : Json
    {
        try{
            return json(['code' => 1, 'msg' => 'OK', 'data' => Url::where('id', $id) -> findOrFail()]);
        } catch (\Exception $e){
            return json(['code' => 0, 'msg' => 'Failed']);
        }
    }

    /**
     * 显示指定的资源
     *
     * @param  string  $route
     * @return \think\Response
     */
    public function read($route) : Json
    {
        try{
            return json(['code' => 1, 'msg' => 'OK', 'data' => Url::where('route', $route) -> findOrFail()]);
        } catch (\Exception $e){
            return json(['code' => 0, 'msg' => 'Failed']);
        }
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id) : Json
    {
        return json(['code' => 1, 'msg' => 'OK', 'data' => Url::where('id', $id) -> delete()]);
    }
}
