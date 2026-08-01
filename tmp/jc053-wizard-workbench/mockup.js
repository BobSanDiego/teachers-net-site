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
      return Boolean(
        step3Text(document.querySelector("#step3-description-editor")?.innerHTML) &&
          step3Text(document.querySelector("#step3-requirements-editor")?.innerHTML),
      );
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
          : "#step-02-job-basics";
    else next.removeAttribute("href");
  };
  const buildStep3SummaryDraft = () => {
    const source = step3Text(document.querySelector("#step3-description-editor")?.innerHTML);
    if (!source) return "";
    const sentence = source.match(/^(.{1,160}?[.!?])(?:\\s|$)/)?.[1];
    if (sentence) return sentence;
    if (source.length <= 160) return source;
    return `${source.slice(0, 157).trim().replace(/\\s+\\S*$/, "")}…`;
  };
  const openStep3SummaryAssist = () => {
    let modal = document.querySelector("#step3-summary-assist");
    if (!modal) {
      modal = document.createElement("div");
      modal.id = "step3-summary-assist";
      modal.className = "step3-summary-assist";
      modal.setAttribute("role", "dialog");
      modal.setAttribute("aria-modal", "true");
      document.body.append(modal);
    }
    const draft = buildStep3SummaryDraft();
    modal.innerHTML = `<div class="step3-summary-assist-card"><h3>One last recommendation before you continue</h3><p>Short summaries help your listing stand out when it is featured, shared, or promoted across Teachers.Net.</p><p>We prepared a draft from your job description. Review it and make any changes for the best display across the site.</p>${draft ? `<textarea id="step3-summary-draft" maxlength="160">${draft}</textarea>` : `<p class="step3-summary-no-draft">Add a Job Description before using a summary draft.</p>`}<div class="step3-summary-assist-actions"><button type="button" class="button primary" data-summary-action="use">Use and continue</button><button type="button" class="button secondary" data-summary-action="edit">Edit summary</button><button type="button" class="button secondary" data-summary-action="skip">Continue without summary</button></div></div>`;
    modal.hidden = false;
    modal.querySelector('[data-summary-action="use"]').disabled = !draft;
    modal.querySelector('[data-summary-action="use"]').addEventListener("click", () => { document.querySelector("#step3-summary").value = modal.querySelector("#step3-summary-draft")?.value || ""; modal.hidden=true; refreshNextAction(); setView("step-04-application-process"); });
    modal.querySelector('[data-summary-action="edit"]').addEventListener("click", () => { modal.hidden=true; document.querySelector("#step3-summary")?.focus({preventScroll:true}); });
    modal.querySelector('[data-summary-action="skip"]').addEventListener("click", () => { modal.hidden=true; setView("step-04-application-process"); });
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
    "Grade Level(s) <small>(Recommended for matching)</small>";
  jobBasicsPanel.querySelector(".subject-area-field label").innerHTML =
    "Subject Area(s) <small>(Recommended for matching)</small>";
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
  const step3Option = select.querySelector(
    'option[value="step-03-job-description"]',
  );
  if (step3Option) step3Option.disabled = false;
  const step2Content = document.createElement("div");
  step2Content.className = "wizard-shell-content wizard-shell-content-step2";
  step2Content.append(
    ...[...jobBasicsPanel.children].filter(
      (child) => !child.classList.contains("job-basics-heading"),
    ),
  );
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
          <div id="step3-description-editor" class="step3-editor" contenteditable="true" role="textbox" aria-multiline="true" aria-label="Job Description"></div>
          <div class="step3-counter"><span data-counter-for="step3-description-editor">0</span> characters</div>
        </div>
        <div class="step3-field step3-requirements-field">
          <label for="step3-requirements-editor">Requirements / Qualifications <span aria-hidden="true">*</span></label>
          <p class="step3-field-help">List the required qualifications, certifications, education, and experience for this role.</p>
          <div id="step3-requirements-editor" class="step3-editor step3-editor-requirements" contenteditable="true" role="textbox" aria-multiline="true" aria-label="Requirements / Qualifications"></div>
          <div class="step3-counter"><span data-counter-for="step3-requirements-editor">0</span> characters</div>
        </div>
        <div class="step3-field step3-summary-field">
          <label for="step3-summary">Short Summary</label><p class="step3-field-help">Summarize this opportunity in one or two sentences. This may appear when your job is featured, shared, or promoted across Teachers.Net.</p>
          <textarea id="step3-summary" maxlength="160" rows="3"></textarea><div class="step3-counter"><span data-counter-for="step3-summary">0</span>/160 characters</div>
        </div>
        <div class="step3-optional-sections">
          ${["Responsibilities","Preferred Qualifications","About Our School"].map((title,index)=>`<details><summary>${title}</summary><div id="step3-optional-${index}" class="step3-editor step3-optional-editor" contenteditable="true" role="textbox" aria-label="${title}"></div></details>`).join("")}
          <section class="step3-benefits" aria-labelledby="step3-benefits-heading">
            <h4 id="step3-benefits-heading">Benefits</h4>
            <div id="step3-benefits-selected" class="step3-benefits-selected" aria-live="polite"></div>
            <p class="step3-benefits-help">Select all benefits that apply. Click any item below to add or remove it.</p>
            <div id="step3-benefits-categories" class="step3-benefits-categories"></div>
            <label class="step3-benefits-additional-toggle"><input id="step3-benefits-additional-enabled" type="checkbox"> <span>Additional benefits</span></label>
            <p class="step3-benefits-additional-help">Describe any benefits not listed above.</p>
            <textarea id="step3-benefits-additional" maxlength="300" rows="3" aria-label="Additional benefits" hidden></textarea>
            <div class="step3-counter"><span data-counter-for="step3-benefits-additional">0</span>/300 characters</div>
          </section>
        </div>
      </div>
      <aside class="step3-preview-pane" aria-label="Listing Preview"><div class="step3-preview-heading"><div><h3>Listing Preview</h3><p>This is how your job listing will look to teachers.</p></div><span>Live preview</span></div><div id="step3-preview" class="step3-preview-card"></div><p class="step3-preview-note">Step 5 remains the canonical full review surface.</p></aside>
    </div>`;
  const step3Editors = ["#step3-description-editor", "#step3-requirements-editor"];
  const step3PlainText = (html) => {
    const node = document.createElement("div");
    node.innerHTML = html || "";
    node.querySelectorAll("script,style,iframe,object,embed").forEach((item) => item.remove());
    node.querySelectorAll("*").forEach((item) => [...item.attributes].forEach((attr) => {
      if (attr.name.toLowerCase().startsWith("on")) item.removeAttribute(attr.name);
      if (item.tagName === "A" && attr.name === "href" && !/^https?:/i.test(attr.value)) item.removeAttribute(attr.name);
    }));
    return node;
  };
  const step3Sanitized = (html) => {
    const node = step3PlainText(html), allowed = new Set(["P","BR","STRONG","B","EM","I","UL","OL","LI","A","H3"]);
    node.querySelectorAll("*").forEach((item) => { if (!allowed.has(item.tagName)) { item.replaceWith(...item.childNodes); } });
    return node.innerHTML;
  };
  const step3Escape = (value) => String(value).replace(/[&<>"']/g, (character) => ({
    "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#39;",
  }[character]));
  const step3PlainPasteHtml = (text) => String(text || "")
    .replace(/\r\n?/g, "\n")
    .split("\n")
    .map((line) => `<p>${step3Escape(line)}</p>`)
    .join("");
  const step3Text = (html) => step3PlainText(html).textContent.replace(/\\s+/g, " ").trim();
  const step3Benefits = {
    Insurance: ["Medical Insurance", "Dental Insurance", "Vision Insurance", "Life Insurance", "Disability Insurance"],
    Financial: ["Retirement Plan", "401(k) Plan", "403(b) Plan", "Pension Plan", "Employer Match", "Tuition Assistance", "Relocation Assistance"],
    Scheduling: ["Paid Time Off", "Paid Holidays", "Paid Sick Leave", "Personal Days", "Flexible Schedule", "Remote / Hybrid Eligible"],
    Other: ["Professional Development", "Mentoring / Coaching", "Conference Support", "Classroom Resources", "Employee Assistance Program", "Wellness Program", "Student Loan Assistance"],
  };
  const step3State = { previewTimer: null, selectedBenefits: new Set() };
  const renderStep3Benefits = () => {
    const selected = document.querySelector("#step3-benefits-selected"), categories = document.querySelector("#step3-benefits-categories");
    if (!selected || !categories) return;
    const values = [...step3State.selectedBenefits];
    selected.innerHTML = values.length
      ? `<span class="step3-benefits-selected-label">Selected (${values.length}):</span> ${values.map((value) => `<span class="step3-benefits-selected-item">${step3Escape(value)} <button type="button" data-benefit-remove="${step3Escape(value)}" aria-label="Remove ${step3Escape(value)}">×</button></span>`).join(" ")} <button type="button" class="step3-benefits-clear" data-benefit-clear>Clear all</button>`
      : `<span class="step3-benefits-empty">No benefits selected yet.</span>`;
    categories.innerHTML = Object.entries(step3Benefits).map(([category, options]) => `<div class="step3-benefits-category"><span class="step3-benefits-category-label">${category}:</span> <span class="step3-benefits-options">${options.map((option) => `<button type="button" class="step3-benefit-option${step3State.selectedBenefits.has(option) ? " is-selected" : ""}" data-benefit-option="${step3Escape(option)}" aria-pressed="${step3State.selectedBenefits.has(option)}">${step3Escape(option)}</button>`).join(", ")}</span></div>`).join("");
  };
  const step3BenefitsText = () => [...step3State.selectedBenefits].join(", ");
  const step3BenefitsActive = () => step3State.selectedBenefits.size > 0 || !!document.querySelector("#step3-benefits-additional-enabled")?.checked && !!document.querySelector("#step3-benefits-additional")?.value.trim();
  const renderStep3Preview = () => {
    const preview = document.querySelector("#step3-preview");
    if (!preview) return;
    const description = document.querySelector("#step3-description-editor"), requirements = document.querySelector("#step3-requirements-editor"), summary = document.querySelector("#step3-summary");
    const additional = document.querySelector("#step3-benefits-additional"), benefits = step3BenefitsActive() ? `<h5>Benefits</h5><div>${step3BenefitsText()}${additional?.checked || document.querySelector("#step3-benefits-additional-enabled")?.checked && additional?.value.trim() ? `${step3State.selectedBenefits.size ? ", " : ""}${step3Escape(additional.value.trim())}` : ""}</div>` : "";
    preview.innerHTML = `<div class="step3-preview-school"><strong>${schoolJobsiteFixture.display_name}</strong><span>Los Angeles, CA · Full-time</span></div><h4>${document.querySelector("#job-title-step2")?.value.trim() || "Teacher position"}</h4>${summary?.value.trim() ? `<p class="step3-preview-summary">${summary.value.trim()}</p>` : ""}<h5>Job Description</h5><div>${step3Sanitized(description?.innerHTML)}</div><h5>Requirements / Qualifications</h5><div>${step3Sanitized(requirements?.innerHTML)}</div>${[0,1,2].map((i)=>{const el=document.querySelector(`#step3-optional-${i}`);return el && step3Text(el.innerHTML) ? `<h5>${["Responsibilities","Preferred Qualifications","About Our School"][i]}</h5><div>${step3Sanitized(el.innerHTML)}</div>` : "";}).join("")}${benefits}`;
  };
  const scheduleStep3Preview = () => { clearTimeout(step3State.previewTimer); step3State.previewTimer = setTimeout(renderStep3Preview, 120); };
  const updateStep3Counters = () => document.querySelectorAll("[data-counter-for]").forEach((counter) => { const field=document.querySelector(`#${counter.dataset.counterFor}`); counter.textContent=field?.isContentEditable ? step3Text(field.innerHTML).length : (field?.value || "").length; });
  step3Content.querySelectorAll("[data-format-command]").forEach((control) => control.addEventListener("click", () => { const command=control.dataset.formatCommand; if(command === "createLink"){const url=window.prompt("Link URL");if(url) document.execCommand(command,false,url);}else document.execCommand(command,false,control.tagName === "SELECT" ? control.value : null); updateStep3Counters(); scheduleStep3Preview(); }));
  step3Content.addEventListener("input", (event) => { if(event.target.matches("[contenteditable], textarea")){updateStep3Counters();scheduleStep3Preview();refreshNextAction();} });
  step3Content.addEventListener("change", (event) => { if (event.target.matches("#step3-benefits-additional-enabled")) { const field=document.querySelector("#step3-benefits-additional"); field.hidden=!event.target.checked; updateStep3Counters(); scheduleStep3Preview(); } });
  step3Content.addEventListener("click", (event) => {
    const option = event.target.closest("[data-benefit-option]"), remove = event.target.closest("[data-benefit-remove]"), clear = event.target.closest("[data-benefit-clear]");
    if (option) { const value=option.dataset.benefitOption; step3State.selectedBenefits.has(value) ? step3State.selectedBenefits.delete(value) : step3State.selectedBenefits.add(value); renderStep3Benefits(); scheduleStep3Preview(); return; }
    if (remove) { step3State.selectedBenefits.delete(remove.dataset.benefitRemove); renderStep3Benefits(); scheduleStep3Preview(); return; }
    if (clear) { step3State.selectedBenefits.clear(); renderStep3Benefits(); scheduleStep3Preview(); }
  });
  step3Content.querySelectorAll("[contenteditable]").forEach((editor) => editor.addEventListener("paste", (event) => {
    event.preventDefault();
    const clipboard = event.clipboardData || window.clipboardData;
    const html = clipboard?.getData("text/html");
    const text = clipboard?.getData("text/plain") || "";
    const sanitized = html ? step3Sanitized(html) : step3PlainPasteHtml(text);
    document.execCommand("insertHTML", false, sanitized);
    updateStep3Counters();
    scheduleStep3Preview();
    refreshNextAction();
  }));
  requestAnimationFrame(renderStep3Benefits);
  renderStep3Preview();
  const wizardShellConfigs = {
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
  };
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
    const step3Mode = config.viewId === "step-03-job-description";
    card?.classList.toggle("step3-workspace-mode", step3Mode);
    card?.classList.toggle("step3-rail-expanded", step3Mode && sessionStorage.getItem("jc053-step3-rail") === "expanded");
    if (rail && step3Mode) {
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
          sessionStorage.setItem("jc053-step3-rail", expanded ? "expanded" : "collapsed");
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
    const heading = jobBasicsPanel
      .querySelector(".job-basics-heading")
      .cloneNode(true);
    heading.querySelector(".form-section-number").textContent =
      config.stepNumber;
    heading.querySelector("h2").textContent = config.title;
    heading.querySelector("p").textContent = config.supportingCopy;
    panel.classList.add("wizard-shell-panel");
    panel.replaceChildren(heading, content);
    wizardStepper.states = config.stepperState;
    wizardStepper.completedTargets = config.completedTargets;
    wizardStepper.render();
    syncAuthorityMarker(config.authority ? config.viewId : "");
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
