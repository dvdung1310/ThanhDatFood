import './bootstrap';

document.querySelector('.admin-toggle')?.addEventListener('click', () => document.querySelector('.admin-side')?.classList.toggle('open'));
const mobileMenuButton = document.querySelector('.store-nav .menu-toggle');
const mobileMenu = document.querySelector('.store-nav .nav-links');

if (mobileMenuButton && mobileMenu) {
    const setMobileMenu = open => {
        mobileMenu.classList.toggle('open', open);
        mobileMenuButton.setAttribute('aria-expanded', open ? 'true' : 'false');
        mobileMenuButton.setAttribute('aria-label', open ? 'Đóng menu' : 'Mở menu');
        mobileMenuButton.querySelector('i')?.classList.toggle('fa-bars', !open);
        mobileMenuButton.querySelector('i')?.classList.toggle('fa-xmark', open);
        document.body.classList.toggle('mobile-menu-open', open);
    };

    mobileMenuButton.addEventListener('click', event => {
        event.stopPropagation();
        setMobileMenu(!mobileMenu.classList.contains('open'));
    });
    mobileMenu.querySelectorAll('a').forEach(link => link.addEventListener('click', () => setMobileMenu(false)));
    document.addEventListener('click', event => {
        if (mobileMenu.classList.contains('open') && !event.target.closest('.store-nav')) setMobileMenu(false);
    });
    document.addEventListener('keydown', event => {
        if (event.key === 'Escape') setMobileMenu(false);
    });
    window.addEventListener('resize', () => {
        if (window.innerWidth > 900) setMobileMenu(false);
    });
}
document.querySelector('.dropdown-toggle')?.addEventListener('click', event => { if (window.innerWidth <= 900) event.currentTarget.closest('.nav-dropdown')?.classList.toggle('open'); });
const galleryImage = document.querySelector('#mainImage');
const galleryThumbs = [...document.querySelectorAll('.thumbs [data-image]')];

if (galleryImage && galleryThumbs.length) {
    const setActiveThumb = activeButton => {
        galleryThumbs.forEach(button => {
            const active = button === activeButton;
            button.classList.toggle('active', active);
            button.setAttribute('aria-current', active ? 'true' : 'false');
        });
    };

    const initialThumb = galleryThumbs.find(button => button.dataset.image === galleryImage.getAttribute('src')) || galleryThumbs[0];
    setActiveThumb(initialThumb);

    galleryThumbs.forEach(button => button.addEventListener('click', () => {
        galleryImage.src = button.dataset.image;
        setActiveThumb(button);
    }));
}

const latestProductsSection = document.querySelector('.latest-products-section');
const homeNewsSection = document.querySelector('.home-news');
if (latestProductsSection && homeNewsSection) homeNewsSection.before(latestProductsSection);

document.querySelectorAll('.market-section .market-heading > a').forEach(link => {
    link.innerHTML = 'Xem tất cả <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>';
});

const mobileNavContainer = document.querySelector('.store-nav > .container');
if (mobileNavContainer && !mobileNavContainer.querySelector('.mobile-nav-label')) {
    mobileNavContainer.insertAdjacentHTML('afterbegin', '<span class="mobile-nav-label"><i class="fa-solid fa-leaf" aria-hidden="true"></i> Menu điều hướng</span>');
}

const slugSource = document.querySelector('[data-slug-source]');
const slugTarget = document.querySelector('[data-slug-target]');
if (slugSource && slugTarget) {
    const slugify = value => value
        .trim()
        .toLowerCase()
        .replace(/[đĐ]/g, 'd')
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-+|-+$/g, '');

    let isManuallyEdited = slugTarget.dataset.hasSavedSlug === 'true' || slugTarget.value.trim() !== '';

    slugSource.addEventListener('input', () => {
        if (!isManuallyEdited) slugTarget.value = slugify(slugSource.value);
    });
    slugTarget.addEventListener('input', () => {
        isManuallyEdited = slugTarget.value.trim() !== '';
        if (!isManuallyEdited) slugTarget.value = slugify(slugSource.value);
    });

    if (!isManuallyEdited) slugTarget.value = slugify(slugSource.value);
}

