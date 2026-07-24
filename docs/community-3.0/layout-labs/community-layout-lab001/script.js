(() => {
  const body = document.body;
  const shell = document.querySelector('.shell-region');
  const readout = name => document.querySelector(`[data-readout="${name}"]`);
  const modeLabel = mode => mode === 'none' ? 'No max-width' : `${mode}px max-width`;
  function update() {
    const shellRect = shell.getBoundingClientRect();
    const center = shell.querySelector('.content-column').getBoundingClientRect();
    const rail = getComputedStyle(shell.querySelector('.right-rail')).display !== 'none';
    readout('viewport').textContent = `${window.innerWidth}px`;
    readout('mode').textContent = modeLabel(body.dataset.shellMode);
    readout('shell').textContent = `${Math.round(shellRect.width)}px`;
    readout('center').textContent = `${Math.round(center.width)}px`;
    readout('rail').textContent = rail ? 'yes (300px)' : 'no';
  }
  document.querySelectorAll('[data-mode]').forEach(button => button.addEventListener('click', () => {
    body.dataset.shellMode = button.dataset.mode;
    document.querySelectorAll('[data-mode]').forEach(item => item.classList.toggle('selected', item === button));
    update();
  }));
  window.addEventListener('resize', update);
  update();
})();
