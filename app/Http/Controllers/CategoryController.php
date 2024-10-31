<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{

    public function __construct()
    {
        $this->constants = config('constants');
    }

    public function index(Category $category)
    {
        $companyId = Auth::user()->company_id;
        $data['categories'] = Category::with('products')->where('company_id', $companyId)->get();
        
        return view('categories.list', $data);
    }   


    public function create()
    {
        $categories = Category::with('createdByUser')->get();
        return view('categories.category', compact('categories'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories,slug',
            'desc' => 'nullable|string',
            'status' => 'required|in:1,3', 
            'icon_file' => 'nullable|max:2048', 
            'background_image' => 'nullable|max:2048', 
            'banner_image' => 'nullable|max:2048', 
        ]);
    
        $category = new Category();
        $category->fill($validatedData);
    
        // Handle icon_file upload
        if ($request->hasFile('icon_file')) {
            $path = $request->file('icon_file')->store('public/icons');
            $category->icon_file = str_replace('public/', '', $path);
        }
    
        // Handle background_image upload
        if ($request->hasFile('background_image')) {
            $path = $request->file('background_image')->store('public/backgrounds');
            $category->background_image = str_replace('public/', '', $path);
        }

        // Handle banner_image upload
        if ($request->hasFile('banner_image')) {
            $path = $request->file('banner_image')->store('public/banners');
            $category->banner_image = str_replace('public/', '', $path);
        }
    
        $category->created_by = Auth::id(); 
        $category->updated_by = Auth::id(); 
        $category->company_id = Auth::user()->company_id; 
    
        if (isset($validatedData['parent_id'])) {
            $category->parent_id = $validatedData['parent_id'];
        }
    
        try {
            $category->save();
            return redirect()->route('category.list')->with('success', 'Category created successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }
    

    public function edit($id)
    {
        $id = base64_decode($id);
        $category = Category::findOrFail($id);

        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories,slug,' . $id,
            'desc' => 'nullable|string',
            'status' => 'required|in:1,3',
            'icon_file' => 'nullable|file|image|max:2048',
            'background_image' => 'nullable|file|image|max:2048',
            'banner_image' => 'nullable|file|image|max:2048',
        ]);
    
        $category = Category::findOrFail($id);
        $category->fill($validatedData);
    
        // remove icon file
        if($request->input('icon_file_remove')){
            $category->icon_file = null;
        }
        if($request->input('background_image_remove')){
            $category->background_image = null;
        }
        if($request->input('banner_image_remove')){
            $category->banner_image = null;
        }

        // Handle icon_file upload
        if ($request->hasFile('icon_file')) {
            $path = $request->file('icon_file')->store('public/icons');
            $category->icon_file = str_replace('public/', '', $path);
        }
    
        // Handle background_image upload
        if ($request->hasFile('background_image')) {
            $path = $request->file('background_image')->store('public/backgrounds');
            $category->background_image = str_replace('public/', '', $path);
        }

        // Handle banner_image upload
        if ($request->hasFile('banner_image')) {
            $path = $request->file('banner_image')->store('public/banners');
            $category->banner_image = str_replace('public/', '', $path);
        }
    
        $category->updated_by = Auth::id();
    
        try {
            $category->save();
            return redirect()->route('category.list')->with('success', 'Category updated successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }
    

    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);
            if($category){
                $products = Product::where('category_id', $id)->get();
                if(count($products)){
                    return redirect()->route('category.list')->with('error', 'There are products related to this category. First delete or update their category');
                }
                else{
                    $category->delete();
                    return redirect()->route('category.list')->with('success', 'Category deleted successfully.');
                }
            }
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
    
    

}