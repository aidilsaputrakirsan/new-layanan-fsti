<script setup>
import { Link } from '@inertiajs/vue3'
import { Head } from '@inertiajs/vue3'
import { NIcon, NButton, NEmpty } from 'naive-ui'
import { 
    DocumentTextOutline, 
    ArrowForwardOutline, 
    SchoolOutline, 
    PersonOutline,
    HomeOutline,
    SearchOutline
} from '@vicons/ionicons5'

const props = defineProps({
    type: String,
    categories: Array,
})

const typeLabel = props.type === 'mahasiswa' ? 'Mahasiswa' : 'Dosen'
const isStudent = props.type === 'mahasiswa'
</script>

<template>
    <Head :title="`Layanan ${typeLabel}`" />
    
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <header class="bg-white shadow-sm sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <Link href="/" class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-lg">F</span>
                        </div>
                        <div>
                            <h1 class="text-lg font-bold text-blue-600">FSTI ITK</h1>
                            <p class="text-xs text-gray-500">Layanan Administrasi</p>
                        </div>
                    </Link>
                    <nav class="hidden md:flex items-center gap-6">
                        <Link href="/" class="text-gray-600 hover:text-blue-600 transition">Beranda</Link>
                        <Link href="/tracking" class="text-gray-600 hover:text-blue-600 transition">Tracking</Link>
                    </nav>
                </div>
            </div>
        </header>

        <!-- Hero Section -->
        <section :class="[
            'relative text-white py-16',
            isStudent ? 'bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800' : 'bg-gradient-to-br from-emerald-600 via-emerald-700 to-teal-800'
        ]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-center gap-4 mb-4">
                    <div :class="[
                        'w-16 h-16 rounded-2xl flex items-center justify-center',
                        isStudent ? 'bg-blue-500/30' : 'bg-emerald-500/30'
                    ]">
                        <NIcon size="36">
                            <SchoolOutline v-if="isStudent" />
                            <PersonOutline v-else />
                        </NIcon>
                    </div>
                </div>
                <h1 class="text-3xl md:text-4xl font-bold text-center mb-3">
                    Layanan {{ typeLabel }}
                </h1>
                <p class="text-center text-white/80 max-w-2xl mx-auto">
                    Pilih layanan administrasi yang Anda butuhkan. Semua pengajuan dapat dilacak secara real-time.
                </p>
            </div>
        </section>

        <!-- Content -->
        <section class="py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div v-if="categories && categories.length > 0" class="space-y-12">
                    <div v-for="category in categories" :key="category.id">
                        <!-- Category Header -->
                        <div class="mb-6">
                            <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                                <span v-if="category.icon" class="text-2xl">{{ category.icon }}</span>
                                {{ category.name }}
                            </h2>
                            <p v-if="category.description" class="text-gray-500 mt-1">
                                {{ category.description }}
                            </p>
                        </div>
                        
                        <!-- Forms Grid -->
                        <div v-if="category.forms && category.forms.length > 0" 
                             class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            <Link v-for="form in category.forms" 
                                  :key="form.id" 
                                  :href="`/form/${form.slug}`"
                                  class="group">
                                <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 p-6 border border-gray-100 group-hover:border-blue-200 h-full flex flex-col">
                                    <div class="flex items-start gap-4 mb-4">
                                        <div :class="[
                                            'w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 transition-colors',
                                            isStudent 
                                                ? 'bg-blue-100 group-hover:bg-blue-600' 
                                                : 'bg-emerald-100 group-hover:bg-emerald-600'
                                        ]">
                                            <NIcon size="24" :class="[
                                                'transition-colors',
                                                isStudent 
                                                    ? 'text-blue-600 group-hover:text-white' 
                                                    : 'text-emerald-600 group-hover:text-white'
                                            ]">
                                                <DocumentTextOutline />
                                            </NIcon>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="font-semibold text-gray-800 group-hover:text-blue-600 transition-colors">
                                                {{ form.title }}
                                            </h3>
                                            <p v-if="form.description" class="text-sm text-gray-500 mt-1 line-clamp-2">
                                                {{ form.description }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="mt-auto pt-4 border-t border-gray-100">
                                        <span :class="[
                                            'inline-flex items-center text-sm font-medium',
                                            isStudent ? 'text-blue-600' : 'text-emerald-600'
                                        ]">
                                            Isi Form
                                            <NIcon size="16" class="ml-1 group-hover:translate-x-1 transition-transform">
                                                <ArrowForwardOutline />
                                            </NIcon>
                                        </span>
                                    </div>
                                </div>
                            </Link>
                        </div>
                        
                        <div v-else class="bg-white rounded-xl p-8 text-center border border-gray-100">
                            <NEmpty description="Belum ada form dalam kategori ini" />
                        </div>
                    </div>
                </div>
                
                <div v-else class="bg-white rounded-xl p-12 text-center border border-gray-100">
                    <NEmpty description="Belum ada layanan tersedia" />
                </div>
            </div>
        </section>

        <!-- Back Button -->
        <section class="pb-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <Link href="/">
                    <NButton quaternary size="large">
                        <template #icon>
                            <NIcon><HomeOutline /></NIcon>
                        </template>
                        Kembali ke Beranda
                    </NButton>
                </Link>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gray-900 text-gray-400 py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm">
                © Fakultas Sains dan Teknologi Informasi - ITK. Hak Cipta Dilindungi.
            </div>
        </footer>
    </div>
</template>
