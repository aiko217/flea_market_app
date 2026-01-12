<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Profile;
use App\Models\Purchase;
use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\AddressRequest;


class PurchaseController extends Controller
{
    //
    public function create($item_id)
    {
        $item = Item::findOrFail($item_id);
        $profile = Profile::where('user_id', auth()->id())->first();

        $temp = session('temp_address');
        if ($temp) {
            $address = $temp;
        } else {
            $address = [
                'postal_code' => $profile->postal_code,
                'address' => $profile->address,
                'building' => $profile->building,
            ];
        }

        return view('purchase', compact('item', 'profile', 'address'));
    }

    public function store(PurchaseRequest $request, $item_id)
    {
        $user = Auth::user();
        $item = Item::findOrFail($item_id);

        $data = $request->validated();

        $temp = session('temp_address');
        if ($temp) {
            $address = $temp;
        } else {
            $address = [
                'postal_code' => $user->profile->postal_code,
                'address'     => $user->profile->address,
                'building'    => $user->profile->building,
            ];
        }

        Purchase::create([
            'item_id' => $item_id,
            'buyer_id' => Auth::id(),
            'sending_postcode' => $address['postal_code'],
            'sending_address' => $address['address'],
            'sending_building' => $address['building'],
            'status' => 'trading',
        ]);
        session()->forget('temp_address');

        return redirect('/')->with('success', '購入が完了しました');
    }

    public function editAddress($item_id)
    {
        $item = Item::findOrFail($item_id);
        $profile = Auth::user()->profile;

        return view('address', compact('item', 'profile'));
    }

    public function updateAddress(AddressRequest $request, $item_id)
    {

        $data = $request->validated();

        session([
            'temp_address' => [
            'postal_code' => $data['postal_code'],
            'address' => $data['address'],
            'building' => $data['building'] ?? '',
            ]
            ]);

        return redirect("/purchase/{$item_id}")->with('success', '配送先住所を変更しました');
    }
}
