<?php

namespace App\Http\Controllers;

use App\Models\Option;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Models\ProductOption;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
        $data['productImage'] = ProductImage::where('product_id', $id)->first();
        
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

        if($response){
            if ($request->hasFile('images')) {
                $oldImage = ProductImage::where('product_id', $product->id)->first();
                if ($oldImage) {
                    // Delete the old image file from storage
                    Storage::delete('public/product_images/' . $oldImage->path);

                    // Delete the old image record from the database
                    $oldImage->delete();
                }
                
                $image = $request->file('images');
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

            $options = $request->options ?: []; 
            $currentOptions = $product->options()->pluck('option_id')->toArray();
            $optionsToRemove = array_diff($currentOptions, $options);
            $optionsToAdd = array_diff($options, $currentOptions);

            // Remove options no longer selected
            if (!empty($optionsToRemove)) {
                ProductOption::where('product_id', $product->id)
                            ->whereIn('option_id', $optionsToRemove)
                            ->delete();
            }

            // Add new options
            if (!empty($optionsToAdd)) {
                foreach ($optionsToAdd as $option) {
                    $productOption = new ProductOption();
                    $productOption->product_id = $product->id;
                    $productOption->option_id = $option;
                    $productOption->save();
                }
            }
        }

        return redirect()->route('products.list')->with('success', 'Product created successfully');
    }

    public function productsByCategory(Request $request)
    {
        $categoryId = $request->input('category_id');
        $products = Product::where('category_id', $categoryId)->get();

        return response()->json(['products' => $products]);
    }
}
