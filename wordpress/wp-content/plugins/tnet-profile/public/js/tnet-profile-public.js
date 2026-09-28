(function () {
  'use strict';
  var activeControl = null;

  function pack(row) {
    var content = row.querySelector('.tnet-profile-public__summary-content');
    var values = Array.prototype.slice.call(row.querySelectorAll('[data-summary-value]'));
    var button = row.querySelector('[data-public-disclosure]');
    var label = button.querySelector('[data-more-label]');
    var suppressed = Number(row.getAttribute('data-suppressed-count')) || 0;
    if (!content.clientWidth) return;
    values.forEach(function (value) {
      value.hidden = false;
      value.classList.remove('is-long');
      var separator = value.querySelector('.tnet-profile-public__separator');
      if (separator) separator.style.visibility = '';
    });
    button.hidden = true;
    var valueArea = content.querySelector('.tnet-profile-public__summary-values');
    var fullWidth = valueArea.clientWidth;
    values.forEach(function (value) { value.classList.toggle('is-long', value.scrollWidth > fullWidth); });
    var widths = values.map(function (value) { return value.getBoundingClientRect().width; });
    var linesAllowed = window.matchMedia('(max-width: 700px)').matches ? 2 : 1;
    function lineCount(count, available) {
      var lines = 1;
      var occupied = 0;
      for (var index = 0; index < count; index++) {
        if (occupied && occupied + widths[index] > available + 0.5) { lines++; occupied = 0; }
        occupied += widths[index];
      }
      return lines;
    }
    var fit = values.length;
    if (suppressed > 0 || (values.length > 1 && lineCount(values.length, fullWidth) > linesAllowed)) {
      label.textContent = 'more';
      button.hidden = false;
      var available = valueArea.clientWidth;
      values.forEach(function (value) { value.classList.toggle('is-long', value.scrollWidth > available); });
      widths = values.map(function (value) { return value.getBoundingClientRect().width; });
      fit = 1;
      for (var count = values.length; count >= 1; count--) {
        if (lineCount(count, available) <= linesAllowed) { fit = count; break; }
      }
    }
    values.forEach(function (value, index) { value.hidden = index >= fit; });
    for (var visible = 1; visible < fit; visible++) {
      if (values[visible].getBoundingClientRect().top > values[visible - 1].getBoundingClientRect().top + 2) {
        var leadingDot = values[visible].querySelector('.tnet-profile-public__separator');
        if (leadingDot) leadingDot.style.visibility = 'hidden';
      }
    }
    var hiddenCount = values.length - fit + suppressed;
    label.textContent = 'more';
    button.dataset.hiddenCount = String(hiddenCount);
    button.hidden = hiddenCount === 0;
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
