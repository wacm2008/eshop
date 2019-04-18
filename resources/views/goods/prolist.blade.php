@include('public.top')
<script src="/js/jquery.js"></script>
  <body>
    <div class="maincont">
     <header>
      <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
      <div class="head-mid">
       <form action="#" method="get" class="search">
        <input type="text" class="seaText fl" name="goods_name"/>
        <input type="submit" value="搜索" class="seaSub fr" />
       </form><!--search/-->
      </div>
     </header>
     <ul class="pro-select">
      <li types="1" class="pro-selCur pro"><a href="javascript:;">新品</a></li>
      <li types="2" class="pro"><a href="javascript:;">销量</a></li>
      <li types="3" class="pro"><a href="javascript:;">价格</a></li>
     </ul><!--pro-select/-->
     <div class="prolist" id="dv">
      @foreach($goodsInfo as $k=>$v)
      <dl>
       <dt><a href="/proinfo/{{$v->goods_id}}"><img src="/uploads/goodsimg/{{$v->goods_img}}" width="100" height="100" /></a></dt>
       <dd>
        <h3><a href="/proinfo/{{$v->goods_id}}">{{$v->goods_name}}</a></h3>
        <div class="prolist-price"><strong>¥{{$v->self_price}}</strong> <span>¥{{$v->market_price}}</span></div>
        <div class="prolist-yishou"><span>5.0折</span> <em>已售：{{$v->goods_sold}}</em></div>
       </dd>
       <div class="clearfix"></div>
      </dl>
      @endforeach
     </div><!--prolist/-->

     <div class="height1"></div>

     @include('public.footer')
    </div><!--maincont-->

    <script>
		$(function () {
		 $("#sliderA").excoloSlider();
         $('.pro').click(function () {
             var _this=$(this);
             _this.addClass('pro-selCur');
             _this.siblings('li').removeClass('pro-selCur');
             var types=_this.attr('types');
             //console.log(types);
             $.get(
                     "/prolist",
                     {types:types},
                     function (res) {
                         $('#dv').html(res);
                     }
             );
         })
		});
	</script>
  </body>
</html>