const richTextEditor = document.querySelector('#post-content, #product-description');
if (richTextEditor) {
    Promise.all([import('ckeditor5'), import('ckeditor5/ckeditor5.css')]).then(([editor]) => {
        const { ClassicEditor, Essentials, Paragraph, Heading, Bold, Italic, Underline, Link, List, BlockQuote, Table, TableToolbar, TableColumnResize, Alignment, Font, Image, ImageCaption, ImageStyle, ImageToolbar, ImageInsertViaUrl, ImageUpload, ImageResize } = editor;
        return ClassicEditor.create(richTextEditor, {
            licenseKey: 'GPL',
            plugins: [Essentials, Paragraph, Heading, Bold, Italic, Underline, Link, List, BlockQuote, Table, TableToolbar, TableColumnResize, Alignment, Font, Image, ImageCaption, ImageStyle, ImageToolbar, ImageInsertViaUrl, ImageUpload, ImageResize],
            toolbar: ['undo','redo','|','heading','|','bold','italic','underline','fontColor','fontBackgroundColor','|','alignment','bulletedList','numberedList','|','link','insertTable','uploadImage','insertImageViaUrl','blockQuote'],
            table: { contentToolbar: ['tableColumn','tableRow','mergeTableCells'] },
            image: {
                resizeUnit: '%',
                resizeOptions: [
                    { name: 'resizeImage:original', value: null, label: 'Kích thước gốc' },
                    { name: 'resizeImage:75', value: '75', label: '75%' },
                    { name: 'resizeImage:50', value: '50', label: '50%' },
                    { name: 'resizeImage:25', value: '25', label: '25%' }
                ],
                toolbar: ['imageTextAlternative','toggleImageCaption','imageStyle:inline','imageStyle:block','imageStyle:side','|','resizeImage']
            },
            link: { addTargetToExternalLinks: true },
            placeholder: 'Nhập nội dung bài viết...'
        }).then(editorInstance => {
            editorInstance.plugins.get('FileRepository').createUploadAdapter = loader => new LaravelMediaUploadAdapter(loader, richTextEditor.dataset.uploadUrl);
            let addingAlt=false;
            editorInstance.model.document.on('change:data',()=>{
                if(addingAlt)return;
                const images=[];
                for(const value of editorInstance.model.createRangeIn(editorInstance.model.document.getRoot()).getWalker({ignoreElementEnd:true})){
                    if((value.item.name==='imageBlock'||value.item.name==='imageInline')&&!value.item.getAttribute('alt'))images.push(value.item);
                }
                if(images.length){addingAlt=true;editorInstance.model.change(writer=>images.forEach(image=>writer.setAttribute('alt','Hình ảnh nông sản Việt Nam',image)));addingAlt=false;}
            });
            return editorInstance;
        });
    }).catch(error => console.error('Không thể khởi tạo CKEditor:', error));
}

class LaravelMediaUploadAdapter {
    constructor(loader, url) { this.loader=loader; this.url=url; this.controller=new AbortController(); }
    upload() {
        return this.loader.file.then(file => {
            const data=new FormData(); data.append('upload',file);
            return fetch(this.url,{method:'POST',headers:{'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]')?.content,'Accept':'application/json'},body:data,signal:this.controller.signal})
                .then(async response => { const json=await response.json(); if(!response.ok) throw new Error(json.message || 'Không thể tải ảnh lên.'); return {default:json.url}; });
        });
    }
    abort() { this.controller.abort(); }
}

