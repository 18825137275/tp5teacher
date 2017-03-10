<?php
namespace app\index\controller;

use app\common\model\Klass;
use think\Request;

class KlassController extends IndexController
{
    /**
     * 班级列表
     * @return [type] [description]
     */
    public function index()
    {
        //分页条数
        $pageSize = 5;

        //实例化模型
        $klass = new Klass;

        //判断是否有查询数据
        if (!empty($name = Request::instance()->get('name'))) {
            $klass->where('name', 'like', '%' . $name . '%');
        }

        //将查询的值放入分页函数paginate的第三个参数query里面，解决翻页的时候查询无效的问题
        $klasses = $klass->paginate($pageSize, false, [
            'query' => [
                'name' => $name,
            ],
        ]);

        //模板赋值
        $this->assign('klasses', $klasses);

        //渲染模板输出
        return $this->fetch();
    }

    /**
     * 新增数据交互页
     */
    public function add()
    {
        //获取所有教师信息
        $klass = new Klass;

        //模板赋值
        $this->assign('klass', $klass);

        //渲染模板输出
        return $this->fetch();
    }

    /**
     * 保存新增数据
     * @return [type] [description]
     */
    public function save()
    {
    	// 接收传入数据
        $postData = Request::instance()->post();

        // 实例化对象，并赋值
        $klass             = new Klass();
        $klass->name       = $postData['name'];
        $klass->teacher_id = $postData['teacher_id'];

        // 新增对象到数据表,并判断是否添加成功！
        $result = $klass->validate()->save($klass->getData());
        if (false === $result) {
            return $this->error("添加失败：" . $klass->getError());
        }

        return $this->success("添加成功!", url('index'));
    }

    /**
     * 编辑数据交互页
     * @return [type] [description]
     */
    public function edit()
    {
        $id = Request::instance()->param('id/d');

        //获取所有教师信息
        // $teachers = Teacher::all();
        // $this->assign('teachers', $teachers);

        //获取编辑数据,并判断是否存在（防止传入id不合法）
        if ($id == 0 || is_null($klass = Klass::get($id))) {
            return $this->error("数据不存在！");
        }

        $this->assign('klass', $klass);
        return $this->fetch();
    }

    /**
     * 更新数据
     * @return [type] [description]
     */
    public function update()
    {
        $postData = Request::instance()->post();

        $klass = Klass::get($postData['id']);

        if (is_null($klass)) {
            return $this->error('数据不存在！');
        }

        $klass->name       = $postData['name'];
        $klass->teacher_id = $postData['teacher_id'];

        if (!$klass->validate(true)->save($klass->getData())) {
            return $this->error('更新失败：' . $klass->getError());
        }

        return $this->success('更新成功！', url('index'));
    }

    /**
     * 删除数据
     * @return [type] [description]
     */
    public function delete()
    {
        $id = Request::instance()->param('id/d');

        if (is_null($id) || $id == 0) {
            return $this->error('数据不存在！');
        }

        // 模型数据删除
        $klass = Klass::get($id);

        //判断对象是否存在(防止传入的id不合法)
        if (is_null($klass)) {
            return $this->error("对象不存在！");
        }

        /**
         * 1:判断此班级是否有关联到学生的数据，如果有则不能删
         */
        $map = ['klass_id' => $id];
        if ($klass->isConnectStudent()->where($map)->find()) {
            return $this->error('此班级下有学生数据，不能删除！');
        }

        /**
         * 2:删除课程表数据
         * @var [type]
         */
        if (!$klass->delete()) {
            return $this->error("删除失败:" . $teacher->getError());
        }

        /**
         * 3:删除中间表数据
         */
        // $map = ['klass_id' => $id];
        if (false === $klass->getKlassCourse()->where($map)->delete()) {
            return $this->error("删除失败:" . $klass->getKlassCourse()->getError());
        }

        //删除成功，跳转
        return $this->success("删除成功！", url('index'));
    }
}
