@include('public.top')
<link href="/css/page.css" rel="stylesheet">
  <body>
    <div class="maincont">
     <div class="head-top">
      <img src="/images/head.jpg" />
      <dl>
       <dt><a href="/user"><img src="/images/touxiang.jpg" /></a></dt>
       <dd>
        @if($user==null)
        <h1 class="username">三级分销终身荣誉会员</h1>
        @else
         <h1 class="username">欢迎<font color="red">{{$user->name}}</font>登录</h1>
        @endif
        <ul>
         <li><a href="/shoucang"><strong>34</strong><p>全部商品</p></a></li>
         <li><a href="javascript:;"><span class="glyphicon glyphicon-star-empty"></span><p>收藏本店</p></a></li>
         <li style="background:none;"><a href="javascript:;"><span class="glyphicon glyphicon-picture"></span><p>二维码</p></a></li>
         <div class="clearfix"></div>
        </ul>
       </dd>
       <div class="clearfix"></div>
      </dl>
     </div><!--head-top/-->
     <form action="#" method="get" class="search">
      <input type="text" class="seaText fl" name="goods_name"/>
      <input type="submit" value="搜索" class="seaSub fr" />
     </form><!--search/-->
     @if($user==null)
     <ul class="reg-login-click">
      <li><a href="/login">登录</a></li>
      <li><a href="/register" class="rlbg">注册</a></li>
      <div class="clearfix"></div>
     </ul><!--reg-login-click/-->
     @endif
     <div id="sliderA" class="slider">
      <img src="/images/image1.jpg" />
      <img src="/images/image2.jpg" />
      <img src="/images/image3.jpg" />
      <img src="/images/image4.jpg" />
      <img src="/images/image5.jpg" />
     </div><!--sliderA/-->
     @foreach($cateInfo as $k=>$v)
     <ul class="pronav">
      <li><a href="/prolist/{{$v->cate_id}}">{{$v->cate_name}}</a></li>
      <div class="clearfix"></div>
     </ul><!--pronav/-->
     @endforeach
     <div class="index-pro1">
      @foreach($goodsInfo as $k=>$v)
      <div class="index-pro1-list">
       <dl>
        <dt><a href="/proinfo/{{$v->goods_id}}"><img src="/uploads/goodsimg/{{$v->goods_img}}" /></a></dt>
        <dd class="ip-text"><a href="/goods/proinfo/{{$v->goods_id}}">{{$v->goods_name}}</a><span>已售：{{$v->goods_sold}}</span></dd>
        <dd class="ip-price"><strong>¥{{$v->self_price}}</strong> <span>¥{{$v->market_price}}</span></dd>
       </dl>
      </div>
      @endforeach
       <div class="clearfix"></div>
     </div><!--index-pro1/-->
     {{ $goodsInfo->appends($res)->links() }}
     <div class="prolist">
      <dl>
       <dt><a href="/proinfo"><img src="/images/prolist1.jpg" width="100" height="100" /></a></dt>
       <dd>
        <h3><a href="/proinfo">四叶草</a></h3>
        <div class="prolist-price"><strong>¥299</strong> <span>¥599</span></div>
        <div class="prolist-yishou"><span>5.0折</span> <em>已售：35</em></div>
       </dd>
       <div class="clearfix"></div>
      </dl>
      <dl>
       <dt><a href="/proinfo"><img src="/images/prolist1.jpg" width="100" height="100" /></a></dt>
       <dd>
        <h3><a href="/proinfo">四叶草</a></h3>
        <div class="prolist-price"><strong>¥299</strong> <span>¥599</span></div>
        <div class="prolist-yishou"><span>5.0折</span> <em>已售：35</em></div>
       </dd>
       <div class="clearfix"></div>
      </dl>
      <dl>
       <dt><a href="/proinfo"><img src="/images/prolist1.jpg" width="100" height="100" /></a></dt>
       <dd>
        <h3><a href="/proinfo">四叶草</a></h3>
        <div class="prolist-price"><strong>¥299</strong> <span>¥599</span></div>
        <div class="prolist-yishou"><span>5.0折</span> <em>已售：35</em></div>
       </dd>
       <div class="clearfix"></div>
      </dl>
     </div><!--prolist/-->
     <div class="joins"><a href="fenxiao.html"><img src="/images/jrwm.jpg" /></a></div>
     <div class="copyright">Copyright &copy; <span class="blue">这是就是三级分销底部信息</span></div>
     
     <div class="height1"></div>
     @include('public.footer')
    </div><!--maincont-->

    <script>
     $(function () {
      $("#sliderA").excoloSlider();
     });
    </script>
  </body>
</html>