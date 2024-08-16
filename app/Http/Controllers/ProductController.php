<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Option;
use App\Models\ProductImage;
use App\Models\ProductOption;
use App\Models\Category;

class ProductController extends Controller
{
    public function index()
    {
        $companyId = Auth::user()->company_id;
        $data['products'] = Product::where('company_id', $companyId)->where('is_enable', 1)->get();
        
        return view('products.list', $data);
    }

    public function create()
    {
        $companyId = Auth::user()->company_id;
        $data['categories'] = Category::where('company_id', $companyId)->get();
        $data['options'] = Option::where('company_id', $companyId)->where('is_enable', 1)->get();
        return view('products.create', $data);
    }

    public function store(Request $request)
    {
        // return $request->file('images');
        $this->validate($request, [
            'title'         => 'required',
            'price'         => 'required',
            'description'   => 'required',
        ]);

        $product = new Product();
        $product->title         = $request->title;
        $product->description   = $request->description;
        $product->price         = $request->price;
        $product->category_id   = $request->category_id;
        $product->company_id    = Auth::user()->company_id;
        $product->created_by    = Auth::id();

        $response = $product->save();
        if($response){
            if ($request->hasFile('images')) {
                $images = $request->file('images');
                foreach($images as $image){
                    $file_name  = time() . '_' . uniqid('', true) . '.' . $image->getClientOriginalExtension();
                    $org_name   = $image->getClientOriginalName();
        
                    $image->storeAs('public/product_images/', $file_name);
        
                    $file_data = new ProductImage();
        
                    $file_data['product_id']    = $product->id;
                    $file_data['file_name']     = $org_name;
                    $file_data['path']          = $file_name;
                    $file_data['created_by']    = Auth::id();
        
                    $file_data->save();
                }
            }

            $options = $request->options;
            if($options){
                foreach($options as $option){
                    $productOption = new ProductOption();
                    $productOption->product_id = $product->id;
                    $productOption->option_id = $option;
                    $productOption->save();
                }
            }
        }
        else{
            return redirect()->route('products.create')->with('failed', 'Something went wrong');
        }

        return redirect()->route('products.list')->with('success', 'Product created successfully');
    }

    public function edit($id)
    {
        $companyId = Auth::user()->company_id;
        $data['categories'] = Category::where('company_id', $companyId)->get();
        $data['product'] = Product::with('options.option.option_values')->find($id);
        $data['options'] = Option::where('company_id', $companyId)->where('is_enable', 1)->get();
        $data['product_options'] = $data['product']->options->pluck('option_id')->toArray();
        
        return view('products.edit', $data);
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'title'         => 'required',
            'price'         => 'required',
            'category_id'   => 'required',
            'description'   => 'required',
        ]);

        $post_data['title']         = $request->title;
        $post_data['description']   = $request->description;
        $post_data['price']         = $request->price;
        $post_data['category_id']   = $request->category_id;
        $post_data['updated_by']    = Auth::id();

        $product = Product::find($request->id);
        $response = $product->update($post_data);

        return redirect()->route('products.list')->with('success', 'Product created successfully');
    }

    public function productsByCategory(Request $request)
    {
        $categoryId = $request->input('category_id');
        $products = Product::where('category_id', $categoryId)->get();

        return response()->json(['products' => $products]);
    }
}
