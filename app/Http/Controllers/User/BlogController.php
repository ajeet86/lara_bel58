<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Comment;
use Auth;

class BlogController extends Controller
{
    /**
     * Post Comment on blog.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function post_blog_comment(Request $request, $blog_id){
        if (Auth::check()) {
            $this->validate($request, [
                'comment' => 'required',
            ]);
            try {
                $comment = new Comment;
                $comment->comment = $request->comment;
                $comment->blog_or_pod_id = $blog_id;
                $comment->user_id = Auth::User()->id;
                $comment->type = 'blog';
                $comment->save();
                $data = ['success' => 1, 'message' => 'Comment Posted Successfully. Please note, comments must be approved before they are published.',
                    'user_name' => Auth::User()->name, 'comment' => $request->comment,
                    'time' => \Carbon\Carbon::now()->diffForHumans()];
            } catch (Exception $ex) {
                $data = ['error' => 1, 'message' => 'Some error occur!! Please try again.'];
            }
        } else {
            $data = ['error' => 0, 'redirect' => 'login'];
        }
        return $data;
    }
    
    /**
     * Post Comment on blog.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function post_pod_comment(Request $request, $pod_id){
        if (Auth::check()) {
            $this->validate($request, [
                'comment' => 'required',
            ]);
            try {
                $comment = new Comment;
                $comment->comment = $request->comment;
                $comment->blog_or_pod_id = $pod_id;
                $comment->user_id = Auth::User()->id;
                $comment->type = 'pod';
                $comment->save();
                $data = ['success' => 1, 'message' => 'Comment Posted Successfully. Please note, comments must be approved before they are published.',
                    'user_name' => Auth::User()->name, 'comment' => $request->comment,
                    'time' => \Carbon\Carbon::now()->diffForHumans()];
            } catch (Exception $ex) {
                $data = ['error' => 1, 'message' => 'Some error occur!! Please try again.'];
            }
        } else {
            $data = ['error' => 0, 'redirect' => 'login'];
        }
        return $data;
    }
}
