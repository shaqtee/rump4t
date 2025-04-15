<?php

namespace Modules\SocialMedia\App\Http\Controllers;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Community\App\Models\SocialMedia;
use Modules\SocialMedia\App\Models\Post;

class ModeratorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::orderBy("created_at" , "desc")->paginate(6);
        return view('socialmedia.moderations::index' , compact('posts'));
    }

    public function moderate($id)
    {
        $post = Post::find($id);
        $socialmedia = SocialMedia::where('id_post', $id)->first();
        return view('socialmedia.moderations::moderate' , compact('post', 'socialmedia'));
    }

    public function comment($id)
    {
        $post = Post::find($id);
        $socialmedia = SocialMedia::where('id_post', $id)->first();
        return view('socialmedia.moderations::comment' , compact('post', 'socialmedia'));
    }

    public function commentStore(Request $request, $id)
    {
        $post = Post::find($id);
        $socialmedia = SocialMedia::where('id_post', $id)->first();
        return view('socialmedia.moderations::comment' , compact('post', 'socialmedia'));
    }

    public function commentUpdate(Request $request, $id)
    {
        $post = Post::find($id);
        $socialmedia = SocialMedia::where('id_post', $id)->first();
        return view('socialmedia.moderations::comment' , compact('post', 'socialmedia'));
    }

    public function commentDestroy($id)
    {
        $post = Post::find($id);
        $socialmedia = SocialMedia::where('id_post', $id)->first();
        return view('socialmedia.moderations::comment' , compact('post', 'socialmedia'));
    }

    public function subcomment($comment_id)
    {
        $post = Post::find($comment_id);
        $socialmedia = SocialMedia::where('id_post', $comment_id)->first();
        return view('socialmedia.moderations::subcomment' , compact('post', 'socialmedia'));
    }
    public function subcommentStore(Request $request, $comment_id)
    {
        $post = Post::find($comment_id);
        $socialmedia = SocialMedia::where('id_post', $comment_id)->first();
        return view('socialmedia.moderations::subcomment' , compact('post', 'socialmedia'));
    }
    public function subcommentUpdate(Request $request, $comment_id)
    {
        $post = Post::find($comment_id);
        $socialmedia = SocialMedia::where('id_post', $comment_id)->first();
        return view('socialmedia.moderations::subcomment' , compact('post', 'socialmedia'));
    }
    public function subcommentDestroy($comment_id)
    {
        $post = Post::find($comment_id);
        $socialmedia = SocialMedia::where('id_post', $comment_id)->first();
        return view('socialmedia.moderations::subcomment' , compact('post', 'socialmedia'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('socialmedia.moderations::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {


        // push image to s3 and write full link to database url_cover_image

        try {
        
        $img = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $path = 'socialmedia/' . $filename;
            \Storage::disk('s3')->put($path, file_get_contents($file));
            $img = \Storage::disk('s3')->url($path);
        }
        DB::beginTransaction();
        $post = Post::create([
            'title' => $request->title,
            'desc' => $request->content,
            'id_user' => auth()->user()->id,
            'url_cover_image' => $img,
        ]) ;
        $post->save();
        // dd($post);
    }catch (\Exception $e) {
        DB::rollBack();
            return redirect()->back()->with('error', 'Failed to upload image: ' . $e->getMessage());
        }
        DB::commit();
        return redirect()->route('socialmedia.moderation.index')->with('success', 'Post created successfully.');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('socialmedia.moderation::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $post = Post::find($id);
        return view('socialmedia.moderations::edit' , compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $post = Post::find($id);
            $post->title = $request->input('title');
            $post->desc = $request->input('description');

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $path = 'socialmedia/' . $filename;
                \Storage::disk('s3')->put($path, file_get_contents($file));
                $post->url_cover_image = \Storage::disk('s3')->url($path);
            } else {
                // dd('no image');
                $post->url_cover_image = null; // or set a default value if needed
            }
            $post->save();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update post: ' . $e->getMessage());

        }
        DB::commit();
        return redirect()->route('socialmedia.moderation.index')->with('success', 'Post updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
