<?php

namespace App\Http\Controllers;

use App\Models\Option;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Models\ProductOption;
use Illuminate\Validation\Rule;
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
        $data['yesNo'] = config('constants.YES_NO');
        return view('products.create', $data);
    }

    public function store(Request $request)
    {
        $yesNoValues = array_keys(config('constants.YES_NO'));

        $this->validate($request, [
            'title'         => 'required',
            'price'         => 'required',
            'description'   => 'required',
            'description'   => 'required',
            'ask_instruction' => ['required', Rule::in($yesNoValues)],
        ]);

        $product = new Product();
        $product->title         = $request->title;
        $product->description   = $request->description;
        $product->price         = $request->price;
        $product->category_id   = $request->category_id;
        $product->company_id    = Auth::user()->company_id;
        $product->ask_instruction = $request->ask_instruction;
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
        $id = base64_decode($id);
        $companyId = Auth::user()->company_id;
        $data['categories'] = Category::where('company_id', $companyId)->get();
        $data['product'] = Product::with('options.option.option_values')->find($id);
        $data['options'] = Option::where('company_id', $companyId)->where('is_enable', 1)->get();
        $data['product_options'] = $data['product']->options->pluck('option_id')->toArray();
        $data['productImage'] = ProductImage::where('product_id', $id)->first();
        $data['yesNo'] = config('constants.YES_NO');
        
        return view('products.edit', $data);
    }

    public function update(Request $request)
    {
        $yesNoValues = array_keys(config('constants.YES_NO'));

        $this->validate($request, [
            'title'         => 'required',
            'price'         => 'required',
            'category_id'   => 'required',
            'description'   => 'required',
            'ask_instruction' => ['required', Rule::in($yesNoValues)],
        ]);

        $post_data['title']         = $request->title;
        $post_data['description']   = $request->description;
        $post_data['price']         = $request->price;
        $post_data['category_id']   = $request->category_id;
        $post_data['ask_instruction'] = $request->ask_instruction;
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
        $companyId = Auth::user()->company_id;
        $categoryId = $request->input('category_id');
        $products = Product::where('category_id', $categoryId)->where('company_id', $companyId)->with('images', 'options.option.option_values')->get();

        return response()->json(['products' => $products]);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete(); // Soft delete

        return redirect()->route('products.list')->with('success', 'Record deleted successfully.');
    }

    public function getOptions(Request $request)
    {
        $productId = $request->input('product_id');
        $product = Product::with('options.option.option_values')->find($productId);

        $response = [
            'id' => $product->id,
            'options' => $product->options->mapWithKeys(function ($option) {
                return [
                    $option->option->name => [
                        'id' => $option->option->id,
                        'option_values' => $option->option->option_values->map(function ($value) {
                            return [
                                'id' => $value->id,
                                'name' => $value->name,
                                'price' => $value->price,
                                'is_enable' => $value->is_enable,
                            ];
                        })->toArray(),
                    ],
                ];
            })->toArray(),
        ];
        
        return response()->json($response);
    }
}
