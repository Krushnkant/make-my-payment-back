<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\AdIds;


class AdsController extends Controller
{
    public function index(Request $request)
    {
        $sort_search = null; 
        $ads = AdIds::orderBy('created_at', 'desc');
        if ($request->has('search')){
            $sort_search = $request->search;
            $ads = $ads->where('ad_id', 'like', '%'.$sort_search.'%');
        }
        $ads = $ads->paginate(15);
        return view('ads.index', compact('ads'));
    }

    public function store(Request $request)
    {
        $ads = new AdIds; 
        $ads->ad_id = $request->ad_id;
        $ads->device_type = $request->device_type;

        if($ads->save()){
            toastr()->success('Ad Id added successfully!');
            return redirect()->route('ads.index');
        }
    }

    public function create(Request $request)
    {
       return view('ads.create');
    }

    public function edit(Request $request, $id)
    {
        $ads = AdIds::findorFail($id);
        if(isset($ads) && $ads != ""){
            return view('ads.edit', compact('ads'));
        }
    }

    public function update(Request $request, $id)
    {
        $ads = AdIds::findorFail($id);
        $ads->ad_id = $request->ad_id;
        $ads->device_type = $request->device_type;

        if($ads->save()){
            toastr()->success('Ad Id updated successfully');
            return redirect()->route('ads.index');
        }
    }

    public function destroy(Request $request)
    {
        $ads = AdIds::where('id', $request->id)->first();
        if($ads->delete()){
            return response()->json(['status'=>true]);
        }
    }

}
