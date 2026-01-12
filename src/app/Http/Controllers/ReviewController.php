<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function store(Request $request, Purchase $purchase)
    {
        $user = auth()->user();

        $isBuyer = $purchase->buyer_id === $user->id;
        $isSeller = $purchase->item->user_id === $user->id;

        abort_unless($isBuyer || $isSeller, 403);
        
        if (
            $purchase->reviews()
            ->where('reviewer_id', $user->id)
            ->exists()
        ) {
            return redirect('/');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        DB::transaction(function () use ($purchase, $user, $isBuyer, $request) {

            Review::create([
                'purchase_id' => $purchase->id,
                'reviewer_id' => $user->id,
                'reviewed_user_id' => $isBuyer
                    ? $purchase->item->user_id
                    : $purchase->buyer_id,
                'rating' => $request->rating,
            ]);

            if ($purchase->reviews()->count() >= 2) {
                $purchase->update(['status' => 'rated']);
            }
        });

        return redirect('/');
    }
}
