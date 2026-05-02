/* global linkdigestCats */
(function() {
    var cfg = window.linkdigestCats || {};

    // ── Delete confirmation ───────────────────────────────────────────
    document.querySelectorAll('.linkdigest-cat-delete-form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            var name  = form.dataset.name;
            var count = parseInt(form.dataset.count, 10);
            var msg   = count > 0
                ? "Delete '" + name + "'? " + count + ' ' + (count === 1 ? cfg.labels.deleteOne : cfg.labels.deleteMany)
                : "Delete '" + name + "'?";
            if (!confirm(msg)) { e.preventDefault(); }
        });
    });

    // ── Inline edit ───────────────────────────────────────────────────
    document.addEventListener('click', function(e) {
        var editBtn   = e.target.closest('.linkdigest-cat-edit-btn');
        var cancelBtn = e.target.closest('.linkdigest-cat-cancel-btn');
        var saveBtn   = e.target.closest('.linkdigest-cat-save-btn');
        if (editBtn)   { enterEdit(editBtn.closest('tr'));   return; }
        if (cancelBtn) { exitEdit(cancelBtn.closest('tr'), null); return; }
        if (saveBtn)   { saveEdit(saveBtn.closest('tr')); }
    });

    function enterEdit(tr) {
        if (tr.classList.contains('linkdigest-cat-editing')) { return; }
        tr.classList.add('linkdigest-cat-editing');

        var nameInput = mkInput('text',     tr.dataset.name,        '');
        var descInput = mkTextarea(          tr.dataset.description, cfg.labels.descPlaceholder);
        var slugInput = mkInput('text',     tr.dataset.slug,        cfg.labels.slugPlaceholder);
        slugInput.classList.add('linkdigest-cat-inline-slug');

        cell(tr, 'name').innerHTML        = '';  cell(tr, 'name').appendChild(nameInput);
        cell(tr, 'description').innerHTML = '';  cell(tr, 'description').appendChild(descInput);
        cell(tr, 'slug').innerHTML        = '';  cell(tr, 'slug').appendChild(slugInput);

        var actionsCell = tr.querySelector('.linkdigest-cat-actions');
        var deleteForm  = actionsCell.querySelector('.linkdigest-cat-delete-form');
        actionsCell.innerHTML = '';

        var saveBtn   = mkBtn(cfg.labels.save,   'button button-primary linkdigest-cat-save-btn');
        var cancelBtn = mkBtn(cfg.labels.cancel, 'button-link linkdigest-cat-cancel-btn');
        var errSpan   = document.createElement('span');
        errSpan.className = 'linkdigest-cat-inline-error';

        actionsCell.appendChild(saveBtn);
        actionsCell.appendChild(document.createTextNode(' '));
        actionsCell.appendChild(cancelBtn);
        actionsCell.appendChild(errSpan);
        if (deleteForm) {
            deleteForm.style.display = 'none';
            actionsCell.appendChild(deleteForm);
        }

        nameInput.focus();
    }

    function exitEdit(tr, updated) {
        tr.classList.remove('linkdigest-cat-editing');

        var name = updated ? updated.name        : tr.dataset.name;
        var desc = updated ? updated.description : tr.dataset.description;
        var slug = updated ? updated.slug        : tr.dataset.slug;

        if (updated) {
            tr.dataset.name        = updated.name;
            tr.dataset.description = updated.description;
            tr.dataset.slug        = updated.slug;
        }

        cell(tr, 'name').innerHTML        = '<strong>' + esc(name) + '</strong>';
        cell(tr, 'description').innerHTML = esc(desc);
        cell(tr, 'slug').innerHTML        = '<code>' + esc(slug) + '</code>';

        var actionsCell = tr.querySelector('.linkdigest-cat-actions');
        var deleteForm  = actionsCell.querySelector('.linkdigest-cat-delete-form');
        if (updated && deleteForm) { deleteForm.dataset.name = updated.name; }
        actionsCell.innerHTML = '';

        var editBtn = mkBtn(cfg.labels.edit, 'button-link linkdigest-cat-edit-btn');
        actionsCell.appendChild(editBtn);
        actionsCell.appendChild(document.createTextNode(' | '));
        if (deleteForm) {
            deleteForm.style.display = 'inline';
            actionsCell.appendChild(deleteForm);
        }
    }

    async function saveEdit(tr) {
        var saveBtn = tr.querySelector('.linkdigest-cat-save-btn');
        var errSpan = tr.querySelector('.linkdigest-cat-inline-error');
        saveBtn.disabled    = true;
        saveBtn.textContent = cfg.labels.saving;
        errSpan.textContent = '';

        var id   = parseInt(tr.dataset.id, 10);
        var name = cell(tr, 'name').querySelector('input').value.trim();
        var desc = cell(tr, 'description').querySelector('textarea').value;
        var slug = cell(tr, 'slug').querySelector('input').value.trim();

        if (!name) {
            errSpan.textContent = cfg.labels.nameRequired;
            saveBtn.disabled    = false;
            saveBtn.textContent = cfg.labels.save;
            return;
        }

        try {
            var res  = await fetch(cfg.restUrl + id, {
                method:      'POST',
                credentials: 'same-origin',
                headers:     { 'Content-Type': 'application/json', 'X-WP-Nonce': cfg.nonce },
                body:        JSON.stringify({ name: name, description: desc, slug: slug }),
            });
            var data = await res.json();
            if (!res.ok) {
                errSpan.textContent = (data && data.message) ? data.message : cfg.labels.saveError;
                saveBtn.disabled    = false;
                saveBtn.textContent = cfg.labels.save;
                return;
            }
            exitEdit(tr, data);
        } catch (_err) {
            errSpan.textContent = cfg.labels.saveError;
            saveBtn.disabled    = false;
            saveBtn.textContent = cfg.labels.save;
        }
    }

    // ── Helpers ───────────────────────────────────────────────────────
    function cell(tr, name) { return tr.querySelector('.linkdigest-cat-cell-' + name); }
    function mkInput(type, value, placeholder) {
        var el = document.createElement('input');
        el.type = type; el.value = value || ''; el.placeholder = placeholder;
        el.className = 'linkdigest-cat-inline-input';
        return el;
    }
    function mkTextarea(value, placeholder) {
        var el = document.createElement('textarea');
        el.value = value || ''; el.placeholder = placeholder; el.rows = 2;
        el.className = 'linkdigest-cat-inline-input';
        return el;
    }
    function mkBtn(label, cls) {
        var el = document.createElement('button');
        el.type = 'button'; el.textContent = label; el.className = cls;
        return el;
    }
    function esc(str) {
        return (str || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }
})();
