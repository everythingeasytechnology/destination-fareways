<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::where('status', '!=', 'draft')->orderBy('created_at', 'desc')->get();
        return view('admin.blogs.index', compact('blogs'));
    }

    public function create()
    {
        return view('admin.blogs.create');
    }

    public function store(Request $request)
    {
        $draftId = $request->input('draft_id') ?: null;

        $slugRule = [
            'nullable', 'string', 'max:255',
            Rule::unique('blogs', 'slug')
                ->where(fn ($q) => $q->where('status', '!=', 'draft'))
                ->ignore($draftId),
        ];

        $rules = [
            'title'          => ['required', 'string', 'max:255'],
            'slug'           => $slugRule,
            'subtitle'       => ['nullable', 'string', 'max:255'],
            'excerpt'        => ['nullable', 'string', 'max:500'],
            'content'        => ['nullable', 'string'],
            'featured_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:2048'],
            'banner_image'   => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'author_name'    => ['nullable', 'string', 'max:100'],
            'author_image'   => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:1024'],
            'category'       => ['nullable', 'string', 'max:100'],
            'tags'           => ['nullable', 'string', 'max:255'],
            'read_time'      => ['nullable', 'string', 'max:50'],
            'is_featured'    => ['required', 'boolean'],
            'status'         => ['required', 'string', 'in:active,inactive'],
            'published_at'   => ['nullable', 'date'],
            'seo_title'      => ['nullable', 'string', 'max:255'],
            'seo_description'=> ['nullable', 'string', 'max:500'],
            'seo_keywords'   => ['nullable', 'string', 'max:255'],
            'og_image'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'canonical_url'  => ['nullable', 'url', 'max:255'],
            'schema_markup'  => ['nullable', 'string'],
        ];

        $validatedData = $request->validate($rules);

        $validatedData['slug'] = $validatedData['slug']
            ? Str::slug($validatedData['slug'])
            : Str::slug($validatedData['title']);

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

        if ($validatedData['status'] === 'active' && empty($validatedData['published_at'])) {
            $validatedData['published_at'] = now();
        }

        $validatedData['created_by'] = Auth::id() ?? 1;

        // If an auto-saved draft exists, update it and publish instead of creating a duplicate
        if ($draftId) {
            $draft = Blog::where('id', $draftId)->where('status', 'draft')->first();
            if ($draft) {
                $draft->update($validatedData);
                return redirect()->route('admin.blogs.index')->with('success', 'Blog post published successfully.');
            }
        }

        // Ensure slug uniqueness when creating fresh
        $base  = $validatedData['slug'];
        $count = Blog::where('slug', $base)->count();
        if ($count > 0) {
            $validatedData['slug'] = $base . '-' . time();
        }

        Blog::create($validatedData);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog post created successfully.');
    }

    public function autosave(Request $request)
    {
        $draftId = $request->input('draft_id');

        $data = $request->only([
            'title', 'slug', 'subtitle', 'excerpt', 'content',
            'author_name', 'category', 'tags', 'read_time',
            'is_featured', 'seo_title', 'seo_description', 'seo_keywords',
            'canonical_url', 'schema_markup',
        ]);

        if ($request->filled('published_at')) {
            $data['published_at'] = $request->input('published_at');
        }

        $data['status']     = 'draft';
        $data['created_by'] = Auth::id() ?? 1;
        $data['is_featured'] = $data['is_featured'] ?? 0;

        if ($draftId) {
            $blog = Blog::where('id', $draftId)->where('status', 'draft')->first();
            if ($blog) {
                $blog->update($data);
                return response()->json(['success' => true, 'id' => $blog->id]);
            }
        }

        if (empty($data['title'])) {
            return response()->json(['success' => false, 'message' => 'Enter a title first to enable auto-save.']);
        }

        $slug = !empty($data['slug']) ? Str::slug($data['slug']) : Str::slug($data['title']);
        $base = $slug;
        $i    = 1;
        while (Blog::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }
        $data['slug'] = $slug;

        $blog = Blog::create($data);

        return response()->json(['success' => true, 'id' => $blog->id]);
    }

    public function edit(Blog $blog)
    {
        return view('admin.blogs.edit', compact('blog'));
    }

    public function update(Request $request, Blog $blog)
    {
        $rules = [
            'title'          => ['required', 'string', 'max:255'],
            'slug'           => ['required', 'string', 'unique:blogs,slug,' . $blog->id, 'max:255'],
            'subtitle'       => ['nullable', 'string', 'max:255'],
            'excerpt'        => ['nullable', 'string', 'max:500'],
            'content'        => ['nullable', 'string'],
            'featured_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:2048'],
            'banner_image'   => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'author_name'    => ['nullable', 'string', 'max:100'],
            'author_image'   => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:1024'],
            'category'       => ['nullable', 'string', 'max:100'],
            'tags'           => ['nullable', 'string', 'max:255'],
            'read_time'      => ['nullable', 'string', 'max:50'],
            'is_featured'    => ['required', 'boolean'],
            'status'         => ['required', 'string', 'in:active,inactive,draft'],
            'published_at'   => ['nullable', 'date'],
            'seo_title'      => ['nullable', 'string', 'max:255'],
            'seo_description'=> ['nullable', 'string', 'max:500'],
            'seo_keywords'   => ['nullable', 'string', 'max:255'],
            'og_image'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'canonical_url'  => ['nullable', 'url', 'max:255'],
            'schema_markup'  => ['nullable', 'string'],
        ];

        $validatedData = $request->validate($rules);
        $validatedData['slug'] = Str::slug($validatedData['slug']);

        if ($request->hasFile('featured_image')) {
            if ($blog->featured_image) Storage::disk('public')->delete($blog->featured_image);
            $validatedData['featured_image'] = $request->file('featured_image')->store('uploads/blogs', 'public');
        }
        if ($request->hasFile('banner_image')) {
            if ($blog->banner_image) Storage::disk('public')->delete($blog->banner_image);
            $validatedData['banner_image'] = $request->file('banner_image')->store('uploads/blogs', 'public');
        }
        if ($request->hasFile('author_image')) {
            if ($blog->author_image) Storage::disk('public')->delete($blog->author_image);
            $validatedData['author_image'] = $request->file('author_image')->store('uploads/blogs', 'public');
        }
        if ($request->hasFile('og_image')) {
            if ($blog->og_image) Storage::disk('public')->delete($blog->og_image);
            $validatedData['og_image'] = $request->file('og_image')->store('uploads/blogs', 'public');
        }

        if ($validatedData['status'] === 'active' && empty($validatedData['published_at']) && !$blog->published_at) {
            $validatedData['published_at'] = now();
        }

        $blog->update($validatedData);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog post updated successfully.');
    }

    public function destroy(Blog $blog)
    {
        $blog->delete();
        return redirect()->route('admin.blogs.index')->with('success', 'Blog post moved to trash.');
    }
}
