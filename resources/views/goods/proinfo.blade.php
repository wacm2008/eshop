@include('public.top')
  <script src="/js/jquery.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
  <body>
    <div class="maincont">
     <header>
      <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
      <div class="head-mid">
       <h1>产品详情</h1>
      </div>
     </header>
     <div id="sliderA" class="slider">
         @foreach($data['goods_imgs'] as $k=>$v)
      <img src="/uploads/goodsimg/{{$v}}" />
      @endforeach
     </div><!--sliderA/-->
     <table class="jia-len">
         <input type="hidden" id="goods_id" value="{{$data['goods_id']}}">
      <tr>
       <th><strong class="orange">{{$data['self_price']}}</strong></th>
       <td>
           <button id="less" class="decrease">-</button>
        <input type="text" id="buy_number" class="spinnerExample" value="1"/>
           <button id="more" class="increase">+</button>
           库存(<font color="red" size="3" id="goods_num">{{$data['goods_num']}}</font>)件
       </td>
      </tr>
      <tr>
       <td>
        <strong>{{$data['goods_name']}}</strong>
        <p class="hui">{{$data['goods_desc']}}</p>
       </td>
       <td align="right">
        <a href="javascript:;" class="shoucang"><span class="glyphicon glyphicon-star-empty"></span></a>
       </td>
      </tr>
     </table>
     <div class="height2"></div>
     <h3 class="proTitle">商品规格</h3>
     <ul class="guige">
      <li class="guigeCur"><a href="javascript:;">50ML</a></li>
      <li><a href="javascript:;">100ML</a></li>
      <li><a href="javascript:;">150ML</a></li>
      <li><a href="javascript:;">200ML</a></li>
      <li><a href="javascript:;">300ML</a></li>
      <div class="clearfix"></div>
     </ul><!--guige/-->
     <div class="height2"></div>
     <div class="zhaieq">
      <a href="javascript:;" class="zhaiCur">商品简介</a>
      <a href="javascript:;">商品参数</a>
      <a href="javascript:;" style="background:none;">订购列表</a>
      <div class="clearfix"></div>
     </div><!--zhaieq/-->
     <div class="proinfoList">
      <img src="/uploads/goodsimg/{{$data['goods_img']}}" width="636" height="822" />
     </div><!--proinfoList/-->
     <div class="proinfoList">
      暂无信息....
     </div><!--proinfoList/-->
     <div class="proinfoList">
      暂无信息......
     </div><!--proinfoList/-->
     <table class="jrgwc">
      <tr>
       <th>
        <a href="/"><span class="glyphicon glyphicon-home"></span></a>
       </th>
       <td><a href="#" id="carAdd">加入购物车</a></td>
      </tr>
     </table>
    </div><!--maincont-->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="/js/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/style.js"></script>
    <!--焦点轮换-->
    <script src="/js/jquery.excoloSlider.js"></script>
    <script>
		$(function () {
		 $("#sliderA").excoloSlider();
		});
	</script>
     <!--jq加减-->
    {{--<script src="/js/jquery.spinner.js"></script>--}}
   {{--<script>--}}
	{{--$('.spinnerExample').spinner({});--}}
	{{--</script>--}}
  </body>
</html>
<script>
    $(function(){
        var _this=$(this);
        var goods_num=parseInt($("#goods_num").text());
        //加号
        $("#more").click(function(){
            var buy_number=parseInt($("#buy_number").val());
            if(buy_number>=goods_num){
                _this.prop('disabled',true);
            }else{
                buy_number=buy_number+1;
                $("#buy_number").val(buy_number);
                _this.siblings("button[class='decrease']").prop('disabled',false);
            }
        })
        //减号
        $("#less").click(function(){
            var buy_number=$("#buy_number").val();
            if(buy_number<=1){
                _this.prop('disabled',true);
            }else{
                buy_number=buy_number-1;
                $("#buy_number").val(buy_number);
                _this.siblings("button[class='increase']").prop('disabled',false);
            }
        })
        //失去焦点
        $("#buy_number").blur(function(){
            var buy_number=$("#buy_number").val();
            var reg=/^[1-9]\d*$/;
            if(!reg.test(buy_number)){
                $("#buy_number").val(1);
            }else if(buy_number<=1){
                $("#buy_number").val(1);
            }else if(buy_number>=goods_num){
                $("#buy_number").val(goods_num);
            }
        });
        //添加购物车
        $('#carAdd').click(function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var buy_number=$("#buy_number").val();
            var goods_id=$("#goods_id").val();
            // console.log(buy_number);
            // console.log(goods_id);
            $.post(
                "/carAdd",
                {buy_number:buy_number,goods_id:goods_id},
                function(res){
                    console.log(res);
                }
            );
        })
    })
</script>