<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Blog;
use App\Models\SeoSetting;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a paginated list of Blog Posts
     */
    public function index(Request $request)
    {
        $settings = Setting::first();
        $seoData = SeoSetting::where('page_identifier', 'blogs')->first();

        $query = Blog::where('status', 'published')->orderBy('published_at', 'desc');

        // Apply Category Filter if present
        $category = $request->input('category');
        if ($category) {
            $query->where('category', $category);
        }

        // Apply Search query if present
        $search = $request->input('q');
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('excerpt', 'LIKE', "%{$search}%")
                  ->orWhere('content', 'LIKE', "%{$search}%");
            });
        }

        // Get a Featured post
        $featuredBlog = Blog::where('status', 'published')
            ->where('is_featured', true)
            ->orderBy('published_at', 'desc')
            ->first();
            
        if (!$featuredBlog) {
            $featuredBlog = Blog::where('status', 'published')
                ->orderBy('published_at', 'desc')
                ->first();
        }

        $blogs = $query->paginate(9)->withQueryString();

        // Fetch dynamic categories
        $categories = Blog::where('status', 'published')
            ->distinct()
            ->pluck('category')
            ->filter();

        $breadcrumbs = [
            ['title' => 'Blog', 'url' => route('blog.index')]
        ];

        return view('frontend.blogs.index', compact('settings', 'blogs', 'featuredBlog', 'categories', 'seoData', 'breadcrumbs'));
    }

    /**
     * Display a Single Blog Post Details
     */
    public function show($slug)
    {
        $settings = Setting::first();
        
        $blog = Blog::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        // Increment view count safely
        $blog->increment('views');

        // Fetch related posts excluding the current one
        $relatedBlogs = Blog::where('status', 'published')
            ->where('id', '!=', $blog->id)
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();

        // Dynamic SEO Details mapping model contents
        $seoData = (object) [
            'meta_title' => $blog->seo_title ?? $blog->title . ' | Destination Fareways',
            'meta_description' => $blog->seo_description ?? $blog->excerpt,
            'meta_keywords' => $blog->seo_keywords ?? 'travel tips, flight guides, business cabin, ' . $blog->author_name,
            'og_title' => $blog->seo_title ?? $blog->title,
            'og_description' => $blog->seo_description ?? $blog->excerpt,
            'og_image' => $blog->og_image ?? $blog->featured_image,
            'canonical_url' => $blog->canonical_url ?? route('blog.show', $blog->slug),
            'robots_tag' => 'index, follow',
            'schema_markup' => $blog->schema_markup ?? null
        ];

        $breadcrumbs = [
            ['title' => 'Blog', 'url' => route('blog.index')],
            ['title' => $blog->title]
        ];

        return view('frontend.blogs.show', compact('settings', 'blog', 'relatedBlogs', 'seoData', 'breadcrumbs'));
    }
}
