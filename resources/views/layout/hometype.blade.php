
<div class="top">
    <div class="logo"><a href="Index.html"><img src="/home/images/logo.png" /></a></div>
    <div class="search">
        <form>
            <input type="text" value="" class="s_ipt" />
            <input type="submit" value="搜索" class="s_btn" />
        </form>                      
        <span class="fl"><a href="#">咖啡</a><a href="#">iphone 6S</a><a href="#">新鲜美食</a><a href="#">蛋糕</a><a href="#">日用品</a><a href="#">连衣裙</a></span>
    </div>
    <div class="i_car">
        <div class="car_t">购物车 [ <span>{{empty($carts)?'0':count($carts)}}</span> ]</div>
        <div class="car_bg">
        @if(session('qname'))

            @if(empty($carts))
                <!--Begin 购物车未登录 Begin-->
                <div class="un_login">购物车为空</div>
                <!--End 购物车未登录 End-->
                <!--Begin 购物车已登录 Begin-->
            @else
                <ul class="cars">
                @foreach($carts as $k=>$v)
                    <li>
                        <div class="img"><a href="#"><img src="{{$v->pic}}" width="58" height="58" /></a></div>
                        <div class="name"><a href="#">{{$v->gname}} {{$v->type}}</a></div>
                        <div class="price jiage" ><font color="#ff4e00">￥<b>{{$v->price}}</b></font> X<b>{{$v->num}}</b></div>
                    </li>
                @endforeach
                </ul>
                <div class="price_sum">共计&nbsp; <font color="#ff4e00">￥</font><span>1058</span></div>
                <div class="price_a"><a href="{{url('/home/cart')}}">去购物车结算</a></div>
                <!--End 购物车已登录 End-->
            @endif
        @else
             <div class="un_login">还未登录！<a href="Login.html" style="color:#ff4e00;">马上登录</a> 查看购物车！</div>
        @endif

        </div>
    </div>
</div>
<script type="text/javascript">
    var sum=0;
    $('.jiage').each(function(){
      var pic = parseInt($(this).children('font').children('b').text());
      var num = parseInt($(this).children('b').text());
        sum +=pic*num;
    })
    $('.price_sum').children('span').text(sum);
</script>
<!--End Header End--> 
<!--Begin Menu Begin-->
<div class="menu_bg">
    <div class="menu">
        <!--Begin 商品分类详情 Begin-->    
        <div class="nav">
            <div class="nav_t">全部商品分类</div>
            <div class="leftNav none">
                <ul>  
                @foreach($restypes as $k => $restype)
                  @if($restype->pid == 0)
                    <li>
                        <div class="fj">
                            <span class="n_img"><span></span><img src="/home/images/nav1.png" /></span>
                            <span class="fl">{{$restype->tname}}</span>
                        </div>
                        <div class="zj" style="top:-{{ $k * 40 }}px;">
                            <div class="zj_l">
                            @foreach($restypes as $val)
                                @if( $val->pid == $restype->id)
                                <div class="zj_l_c">
                                    <h2>{{$val->tname}}</h2>
                                     @foreach($restypes as $v)
                                      @if( $v->pid == $val->id)
                                    <a href="{{url('home/list/'.$v->id)}}">{{$v->tname}}</a>|
                                        @endif
                                    @endforeach
                                   
                                </div>
                                @endif
                            @endforeach
                                
                            </div>
                            <div class="zj_r">
                                <a href="#"><img src="/home/images/n_img1.jpg" width="236" height="200" /></a>
                                <a href="#"><img src="/home/images/n_img2.jpg" width="236" height="200" /></a>
                            </div>
                        </div>
                    </li>
                     @endif
                @endforeach
       
                </ul>            
            </div>
        </div>  
        <!--End 商品分类详情 End-->                                                     
        <ul class="menu_r">
            <li><a href="Index.html">首页</a></li>
            <li><a href="Food.html">美食</a></li>
            <li><a href="Fresh.html">生鲜</a></li>
            <li><a href="HomeDecoration.html">家居</a></li>
            <li><a href="SuitDress.html">女装</a></li>
            <li><a href="MakeUp.html">美妆</a></li>
            <li><a href="Digital.html">数码</a></li>
            <li><a href="GroupBuying.html">团购</a></li>
        </ul>
        <div class="m_ad">中秋送好礼！</div>
    </div>