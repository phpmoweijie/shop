
@extends('layout.admins')
@section('center')
   
    <body>
        <div class="x-body">
            <form class="layui-form" action="javascript:;" id="formdate">
                <div class="layui-form-item">
                    <label for="username" class="layui-form-label">
                        <span class="x-red">*</span>角色名</label>
                    <div class="layui-input-inline">
                        <input type="text" id="rname" name="rname"  lay-verify="required" autocomplete="off" class="layui-input" >
                    </div>
                </div>
                <div class="layui-form-item layui-form-text">
                    <label class="layui-form-label">
                        <span class="x-red">*</span>拥有权限
                    </label>
                    <table  class="layui-table layui-input-block">
                        <tbody>
                            @foreach($data as $v)
                            @if(substr_count($v['path'],',') == 1)
                            <tr>
                                <td>
                                    <input type="checkbox" name="per[]" lay-skin="primary" lay-filter="father" title="{{ $v['pername'] }}" value="{{ $v['id'] }}">
                                </td>
                                <td>
                                    <div  class="layui-input-block">
                                        @foreach($data as $val)
                                            @php
                                                $arr = explode(",",$val['path']);
                                            @endphp
                                            @if(in_array($v['id'],$arr))
                                                <input  name="per[]" lay-skin="primary" type="checkbox" title="{{ $val['pername'] }}" value="{{ $val['id'] }}" lay-filter="fa"class="now">
                                            @endif
                                        @endforeach
                                            <span style='display:none'>
                                            <input  name="per[]" lay-skin="primary" type="checkbox"  value="{{ $v['id'] }}" class="last">
                                            </span>

                                    </div>
                                </td>
                            </tr>
                            @endif
                            @endforeach

                        </tbody>
                    </table>
                </div>
                <div class="layui-form-item">
                    <label for="L_repass" class="layui-form-label"></label>
                    <button class="layui-btn" lay-filter="add" lay-submit="">增加</button>
                </div>

                    {{csrf_field()}}
            </form>
        </div>
        <script>
            layui.use(['form', 'layer'],
            function() {
                $ = layui.jquery;
                var form = layui.form,
                layer = layui.layer;

                //自定义验证规则
                form.verify({
                    
                    required: function(value){ 
                    //value：表单的值
                        if (value.length < 2) {
                            return '角色名至少得2个字符';
                        }
                        if(/^\S$/.test(value)){
                          return '角色名不能为空';
                        }
                    },
                });
                //控制全选
                form.on('checkbox(father)', function(data){
                    if(data.elem.checked){
                        $(data.elem).parent().siblings('td').find('input').prop("checked", true);
                        form.render(); 
                    }else{
                       $(data.elem).parent().siblings('td').find('input').prop("checked", false);
                        form.render();  
                    }
                });

                form.on('checkbox(fa)', function(data){
                    var a = $(this).prop('checked');
                    if(a){
                        $(this).siblings('span').find('.last').prop('checked',a);
                        form.render();
                    }else{
                        var num = 0;
                        $(this).siblings('.now').each(function(){
                            if($(this).prop('checked')){
                                num++;
                            }
                        })
                    }
                    if(num == 0){
                        $(this).siblings('span').find('.last').prop('checked',a);
                        form.render();
                    }
                });
                //监听提交
            form.on('submit(add)',
            function(data) {
                //console.log(data);
                //发异步，把数据提交给php
                $.ajax({
                        url: '/admin/role',  
                        data: $('#formdate').serialize(),
                        dataType: 'json',    
                        type: 'POST',    
                        success: function(data){
                            if(data == 2){
                                layui.use(['form', 'layer'],
                                function() {
                                    $ = layui.jquery;
                                    var form = layui.form,
                                    layer = layui.layer;
                                    layer.alert("角色名已存在", {icon: 5});
                                });
                            }
                            if(data == 1){
                                layui.use(['form', 'layer'],
                                function() {
                                    $ = layui.jquery;
                                    var form = layui.form,
                                    layer = layui.layer;
                                    layer.alert("增加成功", {icon: 6},function () {
                                        // 获得frame索引
                                        var index = parent.layer.getFrameIndex(window.name);
                                        // 关闭窗口刷新父页面
                                        window.parent.location.reload();
                                        //关闭当前frame
                                        parent.layer.close(index);
                                    });
                                });
                            }
                            if(data == 0){
                                layui.use(['form', 'layer'],
                                function() {
                                    $ = layui.jquery;
                                    var form = layui.form,
                                    layer = layui.layer;
                                    layer.alert("增加失败", {icon: 5});
                                });
                            }
                        },            
                        async: false    
                    })

                return false;
            });
                
            });
            
        </script>
        <script>
            var _hmt = _hmt || []; (function() {
                var hm = document.createElement("script");
                hm.src = "https://hm.baidu.com/hm.js?b393d153aeb26b46e9431fabaf0f6190";
                var s = document.getElementsByTagName("script")[0];
                s.parentNode.insertBefore(hm, s);
            })();</script>
    </body>
@stop
