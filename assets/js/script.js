document.addEventListener('DOMContentLoaded',()=>{
 document.querySelectorAll('[data-confirm]').forEach(el=>el.addEventListener('click',e=>{if(!confirm(el.dataset.confirm||'Confirmar esta ação?'))e.preventDefault()}));
 document.querySelectorAll('.datatable').forEach(t=>{if(window.DataTable)new DataTable(t,{language:{url:'https://cdn.datatables.net/plug-ins/2.1.8/i18n/pt-BR.json'},pageLength:10})});
 document.querySelectorAll('[data-qty]').forEach(input=>input.addEventListener('change',()=>{if(input.value<1)input.value=1}));
});
