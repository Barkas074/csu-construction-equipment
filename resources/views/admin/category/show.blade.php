@extends('layout.admin', ['title' => 'Просмотр категории'])

@section('content')
    <h1>Просмотр категории</h1>
    <div class="row">
        <div class="col-md-6">
            <p><strong>Название:</strong> {{ $category->name }}</p>
            <p><strong>ЧПУ (англ):</strong> {{ $category->slug }}</p>
            <p><strong>Краткое описание</strong></p>
            @isset($category->content)
                <p>{{ $category->content }}</p>
            @else
                <p>Описание отсутствует</p>
            @endisset
        </div>
        <div class="col-md-6">
            @php
                use Illuminate\Support\Facades\Storage;
                    if ($category->image) {
                        $url = Storage::disk('local')->url('catalog/category/image/' . $category->image);
                    } else {
                        $url = Storage::disk('local')->url('catalog/category/image/default.jpg');
                    }
            @endphp
            <img src="{{ $url }}" alt="" class="img-fluid">
        </div>
    </div>
    @if ($category->children)
        <p><strong>Дочерние категории</strong></p>
    @else
        <p>Нет дочерних категорий</p>
    @endif
    <a href="{{ route('admin.category.edit', ['category' => $category->id]) }}"
       class="btn btn-success">
        Редактировать категорию
    </a>
    <form method="post" class="d-inline"
          action="{{ route('admin.category.destroy', ['category' => $category->id]) }}">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">
            Удалить категорию
        </button>
    </form>
@endsection