<?php
namespace app\common\model;

use think\Model;

class Course extends Model
{
    /**
     * 多对多关联：关联班级课程表信息
     * @return [type] [description]
     */
    public function getKlass()
    {
        //Klass关联的类名，config('database.prefix') . 'klass_course' = config('数据表前缀'). '表名'，指定了中间表的名称。
        return $this->belongsToMany('Klass', config('database.prefix') . 'klass_course');
    }

    /**
     * 获取是否存在相关关联记录
     * @param  [type] &$klass [description]
     * @return [type]         [description]
     */
    public function getIsChecked(&$klass)
    {
        $courseId = (int) $this->id;
        $klassId  = (int) $klass->id;

        $map              = array();
        $map['course_id'] = $courseId;
        $map['klass_id']  = $klassId;

        $result = KlassCourse::get($map);
        if (is_null($result)) {
        	return false;
        }

        return true;
    }

    /**
     * 一对多关联：关联中间表
     * @return [type] [description]
     */
    public function getKlassCourse()
    {
    	return $this->hasMany('KlassCourse');
    }
}
