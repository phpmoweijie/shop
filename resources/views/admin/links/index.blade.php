@extends('layout.admins')
@section('center')

     <body>
        <div class="x-nav">
          <span class="layui-breadcrumb">
            <a href="">首页</a>
            <a href="">演示</a>
            <a>
              <cite>导航元素</cite></a>
          </span>
          <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
            <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
        </div>
        <div class="layui-fluid">
            <div class="layui-row layui-col-space15">
                <div class="layui-col-md12">
                    <div class="layui-card">
                        <div class="layui-card-body ">
                            
                        </div>
                        <div class="layui-card-header">
                            
                            <button class="layui-btn" onclick="xadmin.open('添加用户','{{url('admin/link/create')}}',600,400)"><i class="layui-icon"></i>添加</button>
                        </div>
                        <div class="layui-card-body ">
                            <table class="layui-table layui-form">
                              <thead>
                                <tr>
                                  <th>
                                    <input type="checkbox" name=""  lay-skin="primary">
                                  </th>
                                  <th>编号</th>
                                  <th>链接名称</th>
                                  <th>链接地址</th>
                                  <th>操作</th>
                              </thead>
                              <tbody>
                              @foreach($res as $k =>$v)
                                <tr>
                                  <td>
                                    <input type="checkbox" name=""  lay-skin="primary">
                                  </td>
                                  <td>{{$loop->iteration}}</td>
                                  <td>{{$v->fname}}</td>
                                   <td>{{$v->url}}</td>
                                  <td class="td-manage">
                                  
                                    <a title="编辑"  onclick="xadmin.open('编辑','{{url('admin/link/'.$v['id'].'/edit')}}')" href="javascript:;">
                                      <i class="layui-icon">&#xe642;</i>
                                    </a>
                                    
                                   
                                   
                                    <a title="删除" onclick="member_del(this,'{{$v['id']}}')" href="javascript:;">
                                      <i class="layui-icon">&#xe640;</i>
                                    </a>

                                  </td>
                                </tr>
                              @endforeach
                              </tbody>
                            </table>
                        </div>
                        <div class="layui-card-body ">
                            <div class="page">
                                <div>
                                  <a class="prev" href="">&lt;&lt;</a>
                                  <a class="num" href="">1</a>
                                  <span class="current">2</span>
                                  <a class="num" href="">3</a>
                                  <a class="num" href="">489</a>
                                  <a class="next" href="">&gt;&gt;</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </body>
    <script>
      layui.use(['laydate','form'], function(){
        var laydate = layui.laydate;
        var form = layui.form;
        
        //执行一个laydate实例
        laydate.render({
          elem: '#start' //指定元素
        });

        //执行一个laydate实例
        laydate.render({
          elem: '#end' //指定元素
        });
      });

   

      /*用户-删除*/
      function member_del(obj,id){
        var rem = $(obj);
          layer.confirm('确认要删除吗？',function(index){
             var _token = "{{csrf_token()}}";
                  $.ajax({
                          type:'DELETE',
                          url:'/admin/link/'+id,
                          datatype:'json',
                          data:{_token},
                          success:function(res){
                            //console.log(res);
                            if(res.status=='success'){
                               layer.msg("删除成功");
                               rem.parents("tr").remove();
                            }else{
                                 layer.msg(res.msg,{icon:2});
                            }
                          }
                      })
              //发异步删除数据
             
          });
      }



      function delAll (argument) {

        var data = tableCheck.getData();
  
        layer.confirm('确认要删除吗？'+data,function(index){
            //捉到所有被选中的，发异步进行删除
            layer.msg('删除成功', {icon: 1});
            $(".layui-form-checked").not('.header').parents('tr').remove();
        });
      }

      function tab_add(url)
      {
        //alert(111);
       location.href = url;

      }
    </script>
@endsection
