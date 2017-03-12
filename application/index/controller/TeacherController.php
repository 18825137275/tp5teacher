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
     * 对数据进行更新或保存
     * @说明：这里把function设置为private私有属性，一是为了更加安全，因为声明为private后，就不能通过URL来进行访问了；二是为了区别触发器与一般的函数，我们触发器是可以被URL来触发，而一般的函数只所以不叫做触发器，是由于通过URL触发不到。我们声明为private就达到了这个触发不到的目的。
     * @param  Teacher &$Teacher 注意：我们在这的参数为(&$Teacher)，这使得：如果执行$Teacher->validate(true)->save()时发生错误，错误信息能够能过Teacher变量进行回传，这和C语言中的&a(将变量a的地址传入)是相同的道理。
     * @param  boolean $isUpdate 判断是否为更新操作，如果是更新某些不能修改的数据则不被提交
     * @return [type]            [description]
     */
    private function saveTeacher(Teacher &$teacher, $isUpdate = false)
    {
        $teacher->name     = input('post.name');
        if (!$isUpdate) {
            $teacher->username = input('post.username');
        }
        $teacher->sex      = input('post.sex');
        $teacher->email    = input('post.email');

        return $teacher->validate(true)->save($teacher->getData());
    }

    /**
     * 新增数据交互页
     */
    public function add()
    {
        $teacher = new Teacher();

        $teacher->id       = 0;
        $teacher->name     = '';
        $teacher->sex      = 0;
        $teacher->username = '';
        $teacher->email    = '';

        $this->assign('teacher', $teacher);

        return $this->fetch('edit');
    }

    /**
     * 保存新增数据
     * @return [type] [description]
     */
    public function save()
    {
        // 实例化对象，并赋值
        $teacher           = new Teacher();

        // 判断是否添加成功！
        if ($this->saveTeacher($teacher) === false) {
            return $teacher->getError();
        }

        return $this->success('添加成功！', url('index'));
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
        $id = Request::instance()->post('id/d');

        //获取编辑数据,并判断是否存在（防止传入id不合法）
        if ($id == 0 || is_null($teacher = Teacher::get($id))) {
            return $this->error('数据不存在！');
        }

        // 判断是否更新成功！
        if ($this->saveTeacher($teacher, true) === false) {
            return $this->error("更新失败：" . $teacher->getError());
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
}
