<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

return [
    '__pattern__'        => [
        'name' => '\w+',
    ],
    '[hello]'            => [
        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['index/hello', ['method' => 'post']],
    ],

    // 首页 定位到 Login控制器下的index触发器, 方法为get
    ''                   => ['Login/index', ['method' => 'get']],
    // login 定位到 Login控制器下的login控制器， 方法为post
    'login'              => ['Login/login', ['method' => 'post']],
    // logout 定位到 Login控制器下的logOut控制器， 方法为get
    //要使URL与路由配合工作，则要求url()中传入的参数要以路由中的配置完全匹配。
    //<a href="{:url('Login/logOut')}">注销</a>与以下路由地址：Login/logOut匹配【大小写也要匹配】
    'loginout'           => ['Login/loginOut', ['method' => 'get']],

    //Teacher控制器
    'teacher'            => ['Teacher/index', ['method' => 'get']],
    'teacher/add'        => ['Teacher/add', ['method' => 'get']],
    'teacher/save'       => ['Teacher/save', ['method' => 'post']],
    'teacher/edit/:id'   => ['Teacher/edit', ['method' => 'get'], ['id' => '\d+']],
    'teacher/update'     => ['Teacher/update', ['method' => 'post']],
    'teacher/delete/:id' => ['Teacher/delete', ['method' => 'get'], ['id' => '\d+']],

    //Klass控制器
    'klass'              => ['Klass/index', ['method' => 'get']],
    'klass/add'          => ['Klass/add', ['method' => 'get']],
    'klass/save'         => ['Klass/save', ['method' => 'post']],
    'klass/edit/:id'     => ['Klass/edit', ['method' => 'get'], ['id' => '\d+']],
    'klass/update'       => ['Klass/update', ['method' => 'post']],
    'klass/delete/:id'   => ['Klass/delete', ['method' => 'get'], ['id' => '\d+']],

    //Student控制器
    'student'            => ['Student/index', ['method' => 'get']],
    'student/add'        => ['Student/add', ['method' => 'get']],
    'student/save'       => ['Student/save', ['method' => 'post']],
    'student/edit/:id'   => ['Student/edit', ['method' => 'get'], ['id' => '\d+']],
    'student/update'     => ['Student/update', ['method' => 'post']],
    'student/delete/:id' => ['Student/delete', ['method' => 'get'], ['id' => '\d+']],

    //Course控制器
    'course'             => ['Course/index', ['method' => 'get']],
    'course/add'         => ['Course/add', ['method' => 'get']],
    'course/save'        => ['Course/save', ['method' => 'post']],
    'course/edit/:id'    => ['Course/edit', ['method' => 'get'], ['id' => '\d+']],
    'course/update'      => ['Course/update', ['method' => 'post']],
    'course/delete/:id'  => ['Course/delete', ['method' => 'get'], ['id' => '\d+']],
];
