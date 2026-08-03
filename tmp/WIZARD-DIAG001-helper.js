// Non-production browser-console diagnostic helper for WIZARD-DIAG001.
// Paste into the canonical workbench console; it changes no DOM or styles.
(async () => {
  const views = ["step-01-initial", "step-01-add-school-us", "step-02-job-basics", "step-03-job-description", "step-04-application-process"];
  const box = (node) => {
    if (!node) return null;
    const style = getComputedStyle(node), rect = node.getBoundingClientRect();
    return { tag: node.tagName, className: String(node.className), rect: { x: rect.x, y: rect.y, width: rect.width, height: rect.height }, display: style.display, position: style.position, font: style.font, lineHeight: style.lineHeight, marginBlockStart: style.marginBlockStart, marginBlockEnd: style.marginBlockEnd, paddingBlockStart: style.paddingBlockStart, paddingBlockEnd: style.paddingBlockEnd, borders: [style.borderTopWidth, style.borderBottomWidth], minHeight: style.minHeight, transform: style.transform, zoom: style.zoom, whiteSpace: style.whiteSpace, letterSpacing: style.letterSpacing };
  };
  const measure = () => {
    const panel = [...document.querySelectorAll("[data-view]")].find((node) => !node.hidden && node.id);
    const heading = panel?.querySelector(".wizard-stage-heading, .panel-heading");
    const eyebrow = heading?.querySelector(".eyebrow"), h2 = heading?.querySelector("h2"), paragraphs = [...(heading?.querySelectorAll(":scope > p") || [])], support = paragraphs.at(-1);
    const firstContent = [...(panel?.children || [])].find((node) => node !== heading && node.getBoundingClientRect().height > 0);
    const panelRect = panel?.getBoundingClientRect(), eyebrowRect = eyebrow?.getBoundingClientRect(), h2Rect = h2?.getBoundingClientRect(), supportRect = support?.getBoundingClientRect(), contentRect = firstContent?.getBoundingClientRect();
    return { url: location.href, build: document.documentElement.dataset.workbenchBuild, hierarchy: heading?.outerHTML, panel: box(panel), heading: box(heading), eyebrow: box(eyebrow), h2: box(h2), support: box(support), firstContent: box(firstContent), gaps: { panelToEyebrow: eyebrowRect?.top - panelRect?.top, eyebrowToH2: h2Rect?.top - eyebrowRect?.bottom, h2ToSupport: supportRect?.top - h2Rect?.bottom, supportToContent: contentRect?.top - supportRect?.bottom, headingHeight: heading?.getBoundingClientRect().height }, fontStatus: document.fonts.status, timestamp: new Date().toISOString() };
  };
  const output = {};
  for (const view of views) { location.hash = view; await new Promise((resolve) => setTimeout(resolve, 300)); output[view] = measure(); }
  console.log(JSON.stringify(output, null, 2));
})();
