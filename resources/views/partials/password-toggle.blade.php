<style>
    .pw-toggle-btn {
        position: absolute;
        z-index: 5;
        display: none;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        padding: 0;
        margin: 0;
        border: none;
        border-radius: 6px;
        background: transparent;
        color: #6b7280;
        cursor: pointer;
        line-height: 1;
    }
    .pw-toggle-btn:hover { color: #1557c0; }
    .pw-toggle-btn:focus-visible { outline: 2px solid #1557c0; outline-offset: 1px; }
    .pw-toggle-btn svg { width: 20px; height: 20px; pointer-events: none; }
    body.dark-mode .pw-toggle-btn { color: #9ca3af; }
    body.dark-mode .pw-toggle-btn:hover { color: #ffffff; }
</style>
<script>
    (function () {
        var EYE = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>';
        var EYE_OFF = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>';

        function enhance(input) {
            if (input.dataset.pwToggle) return;
            input.dataset.pwToggle = '1';

            var parent = input.parentElement;
            if (!parent) return;
            if (getComputedStyle(parent).position === 'static') parent.style.position = 'relative';

            var btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'pw-toggle-btn';
            btn.setAttribute('aria-label', 'Show password');
            btn.setAttribute('title', 'Show password');
            btn.innerHTML = EYE;
            // Inserted BEFORE the input so "input + label" CSS selectors keep working.
            parent.insertBefore(btn, input);

            input.style.paddingRight = '46px';

            function position() {
                if (input.offsetWidth === 0) return;
                btn.style.display = 'flex';
                btn.style.top = (input.offsetTop + (input.offsetHeight - 32) / 2) + 'px';
                btn.style.left = (input.offsetLeft + input.offsetWidth - 32 - 8) + 'px';
            }

            btn.addEventListener('mousedown', function (e) { e.preventDefault(); });
            btn.addEventListener('click', function () {
                var show = input.type === 'password';
                input.type = show ? 'text' : 'password';
                btn.innerHTML = show ? EYE_OFF : EYE;
                var label = show ? 'Hide password' : 'Show password';
                btn.setAttribute('aria-label', label);
                btn.setAttribute('title', label);
                input.focus();
            });

            if (window.ResizeObserver) new ResizeObserver(position).observe(input);
            if (window.IntersectionObserver) new IntersectionObserver(position).observe(input);
            window.addEventListener('resize', position);
            position();
        }

        function init() {
            document.querySelectorAll('input[type="password"]').forEach(enhance);
        }

        if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
        else init();
        window.addEventListener('load', init);
    })();
</script>
