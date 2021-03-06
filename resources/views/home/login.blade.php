@extends('layout.home')

@section('title', '登录尤洪')

@section('nameinfo')
  
    <div class="log_bg">    
        <div class="top">
            <div class="logo"><a href="Index.html"><img src="/home/images/logo.png" /></a></div>
        </div>
        <div class="login">
            <div class="log_img"><img src="/home/images/l_img.png" width="611" height="425" /></div>
            <div class="log_c">
                
                @if(session('error'))
                  <div style="color:red;">
                      {{session('error')}}
                  </div>
                @endif

                <form action="/home/dologin" method="post">
                <table border="0" style="width:370px; font-size:14px; margin-top:30px;" cellspacing="0" cellpadding="0">
                  <tr height="50" valign="top">
                    <td width="55">&nbsp;</td>
                    <td>
                        <span class="fl" style="font-size:24px;">登录</span>
                        <span class="fr">还没有商城账号，<a href="/home/regist" style="color:#ff4e00;">立即注册</a></span>
                    </td>
                  </tr>
                  <tr height="70">
                    <td>用户名</td>
                    <td><input type="text" name="uname" class="l_user" required/></td>
                  </tr>
                  <tr height="70">
                    <td>密&nbsp; &nbsp; 码</td>
                    <td><input type="password" name="pass"  class="l_pwd" required/></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>
                        <span class="fr"><a href="/home/forget" style="color:#ff4e00;">忘记密码？</a></span>
                    </td>
                  </tr>
                  <tr height="60">
                    <td>&nbsp;</td>

                    {{csrf_field()}}
                    <td><input type="submit" value="登录" class="log_btn" /></td>
                  </tr>
                </table>
                </form>
            </div>
        </div>
    </div>
    <!--End Login End--> 
    <!--Begin Footer Begin-->
    <div class="btmbg">
        <div class="btm">
            备案/许可证编号：蜀ICP备12009302号-1-www.dingguagua.com   Copyright © 2015-2018 尤洪商城网 All Rights Reserved. 复制必究 , Technical Support: Dgg Group <br />
            <img src="/home/images/b_1.gif" width="98" height="33" /><img src="/home/images/b_2.gif" width="98" height="33" /><img src="/home/images/b_3.gif" width="98" height="33" /><img src="/home/images/b_4.gif" width="98" height="33" /><img src="/home/images/b_5.gif" width="98" height="33" /><img src="/home/images/b_6.gif" width="98" height="33" />
        </div>      
    </div>
    <!--End Footer End -->    
    </body>
@stop