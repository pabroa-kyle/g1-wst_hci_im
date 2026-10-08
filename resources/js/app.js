import Alpine from 'alpinejs';

/**
 * A rendered page shown as a scaled-down sheet. The iframe is laid out at
 * `designWidth` CSS pixels and scaled to fit its container exactly.
 */
Alpine.data('sheet', (designWidth = 1280) => ({
    init() {
        const frame = this.$el.querySelector('iframe');
        if (!frame) return;
        const fit = () => {
            const scale = this.$el.clientWidth / designWidth;
            frame.style.width = `${designWidth}px`;
            frame.style.height = `${this.$el.clientHeight / scale}px`;
            frame.style.transform = `scale(${scale})`;
        };
        new ResizeObserver(fit).observe(this.$el);
        fit();
    },
}));

/** Preview stage with desktop / tablet / phone widths. */
Alpine.data('previewStage', () => ({
    device: 'desktop',
    widths: { desktop: 1280, tablet: 834, phone: 390 },
    scale: 1,
    init() {
        if (window.innerWidth < 640) this.device = 'phone';
        else if (window.innerWidth < 1024) this.device = 'tablet';
        new ResizeObserver(() => this.fit()).observe(this.$refs.stage);
        this.$watch('device', () => this.$nextTick(() => this.fit()));
        this.fit();
    },
    fit() {
        const width = this.widths[this.device];
        const available = this.$refs.stage.clientWidth;
        this.scale = Math.min(1, available / width);
        const frame = this.$refs.frame;
        frame.style.width = `${width}px`;
        frame.style.height = `${this.$refs.stage.clientHeight / this.scale}px`;
        frame.style.transform = `scale(${this.scale})`;
        this.$refs.holder.style.width = `${width * this.scale}px`;
    },
}));

/**
 * Tracks unsaved changes on a section form. Changed fields get a coral mark,
 * the save bar switches to "Unsaved changes", and leaving the page warns.
 */
Alpine.data('dirtyForm', () => ({
    dirty: false,
    saving: false,
    init() {
        const mark = (event) => {
            if (event.target?.closest?.('[data-no-dirty]')) return;
            this.dirty = true;
            event.target?.closest?.('[data-field]')?.classList.add('is-dirty');
        };
        this.$el.addEventListener('input', mark);
        this.$el.addEventListener('change', mark);
        this.$el.addEventListener('submit', () => {
            this.saving = true;
            this.dirty = false;
        });
        window.addEventListener('beforeunload', (event) => {
            if (this.dirty && !this.saving) {
                event.preventDefault();
                event.returnValue = '';
            }
        });
    },
}));

/** Add / remove / reorder rows of a repeatable section. */
Alpine.data('repeater', (rows = [], blank = {}, errors = {}) => {
    let key = 0;
    const withKey = (row) => ({ ...row, _k: ++key });
    return {
        rows: rows.map(withKey),
        errors,
        add() {
            this.rows.push(withKey(structuredClone(blank)));
            this.changed();
            this.$nextTick(() => {
                const items = this.$el.querySelectorAll('[data-row]');
                items[items.length - 1]?.querySelector('input, textarea')?.focus();
            });
        },
        remove(index) {
            this.rows.splice(index, 1);
            this.errors = {};
            this.changed();
        },
        move(index, delta) {
            const target = index + delta;
            if (target < 0 || target >= this.rows.length) return;
            const [row] = this.rows.splice(index, 1);
            this.rows.splice(target, 0, row);
            this.errors = {};
            this.changed();
        },
        error(index, field) {
            return this.errors[`items.${index}.${field}`]?.[0] ?? null;
        },
        changed() {
            this.$el.dispatchEvent(new Event('change', { bubbles: true }));
        },
    };
});

/**
 * Profile picture picker. Large phone photos are downscaled in the browser
 * before upload so they stay well under the server's upload limit.
 */
Alpine.data('photoField', (current = null) => ({
    preview: current,
    removed: false,
    busy: false,
    async pick(event) {
        const input = event.target;
        const file = input.files?.[0];
        if (!file) return;
        this.removed = false;
        this.busy = true;
        try {
            const resized = await downscale(file, 1200);
            if (resized) {
                const transfer = new DataTransfer();
                transfer.items.add(resized);
                input.files = transfer.files;
            }
            this.preview = URL.createObjectURL(input.files[0]);
        } finally {
            this.busy = false;
        }
    },
    clear() {
        this.preview = null;
        this.removed = true;
        this.$refs.input.value = '';
        this.$el.dispatchEvent(new Event('change', { bubbles: true }));
    },
}));

async function downscale(file, max) {
    if (!file.type.startsWith('image/') || file.type === 'image/gif') return null;
    try {
        const bitmap = await createImageBitmap(file);
        const ratio = Math.min(1, max / Math.max(bitmap.width, bitmap.height));
        if (ratio === 1 && file.size < 1.5 * 1024 * 1024) return null;
        const canvas = document.createElement('canvas');
        canvas.width = Math.round(bitmap.width * ratio);
        canvas.height = Math.round(bitmap.height * ratio);
        canvas.getContext('2d').drawImage(bitmap, 0, 0, canvas.width, canvas.height);
        const blob = await new Promise((resolve) => canvas.toBlob(resolve, 'image/jpeg', 0.86));
        if (!blob) return null;
        return new File([blob], file.name.replace(/\.\w+$/, '') + '.jpg', { type: 'image/jpeg' });
    } catch {
        return null;
    }
}

window.Alpine = Alpine;
Alpine.start();
