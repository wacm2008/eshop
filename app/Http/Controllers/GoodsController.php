<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class GoodsController extends Controller
{
    //获取分类id
    function getSonCateId($cateInfo,$pid){
        static $cate_id=[];
        foreach($cateInfo as $k=>$v){
            if($v->pid==$pid){
                $cate_id[]=$v->cate_id;
                $this->getSonCateId($cateInfo,$v->cate_id);
            }
        }
        return $cate_id;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    //列表展示
    public function index($id=0)
    {
        $cateInfo=\App\Category::get();
        //dd($cateInfo);
        //查询顶级分类的子类
        $cateSonId=$this->getSonCateId($cateInfo,$id);
        //dd($cateSonId);
        //顶级分类的id压入子类
        $cateRes=array_push($cateSonId,$id);
       //dd($cateSonId);
        //搜索
        $res=request()->input();
        //dd($res);
        $where=[];
        if(isset($res['goods_name'])?$res['goods_name']:''){
            $where[]=['goods_name','like',"%$res[goods_name]%"];
        }
        $types=request()->types;
        //dd($types);
        $field='types';
        if(request()->ajax()){
            if($types=="1"){
                $where['goods_new']=1;
                $data=\App\Goods::where($where)->whereIn('cate_id',$cateSonId)->get();
            }else if($types=="2"){
                $field='goods_sold';
                $data=\App\Goods::where($where)->whereIn('cate_id',$cateSonId)->orderBy($field,'desc')->get();
            }else if($types=="3"){
                $field='self_price';
                $data=\App\Goods::where($where)->whereIn('cate_id',$cateSonId)->orderBy($field,'desc')->get();
            }
            return view('goods/new',compact('data'));
        }else{
            $goodsInfo=\App\Goods::where($where)->whereIn('cate_id',$cateSonId)->get();
            return view('goods/prolist',compact('goodsInfo'));
        }
    }
    //详情
    public function proinfo($id)
    {
        if(!$id){
            return;
        }
        $data=\App\Goods::where('goods_id',$id)->first();
        //dd($data);
        $res=rtrim($data['goods_imgs'],'|');
        $res=explode('|',$res);
        //dd($res);
        $data['goods_imgs']=$res;
       // dd($data);
        return view('goods/proinfo',compact('data'));
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
