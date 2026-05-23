<x-filament-panels::page>
    <form wire:submit="save" class="space-y-6">
        <section class="rounded-lg border border-emerald-100 bg-white p-5 shadow-sm">
            <div class="grid gap-4 md:grid-cols-2">
                @foreach ($this->settingFields() as $key => $field)
                    <label class="grid gap-1 text-sm {{ ($field['type'] ?? 'text') === 'textarea' ? 'md:col-span-2' : '' }}">
                        <span class="font-medium text-gray-800">{{ $field['label'] }}</span>

                        @if (($field['type'] ?? 'text') === 'textarea')
                            <textarea
                                wire:model="settings.{{ $key }}"
                                rows="4"
                                class="rounded-md border-gray-300 text-sm focus:border-emerald-600 focus:ring-emerald-600"
                            ></textarea>
                        @elseif (($field['type'] ?? 'text') === 'boolean')
                            <span class="flex items-center gap-3 rounded-md border border-gray-200 bg-gray-50 px-3 py-2">
                                <input
                                    type="checkbox"
                                    wire:model="settings.{{ $key }}"
                                    class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-600"
                                >
                                <span class="text-gray-600">{{ $field['description'] ?? 'Aktifkan pengaturan ini.' }}</span>
                            </span>
                        @else
                            <input
                                type="{{ $field['type'] ?? 'text' }}"
                                wire:model="settings.{{ $key }}"
                                class="rounded-md border-gray-300 text-sm focus:border-emerald-600 focus:ring-emerald-600"
                            >
                        @endif

                        @if (($field['type'] ?? 'text') !== 'boolean' && filled($field['description'] ?? null))
                            <span class="text-xs leading-5 text-gray-500">{{ $field['description'] }}</span>
                        @endif
                    </label>
                @endforeach
            </div>
        </section>

        <div class="flex justify-end">
            <button
                type="submit"
                class="rounded-md bg-emerald-700 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800"
            >
                Simpan Pengaturan
            </button>
        </div>
    </form>
</x-filament-panels::page>
