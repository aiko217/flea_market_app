@extends('layouts.login_register')

@section('css')
<link rel="stylesheet" href="{{ asset('css/show.css') }}">
@endsection

@section('content')
<div class="all-contents">
    <div class="chat-sidebar">
        <h2 class="sidebar-title">その他の取引</h2>

        @if($isSeller)
        <ul class="sidebar-list">
            @foreach($sidebarPurchases as $sidePurchase)
            <li class="sidebar-item 
            {{ $sidePurchase->id === $purchase->id ? 'active' : ''}}">
                <a href="{{ route('chat.show', $sidePurchase->id) }}" class="item-button">
                    {{ $sidePurchase->item->name }}
                </a>
            </li>
            @endforeach
        </ul>
        @endif
    </div>
    <main class="chat-main">
        <div class="chat-header">
            <div class="chat-header-inner">
                <div class="chat-user">
                    <div class="avatar">
                        <img src="@if(!empty($partner->profile) && $partner->profile->image) {{ asset('storage/' . $partner->profile->image) }}
                        @else
                            {{ asset('images/avatar-placeholder.png') }}
                             @endif"
                            alt="avatar">
                    </div>
                    <h1>「{{ $partner->name }}」さんとの取引画面</h1>
                </div>

                @if($isBuyer && $purchase->status === 'trading')
                <button id="completeBtn" class="complete-btn">取引を完了する</button>
                @endif
            </div>
        </div>
        <div class="chat-item">
            <img src="{{ asset('storage/' . $purchase->item->image) }}" alt="">
            <div class="item-info">
                <p class="item-name">{{ $purchase->item->name }}</p>
                <p class="item-price">¥{{ number_format($purchase->item->price) }}</p>
            </div>
        </div>
        <div class="chat-messages">
            @foreach($messages as $message)
            <div class="message {{ $message->user_id === auth()->id() ? 'me' : 'other' }}">
                <div class="message-header">
                    <div class="avatar">
                        <img src="
                        @if(!empty($message->user->profile) &&  $message->user->profile->image) {{ asset('storage/' . $message->user->profile->image) }}
                        @else
                            {{ asset('images/avatar-placeholder.png') }}
                        @endif
                            " alt="avatar">
                    </div>
                    <span class="username">{{ $message->user->name }}</span>
                </div>
                <div class="bubble">
                    {{ $message->body }}
                    @if($message->image)
                    <img src="{{ asset('storage/' . $message->image) }}" alt="">
                    @endif

                </div>
                @if($message->user_id === auth()->id())
                <div class="message-actions">
                    <button class="edit-btn" data-id="{{ $message->id }}" data-body="{{ $message->body }}">編集</button>
                    <form method="POST" action="{{ route('chat.destroy', $message->id) }}" onsubmit="return confirm('削除しますか？')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="delete-btn">削除</button>
                    </form>
                    <div id="editModal" class="edit-modal" style="display:none;">
                        <form method="POST" id="editForm">
                            @csrf
                            @method('PUT')

                            <textarea name="body" id="editBody" maxlength="400"></textarea>
                            <div class="modal-actions">
                                <button type="submit">保存</button>
                                <button type="button" onclick="closeModal()">キャンセル</button>
                            </div>
                        </form>
                    </div>
                </div>
                @endif
                <span class="time">{{ $message->created_at->format('H:i') }}</span>
            </div>
            @endforeach
        </div>

        @if ($errors->any())
        <div class="error-box">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form class="chat-form" method="POST" action="{{ route('chat.store', $purchase->id) }}" enctype="multipart/form-data">
            @csrf
            <input type="text" name="content" id="chat-content" placeholder="取引メッセージを記入してください">
            <label class="image-btn">画像を追加
                <input type="file" name="image" hidden>
            </label>
            <button type="submit" class="send-btn">
                <img src="{{ asset('storage/profiles/send.jpg') }}" alt="送信">
            </button>
        </form>
    </main>
</div>

<script>
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.getElementById('editModal').style.display = 'flex';
            document.getElementById('editBody').value = btn.dataset.body;
            document.getElementById('editForm').action =
                `/chat/message/${btn.dataset.id}`;
        });
    });

    function closeModal() {
        document.getElementById('editModal').style.display = 'none';
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const input = document.getElementById('chat-content');
        const storageKey = 'chat_draft_{{ $purchase->id }}';

        if (localStorage.getItem(storageKey)) {
            input.value = localStorage.getItem(storageKey);
        }

        input.addEventListener('input', () => {
            localStorage.setItem(storageKey, input.value);
        });

        const form = input.closest('form');
        form.addEventListener('submit', () => {
            localStorage.removeItem(storageKey);
        });
    });
</script>

@if(
$isBuyer && in_array($purchase->status, ['trading', 'completed']) && !$purchase->review
)

<div id="reviewModal" class="modal hidden">
    <form method="POST" action="{{ route('review.store', $purchase) }}">
        @csrf
        <div class="container">
            <p class="complete">取引が完了しました。</p>
            <p class="review">今回の取引相手はどうでしたか？</p>
            <div class="star-rating">
                @for($i=5; $i>=1; $i--)
                <input type="radio" name="rating" id="star{{ $i }}" value="{{ $i}}">
                <label for="star{{ $i }}">
                    ★
                </label>
                @endfor
            </div>
            <button class="send" type="submit">送信する</button>
        </div>
    </form>
</div>
@endif
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const btn = document.getElementById('completeBtn');
        const modal = document.getElementById('reviewModal');

        if (!btn || !modal) return;

        btn.addEventListener('click', () => {
            modal.classList.remove('hidden');
            modal.classList.add('show')
        });
    });
</script>
@endsection