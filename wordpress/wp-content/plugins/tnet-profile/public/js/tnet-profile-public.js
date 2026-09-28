(function () {
  'use strict';
  var activeControl = null;

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
    var triggerGap = 6;
    var fit = 0;
    for (var count = values.length; count >= 0; count--) {
      var remaining = values.length - count + suppressed;
      label.textContent = 'more';
      var needsButton = remaining > 0 || hasDetails;
      button.hidden = !needsButton;
      var buttonWidth = needsButton ? button.getBoundingClientRect().width + triggerGap : 0;
      var line = 1;
      var occupied = 0;
      for (var index = 0; index < count; index++) {
        if (occupied && occupied + widths[index] > available + 0.5) { line++; occupied = 0; }
        occupied += widths[index];
      }
      if (needsButton && occupied + buttonWidth > available + 0.5) line++;
      if (line <= linesAllowed) { fit = count; break; }
    }
    values.forEach(function (value, index) { value.hidden = index >= fit; });
    var hiddenCount = values.length - fit + suppressed;
    label.textContent = 'more';
    button.dataset.hiddenCount = String(hiddenCount);
    button.hidden = hiddenCount === 0 && !hasDetails;
    if (button.hidden && button === activeControl) setOpen(row.closest('.tnet-profile-public__hero'), null);
  }

  function positionPopover(control) {
    if (!control) return;
    var panel = document.getElementById(control.getAttribute('aria-controls'));
    if (!panel || panel.hidden) return;
    var anchor = control.getBoundingClientRect();
    var inset = 12;
    var gap = 7;
    var viewportWidth = document.documentElement.clientWidth;
    var viewportHeight = window.innerHeight;
    if (anchor.bottom < 0 || anchor.top > viewportHeight) {
      setOpen(control.closest('.tnet-profile-public__hero'), null);
      return;
    }
    panel.style.maxHeight = '';
    var below = viewportHeight - anchor.bottom - gap - inset;
    var above = anchor.top - gap - inset;
    var openAbove = panel.getBoundingClientRect().height > below && above > below;
    panel.style.maxHeight = Math.max(0, openAbove ? above : below) + 'px';
    var bounds = panel.getBoundingClientRect();
    var left = Math.max(inset, Math.min(anchor.left, viewportWidth - bounds.width - inset));
    var top = openAbove ? anchor.top - gap - bounds.height : anchor.bottom + gap;
    panel.style.left = left + 'px';
    panel.style.top = Math.max(inset, top) + 'px';
  }

  function setOpen(hero, target) {
    if (!hero) return;
    hero.querySelectorAll('[data-public-disclosure]').forEach(function (control) {
      var panel = document.getElementById(control.getAttribute('aria-controls'));
      var open = control === target;
      control.setAttribute('aria-expanded', open ? 'true' : 'false');
      control.setAttribute('aria-label', control.getAttribute(open ? 'data-label-open' : 'data-label-closed'));
      if (panel) {
        panel.hidden = !open;
        if (!open) { panel.style.left = ''; panel.style.top = ''; panel.style.maxHeight = ''; }
      }
    });
    activeControl = target;
    positionPopover(target);
  }

  document.addEventListener('click', function (event) {
    var control = event.target.closest('[data-public-disclosure]');
    var hide = event.target.closest('[data-public-hide]');
    if (control) {
      var hero = control.closest('.tnet-profile-public__hero');
      setOpen(hero, control.getAttribute('aria-expanded') === 'true' ? null : control);
      return;
    }
    if (!activeControl) return;
    var activePanel = document.getElementById(activeControl.getAttribute('aria-controls'));
    if (hide || !activePanel || !activePanel.contains(event.target)) {
      setOpen(activeControl.closest('.tnet-profile-public__hero'), null);
    }
  });

  document.addEventListener('keydown', function (event) {
    if (event.key !== 'Escape' || !activeControl) return;
    var control = activeControl;
    setOpen(control.closest('.tnet-profile-public__hero'), null);
    control.focus({ preventScroll: true });
    event.preventDefault();
  });

  function repack() { document.querySelectorAll('[data-public-summary]').forEach(pack); positionPopover(activeControl); }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', repack);
  else repack();
  if (typeof ResizeObserver !== 'undefined') {
    var observer = new ResizeObserver(repack);
    document.querySelectorAll('[data-public-summary] .tnet-profile-public__summary-content').forEach(function (content) { observer.observe(content); });
  } else window.addEventListener('resize', repack);
  window.addEventListener('resize', function () { positionPopover(activeControl); });
  window.addEventListener('scroll', function () { positionPopover(activeControl); }, true);
})();
