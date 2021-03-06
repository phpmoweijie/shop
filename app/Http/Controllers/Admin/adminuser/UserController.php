<?php

namespace App\Http\Controllers\Admin\adminuser;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use Hash;
use App\Model\Admin\adminuser\User;
use App\Model\Admin\adminuser\Role;
use App\Model\Admin\adminuser\Permission;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     * 用户列表页
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $txt = $request->input('uname');
        // var_dump($txt);
        $perPage = $request->input('per_num',10); //每页页码
        $query = User::query()->orderBy('id', 'asc')->where(function($query) use($request){
            //检测关键字
            $uname = $request->search;
            $start = $request->start;
            $end = $request->end;
            $role = $request->role;
            // dump($start);
            //如果用户名不为空
            if(!empty($uname)) {
                $query->where('nick','like','%'.$uname.'%');
            }
            $roles = Role::where('rname',$role)->first();
            if(!empty($role)) {
                $query->where('rid','like','%'.$roles['id'].'%');
            }
            if(!empty($start) && !empty($end)) {
                $query->whereBetween('time',[$start,$end]);
            }
        });
        $result = $query->paginate($perPage);
        foreach($result as $k => $v){
            $role[] = $v->role;
        }
        $paginator = $result->render();
        $result =  collect($result)->toArray();
        $req = $request['search'];
        $re = $request['role'];
        $users = $result['data'];
        $total = $result['total'];//总页码
        $current_page = $result['current_page'];//当前页
        $i=1+(($current_page-1)*$perPage);  
        return view('admin.adminuser.user_info',compact('users','paginator' ,'total','current_page','perPage','i','txt','req','re'));
    }
    

    /**
     * Show the form for creating a new resource.
     * 用户添加页面
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $rs = Role::get();

        //加载页面
        return view('admin.adminuser.create',['rs'=>$rs]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //获取添加表单传过来的数据
        $data = $request->except(['_token','repass']);
        $ver = DB::table('admin_user')->where('aname',$data['aname'])->get();
        if(!empty($ver[0])){
            echo '2';die;
        }

        // 密码进行哈希加密
        $data['pass'] = password_hash($data['pass'],PASSWORD_DEFAULT);
        // 添加时间
        $data['time'] = date('Y-m-d H:i:s', time());
        $data['status'] = 1;
        // var_dump($data);
        // 将数据写入数据库
        $rs = DB::table('admin_user')->insert($data);
        if($rs){
            echo '1';
        }else{
            echo '0';
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // 查询填充当前用户数据
        // echo $id;
        $rs = User::where('id',$id)->get();
        // var_dump($rs[0]->id);
        $rol = Role::get();

        //加载修改页面
        return view('admin.adminuser.edit',['rs'=>$rs[0],'rol'=>$rol]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //处理修改
        $data = $request->except(['_token','_method']);
        // echo $request;
        // 链表查询数据做判断
        $verify = User::where('id',$id)->get();
        // 判断如果没有改数据返回3：保存成功
        if($data['nick'] == $verify[0]->nick && $data['rid'] == $verify[0]->rid){
            echo '3';die;
        }
        // 更改admin_user表昵称
        if($data['nick'] != $verify[0]->nick){
            $rs = User::where('id',$id)->update(['nick'=>$data['nick']]);
            if($rs != 1){
                echo '2';die;
            }
        }
        if($data['rid'] != $verify[0]->rid){
            $rs = User::where('id',$id)->update(['rid'=>$data['rid']]);
            if($rs != 1){
                echo '2';die;
            }
        }


        echo '1';
        

    }

    /**
     * 删除用户
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //删除用户
        $res = DB::table('users')->where('id', '=', $id)->delete();
        $res1 = DB::table('users_info')->where('uid', '=', $id)->delete();

        if ($res && $res1) {
          $data = [
            'status' => 0,
          ];
        } else {
          $data = [
            'status' => 1,
          ];
        }
        return $data;
    }

    // 用户状态方法
    public function status(Request $request,$id){
        $data = $request->only('status');
        $sta = User::where('id',$id)->update($data);
        // var_dump($sta);
        if($sta){
            return ['msg'=>'修改成功','status'=>'success'];
        }else{
            return ['msg'=>'修改失败','status'=>'fail'];
        }
    }
    // 修改密码
    public function pass($id){
        // 查询出当前用户名
        $rs = User::where('id',$id)->get();
        // var_dump($rs);
        return view('admin.adminuser.pass',['rs'=>$rs[0]]);
    }
    // 处理密码修改
    public function dopass(Request $request, $id){
        // 获取填写的密码
        $data = $request->except(['repass','_token']);
        // var_dump($data);
        // 查询数据库密码
        $rs = User::where('id',$id)->get();
        // hash解密判断旧密码是否一致
        if(!Hash::check($data['pass'], $rs[0]->pass)){
            // 返回值  0：旧密码不正确
            echo '0';die;
        }
        // hash加密新密码
        $data['pass'] = password_hash($data['newpass'],PASSWORD_DEFAULT);
        // 修改数据库密码
        $pass = User::where('id', $id)->update(['pass' => $data['pass']]);

        // 返回值，1：成功   0：失败
        if($pass){
            echo '1';
        }else{
            echo '2';
        }
    }


    // 批量删除
    public function batch(){
        // 遍历ajax传过来的数组
        foreach ($_GET['arr'] as $k => $v) {
            $res = DB::table('users')->where('id', '=', $v)->delete();
            $res1 = DB::table('users_info')->where('uid', '=', $v)->delete();
        }
            if ($res && $res1) {
              $data = [
                'status' => 0,
              ];
            } else {
              $data = [
                'status' => 1,
              ];
            }
        return $data;
    }

}