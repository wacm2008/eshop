<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    //购物车展示
    public function index()
    {
        $user=Auth::user();
        //dd($user);
        //$id=Auth::id();
//        $carInfo=\App\Car::where(['user_id'=>$id])->get();
//        dd($carInfo);
        if($user){
            $count=\App\Car::get()->count();
            $carInfo = DB::table('car')
                ->join('goods', 'car.goods_id', '=', 'goods.goods_id')
                ->select('car.*', 'goods.goods_name', 'goods.self_price','goods.goods_img','goods.goods_num')
                ->get();
            //dd($carInfo);
            return view('car/car',compact('carInfo','count'));
        }else{
            return redirect('/login');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    //购物车添加
    public function create()
    {
        $id=Auth::id();
        //print_r($id);
        if($id){
            $buy_number=request()->input('buy_number');
            $goods_id=request()->input('goods_id');
            $goodsInfo=\App\Goods::where('goods_id',$goods_id)->first();
            $carInfo=\App\Car::where('goods_id',$goods_id)->first();
            //累加购物车
            if($carInfo){
                $this->checkGoodsNum($goods_id,$carInfo['buy_number'],$buy_number);
                $updateInfo=[
                    'buy_number'=>$carInfo['buy_number']+$buy_number,
                ];
                $carWhere=[
                    'user_id'=>$id,
                    'goods_id'=>$goods_id,
                ];
                $carInfoRes=\App\Car::where($carWhere)->update($updateInfo);
            }else{
                //正常添加
                $this->checkGoodsNum($goods_id,$carInfo['buy_number'],$buy_number);
                $car=new \App\Car;
                $where=[
                    'goods_id'=>$goods_id,
                    'buy_number'=>$buy_number,
                    'user_id'=>$id,
                    'create_time'=>time(),
                    'goods_name'=>$goodsInfo['goods_name'],
                    'self_price'=>$goodsInfo['self_price'],
                    'goods_img'=>$goodsInfo['goods_img'],
                ];
                $carInfoRes=$car->insert($where);
            }
        }else{
            return redirect('/login');
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

    /**
     * 1.根据商品id去商品表查询(goods_num)商品数据
     *      如果没有此商品信息，提示非法请求，无此商品
     *         如果有 就拿购买数量比较库存
     *              如果购买数量大于库存 提示库存不足 返回最大库存
     *
     */
    //改变购买数量
    public function num(){
        $id=Auth::id();
        if($id){
            $buy_number=request()->input('buy_number');
            $goods_id=request()->input('goods_id');
            $goodsInfo=\App\Goods::where('goods_id',$goods_id)->first()->toArray();
            if(!$goodsInfo){
                echo "<script>alert('无此商品')</script>";
            }
            if($goodsInfo){
                $this->checkGoodsNum($goods_id,0,$buy_number);
                $carWhere=[
                    'user_id'=>$id,
                    'goods_id'=>$goods_id,
                ];
                $updateInfo=[
                    'buy_number'=>$buy_number
                ];
                $carInfoRes=\App\Car::where($carWhere)->update($updateInfo);
            }
        }
    }
    //总价
    public function totalPrice(){
        $uesr_id=Auth::id();
        if($uesr_id){
            $goods_id=request()->input('goods_id');
            $where=[
                'user_id'=>$uesr_id,
                'goods_id'=>$goods_id
            ];
            $carInfo=\App\Car::where($where)->get();
            //var_dump($carInfo);
            $goodInfo=\App\Goods::where('goods_id',$goods_id);
            $totalPrice=0;
            foreach($carInfo as $k=>$v){
                $totalPrice+=$v->self_price*$v->buy_number;
            }
            echo $totalPrice;
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

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
