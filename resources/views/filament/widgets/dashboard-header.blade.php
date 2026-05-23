<x-filament-widgets::widget>
    <div class="relative overflow-hidden rounded-lg border border-emerald-100 bg-white shadow-sm">
        <div class="absolute inset-y-0 right-0 hidden w-1/2 opacity-10 sm:block"
            style="background-image: linear-gradient(135deg, transparent 25%, #059669 25%, #059669 30%, transparent 30%, transparent 75%, #d6a84f 75%, #d6a84f 80%, transparent 80%); background-size: 28px 28px;">
        </div>

        <div class="relative grid gap-5 p-5 sm:p-6 lg:grid-cols-[1fr_auto] lg:items-center">
            <div class="min-w-0">
                <p class="text-sm font-medium text-emerald-700">TPQ Management System</p>
                <h2 class="mt-1 text-xl font-semibold tracking-normal text-gray-950 sm:text-2xl">
                    Pusat kendali pendidikan Al-Qur'an
                </h2>
                <p class="mt-2 max-w-3xl text-sm leading-6 text-gray-600">
                    Dashboard ini mengikuti permission user aktif, sehingga metrik dan widget yang muncul sesuai akses kerja masing-masing.
                </p>
            </div>

            <div class="grid grid-cols-2 gap-2 sm:flex sm:flex-wrap sm:justify-end">
                <div class="rounded-md border border-emerald-100 bg-emerald-50 px-3 py-2">
                    <p class="text-xs font-medium text-emerald-700">Role</p>
                    <p class="text-sm font-semibold text-emerald-950">{{ $this->roleSummary() }}</p>
                </div>
                <div class="rounded-md border border-yellow-200 bg-yellow-50 px-3 py-2">
                    <p class="text-xs font-medium text-yellow-700">Akses</p>
                    <p class="text-sm font-semibold text-yellow-950">{{ $this->accessSummary() }}</p>
                </div>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
