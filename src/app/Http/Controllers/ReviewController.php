<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Purchase $purchase)
    {
        abort_if($purchase->buyer_id !== auth()->id(), 403);
        
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        Review::create([
            'purchase_id' => $purchase->id,
            'reviewer_id' => auth()->id(),
            'reviewed_user_id' => $purchase->item->user_id,
            'rating' => $request->rating,
        ]);

        $purchase->update([
            'status' => 'rated'
        ]);

        return redirect('/');
    }
}
