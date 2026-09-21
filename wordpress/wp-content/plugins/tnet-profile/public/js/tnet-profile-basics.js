(function () {
  'use strict';

  function textLength(value) {
    return Array.from(value || '').length;
  }

  document.addEventListener('DOMContentLoaded', function () {
    var bio = document.querySelector('[data-tnet-profile-bio]');
    var count = document.querySelector('[data-tnet-profile-bio-count]');
    if (bio && count) {
      var updateCount = function () { count.textContent = String(textLength(bio.value)); };
      bio.addEventListener('input', updateCount);
      updateCount();
    }

    var search = document.querySelector('[data-tnet-profile-subject-search]');
    var select = document.querySelector('[data-tnet-profile-subject-select]');
    if (search && select) {
      search.addEventListener('input', function () {
        var query = search.value.trim().toLocaleLowerCase();
        Array.prototype.forEach.call(select.options, function (option) {
          option.hidden = query !== '' && option.text.toLocaleLowerCase().indexOf(query) === -1;
        });
      });
    }
  });
}());
