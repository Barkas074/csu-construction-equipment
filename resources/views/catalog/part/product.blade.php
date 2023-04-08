<div class="col-md-6 mb-4">
    <div class="card list-item">
        <div class="card-header">
            <h3 class="mb-0">{{ $product->name }}</h3>
        </div>
        @if ($product->image)
            @php $url = url('storage/catalog/product/thumb/' . $product->image) @endphp
            <img src="{{ $url }}" class="img-fluid" alt="">
        @else
            <img src="https://via.placeholder.com/300x150" class="img-fluid" alt="">
        @endif
        <div class="card-footer">
            <form action="{{ route('basket.add', ['id' => $product->id]) }}"
                  method="post" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-success">Добавить в корзину</button>
            </form>
            <a href="{{ route('catalog.product', ['slug' => $product->slug]) }}"
               class="btn btn-dark float-right">Перейти к товару</a>
        </div>
    </div>
</div>