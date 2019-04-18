<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //存用户信息
        $user=Auth::user();
        //dd($user);
//        session(['userInfo'=>$user]);
//        dd(session('userInfo'));
        $res=request()->input();
        $where=[];
        if(isset($res['goods_name'])?$res['goods_name']:''){
            $where[]=['goods_name','like',"%$res[goods_name]%"];
        }
        $goodsInfo=\App\Goods::where($where)->paginate(4);
        $cateWhere=[
            'pid'=>0
        ];
        $cateInfo=\App\Category::where($cateWhere)->get();
        //dd($cateInfo);
        //dd($data);
        return view('index/index',compact('goodsInfo','user','res','cateInfo'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function footer()
    {
        return view('public/footer');
    }
    public function top()
    {
        return view('public/top');
    }
}
