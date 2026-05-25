<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    /**
     * Display a listing of the blogs.
     */
    public function index()
    {
        $blogs = Blog::orderBy('created_at', 'desc')->get();
        return view('admin.blogs.index', compact('blogs'));
    }

    /**
     * Show the form for creating a new blog.
     */
    public function create()
    {
        return view('admin.blogs.create');
    }

    /**
     * Store a newly created blog in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'unique:blogs,slug', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['nullable', 'string'],
            'featured_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:2048'],
            'banner_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'author_name' => ['nullable', 'string', 'max:100'],
            'author_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:1024'],
            'category' => ['nullable', 'string', 'max:100'],
            'tags' => ['nullable', 'string', 'max:255'],
            'read_time' => ['nullable', 'string', 'max:50'],
            'is_featured' => ['required', 'boolean'],
            'status' => ['required', 'string', 'in:active,inactive'],
            'published_at' => ['nullable', 'date'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:500'],
            'seo_keywords' => ['nullable', 'string', 'max:255'],
            'og_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'canonical_url' => ['nullable', 'url', 'max:255'],
            'schema_markup' => ['nullable', 'string'],
        ];

        $validatedData = $request->validate($rules);

        // Generate Slug
        $validatedData['slug'] = $validatedData['slug'] ? Str::slug($validatedData['slug']) : Str::slug($validatedData['title']);
        
        // Ensure uniqueness for generated slug
        $count = Blog::where('slug', $validatedData['slug'])->count();
        if ($count > 0) {
            $validatedData['slug'] = $validatedData['slug'] . '-' . time();
        }

        // Image uploads
        if ($request->hasFile('featured_image')) {
            $validatedData['featured_image'] = $request->file('featured_image')->store('uploads/blogs', 'public');
        }
        if ($request->hasFile('banner_image')) {
            $validatedData['banner_image'] = $request->file('banner_image')->store('uploads/blogs', 'public');
        }
        if ($request->hasFile('author_image')) {
            $validatedData['author_image'] = $request->file('author_image')->store('uploads/blogs', 'public');
        }
        if ($request->hasFile('og_image')) {
            $validatedData['og_image'] = $request->file('og_image')->store('uploads/blogs', 'public');
        }

        // Default published_at if active
        if ($validatedData['status'] === 'active' && empty($validatedData['published_at'])) {
            $validatedData['published_at'] = now();
        }

        $validatedData['created_by'] = Auth::id() ?? 1;

        Blog::create($validatedData);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog post created successfully.');
    }

    /**
     * Show the form for editing the specified blog.
     */
    public function edit(Blog $blog)
    {
        return view('admin.blogs.edit', compact('blog'));
    }

    /**
     * Update the specified blog in storage.
     */
    public function update(Request $request, Blog $blog)
    {
        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'unique:blogs,slug,' . $blog->id, 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['nullable', 'string'],
            'featured_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:2048'],
            'banner_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'author_name' => ['nullable', 'string', 'max:100'],
            'author_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:1024'],
            'category' => ['nullable', 'string', 'max:100'],
            'tags' => ['nullable', 'string', 'max:255'],
            'read_time' => ['nullable', 'string', 'max:50'],
            'is_featured' => ['required', 'boolean'],
            'status' => ['required', 'string', 'in:active,inactive'],
            'published_at' => ['nullable', 'date'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:500'],
            'seo_keywords' => ['nullable', 'string', 'max:255'],
            'og_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'canonical_url' => ['nullable', 'url', 'max:255'],
            'schema_markup' => ['nullable', 'string'],
        ];

        $validatedData = $request->validate($rules);
        $validatedData['slug'] = Str::slug($validatedData['slug']);

        // Handle uploads & old file deletions
        if ($request->hasFile('featured_image')) {
            if ($blog->featured_image) {
                Storage::disk('public')->delete($blog->featured_image);
            }
            $validatedData['featured_image'] = $request->file('featured_image')->store('uploads/blogs', 'public');
        }
        if ($request->hasFile('banner_image')) {
            if ($blog->banner_image) {
                Storage::disk('public')->delete($blog->banner_image);
            }
            $validatedData['banner_image'] = $request->file('banner_image')->store('uploads/blogs', 'public');
        }
        if ($request->hasFile('author_image')) {
            if ($blog->author_image) {
                Storage::disk('public')->delete($blog->author_image);
            }
            $validatedData['author_image'] = $request->file('author_image')->store('uploads/blogs', 'public');
        }
        if ($request->hasFile('og_image')) {
            if ($blog->og_image) {
                Storage::disk('public')->delete($blog->og_image);
            }
            $validatedData['og_image'] = $request->file('og_image')->store('uploads/blogs', 'public');
        }

        // Default published_at if active
        if ($validatedData['status'] === 'active' && empty($validatedData['published_at']) && !$blog->published_at) {
            $validatedData['published_at'] = now();
        }

        $blog->update($validatedData);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog post updated successfully.');
    }

    /**
     * Remove the specified blog from storage (soft delete).
     */
    public function destroy(Blog $blog)
    {
        $blog->delete();

        return redirect()->route('admin.blogs.index')->with('success', 'Blog post moved to trash.');
    }
}
