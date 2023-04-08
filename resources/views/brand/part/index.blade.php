<div class="col-md-6 mb-4">
    <div class="card">
        <div class="card-header">
            <h3>{{ $brand->name }}</h3>
        </div>
        <div class="card-body p-0">
            @if ($brand->image)
                @php $url = url('storage/catalog/brand/thumb/' . $brand->image) @endphp
                <img src="{{ $url }}" class="img-fluid" alt="">
            @else
                <img src="https://via.placeholder.com/300x150" class="img-fluid" alt="">
            @endif
        </div>
        <div class="card-footer">
            <a href="{{ route('catalog.brand', ['slug' => $brand->slug]) }}"
               class="btn btn-dark">Перейти к бренду</a>
        </div>
    </div>
</div>