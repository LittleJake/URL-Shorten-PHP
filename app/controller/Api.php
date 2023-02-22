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
        $data = $request->post();
        $valid = validate('Url',[],false,false);
        if(!$valid->check($data))
            return json(['code' => 0, 'msg' => $valid->getError()]);

        try{
            $route = URL::set($data['url']);
        } catch (\Exception $e){
            Log::error($e ->getMessage());
            return json(['code' => 0, 'msg' => 'failed']);
        }

        return json(['code' => 1, 'msg' => 'OK', 'data' => [
            'url' => $request->scheme().'://'.$request->host().'/'.$route,
            'safe' => URL::safe($data['url'])
        ]]);
    }

//    public function get($id) : Json
//    {
//        try{
//            return json(['code' => 1, 'msg' => 'OK', 'data' => URL::get('id', $id)]);
//        } catch (\Exception $e){
//            Log::error($e ->getMessage());
//            return json(['code' => 0, 'msg' => 'failed']);
//        }
//    }

    public function read($route) : Json
    {
        try{
            return json(['code' => 1, 'msg' => 'OK',
                'data' => ['url' => URL::get('route', $route), 'route' => $route]]);
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
