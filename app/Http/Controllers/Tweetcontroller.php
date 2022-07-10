<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//追記
use Validator;
use App\Models\Laratter;

use Auth;

use App\Models\User;

class Tweetcontroller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $tweets=laratter::getALLOrderByUpdated_at();
        return view('laratter.index',compact('tweets'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('laratter.create');
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
        $validator = Validator::make($request ->all(),[
            'tweet'=>'required | max:191',
            'description'=>'required',
            ]);
            
        if($validator -> fails()){
            return redirect()
            ->route('laratter.create')
            ->withInput()
            ->withErrors($validator);
        }
        
        $data = $request ->merge(['user_id' => Auth::user()->id])->all();
        $result = laratter::create($data);
        
        //$result = laratter::create($request->all());
        return redirect()->route('tweet.index');
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
        $tweet = laratter::find($id);
        return view ('laratter.show', compact('tweet'));
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
        $tweet = laratter::find($id);
        return view('laratter.edit', compact('tweet'));
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
            $validator = Validator::make($request ->all(),[
            'tweet'=>'required | max:191',
            'description'=>'required',
            ]);
            
        if($validator -> fails()){
            return redirect()
            ->route('laratter.create')
            ->withInput()
            ->withErrors($validator);
        }
        
        $result = laratter::find($id)->update($request->all());
        return redirect()->route('tweet.index');
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
    $tweet = laratter::find($id)->delete();
    return redirect()->route('tweet.index');
    }
    
    public function mydata()
  {
    // Userモデルに定義したリレーションを使用してデータを取得する．
    $tweets = User::query()
      ->find(Auth::user()->id)
      ->userTweets()
      ->orderBy('created_at','desc')
      ->get();
    return view('laratter.index', compact('tweets'));
  }
}
