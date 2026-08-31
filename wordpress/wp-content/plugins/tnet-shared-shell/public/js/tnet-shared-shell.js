(function () {
  'use strict';

  var root = document.querySelector('[data-tnet-shared-shell]');
  if (!root) return;

  function closeOthers(current) {
    root.querySelectorAll('[data-tnet-shell-disclosure][aria-expanded="true"]').forEach(function (button) {
      if (button !== current) {
        button.setAttribute('aria-expanded', 'false');
        var panel = document.getElementById(button.getAttribute('aria-controls'));
        if (panel) panel.hidden = true;
      }
    });
  }

  root.querySelectorAll('[data-tnet-shell-disclosure]').forEach(function (button) {
    var panel = document.getElementById(button.getAttribute('aria-controls'));
    if (!panel) return;
    button.addEventListener('click', function () {
      var opening = button.getAttribute('aria-expanded') !== 'true';
      if (opening) closeOthers(button);
      button.setAttribute('aria-expanded', opening ? 'true' : 'false');
      panel.hidden = !opening;
    });
    button.parentElement.addEventListener('keydown', function (event) {
      if (event.key === 'Escape' && button.getAttribute('aria-expanded') === 'true') {
        event.preventDefault();
        button.setAttribute('aria-expanded', 'false');
        panel.hidden = true;
        button.focus();
      }
    });
    document.addEventListener('click', function (event) {
      if (button.getAttribute('aria-expanded') === 'true' && !button.parentElement.contains(event.target)) {
        button.setAttribute('aria-expanded', 'false');
        panel.hidden = true;
      }
    });
  });

  root.querySelectorAll('[data-tnet-shell-flow-menu]').forEach(function (menu) {
    menu.querySelectorAll('[data-tnet-shell-flow-switch]').forEach(function (button) {
      button.addEventListener('click', function () {
        var selected = button.getAttribute('data-tnet-shell-flow-switch');
        menu.querySelectorAll('[data-tnet-shell-flow-panel]').forEach(function (panel) {
          panel.hidden = panel.getAttribute('data-tnet-shell-flow-panel') !== selected;
        });
        menu.querySelectorAll('[data-tnet-shell-flow-switch]').forEach(function (candidate) {
          candidate.setAttribute('aria-pressed', candidate === button ? 'true' : 'false');
        });
      });
    });
  });

  root.querySelectorAll('[data-tnet-shell-nested-disclosure]').forEach(function (button) {
    var panel = document.getElementById(button.getAttribute('aria-controls'));
    if (!panel) return;
    button.addEventListener('click', function (event) {
      event.stopPropagation();
      var opening = button.getAttribute('aria-expanded') !== 'true';
      button.setAttribute('aria-expanded', opening ? 'true' : 'false');
      panel.hidden = !opening;
    });
    button.parentElement.addEventListener('keydown', function (event) {
      if (event.key === 'Escape' && button.getAttribute('aria-expanded') === 'true') {
        event.preventDefault();
        event.stopPropagation();
        button.setAttribute('aria-expanded', 'false');
        panel.hidden = true;
        button.focus();
      }
    });
  });

  var compactToggle = root.querySelector('[data-tnet-shell-compact-toggle]');
  var compactPanel = root.querySelector('[data-tnet-shell-compact-panel]');
  if (compactToggle && compactPanel) {
    compactToggle.addEventListener('click', function () {
      var opening = compactToggle.getAttribute('aria-expanded') !== 'true';
      compactToggle.setAttribute('aria-expanded', opening ? 'true' : 'false');
      compactPanel.hidden = !opening;
      if (opening) compactPanel.querySelector('a')?.focus();
    });
    compactToggle.addEventListener('keydown', function (event) {
      if (event.key === 'Escape' && compactToggle.getAttribute('aria-expanded') === 'true') {
        compactToggle.setAttribute('aria-expanded', 'false');
        compactPanel.hidden = true;
        compactToggle.focus();
      }
    });
  }

  root.querySelectorAll('[data-tnet-shell-compact-disclosure]').forEach(function (button) {
    var panel = document.getElementById(button.getAttribute('aria-controls'));
    if (!panel) return;
    button.addEventListener('click', function () {
      var opening = button.getAttribute('aria-expanded') !== 'true';
      button.setAttribute('aria-expanded', opening ? 'true' : 'false');
      panel.hidden = !opening;
    });
  });

  root.dispatchEvent(new CustomEvent('tnet:shared-shell-ready', {
    detail: window.TNetSharedShellConfig || { contractVersion: 'unknown' }
  }));
}());
