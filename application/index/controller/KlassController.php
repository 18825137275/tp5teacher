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
     * 对数据进行更新或保存
     * @说明：这里把function设置为private私有属性，一是为了更加安全，因为声明为private后，就不能通过URL来进行访问了；二是为了区别触发器与一般的函数，我们触发器是可以被URL来触发，而一般的函数只所以不叫做触发器，是由于通过URL触发不到。我们声明为private就达到了这个触发不到的目的。
     * @param  Teacher &$Teacher 注意：我们在这的参数为(&$Teacher)，这使得：如果执行$Teacher->validate(true)->save()时发生错误，错误信息能够能过Teacher变量进行回传，这和C语言中的&a(将变量a的地址传入)是相同的道理。
     */
    private function saveKlass(Klass &$klass)
    {
        $klass->name       = input('post.name');
        $klass->teacher_id = input('post.teacher_id');

        return $klass->validate(true)->save($klass->getData());
    }

    /**
     * 新增数据交互页
     */
    public function add()
    {
        //获取所有教师信息
        $klass = new Klass;

        $klass->id         = 0;
        $klass->name       = '';
        $klass->teacher_id = 0;

        //模板赋值
        $this->assign('klass', $klass);

        //渲染模板输出
        return $this->fetch('edit');
    }

    /**
     * 保存新增数据
     * @return [type] [description]
     */
    public function save()
    {
        // 实例化对象，并赋值
        $klass = new Klass();

        // 新增对象到数据表,并判断是否添加成功！
        if (false === $this->saveKlass($klass)) {
            return $this->error("添加失败：" . $klass->getError());
        }

        return $this->success("添加成功!", url('Klass/index'));
    }

    /**
     * 编辑数据交互页
     * @return [type] [description]
     */
    public function edit()
    {
        $id = Request::instance()->param('id/d');

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
        $id = Request::instance()->post('id/d');

        if ($id == 0 || is_null($klass = Klass::get($id))) {
            return $this->error('数据不存在！');
        }

        if (false === $this->saveKlass($klass)) {
            return $this->error('更新失败：' . $klass->getError());
        }

        return $this->success('更新成功！', url('Klass/index'));
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
        return $this->success("删除成功！", url('Klass/index'));
    }
}
