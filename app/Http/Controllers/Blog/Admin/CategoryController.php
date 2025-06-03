<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Models\BlogCategory;
use Illuminate\Support\Str;
use App\Http\Requests\BlogCategoryUpdateRequest;
use App\Http\Requests\BlogCategoryCreateRequest;
use App\Repositories\BlogCategoryRepository;

class CategoryController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    private $blogCategoryRepository;

    public function __construct()
    {
        parent::__construct(); // Викликаємо конструктор батьківського класу (BaseController)
        $this->blogCategoryRepository = app(BlogCategoryRepository::class); // Ініціалізуємо репозиторій
    }
    public function index()
    {
        $paginator = $this->blogCategoryRepository->getAllWithPaginate(5);

        return view('blog.admin.categories.index', compact('paginator'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $item = new BlogCategory();
        $categoryList = $this->blogCategoryRepository->getForComboBox();

        return view('blog.admin.categories.edit', compact('item', 'categoryList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BlogCategoryCreateRequest $request)
    {
        $data = $request->input();

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        $item = (new BlogCategory())->create($data);

        if ($item) {
            return redirect()
                ->route('blog.admin.categories.edit', [$item->id])
                ->with(['success' => 'Успішно збережено']);
        } else {
            return back()
                ->withErrors(['msg' => 'Помилка збереження'])
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $item = $this->blogCategoryRepository->getEdit($id); // Отримуємо категорію через репозиторій
        if (empty($item)) { //помилка, якщо репозиторій не знайде наш ід
            abort(404); // Викидаємо 404 помилку, якщо категорію не знайдено
        }
        $categoryList = $this->blogCategoryRepository->getForComboBox($item->parent_id); // Отримуємо список для комбобоксу

        return view('blog.admin.categories.edit', compact('item', 'categoryList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BlogCategoryUpdateRequest $request, $id)
    {
        $item = $this->blogCategoryRepository->getEdit($id);
        if (empty($item)) {
            return back()
            ->withErrors(['msg' => "Запис id=[{$id}] не знайдено"])
            ->withInput();
        }

        $data = $request->all();
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        $result = $item->update($data);

        if ($result) {
            return redirect()
                ->route('blog.admin.categories.edit', $item->id)
                ->with(['success' => 'Успішно збережено']);
        } else {
            return back()
                ->with(['msg' => 'Помилка збереження'])
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
