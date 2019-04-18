@include('public.top')
<script src="/js/jquery.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
  <body>
    <div class="maincont">
     <header>
      <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
      <div class="head-mid">
       <h1>收货地址</h1>
      </div>
     </header>
     <div class="head-top">
      <img src="images/head.jpg" />
     </div><!--head-top/-->
     <table class="shoucangtab">
      <tr>
       <td width="75%"><a href="/address" class="hui"><strong class="">+</strong> 新增收货地址</a></td>
       <td width="25%" align="center" style="background:#fff url(images/xian.jpg) left center no-repeat;"><a href="javascript:;" class="orange">删除信息</a></td>
      </tr>
     </table>
     
     <div class="dingdanlist" onClick="window.location.href='#'">
         @foreach($addressInfo as $k=>$v)
         @if($v->is_default==1)
             <div class="address" style="border: 1px solid red;" address_id="{{$v->address_id}}">
                 <table>
                     <tr>
                         <td width="70%">
                             <h3>{{$v->address_name}} {{$v->address_tel}}</h3>
                             <time>{{$v->province}}{{$v->city}}{{$v->area}} {{$v->address_detail}}</time>
                         </td>
                         <td align="right"><a href="#"  class="default" address_id="{{$v->address_id}}">已默认</a>&nbsp; &nbsp; &nbsp; &nbsp; <a href="/address" class="hui"><span class="glyphicon glyphicon-check"></span> 修改信息</a></td>
                     </tr>
                 </table>
             </div>
         @else
             <div class="address" address_id="{{$v->address_id}}">
                 <table>
                     <tr>
                         <td width="70%">
                             <h3>{{$v->address_name}} {{$v->address_tel}}</h3>
                             <time>{{$v->province}}{{$v->city}}{{$v->area}} {{$v->address_detail}}</time>
                         </td>
                         <td align="right"><a href="#"  class="default" address_id="{{$v->address_id}}">设为默认</a>&nbsp; &nbsp; &nbsp; &nbsp; <a href="/address" class="hui"><span class="glyphicon glyphicon-check"></span> 修改信息</a></td>
                     </tr>
                 </table>
             </div>
         @endif
        @endforeach
     </div><!--dingdanlist/-->

    @include('public.footer')
    <!--jq加减-->
    <script src="js/jquery.spinner.js"></script>
   <script>
	$('.spinnerExample').spinner({});
   </script>
  </body>
</html>
<script>
    $(function () {
        //默认
        $(document).on('click','.default',function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var _this=$(this);
            var address_id=_this.attr('address_id');
            //console.log(address_id);
            $.post(
                "/addressDefault",
                {address_id:address_id},
                function(res){
                    if(res.code ==1){
                        history.go(0);
                    }
                },
                'json'
            );
        })
    })
</script>