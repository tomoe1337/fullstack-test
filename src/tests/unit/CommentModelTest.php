<?php

use App\Models\CommentModel;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class CommentModelTest extends CIUnitTestCase
{
    public function testValidRowPasses()
    {
        $model = new CommentModel();

        $this->assertTrue($model->validate([
            'name' => 'user@example.com',
            'text' => 'Нормальный комментарий',
            'date' => '2026-01-10 12:00',
        ]));
    }

    /**
     * @dataProvider invalidRows
     */
    public function testInvalidRowRejected(array $row, string $field)
    {
        $model = new CommentModel();

        $this->assertFalse($model->validate($row));
        $this->assertArrayHasKey($field, $model->errors());
    }

    public function invalidRows(): array
    {
        return [
            'битый email'        => [['name' => 'asd@', 'text' => 'ok', 'date' => '2026-01-10 12:00'], 'name'],
            'пустой текст'       => [['name' => 'a@b.cc', 'text' => '', 'date' => '2026-01-10 12:00'], 'text'],
            'кривой формат даты' => [['name' => 'a@b.cc', 'text' => 'ok', 'date' => 'вчера'], 'date'],
        ];
    }
}
