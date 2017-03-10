<?php
namespace app\index\controller;

use app\common\model\Teacher;
use think\Request;

class TeacherController extends IndexController
{
    /**
     * 教师列表
     * @return [type] [description]
     */
    public function index()
    {
        //获取查询数据
        $name = Request::instance()->get('name');
        //每页显示条数
        $pageSize = 10;

        //实例化模型
        $Teacher = new Teacher;

        //判断是否有查询数据
        if (!empty($name)) {
            $Teacher->where('name', 'like', '%' . $name . '%');
        }

        //将查询的值放入分页函数paginate的第三个参数query里面，解决翻页的时候查询无效的问题
        $teachers = $Teacher->paginate($pageSize, false, [
            'query' => [
                'name' => $name,
            ],
        ]);

        //向V层传数据
        $this->assign('teachers', $teachers);

        //渲染模板输出
        return $this->fetch();
    }

    /**
     * 新增数据交互页
     */
    public function add()
    {
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
        $teacher           = new Teacher();
        $teacher->name     = $postData['name'];
        $teacher->username = $postData['username'];
        $teacher->sex      = $postData['sex'];
        $teacher->email    = $postData['email'];

        // 新增对象到数据表
        $result = $teacher->validate(true)->save($teacher->getData());

        // 判断是否添加成功！
        if ($result === false) {
            return $teacher->getError();
        }

        return $this->success('添加成功！', url('index'));
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

        $teacher = Teacher::get($id);

        /**
         * 1:判断此教师是否为某个班的辅导员，如果是则不能删
         */
        $map = ['teacher_id' => $id];
        if ($teacher->isConnectKlass()->where($map)->find()) {
            return $this->error('此教师是现有班级的辅导员，不能删除！');
        }

        /**
         * 2:删除数据
         * @var [type]
         */

        //判断对象是否存在(防止传入的id不合法)
        if (is_null($teacher)) {
            return $this->error("对象不存在！");
        }

        //删除对象
        if (!$teacher->delete()) {
            return $this->error("删除失败:" . $teacher->getError());
        }

        //删除成功，跳转
        return $this->success("删除成功！", url('index'));
    }

    /**
     * 编辑数据交互页
     * @return [type] [description]
     */
    public function edit()
    {
        //获取ID信息
        $id = Request::instance()->param('id/d');

        //获取编辑数据,并判断是否存在（防止传入id不合法）
        if ($id == 0 || is_null($teacher = Teacher::get($id))) {
            return $this->error("数据不存在！");
        }

        //模板赋值
        $this->assign('teacher', $teacher);

        //渲染模板输出
        return $this->fetch();
    }

    /**
     * 更新数据
     * @return [type] [description]
     */
    public function update()
    {
        //获取数据
        $postData = Request::instance()->post();

        //获取编辑数据,并判断是否存在（防止传入id不合法）
        if (is_null($teacher = Teacher::get($postData['id']))) {
            return $this->error('数据不存在！');
        }

        $teacher->name     = $postData['name'];
        $teacher->username = $postData['username'];
        $teacher->sex      = $postData['sex'];
        $teacher->email    = $postData['email'];

        $result = $teacher->validate(true)->save($teacher->getData());

        // 判断是否更新成功！
        if ($result === false) {
            return $this->error("更新失败：" . $teacher->getError());
        }

        return $this->success('更新成功！', url('index'));
    }
}
