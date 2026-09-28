(function () {
  'use strict';
  document.addEventListener('click', function (event) {
    var button = event.target.closest('[data-public-disclosure]');
    if (!button) return;
    var hero = button.closest('.tnet-profile-public__hero');
    if (!hero) return;
    var willOpen = button.getAttribute('aria-expanded') !== 'true';
    hero.querySelectorAll('[data-public-disclosure]').forEach(function (control) {
      var panel = document.getElementById(control.getAttribute('aria-controls'));
      var open = control === button && willOpen;
      control.setAttribute('aria-expanded', open ? 'true' : 'false');
      control.setAttribute('aria-label', control.getAttribute(open ? 'data-label-open' : 'data-label-closed'));
      if (panel) panel.hidden = !open;
    });
  });
})();
