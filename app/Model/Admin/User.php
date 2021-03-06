<?php

namespace App\Model\Admin;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    /**
     * 与模型关联的数据表
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * 主键
     *
     * @var string
     */
    public $primaryKey = 'id';

    /**
     * 该模型是否被自动维护时间戳
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * 不可被批量赋值的属性。
     *
     * @var array
     */


    protected $guarded = [];
    
    // 关联详情表
    public function usersinfo()
    {
        return $this->hasOne('App\Model\Admin\Usersinfo','uid','id');
    }

}
