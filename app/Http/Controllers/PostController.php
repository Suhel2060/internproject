<?php

namespace App\Http\Controllers;

use App\Models\Catagory;
use App\Models\Post;
use App\Models\PostImage;
use Exception;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $post=Post::all();
        return view('post.index',compact('post'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $catagory=Catagory::where('status',1)->select('id','catagory')->get();
       return view('post.create',compact('catagory'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'string|required',
            'content'=>'string',
            'catagory'=>'required',
            'image' => 'sometimes|max:2048',
        ]);

        // $post=Post::create([
        //     'title'=>$request->title,
        //     'content'=>$request->content,
        //     'catagory_id'=>$request->catagory,
        //     'status'=>$request->status,
        //     'image'=>'',
        //     'user_id'=>auth()->id()
            
        // ]);

        // if($request->hasFile('images')){
        //     $images=$request->images;
        //     foreach($images as $image){

        //         $imageName = rand(1,1000).time().'.'.$image->extension();
        //         $image->move(public_path('images'),$imageName);
        //         $postimage=new PostImage;
        //         $postimage->images=$imageName;
        //         $postimage->post_id=$post->id;
        //         $postimage->save();
        //     }

        // }
        // else{
        //     $imageName="NUll";
        // }

        if($request->hasFile('images')){
            $images=$request->images;
                $imageName = rand(1,1000).time().'.'.$images->extension();
                $images->move(public_path('images'),$imageName);

        }
        else{
            $imageName="NUll";
        }

        Post::create([
            'title'=>$request->title,
            'content'=>$request->content,
            'catagory_id'=>$request->catagory,
            'image'=>$imageName,
            'status'=>$request->status,
            'user_id'=>auth()->id()
            
        ]);

        return redirect()->route('post.index');
    }

  

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        $catagory=Catagory::where('status',1)->select('id','catagory')->get();
        return view('post.edit',compact('post','catagory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        try{
        $request->validate([
            'title' => 'string|required',
            'content'=>'string',
            'catagory'=>'required',
            'image' => 'sometimes|max:2048',
        ]);
        if($request->hasFile('image')){
            $imageName = time().'.'.$request->image->extension();
            //moving image to the public/images/imagename path
            $request->image->move(public_path('images'),$imageName);
            unlink(public_path('images/'.$post->image));
        }
        else{
            $imageName=$post->image;
        }

        Post::find($post->id)
        ->update([
            'title'=>$request->title,
            'content'=>$request->content,
            'catagory_id'=>$request->catagory,
            'image'=>$imageName,
            'status'=>$request->status,
            
        ]);
        return redirect()->route('post.index');
    }catch(Exception $e){
        //back helps to redirection to the update page 
        return redirect()->back()->with('error',$e->getMessage());
    }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        try{
            unlink(public_path('images/'.$post->image));
            Post::find($post->id)->delete();
            return redirect()->route('post.index')->with("success","Data deleted Successfully");

        }catch(Exception $e){
            return redirect()->back()->with("error",$e->getMessage());
        }
    }
}
