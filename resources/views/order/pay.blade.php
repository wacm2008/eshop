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
      <img src="images/head.jpg" />
     </div><!--head-top/-->
     <div class="dingdanlist" onClick="window.location.href='#'">
      <table>
       @if($addressInfo['is_default']==1)
        <div class="address"  address_id="{{$addressInfo['address_id']}}">
           <td width="80%">
            <h3>{{$addressInfo['address_name']}} {{$addressInfo['address_tel']}}</h3>
            <time>{{$addressInfo['province']}}{{$addressInfo['city']}}{{$addressInfo['area']}} {{$addressInfo['address_detail']}}</time>
           </td>
        </div>
       @else
        <tr>
         <td class="dingimg" width="75%" colspan="2">新增收货地址</td>
         <td align="right"><img src="images/jian-new.png" /></td>
        </tr>
        <tr><td colspan="3" style="height:10px; background:#efefef;padding:0;"></td></tr>
       @endif
       <tr>
        <td class="dingimg" width="75%" colspan="2">选择收货时间</td>
        <td align="right"><img src="images/jian-new.png" /></td>
       </tr>
       <tr><td colspan="3" style="height:10px; background:#efefef;padding:0;"></td></tr>
       <tr>
        <td  width="75%" colspan="2">支付方式</td>
        <td align="right">
         <input type="checkbox" class="checked" pay_type="1" name="pay_type" value="1">支付宝
        </td>
       </tr>
       <tr><td colspan="3" style="height:10px; background:#efefef;padding:0;"></td></tr>
       <tr>
        <td class="dingimg" width="75%" colspan="2">优惠券</td>
        <td align="right"><span class="hui">无</span></td>
       </tr>
       <tr><td colspan="3" style="height:10px; background:#efefef;padding:0;"></td></tr>
       <tr>
        <td class="dingimg" width="75%" colspan="2">是否需要开发票</td>
        <td align="right"><a href="javascript:;" class="orange">是</a> &nbsp; <a href="javascript:;">否</a></td>
       </tr>
       <tr>
        <td class="dingimg" width="75%" colspan="2">发票抬头</td>
        <td align="right"><span class="hui">个人</span></td>
       </tr>
       <tr>
        <td class="dingimg" width="75%" colspan="2">发票内容</td>
        <td align="right"><a href="javascript:;" class="hui">请选择发票内容</a></td>
       </tr>
       <tr><td colspan="3" style="height:10px; background:#fff;padding:0;"></td></tr>
       <tr>
        <td class="dingimg" width="75%" colspan="3">商品清单</td>
       </tr>
       @foreach($goodsInfo as $k=>$v)
       <tr class="goods_id" goods_id="{{$v->goods_id}}">
        <td class="dingimg" width="15%"><img src="/uploads/goodsimg/{{$v->goods_img}}" /></td>
        <td width="50%">
         <h3>{{$v->goods_name}}</h3>
         <time>{{date('Y-m-d H:i:s')}}</time>
        </td>
        <td align="right"><span class="qingdan">X {{$v->buy_number}}</span></td>
       </tr>
       <tr>
        <th colspan="3"><strong class="orange">¥{{$v->self_price*$v->buy_number}}</strong></th>
       </tr>
       @endforeach
       <tr>
        <td class="dingimg" width="75%" colspan="2">折扣优惠</td>
        <td align="right"><strong class="green">¥0.00</strong></td>
       </tr>
       <tr>
        <td class="dingimg" width="75%" colspan="2">抵扣金额</td>
        <td align="right"><strong class="green">¥0.00</strong></td>
       </tr>
       <tr>
        <td class="dingimg" width="75%" colspan="2">运费</td>
        <td align="right"><strong class="orange">¥20.80</strong></td>
       </tr>
      </table>
     </div><!--dingdanlist/-->
     
     
    </div><!--content/-->
    
    <div class="height1"></div>
    <div class="gwcpiao">
     <table>
      <tr>
       <th width="10%"><a href="javascript:history.back(-1)"><span class="glyphicon glyphicon-menu-left"></span></a></th>
       <td width="50%">总计：<strong class="orange">¥{{$totalPrice}}</strong></td>
       <td width="40%"><a href="#" id="entrega" class="jiesuan">提交订单</a></td>
      </tr>
     </table>
    </div><!--gwcpiao/-->
    </div><!--maincont-->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/style.js"></script>
    <!--jq加减-->
    <script src="js/jquery.spinner.js"></script>
   <script>
	$('.spinnerExample').spinner({});
	</script>
  </body>
</html>
<script>
    $(function () {
         //提交订单
        $('#entrega').click(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var _this=$(this);
             //支付
            var pay_type=$('.checked').attr('pay_type');
            //console.log(pay_type);
            //购物车
            var goods_id='';
            $('.goods_id').each(function(index){
                //console.log($(this).attr('goods_id'));
                goods_id+=$(this).attr('goods_id')+',';
            })
            goods_id=goods_id.substr(0,goods_id.length-1);
            //console.log(goods_id);
            //地址
            var address_id=$('.address').attr('address_id');
            //console.log(address_id);
            $.post(
                "/entrega",
                {goods_id:goods_id,address_id:address_id,pay_type:pay_type},
                function (res) {
                    if(res=='失败'){
                        location.href="/";
                    }else{
                        location.href="/triunfo/"+res;
                    }
                }
            );
        })
    })
</script>