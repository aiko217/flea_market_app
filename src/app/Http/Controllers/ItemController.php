<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ExhibitionRequest;
use App\Http\Requests\CommentRequest;
use App\Models\Item;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    //
    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $viewType = $request->input('viewType', 'recommend'); 

        if ($viewType === 'mylist' && Auth::check()) {
            $items = Auth::user()->favorites()
            ->whereHas('item', function ($query) use ($keyword) {
                if ($keyword) {
                    $query->where('name', 'like', "%{$keyword}%");
                }
            })
            ->with('item')
            ->get()
            ->pluck('item');
        } else {
            $items = Item::when($keyword, function ($query, $keyword) {
                return $query->where('name', 'like', "%{$keyword}%");
            })->get();
        }

        return view('index', compact('items', 'keyword', 'viewType'));
    }

    public function index(Request $request)
    {
        $keyword = $request->input('keyword');
        $viewType = $request->input('viewType', 'recommend');
    
        if ($viewType === 'mylist') {
            if (Auth::check()) {
                $items = Auth::user()->favoriteItems()
                ->with('purchase')
                ->where('items.user_id', '!=', Auth::id())
                ->when($keyword && $keyword !== '', function($query) use ($keyword) {
                    $query->where('items.name', 'like', "%{$keyword}%");
                })
                ->get();
            } else {
                $items = collect();
        }  
    }
    else {
            $items = Item::when(Auth::check(), function($query) {
                    $query->where('user_id', '!=', Auth::id());
                })
                ->when($keyword && $keyword !== '', function($query) use ($keyword) {
                    $query->where('name', 'like', "%{$keyword}%");
                })
                ->inRandomOrder()
                ->get();
        }
    
        return view('index', [
            'items' => $items,
            'viewType' => $viewType,
            'keyword' => $keyword
        ]);
}
        
    public function detail($item_id)
    {
        $item = Item::withCount(['favorites', 'comments'])
        ->with(['comments.user', 'categories'])
        ->findOrFail($item_id);

        return view('detail', compact('item'));
    }

    public function favorite($item_id)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json([
                'error' => 'ログインしてください',
                'login_url' => route('login')
            ], 401);
        }

        $item = Item::findOrFail($item_id);

        $favorite = $user->favorites()->where('item_id', $item_id)->first();

    if ($favorite) {

        $favorite->delete();
        $favorited = false;
    } else {
    
        $user->favorites()->create([
            'item_id' => $item_id
        ]);
        $favorited = true;
    }
        

        return response()->json([
            'favorited' => $favorited,
            'favorites_count' => $item->favorites()->count(),
        ]);
    }
    public function comment(CommentRequest $request, $item_id)
    {
        $item = Item::with('categories')->withCount('comments')->findOrFail($item_id);

        $item->comments()->create([
            'user_id' => Auth::id(),
            'comment' => $request->comment,
        ]);
        return back();
    }
    
    public function create()
    {
        $categories = Category::all();
        return view('sell', compact('categories'));
    }

    public function store(ExhibitionRequest $request)
    {
        $validated = $request->validated();

        $path = $request->file('image')->store('items', 'public');

        $item = Item::create([
            'user_id' => Auth::id(),
            'name' => $validated['name'],
            'description' => $validated['description'],
            'image' => $path,
            'categories' => $validated['categories'],
            'condition_id' => $validated['condition_id'],
            'price' => $validated['price'],
        ]);

        if (isset($validated['categories'])) {
        $item->categories()->sync($validated['categories']);
        }
        return redirect('/')->with('success', '商品を出品しました');
    }
}
