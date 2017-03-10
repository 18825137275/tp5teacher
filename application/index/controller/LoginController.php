<?php
namespace app\index\controller;

use app\common\model\Teacher;
use think\Controller;
use think\Request;

class LoginController extends controller
{
    /**
     * 用户登录表单页面
     * @return [type] [description]
     */
    public function index()
    {
        return $this->fetch();
    }

    /**
     * 处理用户提交的登录数据
     * @return [type] [description]
     */
    public function login()
    {
        //接受POST数据
        $postData = Request::instance()->post();

        //调用M层方法进行登录验证
        if (Teacher::login($postData['username'], $postData['password'])) {
            return $this->success('login success', url('Teacher/index'));
        }

        return $this->error('username or password incorrent', url('index'));
    }

    /**
     * 注销
     * @return [type] [description]
     */
    public function loginOut()
    {
        if (Teacher::logOut()) {
            return $this->success('logout success', url('index'));
        }

        return $this->error('logout error', url('index'));
    }
}
