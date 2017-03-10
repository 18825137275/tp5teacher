<?php
namespace app\common\validate;

use think\Validate;

class Student extends Validate
{
    protected $rule = [
        'name'     => 'require|length:2,15',
        'num'      => 'require|number',
        'sex'      => 'require|in:0,1',
        'klass_id' => 'require|number',
        'email'    => 'email',
    ];

    protected $message = [
        'name.require'     => '名称必须！',
        'name.length'      => '名称长度为2-15个字符！',
        'num.require'      => '学号必须！',
        'num.number'       => '学号必须为数字！',
        'sex.require'      => '性别必须！',
        'sex.in'           => '性别有误！',
        'klass_id.require' => '班级必须！',
        'klass_id.number'  => '班级格式错误！',
        'email'            => '邮箱格式错误！',
    ];
}
