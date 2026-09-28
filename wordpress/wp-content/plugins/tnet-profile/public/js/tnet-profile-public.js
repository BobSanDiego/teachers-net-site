(function () {
  'use strict';

  function pack(row) {
    var content = row.querySelector('.tnet-profile-public__summary-content');
    var values = Array.prototype.slice.call(row.querySelectorAll('[data-summary-value]'));
    var button = row.querySelector('[data-public-disclosure]');
    var label = button.querySelector('[data-more-label]');
    var suppressed = Number(row.getAttribute('data-suppressed-count')) || 0;
    var hasDetails = row.getAttribute('data-has-details') === '1';
    var available = content.clientWidth;
    if (!available) return;

    values.forEach(function (value) { value.hidden = false; value.classList.toggle('is-long', value.scrollWidth > available); });
    button.hidden = false;
    var widths = values.map(function (value) { return value.getBoundingClientRect().width; });
    var linesAllowed = window.matchMedia('(max-width: 700px)').matches ? 2 : 1;
    var gap = 7;
    var fit = 0;
    for (var count = values.length; count >= 0; count--) {
      var remaining = values.length - count + suppressed;
      label.textContent = remaining ? '(' + remaining + ' more)' : 'Details';
      var needsButton = remaining > 0 || hasDetails;
      button.hidden = !needsButton;
      var buttonWidth = needsButton ? button.getBoundingClientRect().width + gap : 0;
      var line = 1;
      var occupied = 0;
      for (var index = 0; index < count; index++) {
        var width = widths[index] + (occupied ? gap : 0);
        if (occupied && occupied + width > available + 0.5) { line++; occupied = 0; }
        occupied += occupied ? width : widths[index];
      }
      if (needsButton && occupied + buttonWidth > available + 0.5) line++;
      if (line <= linesAllowed) { fit = count; break; }
    }
    values.forEach(function (value, index) { value.hidden = index >= fit; });
    var hiddenCount = values.length - fit + suppressed;
    label.textContent = hiddenCount ? '(' + hiddenCount + ' more)' : 'Details';
    button.hidden = hiddenCount === 0 && !hasDetails;
  }

  function setOpen(hero, target) {
    hero.querySelectorAll('[data-public-disclosure]').forEach(function (control) {
      var panel = document.getElementById(control.getAttribute('aria-controls'));
      var open = control === target;
      control.setAttribute('aria-expanded', open ? 'true' : 'false');
      control.setAttribute('aria-label', control.getAttribute(open ? 'data-label-open' : 'data-label-closed'));
      if (panel) panel.hidden = !open;
    });
  }

  document.addEventListener('click', function (event) {
    var control = event.target.closest('[data-public-disclosure]');
    var hide = event.target.closest('[data-public-hide]');
    if (!control && !hide) return;
    var hero = (control || hide).closest('.tnet-profile-public__hero');
    if (!hero) return;
    if (control) setOpen(hero, control.getAttribute('aria-expanded') === 'true' ? null : control);
    else setOpen(hero, null);
  });

  function repack() { document.querySelectorAll('[data-public-summary]').forEach(pack); }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', repack);
  else repack();
  if (typeof ResizeObserver !== 'undefined') {
    var observer = new ResizeObserver(repack);
    document.querySelectorAll('[data-public-summary] .tnet-profile-public__summary-content').forEach(function (content) { observer.observe(content); });
  } else window.addEventListener('resize', repack);
})();
