<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use think\response\Redirect;
use Log;
class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id=Auth::id();
        $goods_id=request()->input('goods_id');
        //购物车商品信息
        $goodsInfo = DB::table('car')
            ->join('goods', 'car.goods_id', '=', 'goods.goods_id')
            ->select('car.*','goods.goods_name', 'goods.self_price','goods.goods_img','goods.goods_num')
            ->get();
        //获取总价
        $totalPrice=0;
        foreach($goodsInfo as $k=>$v){
            $totalPrice+=$v->self_price*$v->buy_number;
        }
        //收货地址信息
        $addressInfo=$this->getAddressInfo();
        //dd($addressInfo);
        return view('order/pay',compact('goodsInfo','totalPrice','addressInfo'));
    }
    //收货地址信息
    public function getAddressInfo(){
        $user_id=Auth::id();
        $where=[
            'user_id'=>$user_id,
            'address_status'=>1,
            'is_default'=>1
        ];
        $addressInfo=\App\Address::where($where)->first();
        //dd($addressInfo);
        if(!empty($addressInfo)){
                $addressInfo['province']=\App\Area::where(['area_id'=>$addressInfo['province']])->value('area_name');
                $addressInfo['city']=\App\Area::where(['area_id'=>$addressInfo['city']])->value('area_name');
                $addressInfo['area']=\App\Area::where(['area_id'=>$addressInfo['area']])->value('area_name');
            return $addressInfo;
        }else{
            return false;
        }
    }
    //提交订单
    public function entrega(){
        $user_id=Auth::id();
        //echo $user_id;
        $address_id=request()->input('address_id');
        //echo $address_id;
        $goods_id=request()->input('goods_id');
        $goods_id=explode(',',$goods_id);
        //print_r($goods_id);
        $pay_type=request()->input('pay_type');
        //print_r($pay_type);
        if(empty($address_id)){
            echo "<script>alert('请选择地址')</script>";
        }
        if(empty($goods_id)){
            echo "<script>alert('请选择商品')</script>";
        }
        if(empty($pay_type)){
            echo "<script>alert('请选择支付方式')</script>";
        }
        if($user_id){
            //订单号
            $orderInfo['order_no']=$this->getOrderNo();
            //购物车商品信息
            $goodsInfo = DB::table('car')
                ->join('goods', 'car.goods_id', '=', 'goods.goods_id')
                ->select('car.*','goods.goods_name', 'goods.self_price','goods.goods_img','goods.goods_num')
                ->get()
                ->toArray();
            //print_r($goodsInfo);exit;
            //订单总价
            $order_amount=0;
            foreach($goodsInfo as $k=>$v){
                $order_amount+=$v->buy_number*$v->self_price;
            }
            //订单信息存入订单表
            $orderInfo['order_amount']=$order_amount;
            $orderInfo['pay_type']=$pay_type;
            $orderInfo['user_id']=$user_id;
            $orderInfo['create_time']=time();
            $orderInfo['update_time']=time();
            $order= new \App\Order;
            $res1=$order->insert($orderInfo);
            //订单商品信息存入订单详情表
            $order_id=DB::getPdo()->lastInsertId($res1);
            $orderdetail=[];
            foreach ($goods_id as $k=>$v){
                $res=\App\Car::where('user_id',$user_id)->where('goods_id',$v)->first();
                $orderdetail['buy_number'] = $res['buy_number'];
                $orderdetail['goods_id'] = $res['goods_id'];
                $orderdetail['goods_name'] = $res['goods_name'];
                $orderdetail['goods_img'] = $res['goods_img'];
                $orderdetail['self_price'] = $res['self_price'];
                $orderdetail['create_time'] = time();
                $orderdetail['update_time'] = time();
                $orderdetail['user_id'] = $user_id;
                $orderdetail['order_id'] = $order_id;
                $res2=\App\OrderDetail::insert($orderdetail);
            }
//            foreach($goodsInfo as $k=>$v){
//                $goodsInfo[$k]->order_id=$order_id;
//                $goodsInfo[$k]->user_id=$user_id;
//            }
//            //print_r($goodsInfo);exit;
//            $orderDetail= new \App\OrderDetail;
//            $res2=$orderDetail->insert($goodsInfo);
            //订单收货地址存入订单收货地址表
            $addressWhere=[
                'address_id'=>$address_id
            ];
            $addressInfo=\App\Address::where($addressWhere)->first()->toArray();
            $addressInfo['order_id']=$order_id;
            $addressInfo['user_id']=$user_id;
            unset($addressInfo['address_id']);
            unset($addressInfo['is_default']);
            unset($addressInfo['address_status']);
            //print_r($addressInfo);exit;
            $res3=\App\OrderAddress::insert($addressInfo);

            //清空当前购物车数据
            $carWhere=[
                'user_id'=>$user_id,
            ];

          //  DB::connection()->enableQueryLog();
            $res4=\App\Car::where($carWhere)->whereIn('goods_id',$goods_id)->delete();
           // var_dump(DB::getQueryLog());
            //减少商品库存
            foreach($goodsInfo as $k=>$v){
                //$res=\App\Goods::where('goods_id',$v)->first();
                $goodsWhere=[
                    'goods_id'=>$v->goods_id
                ];
                $updateInfo=[
                    'goods_num'=>$v->goods_num-$v->buy_number
                ];
                $res5=\App\Goods::where($goodsWhere)->update($updateInfo);
            }
        }
        if($res1&&$res2&&$res3&&$res4&&$res5){
            return $orderInfo['order_no'];
        }else{
            return "失败";
        }
    }
    //检测商品数量库存
    public function checkGoodsNum($goods_id,$num,$buy_number,$type=1){
        $where=[
            'goods_id'=>$goods_id
        ];
        $goods_num=\App\Goods::where($where)->value('goods_num');
        if($num+$buy_number>$goods_num){
            $buy_pieza=$goods_num-$num;
            if($type==1){
                echo '购买数量超过库存，还可购买'.$buy_pieza.'件';
            }else{
                return false;
            }
        }else{
            return true;
        }
    }
    //获取订单号
    public function getOrderNo(){
        return time().rand(1111,9999);
    }
    //订单提交成功
    public function triunfo($order_no){
        $user_id=Auth::id();
        $where=[
            'order_no'=>$order_no
        ];
        if($user_id){
            $orderInfo=DB::table('order')->where($where)->first();
        }else{
            return redirect('/login');
        }
        return view('order/success',compact('orderInfo'));
    }
    //支付
