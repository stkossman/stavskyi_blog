@extends('layouts.main')

@section('content')
    @php /** @var \App\Models\BlogPost $item */ @endphp
    <div class="container">
        {{-- Включаємо шаблон для відображення повідомлень про успіх/помилки --}}
        @include('blog.admin.posts.includes.result_messages')

        @if ($item->exists)
            {{-- Форма для оновлення існуючого посту --}}
            <form method="POST" action="{{ route('blog.admin.posts.update', $item->id) }}">
                @method('PATCH')
                @else
                    {{-- Форма для створення нового посту --}}
                    <form method="POST" action="{{ route('blog.admin.posts.store') }}">
                        @endif
                        @csrf {{-- CSRF-токен для захисту форми --}}
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                {{-- Включаємо основну частину форми --}}
                                @include('blog.admin.posts.includes.post_edit_main_col')
                            </div>
                            <div class="col-md-3">
                                {{-- Включаємо додаткову частину форми (бічну колонку) --}}
                                @include('blog.admin.posts.includes.post_edit_add_col')
                            </div>
                        </div>
                    </form>

                    @if ($item->exists)
                        <br>
                        {{-- Форма для видалення посту (відображається лише для існуючих постів) --}}
                        <form method="POST" action="{{ route('blog.admin.posts.destroy', $item->id) }}">
                            @method('DELETE') {{-- Метод DELETE для видалення --}}
                            @csrf
                            <div class="row justify-content-center">
                                <div class="col-md-8">
                                    <div class="card card-block">
                                        <div class="card-body ml-auto">
                                            <button type="submit" class="btn btn-link">Видалити</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3"></div>
                            </div>
                        </form>
        @endif
    </div>
@endsection
