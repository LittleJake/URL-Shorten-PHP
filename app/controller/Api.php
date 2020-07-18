<?php
declare (strict_types = 1);

namespace app\controller;

use app\middleware\CheckFrequent;
use think\Exception;
use think\facade\Log;
use think\Request;
use app\BaseController;
use app\lib\URL;
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

    public function save(Request $request) : Json
    {
        try{
            $data = $request->post();
            $valid = validate('Url');
            if(!$valid->check($data))
                throw new \Exception($valid->getError());

            $route = URL::set($data['url']);
            return json(['code' => 1, 'msg' => 'OK', 'data' => $request->host().'/'.$route]);
        } catch (\Exception $e){
            Log::error($e ->getMessage());
            return json(['code' => 0, 'msg' => 'failed']);
        }
    }

    public function get($id) : Json
    {
        try{
            return json(['code' => 1, 'msg' => 'OK', 'data' => URL::get('id', $id)]);
        } catch (\Exception $e){
            Log::error($e ->getMessage());
            return json(['code' => 0, 'msg' => 'failed']);
        }
    }

    public function read($route) : Json
    {
        try{
            return json(['code' => 1, 'msg' => 'OK', 'data' => URL::get('route', $route)]);
        } catch (\Exception $e){
            Log::error($e ->getMessage());
            return json(['code' => 0, 'msg' => 'failed']);
        }
    }

    public function delete($id) : Json
    {
        return json(['code' => 1, 'msg' => 'OK', 'data' => URL::del($id)]);
    }

    public function remove($route) : Json
    {
        return json(['code' => 1, 'msg' => 'OK', 'data' => URL::remove($route)]);
    }
}
