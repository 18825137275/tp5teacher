<?php
namespace app\common\validate;

use think\Validate;
//内置验证类

class Teacher extends Validate
{
    protected $rule = [
        'username' => 'require|unique:teacher|length:3,25',
        'name'     => 'require|length:2,25',
        'sex'      => 'require|in:0,1',
        'email'    => 'require|email',
    ];

    protected $message = [
        'username.require'       => '用户名必须！',
        'username.length'        => '用户名长度为3-25个字符！',
        'username.unique'        => '用户名已存在！',
        'name.require' => '名称必须！',
        'name.length'  => '名称长度为2-25个字符！',
        'sex.require' => '性别必须！',
        'sex.in' => '性别错误',
        'email.require' => '邮箱必须！',
        'email.require' => '邮箱格式错误',
    ];
}
