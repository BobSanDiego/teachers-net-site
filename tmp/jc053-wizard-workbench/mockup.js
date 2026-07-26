(() => {
  const views = [
    ['step-01-first-touch','Step 1 — First touch'],['step-01-school-selected','Step 1 — School selected'],['step-01-nav-back','Step 1 — Nav-back selected'],['step-01-add-physical-us','Step 1 — Add Physical U.S.'],['step-01-add-international','Step 1 — Add International'],['step-01-add-multiple-locations','Step 1 — Add Multiple Locations'],['step-01-add-additional-info','Step 1 — Additional Information'],['step-02-job-basics','Step 2 — Job Basics'],['step-03-job-description','Step 3 — Job Description'],['step-04-application-process','Step 4 — Application Process'],['step-05-review-publish','Step 5 — Review & Publish']
  ];
  const select=document.querySelector('#view-select'), status=document.querySelector('#view-status'), panel=document.querySelector('#step-01-nav-back'), placeholder=document.querySelector('#placeholder');
  class WizardStepper {
    constructor(root,{steps,activeStep,states}){this.root=root;this.steps=steps;this.activeStep=activeStep;this.states=states}
    render(){
      this.root.replaceChildren(...this.steps.map((step,index)=>{
        const state=this.states[index]||'is-upcoming';
        const item=document.createElement('li'); item.className=state; item.dataset.state=state;
        const marker=document.createElement('span'); marker.textContent=String(index+1); marker.setAttribute('aria-hidden','true');
        const label=document.createElement('strong'); label.textContent=step.label;
        item.append(marker,label); return item;
      }));
    }
  }
  new WizardStepper(document.querySelector('[data-wizard-stepper]'),{
    steps:[{label:'School / Jobsite'},{label:'Job Basics'},{label:'Job Description'},{label:'Application Process'},{label:'Review & Publish'}],
    activeStep:1,
    states:['is-current','is-upcoming','is-upcoming','is-upcoming','is-upcoming']
  }).render();
  class NavbarDropdown {
    constructor(trigger,config){
      this.config=config; this.wrapper=document.createElement('span'); this.wrapper.className='tnet-navbar-dropdown';
      this.button=document.createElement('button'); this.button.type='button'; this.button.className=trigger.className; this.button.innerHTML=trigger.innerHTML;
      this.button.setAttribute('aria-haspopup','menu'); this.button.setAttribute('aria-expanded','false');
      trigger.replaceWith(this.wrapper); this.wrapper.append(this.button);
      this.menu=document.createElement('div'); this.menu.className='tnet-navbar-dropdown-menu'; this.menu.setAttribute('role','menu'); this.menu.hidden=true;
      const header=document.createElement('div'); header.className='tnet-navbar-dropdown-header'; header.textContent=config.title; this.menu.append(header);
      config.items.forEach(item=>{const el=document.createElement(item.available?'a':'span'); el.className='tnet-navbar-dropdown-item'+(item.current?' is-current':'')+(item.available?'':' is-unavailable'); el.setAttribute('role','menuitem'); if(item.available){el.href=item.href}else{el.setAttribute('aria-disabled','true')} const icon=document.createElement('span');icon.className='tnet-navbar-dropdown-item-icon';icon.setAttribute('aria-hidden','true');icon.innerHTML='<svg viewBox="0 0 16 16" focusable="false"><rect x="2.5" y="2.5" width="11" height="11" rx="2"/><path d="M5 8h6M8 5v6"/></svg>'; const label=document.createElement('span');label.textContent=item.label;el.append(icon,label);if(item.badge){const badge=document.createElement('span');badge.className='tnet-navbar-dropdown-item-badge';badge.textContent=item.badge;el.append(badge)}else if(item.current){const status=document.createElement('span');status.className='tnet-navbar-dropdown-item-status';status.textContent='Current';el.append(status)}this.menu.append(el)});
      if(config.items.some(item=>!item.available)){const legend=document.createElement('div');legend.className='tnet-navbar-dropdown-legend';legend.innerHTML='<span>* Planned for V1</span><span>** Planned after V1</span>';this.menu.append(legend)}
      this.wrapper.append(this.menu); this.button.addEventListener('click',()=>this.toggle()); this.button.addEventListener('keydown',event=>this.onTriggerKey(event)); this.menu.addEventListener('keydown',event=>this.onMenuKey(event));
    }
    toggle(){this.menu.hidden?this.open():this.close()}
    open(){dropdowns.forEach(dropdown=>{if(dropdown!==this)dropdown.close()});this.menu.hidden=false;this.button.setAttribute('aria-expanded','true');const first=this.menu.querySelector('[role="menuitem"]');first?.focus()}
    close(returnFocus=false){this.menu.hidden=true;this.button.setAttribute('aria-expanded','false');if(returnFocus)this.button.focus()}
    onTriggerKey(event){if(event.key==='Enter'||event.key===' '||event.key==='ArrowDown'){event.preventDefault();this.open()}else if(event.key==='Escape'){this.close(true)}}
    onMenuKey(event){const items=[...this.menu.querySelectorAll('[role="menuitem"]')],index=items.indexOf(document.activeElement);if(event.key==='ArrowDown'){event.preventDefault();items[(index+1)%items.length].focus()}else if(event.key==='ArrowUp'){event.preventDefault();items[(index-1+items.length)%items.length].focus()}else if(event.key==='Escape'){event.preventDefault();this.close(true)}else if(event.key==='Tab'){this.close()}}
  }
  const hrefs={workspace:'#step-01-nav-back',school:'#step-01-school-selected'};
  const menuSets={
    'my-jobs':{title:'My Jobs',items:[['My Jobs',true,true,'3'],['Post a Job',true],['Schools / Jobsites',true,false,'5'],['Candidates **',false],['Saved Searches **',false],['Billing **',false],['Employer Dashboard **',false]]},
    'career-resources':{title:'Career Resources',items:[['Browse Jobs',true],['Salary Explorer **',false],['Resume Advice *',false],['Interview Resources *',false],['Career Articles *',false],['Job Alerts **',false]]},
    'teacher-resources':{title:'Teacher Resources',items:[['Lesson Plans',true],['Chatboards',true],['Teaching Jobs',true],['Classroom Management',true],['Printables',true],['Professional Development',true],['Teacher Humor',true]]},
    'my-account':{title:'My Account',items:[['Profile *',false],['Organization *',false],['Billing **',false],['Notifications *',false],['Preferences *',false],['Help *',false],['Sign Out',true]]}
  };
  const dropdowns=[...document.querySelectorAll('[data-dropdown]')].map(trigger=>{const key=trigger.dataset.dropdown,config=menuSets[key];config.items=config.items.map(([label,available,current,badge])=>({label,available,current,badge,href:label==='Schools / Jobsites'?hrefs.school:hrefs.workspace}));return new NavbarDropdown(trigger,config)});
  document.addEventListener('click',event=>{if(!(event.target instanceof Element)||!event.target.closest('.tnet-navbar-dropdown'))dropdowns.forEach(dropdown=>dropdown.close())});
  views.forEach(([id,label])=>{const o=document.createElement('option');o.value=id;o.textContent=label;o.disabled=id!=='step-01-nav-back';select.append(o)});
  function render(){const id=location.hash.slice(1)||'step-01-nav-back';const implemented=id==='step-01-nav-back';select.value=implemented?id:'step-01-nav-back';status.textContent=id;panel.hidden=!implemented;placeholder.hidden=implemented;placeholder.querySelector('p').textContent=implemented?'':'This view is registered for later authority work and is intentionally not implemented.';document.querySelector('#previous-view').href='#step-01-nav-back';document.querySelector('#next-view').href='#step-01-school-selected';}
  select.addEventListener('change',()=>{location.hash=select.value});window.addEventListener('hashchange',render);render();
  const diagnostics=document.querySelector('#diagnostics'), toggle=document.querySelector('#diagnostics-toggle');
  function measure(){const card=document.querySelector('.application-card').getBoundingClientRect(),rail=document.querySelector('.left-rail').getBoundingClientRect(),workspace=document.querySelector('.main-workspace').getBoundingClientRect();diagnostics.textContent=[`view: ${location.hash.slice(1)||'step-01-nav-back'}`,`card: ${card.width}px`, `rail: ${rail.width}px`, `workspace: ${workspace.width}px`,`viewport: ${innerWidth}×${innerHeight}`,`overflow: ${document.documentElement.scrollWidth>innerWidth?'yes':'no'}`].join('\n')}
  toggle.addEventListener('click',()=>{diagnostics.hidden=!diagnostics.hidden;toggle.textContent=diagnostics.hidden?'Show diagnostics':'Hide diagnostics';measure()});window.addEventListener('resize',measure);
})();
