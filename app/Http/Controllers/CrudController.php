<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

abstract class CrudController extends Controller
{
    abstract protected function modelClass(): string;

    abstract protected function rules(?int $id = null): array;

    protected function prepareData(array $data): array
    {
        return $data;
    }

    public function index(Request $request)
    {
        $model = $this->modelClass();

        return response()->json(
            $model::query()->paginate(10)
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());

        $model = $this->modelClass();

        $item = $model::create($this->prepareData($data));

        return response()->json([
            'message' => 'Data berhasil ditambahkan.',
            'data' => $item,
        ], 201);
    }

    public function show(int $id)
    {
        $model = $this->modelClass();

        return response()->json(
            $model::query()->findOrFail($id)
        );
    }

    public function update(Request $request, int $id)
    {
        $model = $this->modelClass();

        $item = $model::query()->findOrFail($id);

        $data = $request->validate($this->rules($id));

        $item->update($this->prepareData($data));

        return response()->json([
            'message' => 'Data berhasil diperbarui.',
            'data' => $item->fresh(),
        ]);
    }

    public function destroy(int $id)
    {
        $model = $this->modelClass();

        $item = $model::query()->findOrFail($id);

        $item->delete();

        return response()->json([
            'message' => 'Data berhasil dihapus.',
        ]);
    }
}
