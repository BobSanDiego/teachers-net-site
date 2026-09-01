(function () {
  var storageKey = 'tnetJobsShellLab.v1'; // fixture adapter instrumentation only
  var defaults = { presentation: 'floating', railWidth: 250, logoWidth: 210, navHeight: 80, navLinkFont: 17, canvas: '#f4f5f7', surface: '#ffffff', pageEdge: '#cbd5e1', pageEdgeWidth: 0, navStart: '#ffffff', navEnd: '#ffffff', navText: '#102a75', gradientEnabled: false, radius: 18, shadowX: 0, shadowY: 14, shadowBlur: 42, shadowSpread: 0, shadowOpacity: 10, navShadowX: 0, navShadowY: 0, navShadowBlur: 0, navShadowSpread: 0, navShadowOpacity: 0, stress: false };

  function closeOtherShellDisclosures(current) {
    Array.prototype.forEach.call(document.querySelectorAll('.tnet-jobs-shell-lab-nav-menu.is-open, .tnet-jobs-shell-lab-account-menu.is-open, .tnet-jobs-shell-lab-employer-menu.is-open, .tnet-jobs-shell-lab-mobile-menu.is-open, .tnet-jobs-shell-lab-notification-menu.is-open'), function (other) {
      if (other !== current) {
        other.classList.remove('is-open');
        var otherToggle = other.querySelector('.tnet-jobs-shell-lab-nav-trigger, .tnet-jobs-shell-lab-disclosure-toggle, .tnet-jobs-shell-lab-notification-toggle');
        if (otherToggle) otherToggle.setAttribute('aria-expanded', 'false');
      }
    });
  }

  function initDisclosure(menu) {
    if (menu.dataset.shellLabDisclosureReady === 'true') return;
    menu.dataset.shellLabDisclosureReady = 'true';
    var toggle = menu.querySelector('.tnet-jobs-shell-lab-disclosure-toggle, .tnet-jobs-shell-lab-nav-trigger');
    if (!toggle) return;
    function close(returnFocus) { menu.classList.remove('is-open'); toggle.setAttribute('aria-expanded', 'false'); if (returnFocus) toggle.focus(); }
    function toggleMenu() { var open = !menu.classList.contains('is-open'); if (open) closeOtherShellDisclosures(menu); menu.classList.toggle('is-open', open); toggle.setAttribute('aria-expanded', open ? 'true' : 'false'); }
    toggle.addEventListener('click', function (event) { event.preventDefault(); toggleMenu(); });
    menu.addEventListener('keydown', function (event) { if (event.key === 'Escape' && menu.classList.contains('is-open')) { event.preventDefault(); close(true); } });
    document.addEventListener('click', function (event) { if (menu.classList.contains('is-open') && !menu.contains(event.target)) close(false); });
  }

  function initProductNavigation(menu) {
    if (menu.dataset.shellLabProductReady === 'true') return;
    menu.dataset.shellLabProductReady = 'true';
    var toggle = menu.querySelector('.tnet-jobs-shell-lab-nav-trigger');
    if (!toggle) return;
    function close(returnFocus) { menu.classList.remove('is-open'); toggle.setAttribute('aria-expanded', 'false'); if (returnFocus) toggle.focus(); }
    function open() {
      closeOtherShellDisclosures(menu);
      menu.classList.add('is-open'); toggle.setAttribute('aria-expanded', 'true');
    }
    toggle.addEventListener('click', function (event) { event.preventDefault(); if (menu.classList.contains('is-open')) close(false); else open(); });
    Array.prototype.forEach.call(menu.querySelectorAll('[data-product-flow-switch]'), function (switcher) {
      switcher.addEventListener('click', function (event) { event.preventDefault(); menu.dataset.productFlow = switcher.dataset.productFlowSwitch === 'jobseeker' ? 'jobseeker' : 'employer'; open(); });
    });
    menu.addEventListener('keydown', function (event) { if (event.key === 'Escape' && menu.classList.contains('is-open')) { event.preventDefault(); close(true); } });
    document.addEventListener('click', function (event) { if (menu.classList.contains('is-open') && !menu.contains(event.target)) close(false); });
  }

  function initLessonPlansNavigation(menu) {
    if (menu.dataset.shellLabLessonPlansReady === 'true') return;
    menu.dataset.shellLabLessonPlansReady = 'true';
    var toggle = menu.querySelector('.tnet-jobs-shell-lab-nav-trigger');
    var accordionTriggers = menu.querySelectorAll('[data-lesson-accordion-trigger]');
    if (!toggle) return;
    function closeAccordion(returnFocus) { var active = menu.querySelector('[data-lesson-accordion-trigger][aria-expanded="true"]'); if (active) { active.setAttribute('aria-expanded', 'false'); active.classList.remove('is-accordion-active'); var panel = menu.querySelector('#' + active.getAttribute('aria-controls')); if (panel) panel.hidden = true; if (returnFocus) active.focus(); } }
    function close(returnFocus) { closeAccordion(false); menu.classList.remove('is-open'); toggle.setAttribute('aria-expanded', 'false'); if (returnFocus) toggle.focus(); }
    function open() { closeOtherShellDisclosures(menu); menu.classList.add('is-open'); toggle.setAttribute('aria-expanded', 'true'); }
    function toggleAccordion(trigger) { if (!menu.classList.contains('is-open')) open(); var shouldOpen = trigger.getAttribute('aria-expanded') !== 'true'; closeAccordion(false); if (shouldOpen) { trigger.setAttribute('aria-expanded', 'true'); trigger.classList.add('is-accordion-active'); var panel = menu.querySelector('#' + trigger.getAttribute('aria-controls')); if (panel) panel.hidden = false; } }
    toggle.addEventListener('click', function (event) { event.preventDefault(); if (menu.classList.contains('is-open')) close(false); else open(); });
    Array.prototype.forEach.call(accordionTriggers, function (trigger) { trigger.addEventListener('click', function (event) { event.preventDefault(); toggleAccordion(trigger); }); });
    menu.addEventListener('keydown', function (event) { if (event.key === 'Escape' && menu.classList.contains('is-open')) { event.preventDefault(); close(true); } });
    document.addEventListener('click', function (event) { if (menu.classList.contains('is-open') && !menu.contains(event.target)) close(false); });
  }

  function initCompactNavigation(menu) {
    if (menu.dataset.shellLabCompactReady === 'true') return;
    menu.dataset.shellLabCompactReady = 'true';
    var toggle = menu.querySelector('.tnet-jobs-shell-lab-disclosure-toggle');
    if (!toggle) return;
    var rootPanel = menu.querySelector('[data-compact-panel="root"]');
    ['chatboards', 'lesson-plans'].forEach(function (name) {
      var resource = rootPanel && rootPanel.querySelector('[data-compact-resource="' + name.replace('-', '') + '"]');
      var button = rootPanel && rootPanel.querySelector('[data-compact-accordion-toggle="' + name + '"]');
      if (!resource && button) {
        resource = document.createElement('div');
        resource.className = 'tnet-jobs-shell-lab-compact-resource';
        resource.dataset.compactResource = name.replace('-', '');
        button.parentNode.insertBefore(resource, button);
        resource.appendChild(button);
      }
      var panel = menu.querySelector('[data-compact-panel="' + name + '"]');
      if (!resource || !panel) return;
      panel.id = 'tnet-jobs-shell-compact-' + name;
      button.setAttribute('aria-controls', panel.id);
      var back = panel.querySelector('.tnet-jobs-shell-lab-compact-back');
      if (back) back.remove();
      panel.dataset.compactAccordionPanel = name;
      panel.hidden = true;
      resource.appendChild(panel);
    });
    if (rootPanel) {
      var initialResource = rootPanel.querySelector('[data-compact-resource="' + rootPanel.dataset.compactFlow + '"]');
      // Session utilities are platform-level controls. Preserve their leading
      // position instead of deriving it from the active consumer resource.
      var utilityRow = rootPanel.querySelector('.tnet-jobs-shell-lab-compact-auth');
      if (initialResource) rootPanel.insertBefore(initialResource, utilityRow ? utilityRow.nextSibling : rootPanel.firstElementChild);
    }
    function toggleResource(button) {
      var root = rootPanel;
      var name = button.dataset.compactAccordionToggle;
      var shouldOpen = button.getAttribute('aria-expanded') !== 'true';
      Array.prototype.forEach.call(root.querySelectorAll('[data-compact-accordion-toggle]'), function (other) {
        other.setAttribute('aria-expanded', 'false');
        var panel = root.querySelector('[data-compact-accordion-panel="' + other.dataset.compactAccordionToggle + '"]');
        if (panel) panel.hidden = true;
      });
      if (shouldOpen) {
        if (name === 'employer' || name === 'jobseeker') {
          var resource = root.querySelector('[data-compact-resource="' + name.replace('-', '') + '"]');
          if (resource) root.insertBefore(resource, root.firstElementChild);
        }
        button.setAttribute('aria-expanded', 'true');
        var panel = root.querySelector('[data-compact-accordion-panel="' + name + '"]');
        if (panel) panel.hidden = false;
      }
    }
    Array.prototype.forEach.call(menu.querySelectorAll('[data-compact-taxonomy-toggle]'), function (button) {
      button.addEventListener('click', function () {
        var panel = button.closest('[data-compact-panel]');
        var shouldOpen = button.getAttribute('aria-expanded') !== 'true';
        Array.prototype.forEach.call(panel.querySelectorAll('[data-compact-taxonomy-toggle]'), function (other) {
          other.setAttribute('aria-expanded', 'false');
          var otherPanel = panel.querySelector('[data-compact-taxonomy-panel="' + other.dataset.compactTaxonomyToggle + '"]');
          if (otherPanel) otherPanel.hidden = true;
        });
        if (shouldOpen) {
          button.setAttribute('aria-expanded', 'true');
          var taxonomyPanel = panel.querySelector('[data-compact-taxonomy-panel="' + button.dataset.compactTaxonomyToggle + '"]');
          if (taxonomyPanel) taxonomyPanel.hidden = false;
        }
      });
    });
    function close(returnFocus) { menu.classList.remove('is-open'); toggle.setAttribute('aria-expanded', 'false'); if (returnFocus) toggle.focus(); }
    toggle.addEventListener('click', function (event) { event.preventDefault(); if (menu.classList.contains('is-open')) close(false); else { closeOtherShellDisclosures(menu); menu.classList.add('is-open'); toggle.setAttribute('aria-expanded', 'true'); } });
    Array.prototype.forEach.call(menu.querySelectorAll('[data-compact-accordion-toggle]'), function (button) { button.addEventListener('click', function () { toggleResource(button); }); });
    menu.addEventListener('keydown', function (event) { if (event.key === 'Escape' && menu.classList.contains('is-open')) { event.preventDefault(); close(true); } });
    document.addEventListener('click', function (event) { if (menu.classList.contains('is-open') && !menu.contains(event.target)) close(false); });
  }

  function escapeHtml(value) {
    return String(value || '').replace(/[&<>"']/g, function (character) {
      return ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' })[character];
    });
  }

  function initNotificationCenter(menu) {
    if (menu.dataset.notificationCenterReady === 'true') return;
    menu.dataset.notificationCenterReady = 'true';
    var fixture = window.TNetNotificationsFixture;
    var runtime = window.TNetNotificationsRuntime;
    var fixtureOnly = menu.getAttribute('data-notification-fixture-only') === 'true';
    var icons = window.TNetNotificationsIcons;
    var toggle = menu.querySelector('.tnet-jobs-shell-lab-notification-toggle');
    var panel = menu.querySelector('[data-notification-panel]');
    var count = menu.querySelector('[data-notification-unread-count]');
    var provider = fixtureOnly ? fixture : runtime;
    if (!provider || (fixtureOnly && !fixture.is_synthetic) || !icons || !toggle || !panel) return;
    var notifications = provider.getNotifications();
    var fixtureState = menu.getAttribute('data-notification-fixture-state');
    if (fixtureOnly && fixtureState === 'zero') notifications = [];
    var selectedFilter = 'all';
    var sourceLabels = { all: 'All', jobs: 'Jobs', chatboards: 'Chatboards', lessons: 'Lessons' };
    var sourceIcons = { jobs: 'briefcase', chatboards: 'chat', lessons: 'book' };

    function unreadCount() {
      return notifications.filter(function (item) { return item.active_state === 'active' && item.read_state === 'unread'; }).length;
    }
    function updateBadge() {
      var unread = unreadCount();
      count.textContent = String(unread);
      count.hidden = unread === 0;
      var qualifier = fixtureOnly ? ' synthetic fixture items' : ' notifications';
      toggle.setAttribute('aria-label', unread ? 'Notifications, ' + unread + ' unread' + qualifier : 'Notifications, no unread' + qualifier);
    }
    function icon(name, className) { return icons.render(name, className); }
    function semanticPresentation(item) {
      var metadata = item.metadata || {};
      var actor = metadata.like_actor || metadata.reply_author || '';
      if (!actor && item.event_type === 'like.added') actor = String(metadata.payload || '').replace(/\s+liked your post:?\s*$/i, '');
      if (!actor && item.event_type === 'reply.created') actor = String(metadata.payload || '').replace(/^New reply from\s+/i, '');
      if (item.event_type === 'like.added') {
        return { icon: 'heart', primary: (actor || 'Someone') + ' liked your post:', context: metadata.topic_title || metadata.meta || '' };
      }
      if (item.event_type === 'reply.created') {
        return { icon: 'reply', primary: (actor || 'Someone') + ' replied to your post:', context: metadata.reply_excerpt || metadata.meta || '' };
      }
      return { icon: item.event_icon || 'system', primary: metadata.payload || 'You have a new notification.', context: metadata.meta || '' };
    }
    function identity(item) {
      if (item.actor && item.actor.avatar) {
        return '<span class="tnet-jobs-shell-lab-notification-avatar is-fixture-avatar tnet-jobs-shell-lab-notification-avatar--' + escapeHtml(item.actor.avatar.tone) + '" data-notification-actor-avatar="fixture" aria-label="Fixture avatar for ' + escapeHtml(item.actor.display_name) + '">' + escapeHtml(item.actor.avatar.initials) + '<span class="tnet-jobs-shell-lab-notification-event-badge">' + icon(item.event_icon, 'tnet-jobs-shell-lab-notification-event-icon') + '</span></span>';
      }
      return '<span class="tnet-jobs-shell-lab-notification-avatar is-source-fallback" data-notification-source-fallback="' + escapeHtml(item.source_product) + '" aria-label="' + escapeHtml(sourceLabels[item.source_product]) + ' source fallback">' + icon(sourceIcons[item.source_product] || 'system', 'tnet-jobs-shell-lab-notification-source-icon') + '<span class="tnet-jobs-shell-lab-notification-event-badge">' + icon(item.event_icon, 'tnet-jobs-shell-lab-notification-event-icon') + '</span></span>';
    }
    function row(item) {
      var unread = item.read_state === 'unread';
      var presentation = semanticPresentation(item);
      var label = presentation.primary + ' ' + presentation.context + ' ' + (unread ? 'Unread.' : 'Read.') + ' Notification destination: ' + (item.destination.key || '') + '.';
      var href = item.destination && item.destination.href ? item.destination.href : '#';
      return '<a class="tnet-jobs-shell-lab-notification-row' + (unread ? ' is-unread' : '') + '" href="' + escapeHtml(href) + '" data-notification-id="' + escapeHtml(item.notification_id) + '" data-notification-destination="' + escapeHtml(href) + '" aria-label="' + escapeHtml(label) + '">' +
        identity(Object.assign({}, item, { event_icon: presentation.icon })) +
        '<span class="tnet-jobs-shell-lab-notification-copy"><span class="tnet-jobs-shell-lab-notification-payload">' + escapeHtml(presentation.primary) + '</span><span class="tnet-jobs-shell-lab-notification-meta">' + escapeHtml(presentation.context) + '</span><span class="tnet-jobs-shell-lab-notification-time">' + escapeHtml(item.display_time) + '</span></span>' +
        (unread ? '<span class="tnet-jobs-shell-lab-notification-unread-dot" aria-hidden="true"></span>' : '<span class="tnet-jobs-shell-lab-notification-read-spacer" aria-hidden="true"></span>') +
      '</a>';
    }
    function group(name, items) {
      if (!items.length) return '';
      return '<section class="tnet-jobs-shell-lab-notification-group" aria-label="' + (name === 'new' ? 'New notifications' : 'Earlier notifications') + '"><h3>' + (name === 'new' ? 'NEW' : 'EARLIER') + '</h3>' + items.map(row).join('') + '</section>';
    }
    function render(message) {
      var visible = notifications.filter(function (item) { return selectedFilter === 'all' || item.source_product === selectedFilter; });
      var newItems = visible.filter(function (item) { return item.group === 'new'; });
      var earlierItems = visible.filter(function (item) { return item.group === 'earlier'; });
      panel.innerHTML = '<div class="tnet-jobs-shell-lab-notification-header"><h2 id="tnet-jobs-shell-notification-title">Notifications</h2><span class="tnet-jobs-shell-lab-notification-actions"><button type="button" class="tnet-jobs-shell-lab-notification-more" aria-label="More notification actions" aria-expanded="false" aria-controls="tnet-jobs-shell-notification-actions-menu">···</button><span id="tnet-jobs-shell-notification-actions-menu" class="tnet-jobs-shell-lab-notification-actions-menu" role="menu" hidden><button type="button" role="menuitem" data-notification-mark-all>Mark all as read</button><a role="menuitem" href="/notifications/">Open Notifications</a></span></span></div>' +
        '<div class="tnet-jobs-shell-lab-notification-filters" role="group" aria-label="Filter synthetic notifications">' + Object.keys(sourceLabels).map(function (key) {
          return '<button type="button" class="tnet-jobs-shell-lab-notification-filter' + (selectedFilter === key ? ' is-selected' : '') + '" data-notification-filter="' + key + '" aria-pressed="' + (selectedFilter === key ? 'true' : 'false') + '">' + sourceLabels[key] + '</button>';
        }).join('') + '</div>' +
        '<div class="tnet-jobs-shell-lab-notification-feed" data-notification-feed>' + group('new', newItems) + group('earlier', earlierItems) + (visible.length ? '' : '<p class="tnet-jobs-shell-lab-notification-empty">' + (fixtureState === 'zero' ? 'You’re all caught up. New notifications will appear here.' : 'No synthetic notifications in this filter.') + '</p>') + '</div>' +
        '<p class="screen-reader-text" aria-live="polite" data-notification-status>' + escapeHtml(message || 'Synthetic fixture notification center loaded.') + '</p>';
      Array.prototype.forEach.call(panel.querySelectorAll('[data-notification-filter]'), function (button) {
        button.addEventListener('click', function (event) { event.stopPropagation(); selectedFilter = button.dataset.notificationFilter; render('Showing ' + sourceLabels[selectedFilter] + ' synthetic fixture notifications.'); });
      });
      Array.prototype.forEach.call(panel.querySelectorAll('[data-notification-id]'), function (button) {
        button.addEventListener('click', function (event) {
          event.preventDefault(); // Fixture-only interception preserves the browser-visible mark-one-read proof.
          event.stopPropagation(); // Rendering replaces the clicked row before the shared outside-dismissal listener runs.
          var item = notifications.filter(function (candidate) { return String(candidate.notification_id) === String(button.dataset.notificationId); })[0];
          if (!item) return;
          if (fixtureOnly) {
            item.read_state = 'read';
            item.read_at = 'fixture-session';
            updateBadge();
            render('Synthetic fixture notification marked read.');
          } else if (provider.markRead) {
            provider.markRead(item.notification_id).then(function () { item.read_state = 'read'; item.read_at = 'runtime-session'; updateBadge(); window.location.assign(href); }).catch(function () { window.location.assign(href); });
          }
        });
      });
      var more = panel.querySelector('[aria-controls="tnet-jobs-shell-notification-actions-menu"]'), actions = panel.querySelector('#tnet-jobs-shell-notification-actions-menu'), markAll = panel.querySelector('[data-notification-mark-all]');
      if (more && actions) {
        more.addEventListener('click', function (event) { event.stopPropagation(); var open = !actions.hidden; actions.hidden = open; more.setAttribute('aria-expanded', open ? 'false' : 'true'); });
        actions.addEventListener('click', function (event) { event.stopPropagation(); });
        markAll.addEventListener('click', function () { if (unreadCount() === 0 || !provider.markAllRead) return; provider.markAllRead().then(function () { notifications.forEach(function (item) { item.read_state = 'read'; item.read_at = item.read_at || 'runtime-session'; }); actions.hidden = true; more.setAttribute('aria-expanded', 'false'); updateBadge(); render('All notifications marked read.'); }); });
        actions.addEventListener('keydown', function (event) { if (event.key === 'Escape') { event.preventDefault(); event.stopPropagation(); actions.hidden = true; more.setAttribute('aria-expanded', 'false'); more.focus(); } });
        document.addEventListener('click', function (event) { if (!actions.hidden && !actions.contains(event.target) && event.target !== more) { actions.hidden = true; more.setAttribute('aria-expanded', 'false'); } });
      }
    }
    function close(returnFocus) { menu.classList.remove('is-open'); toggle.setAttribute('aria-expanded', 'false'); if (returnFocus) toggle.focus(); }
    function open() { closeOtherShellDisclosures(menu); menu.classList.add('is-open'); toggle.setAttribute('aria-expanded', 'true'); }
    updateBadge();
    render();
    if (!fixtureOnly && provider.load) provider.load().then(function (loaded) { notifications = loaded; updateBadge(); render('Notifications loaded.'); });
    toggle.addEventListener('click', function (event) { event.preventDefault(); if (menu.classList.contains('is-open')) close(false); else open(); });
    document.addEventListener('keydown', function (event) { if (event.key === 'Escape' && menu.classList.contains('is-open')) { event.preventDefault(); close(true); } });
    document.addEventListener('click', function (event) { if (menu.classList.contains('is-open') && !menu.contains(event.target)) close(false); });
  }

  function safeRead() { try { return JSON.parse(window.localStorage.getItem(storageKey)) || { settings: {}, candidates: {} }; } catch (error) { return { settings: {}, candidates: {} }; } }
  function safeWrite(value) { try { window.localStorage.setItem(storageKey, JSON.stringify(value)); } catch (error) {} }
  function validHex(value) { return typeof value === 'string' && /^#[0-9a-f]{6}$/i.test(value); }
  function number(value, fallback) { var parsed = Number(value); return Number.isFinite(parsed) ? parsed : fallback; }

  function initLab(root) {
    if (root.dataset.shellClean === 'true') return;
    var state = safeRead();
    var settingInputs = root.querySelectorAll('[data-shell-setting]');
    var hexInputs = root.querySelectorAll('[data-shell-setting-hex]');
    var modeButtons = root.querySelectorAll('[data-shell-presentation-control]');
    var status = root.querySelector('[data-shell-lab-status]');
    var candidateName = root.querySelector('#shell-lab-candidate-name');
    var candidateSelect = root.querySelector('[data-shell-candidate-select]');
    var candidateSave = root.querySelector('[data-shell-candidate-save]');
    var candidateLoad = root.querySelector('[data-shell-candidate-load]');
    var candidateDelete = root.querySelector('[data-shell-candidate-delete]');
    var candidateReset = root.querySelector('[data-shell-candidate-reset]');
    var settingsCopy = root.querySelector('[data-shell-settings-copy]');
    var initialPresentation = root.dataset.shellPresentation || defaults.presentation;
    var settings = Object.assign({}, defaults, state.settings || {}, { presentation: initialPresentation });

    function announce(message) { if (status) status.textContent = message; }
    function currentSettings() { return Object.assign({}, settings); }
    function updateOutputs() { Array.prototype.forEach.call(root.querySelectorAll('[data-shell-output]'), function (output) { var key = output.dataset.shellOutput; output.value = /Opacity$/.test(key) ? (number(settings[key], defaults[key]) / 100).toFixed(2).replace(/^0/, '') : number(settings[key], defaults[key]) + 'px'; output.textContent = output.value; }); }
    function syncInputs() {
      Array.prototype.forEach.call(settingInputs, function (input) { var value = settings[input.dataset.shellSetting]; if (value !== undefined) { if (input.type === 'checkbox') input.checked = Boolean(value); else input.value = value; } });
      Array.prototype.forEach.call(hexInputs, function (input) { var key = input.dataset.shellSettingHex; if (settings[key] !== undefined) input.value = settings[key]; });
      Array.prototype.forEach.call(modeButtons, function (button) { button.setAttribute('aria-pressed', button.dataset.shellPresentationControl === settings.presentation ? 'true' : 'false'); });
      updateOutputs();
    }
    function apply() {
      root.dataset.shellPresentation = settings.presentation;
      root.dataset.shellGradient = settings.gradientEnabled ? 'on' : 'off';
      root.classList.toggle('is-shell-lab-stress', Boolean(settings.stress));
      root.style.setProperty('--shell-lab-canvas', settings.canvas); root.style.setProperty('--shell-lab-surface', settings.surface); root.style.setProperty('--shell-lab-page-edge', settings.pageEdge); root.style.setProperty('--shell-lab-page-edge-width', number(settings.pageEdgeWidth, defaults.pageEdgeWidth) + 'px'); root.style.setProperty('--shell-lab-nav-start', settings.navStart); root.style.setProperty('--shell-lab-nav-end', settings.navEnd); root.style.setProperty('--shell-lab-nav-text', settings.navText);
      root.style.setProperty('--shell-lab-rail-width', number(settings.railWidth, defaults.railWidth) + 'px'); root.style.setProperty('--shell-lab-logo-width', number(settings.logoWidth, defaults.logoWidth) + 'px'); root.style.setProperty('--shell-lab-nav-height', number(settings.navHeight, defaults.navHeight) + 'px'); root.style.setProperty('--shell-lab-nav-link-font', number(settings.navLinkFont, defaults.navLinkFont) + 'px');
      root.style.setProperty('--shell-lab-radius', number(settings.radius, defaults.radius) + 'px'); root.style.setProperty('--shell-lab-shadow-x', number(settings.shadowX, defaults.shadowX) + 'px'); root.style.setProperty('--shell-lab-shadow-y', number(settings.shadowY, defaults.shadowY) + 'px'); root.style.setProperty('--shell-lab-shadow-blur', number(settings.shadowBlur, defaults.shadowBlur) + 'px'); root.style.setProperty('--shell-lab-shadow-spread', number(settings.shadowSpread, defaults.shadowSpread) + 'px'); root.style.setProperty('--shell-lab-shadow-opacity', (number(settings.shadowOpacity, defaults.shadowOpacity) / 100).toFixed(2));
      root.style.setProperty('--shell-lab-nav-shadow-x', number(settings.navShadowX, defaults.navShadowX) + 'px'); root.style.setProperty('--shell-lab-nav-shadow-y', number(settings.navShadowY, defaults.navShadowY) + 'px'); root.style.setProperty('--shell-lab-nav-shadow-blur', number(settings.navShadowBlur, defaults.navShadowBlur) + 'px'); root.style.setProperty('--shell-lab-nav-shadow-spread', number(settings.navShadowSpread, defaults.navShadowSpread) + 'px'); root.style.setProperty('--shell-lab-nav-shadow-opacity', (number(settings.navShadowOpacity, defaults.navShadowOpacity) / 100).toFixed(2));
      document.body.style.backgroundColor = settings.canvas;
      if (settings.presentation !== 'floating') root.style.setProperty('--shell-lab-radius', '0px');
      Array.prototype.forEach.call(root.querySelectorAll('.tnet-jobs-shell-lab-fixture-switcher a'), function (link) {
        var url = new URL(link.href); url.searchParams.set('shell_mode', settings.presentation); link.href = url.toString();
      });
      var route = root.querySelector('.tnet-jobs-shell-lab-route');
      if (route) {
        Array.prototype.forEach.call(route.querySelectorAll('[data-shell-lab-stress-instance]'), function (node) { node.remove(); });
        if (settings.stress) {
          var template = route.querySelector('template[data-shell-lab-stress-template]');
          var target = route.dataset.consumer === 'dashboard-list' ? route.querySelector('tbody') : route.querySelector('.tnet-jobs-shell-lab-form');
          if (template && target) for (var index = 0; index < 8; index += 1) { var fragment = template.content.cloneNode(true); Array.prototype.forEach.call(fragment.querySelectorAll('[data-shell-lab-stress-row], [data-shell-lab-stress-section]'), function (node) { node.setAttribute('data-shell-lab-stress-instance', 'true'); }); target.appendChild(fragment); }
        }
      }
      syncInputs(); state.settings = currentSettings(); safeWrite(state);
    }
    function setPresentation(presentation, updateUrl) {
      settings.presentation = ['floating', 'flush', 'pinned'].indexOf(presentation) >= 0 ? presentation : defaults.presentation;
      apply();
      if (updateUrl) { var url = new URL(window.location.href); url.searchParams.set('shell_mode', settings.presentation); window.history.replaceState({}, '', url.toString()); }
    }
    function refreshCandidates() {
      if (!candidateSelect) return;
      candidateSelect.innerHTML = '<option value="">Saved candidates</option>';
      Object.keys(state.candidates || {}).sort().forEach(function (name) { var option = document.createElement('option'); option.value = name; option.textContent = name; candidateSelect.appendChild(option); });
    }
    Array.prototype.forEach.call(modeButtons, function (button) { button.addEventListener('click', function () { setPresentation(button.dataset.shellPresentationControl, true); announce(button.textContent + ' comparison loaded.'); }); });
    Array.prototype.forEach.call(root.querySelectorAll('[data-shell-rail-preset]'), function (button) { button.addEventListener('click', function () { var input = root.querySelector('[data-shell-setting="railWidth"]'); input.value = button.dataset.shellRailPreset; input.dispatchEvent(new Event('input', { bubbles: true })); }); });
    Array.prototype.forEach.call(settingInputs, function (input) { input.addEventListener(input.type === 'checkbox' ? 'change' : 'input', function () { settings[input.dataset.shellSetting] = input.type === 'color' ? input.value.toLowerCase() : input.type === 'checkbox' ? input.checked : number(input.value, defaults[input.dataset.shellSetting]); apply(); }); });
    Array.prototype.forEach.call(hexInputs, function (input) { input.addEventListener('change', function () { var key = input.dataset.shellSettingHex; if (validHex(input.value)) { settings[key] = input.value.toLowerCase(); apply(); } else { input.value = settings[key]; announce('Use a six-digit hex color.'); } }); });
    if (candidateSave && candidateName && candidateSelect) candidateSave.addEventListener('click', function () { var name = (candidateName.value || '').trim(); if (!name) { announce('Name the candidate before saving.'); candidateName.focus(); return; } state.candidates = state.candidates || {}; state.candidates[name] = currentSettings(); safeWrite(state); refreshCandidates(); candidateSelect.value = name; announce(name + ' saved locally.'); });
    if (candidateLoad && candidateName && candidateSelect) candidateLoad.addEventListener('click', function () { var candidate = state.candidates && state.candidates[candidateSelect.value]; if (!candidate) { announce('Choose a saved candidate first.'); return; } settings = Object.assign({}, defaults, candidate); setPresentation(settings.presentation, true); candidateName.value = candidateSelect.value; announce(candidateSelect.value + ' loaded.'); });
    if (candidateDelete && candidateSelect) candidateDelete.addEventListener('click', function () { var name = candidateSelect.value; if (!name || !state.candidates || !state.candidates[name]) { announce('Choose a saved candidate first.'); return; } delete state.candidates[name]; safeWrite(state); refreshCandidates(); announce(name + ' deleted.'); });
    if (candidateReset) candidateReset.addEventListener('click', function () { settings = Object.assign({}, defaults); setPresentation(defaults.presentation, true); announce('Baseline restored.'); });
    if (settingsCopy) settingsCopy.addEventListener('click', function () {
      var summary = 'presentation=' + settings.presentation + '; rail=' + settings.railWidth + 'px; logo=' + settings.logoWidth + 'px; navbarHeight=' + settings.navHeight + 'px; navLinkFont=' + settings.navLinkFont + 'px; canvas=' + settings.canvas + '; pageFrame=' + settings.surface + '; navGradient=' + (settings.gradientEnabled ? 'on' : 'off') + '; nav=' + settings.navStart + '>' + settings.navEnd + '; navText=' + settings.navText + '; radius=' + settings.radius + 'px; applicationShadow=' + settings.shadowX + 'px ' + settings.shadowY + 'px ' + settings.shadowBlur + 'px ' + settings.shadowSpread + 'px rgba(35,48,68,' + (settings.shadowOpacity / 100).toFixed(2) + '); navbarShadow=' + settings.navShadowX + 'px ' + settings.navShadowY + 'px ' + settings.navShadowBlur + 'px ' + settings.navShadowSpread + 'px rgba(22,37,73,' + (settings.navShadowOpacity / 100).toFixed(2) + '); scrollStress=' + (settings.stress ? 'on' : 'off');
      if (navigator.clipboard && navigator.clipboard.writeText) navigator.clipboard.writeText(summary).then(function () { announce('Settings copied.'); }, function () { announce(summary); }); else announce(summary);
    });
    refreshCandidates(); apply();
  }

  function init() {
    Array.prototype.forEach.call(document.querySelectorAll('[data-notification-center]'), initNotificationCenter);
    Array.prototype.forEach.call(document.querySelectorAll('.tnet-jobs-shell-lab-account-menu, .tnet-jobs-shell-lab-employer-menu, .tnet-jobs-shell-lab-nav-menu:not(.tnet-jobs-shell-lab-product-menu):not(.tnet-jobs-shell-lab-lessonplans-menu):not(.tnet-jobs-shell-lab-chatboards-menu)'), initDisclosure);
    Array.prototype.forEach.call(document.querySelectorAll('.tnet-jobs-shell-lab-product-menu'), initProductNavigation);
    Array.prototype.forEach.call(document.querySelectorAll('.tnet-jobs-shell-lab-lessonplans-menu, .tnet-jobs-shell-lab-chatboards-menu'), initLessonPlansNavigation);
    Array.prototype.forEach.call(document.querySelectorAll('.tnet-jobs-shell-lab-mobile-menu'), initCompactNavigation);
    Array.prototype.forEach.call(document.querySelectorAll('[data-shell-adapter="shell-lab"]'), initLab);
  }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init, { once: true }); else init();
}());
