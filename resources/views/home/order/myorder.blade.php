@extends('layout.person.per')
@section('title','尤洪-我的订单')

@section('center')
    <div class="m_right">
            <p></p>
            <div class="mem_tit">我的订单</div>
            <table border="0" class="order_tab" style="width:930px; text-align:center; margin-bottom:30px;" cellspacing="0" cellpadding="0">
              <tr>                                                                                                                                                    
                <td width="20%">订单号</td>
                <td width="25%">下单时间</td>
                <td width="15%">订单总金额</td>
                <td width="25%">订单状态</td>
                <td width="15%">操作</td>
              </tr>
            @foreach($orders as $k => $v)
              <tr>
                <td><font color="#ff4e00">{{$v['number']}}</font></td>
                <td>{{date('Y-m-d H:i:s',$v['addtime'])}}</td>
                <td>￥{{$v['total']}}</td>
                <td class="ztai">@switch($v['status'])
                        @case(0)
                            新定单
                            @break

                        @case(1)
                            等待卖家发货
                            @break
                         @case(2)
                            已发货
                            @break
                         @case(3)
                            确认收货
                            @break
                         @case(4)
                            订单完成
                            @break
                    @endswitch</td>
                <td>
                @if($v['status']==2)
                <a href="javascript:;" onclick="querenshoh(this,{{$v['id']}})">确认收货</a>
                @endif
                <a href="{{url('/home/myorderinfo/'.base64_encode($v['id']))}}">查看订单</a></td>
              </tr>
            @endforeach
             
            </table>

        </div>
    <script type="text/javascript">
    function  querenshoh(obj,id)
    {   var _token = "{{csrf_token()}}";
        var obj = $(obj);
       
         $.ajax({
                 type:'post',
                          url:'/home/orders_status/'+id,
                          datatype:'json',
                          data:{_token},
                          success:function(res){
                            //console.log(res);
                            if(res.status=='success'){
                               layer.msg(res.msg);
                               
                              obj.parent().prev().text('确认收货');
                               obj.remove();
                            }else{
                                 layer.msg(res.msg,{icon:2});
                            }
                          }
                      })
    }
    </script>
@stop