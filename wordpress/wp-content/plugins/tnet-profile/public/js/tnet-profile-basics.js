(function () {
  'use strict';

  function textLength(value) { return Array.from(value || '').length; }
  function decode(value) { try { return JSON.parse(value || '[]'); } catch (error) { return []; } }

  function mountPicker(root) {
    var name = root.getAttribute('data-picker-name');
    var catalog = decode(root.getAttribute('data-picker-catalog'));
    var selected = decode(root.getAttribute('data-picker-selected'));
    var suggested = decode(root.getAttribute('data-picker-suggestions'));
    var preservedLabels = decode(root.getAttribute('data-picker-preserved-labels'));
    var selectedRegion = root.querySelector('[data-picker-selected]');
    var search = root.querySelector('[data-picker-search]');
    var results = root.querySelector('[data-picker-results]');
    var labels = {};
    catalog.forEach(function (item) { labels[item.uuid] = item.label; });

    function selectedValues() { return selected.slice(); }
    function renderSelected() {
      selectedRegion.innerHTML = '';
      selectedValues().forEach(function (uuid) {
        var chip = document.createElement('span');
        chip.className = 'tnet-profile-basics-chip';
        var itemLabel = labels[uuid] || preservedLabels[uuid] || uuid;
        chip.appendChild(document.createTextNode(itemLabel));
        if (labels[uuid]) {
          var remove = document.createElement('button');
          remove.type = 'button';
          remove.setAttribute('aria-label', 'Remove ' + itemLabel);
          remove.textContent = '×';
          remove.addEventListener('click', function () {
            selected = selected.filter(function (value) { return value !== uuid; });
            renderSelected(); renderResults(search.value); search.focus();
          });
          chip.appendChild(remove);
        } else {
          chip.classList.add('tnet-profile-basics-chip--preserved');
          chip.setAttribute('aria-label', itemLabel + ' — previously saved and retained');
        }
        var input = document.createElement('input');
        input.type = 'hidden'; input.name = name + '[]'; input.value = uuid;
        selectedRegion.appendChild(chip); selectedRegion.appendChild(input);
      });
      document.dispatchEvent(new CustomEvent('profile:pending-change'));
    }
    function renderResults(value) {
      var query = (value || '').trim().toLocaleLowerCase();
      results.innerHTML = '';
      var matches = catalog.filter(function (item) {
        return selected.indexOf(item.uuid) === -1 && (!query || item.label.toLocaleLowerCase().indexOf(query) !== -1);
      }).slice(0, query ? 8 : 5);
      if (!query) {
        suggested.forEach(function (uuid) {
          var item = catalog.filter(function (candidate) { return candidate.uuid === uuid; })[0];
          if (item && selected.indexOf(item.uuid) === -1 && matches.indexOf(item) === -1) matches.unshift(item);
        });
        if (!suggested.length) return;
      }
      if (!matches.length && query) {
        var empty = document.createElement('p'); empty.className = 'tnet-profile-basics-picker-empty'; empty.textContent = 'No matching choices.'; results.appendChild(empty); return;
      }
      matches.forEach(function (item) {
        var option = document.createElement('button');
        option.type = 'button'; option.className = 'tnet-profile-basics-picker-option'; option.setAttribute('role', 'option');
        option.textContent = item.label + (suggested.indexOf(item.uuid) !== -1 ? ' — suggested' : '');
        option.addEventListener('click', function () { selected.push(item.uuid); search.value = ''; renderSelected(); renderResults(''); search.focus(); });
        results.appendChild(option);
      });
    }
    search.addEventListener('input', function () { renderResults(search.value); });
    search.addEventListener('keydown', function (event) {
      if (event.key === 'ArrowDown') { var first = results.querySelector('button'); if (first) { event.preventDefault(); first.focus(); } }
    });
    renderSelected(); renderResults('');
  }

  function mountGuidedLocation(root) {
    var controls = root.querySelector('[data-location-controls]');
    var completed = root.querySelector('[data-location-complete]');
    var row = root.querySelector('[data-location-row]');
    var mode = root.querySelector('[data-location-mode]');
    var state = root.querySelector('[data-location-state]');
    var country = root.querySelector('[data-location-country]');
    var selection = root.querySelector('[data-location-selection]');
    var label = root.querySelector('[data-location-label]');
    var change = root.querySelector('[data-location-change]');
    var usPanel = root.querySelector('[data-location-panel="us"]');
    var internationalPanel = root.querySelector('[data-location-panel="international"]');
    if (!controls || !completed || !row || !mode || !state || !country || !selection || !label || !change || !usPanel || !internationalPanel) return;

    function activeControl() { return mode.value === 'us' ? state : (mode.value === 'international' ? country : null); }
    function syncMode() {
      var isUs = mode.value === 'us';
      var isInternational = mode.value === 'international';
      usPanel.hidden = !isUs;
      internationalPanel.hidden = !isInternational;
      state.disabled = !isUs;
      country.disabled = !isInternational;
      row.classList.toggle('is-split', isUs || isInternational);
      var control = activeControl();
      selection.value = control ? control.value : '';
    }
    function complete() {
      var control = activeControl();
      if (!control || !control.value) return;
      selection.value = control.value;
      label.textContent = control.options[control.selectedIndex].textContent;
      controls.hidden = true;
      completed.hidden = false;
    }

    mode.addEventListener('change', syncMode);
    state.addEventListener('change', complete);
    country.addEventListener('change', complete);
    change.addEventListener('click', function () {
      completed.hidden = true;
      controls.hidden = false;
      syncMode();
      (activeControl() || mode).focus();
    });
    syncMode();
  }

  function mountGradeGroups(root) {
    Array.prototype.forEach.call(root.querySelectorAll('[data-tnet-profile-grade-family]'), function (group) {
      var parent = group.querySelector('[data-grade-parent]');
      var disclosure = group.querySelector('[data-grade-disclosure]');
      var children = Array.prototype.slice.call(group.querySelectorAll('input[name="teaching_grades[]"]'));
      if (!parent || !disclosure || !children.length) return;
      function syncState() {
        var selectedCount = children.filter(function (child) { return child.checked; }).length;
        var partial = selectedCount > 0 && selectedCount < children.length;
        // Jobs uses a muted checked parent for derived selection, not a blue minus box.
        parent.checked = selectedCount > 0;
        parent.indeterminate = false;
        parent.setAttribute('aria-checked', partial ? 'mixed' : (parent.checked ? 'true' : 'false'));
        group.classList.toggle('is-derived', partial);
      }
      group.addEventListener('change', function (event) {
        if (children.indexOf(event.target) !== -1) syncState();
      });
      parent.addEventListener('click', function () {
        var selectAll = children.some(function (child) { return !child.checked; });
        children.forEach(function (child) { child.checked = selectAll; });
        if (selectAll) disclosure.open = true;
        syncState();
      });
      syncState();
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    var bio = document.querySelector('[data-tnet-profile-bio]');
    var count = document.querySelector('[data-tnet-profile-bio-count]');
    if (bio && count) { var updateCount = function () { count.textContent = String(textLength(bio.value)); }; bio.addEventListener('input', updateCount); updateCount(); }
    Array.prototype.forEach.call(document.querySelectorAll('[data-tnet-profile-picker]'), mountPicker);
    Array.prototype.forEach.call(document.querySelectorAll('[data-tnet-profile-guided-location]'), mountGuidedLocation);
    Array.prototype.forEach.call(document.querySelectorAll('.tnet-profile-basics-grades'), mountGradeGroups);
  });
}());
