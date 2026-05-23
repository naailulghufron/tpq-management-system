<x-public-layout title="Pendaftaran Santri">
    <section class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
        <p class="text-sm font-semibold text-emerald-700">Pendaftaran Santri</p>
        <h1 class="mt-2 text-3xl font-semibold text-gray-950">Form minat pendaftaran santri baru.</h1>
        <div class="mt-6 grid gap-6 lg:grid-cols-[1fr_0.8fr]">
            <form class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm">
                <div class="grid gap-4">
                    <label class="grid gap-1 text-sm font-medium text-gray-700">
                        Nama santri
                        <input type="text" class="rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-emerald-600 focus:outline-none" placeholder="Nama lengkap">
                    </label>
                    <label class="grid gap-1 text-sm font-medium text-gray-700">
                        Nama wali
                        <input type="text" class="rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-emerald-600 focus:outline-none" placeholder="Nama orang tua/wali">
                    </label>
                    <label class="grid gap-1 text-sm font-medium text-gray-700">
                        Nomor WhatsApp
                        <input type="tel" class="rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-emerald-600 focus:outline-none" placeholder="08xx">
                    </label>
                    <label class="grid gap-1 text-sm font-medium text-gray-700">
                        Program diminati
                        <select class="rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-emerald-600 focus:outline-none">
                            <option>Pilih program</option>
                            @foreach ($programs as $program)
                                <option>{{ $program->name }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="grid gap-1 text-sm font-medium text-gray-700">
                        Catatan
                        <textarea rows="4" class="rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-emerald-600 focus:outline-none" placeholder="Usia santri, kemampuan awal, atau jadwal yang diharapkan"></textarea>
                    </label>
                    <button type="button" class="rounded-md bg-emerald-700 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-800">Kirim minat pendaftaran</button>
                </div>
            </form>
            <aside class="rounded-lg border border-yellow-200 bg-yellow-50 p-5">
                <h2 class="font-semibold text-yellow-950">Alur pendaftaran</h2>
                <ol class="mt-4 grid gap-3 text-sm text-yellow-900">
                    <li>1. Isi data minat pendaftaran.</li>
                    <li>2. Pengurus menghubungi wali santri.</li>
                    <li>3. Santri mengikuti asesmen awal.</li>
                    <li>4. Penempatan kelas dan jadwal belajar.</li>
                </ol>
            </aside>
        </div>
    </section>
</x-public-layout>
