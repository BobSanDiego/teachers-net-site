(function (window, document) {
  'use strict';

  document.querySelectorAll('[data-profile-avatar-editor]').forEach(function (root) {
    var form = root.querySelector('[data-profile-avatar-upload]');
    var file = root.querySelector('[data-avatar-file]');
    var preview = root.querySelector('[data-avatar-preview]');
    var placeholder = root.querySelector('[data-avatar-placeholder]');
    var dropCopy = root.querySelector('[data-avatar-drop-copy]');
    var tools = root.querySelector('[data-avatar-photo-tools]');
    var label = root.querySelector('[data-avatar-file-label]');
    var submit = root.querySelector('[data-avatar-photo-submit]');
    var cropButton = root.querySelector('[data-avatar-crop-open]');
    var removeButton = root.querySelector('[data-avatar-photo-remove]');
    var message = root.querySelector('[data-profile-avatar-message]');
    var removeForm = root.querySelector('[data-profile-avatar-remove-form]');
    var dropzone = root.querySelector('[data-avatar-dropzone]');
    var objectUrl = '';
    var cropModal = root.parentNode.querySelector('[data-avatar-crop-modal]');
    var crop = window.TNetIdentityAvatarPhoto && window.TNetIdentityAvatarPhoto.mountCrop({
      modal: cropModal,
      getSource: function () { return preview && preview.src; },
      onApply: function (blob) {
        var transfer = new DataTransfer();
        transfer.items.add(new File([blob], 'profile-avatar.jpg', { type: 'image/jpeg' }));
        file.files = transfer.files;
        preview.src = URL.createObjectURL(blob);
        submit.hidden = false;
        message.textContent = '';
      },
      onError: function (error) { message.textContent = error.message; }
    });

    function selectPhoto(selected) {
      if (!selected) return;
      if (!/^image\/(jpeg|png|webp)$/.test(selected.type) || selected.size > 5242880) {
        message.textContent = 'Choose a JPG, PNG, or WebP image no larger than 5 MB.';
        file.value = '';
        return;
      }
      if (objectUrl) URL.revokeObjectURL(objectUrl);
      objectUrl = URL.createObjectURL(selected);
      var image = new Image();
      image.onload = function () {
        if (image.naturalWidth < 64 || image.naturalHeight < 64 || image.naturalWidth > 2048 || image.naturalHeight > 2048) {
          message.textContent = 'Image dimensions must be between 64px and 2048px.';
          file.value = '';
          URL.revokeObjectURL(objectUrl);
          objectUrl = '';
          return;
        }
        preview.src = objectUrl;
        preview.hidden = false;
        placeholder.hidden = true;
        dropCopy.hidden = true;
        tools.hidden = false;
        label.textContent = 'Change photo';
        dropzone.classList.add('has-photo');
        submit.hidden = false;
        message.textContent = '';
      };
      image.onerror = function () {
        message.textContent = 'The selected image could not be read.';
        file.value = '';
      };
      image.src = objectUrl;
    }

    file.addEventListener('change', function () { selectPhoto(file.files && file.files[0]); });
    if (cropButton) cropButton.addEventListener('click', function () { if (crop) crop.open(); });
    if (dropzone) {
      ['dragenter', 'dragover'].forEach(function (name) {
        dropzone.addEventListener(name, function (event) { event.preventDefault(); dropzone.classList.add('is-dragover'); });
      });
      ['dragleave', 'drop'].forEach(function (name) {
        dropzone.addEventListener(name, function (event) { event.preventDefault(); dropzone.classList.remove('is-dragover'); });
      });
      dropzone.addEventListener('drop', function (event) {
        var dropped = event.dataTransfer && event.dataTransfer.files && event.dataTransfer.files[0];
        if (!dropped) return;
        var transfer = new DataTransfer();
        transfer.items.add(dropped);
        file.files = transfer.files;
        selectPhoto(dropped);
      });
    }
    if (removeButton) removeButton.addEventListener('click', function () {
      if (removeForm) { removeForm.requestSubmit(); return; }
      file.value = '';
      preview.removeAttribute('src');
      preview.hidden = true;
      placeholder.hidden = false;
      dropCopy.hidden = false;
      tools.hidden = true;
      submit.hidden = true;
      label.textContent = 'Choose a photo';
      dropzone.classList.remove('has-photo');
      if (objectUrl) URL.revokeObjectURL(objectUrl);
      objectUrl = '';
      message.textContent = '';
    });
    form.addEventListener('submit', function (event) {
      if (!file.files || !file.files.length) {
        event.preventDefault();
        message.textContent = 'Choose a photo before saving.';
      }
    });
  });

  document.querySelectorAll('[data-profile-avatar-modal]').forEach(function (dialog) {
    document.querySelectorAll('[data-open-avatar-editor]').forEach(function (opener) {
      opener.addEventListener('click', function () { dialog.showModal(); });
    });
    var close = dialog.querySelector('[data-avatar-dialog-close]');
    if (close) close.addEventListener('click', function () { dialog.close(); });
    if (new URLSearchParams(window.location.search).get('avatar_modal') === '1') dialog.showModal();
  });
}(window, document));
