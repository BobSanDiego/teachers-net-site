(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {
    var notice = document.querySelector('.tnet-profile-self__notice[role="status"]');
    if (notice) window.setTimeout(function () { notice.remove(); }, 5000);
    var menuTriggers = Array.prototype.slice.call(document.querySelectorAll('.tnet-profile-self__menu-trigger, .tnet-profile-self__card-menu-trigger'));
    function menuFor(trigger) {
      return trigger && document.getElementById(trigger.getAttribute('aria-controls'));
    }
    function closeProfileMenu(trigger, restoreFocus) {
      var menu = menuFor(trigger);
      if (!menu || menu.hidden) return;
      menu.hidden = true;
      trigger.setAttribute('aria-expanded', 'false');
      if (trigger.parentElement) trigger.parentElement.classList.remove('is-open');
      if (restoreFocus) trigger.focus();
    }
    function closeOtherMenus(keepTrigger) {
      menuTriggers.forEach(function (trigger) {
        if (trigger !== keepTrigger) closeProfileMenu(trigger, false);
      });
    }
    menuTriggers.forEach(function (menuTrigger) {
      var profileMenu = menuFor(menuTrigger);
      if (!profileMenu) return;
      menuTrigger.addEventListener('click', function () {
        var opening = profileMenu.hidden;
        closeOtherMenus(menuTrigger);
        profileMenu.hidden = !opening;
        menuTrigger.setAttribute('aria-expanded', opening ? 'true' : 'false');
        if (menuTrigger.parentElement) menuTrigger.parentElement.classList.toggle('is-open', opening);
        if (opening) {
          var firstItem = profileMenu.querySelector('[role="menuitem"]');
          if (firstItem) firstItem.focus();
        }
      });
      profileMenu.addEventListener('keydown', function (event) {
        var items = Array.prototype.slice.call(profileMenu.querySelectorAll('[role="menuitem"]'));
        var index = items.indexOf(document.activeElement);
        if (event.key === 'Escape') {
          event.preventDefault();
          closeProfileMenu(menuTrigger, true);
        } else if (items.length && (event.key === 'ArrowDown' || event.key === 'ArrowUp')) {
          event.preventDefault();
          var offset = event.key === 'ArrowDown' ? 1 : -1;
          items[(index + offset + items.length) % items.length].focus();
        }
      });
      menuTrigger.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && !profileMenu.hidden) {
          event.preventDefault();
          closeProfileMenu(menuTrigger, true);
        }
      });
    });
    document.addEventListener('pointerdown', function (event) {
      menuTriggers.forEach(function (trigger) {
        var menu = menuFor(trigger);
        if (menu && !menu.hidden && !menu.contains(event.target) && !trigger.contains(event.target)) closeProfileMenu(trigger, false);
      });
    });
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
        var ownerMenu = button.closest('[data-profile-card-menu]');
        var trigger = ownerMenu && ownerMenu.querySelector('.tnet-profile-self__card-menu-trigger');
        if (ownerMenu) closeProfileMenu(trigger, false);
        open(dialogs.find(function (dialog) { return dialog.getAttribute('data-profile-editor-dialog') === section; }), trigger || button);
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
    var requested = dialogs.find(function (dialog) { return dialog.hasAttribute('data-profile-editor-open-on-load'); });
    if (requested) {
      var section = requested.getAttribute('data-profile-editor-dialog');
      var initialTrigger = document.querySelector('.tnet-profile-self__card-menu-trigger[data-profile-editor-section="' + section + '"]');
      open(requested, initialTrigger || document.querySelector('[data-profile-editor-open="' + section + '"]'));
    }
  });
}());
