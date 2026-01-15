<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;
use App\Models\Profile;
use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    //
    public function edit()
    {
        $user = Auth::user();
        $profile = $user->profile ?? new Profile([
            'user_id' => $user->id,
        ]);

        return view('mypage.edit', compact('user', 'profile'));
    }


    public function update(ProfileRequest $request)
    {
        $user = Auth::user();
        $profile = $user->profile ?? new Profile(['user_id' => $user->id]);

        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('profiles', 'public');
        }
        else if ($profile->exists) {
            $data['image'] = $profile->image;
        }

        $profile->fill($data)->save();

        return redirect('/');
    }

    public function profile(Request $request)
    {
        
        $viewType = $request->input('viewType', 'sell');
        $user = Auth::user();
        $profile = $user->profile ?? new Profile();

        $reviewAvg = null;

        if ($user->receivedReviews()->count() > 0) {
            $reviewAvg = round($user->receivedReviews()->avg('rating'));
        }

        if ($viewType === 'sell') {

            $items = Item::where('user_id', $user->id)
            ->with('purchase')->get();
        }
        elseif($viewType === 'purchase') {

            $items = Item::whereHas('purchase', function ($query) use ($user) {
                $query->where('buyer_id', $user->id);
            })->with('purchase')->get();

        } elseif ($viewType === 'trading') {

            $items = Item::WhereHas('purchase', function ($query) use ($user) {
                $query->where(function ($q) use ($user) {
                    $q->where('status', 'trading')
                    ->orWhere(function ($q2) use ($user) {
                        $q2->where('status', 'completed')
                        ->whereDoesntHave('reviews', function ($qr) use ($user) {
                            $qr->where('reviewer_id', $user->id);
                        });
                    });
                });
            })
                ->where(function ($q) use ($user) {
                    $q->where('user_id', $user->id)
                    ->orWhereHas('purchase', function ($pq) use ($user) {
                        $pq->where('buyer_id', $user->id);
                    });
                })
            ->with(['purchase.messages' => function ($q) use ($user) {
                $q->where('is_read', false)
                ->where('user_id', '!=', $user->id);
            }
            ])
            ->get();
        }

        $tradingCount = Purchase::where(function ($q) use ($user) {
            $q->where('buyer_id', $user->id)
            ->orWhereHas('item', function ($q2) use ($user) {
                $q2->where('user_id', $user->id);
            });
        })
        ->where(function ($q) use ($user) {
            $q->where('status', 'trading')
            ->orWhere(function ($q2) use ($user) {
                $q2->where('status', 'completed')
                ->whereDoesntHave('reviews', function ($qr) use ($user) {
                    $qr->where('reviewer_id', $user->id);
                });
            });
        })
        ->count();

        return view('mypage.profile', compact('items', 'user', 'viewType', 'profile', 'reviewAvg', 'tradingCount'));
    }
}
