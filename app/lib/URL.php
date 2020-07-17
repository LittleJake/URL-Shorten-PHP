<?php


namespace app\lib;

use app\model\Url as U;
use think\facade\Cache;

class URL
{
    public static function get($id): string
    {
        if(Cache::has("URL:id:$id"))
            $url = Cache::get("URL:id:$id");
        else
            $url = U::where('id', $id)-> findOrFail();

        Cache::set("URL:id:$id", $url,3600);
        return $url;
    }

    public static function read($route): string
    {
        if(Cache::has("URL:route:$route"))
            $url = Cache::get("URL:route:$route");
        else
            $url = U::where('route', $route)-> findOrFail();

        Cache::set("URL:route:$route", $url,3600);
        return $url;
    }

    public static function set($url) {
        $route = getRandStr(8);
        U::create([
            'url' => $url,
            'route' => $route,
            'update_time' => time(),
        ]);
        return $route;
    }
}