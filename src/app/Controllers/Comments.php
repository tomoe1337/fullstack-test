<?php

namespace App\Controllers;

class Comments extends BaseController
{
    private const PER_PAGE = 3;
    private const SORT_FIELDS = ['id', 'date'];
    private const SORT_DIRS = ['asc', 'desc'];

    public function index(): string
    {
        return view('comments/index');
    }

    public function list()
    {
        $page = max(1, (int) $this->request->getGet('page'));
        $sort = $this->request->getGet('sort');
        $dir  = strtolower((string) $this->request->getGet('dir'));

        if (! in_array($sort, self::SORT_FIELDS, true)) {
            $sort = 'id';
        }
        if (! in_array($dir, self::SORT_DIRS, true)) {
            $dir = 'desc';
        }

        $model = model('CommentModel');
        $total = $model->countAllResults();
        $pages = (int) ceil($total / self::PER_PAGE);
        $rows  = $model->orderBy($sort, $dir)->findAll(self::PER_PAGE, ($page - 1) * self::PER_PAGE);

        return $this->response->setJSON([
            'data'  => $rows,
            'pager' => ['page' => $page, 'perPage' => self::PER_PAGE, 'total' => $total, 'pages' => $pages, 'sort' => $sort, 'dir' => $dir],
        ]);
    }

    public function create()
    {
        $model = model('CommentModel');

        $data = [
            'name' => trim((string) $this->request->getPost('name')),
            'text' => trim((string) $this->request->getPost('text')),
            'date' => str_replace('T', ' ', trim((string) $this->request->getPost('date'))),
        ];

        if (! $model->insert($data)) {
            return $this->response->setStatusCode(422)->setJSON(['errors' => $model->errors()]);
        }

        return $this->response->setStatusCode(201)->setJSON(['data' => $model->find($model->getInsertID())]);
    }

    // Удаление без авторизации — так в ТЗ, доступ есть у любого посетителя
    public function delete($id = null)
    {
        $model = model('CommentModel');

        if (! $model->find($id)) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Комментарий не найден']);
        }

        $model->delete($id);

        return $this->response->setJSON(['ok' => true]);
    }
}
