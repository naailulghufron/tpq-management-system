<x-public-layout title="Profil TPQ">
    <section class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
        <p class="text-sm font-semibold text-emerald-700">Profil TPQ</p>
        <h1 class="mt-2 text-3xl font-semibold text-gray-950">Membina santri dengan ilmu, adab, dan lingkungan yang aman.</h1>
        <div class="mt-6 grid gap-4 md:grid-cols-3">
            @foreach ([['Visi', 'Menjadi TPQ yang menumbuhkan kecintaan kepada Al-Qur’an dan akhlak mulia.'], ['Misi', 'Menghadirkan pembelajaran bertahap, disiplin, dan ramah anak.'], ['Pendekatan', 'Menggabungkan talaqqi, pembiasaan ibadah, dan komunikasi wali santri.']] as [$title, $text])
                <div class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm">
                    <h2 class="font-semibold text-gray-950">{{ $title }}</h2>
                    <p class="mt-2 text-sm leading-6 text-gray-600">{{ $text }}</p>
                </div>
            @endforeach
        </div>
        <div class="mt-8 rounded-lg bg-emerald-700 p-6 text-white">
            <h2 class="text-xl font-semibold">Lingkungan belajar yang tertata</h2>
            <p class="mt-2 text-sm leading-6 text-emerald-50">Setiap kegiatan dirancang agar santri merasa dekat dengan Al-Qur’an, guru dapat memantau perkembangan, dan wali santri mendapatkan informasi yang jelas.</p>
        </div>
    </section>
</x-public-layout>
