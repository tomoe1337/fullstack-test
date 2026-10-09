const state = { page: 1, sort: 'id', dir: 'desc' };
let loading = false;

function csrfToken() {
  return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
}

$.ajaxSetup({ beforeSend: (xhr) => xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken()) });

function escapeHtml(s) {
  return String(s ?? '').replace(/[&<>"']/g, (c) => ({
    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;',
  }[c]));
}

function loadComments() {
  if (loading) return;
  loading = true;
  if (!$('#comments-list').children().length) {
    $('#comments-list').html('<div class="text-muted">Загрузка…</div>');
  }
  $.getJSON('/comments/list', state)
    .done((res) => { renderList(res.data); renderPagination(res.pager); })
    .fail(() => $('#comments-list').html('<div class="alert alert-danger">Не удалось загрузить комментарии</div>'))
    .always(() => { loading = false; });
}

function renderList(rows) {
  if (!rows.length) {
    $('#comments-list').html('<div class="alert alert-info">Комментариев пока нет</div>');
    return;
  }
  $('#comments-list').html(rows.map((c) => `
    <div class="card mb-2" data-id="${c.id}">
      <div class="card-body py-2">
        <div class="d-flex justify-content-between flex-wrap">
          <strong>#${c.id} ${escapeHtml(c.name)}</strong>
          <small class="text-muted">${escapeHtml(c.date)}</small>
        </div>
        <p class="mb-1">${escapeHtml(c.text)}</p>
        <button class="btn btn-sm btn-outline-danger btn-delete">Удалить</button>
      </div>
    </div>`).join(''));
}

function renderPagination(pager) {
  const ul = $('#pagination').empty();
  const item = (label, page, cls = '') =>
    `<li class="page-item ${cls}"><a class="page-link" href="#" data-page="${page}">${label}</a></li>`;
  ul.append(item('&laquo;', Math.max(1, pager.page - 1), pager.page <= 1 ? 'disabled' : ''));
  for (let p = 1; p <= pager.pages; p++) ul.append(item(p, p, p === pager.page ? 'active' : ''));
  ul.append(item('&raquo;', Math.min(pager.pages, pager.page + 1), pager.page >= pager.pages ? 'disabled' : ''));
}

function showErrors(errors) {
  for (const f of ['name', 'text', 'date']) {
    $(`#field-${f}`).toggleClass('is-invalid', !!errors[f]);
    $(`#error-${f}`).text(errors[f] || '');
  }
}

function validEmail(v) { return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v); }

function setDefaultDate() {
  const now = new Date();
  now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
  $('#field-date').val(now.toISOString().slice(0, 16));
}

$(() => {
  $('#sort-field, #sort-dir').on('change', () => {
    state.sort = $('#sort-field').val();
    state.dir = $('#sort-dir').val();
    state.page = 1;
    loadComments();
  });

  $('#pagination').on('click', 'a', (e) => {
    e.preventDefault();
    if ($(e.currentTarget).closest('li').hasClass('disabled')) return;
    state.page = parseInt($(e.currentTarget).data('page'), 10);
    loadComments();
  });

  $('#comment-form').on('submit', (e) => {
    e.preventDefault();
    const errors = {};
    if (!validEmail($('#field-name').val().trim())) errors.name = 'Некорректный email';
    if (!$('#field-text').val().trim()) errors.text = 'Введите текст комментария';
    if (!$('#field-date').val()) errors.date = 'Выберите дату';
    showErrors(errors);
    if (Object.keys(errors).length) return;

    $('#submit-btn').prop('disabled', true);
    $.post('/comments/create', {
      name: $('#field-name').val().trim(),
      text: $('#field-text').val().trim(),
      date: $('#field-date').val().replace('T', ' '),
    })
      .done(() => {
        $('#comment-form')[0].reset();
        setDefaultDate();
        showErrors({});
        state.page = 1;
        loadComments();
      })
      .fail((xhr) => showErrors((xhr.responseJSON && xhr.responseJSON.errors) || {}))
      .always(() => $('#submit-btn').prop('disabled', false));
  });

  $('#comments-list').on('click', '.btn-delete', (e) => {
    const card = $(e.currentTarget).closest('.card');
    if (!confirm('Точно удалить комментарий?')) return;
    $.post(`/comments/delete/${card.data('id')}`)
      .done(() => {
        if ($('#comments-list .card').length === 1 && state.page > 1) state.page--;
        loadComments();
      })
      .fail(() => alert('Не удалось удалить комментарий'));
  });

  setDefaultDate();
  loadComments();
});
