'use strict';
const menu = document.querySelector('.menu-toggle');
menu?.addEventListener('click', () => {const open = document.getElementById('sidebar').classList.toggle('open');menu.setAttribute('aria-expanded',String(open));});
document.addEventListener('keydown',e=>{if(e.key==='Escape'&&menu){document.getElementById('sidebar').classList.remove('open');menu.setAttribute('aria-expanded','false');}});
document.querySelectorAll('.password-toggle').forEach(button=>button.addEventListener('click',()=>{const field=button.previousElementSibling;const show=field.type==='password';field.type=show?'text':'password';button.textContent=show?'Hide':'Show';button.setAttribute('aria-label',show?'Hide password':'Show password');}));
document.querySelectorAll('[data-confirm]').forEach(form=>form.addEventListener('submit',event=>{if(!confirm(form.dataset.confirm))event.preventDefault();}));
let dirty=false;
document.querySelectorAll('[data-dirty-form]').forEach(form=>{form.addEventListener('input',()=>dirty=true);form.addEventListener('submit',()=>dirty=false);});
window.addEventListener('beforeunload',event=>{if(dirty){event.preventDefault();event.returnValue='';}});
document.querySelectorAll('table').forEach(table=>{
    const labels=[...table.querySelectorAll('thead th')].map(th=>th.textContent.trim());
    table.querySelectorAll('tbody tr').forEach(row=>[...row.cells].forEach((cell,i)=>cell.dataset.label=labels[i]||''));
});

// Show the exact asset being selected, before saving.
document.querySelectorAll('.upload-field input[type="file"]').forEach(input => {
    input.addEventListener('change', () => {
        const file = input.files[0];
        if (!file || !file.type.startsWith('image/')) return;
        const field = input.closest('.upload-field');
        let image = field.querySelector('.asset-preview');
        if (!image) { image = document.createElement('img'); image.className = 'asset-preview'; image.alt = 'Selected image preview'; field.insertBefore(image,input); }
        if (image.dataset.previewUrl) URL.revokeObjectURL(image.dataset.previewUrl);
        image.dataset.previewUrl = URL.createObjectURL(file); image.src = image.dataset.previewUrl;
    });
});
document.querySelectorAll('[data-dirty-form]').forEach(form => {
    form.addEventListener('input', () => {
        const state = form.querySelector('[data-save-state]');
        if (state) { state.textContent = 'Unsaved changes'; state.classList.add('changed'); }
    });
});
// Jump links open the requested section even when it is collapsed.
document.querySelectorAll('.editor-jumps a').forEach(link => link.addEventListener('click', () => {
    const section = document.querySelector(link.getAttribute('href'));
    if (section) section.open = true;
}));
// Bring a field with a validation error into view, including closed sections.
document.addEventListener('invalid', event => {
    const group = event.target.closest('details'); if(group) group.open = true;
}, true);
const recordSearch=document.querySelector('[data-record-search]');
if(recordSearch){
    const panel=recordSearch.closest('.panel'), rows=[...panel.querySelectorAll('tbody tr')], status=panel.querySelector('[data-record-status]');
    const filter=()=>{let count=0;rows.forEach(row=>{const match=row.textContent.toLowerCase().includes(recordSearch.value.trim().toLowerCase())&&(!status.value||row.querySelector('.badge')?.textContent.trim()===status.value);row.hidden=!match;if(match)count++;});panel.querySelector('[data-record-count]').textContent=`${count} of ${rows.length} items shown`;};
    recordSearch.addEventListener('input',filter);status.addEventListener('change',filter);filter();
}
const nameField=document.querySelector('input[name="name"]'), slugField=document.querySelector('input[name="slug"]');
if(nameField&&slugField){let automatic=!slugField.value;slugField.addEventListener('input',()=>automatic=false);nameField.addEventListener('input',()=>{if(automatic)slugField.value=nameField.value.toLowerCase().normalize('NFKD').replace(/[\u0300-\u036f]/g,'').replace(/[^a-z0-9]+/g,'-').replace(/^-|-$/g,'').slice(0,100);});}
// A row editor keeps specifications understandable; the textarea remains a no-JS fallback.
const specs=document.querySelector('textarea[name="specifications"]');
if(specs){
    const builder=document.createElement('div');builder.className='specification-builder';
    const rows=document.createElement('div');builder.append(rows);
    const sync=()=>{specs.value=[...rows.children].map(row=>{const inputs=row.querySelectorAll('input,textarea');return inputs[0].value.trim()||inputs[1].value.trim()?`${inputs[0].value.trim()}: ${inputs[1].value.trim()}`:'';}).filter(Boolean).join('\n');specs.dispatchEvent(new Event('input',{bubbles:true}));};
    const add=(label='',value='')=>{const row=document.createElement('div');row.className='specification-row';for(const [placeholder,text] of [['Specification name',label],['Value',value]]){const input=document.createElement('textarea');input.rows=2;input.placeholder=placeholder;input.setAttribute('aria-label',placeholder);input.value=text;input.addEventListener('input',sync);row.append(input);}const remove=document.createElement('button');remove.type='button';remove.className='button secondary small';remove.textContent='Remove';remove.addEventListener('click',()=>{row.remove();sync();});row.append(remove);rows.append(row);};
    specs.value.split('\n').filter(line=>line.trim()).forEach(line=>{const colon=line.indexOf(':');if(colon>=0)add(line.slice(0,colon),line.slice(colon+1).trim());});
    const button=document.createElement('button');button.type='button';button.className='button secondary small';button.textContent='+ Add specification';button.addEventListener('click',()=>{add();rows.lastChild.querySelector('input,textarea').focus();});builder.append(button);
    specs.after(builder);specs.hidden=true;
    specs.closest('.field').querySelector('label').removeAttribute('for');
}
