/* Lapor Pak — interaksi ringan yang membuat antarmuka terasa hidup.
   Tanpa dependensi selain Lucide (ikon). Semua fitur punya fallback tanpa JS. */
(() => {
    'use strict';

    const $ = (sel, root = document) => root.querySelector(sel);
    const $$ = (sel, root = document) => Array.from(root.querySelectorAll(sel));
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    /* ---------------------------------------------------------------------
       Ikon Lucide
       --------------------------------------------------------------------- */
    const renderIcons = () => {
        if (window.lucide && typeof window.lucide.createIcons === 'function') {
            window.lucide.createIcons();
        }
    };
    renderIcons();

    /* ---------------------------------------------------------------------
       Navigasi samping (ponsel)
       --------------------------------------------------------------------- */
    const sidebar = $('#sidebar');
    const scrim = $('[data-scrim]');
    const openers = $$('[data-sidebar-open]');
    const closers = $$('[data-sidebar-close]');

    if (sidebar && openers.length) {
        const setSidebar = (open) => {
            sidebar.classList.toggle('is-open', open);
            if (scrim) scrim.classList.toggle('is-open', open);
            openers.forEach((b) => b.setAttribute('aria-expanded', String(open)));
            document.body.style.overflow = open ? 'hidden' : '';
        };

        openers.forEach((b) =>
            b.addEventListener('click', () => setSidebar(!sidebar.classList.contains('is-open')))
        );
        closers.forEach((b) => b.addEventListener('click', () => setSidebar(false)));
        if (scrim) scrim.addEventListener('click', () => setSidebar(false));
        $$('a', sidebar).forEach((a) => a.addEventListener('click', () => setSidebar(false)));

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') setSidebar(false);
        });
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) setSidebar(false);
        });
    }

    /* ---------------------------------------------------------------------
       Tampilkan / sembunyikan password
       --------------------------------------------------------------------- */
    $$('[data-pw-toggle]').forEach((button) => {
        button.addEventListener('click', () => {
            const input = document.getElementById(button.dataset.pwToggle);
            if (!input) return;
            const show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            button.setAttribute('aria-label', show ? 'Sembunyikan password' : 'Tampilkan password');
            button.setAttribute('aria-pressed', String(show));
        });
    });

    /* ---------------------------------------------------------------------
       Persyaratan password langsung tercentang
       --------------------------------------------------------------------- */
    $$('[data-pw-check]').forEach((input) => {
        const scope = input.closest('form') || document;
        const items = $$('[data-req]', scope);
        if (!items.length) return;

        const check = () => {
            const v = input.value;
            const rules = {
                length: v.length >= 8,
                letter: /[a-zA-Z]/.test(v),
                number: /[0-9]/.test(v),
            };
            items.forEach((item) =>
                item.classList.toggle('is-ok', Boolean(rules[item.dataset.req]))
            );
        };
        input.addEventListener('input', check);
        check();
    });

    /* ---------------------------------------------------------------------
       Penghitung karakter
       --------------------------------------------------------------------- */
    $$('[data-counter-for]').forEach((counter) => {
        const input = document.getElementById(counter.dataset.counterFor);
        if (!input) return;
        const max = Number(input.getAttribute('maxlength')) || 0;
        const value = $('[data-counter-value]', counter);

        const update = () => {
            const len = input.value.length;
            if (value) value.textContent = String(len);
            if (max) counter.classList.toggle('is-near', len > max * 0.85);
        };
        input.addEventListener('input', update);
        update();
    });

    /* ---------------------------------------------------------------------
       Unggah foto: pratinjau, validasi, hapus, seret-lepas
       --------------------------------------------------------------------- */
    $$('[data-upload]').forEach((box) => {
        const input = $('[data-upload-input]', box);
        const preview = $('[data-upload-preview]', box);
        const image = $('[data-upload-img]', box);
        const remove = $('[data-upload-remove]', box);
        const error = $('[data-upload-error]', box);
        const hiddenHapus = box.parentElement?.querySelector('[data-hapus-foto]');
        const maxKb = Number(box.dataset.maxKb) || 0;
        if (!input) return;

        const showError = (msg) => {
            if (!error) return;
            error.textContent = msg;
            error.hidden = !msg;
        };
        const setHapus = (val) => {
            if (hiddenHapus) hiddenHapus.value = val ? '1' : '0';
        };
        const useFile = (file) => {
            showError('');
            if (!file) return;
            if (maxKb && file.size > maxKb * 1024) {
                showError(`Ukuran foto ${(file.size / 1024 / 1024).toFixed(1)} MB, maksimal ${(maxKb / 1024).toFixed(0)} MB. Coba pilih foto lain.`);
                input.value = '';
                return;
            }
            if (!file.type.startsWith('image/')) {
                showError('Berkas ini bukan foto. Pilih file JPG, PNG, atau WebP.');
                input.value = '';
                return;
            }
            setHapus(false);
            if (preview && image) {
                image.src = URL.createObjectURL(file);
                preview.hidden = false;
            }
        };

        input.addEventListener('change', () => useFile(input.files?.[0]));

        ['dragenter', 'dragover'].forEach((t) =>
            box.addEventListener(t, (e) => {
                e.preventDefault();
                box.classList.add('is-drag');
            })
        );
        ['dragleave', 'drop'].forEach((t) =>
            box.addEventListener(t, (e) => {
                e.preventDefault();
                box.classList.remove('is-drag');
            })
        );
        box.addEventListener('drop', (e) => {
            const file = e.dataTransfer?.files?.[0];
            if (!file) return;
            const dt = new DataTransfer();
            dt.items.add(file);
            input.files = dt.files;
            useFile(file);
        });

        if (remove) {
            remove.addEventListener('click', () => {
                input.value = '';
                showError('');
                if (preview) preview.hidden = true;
                if (image) image.removeAttribute('src');
                setHapus(true);
            });
        }
    });

    /* ---------------------------------------------------------------------
       Pesan flash: tutup manual & memudar otomatis
       --------------------------------------------------------------------- */
    $$('[data-flash]').forEach((flash) => {
        const close = $('[data-flash-close]', flash);
        const dismiss = () => {
            flash.style.transition = 'opacity .28s ease, transform .28s ease';
            flash.style.opacity = '0';
            flash.style.transform = 'translateY(-6px)';
            window.setTimeout(() => flash.remove(), 300);
        };
        if (close) close.addEventListener('click', dismiss);
        if (!flash.hasAttribute('data-flash-sticky')) {
            window.setTimeout(dismiss, 9000);
        }
    });

    /* ---------------------------------------------------------------------
       Dialog konfirmasi
       --------------------------------------------------------------------- */
    $$('[data-dialog-open]').forEach((button) => {
        button.addEventListener('click', () => {
            const dialog = document.getElementById(button.dataset.dialogOpen);
            if (dialog && typeof dialog.showModal === 'function') dialog.showModal();
        });
    });
    $$('dialog').forEach((dialog) => {
        $$('[data-dialog-close]', dialog).forEach((b) =>
            b.addEventListener('click', () => dialog.close())
        );
        dialog.addEventListener('click', (e) => {
            if (e.target === dialog) dialog.close();
        });
    });

    /* ---------------------------------------------------------------------
       Filter langsung diterapkan
       --------------------------------------------------------------------- */
    $$('[data-auto-submit]').forEach((el) => {
        el.addEventListener('change', () => el.form?.requestSubmit());
    });

    /* ---------------------------------------------------------------------
       Cegah klik ganda + tanda proses saat mengirim formulir
       --------------------------------------------------------------------- */
    $$('form[data-lock]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            if (form.dataset.submitting === '1') {
                event.preventDefault();
                return;
            }
            form.dataset.submitting = '1';
            const btn = event.submitter || $('button[type="submit"]', form);
            if (btn) btn.classList.add('is-busy');
            window.setTimeout(() => {
                form.dataset.submitting = '0';
                if (btn) btn.classList.remove('is-busy');
            }, 4000);
        });
    });

    /* ---------------------------------------------------------------------
       Reveal lembut saat masuk layar (sekali)
       --------------------------------------------------------------------- */
    const revealEls = $$('[data-reveal], [data-stagger]');
    if (revealEls.length) {
        if (reduceMotion || !('IntersectionObserver' in window)) {
            revealEls.forEach((el) => el.classList.add('is-in'));
        } else {
            const io = new IntersectionObserver(
                (entries) => {
                    entries.forEach((entry) => {
                        if (!entry.isIntersecting) return;
                        entry.target.classList.add('is-in');
                        io.unobserve(entry.target);
                    });
                },
                { rootMargin: '0px 0px -8% 0px', threshold: 0.1 }
            );
            revealEls.forEach((el) => io.observe(el));
        }
    }

    /* ---------------------------------------------------------------------
       Bayangan header saat digulir
       --------------------------------------------------------------------- */
    const header = $('.topbar, .pub-nav');
    if (header) {
        const onScroll = () => header.classList.toggle('is-scrolled', window.scrollY > 6);
        onScroll();
        window.addEventListener('scroll', onScroll, { passive: true });
    }
})();
