<script setup>
import { Link } from '@inertiajs/vue3'
import { NButton, NIcon, NCard, NSpace } from 'naive-ui'
import {
    SchoolOutline,
    PersonOutline,
    SearchOutline,
    MailOutline,
    LogoWhatsapp,
    TimeOutline,
    LocationOutline,
    LogoInstagram,
    LogoTiktok,
    GlobeOutline,
    DocumentTextOutline,
    PeopleOutline
} from '@vicons/ionicons5'

defineProps({
    message: String,
})

const strukturOrganisasi = [
    { jabatan: 'Dekan', nama: 'Adi Mahmud Jaya Marindra, S.T., M.Eng., Ph.D.' },
    { jabatan: 'Wakil Dekan I (Akademik dan Kemahasiswaan)', nama: 'Irma Fitria, S.Si., M.Si.' },
    { jabatan: 'Wakil Dekan II (Keuangan dan Umum)', nama: 'Yun Tonce Kusuma Priyanto, S.T., M.T.' },
    { jabatan: 'Ketua Jurusan Sains dan Analitika Data', nama: 'Dr. Swastya Rahastama, S.Si., M.Si.' },
    { jabatan: 'Ketua Jurusan Teknik Elektro, Informatika, dan Bisnis', nama: 'M. Ihsan Alfani Putera, S.Tr.Kom, M.Kom' },
]

