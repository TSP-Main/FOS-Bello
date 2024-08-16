<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Menu;
use App\Models\Category;
use App\Models\Product;

class MenuController extends Controller
{
    public function index()
    {
        // $companyId = Auth::user()->company_id; 
        // $data['menu'] = Menu::where('company_id', $companyId)->where('is_enable', 1)->get();
        return view('menu.list');
    }

    public function create()
    {
        $companyId = Auth::user()->company_id; 
        $data['categories'] = Category::where('company_id', $companyId)->get();
        // $data['menu'] = Menu::where('company_id', Auth::user()->company_id)->get();
        // return $data['menu'];
        return view('menu.create', $data);
    }

    public function store(Request $request)
    {
        // return $request;

        // $validatedData = $request->validate([
        //     'category_id' => 'required|array',
        //     'category_id.*' => 'exists:categories,id',
        //     'product_id' => 'required|array',
        //     'product_id.*' => 'exists:products,id',
        // ]);

        $companyId = Auth::user()->company_id;

        $menu = new Menu();
        foreach ($request->product_id as $productId) {

            if($productId != 'all'){
                $categoryId = Product::where('id', $productId)->value('category_id');

                Menu::updateOrCreate(
                    ['category_id' => $categoryId, 'product_id' => $productId, 'company_id' => $companyId ],
                    [
                        'category_id' => $categoryId,
                        'product_id' => $productId,
                        'company_id' => $companyId,
                        'created_by' => Auth::id(),
                    ]
                );
            }
        }

        return redirect()->route('menu.create')->with('success', 'Data Saved');
    }

    public function edit()
    {
        $companyId = Auth::user()->company_id;
        $data['categories'] = Category::where('company_id', $companyId)->get();
        
        $menu = Menu::where('company_id', Auth::user()->company_id)->get();
        $data['menuItems'] = $menu->groupBy('category_id');
        $data['productIds'] = $menu->pluck('product_id')->toArray();

        $products = Product::where('company_id', Auth::user()->company_id)->get();
        $data['products'] = $products->groupBy('category_id');

        // return $data['products'];
        return view('menu.edit', $data);
    }

    public function update(Request $request)
    {
        $companyId = Auth::user()->company_id;

        // Remove which is not present now
        $existingMenuItems = Menu::where('company_id', $companyId)->pluck('product_id')->toArray();
        $productIdsNew = array_filter($request->product_id, function($id) {
            return $id !== 'all';
        });
        $productsToRemove = array_diff($existingMenuItems, $productIdsNew);

        // Remove from db
        if (!empty($productsToRemove)) {
            Menu::whereIn('product_id', $productsToRemove)
                ->where('company_id', $companyId)
                ->delete();
        }

        $menu = new Menu();
        foreach ($request->product_id as $productId) {

            if($productId != 'all'){
                $categoryId = Product::where('id', $productId)->value('category_id');

                Menu::updateOrCreate(
                    ['category_id' => $categoryId, 'product_id' => $productId, 'company_id' => $companyId ],
                    [
                        'category_id' => $categoryId,
                        'product_id' => $productId,
                        'company_id' => $companyId,
                        'created_by' => Auth::id(),
                    ]
                );
            }
        }

        return redirect()->route('menu.edit')->with('success', 'Data Saved');
    }
}
