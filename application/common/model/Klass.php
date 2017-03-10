<?php
namespace app\common\model;

use think\Model;

class Klass extends Model
{
    // private $teacher;    //用古老方法获取教师信息的时候用（单例模式）

    /**
     * 获取对应的教师（辅导员）信息（多对一关联belongsTo）
     * @return [type] [description]
     */
    public function getTeacher()
    {
        /* 
         * 原始方法获取：
         * if (is_null($this->teacher)) {
         *     $teacherId     = $this->getData('teacher_id');
         *     $this->teacher = Teacher::get($teacherId);
         * }
         * return $this->teacher;
         * V层输出方法：{$klass->getTeacher()->getData('name')}
         */
        return $this->belongsTo('Teacher');
    }

    /**
     * 一对多关联：关联中间表(用以删除中间表中的关联数据)
     * @return [type] [description]
     */
    public function getKlassCourse()
    {
        return $this->hasMany('KlassCourse');
    }

    /**
     * 一对多关联：关联Student表(用以删除klass数据的时候判断此班级是否有关联到学生的数据)
     * @return [type] [description]
     */
    public function isConnectStudent()
    {
        return $this->hasMany('Student');
    }
}
