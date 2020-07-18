<?php


namespace app\lib;

use app\model\Url as U;
use think\facade\Cache;

class URL
{
    public static function get($type, $value): string
    {
        if(Cache::has("URL:$type:$value"))
            $url = Cache::get("URL:$type:$value");
        else
            $url = U::where($type, $value)-> findOrFail();

        Cache::set("URL:$type:$type", $value,3600);
        return $url;
    }

    public static function set($url) {
        $route = getRandStr(8);
        U::create(['url' => $url, 'route' => $route, 'update_time' => time(),]);
        return $route;
    }

    public static function del($id){
        Cache::delete("URL:id:$id");
        return U::where('id', $id) -> delete();
    }

    public static function remove($route){
        Cache::delete("URL:route:$route");
        return U::where('route', $route) -> delete();
    }
}