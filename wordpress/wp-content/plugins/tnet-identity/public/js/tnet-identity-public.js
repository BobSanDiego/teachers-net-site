(function () {
  'use strict';

  document.querySelectorAll('[data-tnet-identity-password-toggle]').forEach(function (toggle) {
    var input = document.getElementById(toggle.getAttribute('aria-controls'));
    if (!input) return;
    toggle.addEventListener('click', function () {
      var showing = input.type === 'text';
      input.type = showing ? 'password' : 'text';
      toggle.textContent = showing ? 'Show' : 'Hide';
      toggle.setAttribute('aria-pressed', showing ? 'false' : 'true');
    });
  });

  document.querySelectorAll('[data-tnet-identity-username-why]').forEach(function (toggle) {
    var explanation = document.getElementById(toggle.getAttribute('aria-controls'));
    if (!explanation) return;
    toggle.addEventListener('click', function () {
      var expanded = toggle.getAttribute('aria-expanded') === 'true';
      toggle.setAttribute('aria-expanded', expanded ? 'false' : 'true');
      explanation.hidden = expanded;
    });
  });

  document.querySelectorAll('[data-tnet-display-name-form]').forEach(function (form) {
    var input = form.querySelector('[data-tnet-display-name-input]');
    var status = form.querySelector('[data-tnet-display-name-status]');
    var submit = form.querySelector('[data-tnet-display-name-submit]');
    if (!input || !status || !submit) return;
    var allowed = /^[\p{L}\p{M}\p{N} .,'’()&-]+$/u;
    var containsLetterOrNumber = /[\p{L}\p{N}]/u;
    var forbidden = /[\p{Cc}\p{Cf}\p{Zl}\p{Zp}]/u;
    var validate = function (showValid) {
      var raw = input.value;
      var value = raw.trim().replace(/\s+/gu, ' ');
      var message = '';
      if (!value) message = 'Enter the name other members should see.';
      else if (Array.from(value).length < 2 || Array.from(value).length > 50) message = 'Use 2–50 characters.';
      else if (forbidden.test(raw) || !containsLetterOrNumber.test(value) || !allowed.test(value)) message = 'Use letters, numbers, spaces, and basic punctuation only.';
      input.setCustomValidity(message);
      input.setAttribute('aria-invalid', message ? 'true' : 'false');
      submit.disabled = !!message;
      status.classList.toggle('is-error', !!message);
      status.classList.toggle('is-valid', !message && showValid);
      status.textContent = message || (showValid ? 'Looks good.' : '');
    };
    if (input.getAttribute('data-tnet-display-name-server-error')) validate(false);
    input.addEventListener('input', function () { validate(true); });
  });

  document.querySelectorAll('[data-tnet-location-form]').forEach(function (form) {
    var modes = Array.prototype.slice.call(form.querySelectorAll('[data-tnet-location-mode]'));
    var panels = Array.prototype.slice.call(form.querySelectorAll('[data-tnet-location-panel]'));
    var state = form.querySelector('[data-tnet-location-state]');
    var country = form.querySelector('[data-tnet-location-country]');
    var submit = form.querySelector('[data-tnet-location-submit]');
    var returnToState = form.querySelector('[data-tnet-location-return]');
    if (!modes.length || !state || !country || !submit) return;

    if (window.Intl && window.Intl.DisplayNames) {
      var names = new Intl.DisplayNames([document.documentElement.lang || 'en'], { type: 'region' });
      Array.prototype.slice.call(country.options).forEach(function (option) {
        if (!option.value) return;
        try { option.textContent = names.of(option.value) + ' (' + option.value + ')'; } catch (error) { option.textContent = option.value; }
      });
    }

    var activeMode = function () {
      var selected = modes.find(function (input) { return input.checked; });
      return selected ? selected.value : 'us';
    };
    var sync = function (clearInactive) {
      var mode = activeMode();
      var useState = mode === 'us';
      panels.forEach(function (panel) { panel.hidden = panel.getAttribute('data-tnet-location-panel') !== mode; });
      state.disabled = !useState;
      country.disabled = useState;
      if (clearInactive) {
        if (useState) country.value = '';
        else state.value = '';
      }
      submit.disabled = useState ? !state.value : !country.value;
    };
    modes.forEach(function (input) { input.addEventListener('change', function () { sync(true); }); });
    [state, country].forEach(function (select) { select.addEventListener('change', function () { sync(false); }); });
    if (returnToState) returnToState.addEventListener('click', function () { modes.find(function (input) { return input.value === 'us'; }).checked = true; sync(true); state.focus(); });
    sync(false);
  });

    var bankNode = document.querySelector('[data-avatar-bank]');
    var form = document.querySelector('[data-avatar-bank]') && document.querySelector('.tnet-identity-avatar-form');
    if (bankNode && form) {
    var identityCard = form.closest('.tnet-identity-card');
    var bank = [];
    try { bank = JSON.parse(bankNode.textContent || '[]'); } catch (error) { bank = []; }
    var selectedId = form.querySelector('[data-avatar-selected-id]');
    var generationValue = form.querySelector('[data-avatar-generation-value]');
    var presentationValue = form.querySelector('[data-avatar-presentation-value]');
    var photoContext = document.querySelector('[data-avatar-photo-context]');
    var photoPanel = form.querySelector('[data-avatar-mode-panel="photo"]');
    var avatarPanel = form.querySelector('[data-avatar-mode-panel="avatar"]');
    var file = document.getElementById('tnet-identity-avatar-file');
    var dropzone = form.querySelector('[data-avatar-dropzone]');
    var preview = form.querySelector('[data-avatar-preview]');
    var placeholder = form.querySelector('[data-avatar-placeholder]');
    var photoSubmit = form.querySelector('[data-avatar-photo-submit]');
    var photoTools = form.querySelector('[data-avatar-photo-tools]');
    var fileLabel = form.querySelector('[data-avatar-file-label]');
    var dropCopy = form.querySelector('[data-avatar-drop-copy]');
    var cropOpen = form.querySelector('[data-avatar-crop-open]');
    var photoRemove = form.querySelector('[data-avatar-photo-remove]');
    var generationFilter = form.querySelector('[data-avatar-generation-filter]');
    var presentationFilter = form.querySelector('[data-avatar-presentation-filter]');
    var results = form.querySelector('[data-avatar-results]');
    var count = form.querySelector('[data-avatar-count]');
    var viewport = form.querySelector('[data-avatar-carousel-viewport]');
    var previous = form.querySelector('[data-avatar-carousel-prev]');
    var next = form.querySelector('[data-avatar-carousel-next]');
    var avatarSubmit = form.querySelector('[data-avatar-submit]');
    var selectedPreview = form.querySelector('[data-avatar-selected-preview]');
    var selectedImage = form.querySelector('[data-avatar-selected-image]');
    var skipButton = form.querySelector('[data-avatar-skip]');
    var skipModal = form.parentNode.querySelector('[data-avatar-skip-modal]');
    var pickModal = form.parentNode.querySelector('[data-avatar-pick-modal]');
    var randomResult = form.querySelector('[data-avatar-random-result]');
    var randomImage = form.querySelector('[data-avatar-random-image]');
    var assignedSubmit = form.querySelector('[data-avatar-assigned-submit]');
    var cropModal = form.parentNode.querySelector('[data-avatar-crop-modal]');
    /*
     * Identity dialogs cannot remain inside the focused shell's main grid
     * layer: that layer intentionally sits below the shell rail and fixed
     * account controls.  Portal every Screen 3 dialog to one body-level
     * overlay root so the backdrop and dialog share one complete-viewport
     * stacking contract.
     */
    var overlayRoot = document.querySelector('[data-tnet-identity-overlay-root]');
    if (!overlayRoot) {
      overlayRoot = document.createElement('div');
      overlayRoot.setAttribute('data-tnet-identity-overlay-root', '');
      document.body.appendChild(overlayRoot);
    }
    [skipModal, pickModal, cropModal].forEach(function (modal) {
      if (modal) overlayRoot.appendChild(modal);
    });
    var cropStage = cropModal && cropModal.querySelector('[data-avatar-crop-stage]');
    var cropCanvas = cropModal && cropModal.querySelector('[data-avatar-crop-canvas]');
    var cropResult = cropModal && cropModal.querySelector('[data-avatar-crop-result]');
    var cropZoom = cropModal && cropModal.querySelector('[data-avatar-crop-zoom]');
    var cropImage = null;
    var cropOffset = { x: 0, y: 0 };
    var currentMode = 'photo';
    var avatarItems = [];
    var avatarIndex = 0;
    var photoState = { file: null, blob: null, url: '', outputUrl: '', image: null };
    var shellAvatar = document.querySelector('[data-avatar-source]');
    var shellAvatarMarkup = shellAvatar ? shellAvatar.innerHTML : '';
    var shellAvatarSource = shellAvatar ? shellAvatar.getAttribute('data-avatar-source') : '';
    var dragState = null;
    var visibleCount = function () { return window.matchMedia('(max-width: 767px)').matches ? 3 : 5; };
    var presentationPool = function (value) { return value === 'Feminine' ? 'Women' : value === 'Masculine' ? 'Men' : ''; };
    var avatarCellOrder = [
      'Gen Z|Women', 'Gen Z|Men',
      'Millennial|Women', 'Millennial|Men',
      'Gen X|Women', 'Gen X|Men',
      'Boomer+|Women', 'Boomer+|Men'
    ];
    var stableHash = function (value) {
      var hash = 2166136261;
      String(value).split('').forEach(function (character) {
        hash ^= character.charCodeAt(0);
        hash = Math.imul(hash, 16777619);
      });
      return hash >>> 0;
    };
    var orderAvatarPool = function (items) {
      var groups = {};
      items.forEach(function (item) {
        var key = item.generation + '|' + item.presentation;
        if (!groups[key]) groups[key] = [];
        groups[key].push(item);
      });
      Object.keys(groups).forEach(function (key) {
        groups[key].sort(function (left, right) {
          var hashDifference = stableHash(left.id) - stableHash(right.id);
          return hashDifference || left.id.localeCompare(right.id);
        });
      });
      var cells = avatarCellOrder.filter(function (key) { return groups[key] && groups[key].length; });
      Object.keys(groups).filter(function (key) { return cells.indexOf(key) === -1; }).sort().forEach(function (key) { cells.push(key); });
      var ordered = [];
      var offset = 0;
      while (cells.some(function (key) { return offset < groups[key].length; })) {
        cells.forEach(function (key) { if (groups[key][offset]) ordered.push(groups[key][offset]); });
        offset += 1;
      }
      return ordered;
    };
    var preloadAdjacent = function () {
      var pageSize = visibleCount();
      [avatarIndex - pageSize, avatarIndex + pageSize].forEach(function (start) {
        if (start < 0 || start >= avatarItems.length) return;
        avatarItems.slice(start, start + pageSize).forEach(function (item) {
          var image = new Image();
          image.decoding = 'async';
          image.src = item.url;
        });
      });
    };
    var setShellPreview = function (url) {
      if (!shellAvatar) return;
      if (!url) {
        shellAvatar.innerHTML = shellAvatarMarkup;
        if (shellAvatarSource) shellAvatar.setAttribute('data-avatar-source', shellAvatarSource);
        return;
      }
      var image = shellAvatar.querySelector('[data-screen3-shell-preview]');
      if (!image) {
        shellAvatar.replaceChildren();
        image = document.createElement('img');
        image.setAttribute('data-screen3-shell-preview', '');
        image.alt = '';
        shellAvatar.appendChild(image);
      }
      image.src = url;
      shellAvatar.setAttribute('data-avatar-source', 'screen3-preview');
    };
    var syncShellPreview = function () {
      var url = '';
      if (currentMode === 'photo' && photoState.blob && preview && !preview.hidden) url = preview.src;
      if (currentMode === 'avatar' && selectedId.value && selectedPreview && !selectedPreview.hidden) url = selectedImage.src;
      if (currentMode === 'assigned' && selectedId.value && randomResult && !randomResult.hidden) url = randomImage.src;
      setShellPreview(url);
    };
    var setMode = function (mode) {
      currentMode = mode;
      if (identityCard) {
        identityCard.classList.toggle('tnet-identity-card--avatar-chooser', mode === 'avatar');
        identityCard.setAttribute('aria-labelledby', mode === 'avatar' ? 'tnet-identity-chooser-title' : (mode === 'assigned' ? 'tnet-identity-random-result-title' : 'tnet-identity-avatar-title'));
      }
      if (photoContext) photoContext.hidden = mode !== 'photo';
      photoPanel.hidden = mode !== 'photo';
      avatarPanel.hidden = mode !== 'avatar';
      randomResult.hidden = mode !== 'assigned';
      skipButton.hidden = mode === 'assigned';
      if (mode === 'avatar' && file) file.value = '';
      if (mode === 'avatar') renderCarousel();
      syncShellPreview();
    };
    var closeSkipModal = function () { if (skipModal) skipModal.hidden = true; if (currentMode !== 'photo') setMode('photo'); };
    var closePickModal = function () { if (pickModal) pickModal.hidden = true; };
    var drawCrop = function () {
      if (!cropImage || !cropCanvas) return;
      var ctx = cropCanvas.getContext('2d');
      var size = cropCanvas.width;
      var scale = Math.max(size / cropImage.naturalWidth, size / cropImage.naturalHeight) * Number(cropZoom.value || 1);
      var width = cropImage.naturalWidth * scale;
      var height = cropImage.naturalHeight * scale;
      var x = (size - width) / 2 + cropOffset.x;
      var y = (size - height) / 2 + cropOffset.y;
      ctx.clearRect(0, 0, size, size);
      ctx.fillStyle = '#172b4d';
      ctx.fillRect(0, 0, size, size);
      ctx.drawImage(cropImage, x, y, width, height);
      if (cropResult) cropResult.style.backgroundImage = 'url(' + cropCanvas.toDataURL('image/jpeg', 0.9) + ')';
    };
    var openCrop = function () { if (!cropImage || !cropModal) return; cropModal.hidden = false; cropModal.classList.add('is-open'); drawCrop(); if (cropZoom) cropZoom.focus(); };
    var closeCrop = function () { if (!cropModal) return; cropModal.hidden = true; cropModal.classList.remove('is-open'); };
    var applyCrop = function () {
      if (!cropImage || !cropCanvas) return;
      cropCanvas.toBlob(function (blob) {
        if (!blob) return;
        photoState.blob = blob;
        if (photoState.outputUrl) URL.revokeObjectURL(photoState.outputUrl);
        photoState.outputUrl = URL.createObjectURL(blob);
        preview.src = photoState.outputUrl;
        syncShellPreview();
        closeCrop();
      }, 'image/jpeg', 0.9);
    };
    var resetCrop = function () { cropOffset = { x: 0, y: 0 }; if (cropZoom) cropZoom.value = '1'; drawCrop(); };
    var handlePhoto = function (chosen) {
      if (!chosen) return;
      if (!/^image\/(jpeg|png|webp)$/.test(chosen.type) || chosen.size > 5242880) { fileLabel.textContent = 'Choose a photo'; return; }
      if (photoState.url) URL.revokeObjectURL(photoState.url);
      if (photoState.outputUrl) URL.revokeObjectURL(photoState.outputUrl);
      photoState.file = chosen;
      photoState.blob = chosen;
      photoState.url = URL.createObjectURL(chosen);
      photoState.outputUrl = '';
      cropImage = new Image();
      cropImage.onload = function () {
        if (cropImage.naturalWidth < 64 || cropImage.naturalHeight < 64 || cropImage.naturalWidth > 2048 || cropImage.naturalHeight > 2048) { photoRemove.click(); return; }
        preview.hidden = false;
        preview.src = photoState.url;
        preview.alt = 'Selected profile photo';
        placeholder.hidden = true;
        dropCopy.hidden = true;
        photoTools.hidden = false;
        photoSubmit.hidden = false;
        fileLabel.textContent = 'Change photo';
        dropzone.classList.add('has-photo');
        syncShellPreview();
      };
      cropImage.src = photoState.url;
      selectedId.value = '';
    };
    var clearPhoto = function () {
      if (photoState.url) URL.revokeObjectURL(photoState.url);
      if (photoState.outputUrl) URL.revokeObjectURL(photoState.outputUrl);
      photoState = { file: null, blob: null, url: '', outputUrl: '', image: null };
      cropImage = null;
      if (file) file.value = '';
      preview.hidden = true;
      preview.alt = '';
      placeholder.hidden = false;
      dropCopy.hidden = false;
      photoTools.hidden = true;
      photoSubmit.hidden = true;
      fileLabel.textContent = 'Choose a photo';
      dropzone.classList.remove('has-photo');
      syncShellPreview();
    };
    var renderCarousel = function () {
      var generation = generationFilter.value;
      var presentation = presentationFilter.value;
      var pool = presentationPool(presentation);
      avatarItems = presentation ? orderAvatarPool(bank.filter(function (item) { return (!generation || item.generation === generation) && (pool === '' || item.presentation === pool); })) : [];
      if (!avatarItems.some(function (item) { return item.id === selectedId.value; })) {
        selectedId.value = '';
        selectedPreview.hidden = true;
        avatarSubmit.disabled = true;
        avatarSubmit.hidden = true;
      }
      var pageSize = visibleCount();
      avatarIndex = Math.min(avatarIndex, Math.max(0, avatarItems.length - pageSize));
      results.replaceChildren();
      results.style.setProperty('--avatar-visible', String(pageSize));
        avatarItems.slice(avatarIndex, avatarIndex + pageSize).forEach(function (item) {
          var button = document.createElement('button');
        button.type = 'button';
        button.setAttribute('aria-label', 'Select illustrated avatar');
        button.setAttribute('aria-pressed', item.id === selectedId.value ? 'true' : 'false');
          var image = document.createElement('img'); image.src = item.url; image.width = 96; image.height = 96; image.alt = ''; image.loading = 'eager'; image.decoding = 'async'; image.fetchPriority = 'high';
          button.appendChild(image);
          var selectionLabel = document.createElement('span'); selectionLabel.className = 'tnet-identity-avatar-selection-label'; selectionLabel.textContent = 'Selected';
          button.appendChild(selectionLabel);
        button.addEventListener('click', function () {
          selectedId.value = item.id;
          selectedImage.src = item.url;
          selectedImage.alt = 'Selected illustrated avatar';
          selectedPreview.hidden = false;
          avatarSubmit.disabled = false;
          avatarSubmit.hidden = false;
          generationValue.value = '';
          presentationValue.value = '';
          results.querySelectorAll('button').forEach(function (candidate) { candidate.setAttribute('aria-pressed', String(candidate === button)); });
          syncShellPreview();
        });
        results.appendChild(button);
      });
      results.hidden = avatarItems.length === 0;
      var lastIndex = Math.min(avatarIndex + pageSize, avatarItems.length);
      count.textContent = avatarItems.length ? (avatarIndex + 1) + '–' + lastIndex + ' of ' + avatarItems.length : (presentation ? 'No avatars available for this view.' : 'Choose a presentation to see avatars.');
      previous.disabled = avatarIndex === 0 || !avatarItems.length;
      next.disabled = avatarIndex + pageSize >= avatarItems.length || !avatarItems.length;
      preloadAdjacent();
      syncShellPreview();
    };
    var moveCarousel = function (direction) { avatarIndex = Math.max(0, Math.min(Math.max(0, avatarItems.length - visibleCount()), avatarIndex + direction * visibleCount())); renderCarousel(); };
    var showRandomResult = function () {
      var preference = pickModal.querySelector('input[name="avatar_pick_presentation"]:checked').value;
      var generation = pickModal.querySelector('[data-avatar-pick-generation]').value;
      var pool = presentationPool(preference);
      var choices = bank.filter(function (item) { return (!generation || item.generation === generation) && (pool === '' || item.presentation === pool); });
      if (!choices.length) return;
      var item = choices[Math.floor(Math.random() * choices.length)];
      selectedId.value = item.id;
      generationValue.value = '';
      presentationValue.value = '';
      randomImage.src = item.url;
      randomImage.alt = 'Assigned illustrated avatar';
      closePickModal();
      closeSkipModal();
      setMode('assigned');
    };
    skipButton.addEventListener('click', function () { skipModal.hidden = false; });
    skipModal.querySelector('[data-avatar-skip-close]').addEventListener('click', closeSkipModal);
    skipModal.querySelector('[data-avatar-skip-cancel]').addEventListener('click', closeSkipModal);
    skipModal.querySelector('[data-avatar-choose]').addEventListener('click', function () { closeSkipModal(); setMode('avatar'); });
    skipModal.querySelector('[data-avatar-pick-open]').addEventListener('click', function () { closeSkipModal(); pickModal.hidden = false; });
    pickModal.querySelector('[data-avatar-pick-close]').addEventListener('click', closePickModal);
    pickModal.querySelector('[data-avatar-pick-cancel]').addEventListener('click', closePickModal);
    pickModal.querySelector('[data-avatar-pick-submit]').addEventListener('click', showRandomResult);
    form.querySelector('[data-avatar-choose-photo]').addEventListener('click', function () { setMode('photo'); });
    form.querySelector('[data-avatar-change-avatar]').addEventListener('click', function () { selectedId.value = ''; selectedPreview.hidden = true; avatarSubmit.disabled = true; avatarSubmit.hidden = true; setMode('avatar'); });
    form.querySelector('[data-avatar-result-photo]').addEventListener('click', function () { setMode('photo'); });
    generationFilter.addEventListener('change', function () { avatarIndex = 0; renderCarousel(); });
    form.querySelectorAll('[data-avatar-presentation-choice]').forEach(function (choice) {
      choice.addEventListener('click', function () {
        presentationFilter.value = choice.getAttribute('data-avatar-presentation-choice') || 'both';
        form.querySelectorAll('[data-avatar-presentation-choice]').forEach(function (button) {
          button.setAttribute('aria-pressed', String(button === choice));
        });
        avatarIndex = 0;
        renderCarousel();
      });
    });
    previous.addEventListener('click', function () { moveCarousel(-1); });
    next.addEventListener('click', function () { moveCarousel(1); });
    if (file) file.addEventListener('change', function () { handlePhoto(file.files && file.files[0]); });
    if (dropzone) {
      ['dragenter', 'dragover'].forEach(function (eventName) { dropzone.addEventListener(eventName, function (event) { event.preventDefault(); dropzone.classList.add('is-dragover'); }); });
      ['dragleave', 'drop'].forEach(function (eventName) { dropzone.addEventListener(eventName, function (event) { event.preventDefault(); dropzone.classList.remove('is-dragover'); }); });
      dropzone.addEventListener('drop', function (event) { handlePhoto(event.dataTransfer && event.dataTransfer.files && event.dataTransfer.files[0]); });
    }
    if (photoRemove) photoRemove.addEventListener('click', clearPhoto);
    if (cropOpen) cropOpen.addEventListener('click', openCrop);
    if (cropModal) {
      cropModal.querySelector('[data-avatar-crop-close]').addEventListener('click', closeCrop);
      cropModal.querySelector('[data-avatar-crop-reset]').addEventListener('click', resetCrop);
      cropModal.querySelector('[data-avatar-crop-apply]').addEventListener('click', applyCrop);
      cropZoom.addEventListener('input', drawCrop);
      cropStage.addEventListener('pointerdown', function (event) { dragState = { x: event.clientX, y: event.clientY }; cropStage.setPointerCapture(event.pointerId); });
      cropStage.addEventListener('pointermove', function (event) { if (!dragState) return; cropOffset.x += event.clientX - dragState.x; cropOffset.y += event.clientY - dragState.y; dragState = { x: event.clientX, y: event.clientY }; drawCrop(); });
      cropStage.addEventListener('pointerup', function () { dragState = null; });
    }
    form.addEventListener('submit', function (event) {
      if (currentMode === 'photo') {
        if (!photoState.blob || photoSubmit.hidden) { event.preventDefault(); return; }
        event.preventDefault();
        var data = new FormData(form);
        data.set('profile_avatar', photoState.blob, 'profile-avatar.jpg');
        fetch(form.getAttribute('action') || window.location.href, { method: 'POST', body: data, credentials: 'same-origin' }).then(function (response) { window.location.assign(response.url); }).catch(function () { photoSubmit.hidden = false; });
      } else if (currentMode === 'avatar') {
        if (!selectedId.value || avatarSubmit.disabled || event.submitter !== avatarSubmit) event.preventDefault();
      } else if (currentMode === 'assigned') {
        if (!selectedId.value || event.submitter !== assignedSubmit) event.preventDefault();
      }
    });
    window.addEventListener('resize', function () { if (currentMode === 'avatar') renderCarousel(); });
    setMode('photo');
    renderCarousel();
  }
}());
