@php /** @var \App\Models\BlogPost $item */ @endphp
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                {{-- Відображення статусу публікації --}}
                @if ($item->is_published)
                    Опубліковано
                @else
                    Чернетка
                @endif
            </div>
            <div class="card-body">
                <div class="card-title"></div>
                <div class="card-subtitle mb-2 text-muted"></div>
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#maindata" role="tab">Основні дані</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#adddata" role="tab">Додаткові дані</a>
                    </li>
                </ul>
                <br>
                <div class="tab-content">
                    <div class="tab-pane active" id="maindata" role="tabpanel">
                        <div class="form-group">
                            <label for="title">Заголовок</label>
                            <input type="text" name="title" value="{{ old('title', $item->title) }}" id="title" class="form-control" minlength="3" required>
                        </div>

                        <div class="form-group">
                            <label for="content_raw">Текст статті</label>
                            <textarea name="content_raw" id="content_raw" rows="20" class="form-control">{{ old('content_raw', $item->content_raw) }}</textarea>
                        </div>
                    </div>
                    <div class="tab-pane" id="adddata" role="tabpanel">
                        <div class="form-group">
                            <label for="category_id">Категорія</label>
                            <select name="category_id" placeholder="Оберіть категорію" id="category_id" class="form-control" required>
                                @foreach ($categoryList as $categoryOption)
                                    <option value="{{ $categoryOption->id }}"
                                            @if($categoryOption->id == $item->category_id) selected @endif>
                                        {{ $categoryOption->id_title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="slug">Псевдонім</label>
                            <input type="text" name="slug" value="{{ old('slug', $item->slug) }}" id="slug" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="excerpt">Короткий текст</label>
                            <textarea name="excerpt" id="excerpt" rows="3" class="form-control">{{ old('excerpt', $item->excerpt) }}</textarea>
                        </div>
                        <div class="form-check">
                            {{-- Приховане поле, щоб гарантувати відправку 0, якщо чекбокс не позначений --}}
                            <input type="hidden" name="is_published" value="0">
                            <input type="checkbox" name="is_published" value="1" class="form-check-input" @if($item->is_published) checked="checked"@endif>
                            <label for="is_published" class="form-check-label">Опубліковано</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
