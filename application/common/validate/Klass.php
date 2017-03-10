<?php
namespace app\common\validate;

use think\Validate;

class Klass extends Validate
{
    protected $rule = [
        'name'       => 'require|length:3,25|unique:klass',
        'teacher_id' => 'require|number',
    ];

    protected $message = [
        'name.require'       => '名称必须！',
        'name.length'        => '名称长度为3-25个字符！',
        'name.unique'        => '名称已存在！',
        'teacher_id.require' => '辅导员必须！',
        'teacher_id.number'  => '违法操作！',
    ];
}
