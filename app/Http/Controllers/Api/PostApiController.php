<?php

namespace App\Http\Controllers\Api;


use App\Jobs\ProcessMail;
use App\Mail\SendMail;
use Exception;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

 /**
 * @OA\Info(
 *     version="1.0",
 *     title="Api for Post"
 * )
 */
class PostApiController extends Controller
{
    
 /**
 * @OA\PathItem(
 *     path="/api/signup",
 *     @OA\Post(
 *         summary="Sign Up a new User",
 *         @OA\RequestBody(
 *             @OA\MediaType(
 *                 mediaType="multipart/form-data",
 *                 @OA\Schema(
 *                     @OA\Property(
 *                         property="email",
 *                         type="string"
 *                     ),
 *                     @OA\Property(
 *                         property="name",
 *                         type="string"
 *                     ),
 *                     @OA\Property(
 *                         property="password",
 *                         type="string"
 *                     ),
 *                     example={"email": "maharjan@gmail.com", "name": "Suhel Maharjan", "phone": "98089687878"}
 *                 )
 *             )
 *         ),
 *         @OA\Response(
 *             response=200,
 *             description="OK",
 *            @OA\JsonContent(
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="user sign up successfully"
 *             ),
 *             @OA\Property(
 *                 property="user",
 *                 type="object",
 *                 @OA\Property(property="name", type="string", example="suhel"),
 *                 @OA\Property(property="email", type="string", example="maharjan@gmail.com"),
 *             )
 *         ),

 *         )
 *     )
 * )
 */
    public function registerUser(Request $request){
        try{

            $userdata=$request->validate([
                'name'=>'string|required',
                'password'=>'min:8|required',
                'email'=>'required|email|unique:users,email',
            ]);
            $user=User::create([
                'name'=>$userdata['name'],
                'email'=>$userdata['email'],
                'password'=>Hash::make($userdata['password']),
            ]);
            $subject="YOur Account have been regitsered";

            // Mail::to($user)->send(new SendMail($subject,$user));
            // Mail::to($user)->send(new SendMail($subject,$user));
            // Mail::to($user)->send(new SendMail($subject,$user));

            dispatch(new ProcessMail($subject,$user));
            // dispatch(new ProcessMail($subject,$user));
            // dispatch(new ProcessMail($subject,$user));
            // dispatch(new ProcessMail($subject,$user));
            // dispatch(new ProcessMail($subject,$user));
            return response()->json([
                "message"=>"User Signup Successfully",
                "user"=>$user
            ]);
        }
        catch(Exception $e){
            return response()->json([$e->getMessage()]);
        }
    }
    public function loginUser(Request $request){
       
       try {
        $user_login_data=$request->validate([
            'email'=>'required',
            'password'=>'required|min:8'
        ]);
        $user = User::where('email', $user_login_data['email'])->first();
                           
        if($user&&Hash::check($user_login_data['password'], $user->password)){
           $token=$user->createToken('user-token')->accessToken;
           return response()->json([
            "message"=>"login Successfull",
            "token"=>$token
           ]);

        }else{
            return response()->json([
                "message"=>"login Unsuccessfull",
    
               ]);
        }
        
       } catch (Exception $e) {
        return response()->json([$e->getMessage()]);
       }
    }


    //get all data
    public function getAllPost(){
        $data=Post::all();
        return response()->json([$data]);
    }


    public function showPost($id){
        try{
            $postdata=Post::findOrFail($id);
            if($postdata){
                return response()->json(["data"=>$postdata]);
                
            }else{
                return response()->json(["message"=>"Data not found"]);
            }
        }catch(Exception $e){
            return response()->json([$e->getMessage()]);
        }
       
    }


        public function deletePost($id){
            try{
                $post=Post::findOrFail($id);
                if($post){
                    $post->delete();
                    return response()->json(['message'=>"Data Deleted Successfully"]);
                }

            }catch(Exception $e){
                return response()->json(["message"=>"Post not found","error"=>$e->getMessage()]);
            }
        }

        public function createPost(Request $request){
            try{
                $request->validate([
                    'title' => 'string|required',
                    'content' => 'string',
                    'catagory' => 'required',
                    'image' => 'sometimes|max:2048',
                ]);
                
            if ($request->hasFile('images')) {
                $images = $request->images;
                $imageName = rand(1, 1000) . time() . '.' . $images->extension();
                $images->move(public_path('images'), $imageName);
            } else {
                $imageName = "NUll";
            }

          $post=Post::create([
                'title' => $request->title,
                'content' => $request->content,
                'catagory_id' => $request->catagory,
                'image' => $imageName,
                'status' => $request->status,
                'user_id' => auth()->id()

            ]);



            //get the data of post with catagory and user table
            // $post = Post::with('catagory','user')->get();
            return response()->json(["message"=>"Post Created Successfully","data"=>$post->with('catagory','user')->find($post->id)]);
            }catch(Exception $e){
                return response()->json(["message"=>"Failed to create a post","error"=>$e->getMessage()]);
            }
        }

    public function updatePost(Request $request,int $id){
        try {
            dd($request);
            $post=Post::findOrFail($id);
            if ($request->hasFile('image')) {
                $imageName = time() . '.' . $request->image->extension();
                $request->image->move(public_path('images'), $imageName);
                if(file_exists(public_path('images/' . $post->image))){
                    unlink(public_path('images/' . $post->image));
                }
                
            } else {
                $imageName = $post->image;
            }

         
                $post->update([
                    'title' => $request->title,
                    'content' => $request->content,
                    'catagory_id' => $request->catagory,
                    'image' => $imageName,
                    'status' => $request->status,

                ]);
            $alldata = Post::with('catagory','user')->find($id);

            return response()->json(['message' => "Post Updated Successfully","data" => $alldata]);
        } catch (Exception $e) {

            return response()->json(["Post update failed" => false, "message" => $e->getMessage()]);
        }
    }
}
