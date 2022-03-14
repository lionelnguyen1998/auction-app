<div class="block2-txt-child2 flex-r p-t-3">
    <form action="{{ route('likeAuction', ['auctionId' => $auction['auction_id']]) }}" method="POST" class="btn-addwish-b2 dis-block pos-relative">
        <input hidden name="abc" value="{{ auth()->user()->user_id }}" />
        <button type="submit">
            <img class="icon-heart1 dis-block trans-04" src="/template/images/icons/icon-heart-01.png" alt="ICON">
            <img class="icon-heart2 dis-block trans-04 ab-t-l" src="/template/images/icons/icon-heart-02.png" alt="ICON">
        </button>
        @csrf
    </form>
</div>