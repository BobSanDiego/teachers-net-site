(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {
    var notice = document.querySelector('.tnet-profile-self__notice[role="status"]');
    if (notice) window.setTimeout(function () { notice.remove(); }, 5000);
    var dialogs = Array.prototype.slice.call(document.querySelectorAll('[data-profile-editor-dialog]'));
    if (!dialogs.length) return;
    var active = null;
    var opener = null;
    var initial = '';

    function snapshot(form) {
      return Array.from(new FormData(form).entries(), function (pair) {
        return pair[0] + '=' + pair[1];
      }).filter(function (entry) { return entry.indexOf('_wpnonce=') !== 0; }).sort().join('&');
    }

    function open(dialog, trigger) {
      if (!dialog || active) return;
      active = dialog;
      opener = trigger || null;
      initial = snapshot(dialog.querySelector('form'));
      dialog.showModal();
      var first = dialog.querySelector('textarea, select, input:not([type="hidden"])');
      if (first) first.focus();
    }

    function close() {
      if (!active) return;
      var form = active.querySelector('form');
      if (snapshot(form) !== initial) {
        if (!window.confirm('Discard your unsaved changes?')) return;
        // Rebuild governed pickers and the location/Grade controls from saved
        // data rather than trying to reset their internal selection state.
        window.location.reload();
        return;
      }
      active.close();
      active = null;
      if (opener) opener.focus();
      opener = null;
    }

    Array.prototype.forEach.call(document.querySelectorAll('[data-profile-editor-open]'), function (button) {
      button.addEventListener('click', function () {
        var section = button.getAttribute('data-profile-editor-open');
        open(dialogs.find(function (dialog) { return dialog.getAttribute('data-profile-editor-dialog') === section; }), button);
      });
    });
    dialogs.forEach(function (dialog) {
      Array.prototype.forEach.call(dialog.querySelectorAll('[data-profile-editor-close]'), function (button) {
        button.addEventListener('click', close);
      });
      dialog.addEventListener('cancel', function (event) {
        event.preventDefault();
        close();
      });
      dialog.addEventListener('click', function (event) {
        if (event.target === dialog) close();
      });
    });
    var errored = dialogs.find(function (dialog) { return dialog.hasAttribute('data-profile-editor-open-on-load'); });
    if (errored) open(errored, document.querySelector('[data-profile-editor-open="' + errored.getAttribute('data-profile-editor-dialog') + '"]'));
  });
}());
