<?php
namespace app\common\model;

use think\Model;

class Teacher extends Model
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
     * 一对多关联：关联Klass表(用以删除teacher数据的时候判断此教师是否为某个班的辅导员)
     * @return [type] [description]
     */
    public function isConnectKlass()
    {
        return $this->hasMany('Klass');
    }

    /**
     * 用户登录验证
     * @param  [type] $username [description]
     * @param  [type] $password [description]
     * @return [type]           [description]
     */
    public static function login($username, $password)
    {
        //验证用户名是否存在
        $map     = array('username' => $username);
        $Teacher = self::get($map);

        //判断用户名和密码($Teacher要么是一个对象，要么是null)
        if (!is_null($Teacher) && $Teacher->checkPassword($password)) {
            session('teacherId', $Teacher->getData('id'));
            return true;
        }
        return false;
    }

    /**
     * 验证密码是否正确
     * @param  [type] $password [description]
     * @return [type]           [description]
     */
    public function checkPassword($password)
    {
        if ($this->getData('password') === self::encryptPassword($password)) {
            return true;
        }
        return false;
    }

    /**
     * 密码加密算法
     * @return [type] [description]
     */
    public static function encryptPassword($password)
    {
        return sha1(md5($password) . 'tp5');
    }

    /**
     * 注销
     * @return [type] [description]
     */
    public static function logOut()
    {
        // 销毁session中数据
        session('teacherId', null);
        return true;
    }

    /**
     * 判断用户是否已登录
     * @return boolean [description]
     */
    public static function isLogin()
    {
        $teacherId = session('teacherId');

        // isset()和is_null()是一对反义词
        if (isset($teacherId)) {
            return true;
        }
        return false;
    }
}
