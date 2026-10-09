<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Комментарии</title>
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>
        html { overflow-y: scroll; }
        #comments-list { min-height: 360px; }
    </style>
</head>
<body>
<div class="container py-4">
    <h1 class="mb-4">Комментарии</h1>

    <div class="form-row mb-3">
        <div class="form-group col-6 col-md-3">
            <label for="sort-field">Сортировать по</label>
            <select id="sort-field" class="form-control">
                <option value="id">ID</option>
                <option value="date">Дате</option>
            </select>
        </div>
        <div class="form-group col-6 col-md-3">
            <label for="sort-dir">Направление</label>
            <select id="sort-dir" class="form-control">
                <option value="desc">По убыванию</option>
                <option value="asc">По возрастанию</option>
            </select>
        </div>
    </div>

    <div id="comments-list" class="mb-3"></div>

    <nav aria-label="Страницы">
        <ul id="pagination" class="pagination flex-wrap"></ul>
    </nav>

    <hr>
    <h2 class="h4 mb-3">Добавить комментарий</h2>
    <form id="comment-form" novalidate>
        <div class="form-group">
            <label for="field-name">Почта</label>
            <input type="email" id="field-name" class="form-control" required>
            <div id="error-name" class="invalid-feedback"></div>
        </div>
        <div class="form-group">
            <label for="field-text">Текст</label>
            <textarea id="field-text" rows="3" class="form-control" required></textarea>
            <div id="error-text" class="invalid-feedback"></div>
        </div>
        <div class="form-group">
            <label for="field-date">Дата</label>
            <input type="datetime-local" id="field-date" class="form-control" required>
            <div id="error-date" class="invalid-feedback"></div>
        </div>
        <button type="submit" id="submit-btn" class="btn btn-primary">Отправить</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="/js/comments.js"></script>
</body>
</html>
