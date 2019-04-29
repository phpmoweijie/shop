<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Model\Admin\LinkModel;

class LinkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
         $links = new LinkModel;
        $res = $links->getLinks();
        return view('admin.links.index',['res'=>$res]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
         return view('admin.links.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
          $data = $request->except('_token');
        $link = new LinkModel;
        $res = $link->addLinks($data);
        if($res){
              return redirect('/admin/link/create');
        }else{
            return back()->with('success','添加失败');
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
          $link = new LinkModel;
        $res = $link->findLinks($id);
        return view('admin.links.edit',['res'=>$res]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {   //dd($request->input());
         $data = $request->except('_token');
          $link = new LinkModel;
          $res = $link->updateLinks($id,$data);
          if($res){
            return ['msg'=>'修改成功','status'=>'success'];
          }else{
            return ['msg'=>'修改失败','status'=>'fail'];
          }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $link = new LinkModel;
        $res = $link->destroyLinks($id);
        if($res){
            return ['msg'=>'删除成功','status'=>'success'];
        }else{
            return ['msg'=>'删除失败','status'=>'fail'];
        }
    }
}
