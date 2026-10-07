<div
    x-data="{
        open: false,
        title: 'Konfirmasi Tindakan',
        message: 'Apakah Anda yakin ingin melanjutkan tindakan ini?',
        confirmText: 'Ya, Lanjutkan',
        confirmColor: 'rose',
        targetForm: null,
        init() {
            window.confirmAction = (options) => {
                this.title = options.title || 'Konfirmasi';
                this.message = options.message || 'Apakah Anda yakin?';
                this.confirmText = options.confirmText || 'Ya, Lanjutkan';
                this.confirmColor = options.color || 'rose';
                this.targetForm = options.form || null;
                this.open = true;
            };
        },
        submit() {
            if (this.targetForm) {
                this.targetForm.submit();
            }
            this.open = false;
        }
    }"
    x-show="open"
    x-on:keydown.escape.window="open = false"
    class="fixed inset-0 z-50 overflow-y-auto"
    style="display: none;"
    role="dialog"
    aria-modal="true"
>
    <!-- Backdrop Blur & Fade -->
    <div
        x-show="open"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs transition-opacity"
        @click="open = false"
    ></div>

    <!-- Modal Content -->
    <div class="min-h-full flex items-center justify-center p-4 text-center sm:p-0">
        <div
            x-show="open"
            x-transition:enter="ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-md w-full border border-slate-100 p-6"
            @click.stop
        >
            <div class="flex flex-col items-center text-center">
                <!-- Icon Badge -->
                <div
                    class="w-12 h-12 rounded-full flex items-center justify-center mb-4 ring-8 transition-colors"
                    :class="{
                        'bg-rose-100 text-rose-600 ring-rose-50': confirmColor === 'rose',
                        'bg-amber-100 text-amber-600 ring-amber-50': confirmColor === 'amber',
                        'bg-teal-100 text-teal-600 ring-teal-50': confirmColor === 'teal'
                    }"
                >
                    <!-- Trash / Danger Icon -->
                    <template x-if="confirmColor === 'rose'">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </template>
                    <!-- Warning / Alert Icon -->
                    <template x-if="confirmColor === 'amber'">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </template>
                    <!-- Success / Info Icon -->
                    <template x-if="confirmColor === 'teal'">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </template>
                </div>

                <!-- Title -->
                <h3 class="text-base sm:text-lg font-bold text-slate-900" x-text="title"></h3>

                <!-- Message -->
                <div class="mt-2 text-xs sm:text-sm text-slate-600 leading-relaxed px-2" x-html="message"></div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-6 flex flex-col-reverse sm:flex-row items-center justify-center gap-2.5">
                <button
                    type="button"
                    @click="open = false"
                    class="w-full sm:w-1/2 px-4 py-2.5 rounded-xl border border-slate-300 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition text-center cursor-pointer"
                >
                    Batal
                </button>
                <button
                    type="button"
                    @click="submit()"
                    class="w-full sm:w-1/2 px-4 py-2.5 rounded-xl text-xs font-semibold text-white shadow-xs transition text-center flex items-center justify-center gap-1.5 cursor-pointer"
                    :class="{
                        'bg-rose-600 hover:bg-rose-700 shadow-rose-200': confirmColor === 'rose',
                        'bg-amber-600 hover:bg-amber-700 shadow-amber-200': confirmColor === 'amber',
                        'bg-teal-600 hover:bg-teal-700 shadow-teal-200': confirmColor === 'teal'
                    }"
                    x-text="confirmText"
                >
                </button>
            </div>
        </div>
    </div>
</div>
