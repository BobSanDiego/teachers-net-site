(function () {
  'use strict';

  function decode(value) { try { return JSON.parse(value || '[]'); } catch (error) { return []; } }
  function labelsFromPicker(form, name) {
    var map = {};
    Array.prototype.forEach.call(form.querySelectorAll('[data-tnet-profile-picker]'), function (picker) {
      decode(picker.getAttribute('data-picker-catalog')).forEach(function (item) { map[item.uuid] = item.label; });
      var preserved = decode(picker.getAttribute('data-picker-preserved-labels'));
      Object.keys(preserved).forEach(function (uuid) { map[uuid] = preserved[uuid]; });
    });
    var prefix = name + '[]';
    return Array.prototype.filter.call(form.querySelectorAll('input[type="hidden"]'), function (input) { return input.name === prefix; })
      .map(function (input) { return map[input.value] || input.value; });
  }

  function readPendingState(form, card) {
    var state = {};
    ['location', 'grade', 'subject', 'bio', 'role', 'teaching_since'].forEach(function (key) {
      var row = card && card.querySelector('[data-profile-fact-row="' + key + '"]');
      var value = row && row.querySelector('[data-profile-fact-value]');
      state[key] = row && !row.hidden && value && value.textContent.trim() ? [value.textContent.trim()] : [];
    });
    var gradeControls = form.querySelectorAll('input[name="teaching_grades[]"]');
    var gradeInputs = Array.prototype.slice.call(form.querySelectorAll('input[name="teaching_grades[]"]:checked'));
    var grades = [];
    var included = new Set();
    Array.prototype.forEach.call(form.querySelectorAll('[data-tnet-profile-grade-family]'), function (family) {
      var uuid = family.getAttribute('data-grade-family');
      var checked = Array.prototype.filter.call(family.querySelectorAll('[data-grade-family-child] input[name="teaching_grades[]"]:checked'), function (input) {
        return input.parentElement.getAttribute('data-grade-family-child') === uuid;
      });
      checked.forEach(function (input) { included.add(input); });
      if (checked.length === 1) grades.push(checked[0].nextElementSibling.textContent.trim());
      else if (checked.length > 1) {
        var heading = family.querySelector('[data-grade-disclosure] > summary span');
        if (heading) grades.push(heading.textContent.trim());
      }
    });
    gradeInputs.forEach(function (input) { if (!included.has(input)) grades.push(input.nextElementSibling ? input.nextElementSibling.textContent.trim() : input.value); });
    if (gradeControls.length) state.grade = grades;

    var location = '';
    var locationRoot = form.querySelector('[data-tnet-profile-guided-location]');
    if (locationRoot) {
      var complete = locationRoot.querySelector('[data-location-complete]');
      var valueLabel = locationRoot.querySelector('[data-location-label]');
      if (complete && !complete.hidden && valueLabel) location = valueLabel.textContent.trim();
      else {
        var mode = locationRoot.querySelector('[data-location-mode]');
        var selected = mode && mode.value === 'us' ? locationRoot.querySelector('[data-location-state]') : (mode && mode.value === 'international' ? locationRoot.querySelector('[data-location-country]') : null);
        if (selected && selected.value) location = selected.options[selected.selectedIndex].textContent.trim();
      }
      state.location = location ? [location] : [];
    }
    // The picker remains present when its final chip is removed. Derive an
    // empty selection from the picker owner rather than using selected hidden
    // inputs as a proxy for whether this page owns the field.
    var subjectPicker = form.querySelector('[data-picker-name="teaching_subjects"]');
    if (subjectPicker) state.subject = labelsFromPicker(form, 'teaching_subjects');
    var bio = form.querySelector('[data-tnet-profile-bio]');
    if (bio) state.bio = bio.value.trim() ? [bio.value.trim()] : [];
    var since = form.querySelector('[name="teaching_since"]');
    if (since) state.teaching_since = since.value ? [since.value] : [];
    var roleControls = form.querySelectorAll('[data-role-choice]');
    var roles = Array.prototype.slice.call(form.querySelectorAll('[data-role-choice]:checked')).map(function (input) {
      var label = input.closest('label');
      var text = label && label.querySelector('span');
      return text ? text.textContent.trim() : input.value;
    });
    if (roleControls.length) state.role = roles;
    return state;
  }

  function renderState(state) {
    Array.prototype.forEach.call(document.querySelectorAll('[data-profile-live-card]'), function (card) {
      Object.keys(state).forEach(function (key) {
        var row = card.querySelector('[data-profile-fact-row="' + key + '"]');
        if (!row) return;
        var values = state[key];
        var value = row.querySelector('[data-profile-fact-value]');
        var text = values.join(', ');
        if (value) value.textContent = text;
        row.hidden = values.length === 0;
      });
    });
    Object.keys(state).forEach(function (key) {
      var complete = state[key].length > 0;
      Array.prototype.forEach.call(document.querySelectorAll('[data-profile-status-fact="' + key + '"]'), function (row) {
        row.classList.toggle('is-complete', complete);
        row.classList.toggle('is-pending', !complete);
        var marker = row.querySelector('span');
        if (marker) marker.textContent = complete ? '✓' : '';
      });
    });
  }

  function syncLivePreview() {
    var form = document.querySelector('.tnet-profile-basics-form, .tnet-profile-enrichment-form');
    var card = document.querySelector('[data-profile-live-card]');
    if (form && (card || document.querySelector('[data-profile-status-fact]'))) renderState(readPendingState(form, card));
  }

  var root = document.querySelector('.tnet-profile-enrichment-form');
  if (root) {
    var choices = Array.prototype.slice.call(root.querySelectorAll('[data-role-choice]'));
    var suggestions = Array.prototype.slice.call(root.querySelectorAll('[data-suggest-role]'));
    function sync(uuid) {
      var selected = choices.some(function (choice) { return choice.value === uuid && choice.checked; });
      suggestions.forEach(function (button) {
        if (button.getAttribute('data-suggest-role') === uuid) button.setAttribute('aria-pressed', selected ? 'true' : 'false');
      });
    }
    choices.forEach(function (choice) {
      choice.addEventListener('change', function () { sync(choice.value); });
    });
    suggestions.forEach(function (button) {
      button.addEventListener('click', function () {
        var uuid = button.getAttribute('data-suggest-role');
        var choice = choices.find(function (item) { return item.value === uuid; });
        if (!choice) return;
        choice.checked = !choice.checked;
        sync(uuid);
        choice.dispatchEvent(new Event('change', { bubbles: true }));
      });
    });
  }

  document.addEventListener('DOMContentLoaded', syncLivePreview);
  document.addEventListener('input', syncLivePreview);
  document.addEventListener('change', syncLivePreview);
  document.addEventListener('profile:pending-change', syncLivePreview);
}());
