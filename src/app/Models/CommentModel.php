<?php

namespace App\Models;

use CodeIgniter\Model;

class CommentModel extends Model
{
    protected $table         = 'comments';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['name', 'text', 'date'];

    protected $useTimestamps = false;

    protected $validationRules = [
        'name' => 'required|valid_email|max_length[255]',
        'text' => 'required|min_length[1]|max_length[2000]',
        'date' => 'required|regex_match[/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}$/]',
    ];

    protected $validationMessages = [
        'name' => [
            'required'    => 'Укажите почту',
            'valid_email' => 'Некорректный email',
            'max_length'  => 'Почта слишком длинная',
        ],
        'text' => [
            'required'   => 'Введите текст комментария',
            'min_length' => 'Текст комментария пустой',
            'max_length' => 'Текст комментария слишком длинный',
        ],
        'date' => [
            'required'    => 'Выберите дату',
            'regex_match' => 'Неверный формат даты',
        ],
    ];
}
