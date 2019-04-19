@include('public.top')
  <body>
    <div class="maincont">
     <header>
      <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
      <div class="head-mid">
       <h1>购物车</h1>
      </div>
     </header>
     <div class="susstext">订单提交成功</div>
     <div class="sussimg">&nbsp;</div>
     <div class="dingdanlist">
      <table>
       <tr>
        <td width="50%">
         <h3>订单号：{{$orderInfo->order_no}}</h3>
         <time>下单时间：{{date('Y-m-d H:i:s')}}<br />
失效日期：2019-11-11</time>
         <strong class="orange">¥{{$orderInfo->order_amount}}</strong>
        </td>
        <td align="right"><span class="orange">等待支付</span></td>
       </tr>
      </table>
     </div><!--dingdanlist/-->
     <div class="succTi orange">请您尽快完成付款，否则订单将被取消</div>
     
    </div><!--content/-->
    
    <div class="height1"></div>
    <div class="gwcpiao">
     <table>
      <tr>
       <td width="50%"><a href="prolist.html" class="jiesuan" style="background:#5ea626;">继续购物</a></td>
       <td width="25%"><a href="/pay/{{$orderInfo->order_no}}" class="jiesuan">支付宝支付</a></td>
	   <td width="25%"><a href="/weixin/paypay/{{$orderInfo->order_no}}" class="jiesuan">微信支付</a></td>
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