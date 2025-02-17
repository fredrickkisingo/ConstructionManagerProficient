<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Post;
use App\User;
use DB;
use App\Map;

class PostsController extends Controller
{
     /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        //sql statement would be  $posts =DB::select('SELECT *FROM')
       $posts = Post::orderBy('created_at','desc')->paginate(10);
       return view('posts.index')->with('posts',$posts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validating the input here
        $this->validate($request, [
            'title'=>'required',
            'body'=>'required',

            'phone_number' => 'required',
            'location' => 'required',
            'cover_image' =>'image|nullable|max:1999',
             ]);
            //Handle File Upload
            if($request->hasFile('cover_image')){
                //Get filename with the extension
                $filenamewithExt=$request->file('cover_image')->getClientOriginalName();
                //Get just filename
                $filename= pathinfo($filenamewithExt,PATHINFO_FILENAME);
                //Get just ext
                $extension= $request->file('cover_image')->getClientOriginalExtension();
                //Filename to store
                $fileNameToStore=$filename.'_'.time().'.'.$extension;
                //Upload Image
                $path=$request->file('cover_image')->storeAs('public/cover_images',$fileNameToStore);
            } else {
                $fileNameToStore= 'noimage.jpg';
            }
            //create Item
            $post= new Post;
            $post->title=$request->input('title');
            $post->body=$request->input('body');
            //not setting it into a request since i'm not obtaining it from  a form
            $post->user_id =auth()->user()->id;
            $post->cover_image =$fileNameToStore;
        
            $post->phone_number=$request->input('phone_number');
            $post->location=$request->input('location');
            

            $post->save();//saved first to access the id in next step

            return redirect('/posts')->with('success','Product Uploaded');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);
       return view('posts.show')->with('post',$post);
    }

    /* 
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::find($id);

        //Check for correct user
        if(auth()->user()->id !==$post->user_id){
            return redirect('/posts')->with('error', 'Unauthorized Page');
        }
        return view('posts.edit')->with('post',$post);
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
        $this->validate($request,[
            'title'=>'required',
            'body'=>'required'
            ]);
             //Handle File Upload
             if($request->hasFile('cover_image')){
                //Get filename with the extension
                $filenamewithExt=$request->file('cover_image')->getClientOriginalName();
                //Get just filename
                $filename= pathinfo($filenamewithExt,PATHINFO_FILENAME);
                //Get just ext
                $extension= $request->file('cover_image')->getClientOriginalExtension();
                //Filename to store
                $fileNameToStore=$filename.'_'.time().'.'.$extension;
                //Upload Image
                $path=$request->file('cover_image')->storeAs('public/cover_images',$fileNameToStore);
            } 
            //we get rid of else because we don't want to replace with it no image
        
            $post= Post::find($id);
            $post->title=$request->input('title');
            $post->body=$request->input('body');
            $post->products_price = $request -> input('price');
            $post->phone_number = $request -> input('phone_number');
            $post->location=$request->input('location');
           
            if($request->hasFile('cover_image')){
                $post->cover_image= $fileNameToStore;
            }
            $post->save();
            return redirect('/posts')->with('success','Item Details Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         
        $post = Post::find($id);
        $mapLocate= Map::where('posts_id','=',$id);
        //Check for correct user
         if(auth()->user()->id !==$post->user_id){
            return redirect('/posts')->with('error', 'Unauthorized Access');
         }
         if($post->cover_image !='noimage.jpg'){
            //Delete image
            Storage::delete('public/cover_images/'.$post->cover_image);
         }
        $post->delete();
        return redirect('/posts')->with('success','Item Removed');
    }
}
