<?php
namespace app\index\controller;

use app\common\model\Student;
use think\Request;

class StudentController extends IndexController
{
    /**
     * 学生列表
     * @return [type] [description]
     */
    public function index()
    {
        //每页条数
        $pageSize = 5;

        $student = new Student;

        if (!empty($name = Request::instance()->param('name'))) {
            $student->where('name', 'like', '%' . $name . '%');
        }

        $students = $student->paginate($pageSize, false, [
            'query' => [
                'name' => $name,
            ],
        ]);

        $this->assign('students', $students);
        return $this->fetch();
    }

    /**
     * 对数据进行更新或保存
     * @说明：这里把function设置为private私有属性，一是为了更加安全，因为声明为private后，就不能通过URL来进行访问了；二是为了区别触发器与一般的函数，我们触发器是可以被URL来触发，而一般的函数只所以不叫做触发器，是由于通过URL触发不到。我们声明为private就达到了这个触发不到的目的。
     * @param  Teacher &$Teacher 注意：我们在这的参数为(&$Teacher)，这使得：如果执行$Teacher->validate(true)->save()时发生错误，错误信息能够能过Teacher变量进行回传，这和C语言中的&a(将变量a的地址传入)是相同的道理。
     */
    private function saveStudent(Student &$student)
    {
        $student->name     = input('post.name');
        $student->num      = input('post.num');
        $student->sex      = input('post.sex');
        $student->klass_id = input('post.klass_id');
        $student->email    = input('post.email');

        return $student->validate(true)->save($student->getData());
    }

    /**
     * 添加学生交互页
     */
    public function add()
    {
        //获取所有的班级信息(取消此方法，用前端$student->getKlass()->select()代替)
        // $klasses = Klass::all();
        // $this->assign('klasses', $klasses);

        //传入一个空的Student，用以V层通过一对多关联的getKlass()方法获取班级信息，而不必传入班级列表（Klass）信息和use Klass模型了。
        $student = new Student;
        // $student = $student->get(1);//此方法可以让$student不为空

        $student->id       = 0;
        $student->name     = '';
        $student->num      = '';
        $student->sex      = 0;
        $student->klass_id = 0;
        $student->email    = '';

        $this->assign('student', $student);

        return $this->fetch('edit');
    }

    /**
     * 保存添加数据
     * @return [type] [description]
     */
    public function save()
    {
        // 实例化对象，并赋值
        $student = new Student;

        // 新增对象到数据表,并判断是否添加成功！
        if (false === $this->saveStudent($student)) {
            return $this->error('添加失败：' . $student->getError());
        }

        return $this->success('添加成功！', url('Student/index'));
    }

    /**
     * 编辑学生
     * @return [type] [description]
     */
    public function edit()
    {
        $id = Request::instance()->param('id/d');

        //获取所有班级信息(取消此方法，用前端$student->getKlass->all()代替)
        // $klasses = Klass::all();
        // $this->assign('klasses', $klasses);

        //获取编辑数据,并判断是否存在（防止传入id不合法）
        if ($id == 0 || is_null($student = Student::get($id))) {
            return $this->error("数据不存在！");
        }

        $this->assign('student', $student);
        return $this->fetch();
    }

    /**
     * 更新数据
     * @return [type] [description]
     */
    public function update()
    {
        $id = Request::instance()->post('id/d');

        if ($id == 0 || is_null($student = Student::get($id))) {
            return $this->error('数据不存在！');
        }

        if (false === $this->saveStudent($student)) {
            return $this->error('更新失败：' . $student->getError());
        }

        return $this->success('更新成功！', url('Student/index'));
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
        $student = Student::get($id);

        //判断对象是否存在(防止传入的id不合法)
        if (is_null($student)) {
            return $this->error("对象不存在！");
        }

        //删除对象
        if (!$student->delete()) {
            return $this->error("删除失败:" . $student->getError());
        }

        //删除成功，跳转
        return $this->success("删除成功！", url('Student/index'));
    }
}
