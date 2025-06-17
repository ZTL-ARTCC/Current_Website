<?php

namespace App\Http\Controllers;

use App\Audit;
use App\Merch;
use Auth;
use Carbon\Carbon;
use Config;
use Illuminate\Http\Request;

class MerchStore extends Controller {
    public function viewStore() {
        $merch_items = Merch::get();
        $merch_email = Config::get('ztl.merch_store_email');
        return view('dashboard.controllers.merch_store', compact('merch_items', 'merch_email'));
    }

    public function adminStore() {
        $merch_items = Merch::get();
        return view('dashboard.admin.store.index', compact('merch_items'));
    }

    public function viewItem($id) {
        $store_item = Merch::find($id);
        return view('dashboard.admin.store.view', compact('store_item'));
    }

    public function newItem() {
        return view('dashboard.admin.store.new');
    }

    public function editItem($id) {
        $store_item = Merch::find($id);
        return view('dashboard.admin.store.edit', compact('store_item'));
    }

    public function saveItem(Request $request, $id = null) {
        $validator = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'image' => 'mimes:jpg,bmp,gif,png'
        ]);
        if ($request->file('image') != null) {
            $filename = Carbon::now()->timestamp . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->storeAs('public/store', $filename);
        }

        $store_item = new Merch;
        if (!is_null($id)) {
            $store_item = Merch::find($id);
        }
        $store_item->title = $request->input('title');
        $store_item->description = $request->input('description');
        $store_item->price = $request->input('price');
        if (isset($filename)) {
            $store_item->image = $filename;
        }
        $store_item->flag = $request->input('flag');
        $store_item->save();

        $audit = new Audit;
        $audit->cid = Auth::id();
        $audit->ip = $_SERVER['REMOTE_ADDR'];
        $audit->what = Auth::user()->full_name.' modified a store item.';
        $audit->save();

        return redirect('/dashboard/admin/store')->with('success', 'Store item modified successfully.');
    }

    public function deleteItem($id) {
        $store_item = Merch::find($id);
        $store_item->delete();

        $audit = new Audit;
        $audit->cid = Auth::id();
        $audit->ip = $_SERVER['REMOTE_ADDR'];
        $audit->what = Auth::user()->full_name.' removed a store item.';
        $audit->save();

        return redirect('/dashboard/admin/store')->with('success', 'Store item deleted successfully.');
    }
}
