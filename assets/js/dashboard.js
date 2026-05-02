/* global linkdigestDash */

document.addEventListener('DOMContentLoaded', function() {
    if (typeof postboxes !== 'undefined') {
        postboxes.add_postbox_toggles(pagenow);
    }

    document.querySelectorAll('.lb-date-time').forEach(function(element) {
        var timestamp = Number.parseInt(element.dataset.timestamp);
        if (!timestamp) return;
        var date = new Date(timestamp * 1000);
        element.textContent = date.toLocaleString(navigator.language, {
            year: 'numeric', month: 'short', day: 'numeric',
            hour: 'numeric', minute: '2-digit', hour12: true
        });
    });
});

document.addEventListener('click', async function(e) {
    if (e.target.closest('.lb-delete-cancel')) {
        var li = e.target.closest('li');
        li.querySelector('.lb-delete-confirm-row').remove();
        li.querySelector('.lb-delete-btn').style.display = '';
        return;
    }

    if (e.target.closest('.lb-delete-confirm-yes')) {
        var btn = e.target.closest('.lb-delete-confirm-yes');
        var li = btn.closest('li');
        btn.disabled = true;
        btn.textContent = '...';
        try {
            var res = await fetch(linkdigestDash.restUrl + li.dataset.linkId, {
                method: 'DELETE',
                credentials: 'same-origin',
                headers: { 'X-WP-Nonce': linkdigestDash.nonce }
            });
            if (res.ok || res.status === 204) {
                li.remove();
                ['lb-stat-total', 'lb-stat-unpublished'].forEach(function(id) {
                    var el = document.getElementById(id);
                    if (el) { el.textContent = Math.max(0, parseInt(el.textContent.replace(/,/g, ''), 10) - 1).toLocaleString(); }
                });
            } else {
                li.querySelector('.lb-delete-confirm-row').remove();
                li.querySelector('.lb-delete-btn').style.display = '';
            }
        } catch (err) {
            li.querySelector('.lb-delete-confirm-row').remove();
            li.querySelector('.lb-delete-btn').style.display = '';
        }
        return;
    }

    var btn = e.target.closest('.lb-delete-btn');
    if (!btn) return;
    var li = btn.closest('li');
    if (li.querySelector('.lb-delete-confirm-row')) return;
    btn.style.display = 'none';
    var row = document.createElement('div');
    row.className = 'lb-delete-confirm-row';
    var lbl = document.createElement('span');
    lbl.className = 'lb-delete-confirm-label';
    lbl.textContent = linkdigestDash.labels.delete;
    var yes = document.createElement('button');
    yes.className = 'lb-delete-confirm-yes';
    yes.textContent = linkdigestDash.labels.yes;
    var no = document.createElement('button');
    no.className = 'lb-delete-cancel';
    no.textContent = linkdigestDash.labels.cancel;
    row.append(lbl, yes, no);
    btn.parentElement.appendChild(row);
});
