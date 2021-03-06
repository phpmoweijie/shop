
@extends('layout.admins')
@section('center')
   
    <body>
        <div class="x-body">
            <form class="layui-form" action="javascript:;" id="formdate">
                <div class="layui-form-item">
                    <label for="username" class="layui-form-label">
                        <span class="x-red">*</span>登录名</label>
                    <div class="layui-input-inline">
                        <input type="text" id="aname" name="aname"  lay-verify="required" autocomplete="off" class="layui-input" value="{{ $rs->aname }}" readonly  ></div>
                    <div class="layui-form-mid layui-word-aux">
                        <span class="x-red">*</span> 将会成为您唯一的登录名
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="phone" class="layui-form-label">
                        <span class="x-red">*</span>手机</label>
                    <div class="layui-input-inline">
                        <input type="text" id="phone" name="phone"  lay-verify="phone" autocomplete="off" class="layui-input" value="{{ $rs->phone }}" readonly  ></div>
                    <div class="layui-form-mid layui-word-aux">
                        <span class="x-red">*</span> 手机号无法修改
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="L_name" class="layui-form-label">
                        <span class="x-red">*</span>昵称</label>
                    <div class="layui-input-inline">
                        <input type="text" id="L_nick" name="nick"  lay-verify="nikename" autocomplete="off" class="layui-input" value="{{ $rs->nick }}">
                    </div>
                    <div class="layui-form-mid layui-word-aux" >
                        <span class="x-red">*</span> 3到35位字符<br>&nbsp;&nbsp;支持（中文，字母，数字，下划线）组成
                    </div>
                </div>
                
                <div class="layui-form-item">
                        <label class="layui-form-label"><span class="x-red">*</span>角色</label>
                        <div class="layui-input-block">
                        @foreach($rol as $k => $v)
                            <input type="radio" name="rid"  lay-skin="primary" title="{{ $v['rname'] }}" value="{{ $v['id'] }}" {{ $v['id'] ==  $rs->rid ? 'checked' : ''}}>
                        @endforeach
                      </div>
                </div>
                <div class="layui-form-item">
                    <label for="L_repass" class="layui-form-label"></label>
                    <button class="layui-btn" lay-filter="add" lay-submit="">修改</button></div>
                    {{csrf_field()}}
                    {{ method_field('PUT') }}
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
                        if(!new RegExp("^[a-zA-Z0-9_\u4e00-\u9fa5\\s·]+$").test(value)){
                          return '用户名不能有特殊字符';
                        }
                        if (value.length < 5) {
                            return '用户名至少得5个字符啊';
                        }
                        if(/(^\_)|(\__)|(\_+$)/.test(value)){
                          return '用户名首尾不能出现下划线\'_\'';
                        }
                        if(/^\d+\d+\d$/.test(value)){
                          return '用户名不能全为数字';
                        }
                    },
                    phone: [/^1(3|4|5|7|8)\d{9}$/, '请输入正确手机号'],
                    nikename: [/^[\w\u4e00-\u9fa5]{3,35}$/, '昵称格式不正确'],
                    repass: function(value) {
                        if ($('#L_pass').val() != $('#L_repass').val()) {
                            return '两次密码不一致';
                        }
                    },

                });
                
            });
           $(document).ready(function(){
                $('.layui-btn').click(function(){
                    $.ajax({
                        url: '/admin/adminuser/{{ $rs->id }}',  
                        data: $('#formdate').serialize(),
                        dataType: 'json',    
                        type: 'POST',    
                        success: function(data){
                            if(data == 1){
                                layui.use(['form', 'layer'],
                                function() {
                                    $ = layui.jquery;
                                    var form = layui.form,
                                    layer = layui.layer;
                                    layer.alert("修改成功", {icon: 6},function () {
                                        // 获得frame索引
                                        var index = parent.layer.getFrameIndex(window.name);
                                        // 关闭窗口刷新父页面
                                        window.parent.location.reload();
                                        //关闭当前frame
                                        parent.layer.close(index);
                                    });
                                });
                            }
                            if(data == 2){
                                layui.use(['form', 'layer'],
                                function() {
                                    $ = layui.jquery;
                                    var form = layui.form,
                                    layer = layui.layer;
                                    layer.alert("修改失败", {icon: 5});
                                });
                            }
                            if(data == 3){
                                layui.use(['form', 'layer'],
                                function() {
                                    $ = layui.jquery;
                                    var form = layui.form,
                                    layer = layui.layer;
                                    layer.alert("保存成功", {icon: 6},function () {
                                        // 获得frame索引
                                        var index = parent.layer.getFrameIndex(window.name);
                                        // 关闭窗口刷新父页面
                                        window.parent.location.reload();
                                        //关闭当前frame
                                        parent.layer.close(index);
                                    });
                                });
                            }
                        },            
                        async: false    
                    })
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
