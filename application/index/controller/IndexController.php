<?php
namespace app\index\controller;

use app\common\model\Teacher;
use think\Controller;

class IndexController extends Controller
{
    public function __construct()
    {
        // 调用父类构造函数(必须)
        parent::__construct();

        // 验证用户是否登陆
        if (!Teacher::isLogin()) {
            return $this->error('plz login first', url('Login/index'));
        }
    }

    public function index()
    {
        return 'nothing';
    }
}