const features = [
    { icon: DocumentTextOutline, title: 'Form Digital', desc: 'Pengajuan layanan secara online tanpa perlu datang ke kantor' },
    { icon: SearchOutline, title: 'Tracking Real-time', desc: 'Pantau status pengajuan Anda kapan saja dan di mana saja' },
    { icon: MailOutline, title: 'Notifikasi Email', desc: 'Dapatkan update status pengajuan langsung ke email Anda' },
]
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <header class="bg-white shadow-sm sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-lg">F</span>
                        </div>
                        <div>
                            <h1 class="text-lg font-bold text-blue-600">FSTI ITK</h1>
                            <p class="text-xs text-gray-500">Layanan Administrasi</p>
                        </div>
                    </div>
                    <nav class="hidden md:flex items-center gap-6">
                        <a href="#beranda" class="text-gray-600 hover:text-blue-600 transition">Beranda</a>
                        <a href="#layanan" class="text-gray-600 hover:text-blue-600 transition">Layanan</a>
                        <Link href="/tracking" class="text-gray-600 hover:text-blue-600 transition">Tracking</Link>
                        <a href="https://fsti.itk.ac.id" target="_blank" class="text-gray-600 hover:text-blue-600 transition">Website ITK</a>
                    </nav>
                </div>
            </div>
        </header>

        <!-- Hero Section -->
        <section id="beranda" class="relative bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 text-white overflow-hidden">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute inset-0" style="background-image: radial-gradient(circle at 25px 25px, white 2px, transparent 0); background-size: 50px 50px;"></div>
            </div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-28 relative">
                <div class="text-center max-w-3xl mx-auto">
                    <h1 class="text-4xl md:text-5xl font-bold mb-6 leading-tight">
                        Layanan Administrasi Digital
                        <span class="block text-blue-200">FSTI ITK</span>
                    </h1>
                    <p class="text-lg md:text-xl text-blue-100 mb-10 leading-relaxed">
                        Fakultas Sains dan Teknologi Informasi - Institut Teknologi Kalimantan.
                        Akses layanan administrasi dengan mudah, cepat, dan transparan.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <Link href="/tracking">
                            <NButton size="large" class="w-full sm:w-auto bg-white text-blue-600 hover:bg-blue-50 font-semibold px-8">
                                <template #icon>
                                    <NIcon><SearchOutline /></NIcon>
                                </template>
                                Tracking Dokumen
                            </NButton>
                        </Link>
                    </div>
                </div>
            </div>
            <div class="absolute bottom-0 left-0 right-0 h-16 bg-gradient-to-t from-gray-50 to-transparent"></div>
        </section>

        <!-- Layanan Section -->
        <section id="layanan" class="py-16 md:py-24">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Pilih Layanan</h2>
                    <p class="text-gray-600 max-w-2xl mx-auto">
                        Silakan pilih kategori layanan sesuai dengan kebutuhan Anda
                    </p>
                </div>
                
                <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                    <!-- Layanan Mahasiswa -->
                    <Link href="/layanan/mahasiswa" class="group">
                        <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 p-8 border border-gray-100 group-hover:border-blue-200 group-hover:-translate-y-2">
                            <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-blue-600 transition-colors">
                                <NIcon size="32" class="text-blue-600 group-hover:text-white transition-colors">
                                    <SchoolOutline />
                                </NIcon>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800 mb-3">Layanan Mahasiswa</h3>
                            <p class="text-gray-600 mb-6">
                                Akses berbagai layanan administrasi akademik untuk mahasiswa FSTI ITK
                            </p>
                            <div class="flex items-center text-blue-600 font-semibold">
                                Lihat Layanan
                                <svg class="w-5 h-5 ml-2 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </div>
                        </div>
                    </Link>

                    <!-- Layanan Dosen -->
                    <Link href="/layanan/dosen" class="group">
                        <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 p-8 border border-gray-100 group-hover:border-emerald-200 group-hover:-translate-y-2">
                            <div class="w-16 h-16 bg-emerald-100 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-emerald-600 transition-colors">
                                <NIcon size="32" class="text-emerald-600 group-hover:text-white transition-colors">
                                    <PersonOutline />
                                </NIcon>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800 mb-3">Layanan Dosen</h3>
                            <p class="text-gray-600 mb-6">
                                Akses berbagai layanan administrasi untuk dosen dan tenaga kependidikan
                            </p>
                            <div class="flex items-center text-emerald-600 font-semibold">
                                Lihat Layanan
                                <svg class="w-5 h-5 ml-2 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </div>
                        </div>
                    </Link>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-800 mb-4">Keunggulan Layanan</h2>
                    <p class="text-gray-600">Nikmati kemudahan layanan administrasi digital</p>
                </div>
                <div class="grid md:grid-cols-3 gap-8">
                    <div v-for="feature in features" :key="feature.title" class="text-center p-6">
                        <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                            <NIcon size="28" class="text-blue-600">
                                <component :is="feature.icon" />
                            </NIcon>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ feature.title }}</h3>
                        <p class="text-gray-600 text-sm">{{ feature.desc }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gray-900 text-gray-300 pt-16 pb-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid md:grid-cols-3 gap-12 mb-12">
                    <!-- FSTI Info -->
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                                <span class="text-white font-bold text-lg">F</span>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-white">FSTI ITK</h3>
                            </div>
                        </div>
                        <p class="text-sm mb-2 font-semibold text-white">Fakultas Sains dan Teknologi Informasi</p>
                        <p class="text-sm text-gray-400 mb-4">Institut Teknologi Kalimantan</p>
                        <div class="flex items-start gap-2 text-sm text-gray-400">
                            <NIcon size="18" class="mt-0.5 flex-shrink-0"><LocationOutline /></NIcon>
                            <span>Kampus ITK, Jl. Soekarno Hatta KM 15, Karang Joang, Balikpapan Utara, Kalimantan Timur 76127</span>
                        </div>
                        <div class="flex gap-3 mt-4">
                            <a href="https://instagram.com/fsti.itk" target="_blank" class="w-9 h-9 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-blue-600 transition">
                                <NIcon size="18"><LogoInstagram /></NIcon>
                            </a>
                            <a href="#" class="w-9 h-9 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-blue-600 transition">
                                <NIcon size="18"><LogoTiktok /></NIcon>
                            </a>
                        </div>
                    </div>

                    <!-- Kontak -->
                    <div>
                        <h3 class="text-lg font-bold text-white mb-4">Kontak</h3>
                        <div class="space-y-3">
                            <div class="flex items-center gap-3 text-sm">
                                <NIcon size="18" class="text-blue-400"><MailOutline /></NIcon>
                                <span>fsti@itk.ac.id</span>
                            </div>
                            <div>
                                <div class="flex items-center gap-3 text-sm mb-2">
                                    <NIcon size="18" class="text-green-400"><LogoWhatsapp /></NIcon>
                                    <span>WhatsApp</span>
                                </div>
                                <div class="flex gap-2 ml-7">
                                    <a href="#" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded-lg transition">Akademik</a>
                                    <a href="#" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs rounded-lg transition">Keuangan</a>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 text-sm">
                                <NIcon size="18" class="text-orange-400 mt-0.5"><TimeOutline /></NIcon>
                                <div>
                                    <p class="font-medium text-white">Jam Operasional:</p>
                                    <p class="text-gray-400">Senin - Kamis, 08.00 - 16.00 WITA</p>
                                    <p class="text-gray-400">Jumat, 08.00 - 16.30 WITA</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Struktur Organisasi -->
                    <div>
                        <h3 class="text-lg font-bold text-white mb-4">Struktur Organisasi</h3>
                        <div class="space-y-3">
                            <div v-for="item in strukturOrganisasi" :key="item.jabatan" class="text-sm">
                                <p class="font-medium text-white">{{ item.jabatan }}:</p>
                                <p class="text-gray-400">{{ item.nama }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bottom Footer -->
                <div class="border-t border-gray-800 pt-8">
                    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                        <nav class="flex flex-wrap justify-center gap-6 text-sm">
                            <a href="#beranda" class="hover:text-white transition">Beranda</a>
                            <a href="#layanan" class="hover:text-white transition">Layanan Administrasi</a>
                            <Link href="/tracking" class="hover:text-white transition">Tracking Dokumen</Link>
                            <a href="https://itk.ac.id" target="_blank" class="hover:text-white transition">Website ITK</a>
                        </nav>
                    </div>
                    <div class="text-center mt-6 text-sm text-gray-500">
                        © Fakultas Sains dan Teknologi Informasi - ITK. Hak Cipta Dilindungi. 
                        Dikembangkan oleh <a href="#" class="text-blue-400 hover:underline">ADL</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>

<style scoped>
html {
    scroll-behavior: smooth;
}
</style>
