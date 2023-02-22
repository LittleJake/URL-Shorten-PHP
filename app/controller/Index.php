<?php
namespace app\controller;

use app\BaseController;
use think\facade\View;

class Index extends BaseController
{
    public function index()
    {
        $vars = ['page_title' => 'URL缩短'];
        return View::fetch('',$vars);
    }

}
