<x-filament-panels::page>
    <form wire:submit="save" class="space-y-6">
        <section class="rounded-lg border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-100 p-4">
                <h2 class="text-base font-semibold text-gray-950">Checklist Permission per Role</h2>
                <p class="mt-1 text-sm text-gray-500">Role dan permission diambil dari database Spatie, bukan dari kode hardcoded.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="sticky left-0 z-10 bg-gray-50 px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Permission</th>
                            @foreach ($this->roles() as $role)
                                <th class="whitespace-nowrap px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide text-gray-500">{{ $role->name }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @foreach ($this->permissionGroups() as $group => $permissions)
                            <tr class="bg-emerald-50">
                                <td colspan="{{ $this->roles()->count() + 1 }}" class="px-4 py-2 text-xs font-bold uppercase tracking-wide text-emerald-800">{{ $group }}</td>
                            </tr>

                            @foreach ($permissions as $permission)
                                <tr class="hover:bg-gray-50">
                                    <td class="sticky left-0 z-10 whitespace-nowrap bg-white px-4 py-3 font-medium text-gray-800">{{ $permission->name }}</td>
                                    @foreach ($this->roles() as $role)
                                        <td class="px-4 py-3 text-center">
                                            <input
                                                type="checkbox"
                                                wire:model="matrix.{{ $role->id }}.{{ $permission->id }}"
                                                class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-600"
                                                @disabled(! auth()->user()?->can('roles.update'))
                                            >
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        @can('roles.update')
            <div class="flex justify-end">
                <button type="submit" class="rounded-md bg-emerald-700 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800">
                    Simpan Matrix
                </button>
            </div>
        @endcan
    </form>
</x-filament-panels::page>
