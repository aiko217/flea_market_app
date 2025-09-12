<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ItemController extends Controller
{
    //
    public function search(Request $request)
    {
        $keyword = $request->input('keyword');

        $item = item::when($keyword, function ($query, $keyword) {
            return $query->where('name', 'like', "%{$keyword}%");
        })->get();
        return view('index', compact('item', 'keyword'));
    }
}
