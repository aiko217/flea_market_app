<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ChatMessageRequest;

class ChatController extends Controller
{
    //
    public function show(Purchase $purchase) 
    {
        $user = Auth::user();

        $isBuyer = $purchase->buyer_id === $user->id;
        $isSeller = $purchase->item->user_id === $user->id;

        if (! $isBuyer && ! $isSeller) {
            abort(403);
        }

        $messages = $purchase->messages()
        ->with('user.profile')
        ->orderBy('created_at')
        ->get();

        $purchase->messages()
        ->where('is_read', false)
        ->where('user_id', '!=', $user->id)
        ->update(['is_read' => true]);

        $sidebarPurchases = collect();

        if ($isSeller) {
            $sidebarPurchases = Purchase::whereHas('item', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->where('status', 'trading')
            ->with('item')
            ->get();
        }

        $partner = $isBuyer
        ? $purchase->item->user
        : $purchase->buyer;


        return view('chat.show', compact('purchase', 'messages', 'isSeller', 'isBuyer', 'sidebarPurchases', 'partner'));
    }

    public function store(ChatMessageRequest $request, Purchase $purchase)
    {
        $data = $request->validated();

        $message = new Message();
        $message->purchase_id = $purchase->id;
        $message->user_id = auth()->id();
        $message->body = $data['content'];

        if($request->hasFile('image')) {
            $message->image = $request->file('image')->store('chat_images', 'public');
        }

        $message->save();

        return redirect()->route('chat.show', $purchase->id);
    }

    public function update(Request $request, Message $message) {
        abort_if($message->user_id !== auth()->id(), 403);

        $request->validate([
            'body' => 'required|max:400',
        ]);

        $message->update([
            'body' => $request->body,
        ]);
        return back();
    }

    public function destroy(Message $message)
    {
        abort_if($message->user_id !== auth()->id(), 403);

        $message->delete();

        return back();
    }
}
