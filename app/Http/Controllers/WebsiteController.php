<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Faq;
use App\Models\Category;

class WebsiteController extends Controller
{
    public function header(Request $request)
    {
        return view('backend.website_settings.header');
    }

    public function footer(Request $request)
    {
        $lang = $request->lang;
        return view('backend.website_settings.footer', compact('lang'));
    }

    public function pages(Request $request)
    {
        return view('backend.website_settings.pages.index');
    }

    public function appearance(Request $request)
    {
        return view('backend.website_settings.appearance');
    }

    public function faqFrontend()
    {
        // Fetch FAQs where category is null or blank
        $faqs = Faq::where(function($query) {
            $query->whereNull('category')
                  ->orWhere('category', '');
        })->get();

        return view('frontend.faq', compact('faqs'));
    }

    
    public function faqIndex(Request $request)
    {
        $faqs = Faq::orderBy('created_at', 'desc')->paginate(10); // Show 10 FAQs per page;
        $categories = Category::all();
        return view('backend.website_settings.faq.index', compact('faqs', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('backend.website_settings.faq.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // Validate individual FAQ inputs
        $request->validate([
            'category' => 'required|exists:categories,id',
            'question' => 'required|string',
            'answer' => 'required|string',
        ]);

        // Create a new FAQ entry
        Faq::create([
            'category' => $request->input('category'),
            'question' => $request->input('question'),
            'answer' => $request->input('answer'),
        ]);

        // Redirect to the FAQ management page with a success message
        return redirect()->route('website.faq')->with('success', 'FAQ added successfully.');
    }




    public function faqEdit($id)
    {
        $faq = Faq::findOrFail($id);
        $categories = Category::all(); // Get all categories to display in the edit form
        return view('backend.website_settings.faq.edit', compact('faq','categories'));
    }

    public function faqUpdate(Request $request, $id)
    {
        $faq = Faq::findOrFail($id);
        $faq->update([
            'question' => $request->question,
            'answer' => $request->answer,
            'category' => $request->category, // Update the category
        ]);

        return redirect()->route('website.faq')->with('success', 'FAQ updated successfully');
    }

    public function faqDelete($id)
    {
        $faq = Faq::findOrFail($id);
        $faq->delete();

        return redirect()->route('website.faq')->with('success', 'FAQ deleted successfully');
    }
}
