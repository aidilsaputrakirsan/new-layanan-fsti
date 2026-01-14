<script setup>
import { ref } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import { NInput, NButton, NIcon, NAlert } from 'naive-ui'
import { SearchOutline, HomeOutline, DocumentTextOutline } from '@vicons/ionicons5'

const props = defineProps({
    error: String,
    searchedNumber: String
})

const trackingNumber = ref(props.searchedNumber || '')
const loading = ref(false)

function handleSearch() {
    if (!trackingNumber.value.trim()) return
    
    loading.value = true
    router.get('/tracking/search', {
        tracking_number: trackingNumber.value.trim()
    }, {
        onFinish: () => loading.value = false
    })
}
</script>

<template>
    <Head title="Tracking Dokumen" />
    
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
                        <Link href="/layanan/mahasiswa" class="text-gray-600 hover:text-blue-600 transition">Layanan</Link>
                    </nav>
                </div>
            </div>
        </header>

        <!-- Hero Section -->
        <section class="bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 text-white py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <div class="w-16 h-16 bg-blue-500/30 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <NIcon size="36"><SearchOutline /></NIcon>
                </div>
                <h1 class="text-3xl md:text-4xl font-bold mb-3">Tracking Dokumen</h1>
                <p class="text-white/80 max-w-2xl mx-auto">
                    Lacak status pengajuan Anda dengan memasukkan nomor tracking
                </p>
            </div>
        </section>

        <!-- Search Section -->
        <section class="py-12">
            <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                    <NAlert v-if="error" type="error" class="mb-6">
                        {{ error }}
                    </NAlert>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nomor Tracking
                            </label>
                            <NInput
                                v-model:value="trackingNumber"
                                placeholder="Contoh: FSTI-20260111-XXXXX"
                                size="large"
                                @keyup.enter="handleSearch"
                            >
                                <template #prefix>
                                    <NIcon class="text-gray-400"><DocumentTextOutline /></NIcon>
                                </template>
                            </NInput>
                        </div>
                        
                        <NButton
                            type="primary"
                            size="large"
                            block
                            :loading="loading"
                            @click="handleSearch"
                            class="h-12"
                        >
                            <template #icon>
                                <NIcon><SearchOutline /></NIcon>
                            </template>
                            Cari Pengajuan
                        </NButton>
                    </div>
                    
                    <div class="mt-8 pt-6 border-t border-gray-100">
                        <p class="text-sm text-gray-500 text-center">
                            Nomor tracking diberikan setelah Anda berhasil mengirim pengajuan.
                            Simpan nomor tracking untuk memantau status pengajuan Anda.
                        </p>
                    </div>
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
        <footer class="bg-gray-900 text-gray-400 py-8 mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm">
                © Fakultas Sains dan Teknologi Informasi - ITK. Hak Cipta Dilindungi.
            </div>
        </footer>
    </div>
</template>
