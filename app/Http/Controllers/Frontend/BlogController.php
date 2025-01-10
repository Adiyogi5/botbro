<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request, $slug = '')
    {
        
        $title = 'Blogs';
        
        if ($slug) {
            $query = Blog::select('blogs.*', 'blog_categories.slug', 'blogs.slug')
                ->leftjoin('blog_categories', 'blog_categories.id', '=', 'blogs.category_id')
                ->where(array('blogs.status' => 1, 'blog_categories.slug' => $slug));
        } else {
            $query = Blog::select('blogs.*')->where('status', 1);
        }

        $allblogcat = BlogCategory::select('*')->where('status', 1)->get();

        $allblog = $query->paginate(12);

        $allsidebarblog = Blog::select('blogs.*')->where('status', 1)->orderBy('created_at', 'desc')->limit(5)->get();
        
        return view('frontend.blog', compact('title','allblogcat','slug', 'allblog', 'allsidebarblog'));
    }


    public function blogDetails($slug = '')
    {
        $data = Blog::where('slug', $slug)->first();
        $title = $data->title;

        $blogdetails = Blog::select('blogs.*')
            ->where('blogs.slug', $slug)
            ->where('blogs.status', '1')
            ->get();

        $allblogcat = BlogCategory::select('*')->where('status', 1)->get();

        $allsidebarblog = Blog::select('blogs.*')->where('status', 1)->orderBy('created_at', 'desc')->limit(5)->get();

        return view('frontend.blogdetails', compact('title', 'slug', 'blogdetails', 'data', 'allblogcat', 'allsidebarblog'));

    }
}
