<?php
namespace app\index\controller;

use app\common\model\Course;
use think\Request;

class CourseController extends IndexController
{
	/**
	 * 课程列表
	 * @return [type] [description]
	 */
    public function index()
    {
        $pageSize = 5;

        $name = Request::instance()->param('name');

        $course = new Course;

        if (!empty($name)) {
            $course->where('name', 'like', '%' . $name . '%');
        }

        $courses = $course->paginate($pageSize, false, [
            'query' => [
                'name' => $name,
            ],
        ]);

        $this->assign('courses', $courses);
        return $this->fetch();
    }

    /**
     * 添加课程交互页
     */
    public function add()
    {
    	$course = new Course;
    	$this->assign('course', $course);

    	return $this->fetch();
    }

    /**
     * 保存新增课程(多对多关联)
     * @return [type] [description]
     */
    public function save()
    {
    	/**
    	 * 1:存课程信息
    	 * @var [type]
    	 */
    	$course = new Course;

    	$course->name = Request::instance()->post('name');

    	if (false === $course->validate(true)->save($course->getData())) {
    		return $this->error('新增失败：' . $course->getError());
    	}

    	/**
    	 * 2:存班级课程表信息
    	 * @var [type]
    	 */
		//判断是否为空数组array()
		if (is_null($klassIds = Request::instance()->post('klass_id/a'))) {
			return $this->error('未添加班级信息！');
		}

		if (!$course->getKlass()->saveAll($klassIds)) {
			return $this->error('新增失败：' . $course->getKlass()->getError());
		}

		return $this->success('新增成功！', url('index'));
    }

    /**
     * 编辑课程
     * @return [type] [description]
     */
    public function edit()
    {
    	$id = Request::instance()->param('id/d');

    	$course = Course::get($id);

    	if ($id == 0 || is_null($course)) {
    		return $this->error('数据不存在');
    	}

    	$this->assign('course', $course);
    	return $this->fetch();
    }

    /**
     * 更新课程及中间表
     * @return [type] [description]
     */
    public function update()
    {
    	/**
    	 * 1:更新课程表
    	 * @var [type]
    	 */
    	$id = Request::instance()->post('id/d');

    	if ($id == 0 || is_null($course = Course::get($id))) {
    		return $this->error('数据不存在！');
    	}

    	$course->name = Request::instance()->post('name');

    	if (is_null($course->validate(true)->save($course->getData()))) {
    		return $this->error('更新失败：' . $course->getError());
    	}

    	/**
    	 * 2:删除中间表原有数据
    	 */
    	//先删除现有数据
    	$map = ['course_id' => $id];

    	// 执行删除操作。由于可能存在 成功删除0条记录，故使用false来进行判断，而不能使用
        // if (!KlassCourse::where($map)->delete()) {
        // 我们认为，删除0条记录，也是成功
    	if (false === $course->getKlassCourse()->where($map)->delete()) {
    		return $this->error("删除失败:" . $course->getKlassCourse()->getError());
    	}

    	/**
    	 * 3:添加新数据到中间表
    	 */
    	$klassIds = Request::instance()->post('klass_id/a');
    	if (is_null($klassIds)) {
    		return $this->error('未添加班级信息！');
    	}

    	if (!$course->getKlass()->saveAll($klassIds)) {
    		return $this->error('添加失败：' . $course->getKlass()->getError());
    	}

    	return $this->success('更新成功！', url('index'));
    }

    /**
     * 删除课程
     * @return [type] [description]
     */
    public function delete()
    {
    	/**
    	 * 删除课程表信息
    	 * @var [type]
    	 */
    	$id = Request::instance()->param('id/d');

    	if ($id == 0 || is_null($course = Course::get($id))) {
    		return $this->error('数据不存在！');
    	}

    	if (!$course->delete()) {
    		return $this->error("删除失败:" . $course->getError());
    	}

    	/**
    	 * 删除中间表信息
    	 */
    	$map = ['course_id' => $id];
    	if (false === $course->getKlassCourse()->where($map)->delete()) {
    		return $this->error("删除失败:" . $course->getKlassCourse()->getError());
    	}

    	//删除成功，跳转
        return $this->success("删除成功！", url('index'));
    }
}
