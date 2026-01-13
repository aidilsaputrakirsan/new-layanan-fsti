<script setup>
import { Head, Link } from '@inertiajs/vue3'
import { NIcon, NButton } from 'naive-ui'
import { AlertCircleOutline, HomeOutline, ArrowBackOutline } from '@vicons/ionicons5'

const props = defineProps({
    message: String
})
</script>

<template>
    <Head title="Form Tidak Tersedia" />
    
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
                </div>
            </div>
        </header>

        <!-- Content -->
        <section class="py-16">
            <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                    <!-- Warning Header -->
                    <div class="bg-gradient-to-r from-amber-500 to-orange-500 text-white p-8 text-center">
                        <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <NIcon size="48"><AlertCircleOutline /></NIcon>
                        </div>
                        <h1 class="text-2xl font-bold mb-2">Form Tidak Tersedia</h1>
                        <p class="text-amber-100">
                            {{ message || 'Form ini sedang tidak tersedia untuk diisi.' }}
                        </p>
                    </div>
                    
                    <!-- Content -->
                    <div class="p-8">
                        <div class="bg-amber-50 rounded-xl p-4 mb-6">
                            <p class="text-sm text-amber-800">
                                Form mungkin sedang dalam pemeliharaan atau sudah tidak aktif. 
                                Silakan hubungi admin jika Anda memerlukan bantuan.
                            </p>
                        </div>
                        
                        <!-- Actions -->
                        <div class="flex flex-col gap-3">
                            <NButton size="large" block @click="$inertia.visit(document.referrer || '/')">
                                <template #icon>
                                    <NIcon><ArrowBackOutline /></NIcon>
                                </template>
                                Kembali
                            </NButton>
                            <Link href="/">
                                <NButton type="primary" size="large" block>
                                    <template #icon>
                                        <NIcon><HomeOutline /></NIcon>
                                    </template>
                                    Ke Beranda
                                </NButton>
                            </Link>
                        </div>
                    </div>
                </div>
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
