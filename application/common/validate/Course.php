<?php
namespace app\common\validate;

use think\Validate;

class Course extends Validate
{
    protected $rule = [
        'name' => 'require|unique:course',
    ];

    protected $message = [
        'name.require'     => '名称必须！',
        'name.unique'      => '名称已存在！',
    ];
}
