<?php

namespace App\Http\Controllers;

use App\Events\TestNotification;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{

    public function create()
    {
        return view('frontend.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'post' => 'required|string|max:255'
        ]);


        $post = Post::create([
            'post' => $request->post
        ]);


        event(new TestNotification([
            'post' => $post->post,
            'desc' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Corrupti tenetur temporibus nulla earum quisquam quis porro hic blanditiis sed id natus, sit vero. Quasi et, ut dignissimos culpa id laudantium!'
        ]));


        return redirect()->back()->with('success','Post Created Succesfully');
    }


}
