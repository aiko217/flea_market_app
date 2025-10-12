<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;
use App\Models\Profile;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    //
    public function edit()
    {
        $user = Auth::user();
        $profile = $user->profile;

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

        $profile->fill($data)->save();

        return redirect('/');
    }

    public function profile(Request $request)
    {
        $viewType = $request->input('viewType', 'sell');
        $user = Auth::user();
        $profile = $user->profile ?? null;

        if($viewType === 'purchase') {
            $items = Item::whereHas('purchase', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->with('purchase')->get();
        } else {
            $items = Item::where('user_id', $user->id)->with('purchase')->get();
        }
        return view('mypage.profile', compact('items', 'user', 'viewType', 'profile'));
    }
}
