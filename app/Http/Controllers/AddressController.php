<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //收货地址信息
        $addressInfo=$this->getAddressInfo();
        return view('address/add-address',compact('addressInfo'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //查询所有省的地址
        $provinceInfo=$this->getAreaInfo(0);
        return view('address/address',compact('provinceInfo'));

    }
    //收货地址信息
    public function getAddressInfo(){
        $user_id=Auth::id();
        $where=[
            'user_id'=>$user_id,
            'address_status'=>1
        ];
        $addressInfo=\App\Address::where($where)->get();
        if(!empty($addressInfo)){
            foreach($addressInfo as $k=>$v){
                $addressInfo[$k]['province']=\App\Area::where(['area_id'=>$v['province']])->value('area_name');
                $addressInfo[$k]['city']=\App\Area::where(['area_id'=>$v['city']])->value('area_name');
                $addressInfo[$k]['area']=\App\Area::where(['area_id'=>$v['area']])->value('area_name');
            }
            return $addressInfo;
        }else{
            return false;
        }
    }
    //获取地区分类
    public function getAreaInfo($pid){
        $where=[
            'pid'=>$pid
        ];
        $areaData=\App\Area::where($where)->get();
        //var_dump($areaData);
        if(!empty($areaData)){
            return $areaData;
        }else{
            return false;
        }
    }
    //下一级分类
    public function getNextArea(){
        $area_id=request()->all('area_id');
        if(empty($area_id)){
            echo "<script>alert('请选择地区')</script>";
        }
        $areaInfo=$this->getAreaInfo($area_id);
        //dd($areaInfo);
        echo json_encode(['areaInfo'=>$areaInfo,'code'=>1]);
    }
    //收货地址添加
    public function addAddress(){
        $user_id=Auth::id();
        $data=request()->all();
        if($data['is_default']==1){
            $where=[
                'user_id'=>$user_id,
                'create_time'=>time()
            ];
            $res=\App\Address::where($where)->update(['is_default'=>2]);
        }
        $res=\App\Address::insert($data);
        if($res){
            return json_encode(['code'=>1,'msg'=>'成功']);
        }else{
            return json_encode(['code'=>2,'msg'=>'失败']);
        }
    }
    //默认
    public function addressDefault(){
        $user_id=Auth::id();
        $address_id = request()->input('address_id');
        $result=\App\Address::where('user_id',$user_id)->update(['is_default'=>2]);
        $res=\App\Address::where('address_id',$address_id)->update(['is_default'=>1]);
        if($result && $res){
            return json_encode(['code'=>1,'msg'=>'成功']);
        }else{
            return json_encode(['code'=>2,'msg'=>'失败']);
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
