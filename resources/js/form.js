/**
 * Effectzorg Uitvraag - Form Logic
 */

const TEAM = [
  {naam:'Giorgio Ashruf', functie:'Eigenaar — GGZ & GHZ'},
  {naam:'Mario Tahir',    functie:'Eigenaar — GGZ'},
  {naam:'Yamila',         functie:'Recruitment & bemiddeling'},
  {naam:'Juliëtte',       functie:'Planner'},
  {naam:'Armano',         functie:'Finance & Control'},
  {naam:'Malou',          functie:'Administratie'},
  {naam:'Ravi',           functie:'Boekhouding'},
  {naam:'Aaliyah',        functie:'Social media & content'},
];

const SYSTEMS = ['Odixus','Icadot','RecruitNow','Flexfact','Overige software'];
const ACCESS  = ['Odixus','Icadot','RecruitNow','Flexfact','Overige software'];
const sysOpts = SYSTEMS.map(s => '<option>'+s+'</option>').join('');

// ===== TEMPLATES =====

function processEntry(){
  return '<div class="entry">'+
    '<div class="entry-top"><span class="entry-tag">Handmatige taak</span><button type="button" class="xbtn" onclick="removeEntry(this)" aria-label="Verwijderen">&times;</button></div>'+
    '<label class="flabel">Wat doe je handmatig?</label>'+
    '<textarea class="auto" data-field="description" placeholder="Bijv. dienstbevestigingen bijhouden in Excel en handmatig doormailen…"></textarea>'+
    '<div class="entry-meta">'+
      '<div class="fcol"><label class="flabel">Hoe vaak?</label><input type="text" data-field="frequency" placeholder="bv. 10× per week"></div>'+
      '<div class="fcol"><label class="flabel">Tijd per keer</label><input type="text" data-field="time_per_task" placeholder="bv. 15 minuten"></div>'+
    '</div></div>';
}

function gapEntry(){
  return '<div class="entry">'+
    '<div class="entry-top"><select class="syspill" data-field="system_name">'+sysOpts+'</select><button type="button" class="xbtn" onclick="removeEntry(this)" aria-label="Verwijderen">&times;</button></div>'+
    '<label class="flabel">Wat ontbreekt in dit systeem?</label>'+
    '<textarea class="auto" data-field="description" placeholder="Welke functie mis je?"></textarea>'+
    '<div class="entry-meta">'+
      '<div class="fcol"><label class="flabel">Workaround nu</label><input type="text" data-field="workaround" placeholder="Hoe los je het nu op?"></div>'+
      '<div class="fcol"><label class="flabel">Extra tijd</label><input type="text" data-field="extra_time" placeholder="bv. 2 uur per week"></div>'+
    '</div></div>';
}

