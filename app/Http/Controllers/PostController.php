<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Category;
use App\Models\CategoryPost;
use App\Models\Post;
use App\Models\PostImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $posts = Post::where('title', 'like', '%' . $request->search . '%')->orderBy('id', 'desc')->paginate(3);
        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        $categories = Category::all();

        return view('posts.create', compact('categories'));
    }

    public function store(PostRequest $request)
    {
        $post = auth()->user()->posts()->create([
            'title' => $request->title,
            'body' => $request->body
        ]);

        // upload multiple image
        foreach($request->file('images') as $file) {
            $filename = time() . '_' . $file->getClientOriginalName();
            $dir = '/upload/images';
            $path = $file->storeAs($dir, $filename);

            PostImage::create([
                'post_id' => $post->id,
                'path' => $path,
            ]);
        }

        $post->categories()->attach($request->category_ids);

        return redirect('/posts')->with('success', 'A post was created successfully.');
    }

    public function edit($id)
    {
        $post = Post::find($id);
        $oldCategoryIds = $post->categories->pluck('id')->toArray();
        $categories = Category::all();

        return view('posts.edit', compact('post', 'categories', 'oldCategoryIds'));
    }

    public function update(PostRequest $request, $id)
    {
        // Get post by id
        $post = Post::findOrFail($id);

        // delete old image
        foreach($post->images as $image) {
            Storage::delete($image->path);
            PostImage::where('post_id', $post->id)->delete();
        }

        // upload a image
        foreach($request->images as $file) {
            $filename = time() . '_' . $file->getClientOriginalName();
            $dir = '/upload/images';
            $path = $file->storeAs($dir, $filename);

            PostImage::create([
                'post_id' => $post->id,
                'path' => $path,
            ]);
        }

        // update post
        $post->update([
            'title' => $request->title,
            'body' => $request->body,
        ]);

        $post->categories()->sync($request->category_ids);
        return redirect('/posts')->with('success', 'A post was updated successfully.');
    }

    public function show($id)
    {
        $post = Post::select('posts.*', 'users.name as author')
                    ->join('users', 'posts.user_id', 'users.id')
                    ->find($id);

        return view('posts.show', compact('post'));
    }

    public function destroy($id)
    {
        Post::destroy($id);

        return redirect('/posts')->with('success', 'A post was deleted successfully');
    }
}
