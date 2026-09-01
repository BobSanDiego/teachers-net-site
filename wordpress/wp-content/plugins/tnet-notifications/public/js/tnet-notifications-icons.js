/* Shared Teachers.Net notification presentation icons. */
(function (window) {
  'use strict';
  var paths = {
    briefcase: '<path d="M8 7V5.8A1.8 1.8 0 0 1 9.8 4h4.4A1.8 1.8 0 0 1 16 5.8V7"/><rect x="3.5" y="7" width="17" height="12.5" rx="1.8"/><path d="M3.5 11.5h17M10 14h4"/>',
    chat: '<path d="M5 5.5h14a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2h-8l-4.5 3v-3H5a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2Z"/><path d="M8 10.8h8M8 13.7h5"/>',
    book: '<path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H11v16H6.5A2.5 2.5 0 0 0 4 21.5Z"/><path d="M20 5.5A2.5 2.5 0 0 0 17.5 3H13v16h4.5a2.5 2.5 0 0 1 2.5 2.5Z"/>',
    reply: '<path d="M9.2 8 4.5 12l4.7 4"/><path d="M5 12h8.2a5.3 5.3 0 0 1 5.3 5.3V19"/>',
    heart: '<path d="M20.8 8.7c0 5.2-8.8 10.4-8.8 10.4S3.2 13.9 3.2 8.7A4.7 4.7 0 0 1 12 6.3a4.7 4.7 0 0 1 8.8 2.4Z"/>',
    reaction: '<path d="M7.5 21H5a2 2 0 0 1-2-2v-6a2 2 0 0 1 2-2h2.5"/><path d="M7.5 21h8.2a2.6 2.6 0 0 0 2.5-2l1.1-5.2A2.3 2.3 0 0 0 17 11h-3V6.5A2.5 2.5 0 0 0 11.5 4L7.5 11v10Z"/>',
    mention: '<circle cx="12" cy="12" r="8.5"/><path d="M15.8 15.8v-4a3.8 3.8 0 1 0-1.2 2.7"/>',
    approved: '<circle cx="12" cy="12" r="8.5"/><path d="m8.3 12.1 2.4 2.5 5.2-5.3"/>',
    warning: '<path d="M11.2 4.8 3.8 18a1.5 1.5 0 0 0 1.3 2.2h13.8a1.5 1.5 0 0 0 1.3-2.2L12.8 4.8a.9.9 0 0 0-1.6 0Z"/><path d="M12 9v4.5M12 17h.01"/>',
    bookmark: '<path d="M6 4.5A1.5 1.5 0 0 1 7.5 3h9A1.5 1.5 0 0 1 18 4.5v16l-6-3.8-6 3.8Z"/>',
    match: '<path d="m12 3 1.8 5.2L19 10l-5.2 1.8L12 17l-1.8-5.2L5 10l5.2-1.8Z"/>',
    update: '<path d="M19 9V4l-2 2A7.5 7.5 0 1 0 19.2 14"/><path d="M19 4v5h-5"/>',
    system: '<rect x="4" y="4" width="16" height="16" rx="3"/><path d="M8 12h8M12 8v8"/>'
  };
  function render(name, className) { var path = paths[name] || paths.system; return '<svg class="' + (className || '') + '" viewBox="0 0 24 24" aria-hidden="true" focusable="false" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8">' + path + '</svg>'; }
  window.TNetNotificationsIcons = Object.freeze({ render: render, names: Object.freeze(Object.keys(paths)) });
}(window));
