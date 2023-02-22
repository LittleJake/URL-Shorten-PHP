<?php


namespace app\lib;

use app\model\Url as U;
use think\facade\Cache;
use think\facade\Log;

class URL
{
    public static function get($type, $value): string
    {
        if(Cache::has("URL:$type:$value"))
            $url = Cache::get("URL:$type:$value");
        else
            $url = U::where($type, $value)-> findOrFail();

        Cache::set("URL:$type:$value", $url,3600);
        return $url->url;
    }

    public static function set($url) : string {
        $route = getRandStr(8);
        U::create(['url' => $url, 'route' => $route, 'update_time' => time(),]);
        return $route;
    }

    public static function del($id) : bool{
        Cache::delete("URL:id:$id");
        return U::where('id', $id) -> delete();
    }

    public static function remove($route) : bool{
        Cache::delete("URL:route:$route");
        return U::where('route', $route) -> delete();
    }

    public static function safe($url){
        if(Cache::has("safe_check:$url"))
            return Cache::get("safe_check:$url");
        else {

            $html = self::curl_get("http://mtool.chinaz.com/Tool/WebScan/?host=$url");
            $pattern = '/<[a-zA-Z0-9][^>]+?id=[^>]+loudong[^>]+?>.*?<span>(.*?)<\\/span>.*?<\\/div>/si';
            preg_match($pattern,$html , $loudong);
            $pattern = '/<[a-zA-Z0-9][^>]+?id=[^>]+xujia[^>]+?>.*?<span>(.*?)<\\/span>.*?<\\/div>/si';
            preg_match($pattern,$html , $xujia);
            $pattern = '/<[a-zA-Z0-9][^>]+?id=[^>]+guama[^>]+?>.*?<span>(.*?)<\\/span>.*?<\\/div>/si';
            preg_match($pattern,$html , $guama);
            $pattern = '/<[a-zA-Z0-9][^>]+?id=[^>]+cuangai[^>]+?>.*?<span>(.*?)<\\/span>.*?<\\/div>/si';
            preg_match($pattern,$html , $cuangai);
            $pattern = '/<[a-zA-Z0-9][^>]+?id=[^>]+google[^>]+?>.*?<span>(.*?)<\\/span>.*?<\\/div>/si';
            preg_match($pattern,$html , $google);
            $pattern = '/<[a-zA-Z0-9][^>]+?id=[^>]+webchat_result[^>]+?>(.*?)<\\/span>/si';
            preg_match($pattern,$html , $webchat_result);

            $result = [
                'vulnerability' => isset($loudong[1])?$loudong[1]:'超时',
                'fake' => isset($xujia[1])?$xujia[1]:'超时',
                'trojan' => isset($guama[1])?$guama[1]:'超时',
                'rewrite' => isset($cuangai[1])?$cuangai[1]:'超时',
                'google' => isset($google[1])?$google[1]:'超时',
                'wechat' => isset($webchat_result[1])?$webchat_result[1]:'超时'
            ];
            Cache::set("safe_check:$url", $result, 7200);
            return $result;
        }
    }

    private static function curl_get($url){

        $header = array();
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, 0);
        // 超时设置,以秒为单位
        curl_setopt($curl, CURLOPT_TIMEOUT, 5);

        // 超时设置，以毫秒为单位
        // curl_setopt($curl, CURLOPT_TIMEOUT_MS, 500);

        // 设置请求头
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        //执行命令
        $data = curl_exec($curl);

        // 显示错误信息
        if (curl_error($curl)) {
            Log::error(curl_error($curl));
            return "Error: " . curl_error($curl);
        } else {
            // 打印返回的内容
            curl_close($curl);
            return $data;
        }
    }
}