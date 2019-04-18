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
     <form action="#" method="get" class="reg-login">
      <div class="lrBox">
       <div class="lrList"><input type="text" id="address_name" placeholder="收货人" /></div>
       <div class="lrList"><input type="text" id="address_detail" placeholder="详细地址" /></div>

        <select class="area" id="province">
            <option value="" selected="selected" >--请选择--</option>
            @foreach($provinceInfo as $k=>$v)
            <option value="{{$v->area_id}}">{{$v->area_name}}</option>
            @endforeach
        </select>


        <select class="area" id="city">
            <option value="" selected="selected" >--请选择--</option>
        </select>


        <select class="area" id="area">
            <option value="" selected="selected" >--请选择--</option>
        </select>

       <div class="lrList"><input type="text" id="address_tel" placeholder="手机" /></div>
       <div class="lrList2"><input type="checkbox" id="is_default" /> <button>设为默认</button></div>
      </div><!--lrBox/-->
      <div class="lrSub">
       <input type="button" class="save" value="保存" />
      </div>
     </form><!--reg-login/-->

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
        //三级联动
        $(document).on('change','.area',function(){
            var _this=$(this);
            var area_id=_this.val();
            //console.log(area_id);
            var _option="<option value='0' selected='selected'>--请选择--</option>";
            _this.nextAll('select').html(_option);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post(
                "/getNextArea",
                {area_id:area_id},
                function(res){
                    if(res.code==1){
                        for(var i in res['areaInfo']){
                            //console.log(res['areaInfo'][i]['area_id']);
                            _option+="<option value='"+res['areaInfo'][i]['area_id']+"' >"+res['areaInfo'][i]['area_name']+"</option>";
                        }
                        //console.log(_option);
                        _this.next('select').html(_option);
                    }
                },
                'json'
            );
        })
        //保存
        $(document).on('click','.save',function(){
            var province=$('#province').val();
            var city=$('#city').val();
            var area=$('#area').val();
            var address_name=$('#address_name').val();
            var address_detail=$('#address_detail').val();
            var address_tel=$('#address_tel').val();
            var is_default=$("#is_default").prop('checked');
            if(address_name==''){
                alert('收货人名不能为空');
                return false;
            }
            if(address_detail==''){
                alert('收货地址不能为空');
                return false;
            }
            if(address_tel==''){
                alert('收货人电话不能为空');
                return false;
            }
            if(is_default==true) {
                is_default=1;
            }else{
                is_default=2;
            }

            $.get(
                '/addAddress',
                { province: province,city:city,area:area,address_name:address_name,address_detail:address_detail,address_tel:address_tel,is_default:is_default},
                function (res){
                    //console.log(res);
                    if(res.code==1){
                        alert('成功');
                    }else{
                        alert('失败');
                    }
                },
                'json'
            )
        })
    })
</script>