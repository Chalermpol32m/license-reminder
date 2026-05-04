const navButtons=[...document.querySelectorAll('.nav-btn')];
const mobileNav=document.getElementById('mobileNav');
const toggle=document.getElementById('menuToggle');
const sections=['receive','product','customer','report'];
mobileNav.innerHTML=navButtons.map(b=>`<button data-section="${b.dataset.section}" class="nav-btn">${b.innerHTML}</button>`).join('');
function switchSection(key){sections.forEach(s=>document.getElementById(`section-${s}`).classList.toggle('hidden',s!==key));document.querySelectorAll('[data-section]').forEach(b=>b.classList.toggle('active',b.dataset.section===key));}
document.addEventListener('click',e=>{const btn=e.target.closest('[data-section]');if(btn){switchSection(btn.dataset.section);mobileNav.classList.add('hidden');}});
toggle?.addEventListener('click',()=>mobileNav.classList.toggle('hidden'));
const get=(k)=>JSON.parse(localStorage.getItem(k)||'[]'); const set=(k,v)=>localStorage.setItem(k,JSON.stringify(v));
const productList=document.getElementById('productList'); const customerList=document.getElementById('customerList');
function renderList(key,el,prefix=''){el.innerHTML=get(key).map((x,i)=>`<li tabindex="0">${prefix}${i+1} : ${x}</li>`).join('')||'<li>ยังไม่มีข้อมูล</li>'}
renderList('products',productList); renderList('customers',customerList,'CUS-');
document.getElementById('productForm').addEventListener('submit',e=>{e.preventDefault();const name=e.target.name.value.trim();if(!name)return;const items=get('products');items.push(name);set('products',items);renderList('products',productList);e.target.reset();});
document.getElementById('customerForm').addEventListener('submit',e=>{e.preventDefault();const name=e.target.name.value.trim();if(!name)return;const items=get('customers');items.push(name);set('customers',items);renderList('customers',customerList,'CUS-');e.target.reset();});
function popLast(key,el,prefix=''){const items=get(key);items.pop();set(key,items);renderList(key,el,prefix)}
document.querySelector('[data-action="delete-product"]').onclick=()=>popLast('products',productList);
document.querySelector('[data-action="delete-customer"]').onclick=()=>popLast('customers',customerList,'CUS-');
document.querySelector('[data-action="edit-product"]').onclick=()=>alert('เลือกแก้ไขจากรายการล่าสุด (ตัวอย่าง UI)');
document.querySelector('[data-action="edit-customer"]').onclick=()=>alert('เลือกแก้ไขจากรายการล่าสุด (ตัวอย่าง UI)');
const form=document.getElementById('receiveForm'); const loading=document.getElementById('loading'); const reportBody=document.getElementById('reportBody');
function renderReports(){const period=document.getElementById('reportPeriod').value;const d=document.getElementById('reportDate').value;let rows=get('receives');if(d){const pivot=new Date(d);rows=rows.filter(r=>{const rd=new Date(r.date);if(period==='day')return r.date===d;if(period==='month')return rd.getMonth()===pivot.getMonth()&&rd.getFullYear()===pivot.getFullYear();if(period==='year')return rd.getFullYear()===pivot.getFullYear();return true;});}
reportBody.innerHTML=rows.map(r=>`<tr><td>${r.date}</td><td>${r.plate}</td><td>${r.company}</td><td>${r.item}</td><td>${r.net}</td><td>${r.operator}</td></tr>`).join('')||'<tr><td colspan="6">ไม่พบข้อมูล</td></tr>';}
form.addEventListener('submit',e=>{e.preventDefault();if(!form.checkValidity()){form.reportValidity();return;}const data=Object.fromEntries(new FormData(form).entries());if(Number(data.net)!==Number(data.gross)-Number(data.tare)){alert('น้ำหนักสุทธิควรเท่ากับ รถหนัก - รถเบา');return;}loading.classList.remove('hidden');setTimeout(()=>{const rows=get('receives');rows.push(data);set('receives',rows);form.reset();renderReports();loading.classList.add('hidden');switchSection('report');},500);});
document.getElementById('reportPeriod').addEventListener('change',renderReports);document.getElementById('reportDate').addEventListener('change',renderReports);
document.getElementById('printReport').addEventListener('click',()=>window.print());
document.getElementById('exportCsv').addEventListener('click',()=>{const rows=get('receives');const head=['date','plate','company','item','bags','gross','tare','net','inTime','outTime','operator'];const csv=[head.join(',')].concat(rows.map(r=>head.map(h=>`"${(r[h]??'')}"`).join(','))).join('\n');const blob=new Blob([csv],{type:'text/csv;charset=utf-8;'});const a=document.createElement('a');a.href=URL.createObjectURL(blob);a.download='report.csv';a.click();});
renderReports();
