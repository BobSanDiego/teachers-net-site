(() => {
  const workbenchBuild = document.documentElement.dataset.workbenchBuild || "1";
  const schoolJobsiteFixture = {
    full_name: "Los Angeles Unified School District",
    display_name: "LAUSD",
  };
  const applySchoolJobsiteDisplayNameRule = () => {
    const initial = document.querySelector("#initial-school");
    if (initial && !initial.querySelector("option[data-full-name]")) {
      const option = document.createElement("option");
      option.value = "lausd";
      option.textContent = schoolJobsiteFixture.display_name;
      option.dataset.fullName = schoolJobsiteFixture.full_name;
      initial.append(option);
    }
    ["#selected-school-configured", "#selected-school"].forEach((selector) => {
      const option = document.querySelector(`${selector} option`);
      if (!option) return;
      option.textContent = schoolJobsiteFixture.display_name;
      option.dataset.fullName = schoolJobsiteFixture.full_name;
    });
  };
  applySchoolJobsiteDisplayNameRule();
  document
    .querySelectorAll(".main-workspace .info-callout")
    .forEach((callout) => callout.remove());
  const addSchoolPanel = document.querySelector("#step-01-add-school-us");
  addSchoolPanel.querySelector(
    ".jobsite-information-section .form-section-heading p",
  ).textContent =
    "Enter details for the organization posting this job. Job-specific details will be added in the next steps.";
  const views = [
    ["step-01-initial", "Step 1 — Initial"],
    ["step-01-add-school-us", "Step 1 — Add School (U.S.)"],
    ["step-01-add-school-international", "Step 1 — Add School (International)"],
    ["step-01-school-selected", "Step 1 — School Selected"],
    ["step-01-return", "Step 1 — Return"],
    ["wizard-authority-v1", "Authority — Wizard UI v1"],
    ["step-02-job-basics", "Step 2 — Job Basics"],
    ["step-03-job-description", "Step 3 — Job Description"],
    ["step-03-clipboard-diagnostics", "Step 3 — Clipboard Diagnostics"],
    ["step-04-application-process", "Step 4 — Application Process"],
    ["step-05-review-publish", "Step 5 — Review & Publish"],
  ];
  const select = document.querySelector("#view-select"),
    status = document.querySelector("#view-status"),
    panel = document.querySelector("#step-01-return"),
    placeholder = document.querySelector("#placeholder");
  const jobBasicsPanel = document
    .querySelector("#step-01-return-legacy")
    .cloneNode(true);
  jobBasicsPanel.id = "step-02-job-basics";
  jobBasicsPanel.dataset.view = "step-02-job-basics";
  jobBasicsPanel.hidden = true;
  jobBasicsPanel.innerHTML =
    '<div class="job-basics-heading"><span class="form-section-number">2</span><div><h2>Job Basics</h2><p>Tell us the key details about this position. These help teachers find your job.</p></div></div><section class="position-classification-section"><div class="position-section-title"><span class="section-icon" aria-hidden="true"><svg viewBox="0 0 24 24" focusable="false"><use href="#section-briefcase"></use></svg></span><h3>Position &amp; Classification</h3></div><div class="job-basics-form"><div class="form-field job-title-field"><label for="job-title-step2">Job Title <span aria-hidden="true">*</span></label><input id="job-title-step2" type="text" placeholder="e.g., Grade 5 Math Teacher"></div><div class="form-field grade-level-field"><label for="grade-levels-step2">Grade Level(s) <small>(Optional)</small></label><select id="grade-levels-step2"><option>Select grade levels</option></select></div><div class="form-field subject-area-field"><label for="subject-areas-step2">Subject Area(s) <small>(Optional)</small></label><select id="subject-areas-step2"><option>Select subject areas</option></select></div></div></section>';
  const step2BaseMarkup = jobBasicsPanel.innerHTML;
  document.querySelector("#step-01-return-legacy").after(jobBasicsPanel);
  const authorityPanel = document.createElement("article");
  authorityPanel.className = "panel";
  authorityPanel.id = "wizard-authority-v1";
  authorityPanel.dataset.view = "step-02-job-basics";
  authorityPanel.dataset.authorityState = "wizard-authority-v1";
  authorityPanel.hidden = true;
  jobBasicsPanel.after(authorityPanel);
  const employmentTypeField =
    '<div class="form-field employment-type-field"><label for="employment-type-step2">Employment Type <span aria-hidden="true">*</span></label><select id="employment-type-step2" required><option value="" selected>Select employment type</option><option value="Full-time">Full-time</option><option value="Part-time">Part-time</option><option value="Contract">Contract</option><option value="Temporary">Temporary</option><option value="Substitute">Substitute</option><option value="Internship">Internship</option><option value="Volunteer">Volunteer</option></select></div>';
  jobBasicsPanel
    .querySelector(".grade-level-field")
    .insertAdjacentHTML("beforebegin", employmentTypeField);
  jobBasicsPanel.querySelector(".position-section-title h3").textContent =
    "Position";
  jobBasicsPanel
    .querySelector(".position-classification-section")
    .insertAdjacentHTML(
      "afterend",
      '<section class="work-location-section"><div class="position-section-title"><span class="section-icon" aria-hidden="true"><svg viewBox="0 0 24 24" focusable="false"><use href="#section-location-pin"></use></svg></span><h3>Work Location</h3></div><div class="job-basics-form work-location-form"><div class="form-field work-location-field"><select id="work-location-step2" aria-label="Work Location" required><option value="Use School / Jobsite Location" selected>Use School / Jobsite Location</option><option value="Different On-site Location">Different On-site Location</option><option value="Remote">Remote</option><option value="Hybrid">Hybrid</option><option value="Multiple Locations">Multiple Locations</option></select></div></div><div class="work-location-summary" hidden><strong>Los Angeles Unified School District</strong><span>333 S. Beaudry Ave.</span><span>Los Angeles, CA 90017</span></div><div class="work-location-alternate-form" hidden><p class="work-location-alternate-guidance">Accurate location information helps teachers find this job in proximity and location-based searches.</p><div class="work-location-alternate-fields"><div class="form-field form-field-zip"><label for="work-location-zip-step2">ZIP Code <span aria-hidden="true">*</span></label><input id="work-location-zip-step2" type="text" placeholder="ZIP code" required></div><div class="form-field form-field-city"><label for="work-location-city-step2">City <span aria-hidden="true">*</span></label><input id="work-location-city-step2" type="text" placeholder="City" required></div><div class="form-field form-field-state"><label for="work-location-state-step2">State <span aria-hidden="true">*</span></label><select id="work-location-state-step2" required><option value="" selected>Select</option><option>CA</option><option>NY</option><option>TX</option><option>WA</option></select></div></div></div><div class="multiple-locations-workflow" hidden><div class="multiple-primary-summary"><span>Primary location</span><strong>Los Angeles Unified School District</strong><span>333 S. Beaudry Ave.</span><span>Los Angeles, CA 90017</span></div><div class="multiple-location-list"></div><button type="button" class="button secondary multiple-add-location">+ Add another location</button><p class="multiple-location-message" role="status" aria-live="polite" hidden></p><div class="multiple-location-editor" hidden><div class="multiple-location-fields"><div class="form-field"><label for="multiple-location-zip-step2">ZIP Code <span aria-hidden="true">*</span></label><input id="multiple-location-zip-step2" type="text" placeholder="ZIP code"></div><div class="form-field"><label for="multiple-location-city-step2">City <span aria-hidden="true">*</span></label><input id="multiple-location-city-step2" type="text" placeholder="City"></div><div class="form-field"><label for="multiple-location-state-step2">State <span aria-hidden="true">*</span></label><select id="multiple-location-state-step2"><option value="" selected>Select</option><option>CA</option><option>NY</option><option>TX</option><option>WA</option></select></div></div><div class="multiple-location-editor-actions"><button type="button" class="button primary multiple-save-location">Add location</button><button type="button" class="button secondary multiple-cancel-location">Cancel</button></div></div></div></section>',
    );
  jobBasicsPanel
    .querySelector(".work-location-section")
    .insertAdjacentHTML(
      "afterend",
      '<section class="job-start-section"><div class="position-section-title"><span class="section-icon" aria-hidden="true"><svg viewBox="0 0 24 24" focusable="false"><use href="#section-calendar"></use></svg></span><h3>Starting Date</h3></div><div class="job-basics-form job-start-form"><label class="job-start-timing-label" for="job-start-step2">Start Timing</label><div class="form-field job-start-field"><select id="job-start-step2" aria-label="Start Timing" required><option value="Immediately" selected>Immediately</option><option value="Specific Date">Specific Date</option><option value="Flexible">Flexible</option></select></div><label class="job-start-date-label" for="job-specific-date-step2" hidden>Start Date <span aria-hidden="true">*</span></label><div class="form-field job-specific-date-field" hidden><input id="job-specific-date-step2" type="date" aria-label="Start Date"></div><p class="job-start-guidance" hidden>Providing a specific start date helps applicants understand your hiring timeline.</p></div></section>',
    );
  jobBasicsPanel
    .querySelector(".job-start-section")
    .insertAdjacentHTML(
      "afterend",
      '<section class="compensation-section"><div class="position-section-title"><span class="section-icon" aria-hidden="true"><svg viewBox="0 0 24 24" focusable="false"><use href="#section-compensation"></use></svg></span><h3>Compensation</h3></div><div class="job-basics-form compensation-form"><div class="form-field salary-visibility-field"><label for="salary-visibility-step2">Salary Visibility</label><select id="salary-visibility-step2" aria-label="Salary Visibility"><option value="Show salary" selected>Show salary</option><option value="Do not show">Do not show</option><option value="Negotiable">Negotiable</option><option value="Volunteer">Volunteer</option></select></div><div class="form-field salary-minimum-field"><label for="salary-minimum-step2">Salary (Minimum) <span aria-hidden="true">*</span></label><input id="salary-minimum-step2" type="number" inputmode="numeric" min="0" step="1" placeholder="$0" aria-label="Salary (Minimum)"></div><div class="form-field salary-maximum-field" hidden><label for="salary-maximum-step2">Salary (Maximum) <small>(Optional)</small></label><input id="salary-maximum-step2" type="number" inputmode="numeric" min="0" step="1" placeholder="$0" aria-label="Salary (Maximum)"></div><div class="form-field salary-type-field" hidden><label for="salary-type-step2">Salary Type <span aria-hidden="true">*</span></label><select id="salary-type-step2" aria-label="Salary Type"><option value="" selected>Select salary type</option><option value="Annual">Annual</option><option value="Monthly">Monthly</option><option value="Weekly">Weekly</option><option value="Daily">Daily</option><option value="Hourly">Hourly</option><option value="Stipend">Stipend</option></select></div></div></section>',
    );
  const compensationSection = jobBasicsPanel.querySelector(
    ".compensation-section",
  );
  const compensationGuidance = document.createElement("p");
  compensationGuidance.className = "compensation-guidance";
  compensationGuidance.innerHTML =
    "Listings with salary information receive more qualified views and applicants.<br><span><strong>Note:</strong> Some locations require salary information in job listings by law.</span>";
  compensationGuidance.hidden = true;
  compensationSection
    .querySelector(".compensation-form")
    .append(compensationGuidance);
  const syncCompensation = () => {
    const visibility =
        document.querySelector("#salary-visibility-step2")?.value ||
        "Show salary",
      minimum = document.querySelector("#salary-minimum-step2"),
      minimumField = document.querySelector(".salary-minimum-field"),
      form = compensationSection.querySelector(".compensation-form"),
      maximumField = compensationSection.querySelector(".salary-maximum-field"),
      typeField = compensationSection.querySelector(".salary-type-field"),
      type = compensationSection.querySelector("#salary-type-step2"),
      showGuidance =
        visibility === "Do not show" || visibility === "Negotiable",
      expanded = visibility === "Show salary" && Number(minimum?.value) > 0;
    if (minimumField) minimumField.hidden = visibility !== "Show salary";
    if (minimum) minimum.required = visibility === "Show salary";
    if (form) form.classList.toggle("is-expanded", expanded);
    if (maximumField) maximumField.hidden = !expanded;
    if (typeField) typeField.hidden = !expanded;
    if (type) type.required = expanded;
    if (!expanded && type) type.value = "";
    if (compensationGuidance) compensationGuidance.hidden = !showGuidance;
  };
  syncCompensation();
  const startingDateSection =
      jobBasicsPanel.querySelector(".job-start-section"),
    startingDateSelect = startingDateSection.querySelector("#job-start-step2"),
    specificDateLabel = startingDateSection.querySelector(
      ".job-start-date-label",
    ),
    specificDateField = startingDateSection.querySelector(
      ".job-specific-date-field",
    ),
    specificDateInput = startingDateSection.querySelector(
      "#job-specific-date-step2",
    ),
    startingDateGuidance = startingDateSection.querySelector(
      ".job-start-guidance",
    );
  const syncStartingDate = () => {
    const specific = startingDateSelect.value === "Specific Date";
    specificDateLabel.hidden = !specific;
    specificDateField.hidden = !specific;
    specificDateInput.required = specific;
    startingDateGuidance.hidden = !specific;
    if (!specific) specificDateInput.value = "";
  };
  syncStartingDate();
  jobBasicsPanel
    .querySelector(".work-location-section")
    .insertAdjacentHTML(
      "beforeend",
      '<div class="remote-location-workflow" hidden><label class="remote-eligibility-label" for="remote-eligibility-step2">Remote Eligibility</label><select id="remote-eligibility-step2"><option value="United States" selected>United States</option><option value="Select States">Select States</option><option value="Worldwide">Worldwide</option></select><select id="remote-states-step2" multiple hidden aria-label="Select states"></select></div><div class="work-location-note-workflow" hidden><label><input type="checkbox" id="work-location-note-toggle-step2"> Add a note for applicants</label><input id="work-location-note-step2" type="text" placeholder="e.g., Core collaboration hours are 9 a.m.–1 p.m. ET." hidden></div>',
    );
  const workLocationSelect = jobBasicsPanel.querySelector(
      "#work-location-step2",
    ),
    workLocationSummary = jobBasicsPanel.querySelector(
      ".work-location-summary",
    ),
    alternateLocationForm = jobBasicsPanel.querySelector(
      ".work-location-alternate-form",
    ),
    multipleWorkflow = jobBasicsPanel.querySelector(
      ".multiple-locations-workflow",
    ),
    remoteWorkflow = jobBasicsPanel.querySelector(".remote-location-workflow"),
    remoteEligibility = jobBasicsPanel.querySelector(
      "#remote-eligibility-step2",
    ),
    remoteStates = jobBasicsPanel.querySelector("#remote-states-step2"),
    noteWorkflow = jobBasicsPanel.querySelector(".work-location-note-workflow"),
    noteToggle = jobBasicsPanel.querySelector(
      "#work-location-note-toggle-step2",
    ),
    noteInput = jobBasicsPanel.querySelector("#work-location-note-step2"),
    alternateZip = jobBasicsPanel.querySelector("#work-location-zip-step2"),
    alternateCity = jobBasicsPanel.querySelector("#work-location-city-step2"),
    alternateState = jobBasicsPanel.querySelector("#work-location-state-step2"),
    multipleZip = jobBasicsPanel.querySelector("#multiple-location-zip-step2"),
    multipleCity = jobBasicsPanel.querySelector(
      "#multiple-location-city-step2",
    ),
    multipleState = jobBasicsPanel.querySelector(
      "#multiple-location-state-step2",
    ),
    multipleEditor = jobBasicsPanel.querySelector(".multiple-location-editor"),
    multipleList = jobBasicsPanel.querySelector(".multiple-location-list"),
    multipleMessage = jobBasicsPanel.querySelector(
      ".multiple-location-message",
    ),
    multipleAdd = jobBasicsPanel.querySelector(".multiple-add-location"),
    multipleSave = jobBasicsPanel.querySelector(".multiple-save-location"),
    workLocationForm = jobBasicsPanel.querySelector(".work-location-form"),
    workLocationReveals = [
      workLocationSummary,
      alternateLocationForm,
      multipleWorkflow,
      remoteWorkflow,
      noteWorkflow,
    ],
    multipleLocations = [];
  workLocationForm.append(noteWorkflow);
  alternateLocationForm.before(remoteWorkflow);
  const canonicalStateOptions = [
    ...document.querySelector("#organization-state").options,
  ].map((option) => option.cloneNode(true));
  const populateCanonicalStates = (select) => {
    select.replaceChildren(
      ...canonicalStateOptions.map((option) => option.cloneNode(true)),
    );
  };
  populateCanonicalStates(alternateState);
  populateCanonicalStates(multipleState);
  const updateMultipleSave = () => {
    const zip = /^\d{5}(?:-\d{4})?$/.test(multipleZip.value.trim()),
      city = multipleCity.value.trim().length > 0,
      state = multipleState.value !== "";
    multipleSave.disabled = !(zip || (city && state));
  };
  const renderMultipleLocations = () => {
    multipleList.replaceChildren(
      ...multipleLocations.map((location, index) => {
        const item = document.createElement("div");
        item.className = "multiple-location-item";
        item.innerHTML = `<span>${location.city}, ${location.state} ${location.zip}</span><button type="button" data-remove-location="${index}">Remove</button>`;
        return item;
      }),
    );
    updateMultipleSave();
  };
  const syncWorkLocation = () => {
    const selected = workLocationSelect.value;
    workLocationReveals.forEach((panel) => {
      panel.hidden = true;
    });
    [
      alternateZip,
      alternateCity,
      alternateState,
      multipleZip,
      multipleCity,
      multipleState,
    ].forEach((field) => {
      field.required = false;
    });
    multipleAdd.hidden = true;
    multipleEditor.hidden = true;
    if (selected === "Use School / Jobsite Location")
      workLocationSummary.hidden = false;
    if (selected === "Different On-site Location") {
      alternateLocationForm.hidden = false;
      [alternateZip, alternateCity, alternateState].forEach((field) => {
        field.required = true;
      });
    }
    if (selected === "Multiple Locations") {
      multipleWorkflow.hidden = false;
      multipleAdd.hidden = false;
      renderMultipleLocations();
    }
    if (selected === "Remote" || selected === "Hybrid") {
      if (selected === "Hybrid") {
        alternateLocationForm.hidden = false;
        [alternateZip, alternateCity, alternateState].forEach((field) => {
          field.required = true;
        });
      }
      if (selected === "Remote") {
        remoteWorkflow.hidden = false;
        remoteStates.hidden = remoteEligibility.value !== "Select States";
      }
      noteWorkflow.hidden = false;
      noteInput.placeholder =
        selected === "Hybrid"
          ? "e.g., On-site Tuesdays and Thursdays."
          : "e.g., Timezone or home office requirements...";
      noteInput.hidden = !noteToggle.checked;
    }
    updateMultipleSave();
  };
  syncWorkLocation();
  populateCanonicalStates(remoteStates);
  class WizardStepper {
    constructor(root, { steps, activeStep, states }) {
      this.root = root;
      this.steps = steps;
      this.activeStep = activeStep;
      this.states = states;
    }
    render() {
      const items = this.steps.map((step, index) => {
          const state = this.states[index] || "is-upcoming";
          const item = document.createElement("li");
          item.className = state;
          item.dataset.state = state;
          const target = this.completedTargets?.[index];
          const content = target
            ? document.createElement("a")
            : document.createElement("div");
          content.className = "stepper-step";
          if (target) {
            content.href = `#${target}`;
            content.setAttribute("aria-label", `Return to ${step.label}`);
          }
          if (state === "is-current")
            content.setAttribute("aria-current", "step");
          const marker = document.createElement("span");
          marker.textContent = String(index + 1);
          marker.setAttribute("aria-hidden", "true");
          marker.className = "stepper-circle";
          const label = document.createElement("strong");
          label.textContent = step.label;
          label.className = "stepper-label";
          content.append(marker, label);
          item.append(content);
          return item;
        });
      const currentIndex = this.states.findIndex((state) => state === "is-current");
      const status = document.createElement("div");
      status.className = "stepper-status";
      status.setAttribute("aria-live", "polite");
      status.innerHTML = `<strong>Step ${currentIndex + 1} of ${this.steps.length}</strong><span>${this.steps[currentIndex]?.label || ""}</span>`;
      this.root.replaceChildren(...items, status);
    }
  }
  const wizardStepper = new WizardStepper(
    document.querySelector("[data-wizard-stepper]"),
    {
      steps: [
        { label: "School / Jobsite" },
        { label: "Job Basics" },
        { label: "Job Description" },
        { label: "Application Process" },
        { label: "Review & Publish" },
      ],
      activeStep: 1,
      states: [
        "is-current",
        "is-upcoming",
        "is-upcoming",
        "is-upcoming",
        "is-upcoming",
      ],
    },
  );
  wizardStepper.render();
  class NavbarDropdown {
    constructor(trigger, config) {
      this.config = config;
      this.wrapper = document.createElement("span");
      this.wrapper.className = "tnet-navbar-dropdown";
      this.button = document.createElement("button");
      this.button.type = "button";
      this.button.className = trigger.className;
      this.button.innerHTML = trigger.innerHTML;
      if (trigger.hasAttribute("aria-label")) {
        this.button.setAttribute("aria-label", trigger.getAttribute("aria-label"));
      }
      this.button.setAttribute("aria-haspopup", "menu");
      this.button.setAttribute("aria-expanded", "false");
      trigger.replaceWith(this.wrapper);
      this.wrapper.append(this.button);
      this.menu = document.createElement("div");
      this.menu.className = "tnet-navbar-dropdown-menu";
      this.menu.setAttribute("role", "menu");
      this.menu.hidden = true;
      const header = document.createElement("div");
      header.className = "tnet-navbar-dropdown-header";
      header.textContent = config.title;
      this.menu.append(header);
      const icons = {
        "My Jobs":
          '<svg viewBox="0 0 24 24" focusable="false"><rect x="4" y="7" width="16" height="13" rx="2"/><path d="M9 7V5.5A1.5 1.5 0 0 1 10.5 4h3A1.5 1.5 0 0 1 15 5.5V7M4 11h16M10 11v2h4v-2"/></svg>',
        "Post a Job":
          '<svg viewBox="0 0 24 24" focusable="false"><rect x="4" y="4" width="16" height="16" rx="2"/><path d="M12 8v8M8 12h8"/></svg>',
        "Schools / Jobsites":
          '<svg viewBox="0 0 24 24" focusable="false"><path d="m3 10 9-5 9 5M5 10v10M9 10v10M15 10v10M19 10v10M3 20h18M2 10h20"/></svg>',
      };
      config.items.forEach((item) => {
        const el = document.createElement(item.available ? "a" : "span");
        el.className =
          "tnet-navbar-dropdown-item" +
          (item.available ? "" : " is-unavailable");
        el.setAttribute("role", "menuitem");
        if (item.available) {
          el.href = item.href;
        } else {
          el.setAttribute("aria-disabled", "true");
        }
        const icon = document.createElement("span");
        icon.className = "tnet-navbar-dropdown-item-icon";
        icon.setAttribute("aria-hidden", "true");
        icon.innerHTML =
          icons[item.label] ||
          '<svg viewBox="0 0 16 16" focusable="false"><rect x="2.5" y="2.5" width="11" height="11" rx="2"/><path d="M5 8h6M8 5v6"/></svg>';
        const label = document.createElement("span");
        label.textContent = item.label;
        el.append(icon, label);
        if (item.badge) {
          const badge = document.createElement("span");
          badge.className = "tnet-navbar-dropdown-item-badge";
          badge.textContent = item.badge;
          el.append(badge);
        } else if (item.current) {
          const status = document.createElement("span");
          status.className = "tnet-navbar-dropdown-item-status";
          status.textContent = "Current";
          el.append(status);
        }
        this.menu.append(el);
      });
      if (config.items.some((item) => !item.available)) {
        const legend = document.createElement("div");
        legend.className = "tnet-navbar-dropdown-legend";
        legend.innerHTML =
          "<span>* Planned for V1</span><span>** Planned after V1</span>";
        this.menu.append(legend);
      }
      this.wrapper.append(this.menu);
      this.button.addEventListener("click", () => this.toggle());
      this.button.addEventListener("keydown", (event) =>
        this.onTriggerKey(event),
      );
      this.menu.addEventListener("keydown", (event) => this.onMenuKey(event));
    }
    toggle() {
      this.menu.hidden ? this.open() : this.close();
    }
    open() {
      dropdowns.forEach((dropdown) => {
        if (dropdown !== this) dropdown.close();
      });
      this.menu.hidden = false;
      this.button.setAttribute("aria-expanded", "true");
      const first = this.menu.querySelector('[role="menuitem"]');
      first?.focus();
    }
    close(returnFocus = false) {
      this.menu.hidden = true;
      this.button.setAttribute("aria-expanded", "false");
      if (returnFocus) this.button.focus();
    }
    onTriggerKey(event) {
      if (
        event.key === "Enter" ||
        event.key === " " ||
        event.key === "ArrowDown"
      ) {
        event.preventDefault();
        this.open();
      } else if (event.key === "Escape") {
        this.close(true);
      }
    }
    onMenuKey(event) {
      const items = [...this.menu.querySelectorAll('[role="menuitem"]')],
        index = items.indexOf(document.activeElement);
      if (event.key === "ArrowDown") {
        event.preventDefault();
        items[(index + 1) % items.length].focus();
      } else if (event.key === "ArrowUp") {
        event.preventDefault();
        items[(index - 1 + items.length) % items.length].focus();
      } else if (event.key === "Escape") {
        event.preventDefault();
        this.close(true);
      } else if (event.key === "Tab") {
        this.close();
      }
    }
  }
  const hrefs = {
    workspace: "#step-01-nav-back",
    school: "#step-01-school-selected",
  };
  const menuSets = {
    "my-jobs": {
      title: "My Jobs",
      items: [
        ["My Jobs", true, true, "3"],
        ["Post a Job", true],
        ["Schools / Jobsites", true, false, "5"],
        ["Candidates **", false],
        ["Saved Searches **", false],
        ["Billing **", false],
        ["Employer Dashboard **", false],
      ],
    },
    "career-resources": {
      title: "Career Resources",
      items: [
        ["Browse Jobs", true],
        ["Salary Explorer **", false],
        ["Resume Advice *", false],
        ["Interview Resources *", false],
        ["Career Articles *", false],
        ["Job Alerts **", false],
      ],
    },
    "teacher-resources": {
      title: "Teacher Resources",
      items: [
        ["Lesson Plans", true],
        ["Chatboards", true],
        ["Teaching Jobs", true],
        ["Classroom Management", true],
        ["Printables", true],
        ["Professional Development", true],
        ["Teacher Humor", true],
      ],
    },
    "my-account": {
      title: "My Account",
      items: [
        ["Profile *", false],
        ["Organization *", false],
        ["Billing **", false],
        ["Notifications *", false],
        ["Preferences *", false],
        ["Help *", false],
        ["Sign Out", true],
      ],
    },
  };
  const dropdowns = [...document.querySelectorAll("[data-dropdown]")].map(
    (trigger) => {
      const key = trigger.dataset.dropdown,
        config = menuSets[key];
      config.items = config.items.map(([label, available, current, badge]) => ({
        label,
        available,
        current,
        badge,
        href: label === "Schools / Jobsites" ? hrefs.school : hrefs.workspace,
      }));
      return new NavbarDropdown(trigger, config);
    },
  );
  const primaryOverflow = document.querySelector(".tnet-jobs-app-primary-overflow"),
    primaryOverflowMenu = document.querySelector(".tnet-jobs-app-primary-overflow-menu");
  if (primaryOverflow && primaryOverflowMenu) {
    primaryOverflow.setAttribute("aria-controls", primaryOverflowMenu.id);
    const syncPrimaryOverflowLabel = () => {
      if (window.matchMedia("(max-width: 530px)").matches) {
        primaryOverflow.setAttribute("aria-label", "Open Job Center navigation");
      } else {
        primaryOverflow.removeAttribute("aria-label");
      }
    };
    syncPrimaryOverflowLabel();
    window.addEventListener("resize", syncPrimaryOverflowLabel);
    const primaryOverflowClose = primaryOverflowMenu.querySelector(
      ".tnet-jobs-app-primary-overflow-close",
    );
    const closePrimaryOverflow = () => {
      primaryOverflowMenu.hidden = true;
      primaryOverflow.setAttribute("aria-expanded", "false");
    };
    primaryOverflow.addEventListener("click", () => {
      const open = primaryOverflowMenu.hidden;
      primaryOverflowMenu.hidden = !open;
      primaryOverflow.setAttribute("aria-expanded", String(open));
      if (open) {
        primaryOverflowMenu.setAttribute("tabindex", "-1");
        primaryOverflowMenu.focus({ preventScroll: true });
      }
    });
    primaryOverflowClose?.addEventListener("click", () => {
      closePrimaryOverflow();
      primaryOverflow.focus();
    });
    primaryOverflowMenu.addEventListener("keydown", (event) => {
      if (event.key === "Escape") {
        event.preventDefault();
        closePrimaryOverflow();
        primaryOverflow.focus();
      }
    });
    document.addEventListener("click", (event) => {
      if (!(event.target instanceof Element) || !event.target.closest(".tnet-jobs-app-primary-region")) {
        closePrimaryOverflow();
      }
    });
  }
  document.addEventListener("click", (event) => {
    if (
      !(event.target instanceof Element) ||
      !event.target.closest(".tnet-navbar-dropdown")
    )
      dropdowns.forEach((dropdown) => dropdown.close());
  });
  const internationalPanel = document
    .querySelector("#step-01-add-school-us")
    .cloneNode(true);
  internationalPanel.id = "step-01-add-school-international";
  internationalPanel.dataset.view = "step-01-add-school-us";
  internationalPanel.hidden = true;
  const clonedIds = new Map(
    [...internationalPanel.querySelectorAll("[id]")].map((element) => [
      element.id,
      `${element.id}-international`,
    ]),
  );
  internationalPanel.querySelectorAll("[id]").forEach((element) => {
    element.id = clonedIds.get(element.id);
  });
  internationalPanel.querySelectorAll("[for]").forEach((element) => {
    if (clonedIds.has(element.htmlFor))
      element.htmlFor = clonedIds.get(element.htmlFor);
  });
  internationalPanel.querySelector(".location-type").value = "International";
  internationalPanel.querySelector(".organization-form").innerHTML =
    '<div class="form-field form-field-intl-full-name"><label for="organization-name-international">School / Jobsite Name (Full) <span aria-hidden="true">*</span></label><input id="organization-name-international" type="text" placeholder="Enter the school or jobsite name"></div><div class="form-field form-field-intl-display-name"><label for="organization-display-name-international">Display Name <span aria-hidden="true">*</span></label><input id="organization-display-name-international" type="text" placeholder="Short display name"></div><div class="form-field form-field-intl-country"><label for="organization-country-international">Country <span aria-hidden="true">*</span></label><select id="organization-country-international"><option selected>Choose a country</option><option>Canada</option><option>United Kingdom</option><option>Australia</option><option>Mexico</option><option>Japan</option></select></div><div class="form-field form-field-intl-city"><label for="organization-city-international">City / Locality <span aria-hidden="true">*</span></label><input id="organization-city-international" type="text" placeholder="City or locality"></div><div class="form-field form-field-intl-region"><label for="organization-region-international">State / Province / Region <small>(Optional)</small></label><input id="organization-region-international" type="text" placeholder="State, province, or region"></div><div class="form-field form-field-intl-postal"><label for="organization-postal-international">Postal Code <small>(Optional)</small></label><input id="organization-postal-international" type="text" placeholder="Postal code"></div><div class="form-field form-field-intl-address"><label for="organization-address-international">Street Address <small>(Optional)</small></label><input id="organization-address-international" type="text" placeholder="Street address"></div><div class="form-field form-field-intl-suite"><label for="organization-suite-international">Suite / Room <small>(Optional)</small></label><input id="organization-suite-international" type="text" placeholder="Suite or room"></div>';
  document.querySelector("#step-01-add-school-us").after(internationalPanel);
  document.querySelectorAll("#step-01-add-school-us .form-section-number, #step-01-add-school-international .form-section-number").forEach((marker) => marker.remove());
  document.querySelectorAll(".panel-heading").forEach((heading) => heading.remove());
  const StageHeading = ({ stepNumber, title, supportingCopy }) => {
    const heading = document.createElement("header");
    heading.className = "wizard-stage-heading";
    heading.innerHTML = `<p class="wizard-stage-heading__eyebrow">Step ${stepNumber} of 5</p><h2 class="wizard-stage-heading__title">${title}</h2><p class="wizard-stage-heading__support">${supportingCopy}</p>`;
    return heading;
  };
  const stageContent = (panel) => {
    const content = document.createDocumentFragment();
    [...panel.children].forEach((child) => {
      if (child.matches(".panel-heading, .wizard-stage-heading")) child.remove();
      else content.append(child);
    });
    return content;
  };
  document.querySelector(".add-school-page-heading")?.remove();
  views.forEach(([id, label]) => {
    const o = document.createElement("option");
    o.value = id;
    o.textContent = label;
    o.disabled = ![
      "step-01-initial",
      "step-01-add-school-us",
      "step-01-add-school-international",
      "step-01-school-selected",
      "step-01-return",
      "wizard-authority-v1",
      "step-02-job-basics",
      "step-03-clipboard-diagnostics",
      "step-04-application-process",
    ].includes(id);
    select.append(o);
  });
  const statePanels = {
    "step-01-add-school-us": document.querySelector("#step-01-add-school-us"),
    "step-02-job-basics": jobBasicsPanel,
    "wizard-authority-v1": authorityPanel,
    "step-01-add-school-international": internationalPanel,
    "step-01-school-selected": document.querySelector(
      "#step-01-school-selected",
    ),
    "step-01-return": document.querySelector("#step-01-school-selected"),
    "step-01-initial": document.querySelector("#step-01-initial"),
  };
  // WIZARD-STATE001: one page-lifetime source of truth for Steps 2–5.
  const wizardState = {
    school: { displayName: schoolJobsiteFixture.display_name, location: "Los Angeles, CA", defaultContact: { email: "jobs@lausd.net", phone: "(213) 241-1000" }, image: null },
    basics: { jobTitle: "", employmentType: "", gradeLevels: "", subjectAreas: "", workLocation: "", alternateLocation: { zip: "", city: "", state: "" }, alternateZip: "", alternateCity: "", alternateState: "", remoteEligibility: "", remoteStates: [], multipleLocations: [], locationNote: "", startTiming: "", specificStartDate: "", salaryVisibility: "", salaryMinimum: "", salaryMaximum: "", salaryType: "" },
    description: { jobDescription: "", shortSummary: "", requirements: "", aboutSchool: "", benefits: [], additionalBenefitsEnabled: false, additionalBenefits: "" },
    application: { method: "", url: "", email: "", instructions: "", deadlineMode: "open", deadline: "", closeOnDeadline: false, contactMode: "default", contactName: "", contactEmail: "", contactPhone: "", hideContact: false, materials: [], otherMaterials: "" },
    media: {},
    ui: { railExpanded: false },
  };
  const wizardControlMap = {
    "#job-title-step2": ["basics", "jobTitle"], "#employment-type-step2": ["basics", "employmentType"], "#grade-levels-step2": ["basics", "gradeLevels"], "#subject-areas-step2": ["basics", "subjectAreas"], "#work-location-step2": ["basics", "workLocation"], "#work-location-zip-step2": ["basics", "alternateZip"], "#work-location-city-step2": ["basics", "alternateCity"], "#work-location-state-step2": ["basics", "alternateState"], "#remote-eligibility-step2": ["basics", "remoteEligibility"], "#remote-states-step2": ["basics", "remoteStates"], "#work-location-note-step2": ["basics", "locationNote"], "#job-start-step2": ["basics", "startTiming"], "#job-specific-date-step2": ["basics", "specificStartDate"], "#salary-visibility-step2": ["basics", "salaryVisibility"], "#salary-minimum-step2": ["basics", "salaryMinimum"], "#salary-maximum-step2": ["basics", "salaryMaximum"], "#salary-type-step2": ["basics", "salaryType"],
    "#step3-description-editor": ["description", "jobDescription"], "#step3-summary": ["description", "shortSummary"], "#step3-optional-1": ["description", "requirements"], "#step3-optional-2": ["description", "aboutSchool"], "#step3-benefits-additional-enabled": ["description", "additionalBenefitsEnabled"], "#step3-benefits-additional": ["description", "additionalBenefits"],
    "#step4-method": ["application", "method"], "#step4-url": ["application", "url"], "#step4-email": ["application", "email"], "#step4-instructions": ["application", "instructions"], "#step4-deadline-mode": ["application", "deadlineMode"], "#step4-deadline": ["application", "deadline"], "#step4-close-on-deadline": ["application", "closeOnDeadline"], "#step4-contact-mode": ["application", "contactMode"], "#step4-contact-name": ["application", "contactName"], "#step4-contact-email": ["application", "contactEmail"], "#step4-contact-phone": ["application", "contactPhone"], "#step4-hide-contact": ["application", "hideContact"], "#step4-other-materials": ["application", "otherMaterials"],
  };
  const readWizardControl = (control) => control.type === "checkbox" ? control.checked : control.multiple ? [...control.selectedOptions].map((option) => option.value) : control.isContentEditable ? control.innerHTML : control.value;
  const writeWizardControl = (control, value) => { if (control.type === "checkbox") control.checked = Boolean(value); else if (control.multiple) [...control.options].forEach((option) => { option.selected = (value || []).includes(option.value); }); else if (control.isContentEditable) control.innerHTML = value || ""; else if (value !== undefined && value !== null) control.value = value; };
  const syncWizardStateFromControl = (control) => { const mapping = Object.entries(wizardControlMap).find(([selector]) => document.querySelector(selector) === control); if (!mapping) return; const [section, key] = mapping[1]; wizardState[section][key] = readWizardControl(control); };
  const hydrateWizardState = (root = document) => { Object.entries(wizardControlMap).forEach(([selector, [section, key]]) => { const control = root.querySelector(selector) || document.querySelector(selector); if (control && wizardState[section][key] !== undefined) writeWizardControl(control, wizardState[section][key]); }); };
  let wizardStatePrimed = false;
  const primeWizardState = () => { if (wizardStatePrimed) return; Object.entries(wizardControlMap).forEach(([selector, [section, key]]) => { const control = document.querySelector(selector); if (control) wizardState[section][key] = readWizardControl(control); }); wizardStatePrimed = true; };
  window.__jc053WizardState = wizardState;
  const syncWizardValueStates = (root = document) => {
    root.querySelectorAll("input:not([type=checkbox]):not([type=radio]), select, textarea").forEach((control) => {
      const value = control.tagName === "SELECT" ? control.options[control.selectedIndex] : control;
      const placeholder = control.tagName === "SELECT"
        ? !control.value || (control.selectedIndex === 0 && /^(select|choose|search|pick)/i.test(value?.textContent.trim() || ""))
        : !control.value.trim();
      control.dataset.valueState = placeholder ? "empty" : "filled";
    });
    root.querySelectorAll("[contenteditable=\"true\"]").forEach((editor) => {
      editor.dataset.valueState = editor.textContent.trim() ? "filled" : "empty";
    });
  };
  const formStateObserver = new MutationObserver((records) => { if (records.some((record) => record.type === "childList" || record.type === "characterData")) syncWizardValueStates(document.querySelector(".application-card") || document); });
  formStateObserver.observe(document.querySelector(".application-card"), { childList: true, characterData: true, subtree: true });
  const implementedViews = views.map(([id]) => id);
  const syncWorkbenchTraversal = (id) => {
    const index = implementedViews.indexOf(id),
      previous = document.querySelector("#workbench-previous"),
      next = document.querySelector("#workbench-next");
    previous.hidden = index <= 0;
    next.hidden = index < 0 || index >= implementedViews.length - 1;
    if (index > 0) previous.href = `#${implementedViews[index - 1]}`;
    if (index < implementedViews.length - 1)
      next.href = `#${implementedViews[index + 1]}`;
  };
  function render() {
    const requested = location.hash.slice(1) || "step-01-return";
    const id =
      statePanels[requested] || views.some(([viewId]) => viewId === requested)
        ? requested
        : "step-01-return";
    select.value = id;
    status.textContent = id;
    const card = document.querySelector(".application-card");
    const implemented = Boolean(statePanels[id]);
    document.documentElement.dataset.authority = implemented ? "true" : "false";
    card.dataset.view =
      id === "step-01-add-school-international"
        ? "step-01-add-school-us"
        : id === "wizard-authority-v1"
          ? "step-02-job-basics"
          : id;
    card.dataset.authority = implemented ? "true" : "false";
    syncWizardValueStates(card);
    Object.values(statePanels).forEach((viewPanel) => {
      viewPanel.hidden = viewPanel !== statePanels[id];
    });
    placeholder.hidden = Boolean(statePanels[id]);
    placeholder.querySelector("p").textContent = statePanels[id]
      ? ""
      : "This view is registered for later authority work and is intentionally not implemented.";
  }
  const bottomNavigationConfig = (id) => ({
    previous: ["step-02-job-basics", "wizard-authority-v1"].includes(id)
      ? { label: "← Previous: School / Jobsite", target: "step-01-return" }
      : null,
    next: ["step-02-job-basics", "wizard-authority-v1"].includes(id)
      ? {
          label: "Next: Job Description →",
          target: "step-03-job-description",
          requiresInput: true,
        }
      : id.startsWith("step-01-")
        ? {
            label: "Next: Job Basics →",
            target: "step-02-job-basics",
            requiresInput: true,
          }
        : null,
  });
  const renderBottomNavigation = (id) => {
    const config = bottomNavigationConfig(id),
      nav = document.querySelector(".view-nav");
    nav.replaceChildren();
    if (config.previous) {
      const previous = document.createElement("a");
      previous.id = "previous-view";
      previous.className = "button";
      previous.href = `#${config.previous.target}`;
      previous.textContent = config.previous.label;
      nav.append(previous);
    }
    if (config.next) {
      const next = document.createElement("a");
      next.id = "next-view";
      next.className = "button";
      next.textContent = config.next.label;
      next.dataset.target = config.next.target;
      next.dataset.requiresInput = String(config.next.requiresInput);
      next.setAttribute("aria-disabled", "true");
      nav.append(next);
      next.addEventListener("click", (event) => {
        if (next.getAttribute("aria-disabled") === "true") return;
        event.preventDefault();
        setView(next.dataset.target);
      });
    }
  };
  const stateIsReady = (id) => {
    if (["step-01-school-selected", "step-01-return"].includes(id)) return true;
    if (id === "step-02-job-basics") {
      const visibility =
          document.querySelector("#salary-visibility-step2")?.value ||
          "Show salary",
        minimumValid =
          Number(document.querySelector("#salary-minimum-step2")?.value) > 0,
        salaryReady = visibility !== "Show salary" || minimumValid,
        expanded = visibility === "Show salary" && minimumValid,
        typeReady = Boolean(
          document.querySelector("#salary-type-step2")?.value,
        ),
        startValue = document.querySelector("#job-start-step2")?.value,
        startDateReady =
          startValue !== "Specific Date" ||
          Boolean(document.querySelector("#job-specific-date-step2")?.value),
        workLocation = document.querySelector("#work-location-step2")?.value,
        locationFormRequired = [
          "Different On-site Location",
          "Hybrid",
        ].includes(workLocation),
        alternateLocationReady =
          !locationFormRequired ||
          Boolean(
            document.querySelector("#work-location-zip-step2")?.value.trim() &&
              document
                .querySelector("#work-location-city-step2")
                ?.value.trim() &&
              document.querySelector("#work-location-state-step2")?.value,
          ),
        multipleEditorReady =
          workLocation !== "Multiple Locations" ||
          multipleEditor.hidden ||
          Boolean(
            multipleZip.value.trim() &&
              multipleCity.value.trim() &&
              multipleState.value,
          );
      return Boolean(
        document.querySelector("#job-title-step2")?.value.trim() &&
          document.querySelector("#employment-type-step2")?.value &&
          workLocation &&
          alternateLocationReady &&
          multipleEditorReady &&
          startValue &&
          startDateReady &&
          salaryReady &&
          (!expanded || typeReady),
      );
    }
    if (id === "wizard-authority-v1")
      return Boolean(
        document.querySelector("#job-title-authority")?.value.trim(),
      );
    if (id === "step-03-job-description")
      return Boolean(step3Text(document.querySelector("#step3-description-editor")?.innerHTML));
    if (id === "step-04-application-process") return step4Ready();
    if (id === "step-01-add-school-us")
      return Boolean(
        document.querySelector("#organization-name")?.value.trim() &&
          document.querySelector("#organization-display-name")?.value.trim(),
      );
    if (id === "step-01-add-school-international")
      return Boolean(
        document
          .querySelector("#organization-name-international")
          ?.value.trim() &&
          document
            .querySelector("#organization-display-name-international")
            ?.value.trim() &&
          document.querySelector("#organization-country-international")
            ?.value &&
          document
            .querySelector("#organization-city-international")
            ?.value.trim(),
      );
    return false;
  };
  const refreshNextAction = () => {
    syncWizardValueStates(document.querySelector(".application-card") || document);
    const id = location.hash.slice(1) || "step-01-return",
      next = document.querySelector("#next-view");
    if (!next) return;
    const ready = stateIsReady(id);
    next.classList.toggle("primary", ready);
    next.classList.toggle("is-disabled", !ready);
    next.setAttribute("aria-disabled", ready ? "false" : "true");
    if (ready)
      next.href = ["step-02-job-basics", "wizard-authority-v1"].includes(id)
        ? "#step-03-job-description"
        : id === "step-03-job-description"
          ? "#step-04-application-process"
          : id === "step-04-application-process"
            ? "#step-05-review-publish"
          : "#step-02-job-basics";
    else next.removeAttribute("href");
  };
  const buildStep3SummaryDraft = () => {
    const source = step3Text(document.querySelector("#step3-description-editor")?.innerHTML);
    if (!source) return "";
    const sentence = source.match(/^(.{1,160}?[.!?])(?:\s|$)/)?.[1];
    if (sentence) return sentence;
    if (source.length <= 160) return source;
    return `${source.slice(0, 157).trim().replace(/\s+\S*$/, "")}…`;
  };
  const step3SummaryQuality = (value) => { const words = (value.match(/[\p{L}\p{N}]+/gu) || []).length; return value.trim().length >= 40 && words >= 5; };
  const openStep3SummaryAssist = () => {
    let modal = document.querySelector("#step3-summary-assist");
    if (!modal) {
      modal = document.createElement("div");
      modal.id = "step3-summary-assist";
      modal.className = "step3-summary-assist";
      modal.setAttribute("role", "dialog");
      modal.setAttribute("aria-modal", "true");
      modal.setAttribute("aria-hidden", "true");
      modal.hidden = true;
      document.body.append(modal);
      modal.addEventListener("click", (event) => {
        if (event.target === modal) modal.__closeToListing?.();
      });
      modal.addEventListener("keydown", (event) => {
        if (event.key === "Escape") {
          event.preventDefault();
          modal.__closeToListing?.();
        }
      });
    }
    if (!modal.hidden) return;
    const draft = buildStep3SummaryDraft();
    const summaryField = document.querySelector("#step3-summary");
    if (draft && !summaryField.value) { summaryField.value = draft; summaryField.dispatchEvent(new Event("input", { bubbles: true })); }
    modal.innerHTML = `<div class="step3-summary-assist-card"><h3 id="step3-summary-assist-title">Review Your Listing Summary</h3><p>This is how your listing will appear in Teachers.Net search results, featured placements, and shared links.</p><label for="step3-summary-draft">Short Summary</label><textarea id="step3-summary-draft" maxlength="160">${step3Escape(summaryField.value || draft)}</textarea><p class="step3-summary-quality-message" role="status" aria-live="polite" hidden>Your summary is too short. Teachers.Net uses this summary in search results and featured placements. Continue editing here or return to your listing to refine it.</p><div class="step3-summary-assist-actions"><button type="button" class="button secondary" data-summary-action="return">Return to Listing</button><button type="button" class="button primary" data-summary-action="use">Accept and Continue</button></div></div>`;
    modal.hidden = false;
    modal.setAttribute("aria-hidden", "false");
    modal.setAttribute("aria-labelledby", "step3-summary-assist-title");
    const draftField = modal.querySelector("#step3-summary-draft"), use = modal.querySelector('[data-summary-action="use"]'), returnButton = modal.querySelector('[data-summary-action="return"]'), qualityMessage = modal.querySelector(".step3-summary-quality-message");
    const sync = () => { summaryField.value = draftField.value.slice(0, 160); summaryField.dispatchEvent(new Event("input", { bubbles: true })); updateStep3Counters(); renderStep3Preview(); const valid = step3SummaryQuality(draftField.value); use.disabled = !valid; returnButton.classList.toggle("primary", !valid); returnButton.classList.toggle("secondary", valid); qualityMessage.hidden = valid; };
    const closeToListing = () => { sync(); step3State.expandedPreviewSections.delete("description"); renderStep3Preview(); collapseStep3Description(); modal.hidden = true; modal.setAttribute("aria-hidden", "true"); refreshNextAction(); summaryField.scrollIntoView({ block: "center" }); summaryField.focus({ preventScroll: true }); };
    modal.__closeToListing = closeToListing;
    draftField.addEventListener("input", sync);
    use.addEventListener("click", () => { sync(); if (!step3SummaryQuality(draftField.value)) return; modal.hidden = true; modal.setAttribute("aria-hidden", "true"); refreshNextAction(); setView("step-04-application-process"); });
    returnButton.addEventListener("click", closeToListing);
    draftField.focus({ preventScroll: true });
    sync();
  };
  let salaryTypeUserControlled = false;
  const salaryTypeField = document.querySelector("#salary-type-step2"),
    salaryMinimumField = document.querySelector("#salary-minimum-step2");
  salaryTypeField?.addEventListener("change", () => {
    salaryTypeUserControlled = true;
    refreshNextAction();
  });
  salaryMinimumField?.addEventListener("blur", () => {
    const visibility = document.querySelector(
        "#salary-visibility-step2",
      )?.value,
      minimum = Number(salaryMinimumField.value);
    if (
      visibility === "Show salary" &&
      minimum >= 10000 &&
      !salaryTypeUserControlled &&
      !salaryTypeField.value
    ) {
      salaryTypeField.value = "Annual";
      refreshNextAction();
    }
  });
  const syncStep2Controls = (id) => {
    const stepTwo = ["step-02-job-basics", "wizard-authority-v1"].includes(id),
      returnState = id === "step-01-return",
      saveDraft = document.querySelector("#save-draft-action");
    renderBottomNavigation(id);
    saveDraft.hidden = !(stepTwo || returnState);
    refreshNextAction();
  };
  document.addEventListener("click", (event) => {
    const add = event.target.closest(".multiple-add-location"),
      save = event.target.closest(".multiple-save-location"),
      cancel = event.target.closest(".multiple-cancel-location"),
      remove = event.target.closest("[data-remove-location]");
    if (add) {
      multipleMessage.hidden = true;
      multipleAdd.hidden = true;
      multipleEditor.hidden = false;
      updateMultipleSave();
      multipleZip.focus({ preventScroll: true });
      return;
    }
    if (cancel) {
      multipleEditor.hidden = true;
      multipleAdd.hidden = false;
      [multipleZip, multipleCity, multipleState].forEach((field) => {
        field.value = "";
      });
      multipleMessage.hidden = true;
      updateMultipleSave();
      return;
    }
    if (remove) {
      multipleLocations.splice(Number(remove.dataset.removeLocation), 1);
      renderMultipleLocations();
      return;
    }
    if (save) {
      const location = {
        zip: multipleZip.value.trim(),
        city: multipleCity.value.trim(),
        state: multipleState.value,
      };
      const zipValid = /^\d{5}(?:-\d{4})?$/.test(location.zip),
        cityStateValid = location.city !== "" && location.state !== "";
      if (!zipValid && !cityStateValid) {
        multipleMessage.textContent =
          "Enter a valid ZIP Code or provide City and State.";
        multipleMessage.hidden = false;
        multipleSave.disabled = true;
        return;
      }
      const duplicate = multipleLocations.some(
        (item) =>
          item.zip === location.zip &&
          item.city.toLowerCase() === location.city.toLowerCase() &&
          item.state === location.state,
      );
      if (duplicate) {
        multipleMessage.textContent = "That location has already been added.";
        multipleMessage.hidden = false;
        return;
      }
      multipleLocations.push(location);
      wizardState.basics.multipleLocations = multipleLocations.map((item) => ({ ...item }));
      renderMultipleLocations();
      multipleEditor.hidden = true;
      multipleAdd.hidden = false;
      [multipleZip, multipleCity, multipleState].forEach((field) => {
        field.value = "";
      });
      multipleMessage.hidden = true;
      multipleWorkflow
        .querySelector(".multiple-add-location")
        .focus({ preventScroll: true });
      refreshNextAction();
    }
  });
  document.addEventListener("input", (event) => {
    if (event.target.matches("input, select, textarea, [contenteditable=\"true\"]")) syncWizardStateFromControl(event.target);
    if (event.target.closest?.(".wizard-shell-content-step2")) renderStep2Preview();
    if (event.target.matches("input, select, textarea")) syncWizardValueStates(event.target.closest(".application-card") || document);
    if (event.target.matches("#next-view, input, select, textarea")) {
      if (event.target.matches("#salary-minimum-step2")) syncCompensation();
      if (
        event.target.matches(
          "#multiple-location-zip-step2,#multiple-location-city-step2,#multiple-location-state-step2",
        )
      )
        updateMultipleSave();
      refreshNextAction();
    }
  });
  document.addEventListener("change", (event) => {
    if (event.target.matches("input, select, textarea, [contenteditable=\"true\"]")) syncWizardStateFromControl(event.target);
    if (event.target.closest?.(".wizard-shell-content-step2")) renderStep2Preview();
    if (event.target.matches("input, select, textarea")) syncWizardValueStates(event.target.closest(".application-card") || document);
    if (event.target.matches("#salary-visibility-step2")) {
      syncCompensation();
      if (event.target.value === "Show salary")
        requestAnimationFrame(() => {
          const field = document.querySelector("#salary-minimum-step2");
          if (field && !field.disabled && !field.hidden)
            field.focus({ preventScroll: true });
        });
    }
    if (event.target.matches("#job-start-step2")) {
      syncStartingDate();
      if (event.target.value === "Specific Date")
        requestAnimationFrame(() => {
          if (
            specificDateInput &&
            !specificDateInput.disabled &&
            !specificDateInput.hidden
          )
            specificDateInput.focus({ preventScroll: true });
        });
    }
    if (event.target.matches("#work-location-step2")) {
      syncWorkLocation();
      if (["Different On-site Location", "Hybrid"].includes(event.target.value))
        requestAnimationFrame(() => {
          if (alternateZip && !alternateZip.disabled && !alternateZip.hidden)
            alternateZip.focus({ preventScroll: true });
        });
    }
    if (event.target.matches("#remote-eligibility-step2")) {
      remoteStates.hidden = remoteEligibility.value !== "Select States";
    }
    if (event.target.matches("#work-location-note-toggle-step2")) {
      noteInput.hidden = !noteToggle.checked;
      if (noteToggle.checked)
        requestAnimationFrame(() => noteInput.focus({ preventScroll: true }));
    }
    if (event.target.matches("#multiple-location-state-step2"))
      updateMultipleSave();
    if (event.target.matches("input, select, textarea")) refreshNextAction();
  });
  const syncAuthorityMarker = (id) => {
    const existing = document.querySelector("#authority-marker");
    if (id === "wizard-authority-v1") {
      if (existing) {
        existing.hidden = false;
        return;
      }
      const marker = document.createElement("div");
      marker.id = "authority-marker";
      marker.className = "authority-marker";
      marker.innerHTML =
        "<strong>Canonical Authority: Wizard UI v1</strong><span>Reference state — do not edit for step-specific requirements</span>";
      document.querySelector("[data-wizard-stepper]").after(marker);
    } else existing?.remove();
  };
  const updateStepper = (id) => {
    if (wizardShellConfigs[id]) {
      syncWorkbenchTraversal(id);
      return;
    }
    const stepTwo = ["step-02-job-basics", "wizard-authority-v1"].includes(id);
    wizardStepper.states = stepTwo
      ? [
          "is-complete",
          "is-current",
          "is-upcoming",
          "is-upcoming",
          "is-upcoming",
        ]
      : [
          "is-current",
          "is-upcoming",
          "is-upcoming",
          "is-upcoming",
          "is-upcoming",
        ];
    wizardStepper.completedTargets = stepTwo
      ? ["step-01-return", null, null, null, null]
      : [null, null, null, null, null];
    wizardStepper.render();
    syncAuthorityMarker(id);
    syncStep2Controls(id);
    syncWorkbenchTraversal(id);
  };
  const setView = (id) => {
    document.querySelectorAll(".location-type").forEach((select) => {
      select.value =
        id === "step-01-add-school-international"
          ? "International"
          : "Physical U.S. Location";
    });
    history.replaceState(null, "", "#" + id);
    render();
    updateStepper(id);
  };
  document
    .querySelectorAll(".location-type")
    .forEach((select) =>
      select.addEventListener("change", () =>
        setView(
          select.value === "International"
            ? "step-01-add-school-international"
            : "step-01-add-school-us",
        ),
      ),
    );
  select.addEventListener("change", () => setView(select.value));
  window.addEventListener("hashchange", () => {
    render();
    updateStepper(location.hash.slice(1) || "step-01-return");
  });
  const diagnostics = document.querySelector("#diagnostics"),
    toggle = document.querySelector("#diagnostics-toggle");
  function measure() {
    const card = document
        .querySelector(".application-card")
        .getBoundingClientRect(),
      rail = document.querySelector(".left-rail").getBoundingClientRect(),
      workspace = document
        .querySelector(".main-workspace")
        .getBoundingClientRect();
    diagnostics.textContent = [
      `Workbench Build: ${workbenchBuild}`,
      `view: ${location.hash.slice(1) || "step-01-return"}`,
      `card: ${card.width}px`,
      `rail: ${rail.width}px`,
      `workspace: ${workspace.width}px`,
      `viewport: ${innerWidth}×${innerHeight}`,
      `overflow: ${document.documentElement.scrollWidth > innerWidth ? "yes" : "no"}`,
    ].join("\n");
  }
  toggle.addEventListener("click", () => {
    diagnostics.hidden = !diagnostics.hidden;
    toggle.textContent = diagnostics.hidden
      ? "Show diagnostics"
      : "Hide diagnostics";
    measure();
  });
  window.addEventListener("resize", measure);
  const formatMultipleLocationLabel = (label) =>
    label
      .trim()
      .replace(/^\s*,\s*/, "")
      .replace(/\s*,\s*$/, "")
      .replace(/\s{2,}/g, " ");
  const normalizeMultipleLocationLabels = () =>
    document
      .querySelectorAll(".multiple-location-item > span")
      .forEach((label) => {
        const formatted = formatMultipleLocationLabel(label.textContent);
        if (label.textContent !== formatted) label.textContent = formatted;
      });
  const multipleLocationLabelObserver = new MutationObserver(
    normalizeMultipleLocationLabels,
  );
  multipleLocationLabelObserver.observe(
    document.querySelector(".multiple-location-list"),
    { childList: true, subtree: true },
  );
  normalizeMultipleLocationLabels();
  jobBasicsPanel.querySelector(".grade-level-field label").innerHTML =
    "Grade Level(s) <small>(Recommended)</small>";
  jobBasicsPanel.querySelector(".subject-area-field label").innerHTML =
    "Subject Area(s) <small>(Recommended)</small>";
  const employmentTypeStep2 = jobBasicsPanel.querySelector(
    "#employment-type-step2",
  );
  const syncVolunteerCompensation = () => {
    compensationSection.hidden = employmentTypeStep2.value === "Volunteer";
  };
  employmentTypeStep2.addEventListener("change", () => {
    syncVolunteerCompensation();
    refreshNextAction();
  });
  syncVolunteerCompensation();
  const step3Panel = document.createElement("article");
  step3Panel.className = "panel";
  step3Panel.id = "step-03-job-description";
  step3Panel.dataset.view = "step-03-job-description";
  jobBasicsPanel.after(step3Panel);
  statePanels["step-03-job-description"] = step3Panel;
  const clipboardDiagnosticsPanel = document.createElement("article");
  clipboardDiagnosticsPanel.className = "panel";
  clipboardDiagnosticsPanel.id = "step-03-clipboard-diagnostics";
  clipboardDiagnosticsPanel.dataset.view = "step-03-clipboard-diagnostics";
  clipboardDiagnosticsPanel.hidden = true;
  step3Panel.after(clipboardDiagnosticsPanel);
  statePanels["step-03-clipboard-diagnostics"] = clipboardDiagnosticsPanel;
  const step3Option = select.querySelector(
    'option[value="step-03-job-description"]',
  );
  if (step3Option) step3Option.disabled = false;
  const step2Content = document.createElement("div");
  step2Content.className = "wizard-shell-content wizard-shell-content-step2";
  const step2AuthoringColumn = document.createElement("div");
  step2AuthoringColumn.className = "step2-authoring-column";
  step2AuthoringColumn.append(
    ...[...jobBasicsPanel.children].filter(
      (child) => !child.classList.contains("job-basics-heading"),
    ),
  );
  const step2PreviewPane = document.createElement("aside");
  step2PreviewPane.className = "step2-preview-pane step3-preview-pane";
  step2PreviewPane.setAttribute("aria-label", "Listing Preview");
  step2PreviewPane.innerHTML = '<div class="step3-preview-heading"><div><h3>Listing Preview</h3><p>Live cumulative preview.</p></div><span>Live preview</span></div><div id="step2-preview" class="step3-preview-card"></div>';
  step2Content.append(step2AuthoringColumn, step2PreviewPane);
  const authorityContent = document.createElement("div");
  authorityContent.className = "wizard-shell-content wizard-shell-content-authority";
  const authoritySource = document.createElement("div");
  authoritySource.innerHTML = step2BaseMarkup;
  authoritySource.querySelector(".job-basics-heading")?.remove();
  authorityContent.innerHTML = authoritySource.innerHTML.replaceAll(
    "job-title-step2",
    "job-title-authority",
  ).replaceAll("grade-levels-step2", "grade-levels-authority").replaceAll(
    "subject-areas-step2",
    "subject-areas-authority",
  );
  const step3Content = document.createElement("section");
  step3Content.className = "position-classification-section step3-foundation-card";
  step3Content.innerHTML = `
    <div class="step3-authoring-layout">
      <div class="step3-authoring-pane">
        <div class="step3-field step3-description-field">
          <label for="step3-description-editor">Job Description <span aria-hidden="true">*</span></label>
          <p class="step3-field-help">Paste your existing job description or write from scratch. Formatting from Word, Google Docs, district websites, and most ATS systems will be preserved.</p>
          <div class="step3-toolbar" role="toolbar" aria-label="Job Description formatting">
            <select data-format-command="formatBlock" aria-label="Paragraph style"><option value="p">Paragraph</option><option value="h3">Heading</option></select>
            <button type="button" data-format-command="bold" aria-label="Bold"><strong>B</strong></button><button type="button" data-format-command="italic" aria-label="Italic"><em>I</em></button><button type="button" data-format-command="insertUnorderedList" aria-label="Bulleted list">•</button><button type="button" data-format-command="insertOrderedList" aria-label="Numbered list">1.</button><button type="button" data-format-command="createLink" aria-label="Insert link">Link</button><button type="button" data-format-command="removeFormat" aria-label="Clear formatting">Clear formatting</button>
          </div>
          <div id="step3-description-editor" class="step3-editor step3-description-editor" contenteditable="true" role="textbox" aria-multiline="true" aria-label="Job Description"></div>
          <div class="step3-counter"><span data-counter-for="step3-description-editor">0</span> characters</div>
        </div>
        <div class="step3-field step3-summary-field">
          <label for="step3-summary">Short Summary <small>(Recommended)</small></label><p class="step3-field-help">Used in search results, featured placements, shared links, and promotions. A concise summary helps teachers understand the opportunity before opening the full listing.</p>
          <textarea id="step3-summary" maxlength="160" rows="3"></textarea><div class="step3-counter"><span data-counter-for="step3-summary">0</span>/160 characters</div>
        </div>
        <div class="step3-optional-intro"><h4>Optional Fields</h4><p>The following sections are optional, but providing additional detail helps teachers better understand your opportunity and improves matching and discovery throughout Teachers.Net.</p></div>
        <div class="step3-optional-sections">
          ${["Requirements / Qualifications","About Our School"].map((title,index)=>{const limit=5000; return `<details><summary>${title}</summary><div id="step3-optional-${index+1}" class="step3-editor step3-optional-editor" data-maxlength="${limit}" contenteditable="true" role="textbox" aria-label="${title}"></div><p class="step3-truncation-notice" role="status" aria-live="polite" hidden>Teachers.Net imported the first 5,000 characters. Additional content was omitted.</p><div class="step3-counter step3-optional-counter" hidden><span data-counter-for="step3-optional-${index+1}">0</span>/${limit} characters</div></details>`;}).join("")}
          <details class="step3-benefits">
            <summary>Benefits</summary>
            <div id="step3-benefits-selected" class="step3-benefits-selected" aria-live="polite"></div>
            <div id="step3-benefits-categories" class="step3-benefits-categories"></div>
            <label class="step3-benefits-additional-toggle"><input id="step3-benefits-additional-enabled" type="checkbox"> <span>Additional benefits</span></label>
            <p id="step3-benefits-additional-help" class="step3-benefits-additional-help" hidden>Describe any benefits not listed above.</p>
            <textarea id="step3-benefits-additional" maxlength="300" rows="3" aria-label="Additional benefits" aria-describedby="step3-benefits-additional-help" hidden></textarea>
            <div class="step3-counter" hidden><span data-counter-for="step3-benefits-additional">0</span>/300 characters</div>
          </details>
        </div>
      </div>
      <aside class="step3-preview-pane" aria-label="Listing Preview"><div class="step3-preview-heading"><div><h3>Listing Preview</h3><p>This is how your job listing will look to teachers.</p></div><span>Live preview</span></div><div id="step3-preview" class="step3-preview-card"></div><p class="step3-preview-note">Step 5 remains the canonical full review surface.</p></aside>
    </div>`;
  const clipboardDiagnosticsContent = document.createElement("section");
  clipboardDiagnosticsContent.className = "clipboard-diagnostics-content";
  clipboardDiagnosticsContent.innerHTML = `<div class="clipboard-diagnostics-intro"><h2>Clipboard Diagnostics</h2><p>Static diagnostic artifact · not production behavior</p></div><div class="clipboard-diagnostics-controls"><label for="clipboard-source-label">Source label</label><select id="clipboard-source-label"><option>Microsoft Word</option><option>Google Docs</option><option>Indeed</option><option>Outlook</option><option>Other</option></select><button type="button" class="button secondary" data-clipboard-capture>Capture Clipboard Payload</button><button type="button" class="button secondary" data-clipboard-reset>Reset</button></div><div id="clipboard-capture-surface" class="clipboard-capture-surface" contenteditable="true" role="textbox" aria-label="Paste source here">Paste source here</div><div class="clipboard-diagnostics-meta" data-clipboard-meta>No capture yet.</div><div class="clipboard-diagnostics-actions"><button type="button" class="button secondary" data-clipboard-copy="html">Copy raw HTML</button><button type="button" class="button secondary" data-clipboard-copy="text">Copy raw text</button><button type="button" class="button primary" data-clipboard-download>Download capture bundle</button></div><div class="clipboard-diagnostics-panels"><div><h3>text/html</h3><pre data-clipboard-output="html"></pre></div><div><h3>text/plain</h3><pre data-clipboard-output="text"></pre></div><div><h3>Clipboard MIME types</h3><pre data-clipboard-output="types"></pre></div></div>`;
  clipboardDiagnosticsPanel.append(clipboardDiagnosticsContent);
  let clipboardCapture = null;
  const clipboardSha256 = async (value) => { const digest = await crypto.subtle.digest("SHA-256", new TextEncoder().encode(value)); return [...new Uint8Array(digest)].map((byte) => byte.toString(16).padStart(2, "0")).join(""); };
  const clipboardRender = () => {
    const meta = clipboardDiagnosticsContent.querySelector("[data-clipboard-meta]");
    if (!clipboardCapture) { meta.textContent = "No capture yet."; clipboardDiagnosticsContent.querySelectorAll("[data-clipboard-output]").forEach((output) => { output.textContent = ""; }); return; }
    meta.textContent = `Captured ${clipboardCapture.timestamp} · ${clipboardCapture.sourceLabel} · ${clipboardCapture.userAgent} · build ${clipboardCapture.build}`;
    clipboardDiagnosticsContent.querySelector('[data-clipboard-output="html"]').textContent = clipboardCapture.html;
    clipboardDiagnosticsContent.querySelector('[data-clipboard-output="text"]').textContent = clipboardCapture.text;
    clipboardDiagnosticsContent.querySelector('[data-clipboard-output="types"]').textContent = clipboardCapture.types.join("\n");
  };
  const clipboardCaptureEvent = async (event) => {
    const data = event.clipboardData;
    if (!data) return;
    event.preventDefault();
    clipboardCapture = { html: data.getData("text/html"), text: data.getData("text/plain"), types: [...data.types], timestamp: new Date().toISOString(), userAgent: navigator.userAgent, build: workbenchBuild, sourceLabel: clipboardDiagnosticsContent.querySelector("#clipboard-source-label").value, htmlSha256: await clipboardSha256(data.getData("text/html")), textSha256: await clipboardSha256(data.getData("text/plain")) };
    localStorage.setItem("jc053-clipboard-capture", JSON.stringify(clipboardCapture));
    clipboardRender();
  };
  const clipboardDownload = () => {
    if (!clipboardCapture) return;
    const stamp = clipboardCapture.timestamp.replace(/[-:]/g, "").replace(/\.\d{3}Z$/, "Z");
    [["html", clipboardCapture.html, "text/html"], ["txt", clipboardCapture.text, "text/plain"], ["json", JSON.stringify(clipboardCapture, null, 2), "application/json"]].forEach(([extension, value, type]) => { const link = document.createElement("a"); link.href = URL.createObjectURL(new Blob([value], { type })); link.download = `clipboard-capture-${stamp}.${extension}`; link.click(); URL.revokeObjectURL(link.href); });
  };
  const clipboardSurface = clipboardDiagnosticsContent.querySelector("#clipboard-capture-surface");
  clipboardSurface.addEventListener("paste", clipboardCaptureEvent);
  clipboardDiagnosticsContent.querySelector("[data-clipboard-capture]").addEventListener("click", () => { if (!clipboardCapture) clipboardSurface.focus(); else clipboardDownload(); });
  clipboardDiagnosticsContent.querySelector("[data-clipboard-reset]").addEventListener("click", () => { clipboardCapture = null; localStorage.removeItem("jc053-clipboard-capture"); clipboardRender(); clipboardSurface.textContent = "Paste source here"; });
  clipboardDiagnosticsContent.querySelectorAll("[data-clipboard-copy]").forEach((button) => button.addEventListener("click", () => { if (clipboardCapture) navigator.clipboard?.writeText(clipboardCapture[button.dataset.clipboardCopy]); }));
  clipboardDiagnosticsContent.querySelector("[data-clipboard-download]").addEventListener("click", clipboardDownload);
  try { const saved = JSON.parse(localStorage.getItem("jc053-clipboard-capture") || "null"); if (saved) { clipboardCapture = saved; clipboardRender(); } } catch {}
  const step3Editors = ["#step3-description-editor", "#step3-optional-1", "#step3-optional-2"];
  const step3NormalizeGoogleDocs = (node) => {
    const googleRedirect = [...node.querySelectorAll("a")].some((link) => /^https:\/\/www\.google\.com\/url(?:\?|$)/i.test(link.getAttribute("href") || ""));
    const googleClass = [...node.querySelectorAll("*")].some((item) => /(?:lst-kix|docs-|google-docs|google-docs)/i.test(item.getAttribute("class") || ""));
    if (!googleRedirect && !googleClass) return node;
    node.querySelectorAll("a").forEach((link) => {
      const href = link.getAttribute("href") || "";
      if (!/^https:\/\/www\.google\.com\/url(?:\?|$)/i.test(href)) return;
      let destination = "";
      try { destination = new URL(href).searchParams.get("q") || ""; } catch { destination = ""; }
      try { destination = decodeURIComponent(destination); } catch { destination = ""; }
      if (/^https?:\/\//i.test(destination)) link.setAttribute("href", destination);
      else link.removeAttribute("href");
    });
    node.querySelectorAll("UL,OL,LI").forEach((item) => {
      item.removeAttribute("class");
      ["margin-left","margin-right","margin-inline-start","margin-inline-end","padding-left","padding-right","padding-inline-start","padding-inline-end","text-indent","list-style","list-style-type","list-style-position"].forEach((property) => item.style.removeProperty(property));
      if (!item.getAttribute("style")?.trim()) item.removeAttribute("style");
    });
    const blank = (item) => item.tagName === "P" && !item.textContent.trim() && [...item.childNodes].every((child) => child.nodeType === Node.TEXT_NODE ? !child.textContent.trim() : child.nodeType === Node.ELEMENT_NODE && child.tagName === "BR");
    while (node.lastElementChild && blank(node.lastElementChild)) node.lastElementChild.remove();
    return node;
  };
  const step3ConvertWordLists = (node) => {
    const wordParagraph = (item) => item.tagName === "P" && (item.className.match(/MsoListParagraph/i) || /(?:^|;)\s*mso-list\s*:/i.test(item.getAttribute("style") || "") || item.querySelector('[style*="mso-list:Ignore"], [style*="mso-list: Ignore"]'));
    const markerInfo = (item) => {
      const marker = item.querySelector('[style*="mso-list:Ignore"], [style*="mso-list: Ignore"]');
      const markerText = marker?.textContent || "";
      const source = `${markerText} ${item.textContent}`;
      const levelMatch = (item.getAttribute("style") || "").match(/level\s*(\d+)/i);
      const bulletMarker = /^[\s\u00a0]*[•·▪◦○‣⁃]/.test(markerText) || /^[\s\u00a0]*[•·▪◦○‣⁃]/.test(item.textContent);
      const ordered = !bulletMarker && (/\d/.test(markerText) || /^[\s\u00a0]*\d+[.)]\s+/.test(source) || /^[\s\u00a0]*[A-Za-z][.)]/.test(markerText));
      return { level: levelMatch ? Math.max(1, Number(levelMatch[1])) : 1, ordered };
    };
    const cleanItem = (item, info) => {
      const clone = item.cloneNode(true);
      clone.removeAttribute("class"); clone.removeAttribute("style");
      clone.querySelectorAll('[style*="mso-list:Ignore"], [style*="mso-list: Ignore"]').forEach((marker) => marker.remove());
      clone.querySelectorAll("*").forEach((child) => {
        ["mso-list", "mso-list:ignore", "mso-margin-left-alt", "mso-text-indent-alt", "text-indent"].forEach((property) => child.style.removeProperty(property));
        if (!child.getAttribute("style")?.trim()) child.removeAttribute("style");
      });
      while (clone.firstChild && clone.firstChild.nodeType === Node.TEXT_NODE && /^[\s\u00a0]*(?:[•·▪◦○‣⁃]|\d+[.)])?[\s\u00a0]*/.test(clone.firstChild.textContent)) {
        clone.firstChild.textContent = clone.firstChild.textContent.replace(/^[\s\u00a0]*(?:[•·▪◦○‣⁃]|\d+[.)])?[\s\u00a0]*/, "");
        if (!clone.firstChild.textContent) clone.removeChild(clone.firstChild);
        else break;
      }
      const li = document.createElement("li");
      li.append(...clone.childNodes);
      return li;
    };
    const children = [...node.children], replacement = document.createDocumentFragment();
    let stack = [], lastWasWord = false;
    children.forEach((child) => {
      if (!wordParagraph(child)) { replacement.append(child); stack = []; lastWasWord = false; return; }
      const info = markerInfo(child);
      while (stack.length && stack.at(-1).level > info.level) stack.pop();
      let list = stack.at(-1)?.list;
      if (stack.length && stack.at(-1).level === info.level && list?.tagName !== (info.ordered ? "OL" : "UL")) stack.pop();
      list = stack.at(-1)?.list;
      if (!list || stack.at(-1).level !== info.level) {
        list = document.createElement(info.ordered ? "ol" : "ul");
        const parentLi = stack.at(-1)?.li;
        (parentLi || replacement).append(list);
      }
      const li = cleanItem(child, info); list.append(li);
      stack.push({ level: info.level, list, li }); lastWasWord = true;
    });
    node.replaceChildren(replacement);
    return node;
  };
  const step3PlainText = (html) => {
    const node = document.createElement("div");
    node.innerHTML = html || "";
    node.querySelectorAll("script,style,iframe,object,embed").forEach((item) => item.remove());
    node.querySelectorAll("*").forEach((item) => [...item.attributes].forEach((attr) => {
      if (attr.name.toLowerCase().startsWith("on")) item.removeAttribute(attr.name);
      if (item.tagName === "A" && attr.name === "href" && !/^https?:/i.test(attr.value)) item.removeAttribute(attr.name);
    }));
    step3NormalizeGoogleDocs(node);
    step3ConvertWordLists(node);
    node.querySelectorAll("UL,OL,LI").forEach((item) => {
      ["margin-left","margin-right","margin-inline-start","margin-inline-end","padding-left","padding-right","padding-inline-start","padding-inline-end","text-indent","list-style-position","mso-list","mso-margin-left-alt","mso-text-indent-alt"].forEach((property) => item.style.removeProperty(property));
      if (!item.getAttribute("style")?.trim()) item.removeAttribute("style");
    });
    node.querySelectorAll("P").forEach((item) => {
      ["margin-top","margin-bottom","margin-block-start","margin-block-end"].forEach((property) => item.style.removeProperty(property));
      if (!item.getAttribute("style")?.trim()) item.removeAttribute("style");
    });
    return node;
  };
  const step3Sanitized = (html) => {
    const node = step3PlainText(html), allowed = new Set(["P","BR","STRONG","B","EM","I","SPAN","UL","OL","LI","A","H3"]);
    const blockTags = new Set(["H1","H2","H3","H4","H5","H6","P","UL","OL","TABLE"]);
    [...node.querySelectorAll("div")].reverse().forEach((item) => {
      const isWrapper = item.parentNode === node && [...item.children].some((child) => blockTags.has(child.tagName));
      if (isWrapper) item.replaceWith(...item.childNodes);
      else {
        if (!item.textContent.trim() && !item.querySelector("br")) { item.remove(); return; }
        const paragraph = document.createElement("p");
        paragraph.innerHTML = item.innerHTML;
        item.replaceWith(paragraph);
      }
    });
    node.querySelectorAll("*").forEach((item) => { if (!allowed.has(item.tagName)) { item.replaceWith(...item.childNodes); } });
    node.querySelectorAll("B").forEach((item) => { const replacement = document.createElement("strong"); replacement.replaceChildren(...item.childNodes); item.replaceWith(replacement); });
    node.querySelectorAll("I").forEach((item) => { const replacement = document.createElement("em"); replacement.replaceChildren(...item.childNodes); item.replaceWith(replacement); });
    node.querySelectorAll("SPAN").forEach((item) => {
      const style = item.getAttribute("style") || "";
      const bold = /font-weight\s*:\s*(?:bold|[6-9]00)/i.test(style), italic = /font-style\s*:\s*italic/i.test(style);
      if (bold || italic) { const replacement = document.createElement(bold && italic ? "strong" : bold ? "strong" : "em"); replacement.replaceChildren(...item.childNodes); if (bold && italic) { const emphasis = document.createElement("em"); emphasis.replaceChildren(...replacement.childNodes); replacement.append(emphasis); } item.replaceWith(replacement); }
      else item.replaceWith(...item.childNodes);
    });
    node.querySelectorAll("*").forEach((item) => {
      [...item.attributes].forEach((attribute) => {
        const name = attribute.name.toLowerCase();
        if (item.tagName === "A" && name === "href") return;
        if (item.tagName === "OL" && name === "start" && /^\d+$/.test(attribute.value)) return;
        item.removeAttribute(attribute.name);
      });
    });
    [...node.children].forEach((item, index, items) => {
      const next = items[index + 1];
      if (next && ["UL", "OL"].includes(item.tagName) && item.tagName === next.tagName) { item.append(...next.childNodes); next.remove(); }
    });
    let previousBlank = false;
    [...node.children].forEach((item) => {
      const blank = item.tagName === "P" && !item.textContent.trim() && [...item.childNodes].every((child) => child.nodeType === Node.TEXT_NODE ? !child.textContent.trim() : child.nodeType === Node.ELEMENT_NODE && child.tagName === "BR");
      if (blank && previousBlank) item.remove();
      else previousBlank = blank;
    });
    while (node.lastElementChild && node.lastElementChild.tagName === "P" && !node.lastElementChild.textContent.trim() && !node.lastElementChild.querySelector("img")) node.lastElementChild.remove();
    return node.innerHTML;
  };
  const step3CanonicalizeClipboard = (html, text = "") => {
    const source = html ? "html" : "plain-text";
    const input = html || step3PlainPasteHtml(text);
    const output = step3Sanitized(input);
    const sourceFamily = /MsoListParagraph|mso-list|Microsoft Office/i.test(html) ? "Microsoft Word" : /lst-kix|docs-internal|google/i.test(html) ? "Google Docs" : /Indeed Sans|jobDescriptionTitle|indeed/i.test(html) ? "Indeed" : /olk-copy|Outlook|Aptos/i.test(html) ? "Outlook" : html ? "Generic HTML" : "Plain text";
    window.__jc053LastClipboardTransform = { source, sourceFamily, confidence: sourceFamily === "Generic HTML" ? "medium" : "high", inputLength: input.length, outputLength: output.length, fallback: !html, transformations: ["safety-prefilter", "semantic-conversion", "block-normalization", "list-normalization", "blank-line-normalization", "link-normalization", "presentation-stripping", "structural-validation"], warnings: [] };
    return output;
  };
  step3Content.querySelectorAll("[contenteditable]").forEach((editor) => {
    const normalized = step3Sanitized(editor.innerHTML);
    if (normalized !== editor.innerHTML) editor.innerHTML = normalized;
  });
  const step3Escape = (value) => String(value).replace(/[&<>"']/g, (character) => ({
    "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#39;",
  }[character]));
  const step3PlainPasteHtml = (text) => String(text || "")
    .replace(/\r\n?/g, "\n")
    .split("\n")
    .map((line) => `<p>${step3Escape(line)}</p>`)
    .join("");
  const step3Text = (html) => step3PlainText(html).textContent.replace(/\s+/g, " ").trim();
  const step3Benefits = {
    Insurance: ["Medical Insurance", "Dental Insurance", "Vision Insurance", "Life Insurance", "Disability Insurance"],
    Financial: ["Retirement Plan", "401(k) Plan", "403(b) Plan", "Pension Plan", "Employer Match", "Tuition Assistance", "Relocation Assistance"],
    Scheduling: ["Paid Time Off", "Paid Holidays", "Paid Sick Leave", "Personal Days", "Flexible Schedule", "Remote / Hybrid Eligible"],
    Other: ["Professional Development", "Mentoring / Coaching", "Conference Support", "Classroom Resources", "Employee Assistance Program", "Wellness Program", "Student Loan Assistance"],
  };
  const step3State = { previewTimer: null, selectedBenefits: new Set(), expandedPreviewSections: new Set() };
  let step3SavedRange = null;
  const step3EditorForRange = (range) => { const node=range?.commonAncestorContainer; return (node?.nodeType === Node.ELEMENT_NODE ? node : node?.parentElement)?.closest("[contenteditable]"); };
  const clearStep3Formatting = () => {
    const saved = step3SavedRange?.cloneRange(), active = document.activeElement?.closest("[contenteditable]");
    const editor = step3EditorForRange(saved) || active;
    if (!editor) return;
    const range = saved && step3EditorForRange(saved) === editor && !saved.collapsed ? saved : null;
    const container = document.createElement("div");
    container.innerHTML = range ? (() => { const fragment = range.cloneContents(); const holder = document.createElement("div"); holder.append(...fragment.childNodes); return holder.innerHTML; })() : editor.innerHTML;
    const normalize = (node) => {
      [...node.childNodes].forEach((child) => {
        if (child.nodeType !== Node.ELEMENT_NODE) return;
        const tag = child.tagName;
        normalize(child);
        if (tag === "A" || tag === "SPAN" || tag === "B" || tag === "I" || tag === "STRONG" || tag === "EM") child.replaceWith(...child.childNodes);
        else if (/^H[1-6]$/.test(tag)) { const paragraph = document.createElement("p"); paragraph.append(...child.childNodes); child.replaceWith(paragraph); }
        [...(child.attributes || [])].forEach((attribute) => { if (!(tag === "OL" && attribute.name === "start")) child.removeAttribute(attribute.name); });
      });
    };
    normalize(container);
    if (range) { const fragment = document.createDocumentFragment(); fragment.append(...container.childNodes); range.deleteContents(); range.insertNode(fragment); }
    else editor.innerHTML = container.innerHTML;
    updateStep3Counters(); scheduleStep3Preview(); refreshNextAction(); editor.focus();
  };
  const renderStep3Benefits = (activeRoot = document) => {
    const selected = activeRoot.querySelector("#step3-benefits-selected"), categories = activeRoot.querySelector("#step3-benefits-categories");
    if (!selected || !categories) return;
    const values = [...step3State.selectedBenefits];
    selected.innerHTML = values.length
      ? `<span class="step3-benefits-selected-label">Benefits offered:</span> ${values.map((value) => `<button type="button" class="step3-benefits-selected-item" data-benefit-remove="${step3Escape(value)}" aria-label="Remove ${step3Escape(value)}">${step3Escape(value)} <span aria-hidden="true">×</span></button>`).join(", ")} <button type="button" class="step3-benefits-clear" data-benefit-clear>Clear all</button>`
      : `<span class="step3-benefits-selected-label">Benefits offered:</span> <span class="step3-benefits-selected-guidance"><span class="step3-benefits-help-click">Click</span> any benefit to add or remove it.</span>`;
    selected.closest(".step3-benefits")?.classList.toggle("is-empty", values.length === 0);
    categories.innerHTML = Object.entries(step3Benefits).map(([category, options]) => { const availableOptions=options.filter((option) => !step3State.selectedBenefits.has(option)); return availableOptions.length ? `<div class="step3-benefits-category" data-benefit-category="${step3Escape(category)}"><span class="step3-benefits-category-label">${category}:</span> <span class="step3-benefits-options">${availableOptions.map((option) => `<button type="button" class="step3-benefit-option" data-benefit-option="${step3Escape(option)}" data-benefit-category="${step3Escape(category)}">${step3Escape(option)}</button>`).join(", ")}</span></div>` : ""; }).join("");
    categories.hidden = !categories.querySelector("[data-benefit-option]");
  };
  const initializeStep3Benefits = (activeStep3Root) => {
    if (!activeStep3Root?.matches?.("#step-03-job-description")) return;
    renderStep3Benefits(activeStep3Root);
  };
  const step3BenefitsText = () => [...step3State.selectedBenefits].join(", ");
  const step3BenefitsActive = () => step3State.selectedBenefits.size > 0 || !!document.querySelector("#step3-benefits-additional-enabled")?.checked && !!document.querySelector("#step3-benefits-additional")?.value.trim();
  const renderStep3Preview = () => {
    const preview = document.querySelector("#step3-preview");
    if (!preview) return;
    const description = document.querySelector("#step3-description-editor"), requirements = document.querySelector("#step3-requirements-editor"), summary = document.querySelector("#step3-summary");
    const section = (title, html, key) => step3Text(html) ? `<h5>${title}</h5><div class="step3-preview-section" data-preview-section="${key}"><div class="step3-preview-section-body" id="step3-preview-${key}">${step3Sanitized(html)}</div><button type="button" class="step3-preview-toggle" data-preview-toggle="${key}" aria-controls="step3-preview-${key}" aria-expanded="false">Show more…</button></div>` : "";
    const additional = document.querySelector("#step3-benefits-additional"), additionalEnabled = wizardState.description.additionalBenefitsEnabled, additionalText = additionalEnabled ? wizardState.description.additionalBenefits.trim() : "";
    const benefits = step3BenefitsActive() ? `<h5>Benefits</h5><div>${step3Escape([step3BenefitsText(), additionalText].filter(Boolean).join(", "))}</div>` : "";
    const compactSummary = wizardState.description.shortSummary ? step3Escape(wizardState.description.shortSummary) : "Add a short summary to preview how this listing may appear across Teachers.Net.";
    preview.innerHTML = `<div class="step3-compact-listing"><strong>${step3Escape(wizardState.basics.jobTitle.trim() || "Teacher position")}</strong><span>${step3Escape(wizardState.school.displayName)} · ${step3Escape(wizardState.school.location)}</span><p>${compactSummary}</p></div><h4>Listing Preview</h4>${section("Job Description", wizardState.description.jobDescription, "description")}${section("Requirements / Qualifications", wizardState.description.requirements, "requirements")}${section("About Our School", wizardState.description.aboutSchool, "about")}${benefits}`;
    syncPreviewCollapsibles();
  };
  const syncPreviewCollapsibles = () => {
    const preview = document.querySelector("#step3-preview");
    if (!preview) return;
    preview.querySelectorAll("[data-preview-section]").forEach((section) => {
      const body = section.querySelector(".step3-preview-section-body"), button = section.querySelector("[data-preview-toggle]"), lineHeight = parseFloat(getComputedStyle(body).lineHeight) || 18, limit = lineHeight * 8, overflowing = body.scrollHeight > limit + 1, expanded = step3State.expandedPreviewSections.has(section.dataset.previewSection);
      button.hidden = !overflowing;
      section.classList.toggle("is-collapsed", overflowing && !expanded);
      button.setAttribute("aria-expanded", String(expanded));
      button.textContent = expanded ? "Show less" : "Show more…";
    });
  };
  const scheduleStep3Preview = () => { clearTimeout(step3State.previewTimer); step3State.previewTimer = setTimeout(renderStep3Preview, 120); };
  const updateStep3Counters = () => document.querySelectorAll("[data-counter-for]").forEach((counter) => { const field=document.querySelector(`#${counter.dataset.counterFor}`); const length=field?.isContentEditable ? step3Text(field.innerHTML).length : (field?.value || "").length; counter.textContent=length; const limit=Number(field?.dataset.maxlength || 0); counter.closest(".step3-optional-counter")?.toggleAttribute("hidden", !limit || length < limit - 100); });
  const truncateStep3Html = (html, limit) => { const container=document.createElement("div"); container.innerHTML=html; let remaining=Math.max(0, limit), truncated=false; const walker=document.createTreeWalker(container, NodeFilter.SHOW_TEXT), nodes=[]; while(walker.nextNode()) nodes.push(walker.currentNode); nodes.forEach((node) => { if(!node.textContent) return; if(remaining <= 0){ node.remove(); truncated=true; } else if(node.textContent.length > remaining){ node.textContent=node.textContent.slice(0, remaining); remaining=0; truncated=true; } else remaining-=node.textContent.length; }); container.querySelectorAll("p,h1,h2,h3,h4,h5,h6,li").forEach((node) => { if(!step3Text(node.innerHTML) && !node.querySelector("img")) node.remove(); }); container.querySelectorAll("ul,ol").forEach((node) => { if(!node.querySelector("li")) node.remove(); }); while(container.lastElementChild && ["P","H1","H2","H3","H4","H5","H6"].includes(container.lastElementChild.tagName) && !step3Text(container.lastElementChild.innerHTML) && !container.lastElementChild.querySelector("img")) container.lastElementChild.remove(); return { html:container.innerHTML, truncated }; };
  const collapseStep3Description = () => { const editor=document.querySelector("#step3-description-editor"); if (!editor || step3Text(editor.innerHTML).length <= 500) return; editor.classList.add("is-collapsed"); };
  const step3LinkForSelection = () => { const selection=window.getSelection(), node=selection?.anchorNode; return (node?.nodeType === Node.ELEMENT_NODE ? node : node?.parentElement)?.closest("a"); };
  const editStep3Link = () => {
    const link = step3LinkForSelection(), current = link?.getAttribute("href") || "", value = window.prompt(link ? "Edit link URL (leave blank to remove)" : "Link URL", current);
    if (value === null) return;
    if (!value.trim()) { if (link) link.replaceWith(...link.childNodes); else document.execCommand("unlink", false, null); }
    else if (/^https?:/i.test(value.trim())) { if (link) link.setAttribute("href", value.trim()); else document.execCommand("createLink", false, value.trim()); }
    else return;
    updateStep3Counters(); scheduleStep3Preview(); refreshNextAction();
  };
  document.addEventListener("selectionchange", () => { const selection=window.getSelection(), range=selection?.rangeCount ? selection.getRangeAt(0) : null; if(range && step3EditorForRange(range)) step3SavedRange=range.cloneRange(); });
  step3Content.querySelectorAll("[data-format-command]").forEach((control) => {
    control.addEventListener("mousedown", (event) => { if(event.button === 0) event.preventDefault(); });
    control.addEventListener("click", () => {
      const command=control.dataset.formatCommand;
      if(command === "removeFormat") clearStep3Formatting();
      else { if(command === "createLink") editStep3Link(); else { document.execCommand(command,false,control.tagName === "SELECT" ? control.value : null); updateStep3Counters(); scheduleStep3Preview(); } }
    });
  });
  step3Content.addEventListener("focusin", (event) => { if(event.target.id === "step3-description-editor") event.target.classList.remove("is-collapsed"); });
  step3Content.addEventListener("beforeinput", (event) => { const editor=event.target.closest("[contenteditable][data-maxlength]"); if(!editor || !["insertText","insertCompositionText"].includes(event.inputType)) return; const selection=window.getSelection(), range=selection?.rangeCount ? selection.getRangeAt(0) : null, selectedLength=range && editor.contains(range.commonAncestorContainer) ? range.toString().replace(/\s+/g," ").length : 0, currentLength=step3Text(editor.innerHTML).length, incomingLength=String(event.data || "").replace(/\s+/g," ").length; if(currentLength-selectedLength+incomingLength > Number(editor.dataset.maxlength)){ event.preventDefault(); } });
  step3Content.addEventListener("input", (event) => { if(event.target.matches("[contenteditable], textarea")){ if(event.target.id === "step3-summary"){ event.target.value=event.target.value.slice(0,160).replace(/\s+/g," "); } if(event.target.isContentEditable) event.target.closest("details")?.querySelector(".step3-truncation-notice")?.setAttribute("hidden", ""); updateStep3Counters();scheduleStep3Preview();refreshNextAction();} });
  step3Content.addEventListener("keydown", (event) => { if(event.target.id === "step3-summary" && event.key === "Enter"){ event.preventDefault(); const start=event.target.selectionStart, end=event.target.selectionEnd; event.target.setRangeText(" ", start, end, "end"); event.target.dispatchEvent(new Event("input", { bubbles:true })); } });
  step3Content.addEventListener("change", (event) => { if (event.target.matches("#step3-benefits-additional-enabled")) { const field=document.querySelector("#step3-benefits-additional"), helper=document.querySelector("#step3-benefits-additional-help"), counter=document.querySelector('[data-counter-for="step3-benefits-additional"]')?.closest(".step3-counter"); field.hidden=!event.target.checked; if(helper) helper.hidden=!event.target.checked; if(counter) counter.hidden=!event.target.checked; updateStep3Counters(); scheduleStep3Preview(); } });
  step3Content.addEventListener("click", (event) => {
    const option = event.target.closest("[data-benefit-option]"), remove = event.target.closest("[data-benefit-remove]"), clear = event.target.closest("[data-benefit-clear]");
    if (option) { const value=option.dataset.benefitOption, keyboardActivation=event.detail === 0; step3State.selectedBenefits.add(value); wizardState.description.benefits=[...step3State.selectedBenefits]; renderStep3Benefits(); scheduleStep3Preview(); if (keyboardActivation) document.querySelector(`[data-benefit-remove="${CSS.escape(value)}"]`)?.focus(); else document.activeElement?.blur(); return; }
    if (remove) { const value=remove.dataset.benefitRemove, keyboardActivation=event.detail === 0; step3State.selectedBenefits.delete(value); wizardState.description.benefits=[...step3State.selectedBenefits]; renderStep3Benefits(); scheduleStep3Preview(); if (keyboardActivation) { const focusTarget=document.querySelector("[data-benefit-clear]") || document.querySelector("[data-benefit-remove]") || document.querySelector("[data-benefit-option]"); focusTarget?.focus(); } else document.activeElement?.blur(); return; }
    if (clear) { const keyboardActivation=event.detail === 0; step3State.selectedBenefits.clear(); wizardState.description.benefits=[]; renderStep3Benefits(); scheduleStep3Preview(); if (keyboardActivation) document.querySelector("[data-benefit-clear]")?.focus(); else document.activeElement?.blur(); }
  });
  step3Content.querySelectorAll("[contenteditable]").forEach((editor) => editor.addEventListener("paste", (event) => {
    event.preventDefault();
    const clipboard = event.clipboardData || window.clipboardData;
    const html = clipboard?.getData("text/html");
    const text = clipboard?.getData("text/plain") || "";
    const sanitized = step3CanonicalizeClipboard(html, text);
    const limit=Number(editor.dataset.maxlength || 0), currentLength=step3Text(editor.innerHTML).length, capacity=limit ? Math.max(0, limit-currentLength) : 0, result=limit ? truncateStep3Html(sanitized, capacity) : { html:sanitized, truncated:false };
    document.execCommand("insertHTML", false, result.html);
    if(result.truncated){ const notice=editor.closest("details")?.querySelector(".step3-truncation-notice"); if(notice) notice.hidden=false; }
    updateStep3Counters();
    scheduleStep3Preview();
    refreshNextAction();
  }));
  document.addEventListener("click", (event) => {
    const button = event.target.closest("[data-preview-toggle]");
    if (!button) return;
    const key = button.dataset.previewToggle, expanded = step3State.expandedPreviewSections.has(key);
    expanded ? step3State.expandedPreviewSections.delete(key) : step3State.expandedPreviewSections.add(key);
    syncPreviewCollapsibles();
  });
  requestAnimationFrame(() => renderStep3Benefits());
  renderStep3Preview();
  const step4State = { materials: new Set() };
  const step4Content = document.createElement("section");
  step4Content.className = "step4-foundation-layout";
  step4Content.innerHTML = `<div class="step4-form-column">
    <section class="step4-section"><div class="step4-section-heading"><span class="form-section-number">1</span><div><h3>Application Method</h3><p>Choose how candidates should apply.</p></div></div>
      <div class="step4-field"><label for="step4-method">Application Method <span aria-hidden="true">*</span></label><select id="step4-method"><option value="">Select application method</option><option value="url">External URL</option><option value="email">Email</option><option value="instructions">Instructions only</option></select></div>
      <div class="step4-field" data-step4-method="url" hidden><label for="step4-url">Application URL <span class="step4-required-marker" hidden>*</span></label><input id="step4-url" type="url" placeholder="https://example.org/apply" aria-describedby="step4-url-error"><p id="step4-url-error" class="step4-validation" hidden>Enter a complete web address beginning with http:// or https://.</p></div>
      <div class="step4-field" data-step4-method="email" hidden><label for="step4-email">Application Email <span class="step4-required-marker" hidden>*</span></label><input id="step4-email" type="email" placeholder="jobs@example.org" aria-describedby="step4-email-error"><p id="step4-email-error" class="step4-validation" hidden>Enter a valid application email address.</p></div>
      <div class="step4-field" data-step4-instructions hidden><label for="step4-instructions">Application Instructions <span class="step4-required-marker">*</span><span class="step4-optional-marker"> (Optional)</span></label><textarea id="step4-instructions" rows="3" placeholder="Tell candidates what to do next."></textarea></div>
    </section>
    <section class="step4-section"><div class="step4-section-heading"><span class="form-section-number">2</span><div><h3>Application Deadline</h3><p>Tell candidates when applications close.</p></div></div><div class="step4-compact-row"><div class="step4-field"><label for="step4-deadline-mode">Deadline Mode <span aria-hidden="true">*</span></label><select id="step4-deadline-mode"><option value="open" selected>Open until filled</option><option value="specific">Specific date</option><option value="none">Do not publish deadline</option></select></div><div class="step4-field" data-step4-specific hidden><label for="step4-deadline">Application Deadline <span aria-hidden="true">*</span></label><input id="step4-deadline" type="date"></div></div><label class="step4-check" data-step4-specific hidden><input id="step4-close-on-deadline" type="checkbox"> Close job on application deadline</label></section>
    <section class="step4-section"><div class="step4-section-heading"><span class="form-section-number">3</span><div><h3>Contact Information</h3><p>Choose the contact information candidates should see.</p></div></div><div class="step4-field"><label for="step4-contact-mode">Contact Information</label><select id="step4-contact-mode"><option value="default" selected>Use School / Jobsite default contact</option><option value="override">Override contact</option></select></div><div class="step4-default-contact">School / Jobsite default contact: jobs@lausd.net · (213) 241-1000</div><div class="step4-override-fields" hidden><div class="step4-field"><label for="step4-contact-name">Contact Name</label><input id="step4-contact-name" type="text"></div><div class="step4-field"><label for="step4-contact-email">Contact Email</label><input id="step4-contact-email" type="email" aria-describedby="step4-contact-email-error"><p id="step4-contact-email-error" class="step4-validation" hidden>Enter a valid application email address.</p></div><div class="step4-field"><label for="step4-contact-phone">Contact Phone</label><input id="step4-contact-phone" type="tel"></div></div><label class="step4-check step4-hide-contact"><input id="step4-hide-contact" type="checkbox"> Do not publish contact information</label></section>
    <section class="step4-section"><div class="step4-section-heading"><span class="form-section-number">4</span><div><h3>Required Application Materials</h3><p>Select any materials candidates should provide.</p></div></div><div class="step4-materials"><label><input type="checkbox" value="resume"> Resume / CV</label><label><input type="checkbox" value="cover"> Cover Letter</label><label><input type="checkbox" value="references"> References</label><label><input type="checkbox" value="credentials"> Credentials / Certificates</label><label><input type="checkbox" value="transcripts"> Transcripts</label><label><input type="checkbox" value="other"> Other</label></div><div class="step4-field" data-step4-other hidden><label for="step4-other-materials">Other Materials</label><input id="step4-other-materials" type="text"></div></section>
    <div class="step4-notice" role="note"><strong>Routing &amp; Privacy</strong><p>Teachers.Net does not collect or receive applications. Candidates will be directed to your selected destination.</p></div></div>
    <aside class="step4-preview-pane" aria-label="Listing Preview"><div class="step3-preview-heading"><div><h3>Listing Preview</h3><p>This is a workbench approximation.</p></div><span>Live preview</span></div><div id="step4-preview" class="step3-preview-card"></div></aside>`;
  const step4DefaultContact = { email: "jobs@lausd.net", phone: "(213) 241-1000" };
  step4Content.querySelector("#step4-contact-email").value = step4DefaultContact.email;
  step4Content.querySelector("#step4-contact-phone").value = step4DefaultContact.phone;
  const step4Icons = ["section-send", "section-calendar", "section-contact", "section-document"];
  step4Content.querySelectorAll(".step4-section-heading").forEach((heading, index) => { const marker = heading.querySelector(".form-section-number"); if (!marker) return; const icon = document.createElement("span"); icon.className = "section-icon"; icon.setAttribute("aria-hidden", "true"); icon.innerHTML = `<svg viewBox="0 0 24 24"><use href="#${step4Icons[index]}"></use></svg>`; marker.replaceWith(icon); });
  const step4Panel = document.createElement("article"); step4Panel.className = "panel"; step4Panel.id = "step-04-application-process"; step4Panel.dataset.view = "step-04-application-process"; step4Panel.hidden = true; step4Panel.append(step4Content); statePanels["step-04-application-process"] = step4Panel; step3Panel.after(step4Panel);
  const step4Escape = (value) => String(value || "").replace(/[&<>\"']/g, (c) => ({"&":"&amp;","<":"&lt;",">":"&gt;",'\"':"&quot;","'":"&#39;"}[c]));
  const step4UrlValid = (value) => { try { const url = new URL(String(value || "").trim()); return ["http:", "https:"].includes(url.protocol) && Boolean(url.hostname); } catch { return false; } };
  const step4EmailValid = (value) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(String(value || "").trim());
  const step4Ready = () => { const method=step4Content.querySelector("#step4-method")?.value, mode=step4Content.querySelector("#step4-deadline-mode")?.value, instructions=step4Content.querySelector("#step4-instructions")?.value.trim(), date=step4Content.querySelector("#step4-deadline")?.value, contact=step4Content.querySelector("#step4-contact-mode")?.value, email=step4Content.querySelector("#step4-contact-email")?.value.trim(); const destination=method==="url"?step4UrlValid(step4Content.querySelector("#step4-url")?.value):method==="email"?step4EmailValid(step4Content.querySelector("#step4-email")?.value):method==="instructions"?Boolean(instructions):false; return Boolean(method&&destination&&mode&&(mode!=="specific"||(date&&date>=new Date().toISOString().slice(0,10)))&&(!email||step4EmailValid(email))); };
  const step4Sync = (focusTarget = null, showErrors = false) => {
    const method=step4Content.querySelector("#step4-method")?.value, mode=step4Content.querySelector("#step4-deadline-mode")?.value, contact=step4Content.querySelector("#step4-contact-mode")?.value;
    const url=step4Content.querySelector("#step4-url"), email=step4Content.querySelector("#step4-email"), instructions=step4Content.querySelector("#step4-instructions"), date=step4Content.querySelector("#step4-deadline"), contactEmail=step4Content.querySelector("#step4-contact-email");
    step4Content.querySelectorAll("[data-step4-method]").forEach((f)=>f.hidden=f.dataset.step4Method!==method);
    const instructionField=step4Content.querySelector("[data-step4-instructions]"); instructionField.hidden=!method; instructionField.querySelector(".step4-required-marker").hidden=method!=="instructions"; instructionField.querySelector(".step4-optional-marker").hidden=method==="instructions"; instructions.required=method==="instructions";
    url.required=method==="url"; email.required=method==="email"; url.closest(".step4-field").querySelector(".step4-required-marker").hidden=method!=="url"; email.closest(".step4-field").querySelector(".step4-required-marker").hidden=method!=="email";
    step4Content.querySelectorAll("[data-step4-specific]").forEach((f)=>f.hidden=mode!=="specific"); date.required=mode==="specific";
    step4Content.querySelector(".step4-override-fields").hidden=contact!=="override"; step4Content.querySelector(".step4-default-contact").hidden=contact!=="default"; step4Content.querySelector(".step4-hide-contact").hidden=contact!=="default"; contactEmail.required=false;
    step4Content.querySelector("[data-step4-other]").hidden=!step4State.materials.has("other");
    if (showErrors) { const urlError=step4Content.querySelector("#step4-url-error"), emailError=step4Content.querySelector("#step4-email-error"), contactError=step4Content.querySelector("#step4-contact-email-error"); urlError.hidden=method!=="url"&&!url.value.trim() || step4UrlValid(url.value); emailError.hidden=method!=="email"&&!email.value.trim() || step4EmailValid(email.value); contactError.hidden=!contactEmail.value.trim() || step4EmailValid(contactEmail.value); }
    const preview=step4Content.querySelector("#step4-preview"), instructionsText=instructions.value.trim(), validUrl=step4UrlValid(url.value), validEmail=step4EmailValid(email.value), activeDestination=method==="url"?validUrl:method==="email"?validEmail:Boolean(instructionsText), methodLead=method==="url"?"Apply through the employer’s website":method==="email"?"Send your application by email":method==="instructions"?"Follow the application instructions below":"", action=validUrl&&method==="url"?"<button type=\"button\" class=\"button secondary step4-preview-action\">Open application page</button>":"", destination=validEmail&&method==="email"?`<p class="step4-preview-destination">${step4Escape(email.value.trim())}</p>`:"", instructionMarkup=instructionsText?`<div class="step4-preview-instructions"><div class="step4-preview-instructions-label">Application instructions</div><div class="step4-preview-instructions-body">${step4Escape(instructionsText)}</div></div>`:"";
    // Future public listings should use tracked "Reveal email address" and optional "Open email app" metrics, with server-side retrieval rather than embedding email in initial HTML.
    const contactValues=["#step4-contact-name","#step4-contact-email","#step4-contact-phone"].map(id=>step4Content.querySelector(id)?.value.trim()).filter(Boolean), contactText=document.querySelector("#step4-hide-contact")?.checked?"<p class=\"step4-preview-note\">Contact details will not be displayed publicly.</p>":contact==="override"?(contactValues.length?`<p>Contact: ${step4Escape(contactValues.join(" · "))}</p>`:""):"<p>Default contact: jobs@lausd.net · (213) 241-1000</p>";
    const materials=[...step4Content.querySelectorAll(".step4-materials input:checked")].map(input=>input.value==="other"?step4Content.querySelector("#step4-other-materials")?.value.trim():({resume:"Resume / CV",cover:"Cover Letter",references:"References",credentials:"Credentials / Certificates",transcripts:"Transcripts"}[input.value])).filter(Boolean);
    const deadline=mode==="specific"&&date.value?`<p><strong>Deadline:</strong> ${step4Escape(date.value)}</p>`:mode==="open"?"<p><strong>Deadline:</strong> Open until filled</p>":"";
    preview.innerHTML=`<div class="step3-compact-listing"><strong>${step4Escape(document.querySelector("#job-title-step2")?.value||"Teacher position")}</strong><span>LAUSD · Los Angeles, CA</span><p>${step4Escape(document.querySelector("#step3-summary")?.value||"Add a short summary to preview this listing.")}</p></div><h4>How to Apply</h4>${method?`<p class="step4-preview-method-lead">${methodLead}</p>${destination}${action}${instructionMarkup}`:`<p>Select an application method to preview how candidates will apply.</p>`}${deadline}<h4>Contact</h4>${contactText}${materials.length?`<h4>Application Materials</h4><p>${materials.map(step4Escape).join(", ")}</p>`:""}`;
    if (focusTarget) { step4Content.querySelector(focusTarget)?.focus(); requestAnimationFrame(()=>step4Content.querySelector(focusTarget)?.focus()); } refreshNextAction();
  };
  step4Content.addEventListener("input", (e)=>{step4Sync();});
  step4Content.addEventListener("blur", (e)=>{if(e.target.matches("#step4-url,#step4-email,#step4-contact-email")) step4Sync(null,true);}, true);
  step4Content.addEventListener("change", (e)=>{
    if(e.target.matches("#step4-method")) step4Sync(e.target.value==="url"?"#step4-url":e.target.value==="email"?"#step4-email":"#step4-instructions");
    else if(e.target.matches("#step4-deadline-mode")) step4Sync(e.target.value==="specific"?"#step4-deadline":null);
    else if(e.target.matches("#step4-contact-mode")) step4Sync();
    else if(e.target.matches(".step4-materials input")){e.target.checked?step4State.materials.add(e.target.value):step4State.materials.delete(e.target.value);wizardState.application.materials=[...step4State.materials];step4Sync(e.target.value==="other"?"#step4-other-materials":null);}
    else step4Sync();
  });
  const renderStep2Preview = () => {
    const preview = document.querySelector("#step2-preview");
    if (!preview) return;
    const basics = wizardState.basics, title = basics.jobTitle.trim() || "Teacher position", details = [basics.employmentType, basics.gradeLevels, basics.subjectAreas].filter(Boolean), salary = basics.salaryVisibility === "Do not show" ? "" : [basics.salaryMinimum && `$${basics.salaryMinimum}`, basics.salaryMaximum && `– $${basics.salaryMaximum}`, basics.salaryType].filter(Boolean).join(" "), start = basics.startTiming === "Specific Date" && basics.specificStartDate ? basics.specificStartDate : basics.startTiming;
    preview.innerHTML = `<div class="step3-compact-listing"><strong>${step4Escape(title)}</strong><span>${step4Escape(wizardState.school.displayName)} · ${step4Escape(wizardState.school.location)}</span>${details.length ? `<p>${details.map(step4Escape).join(" · ")}</p>` : ""}${salary || start ? `<p>${[salary, start].filter(Boolean).map(step4Escape).join(" · ")}</p>` : ""}</div><h4>Listing Preview</h4><p>${wizardState.description.shortSummary ? step4Escape(wizardState.description.shortSummary) : "Add job details to preview this listing."}</p>`;
  };
  const wizardShellConfigs = {
    "step-01-initial": {
      viewId: "step-01-initial", stepNumber: "1", title: "Choose a School / Jobsite",
      supportingCopy: "Select an existing school or jobsite, or add a new one.<br>You can always manage your schools or jobsites from your workspace.",
      authority: false, showCancel: false, showSaveDraft: false,
      previous: null, next: null,
      stepperState: ["is-current", "is-upcoming", "is-upcoming", "is-upcoming", "is-upcoming"],
      completedTargets: [null, null, null, null, null], content: stageContent(document.querySelector("#step-01-initial")),
    },
    "step-01-school-selected": {
      viewId: "step-01-school-selected", stepNumber: "1", title: "Choose a School / Jobsite",
      supportingCopy: "Select an existing school or jobsite, or add a new one.<br>You can always manage your schools or jobsites from your workspace.",
      authority: false, showCancel: false, showSaveDraft: false,
      previous: null, next: null,
      stepperState: ["is-current", "is-upcoming", "is-upcoming", "is-upcoming", "is-upcoming"],
      completedTargets: [null, null, null, null, null], content: stageContent(document.querySelector("#step-01-school-selected")),
    },
    "step-01-add-school-us": {
      viewId: "step-01-add-school-us", stepNumber: "1", title: "Add a School / Jobsite",
      supportingCopy: "Add a new school, campus, office, or jobsite before continuing your job post.",
      authority: false, showCancel: false, showSaveDraft: false,
      previous: null, next: null,
      stepperState: ["is-current", "is-upcoming", "is-upcoming", "is-upcoming", "is-upcoming"],
      completedTargets: [null, null, null, null, null], content: stageContent(document.querySelector("#step-01-add-school-us")),
    },
    "step-01-add-school-international": {
      viewId: "step-01-add-school-international", stepNumber: "1", title: "Add a School / Jobsite",
      supportingCopy: "Add a new school, campus, office, or jobsite before continuing your job post.",
      authority: false, showCancel: false, showSaveDraft: false,
      previous: null, next: null,
      stepperState: ["is-current", "is-upcoming", "is-upcoming", "is-upcoming", "is-upcoming"],
      completedTargets: [null, null, null, null, null], content: stageContent(internationalPanel),
    },
    "step-02-job-basics": {
      viewId: "step-02-job-basics",
      stepNumber: "2",
      title: "Job Basics",
      supportingCopy:
        "Tell us the key details about this position. These help teachers find your job.",
      authority: false,
      showCancel: true,
      showSaveDraft: true,
      previous: { label: "← Previous: School / Jobsite", target: "#step-01-return" },
      next: {
        label: "Next: Job Description →",
        target: "#step-03-job-description",
        requiresInput: true,
      },
      stepperState: ["is-complete", "is-current", "is-upcoming", "is-upcoming", "is-upcoming"],
      completedTargets: ["step-01-return", null, null, null, null],
      content: step2Content,
    },
    "wizard-authority-v1": {
      viewId: "wizard-authority-v1",
      stepNumber: "2",
      title: "Job Basics",
      supportingCopy:
        "Tell us the key details about this position. These help teachers find your job.",
      authority: true,
      showCancel: true,
      showSaveDraft: true,
      previous: { label: "← Previous: School / Jobsite", target: "#step-01-return" },
      next: {
        label: "Next: Job Description →",
        target: "#step-03-job-description",
        requiresInput: true,
      },
      stepperState: ["is-complete", "is-current", "is-upcoming", "is-upcoming", "is-upcoming"],
      completedTargets: ["step-01-return", null, null, null, null],
      content: authorityContent,
    },
    "step-03-clipboard-diagnostics": {
      viewId: "step-03-clipboard-diagnostics",
      stepNumber: "3",
      title: "Clipboard Diagnostics",
      supportingCopy: "Capture untouched native clipboard payloads for engineering diagnostics.",
      authority: false,
      showCancel: false,
      showSaveDraft: false,
      previous: null,
      next: null,
      stepperState: ["is-complete", "is-complete", "is-current", "is-upcoming", "is-upcoming"],
      completedTargets: ["step-01-return", "step-02-job-basics", null, null, null],
      content: clipboardDiagnosticsContent,
    },
    "step-03-job-description": {
      viewId: "step-03-job-description",
      stepNumber: "3",
      title: "Job Description",
      supportingCopy:
        "Describe the position, responsibilities, and qualifications.",
      authority: false,
      showCancel: true,
      showSaveDraft: true,
      previous: {
        label: "← Previous: Job Basics",
        target: "#step-02-job-basics",
      },
      next: {
        label: "Next: Application Process →",
        target: "#step-04-application-process",
      },
      stepperState: [
        "is-complete",
        "is-complete",
        "is-current",
        "is-upcoming",
        "is-upcoming",
      ],
      completedTargets: ["step-01-return", "step-02-job-basics", null, null, null],
      content: step3Content,
    },
    "step-04-application-process": {
      viewId: "step-04-application-process", stepNumber: "4", title: "Application Process", supportingCopy: "Tell candidates how to apply for this position.", authority: false, showCancel: true, showSaveDraft: true,
      previous: { label: "← Previous: Job Description", target: "#step-03-job-description" }, next: { label: "Next: Review & Publish →", target: "#step-05-review-publish", requiresInput: true },
      stepperState: ["is-complete", "is-complete", "is-complete", "is-current", "is-upcoming"], completedTargets: ["step-01-return", "step-02-job-basics", "step-03-job-description", null, null], content: step4Content,
    },
  };
  wizardShellConfigs["step-01-return"] = wizardShellConfigs["step-01-school-selected"];
  const renderWizardShell = (config, panel, content) => {
    const card = document.querySelector(".application-card"),
      topbar = card?.querySelector(".tnet-jobs-app-topbar-inner");
    let mobileNav = card?.querySelector("[data-authority-mobile-nav]");
    if (config.authority && topbar && !mobileNav) {
      mobileNav = document.createElement("button");
      mobileNav.type = "button";
      mobileNav.className = "authority-mobile-nav-toggle";
      mobileNav.dataset.authorityMobileNav = "true";
      mobileNav.setAttribute("aria-controls", "authority-left-rail");
      mobileNav.setAttribute("aria-expanded", "false");
      mobileNav.textContent = "Workspace menu";
      topbar.append(mobileNav);
      mobileNav.addEventListener("click", () => {
        const open = card.classList.toggle("authority-nav-open");
        mobileNav.setAttribute("aria-expanded", String(open));
      });
    }
    const rail = card?.querySelector(".left-rail");
    if (rail) rail.id = "authority-left-rail";
    const wizardRailMode = ["step-02-job-basics", "wizard-authority-v1", "step-03-job-description", "step-04-application-process", "step-05-review-publish"].includes(config.viewId);
    card?.classList.toggle("step3-workspace-mode", wizardRailMode);
    card?.classList.toggle("step3-rail-expanded", wizardRailMode && wizardState.ui.railExpanded);
    if (rail && wizardRailMode) {
      let railToggle = rail.querySelector("[data-step3-rail-toggle]");
      if (!railToggle) {
        railToggle = document.createElement("button");
        railToggle.type = "button";
        railToggle.dataset.step3RailToggle = "true";
        railToggle.className = "step3-rail-toggle";
        railToggle.setAttribute("aria-label", "Expand navigation");
        railToggle.title = "Expand navigation";
        railToggle.innerHTML = "<span aria-hidden=\"true\">☰</span>";
        rail.prepend(railToggle);
        railToggle.addEventListener("click", () => {
          const expanded = card.classList.toggle("step3-rail-expanded");
          wizardState.ui.railExpanded = expanded;
          railToggle.setAttribute("aria-label", expanded ? "Collapse navigation" : "Expand navigation");
          railToggle.title = expanded ? "Collapse navigation" : "Expand navigation";
        });
      }
      railToggle.setAttribute("aria-label", card.classList.contains("step3-rail-expanded") ? "Collapse navigation" : "Expand navigation");
      railToggle.title = card.classList.contains("step3-rail-expanded") ? "Collapse navigation" : "Expand navigation";
    } else {
      card?.querySelector("[data-step3-rail-toggle]")?.remove();
    }
    card?.classList.toggle("authority-nav-open", false);
    const heading = StageHeading(config);
    panel.classList.add("wizard-shell-panel");
    const body = content?.childNodes.length ? content : stageContent(panel);
    panel.replaceChildren(heading, body);
    primeWizardState();
    hydrateWizardState(panel);
    initializeStep3Benefits(panel);
    syncWizardValueStates(panel);
    wizardStepper.states = config.stepperState;
    wizardStepper.completedTargets = config.completedTargets;
    wizardStepper.render();
    syncAuthorityMarker(config.authority ? config.viewId : "");
    renderStep2Preview();
    const saveDraft = document.querySelector("#save-draft-action");
    if (saveDraft) saveDraft.hidden = !config.showSaveDraft;
    const nav = document.querySelector(".view-nav");
    nav.replaceChildren();
    for (const item of [config.previous, config.next].filter(Boolean)) {
      const link = document.createElement("a");
      link.id = item === config.previous ? "previous-view" : "next-view";
      link.className = "button";
      link.href = item.target;
      link.setAttribute("aria-label", item.label);
      const compactLabel = item.label
        .replace("← Previous: ", "← ")
        .replace("Next: ", "");
      link.innerHTML = `<span class="wizard-nav-label-full">${item.label}</span><span class="wizard-nav-label-compact" aria-hidden="true">${compactLabel}</span>`;
      if (item === config.next) {
        link.dataset.target = item.target.slice(1);
        link.dataset.requiresInput = String(Boolean(item.requiresInput));
        link.setAttribute("aria-disabled", item.requiresInput ? "true" : "false");
        link.addEventListener("click", (event) => {
          if (link.getAttribute("aria-disabled") === "true") {
            event.preventDefault();
            return;
          }
          event.preventDefault();
          if (config.viewId === "step-03-job-description" && !document.querySelector("#step3-summary")?.value.trim()) {
            openStep3SummaryAssist();
            return;
          }
          setView(link.dataset.target);
        });
      }
      nav.append(link);
    }
    saveDraft.hidden = !config.showSaveDraft;
    if (config.viewId === "step-04-application-process") step4Sync();
    refreshNextAction();
  };
  const shellContent = (id) => wizardShellConfigs[id]?.content;
  const originalRender = render;
  render = () => {
    originalRender();
    const id = location.hash.slice(1) || "step-01-return";
    const config = wizardShellConfigs[id];
    if (config) renderWizardShell(config, statePanels[id], shellContent(id));
    else {
      const card = document.querySelector(".application-card");
      card?.classList.remove("step3-workspace-mode", "step3-rail-expanded");
      card?.querySelector("[data-step3-rail-toggle]")?.remove();
    }
  };
  render();
  updateStepper(location.hash.slice(1) || "step-01-return");
  window.__jc053WizardShellChecks = () => ({
    step2Registered: Boolean(wizardShellConfigs["step-02-job-basics"]),
    authorityRegistered: Boolean(wizardShellConfigs["wizard-authority-v1"]),
    step3Registered: Boolean(wizardShellConfigs["step-03-job-description"]),
    oneShellRoot: document.querySelectorAll(".application-card").length === 1,
    oneStepperRoot: document.querySelectorAll("[data-wizard-stepper]").length === 1,
    oneBottomNavigationRoot: document.querySelectorAll(".view-nav").length === 1,
    authorityMarkerOnlyInAuthority:
      document.querySelectorAll("#authority-marker").length === 0 ||
      location.hash === "#wizard-authority-v1",
    sharedRenderer: true,
    noAuthorityClonePath: true,
    noStep3LatePatch: true,
  });
})();