function accBlock(p, idx, open){
  const naam=(p.naam||'').replace(/"/g,'&quot;');
  const func=(p.functie||'').replace(/"/g,'&quot;');
  const nameDisp = p.naam ? p.naam : 'Naam collega';
  const emptyCls = p.naam ? '' : ' is-empty';
  const funcDisp = p.functie ? p.functie : '—';
  return '<div class="acc'+(open?' open':'')+'" data-idx="'+idx+'" data-filled="0">'+
    '<button type="button" class="acc-head" aria-expanded="'+(open?'true':'false')+'" onclick="toggleAcc(this)">'+
      '<span class="acc-num">'+(idx+1)+'</span>'+
      '<span class="acc-id"><span class="acc-name'+emptyCls+'" data-empty="Naam collega">'+nameDisp+'</span><span class="acc-func">'+funcDisp+'</span></span>'+
      '<span class="acc-status" title="Ingevuld"></span>'+
      '<svg class="acc-chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>'+
    '</button>'+
    '<div class="acc-body"'+(open?' style="max-height:none"':'')+'>'+
      '<div class="acc-inner">'+
        '<div class="pid2">'+
          '<div class="fcol"><label class="flabel">Naam</label><input type="text" class="pname" value="'+naam+'" placeholder="Naam collega"></div>'+
          '<div class="fcol"><label class="flabel">Functie</label><input type="text" class="pfunc" value="'+func+'" placeholder="Functie / rol"></div>'+
        '</div>'+
        '<div class="qbox"><span class="qlabel">Wensen</span><p class="qhint">Wat zou jouw werk makkelijker maken? Wat zou je graag willen in de nieuwe software?</p><textarea class="auto" data-field="wishes" placeholder="Schrijf hier jouw wensen…"></textarea></div>'+
        '<div class="qbox"><span class="qlabel">Waar loop je tegenaan</span><p class="qhint">Welke knelpunten ervaar je nu in de systemen?</p><textarea class="auto" data-field="pain_points" placeholder="Beschrijf de knelpunten…"></textarea></div>'+
        '<div class="qgroup" data-group="manual_tasks"><div class="qg-head"><span class="qlabel">Handmatige werkzaamheden buiten de systemen</span><p class="qhint">Wat doe je handmatig (Excel, Word, mail, dubbel invoeren, controles)? Wees concreet met tijd en frequentie.</p></div><div class="entries">'+processEntry()+'</div><button type="button" class="addrow" onclick="addProcess(this)">+ taak toevoegen</button></div>'+
        '<div class="qgroup" data-group="missing_features"><div class="qg-head"><span class="qlabel">Ontbrekende functionaliteit per systeem</span><p class="qhint">Welke functie mis je, in welk systeem, welke workaround gebruik je en hoeveel extra tijd kost dat?</p></div><div class="entries">'+gapEntry()+'</div><button type="button" class="addrow" onclick="addGap(this)">+ knelpunt toevoegen</button></div>'+
        '<div class="qbox"><span class="qlabel">Gewenste werkwijze</span><p class="qhint">Hoe ziet jouw ideale proces eruit? Wat zou volledig geautomatiseerd moeten worden?</p><textarea class="auto" data-field="desired_workflow" placeholder="Beschrijf hoe het idealiter zou werken…"></textarea></div>'+
        '<div class="qgroup"><div class="qg-head"><span class="qlabel">Bijlagen / screenshots <span class="opt">optioneel</span></span><p class="qhint">Voeg screenshots toe van waar je vastloopt, of een bestand dat je nu gebruikt.</p></div><div class="uploader"><label class="upbtn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg> Bestand toevoegen<input type="file" multiple accept="image/*,.pdf,.doc,.docx,.xls,.xlsx,.csv" hidden onchange="handleFiles(this)"></label><div class="uplist"></div></div></div>'+
      '</div>'+
    '</div>'+
  '</div>';
}

function accessCard(sys){
  const isOther = (sys === 'Overige software');
  const title = isOther ? '<input type="text" class="other-title" data-field="system_name" placeholder="Naam software…">' : '<h3>'+sys+'</h3>';
  return '<div class="acard" data-system="'+sys+'">'+
    '<div class="acard-h"><span class="adot"></span>'+title+'</div>'+
    '<div class="afields">'+
      '<div class="afield wide"><label class="flabel">Wat doen we met dit systeem?</label><select data-field="action"><option>— kies —</option><option>Behouden zoals het is</option><option>Vervangen door de nieuwe software</option><option>Koppelen (via API / koppeling)</option><option>Nog onbekend</option></select></div>'+
      '<div class="afield"><label class="flabel">Testaccount (read-only) mogelijk?</label><input type="text" data-field="test_account" placeholder="Ja / Nee — + login of contact"></div>'+
      '<div class="afield"><label class="flabel">Demo-omgeving of GitHub-link</label><input type="text" data-field="demo_url" placeholder="URL of repository"></div>'+
      '<div class="afield"><label class="flabel">Wie kan toegang verlenen?</label><input type="text" data-field="access_contact" placeholder="Naam / leverancier / beheerder"></div>'+
      '<div class="afield"><label class="flabel">Welke modules / onderdelen?</label><input type="text" data-field="modules" placeholder="bv. planning, facturatie…"></div>'+
      '<div class="afield wide"><label class="flabel">Opmerkingen</label><textarea class="auto" data-field="remarks" placeholder="Beperkingen, kosten, bijzonderheden…"></textarea></div>'+
    '</div></div>';
}

// ===== UTILITIES =====

function grow(el){ el.style.height='auto'; el.style.height=(el.scrollHeight+2)+'px'; }
function growAll(){ document.querySelectorAll('textarea.auto').forEach(grow); }
function refreshOpenHeight(acc){ if(acc && acc.classList.contains('open')){ acc.querySelector('.acc-body').style.maxHeight='none'; } }

function setOpen(acc, open){
  const body=acc.querySelector('.acc-body');
  const head=acc.querySelector('.acc-head');
  if(open){
    acc.classList.add('open'); head.setAttribute('aria-expanded','true');
    body.style.maxHeight=body.scrollHeight+'px';
    const done=()=>{ if(acc.classList.contains('open')) body.style.maxHeight='none'; body.removeEventListener('transitionend',done); };
    body.addEventListener('transitionend',done);
  } else {
    body.style.maxHeight=body.scrollHeight+'px';
    requestAnimationFrame(()=>requestAnimationFrame(()=>{ body.style.maxHeight='0px'; }));
    acc.classList.remove('open'); head.setAttribute('aria-expanded','false');
  }
}

function toggleAcc(btn){ const acc=btn.closest('.acc'); setOpen(acc, !acc.classList.contains('open')); }

let allOpen=false;
function updateToggleBtn(){ const b=document.getElementById('expandBtn'); if(b) b.textContent = allOpen ? 'Alles inklappen' : 'Alles uitklappen'; }
function expandAll(){ document.querySelectorAll('.acc').forEach(a=>{ if(!a.classList.contains('open')) setOpen(a,true); }); allOpen=true; updateToggleBtn(); }
function collapseAll(){ document.querySelectorAll('.acc').forEach(a=>{ if(a.classList.contains('open')) setOpen(a,false); }); allOpen=false; updateToggleBtn(); }
function toggleAll(){ allOpen ? collapseAll() : expandAll(); }

function goPerson(idx){
  const acc=document.querySelector('.acc[data-idx="'+idx+'"]'); if(!acc) return;
  if(!acc.classList.contains('open')) setOpen(acc,true);
  setTimeout(()=>acc.scrollIntoView({behavior:'smooth',block:'start'}), 60);
}

function addProcess(btn){ const wrap=btn.closest('.qgroup').querySelector('.entries'); wrap.insertAdjacentHTML('beforeend', processEntry()); wrap.lastElementChild.querySelectorAll('textarea.auto').forEach(grow); refreshOpenHeight(btn.closest('.acc')); }
function addGap(btn){ const wrap=btn.closest('.qgroup').querySelector('.entries'); wrap.insertAdjacentHTML('beforeend', gapEntry()); wrap.lastElementChild.querySelectorAll('textarea.auto').forEach(grow); refreshOpenHeight(btn.closest('.acc')); }
function removeEntry(btn){ const entry=btn.closest('.entry'); const wrap=entry.parentElement; const acc=btn.closest('.acc'); if(wrap.children.length>1){ entry.remove(); } else { entry.querySelectorAll('input,textarea').forEach(f=>f.value=''); entry.querySelectorAll('select').forEach(s=>s.selectedIndex=0); } if(acc) updateFilled(acc); refreshOpenHeight(acc); }

function rebuildNav(){
  const accs=[...document.querySelectorAll('#team .acc')];
  document.getElementById('pnav-chips').innerHTML = accs.map(a=>{
    const idx=a.dataset.idx;
    const nm=a.querySelector('.pname').value.trim();
    const filled=a.dataset.filled==='1';
    return '<button class="navchip" data-idx="'+idx+'"'+(filled?' data-filled="1"':'')+' onclick="goPerson('+idx+')"><span class="nc-dot"></span><span class="nc-label">'+(nm||'Nieuwe collega')+'</span></button>';
  }).join('');
}

function updateHeader(acc){
  const nm=acc.querySelector('.pname').value.trim();
  const fn=acc.querySelector('.pfunc').value.trim();
  const ne=acc.querySelector('.acc-name'); ne.textContent=nm||ne.dataset.empty; ne.classList.toggle('is-empty', !nm);
  acc.querySelector('.acc-func').textContent=fn||'—';
  const chip=document.querySelector('.navchip[data-idx="'+acc.dataset.idx+'"]'); if(chip){ chip.querySelector('.nc-label').textContent=nm||'Nieuwe collega'; }
}

function updateFilled(acc){
  const fields=acc.querySelectorAll('.acc-inner textarea, .acc-inner input:not(.pname):not(.pfunc)');
  let filled=false; fields.forEach(f=>{ if(f.value && f.value.trim()) filled=true; });
  acc.dataset.filled = filled?'1':'0';
  const chip=document.querySelector('.navchip[data-idx="'+acc.dataset.idx+'"]'); if(chip){ if(filled) chip.setAttribute('data-filled','1'); else chip.removeAttribute('data-filled'); }
}

function addColleague(){
  const host=document.getElementById('team');
  const idx=host.querySelectorAll('.acc').length;
  host.insertAdjacentHTML('beforeend', accBlock({naam:'',functie:''}, idx, true));
  rebuildNav();
  const acc=host.lastElementChild;
  acc.querySelectorAll('textarea.auto').forEach(grow);
  acc.querySelector('.acc-body').style.maxHeight='none';
  setTimeout(()=>{ acc.scrollIntoView({behavior:'smooth',block:'center'}); const nm=acc.querySelector('.pname'); if(nm) nm.focus(); }, 60);
}

// ===== FILE HANDLING =====

function escapeHtml(s){ return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }
function fmtSize(b){ if(b<1024) return b+' B'; if(b<1048576) return (b/1024).toFixed(0)+' KB'; return (b/1048576).toFixed(1)+' MB'; }
function fileSvg(){ return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/></svg>'; }

function pickLogo(slot){ const i=slot.querySelector('input[type=file]'); if(i) i.click(); }
function setLogo(input){
  const slot=input.closest('.logoslot');
  const f=input.files && input.files[0]; if(!f) return;
  const r=new FileReader();
  r.onload=e=>{
    let img=slot.querySelector('img.logo-img');
    if(!img){ img=document.createElement('img'); img.className='logo-img'; img.alt='logo'; slot.insertBefore(img, slot.firstChild); }
    img.src=e.target.result; slot.classList.add('has-logo');
  };
  r.readAsDataURL(f); input.value='';
}

function handleFiles(input){
  const list=input.closest('.uploader').querySelector('.uplist');
  const acc=input.closest('.acc');
  const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

  Array.from(input.files||[]).forEach(f=>{
    const formData = new FormData();
    formData.append('file', f);

    // Upload immediately
    fetch('/uploads', {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': csrfToken },
      body: formData
    })
    .then(res => res.json())
    .then(data => {
      if(data.id){
        if(f.type.indexOf('image/')===0){
          const r=new FileReader();
          r.onload=e=>{
            const d=document.createElement('div');
            d.className='upitem upimg';
            d.dataset.fileId = data.id;
            d.innerHTML='<img src="'+e.target.result+'" alt=""><button type="button" class="upx" onclick="removeFile(this)" aria-label="Verwijderen">&times;</button><span class="upname">'+escapeHtml(f.name)+'</span>';
            list.appendChild(d);
            refreshOpenHeight(acc);
          };
          r.readAsDataURL(f);
        } else {
          const d=document.createElement('div');
          d.className='upitem upfile';
          d.dataset.fileId = data.id;
          d.innerHTML='<span class="upicon">'+fileSvg()+'</span><span class="upmeta"><span class="upname">'+escapeHtml(f.name)+'</span><span class="upsize">'+fmtSize(f.size)+'</span></span><button type="button" class="upx" onclick="removeFile(this)" aria-label="Verwijderen">&times;</button>';
          list.appendChild(d);
          refreshOpenHeight(acc);
        }
      }
    })
    .catch(err => console.error('Upload failed:', err));
  });
  input.value='';
}

function removeFile(btn){
  const acc=btn.closest('.acc');
  const item=btn.closest('.upitem');
  const fileId=item.dataset.fileId;
  if(fileId){
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    fetch('/uploads/'+fileId, {
      method: 'DELETE',
      headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json' }
    }).catch(()=>{});
  }
  item.remove();
  refreshOpenHeight(acc);
}

// ===== DATA COLLECTION =====

function collectFormData(){
  const data = {};

  // Cover meta
  data.datum = document.getElementById('meta-datum').value.trim();
  data.project_name = document.getElementById('meta-project').value.trim();
  data.contactpersoon = document.getElementById('meta-contact').value.trim();

  // Section A: System accesses
  data.system_accesses = [];
  document.querySelectorAll('#access .acard').forEach((card, i)=>{
    const sys = {};
    sys.system_name = card.querySelector('.other-title') ? card.querySelector('.other-title').value.trim() : card.dataset.system;
    sys.sort_order = i;
    sys.action = getSelectVal(card.querySelector('[data-field="action"]'));
    sys.test_account = getVal(card, 'test_account');
    sys.demo_url = getVal(card, 'demo_url');
    sys.access_contact = getVal(card, 'access_contact');
    sys.modules = getVal(card, 'modules');
    sys.remarks = getVal(card, 'remarks');
    data.system_accesses.push(sys);
  });

  // Section B: Colleagues
  data.colleagues = [];
  document.querySelectorAll('#team .acc').forEach((acc, i)=>{
    const col = {};
    col.sort_order = i;
    col.name = acc.querySelector('.pname').value.trim();
    col.function = acc.querySelector('.pfunc').value.trim();
    col.wishes = getFieldVal(acc, 'wishes');
    col.pain_points = getFieldVal(acc, 'pain_points');
    col.desired_workflow = getFieldVal(acc, 'desired_workflow');

    // Manual tasks
    col.manual_tasks = [];
    const taskGroup = acc.querySelector('[data-group="manual_tasks"]');
    if(taskGroup){
      taskGroup.querySelectorAll('.entry').forEach((entry, j)=>{
        col.manual_tasks.push({
          sort_order: j,
          description: getVal(entry, 'description'),
          frequency: getVal(entry, 'frequency'),
          time_per_task: getVal(entry, 'time_per_task'),
        });
      });
    }

    // Missing features
    col.missing_features = [];
    const gapGroup = acc.querySelector('[data-group="missing_features"]');
    if(gapGroup){
      gapGroup.querySelectorAll('.entry').forEach((entry, j)=>{
        col.missing_features.push({
          sort_order: j,
          system_name: getSelectVal(entry.querySelector('[data-field="system_name"]')),
          description: getVal(entry, 'description'),
          workaround: getVal(entry, 'workaround'),
          extra_time: getVal(entry, 'extra_time'),
        });
      });
    }

    // File IDs
    col.file_ids = [];
    acc.querySelectorAll('.upitem[data-file-id]').forEach(item=>{
      col.file_ids.push(parseInt(item.dataset.fileId));
    });

    data.colleagues.push(col);
  });

  // Section C
  data.candidates_capabilities = document.getElementById('candidates_capabilities').value.trim();
  data.candidates_matching = document.getElementById('candidates_matching').value.trim();
  data.candidates_mobile = getSelectVal(document.getElementById('candidates_mobile'));
  data.employers_capabilities = document.getElementById('employers_capabilities').value.trim();
  data.employers_requesting = document.getElementById('employers_requesting').value.trim();
  data.employers_portal = getSelectVal(document.getElementById('employers_portal'));

  // Section D
  data.scope_replace_or_connect = document.getElementById('scope_replace_or_connect').value.trim();
  data.scope_mvp = document.getElementById('scope_mvp').value.trim();
  data.scope_budget_deadline = document.getElementById('scope_budget_deadline').value.trim();
  data.scope_decision_maker = document.getElementById('scope_decision_maker').value.trim();
  data.data_current_systems = document.getElementById('data_current_systems').value.trim();
  data.data_migration = document.getElementById('data_migration').value.trim();
  data.data_api_integrations = document.getElementById('data_api_integrations').value.trim();
  data.compliance_checks = document.getElementById('compliance_checks').value.trim();
  data.compliance_privacy = document.getElementById('compliance_privacy').value.trim();
  data.compliance_working_well = document.getElementById('compliance_working_well').value.trim();
  data.compliance_numbers_success = document.getElementById('compliance_numbers_success').value.trim();

  // Section E
  data.overall_workflows = document.getElementById('overall_workflows').value.trim();
  data.overall_remarks = document.getElementById('overall_remarks').value.trim();

  // Overall file IDs
  data.overall_file_ids = [];
  document.querySelectorAll('#overall-uploader .upitem[data-file-id]').forEach(item=>{
    data.overall_file_ids.push(parseInt(item.dataset.fileId));
  });

  return data;
}

function getVal(container, field){
  const el = container.querySelector('[data-field="'+field+'"]');
  return el ? el.value.trim() : '';
}

function getFieldVal(container, field){
  const el = container.querySelector('[data-field="'+field+'"]');
  return el ? el.value.trim() : '';
}

function getSelectVal(el){
  if(!el) return '';
  const val = el.value;
  return (val && val !== '— kies —') ? val : '';
}

// ===== FORM SUBMISSION =====

function submitForm(){
  const btn = document.getElementById('saveBtn');
  btn.disabled = true;
  btn.innerHTML = '<svg class="animate-spin" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg> Bezig met opslaan…';

  const data = collectFormData();
  const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
  const existing = window.EXISTING_SUBMISSION;
  const isEdit = existing && existing.id;
  const url = isEdit ? '/submissions/' + existing.id : '/submissions';
  const method = isEdit ? 'PUT' : 'POST';

  fetch(url, {
    method: method,
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': csrfToken,
      'Accept': 'application/json',
    },
    body: JSON.stringify(data),
  })
  .then(res => {
    if(!res.ok) return res.json().then(err => { throw err; });
    return res.json();
  })
  .then(result => {
    showToast('Uitvraag succesvol opgeslagen!', 'success');
    btn.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><path d="M20 6L9 17l-5-5"/></svg> Opgeslagen';

    if(!isEdit && result.id){
      // Redirect to the edit URL so the user can continue working
      setTimeout(()=>{ window.location.href = '/' + result.id; }, 1000);
      return;
    }

    // Update the in-memory submission so subsequent saves use PUT
    if(result.id && !window.EXISTING_SUBMISSION){
      window.EXISTING_SUBMISSION = { id: result.id };
    }

    setTimeout(()=>{
      btn.disabled = false;
      btn.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><path d="M17 21v-8H7v8M7 3v5h8"/></svg> Opslaan &amp; Versturen';
    }, 3000);
  })
  .catch(err => {
    console.error('Submit error:', err);
    let msg = 'Er is iets misgegaan bij het opslaan.';
    if(err.errors){
      msg = Object.values(err.errors).flat().join('\n');
    }
    showToast(msg, 'error');
    btn.disabled = false;
    btn.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><path d="M17 21v-8H7v8M7 3v5h8"/></svg> Opslaan &amp; Versturen';
  });
}

// ===== TOAST =====

function showToast(msg, type){
  const toast = document.getElementById('toast');
  toast.textContent = msg;
  toast.className = 'toast ' + (type || '');
  toast.classList.add('show');
  setTimeout(()=> toast.classList.remove('show'), 4000);
}

// ===== INPUT LISTENER =====

document.addEventListener('input', e=>{
  const t=e.target;
  if(t.matches('textarea.auto')){ grow(t); refreshOpenHeight(t.closest('.acc')); }
  const acc = t.closest ? t.closest('.acc') : null;
  if(acc){
    if(t.classList.contains('pname')||t.classList.contains('pfunc')) updateHeader(acc);
    updateFilled(acc);
  }
});

// ===== POPULATE FROM EXISTING DATA =====

function populateForm(sub){
  // Cover meta
  if(sub.datum) document.getElementById('meta-datum').value = sub.datum;
  if(sub.project_name) document.getElementById('meta-project').value = sub.project_name;
  if(sub.contactpersoon) document.getElementById('meta-contact').value = sub.contactpersoon;

  // Section A: System accesses
  if(sub.system_accesses && sub.system_accesses.length){
    document.querySelectorAll('#access .acard').forEach(card=>{
      const sysName = card.dataset.system;
      const isOther = (sysName === 'Overige software');
      const match = sub.system_accesses.find(sa => {
        if(isOther) return !ACCESS.slice(0,-1).includes(sa.system_name);
        return sa.system_name === sysName;
      });
      if(!match) return;
      if(isOther && match.system_name){
        const otherInput = card.querySelector('.other-title');
        if(otherInput) otherInput.value = match.system_name;
      }
      const actionSel = card.querySelector('[data-field="action"]');
      if(actionSel && match.action) setSelectValue(actionSel, match.action);
      setFieldValue(card, 'test_account', match.test_account);
      setFieldValue(card, 'demo_url', match.demo_url);
      setFieldValue(card, 'access_contact', match.access_contact);
      setFieldValue(card, 'modules', match.modules);
      setFieldValue(card, 'remarks', match.remarks);
    });
  }

  // Section B: Colleagues
  const teamHost = document.getElementById('team');
  teamHost.innerHTML = '';
  const colleagues = (sub.colleagues && sub.colleagues.length) ? sub.colleagues : TEAM.map(p=>({name:p.naam, function:p.functie}));
  colleagues.forEach((col, i)=>{
    teamHost.insertAdjacentHTML('beforeend', accBlock({naam:col.name||'', functie:col.function||''}, i, i===0));
    const acc = teamHost.lastElementChild;

    // Wishes, pain points, desired workflow
    setFieldValue(acc, 'wishes', col.wishes);
    setFieldValue(acc, 'pain_points', col.pain_points);
    setFieldValue(acc, 'desired_workflow', col.desired_workflow);

    // Manual tasks
    if(col.manual_tasks && col.manual_tasks.length){
      const taskGroup = acc.querySelector('[data-group="manual_tasks"]');
      if(taskGroup){
        const entries = taskGroup.querySelector('.entries');
        entries.innerHTML = '';
        col.manual_tasks.forEach(task=>{
          entries.insertAdjacentHTML('beforeend', processEntry());
          const entry = entries.lastElementChild;
          setFieldValue(entry, 'description', task.description);
          setFieldValue(entry, 'frequency', task.frequency);
          setFieldValue(entry, 'time_per_task', task.time_per_task);
        });
      }
    }

    // Missing features
    if(col.missing_features && col.missing_features.length){
      const gapGroup = acc.querySelector('[data-group="missing_features"]');
      if(gapGroup){
        const entries = gapGroup.querySelector('.entries');
        entries.innerHTML = '';
        col.missing_features.forEach(feat=>{
          entries.insertAdjacentHTML('beforeend', gapEntry());
          const entry = entries.lastElementChild;
          if(feat.system_name){
            const sel = entry.querySelector('[data-field="system_name"]');
            if(sel) setSelectValue(sel, feat.system_name);
          }
          setFieldValue(entry, 'description', feat.description);
          setFieldValue(entry, 'workaround', feat.workaround);
          setFieldValue(entry, 'extra_time', feat.extra_time);
        });
      }
    }

    // File uploads
    if(col.file_uploads && col.file_uploads.length){
      const uplist = acc.querySelector('.uplist');
      if(uplist){
        col.file_uploads.forEach(f=>{
          const d = document.createElement('div');
          d.className = 'upitem upfile';
          d.dataset.fileId = f.id;
          d.innerHTML = '<span class="upicon">'+fileSvg()+'</span><span class="upmeta"><span class="upname">'+escapeHtml(f.original_name)+'</span><span class="upsize">'+fmtSize(f.file_size)+'</span></span><button type="button" class="upx" onclick="removeFile(this)" aria-label="Verwijderen">&times;</button>';
          uplist.appendChild(d);
        });
      }
    }

    updateFilled(acc);
  });

  // Section C
  setElValue('candidates_capabilities', sub.candidates_capabilities);
  setElValue('candidates_matching', sub.candidates_matching);
  if(sub.candidates_mobile) setSelectValue(document.getElementById('candidates_mobile'), sub.candidates_mobile);
  setElValue('employers_capabilities', sub.employers_capabilities);
  setElValue('employers_requesting', sub.employers_requesting);
  if(sub.employers_portal) setSelectValue(document.getElementById('employers_portal'), sub.employers_portal);

  // Section D
  setElValue('scope_replace_or_connect', sub.scope_replace_or_connect);
  setElValue('scope_mvp', sub.scope_mvp);
  setElValue('scope_budget_deadline', sub.scope_budget_deadline);
  setElValue('scope_decision_maker', sub.scope_decision_maker);
  setElValue('data_current_systems', sub.data_current_systems);
  setElValue('data_migration', sub.data_migration);
  setElValue('data_api_integrations', sub.data_api_integrations);
  setElValue('compliance_checks', sub.compliance_checks);
  setElValue('compliance_privacy', sub.compliance_privacy);
  setElValue('compliance_working_well', sub.compliance_working_well);
  setElValue('compliance_numbers_success', sub.compliance_numbers_success);

  // Section E
  setElValue('overall_workflows', sub.overall_workflows);
  setElValue('overall_remarks', sub.overall_remarks);

  // Overall file uploads
  if(sub.file_uploads && sub.file_uploads.length){
    const uplist = document.querySelector('#overall-uploader .uplist');
    if(uplist){
      sub.file_uploads.forEach(f=>{
        const d = document.createElement('div');
        d.className = 'upitem upfile';
        d.dataset.fileId = f.id;
        d.innerHTML = '<span class="upicon">'+fileSvg()+'</span><span class="upmeta"><span class="upname">'+escapeHtml(f.original_name)+'</span><span class="upsize">'+fmtSize(f.file_size)+'</span></span><button type="button" class="upx" onclick="removeFile(this)" aria-label="Verwijderen">&times;</button>';
        uplist.appendChild(d);
      });
    }
  }
}

function setFieldValue(container, field, value){
  if(!value) return;
  const el = container.querySelector('[data-field="'+field+'"]');
  if(el) el.value = value;
}

function setElValue(id, value){
  if(!value) return;
  const el = document.getElementById(id);
  if(el) el.value = value;
}

function setSelectValue(el, value){
  if(!el || !value) return;
  for(let i=0; i<el.options.length; i++){
    if(el.options[i].value === value || el.options[i].text === value){
      el.selectedIndex = i;
      return;
    }
  }
}

// ===== INIT =====

(function(){
  const sub = window.EXISTING_SUBMISSION;
  document.getElementById('access').innerHTML = ACCESS.map(accessCard).join('');

  if(sub){
    // Editing existing submission
    populateForm(sub);
  } else {
    // New form with defaults
    document.getElementById('team').innerHTML = TEAM.map((p,i)=>accBlock(p,i,i===0)).join('');
  }

  rebuildNav();
  growAll();
})();

// Expose functions globally for onclick handlers
window.toggleAll = toggleAll;
window.addColleague = addColleague;
window.addProcess = addProcess;
window.addGap = addGap;
window.removeEntry = removeEntry;
window.goPerson = goPerson;
window.toggleAcc = toggleAcc;
window.pickLogo = pickLogo;
window.setLogo = setLogo;
window.handleFiles = handleFiles;
window.removeFile = removeFile;
window.submitForm = submitForm;
