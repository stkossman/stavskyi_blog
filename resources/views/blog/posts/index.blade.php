@extends('layouts.main')

@section('content')
    <div class="container">
        <h1>Список блог-постів</h1>
        <table class="table table-bordered table-striped mt-3">
            <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Заголовок</th>
                <th>Дата створення</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($items as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->title }}</td>
                    <td>{{ $item->created_at }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