const mediaModal=document.querySelector('#media-modal');
if(mediaModal){
    const hidden=document.querySelector('#featured-image-id'), preview=document.querySelector('#featured-preview'), confirm=document.querySelector('#confirm-featured'), selectedName=document.querySelector('#selected-media-name'), upload=document.querySelector('#featured-upload'), progress=document.querySelector('#upload-progress');
    let selected={id:hidden.value,url:preview.querySelector('img')?.src || '',name:selectedName.textContent};
    const open=()=>{mediaModal.classList.add('open');mediaModal.setAttribute('aria-hidden','false');document.body.classList.add('modal-open')};
    const close=()=>{mediaModal.classList.remove('open');mediaModal.setAttribute('aria-hidden','true');document.body.classList.remove('modal-open')};
    document.querySelector('.media-modal-open')?.addEventListener('click',open);
    mediaModal.querySelectorAll('[data-close-media]').forEach(button=>button.addEventListener('click',close));
    mediaModal.querySelectorAll('[data-media-tab]').forEach(button=>button.addEventListener('click',()=>{mediaModal.querySelectorAll('[data-media-tab]').forEach(x=>x.classList.remove('active'));mediaModal.querySelectorAll('[data-media-pane]').forEach(x=>x.classList.remove('active'));button.classList.add('active');mediaModal.querySelector(`[data-media-pane="${button.dataset.mediaTab}"]`).classList.add('active')}));
    const selectItem=item=>{mediaModal.querySelectorAll('.modal-media-item').forEach(x=>x.classList.remove('selected'));item.classList.add('selected');selected={id:item.dataset.mediaId,url:item.dataset.mediaUrl,name:item.dataset.mediaName};selectedName.textContent=selected.name;confirm.disabled=false};
    mediaModal.addEventListener('click',event=>{const item=event.target.closest('.modal-media-item');if(item)selectItem(item)});
    confirm.addEventListener('click',()=>{hidden.value=selected.id;preview.innerHTML=`<img src="${selected.url}" alt="${selected.name}">`;preview.classList.add('has-image');document.querySelector('#remove-featured').hidden=false;close()});
    document.querySelector('#remove-featured')?.addEventListener('click',event=>{hidden.value='';selected={id:'',url:'',name:'Chưa chọn ảnh'};preview.innerHTML='<span>Chưa có ảnh đại diện</span>';preview.classList.remove('has-image');event.currentTarget.hidden=true});
    const uploadFile=file=>{if(!file)return;progress.hidden=false;const data=new FormData();data.append('upload',file);fetch(mediaModal.dataset.uploadUrl,{method:'POST',headers:{'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content,'Accept':'application/json'},body:data}).then(async response=>{const json=await response.json();if(!response.ok)throw new Error(json.message||'Tải ảnh thất bại');return json}).then(json=>{const item=document.createElement('button');item.type='button';item.className='modal-media-item';item.dataset.mediaId=json.media.id;item.dataset.mediaUrl=json.media.url;item.dataset.mediaName=json.media.name;item.innerHTML=`<img src="${json.media.url}" alt="${json.media.alt_text||''}" loading="lazy"><span>${json.media.name}</span>`;document.querySelector('#modal-media-grid').prepend(item);selectItem(item);mediaModal.querySelector('[data-media-tab="library"]').click()}).catch(error=>alert(error.message)).finally(()=>{progress.hidden=true;upload.value=''})};
    upload.addEventListener('change',()=>uploadFile(upload.files[0]));
    const dropbox=mediaModal.querySelector('.wp-upload-box'); ['dragenter','dragover'].forEach(type=>dropbox.addEventListener(type,event=>{event.preventDefault();dropbox.classList.add('dragging')})); ['dragleave','drop'].forEach(type=>dropbox.addEventListener(type,event=>{event.preventDefault();dropbox.classList.remove('dragging')})); dropbox.addEventListener('drop',event=>uploadFile(event.dataTransfer.files[0]));
}

const productMediaModal=document.querySelector('#product-media-modal');
if(productMediaModal){
    const inputs=document.querySelector('#product-media-inputs'), preview=document.querySelector('#product-media-preview'), count=document.querySelector('#product-selected-count'), confirm=document.querySelector('#confirm-product-media'), upload=document.querySelector('#product-media-upload'), progress=document.querySelector('#product-upload-progress');
    let selectedIds=new Set([...inputs.querySelectorAll('input[name="media_ids[]"]')].map(input=>input.value));
    let primaryId=inputs.querySelector('input[name="primary_media_id"]')?.value || '';
    let draftIds=new Set(selectedIds), draftPrimary=primaryId;
    const escapeHtml=value=>String(value??'').replace(/[&<>'"]/g,char=>({'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#039;','"':'&quot;'}[char]));
    const items=()=>[...productMediaModal.querySelectorAll('.product-modal-media-item')];
    const syncModal=()=>{items().forEach(item=>{item.classList.toggle('selected',draftIds.has(item.dataset.mediaId));item.classList.toggle('primary',item.dataset.mediaId===draftPrimary)});count.textContent=`Đã chọn ${draftIds.size} ảnh`};
    const open=()=>{draftIds=new Set(selectedIds);draftPrimary=primaryId;syncModal();productMediaModal.classList.add('open');productMediaModal.setAttribute('aria-hidden','false');document.body.classList.add('modal-open')};
    const close=()=>{productMediaModal.classList.remove('open');productMediaModal.setAttribute('aria-hidden','true');document.body.classList.remove('modal-open')};
    document.querySelector('.product-media-open')?.addEventListener('click',open);
    productMediaModal.querySelectorAll('[data-close-product-media]').forEach(button=>button.addEventListener('click',close));
    productMediaModal.querySelectorAll('[data-product-media-tab]').forEach(button=>button.addEventListener('click',()=>{productMediaModal.querySelectorAll('[data-product-media-tab]').forEach(item=>item.classList.remove('active'));productMediaModal.querySelectorAll('[data-product-media-pane]').forEach(item=>item.classList.remove('active'));button.classList.add('active');productMediaModal.querySelector(`[data-product-media-pane="${button.dataset.productMediaTab}"]`)?.classList.add('active')}));
    productMediaModal.addEventListener('click',event=>{
        const item=event.target.closest('.product-modal-media-item');if(!item)return;
        const id=item.dataset.mediaId;
        if(event.target.closest('.product-primary-mark')){draftIds.add(id);draftPrimary=id}
        else if(draftIds.has(id)){draftIds.delete(id);if(draftPrimary===id)draftPrimary=[...draftIds][0]||''}
        else{draftIds.add(id);if(!draftPrimary)draftPrimary=id}
        syncModal();
    });
    confirm.addEventListener('click',()=>{
        selectedIds=new Set(draftIds);primaryId=draftPrimary || [...selectedIds][0] || '';
        inputs.innerHTML=[...selectedIds].map(id=>`<input type="hidden" name="media_ids[]" value="${escapeHtml(id)}">`).join('')+`<input type="hidden" name="primary_media_id" value="${escapeHtml(primaryId)}">`;
        const selectedItems=items().filter(item=>selectedIds.has(item.dataset.mediaId));
        preview.classList.toggle('empty',selectedItems.length===0);
        preview.innerHTML=selectedItems.length?selectedItems.map(item=>`<figure data-preview-id="${escapeHtml(item.dataset.mediaId)}"><img src="${escapeHtml(item.dataset.mediaUrl)}" alt="${escapeHtml(item.dataset.mediaName)}"><figcaption>${escapeHtml(item.dataset.mediaName)}</figcaption>${item.dataset.mediaId===primaryId?'<span>Ảnh đại diện</span>':''}</figure>`).join(''):'<div><b>Chưa có ảnh sản phẩm</b><small>Nhấn “Chọn / chỉnh sửa ảnh” để mở thư viện.</small></div>';
        close();
    });
    const uploadFile=async file=>{const data=new FormData();data.append('upload',file);const response=await fetch(productMediaModal.dataset.uploadUrl,{method:'POST',headers:{'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content,'Accept':'application/json'},body:data});const json=await response.json();if(!response.ok)throw new Error(json.message||'Tải ảnh thất bại');const item=document.createElement('button');item.type='button';item.className='modal-media-item product-modal-media-item selected';item.dataset.mediaId=json.media.id;item.dataset.mediaUrl=json.media.url;item.dataset.mediaName=json.media.name;item.innerHTML=`<img src="${escapeHtml(json.media.url)}" alt="${escapeHtml(json.media.alt_text||'')}" loading="lazy"><span>${escapeHtml(json.media.name)}</span><i class="product-primary-mark" title="Đặt làm ảnh đại diện">★</i>`;document.querySelector('#product-modal-media-grid').prepend(item);draftIds.add(String(json.media.id));if(!draftPrimary)draftPrimary=String(json.media.id)};
    const uploadFiles=async files=>{if(!files?.length)return;progress.hidden=false;try{for(const file of files)await uploadFile(file);syncModal();productMediaModal.querySelector('[data-product-media-tab="library"]')?.click()}catch(error){alert(error.message)}finally{progress.hidden=true;upload.value=''}};
    upload.addEventListener('change',()=>uploadFiles(upload.files));
    const dropbox=productMediaModal.querySelector('.wp-upload-box');['dragenter','dragover'].forEach(type=>dropbox.addEventListener(type,event=>{event.preventDefault();dropbox.classList.add('dragging')}));['dragleave','drop'].forEach(type=>dropbox.addEventListener(type,event=>{event.preventDefault();dropbox.classList.remove('dragging')}));dropbox.addEventListener('drop',event=>uploadFiles(event.dataTransfer.files));
}

document.querySelectorAll('[data-settings-tab]').forEach(button=>button.addEventListener('click',()=>{
    document.querySelectorAll('[data-settings-tab]').forEach(item=>item.classList.remove('active'));
    document.querySelectorAll('[data-settings-pane]').forEach(item=>item.classList.remove('active'));
    button.classList.add('active'); document.querySelector(`[data-settings-pane="${button.dataset.settingsTab}"]`)?.classList.add('active');
}));
document.querySelectorAll('.seo-image-select').forEach(select=>select.addEventListener('change',()=>{
    const preview=document.querySelector(`#${select.dataset.preview}`), option=select.options[select.selectedIndex], url=option.dataset.url;
    preview.innerHTML=url?`<img src="${url}" alt="Ảnh SEO">`:'<span>Chưa chọn ảnh</span>';
}));
document.querySelectorAll('[data-seo-count]').forEach(counter=>{const input=document.querySelector(`#${counter.dataset.seoCount}`);const update=()=>counter.textContent=`${input.value.length}/180`;input?.addEventListener('input',update);if(input)update()});
const settingsMediaModal=document.querySelector('#settings-media-modal');
if(settingsMediaModal){
    const hidden=document.querySelector('#favicon-image-id'),preview=document.querySelector('#preview-favicon'),confirm=document.querySelector('#confirm-settings-media'),selectedName=document.querySelector('#settings-selected-media-name'),upload=document.querySelector('#settings-media-upload'),progress=document.querySelector('#settings-media-progress');
    let selected={id:hidden.value,url:preview.querySelector('img')?.src||'',name:selectedName.textContent};
    let activeSeoSelect=null;
    const escape=value=>String(value??'').replace(/[&<>'"]/g,char=>({'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#039;','"':'&quot;'}[char]));
    const open=()=>{settingsMediaModal.classList.add('open');settingsMediaModal.setAttribute('aria-hidden','false');document.body.classList.add('modal-open')};
    const close=()=>{settingsMediaModal.classList.remove('open');settingsMediaModal.setAttribute('aria-hidden','true');document.body.classList.remove('modal-open')};
    const select=item=>{settingsMediaModal.querySelectorAll('.settings-media-item').forEach(x=>x.classList.remove('selected'));item.classList.add('selected');selected={id:item.dataset.mediaId,url:item.dataset.mediaUrl,name:item.dataset.mediaName};selectedName.textContent=selected.name;confirm.disabled=false};
    document.querySelector('.settings-media-open')?.addEventListener('click',()=>{activeSeoSelect=null;settingsMediaModal.querySelector('header h2').textContent='Chọn favicon';confirm.textContent='Dùng làm favicon';open()});
    document.querySelectorAll('.seo-image-select').forEach(selectElement=>{
        selectElement.hidden=true;
        const actions=document.createElement('div');actions.className='settings-media-actions';
        const choose=document.createElement('button');choose.type='button';choose.className='btn secondary';choose.textContent=selectElement.value?'Thay ảnh đại diện':'Chọn ảnh đại diện';
        const remove=document.createElement('button');remove.type='button';remove.className='link-button';remove.textContent='Bỏ ảnh đã chọn';remove.hidden=!selectElement.value;
        actions.append(choose,remove);selectElement.after(actions);
        choose.addEventListener('click',()=>{activeSeoSelect=selectElement;const option=selectElement.options[selectElement.selectedIndex];selected={id:selectElement.value,url:option?.dataset.url||'',name:option?.textContent||'Chưa chọn ảnh'};settingsMediaModal.querySelectorAll('.settings-media-item').forEach(item=>item.classList.toggle('selected',item.dataset.mediaId===selected.id));selectedName.textContent=selected.name;confirm.disabled=!selected.id;settingsMediaModal.querySelector('header h2').textContent='Chọn ảnh đại diện SEO';confirm.textContent='Dùng làm ảnh SEO';open()});
        remove.addEventListener('click',()=>{selectElement.value='';selectElement.dispatchEvent(new Event('change'));choose.textContent='Chọn ảnh đại diện';remove.hidden=true});
        selectElement.addEventListener('change',()=>{choose.textContent=selectElement.value?'Thay ảnh đại diện':'Chọn ảnh đại diện';remove.hidden=!selectElement.value});
    });
    settingsMediaModal.querySelectorAll('[data-close-settings-media]').forEach(button=>button.addEventListener('click',close));
    settingsMediaModal.addEventListener('click',event=>{const item=event.target.closest('.settings-media-item');if(item)select(item)});
    settingsMediaModal.querySelectorAll('[data-settings-media-tab]').forEach(button=>button.addEventListener('click',()=>{settingsMediaModal.querySelectorAll('[data-settings-media-tab]').forEach(x=>x.classList.remove('active'));settingsMediaModal.querySelectorAll('[data-settings-media-pane]').forEach(x=>x.classList.remove('active'));button.classList.add('active');settingsMediaModal.querySelector(`[data-settings-media-pane="${button.dataset.settingsMediaTab}"]`)?.classList.add('active')}));
    confirm.addEventListener('click',()=>{if(activeSeoSelect){let option=[...activeSeoSelect.options].find(item=>item.value===String(selected.id));if(!option){option=new Option(selected.name,selected.id);option.dataset.url=selected.url;activeSeoSelect.add(option)}activeSeoSelect.value=selected.id;activeSeoSelect.dispatchEvent(new Event('change'));close();return}hidden.value=selected.id;preview.innerHTML=`<img src="${escape(selected.url)}" alt="Favicon">`;preview.classList.add('has-image');document.querySelector('#remove-favicon').hidden=false;close()});
    document.querySelector('#remove-favicon')?.addEventListener('click',event=>{hidden.value='';selected={id:'',url:'',name:'Chưa chọn ảnh'};preview.innerHTML='<span>Chưa chọn favicon</span>';preview.classList.remove('has-image');settingsMediaModal.querySelectorAll('.settings-media-item').forEach(x=>x.classList.remove('selected'));selectedName.textContent=selected.name;confirm.disabled=true;event.currentTarget.hidden=true});
    const uploadFile=async file=>{if(!file)return;progress.hidden=false;const data=new FormData();data.append('upload',file);try{const response=await fetch(settingsMediaModal.dataset.uploadUrl,{method:'POST',headers:{'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content,'Accept':'application/json'},body:data});const json=await response.json();if(!response.ok)throw new Error(json.message||'Tải ảnh thất bại');const item=document.createElement('button');item.type='button';item.className='modal-media-item settings-media-item';item.dataset.mediaId=json.media.id;item.dataset.mediaUrl=json.media.url;item.dataset.mediaName=json.media.name;item.innerHTML=`<img src="${escape(json.media.url)}" alt="${escape(json.media.alt_text||'')}" loading="lazy"><span>${escape(json.media.name)}</span>`;document.querySelector('#settings-media-grid').prepend(item);select(item);settingsMediaModal.querySelector('[data-settings-media-tab="library"]')?.click()}catch(error){alert(error.message)}finally{progress.hidden=true;upload.value=''}};
    upload.addEventListener('change',()=>uploadFile(upload.files[0]));
    const dropbox=settingsMediaModal.querySelector('.wp-upload-box');['dragenter','dragover'].forEach(type=>dropbox.addEventListener(type,event=>{event.preventDefault();dropbox.classList.add('dragging')}));['dragleave','drop'].forEach(type=>dropbox.addEventListener(type,event=>{event.preventDefault();dropbox.classList.remove('dragging')}));dropbox.addEventListener('drop',event=>uploadFile(event.dataTransfer.files[0]));
}
const orderQuantity=document.querySelector('#order-quantity');
if(orderQuantity){const format=value=>new Intl.NumberFormat('vi-VN').format(value)+'đ';const update=()=>{const total=Math.max(1,Number(orderQuantity.value)||1)*Number(orderQuantity.dataset.price);document.querySelector('#order-subtotal').textContent=format(total);document.querySelector('#order-total').textContent=format(total)};orderQuantity.addEventListener('input',update);update()}
const bannerSlider=document.querySelector('[data-banner-slider]');
if(bannerSlider){const slides=[...bannerSlider.querySelectorAll('.banner-slide')],dots=[...bannerSlider.querySelectorAll('[data-banner-index]')];let current=0,timer;const show=index=>{current=(index+slides.length)%slides.length;slides.forEach((slide,i)=>slide.classList.toggle('active',i===current));dots.forEach((dot,i)=>dot.classList.toggle('active',i===current))};const autoplay=()=>{clearInterval(timer);if(slides.length>1)timer=setInterval(()=>show(current+1),5500)};bannerSlider.querySelector('.prev')?.addEventListener('click',()=>{show(current-1);autoplay()});bannerSlider.querySelector('.next')?.addEventListener('click',()=>{show(current+1);autoplay()});dots.forEach(dot=>dot.addEventListener('click',()=>{show(Number(dot.dataset.bannerIndex));autoplay()}));bannerSlider.addEventListener('mouseenter',()=>clearInterval(timer));bannerSlider.addEventListener('mouseleave',autoplay);autoplay()}
const bannerImageSelect=document.querySelector('#banner-image-select');
if(bannerImageSelect)bannerImageSelect.addEventListener('change',()=>{const preview=document.querySelector('#banner-image-preview'),url=bannerImageSelect.options[bannerImageSelect.selectedIndex]?.dataset.url;preview.innerHTML=url?`<img src="${url}" alt="Ảnh banner">`:'<span>Chưa chọn ảnh</span>'});
const bannerDisplayMode=document.querySelector('[data-banner-display-mode]');
if(bannerDisplayMode){const syncBannerFields=()=>{const fields=document.querySelector('[data-banner-text-fields]');const imageOnly=bannerDisplayMode.value==='image_only';fields.hidden=imageOnly;fields.querySelectorAll('input,textarea,select').forEach(field=>field.disabled=imageOnly)};bannerDisplayMode.addEventListener('change',syncBannerFields);syncBannerFields()}
