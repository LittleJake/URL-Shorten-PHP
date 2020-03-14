<?php
declare (strict_types = 1);

namespace app\middleware;

use think\exception\HttpException;
use think\facade\Cache;

class CheckFrequent
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure       $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {
        $name = 'ip:'.$request->ip();
        if(!Cache::has($name))
            Cache::set($name,0,1);

        if(Cache::inc($name, 1) > 5)
            return json(['errorCode' => 400, 'errorMsg' => '触发IP级流控，IP：'.$request->ip()],400);

        return $next($request);
    }
}
