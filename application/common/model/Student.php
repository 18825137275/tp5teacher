<?php
namespace app\common\model;

use think\Model;

class Student extends Model
{
	/**
	 * 获取器：获取性别
	 * @param  [type] $value [description]
	 * @return [type]        [description]
	 */
    public function getSexAttr($value)
    {
        $sex = [0 => '男', 1 => '女'];

        if (is_null($sex[$value])) {
        	return $sex['0'];
        }

        return $sex[$value];
    }

    /**
     * 获取器：转换时间格式
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    public function getCreateTimeAttr($value)
    {
    	return date('Y-m-d H:i:s', $value);
    }

    // 获取对应的班级信息（多对一关联belongsTo）
    public function getKlass()
    {
    	return $this->belongsTo('Klass');
    	/* 
    	 * 原始方法获取：
    	 * return $klass = Klass::get($this->getData('klass_id'));
    	 * V层输出方法：{$student->getKlass()->getData('name')}
    	 */
    }

    // 获取对应的教师（辅导员）信息（多对一关联belongsTo）
    public function getTeacher()
    {
    	return $this->belongsTo('Teacher');
    	/* 
    	 * 原始方法获取：
    	 * return Teacher::get($this->getData('teacher_id'));
    	 * V层输出方法：{$student->getKlass()->getTeacher()->getData('name')}
    	 */
    }
}
