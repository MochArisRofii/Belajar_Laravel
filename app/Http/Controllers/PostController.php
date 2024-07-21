<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $posts = Post::latest()->paginate(5);

        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'title' => 'required|min:5',
            'content' => 'required|min:10'
        ]);

        $image = $request->file('image');
        $image->storeAs('public/posts', $image->hashName());

        Post::create([
            'image' => $image->hashName(),
            'title' => $request->title,
            'content' => $request->content
        ]);
        // Debugging: Log the created post
        // Log::info($post);    

        return redirect()->route('posts.index')->with(['success' => 'Wes Di Simpen!']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) : View
    {
        $post = Post::findOrFail($id);

        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id) : View
    {
        $post = Post::findOrFail($id);

        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) : RedirectResponse
    {
        $this->validate($request, [
            'image' => 'required|mimes:png,jpg,jpeg|max:2048',
            'title' => 'required|min:5',
            'content' => 'required|min:10'
        ]);

        $post = Post::findOrFail($id);

        if ($request->hasFile('image')) {
            
            $image = $request->file('image');
            $image->storeAs('public/posts', $image->hashName());

            Storage::delete('public/posts/'.$post->image);

            $post->update([
                'image' => $image->hashName(),
                'title' => $request->title,
                'content' => $request->content
            ]);
        } else {
            
            $post->update([
                'title' => $request->title,
                'content' => $request->content
            ]);

            return redirect()->route('posts.index')->with(['success' => 'Wes Di Ubah Cik']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) :RedirectResponse
    {
        $post = Post::findOrFail($id);

        Storage::delete('public/posts/'. $post->image);

        $post->delete();

        return redirect()->route('posts.index')->with(["success" => "Data Wes Di Hapus Cik!!"]);
    }
}