//    public function alipay($orderno){
//        if(!$orderno){
//            return redirect('/')->with('没有此订单信息哦');
//        }
//        $order=DB::table('order')->select(['order_amount','order_no'])->where('order_no',$orderno)->first();
//        //dd($order);
//        if($order->order_amount<=0){
//            return redirect('/')->with('无效的订单');
//        }
//        require_once app_path('/libs/alipay/pagepay/service/AlipayTradeService.php');
//        require_once app_path('/libs/alipay/pagepay/buildermodel/AlipayTradePagePayContentBuilder.php');
//
//        //商户订单号，商户网站订单系统中唯一订单号，必填
//        $out_trade_no = trim($orderno);
//
//        //订单名称，必填
//        $subject = trim('测试');
//
//        //付款金额，必填
//        $total_amount = $order->order_amount;
//
//        //商品描述，可空
//        $body = trim('测试');
//
//        //构造参数
//        $payRequestBuilder = new \AlipayTradePagePayContentBuilder();
//        $payRequestBuilder->setBody($body);
//        $payRequestBuilder->setSubject($subject);
//        $payRequestBuilder->setTotalAmount($total_amount);
//        $payRequestBuilder->setOutTradeNo($out_trade_no);
//
//        $aop = new \AlipayTradeService(config('alipay'));
//
//        /**
//         * pagePay 电脑网站支付请求
//         * @param $builder 业务参数，使用buildmodel中的对象生成。
//         * @param $return_url 同步跳转地址，公网可以访问
//         * @param $notify_url 异步通知地址，公网可以访问
//         * @return $response 支付宝返回的信息
//         */
//        $response = $aop->pagePay($payRequestBuilder,config('alipay.return_url'),config('alipay.notify_url'));
//
//        //输出表单
//        var_dump($response);
//    }
    public function alipay($orderno){
        if(!$orderno){
            return redirect('/')->with('没有此订单信息哦');
        }
        $order=DB::table('order')->select(['order_amount','order_no'])->where('order_no',$orderno)->first();
        //dd($order);
        if($order->order_amount<=0){
            return redirect('/')->with('无效的订单');
        }
        require_once app_path('/libs/alipay.trade.wap.pay/wappay/service/AlipayTradeService.php');
        require_once app_path('/libs/alipay.trade.wap.pay/wappay/buildermodel/AlipayTradeWapPayContentBuilder.php');
        if (!empty($orderno)&& trim($orderno)!=""){
            //商户订单号，商户网站订单系统中唯一订单号，必填
            $out_trade_no = $orderno;

            //订单名称，必填
            $subject = "测试";

            //付款金额，必填
            $total_amount = $order->order_amount;

            //商品描述，可空
            $body = "测试";

            //超时时间
            $timeout_express="1m";

            $payRequestBuilder = new \AlipayTradeWapPayContentBuilder();
            $payRequestBuilder->setBody($body);
            $payRequestBuilder->setSubject($subject);
            $payRequestBuilder->setOutTradeNo($out_trade_no);
            $payRequestBuilder->setTotalAmount($total_amount);
            $payRequestBuilder->setTimeExpress($timeout_express);

            $payResponse = new \AlipayTradeService(config('alipay'));
            $result=$payResponse->wapPay($payRequestBuilder,config('alipay.return_url'),config('alipay.notify_url'));

        }
    }
    //同步跳转
    public function paypay(){
        //echo 'ya lo ha pegado，gracias';
        /* 实际验证过程建议商户添加以下校验。
        1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
        2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
        3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
        4、验证app_id是否为该商户本身。
        */
        require_once app_path('/libs/alipay.trade.wap.pay/wappay/service/AlipayTradeService.php');
        $arr=$_GET;
        $alipaySevice = new \AlipayTradeService(config('alipay'));
        $result = $alipaySevice->check($arr);
        $out_trade_no=trim($_GET['out_trade_no']);
        $total_amount=trim($_GET['total_amount']);
        $order=DB::table('order')->where(['order_no'=>$out_trade_no,'order_amount'=>$total_amount])->first();
        if(!$order){
            return redirect('/')->with('无此订单');
        }
        if(trim($_GET['seller_id'])!=config('alipay.seller_id')||trim($_GET['app_id'])!=config('alipay.app_id')){
            return redirect('/')->with('无此订单');
        }
        if($order){
            //商户订单号

            $out_trade_no = htmlspecialchars($_GET['out_trade_no']);

            //支付宝交易号

            $trade_no = htmlspecialchars($_GET['trade_no']);

            return redirect('/')->with("成功支付<br />外部订单号：".$out_trade_no);
        }else {
            return redirect('/pay/{orderno}')->with("支付失败");
        }
    }
    //异步支付
    public function notifypay(){
        $post=json_encode($_POST);
        Log::channel('pay')->info($post);
        $out_trade_no=trim($_POST['out_trade_no']);
        $total_amount=trim($_POST['total_amount']);
        $order=DB::table('order')->where(['order_no'=>$out_trade_no,'order_amount'=>$total_amount])->first();
        if(!$order){
            Log::channel('pay')->info($post.'无此订单');exit;
        }
        if(trim($_POST['seller_id'])!=config('alipay.seller_id')||trim($_POST['app_id'])!=config('alipay.app_id')){
            Log::channel('pay')->info($post.'无此商家或买家');exit;
        }
        //交易状态
        $trade_status = $_POST['trade_status'];
        if($_POST['trade_status'] == 'TRADE_FINISHED') {

        }else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
            if($order){
                DB::table('order')->where(['order_no'=>$out_trade_no])->update(['pay_status'=>2]);
            }
            echo "success";
        }else{
            echo "fail";
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
