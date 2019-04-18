@include('public.top')
<script src="/js/jquery.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
  <body>
    <div class="maincont">
     <header>
      <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
      <div class="head-mid">
       <h1>购物车</h1>
      </div>
     </header>
     <div class="head-top">
      <img src="/images/head.jpg" />
     </div><!--head-top/-->
     <table class="shoucangtab">
      <tr>
       <td width="75%"><span class="hui">购物车共有：<strong class="orange">{{$count}}</strong>件商品</span></td>
       <td width="25%" align="center" style="background:#fff url(images/xian.jpg) left center no-repeat;">
        <span class="glyphicon glyphicon-shopping-cart" style="font-size:2rem;color:#666;"></span>
       </td>
      </tr>
     </table>
     
     <div class="dingdanlist">
      <table>
       <tr>
        <td width="100%" colspan="4"><a href="javascript:;"><input type="checkbox" name="1" id="allbox"/> 全选</a></td>
       </tr>
          @foreach($carInfo as $k=>$v)
       <tr goods_num="{{$v->goods_num}}" goods_id="{{$v->goods_id}}"  price="{{$v->self_price*$v->buy_number}}">
        <td width="4%"><input type="checkbox" name="1" class="box" /></td>
        <td class="dingimg" width="15%"><img src="/uploads/goodsimg/{{$v->goods_img}}" /></td>
        <td width="50%">
         <h3>{{$v->goods_name}}</h3>
         <time>{{date('Y-m-d H:i:s')}}</time>
        </td>
        <td align="right">
         <button  class="decrease less">-</button>
         <input type="text"  class="spinnerExample buy_number" value="{{$v->buy_number}}"/>
         <button  class="increase more">+</button>
        </td>
       </tr>
       <tr>
        <th colspan="4">
            <strong class="orange">¥{{$v->self_price*$v->buy_number}}</strong>
        </th>
       </tr>
        @endforeach
      </table>
     </div><!--dingdanlist/-->
     

     <div class="height1"></div>
     <div class="gwcpiao">
     <table>
      <tr>
       <th width="10%"><a href="javascript:history.back(-1)"><span class="glyphicon glyphicon-menu-left"></span></a></th>
       <td width="50%">总计：<strong class="orange" id="totalPrice">¥0</strong></td>
       <td width="40%"><a href="#" class="jiesuan balance">去结算</a></td>
      </tr>
     </table>
    </div><!--gwcpiao/-->
    </div><!--maincont-->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="/js/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/style.js"></script>
    <!--jq加减-->
    {{--<script src="/js/jquery.spinner.js"></script>--}}
   {{--<script>--}}
	 {{--$('.spinnerExample').spinner({});--}}
	{{--</script>--}}
  </body>
</html>
<script>
    $(function(){
         //加号
         $(".more").click(function(){
            $.ajaxSetup({
               headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
               }
            });
             var _this=$(this);
             var goods_num=_this.parents('tr').attr('goods_num');
             var goods_id=_this.parents('tr').attr('goods_id');
             var buy_number=parseInt(_this.prev().val());
             // console.log(buy_number);
             // console.log(goods_id);
             if(buy_number>=goods_num){
              _this.prop('disabled',true);
             }else{
              buy_number=buy_number+1;
              _this.prev().val(buy_number);
              _this.siblings("button[class='decrease']").prop('disabled',false);
             }
             $.post(
                     "/changeNum",
                     {buy_number:buy_number,goods_id:goods_id},
                     function (res) {
                         console.log(res);
                         totalPrice();
                     }
             );
         })
         //减号
         $(".less").click(function(){
             $.ajaxSetup({
                headers: {
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
             });
             var _this=$(this);
             var goods_num=_this.parents('tr').attr('goods_num');
             var goods_id=_this.parents('tr').attr('goods_id');
             var buy_number=parseInt(_this.next().val());
             // console.log(buy_number);
             // console.log(goods_id);
             if(buy_number<=1){
                _this.prop('disabled',true);
             }else{
              buy_number=buy_number-1;
              _this.next().val(buy_number);
              _this.siblings("button[class='increase']").prop('disabled',false);
             }
             $.post(
                     "/changeNum",
                     {buy_number:buy_number,goods_id:goods_id},
                     function (res) {
                      console.log(res);
                         totalPrice();
                     }
             );
         })
         //失去焦点
         $(".buy_number").blur(function(){
            $.ajaxSetup({
               headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
               }
            });
            var _this=$(this);
            var goods_num=_this.parents('tr').attr('goods_num');
            var goods_id=_this.parents('tr').attr('goods_id');
            var buy_number=parseInt(_this.val());
            var reg=/^[1-9]\d*$/;
             if(!reg.test(buy_number)){
              _this.val(1);
             }else if(buy_number<=1){
              _this.val(1);
             }else if(buy_number>=goods_num){
              _this.val(goods_num);
             }
             buy_number=parseInt(_this.val());
            $.post(
                    "/changeNum",
                    {buy_number:buy_number,goods_id:goods_id},
                    function (res) {
                     console.log(res);
                        totalPrice();
                    }
            );
         });
         //复选框
         $('.box').click(function(){
             // var box=$('.box');
             // var price=0;
             // box.each(function(index){
             //     //console.log(_this.prop('checked'));
             //     if($(this).prop('checked')==true){
             //         price+=parseInt($(this).parents('tr').attr('price'));
             //     }
             // })
             // //console.log(price);
             // $("#totalPrice").text('￥'+price);
             totalPrice();
         })
         //全选复选框
         $('#allbox').click(function(){
            var _this=$(this);
            var estado=_this.prop('checked');
            $('.box').prop('checked',estado);
             totalPrice();
             // var box=$('.box');
             // var price=0;
             // box.each(function(index){
             //     //console.log(_this.prop('checked'));
             //     if($(this).prop('checked')==true){
             //         price+=parseInt($(this).parents('tr').attr('price'));
             //     }
             // })
             // //console.log(price);
             // $("#totalPrice").text('￥'+price);
         })
         //总价
         function totalPrice(){
           var goods_id=$('.box').parents('tr').attr('goods_id');
           //console.log(goods_id);
           var box=$('.box');
           var goods_id='';
           box.each(function(index){
           var _this=$(this);
           //console.log(_this.prop('checked'));
           if(_this.prop('checked')==true){
            goods_id+=_this.parents('tr').attr('goods_id')+',';
           }
          });
          //console.log(goods_id);
          goods_id=goods_id.substr(0,goods_id.length-1);
          //console.log(goods_id);
          $.ajaxSetup({
             headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             }
          });
            $.post(
                    "/totalPrice",
                    {goods_id:goods_id},
                    function(res){
                     //console.log(res);
                     $("#totalPrice").text('￥'+res);
                    }
            );
         }
         //结算
        $('.balance').click(function(){
            var box=$('.box');
            var goods_id='';
            box.each(function(index){
                var _this=$(this);
                //console.log(_this.prop('checked'));
                if(_this.prop('checked')==true){
                    goods_id+=_this.parents('tr').attr('goods_id')+',';
                }
            });
            //console.log(goods_id);
            goods_id=goods_id.substr(0,goods_id.length-1);
            //console.log(goods_id);
            if(goods_id==''){
                alert('请选择商品');
                return false;
            }else{
                location.href="/balance?goods_id="+goods_id;
            }
        })
    })
</script>