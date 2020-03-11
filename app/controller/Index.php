<?php
namespace app\controller;

use app\BaseController;
use think\facade\View;

class Index extends BaseController
{
    public function index()
    {
        $vars = [
            'page_title' => 'URL缩短'
        ];
        return View::fetch('',$vars);
    }

    public function hello($name = 'ThinkPHP6')
    {
        View::assign('a',1);
        return View::fetch();
        return 'hello,' . $name;
    }
}
