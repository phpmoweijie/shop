<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Hash;
use App\Model\Admin\User;
use App\Model\Admin\Usersinfo;
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
        $perPage = $request->input('per_num',10); //每页页码
        $query = User::query()->orderBy('id', 'asc')->where(function($query) use($request){
            //检测关键字
            $uname = $request->search;
            $start = $request->start;
            $end = $request->end;
            // dump($start);
            //如果用户名不为空
            if(!empty($uname)) {
                $query->where('uname','like','%'.$uname.'%');
            }
            if(!empty($start) && !empty($end)) {
                $query->whereBetween('time',[$start,$end]);
            }
        });
        $result = $query->paginate($perPage);
        $paginator = $result->render();
        $result =  collect($result)->toArray();
        $req = $request['search'];
        $users = $result['data'];
        $total = $result['total'];//总页码
        $current_page = $result['current_page'];//当前页
        $i=1+(($current_page-1)*$perPage);  
        return view('admin.user.user_info',compact('users','paginator' ,'total','current_page','perPage','i','txt','req'));
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
        //加载页面
        return view('admin.user.create');
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
        $ver = DB::table('users')->where('uname',$data['uname'])->get();
        if(!empty($ver[0])){
            echo '2';die;
        }

        // 密码进行哈希加密
        $data['pass'] = password_hash($data['pass'],PASSWORD_DEFAULT);
        // 添加时间
        $data['time'] = date('Y-m-d H:i:s', time());

        // var_dump($data);
        // 将数据写入数据库
        $rs = DB::table('users')->insert($data);
        // 查询出当前添加的id
        $info = User::where('uname',$data['uname'])->get();
        // 将用户id写入详情uid
        $rs1 = Usersinfo::create(['uid'=>$info[0]->id]);
        if($rs && $rs1){
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
        $rs = DB::table('users')->where('id',$id)->get();
        $data = DB::table('users_info')->where('uid',$rs[0]->id)->get();
        // var_dump($rs[0]->id);

        //加载修改页面
        return view('admin.user.edit',['rs'=>$rs[0],'data'=>$data[0]]);
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
        $verify = DB::table('users')
        ->join('users_info','users.id','=','users_info.uid')
        ->where('users.id',$id)
        ->select('users.uname','users_info.name','users_info.phone')
        ->get();
        // 判断如果没有改数据返回3：保存成功
        if($data['uname'] == $verify[0]->uname && $data['name'] == $verify[0]->name && $data['phone'] == $verify[0]->phone){
            echo '3';die;
        }
        // 更改users表用户名
        if($data['uname'] != $verify[0]->uname){
            $rs = DB::table('users')->where('id', $id)->update(['uname' => $data['uname']]);;
            if(!$rs){
                echo '0';die;
            }
        }
        if($data['name'] != $verify[0]->name){
            $rs = DB::table('users_info')->where('uid', $id)->update(['name' => $data['name']]);;
            if(!$rs){
                echo '0';die;
            }
        }
        if($data['phone'] != $verify[0]->phone){
            $rs = DB::table('users_info')->where('uid', $id)->update(['phone' => $data['phone']]);;
            if(!$rs){
                echo '0';die;
            }
        }

        // 返回值,1:修改成功  0:修改失败
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
        $rs = DB::table('users')->where('id',$id)->get();
        // var_dump($rs);
        return view('admin.user.pass',['rs'=>$rs[0]]);
    }
    // 处理密码修改
    public function dopass(Request $request, $id){
        // 获取填写的密码
        $data = $request->except(['repass','_token']);
        // var_dump($data);
        // 查询数据库密码
        $rs = DB::table('users')->where('id',$id)->get();
        // hash解密判断旧密码是否一致
        if(!Hash::check($data['pass'], $rs[0]->pass)){
            // 返回值  0：旧密码不正确
            echo '0';die;
        }
        // hash加密新密码
        $data['pass'] = password_hash($data['newpass'],PASSWORD_DEFAULT);
        // 修改数据库密码
        $pass = DB::table('users')->where('id', $id)->update(['pass' => $data['newpass']]);;
        // 返回值，1：成功   0：失败
        if($pass){
            echo '1';
        }else{
            echo '3';
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
