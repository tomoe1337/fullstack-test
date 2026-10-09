<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CommentSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['name' => 'anna@example.com', 'text' => 'Первый комментарий, проверяем пагинацию.', 'date' => '2026-01-10 12:00'],
            ['name' => 'boris@example.com', 'text' => 'Второй — сортировка по дате должна его двигать.', 'date' => '2026-03-15 09:30'],
            ['name' => 'cara@example.com', 'text' => 'Третий комментарий для первой страницы.', 'date' => '2026-02-01 18:45'],
            ['name' => 'den@example.com', 'text' => 'Четвёртый — уже вторая страница при perPage=3.', 'date' => '2026-05-20 10:00'],
            ['name' => 'eva@example.com', 'text' => 'Пятый комментарий.', 'date' => '2026-04-11 14:15'],
            ['name' => 'fred@example.com', 'text' => 'Шестой комментарий.', 'date' => '2026-06-01 08:00'],
            ['name' => 'gina@example.com', 'text' => 'Седьмой — третья страница.', 'date' => '2026-07-07 20:20'],
            ['name' => 'hank@example.com', 'text' => 'Восьмой комментарий.', 'date' => '2026-08-08 11:11'],
            ['name' => 'ira@example.com', 'text' => 'Девятый — ровно 3 страницы по 3.', 'date' => '2026-09-09 09:09'],
        ];
        $this->db->table('comments')->insertBatch($data);
    }
}
