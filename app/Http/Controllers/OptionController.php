<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Models\Option;
use App\Models\OptionValue;

class OptionController extends Controller
{
    public function index()
    {
        $companyId = Auth::user()->company_id; 
        $data['options'] = Option::where('company_id', $companyId)->where('is_enable', 1)->get();
        return view('options.list', $data);
    }

    public function create()
    {
        $data['optionsType'] = config('constants.PRODUCT_OPTIONS_TYPE');
        $data['yesNo'] = config('constants.YES_NO');
        return view('options.create', $data);
    }

    public function store(Request $request)
    {
        $yesNoValues = array_keys(config('constants.YES_NO'));
        $optionType = array_keys(config('constants.PRODUCT_OPTIONS_TYPE'));

        $request->validate([
            'name' => 'required',
            'is_required' => ['required', Rule::in($yesNoValues)],
            'option_type' => ['required', Rule::in($optionType)],
        ]);

        $option = new Option();
        $option->name = $request->name;
        $option->is_required = $request->is_required;
        $option->option_type = $request->option_type;
        $option->company_id = Auth::user()->company_id;
        $option->created_by = Auth::user()->id;

        $response = $option->save();

        if($response){
            $valueName = $request->value_name;
            $valuePrice = $request->value_price;

            if($valueName){
                foreach($valueName as $key => $value){
                    if($value){
                        $postData = new OptionValue();
                        $postData->option_id = $option->id;
                        $postData->name = $value;
                        $postData->price = $valuePrice[$key];
        
                        $postData->save();
                    }
                }
            }
        }
        else{
            return redirect()->route('options.create')->with('failed', 'Something went wrong');
        }

        return redirect()->route('options.list')->with('success', 'Data Saved');
    }

    public function edit($id)
    {
        $data['optionsType'] = config('constants.PRODUCT_OPTIONS_TYPE');
        $data['yesNo'] = config('constants.YES_NO');
        $data['option'] = Option::with('option_values')->find(base64_decode($id));
        
        return view('options.edit', $data);
    }

    public function update(Request $request)
    {
        $yesNoValues = array_keys(config('constants.YES_NO'));
        $optionType = array_keys(config('constants.PRODUCT_OPTIONS_TYPE'));
    
        $id = base64_decode($request->id);
        
        $request->validate([
            'name' => 'required',
            'is_required' => ['required', Rule::in($yesNoValues)],
            'option_type' => ['required', Rule::in($optionType)],
        ]);

        $post_data['name']         = $request->name;
        $post_data['is_required']  = $request->is_required;
        $post_data['option_type']  = $request->option_type;
        $post_data['updated_by']   = Auth::user()->id;

        $option = Option::find($id);
        $response = $option->update($post_data);
    
        if($response){
            $valueName = $request->value_name;
            $valuePrice = $request->value_price;
            $valueIds = $request->value_id;

            // Update or create option values
            if($valueName){
                $processedIds = [];

                foreach ($valueName as $key => $value) {
                    if($value){
                        if (isset($valueIds[$key])) {
                            // Update existing OptionValue
                            $optionValue = OptionValue::find($valueIds[$key]);
                            if ($optionValue) {
                                $optionValue->name = $value;
                                $optionValue->price = $valuePrice[$key];
                                $optionValue->save();
                                $processedIds[] = $optionValue->id;
                            }
                        } else {
                            // Create new OptionValue
                            $optionValue = new OptionValue();
                            $optionValue->option_id = $option->id;
                            $optionValue->name = $value;
                            $optionValue->price = $valuePrice[$key];
                            $optionValue->save();
                            $processedIds[] = $optionValue->id;
                        }
                    }
                }
        
                OptionValue::where('option_id', $option->id)
                    ->whereNotIn('id', $processedIds)
                    ->delete();
            }
        } else {
            return redirect()->route('options.edit', $id)->with('failed', 'Something went wrong');
        }
    
        return redirect()->route('options.list')->with('success', 'Option updated successfully');
    }
}
