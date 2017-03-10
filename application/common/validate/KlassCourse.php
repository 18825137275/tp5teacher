<?php
namespace app\common\validate;

use think\Validate;

class KlassCourse extends Validate
{
    protected $rule = [
        'klass_id'  => 'require|number',
        'course_id' => 'require|number',
    ];

    protected $message = [
        'klass_id.require'  => '班级信息不能为空！',
        'klass_id.number'   => '班级信息格式错误！',
        'course_id.require' => '课程信息不能为空！',
        'course_id.number'  => '课程信息格式错误！',
    ];
}
