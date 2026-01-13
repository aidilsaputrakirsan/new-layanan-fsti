<script setup>
import { Head, Link } from '@inertiajs/vue3'
import { NIcon, NButton } from 'naive-ui'
import { 
    CheckmarkCircleOutline, 
    SearchOutline, 
    HomeOutline,
    MailOutline,
    CopyOutline
} from '@vicons/ionicons5'
import { useMessage } from 'naive-ui'

const props = defineProps({
    trackingNumber: String,
    email: String
})

const message = useMessage()

function copyTrackingNumber() {
    navigator.clipboard.writeText(props.trackingNumber)
    message.success('Nomor tracking berhasil disalin!')
}
</script>

<template>
    <Head title="Pengajuan Berhasil" />
    
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

        <!-- Success Content -->
        <section class="py-16">
            <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                    <!-- Success Header -->
                    <div class="bg-gradient-to-r from-emerald-500 to-teal-600 text-white p-8 text-center">
                        <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <NIcon size="48"><CheckmarkCircleOutline /></NIcon>
                        </div>
                        <h1 class="text-2xl font-bold mb-2">Pengajuan Berhasil!</h1>
                        <p class="text-emerald-100">
                            Pengajuan Anda telah berhasil dikirim dan sedang diproses
                        </p>
                    </div>
                    
                    <!-- Tracking Number -->
                    <div class="p-8">
                        <div class="bg-gray-50 rounded-xl p-6 text-center mb-6">
                            <p class="text-sm text-gray-500 mb-2">Nomor Tracking</p>
                            <div class="flex items-center justify-center gap-2">
                                <span class="text-2xl font-bold font-mono text-gray-800">
                                    {{ trackingNumber }}
                                </span>
                                <button 
                                    @click="copyTrackingNumber"
                                    class="p-2 hover:bg-gray-200 rounded-lg transition"
                                    title="Salin nomor tracking"
                                >
                                    <NIcon size="20" class="text-gray-500"><CopyOutline /></NIcon>
                                </button>
                            </div>
                        </div>
                        
                        <div class="bg-blue-50 rounded-xl p-4 mb-6">
                            <div class="flex items-start gap-3">
                                <NIcon size="20" class="text-blue-600 mt-0.5"><MailOutline /></NIcon>
                                <div>
                                    <p class="text-sm text-blue-800 font-medium">Notifikasi Email</p>
                                    <p class="text-sm text-blue-600">
                                        Update status akan dikirim ke: <strong>{{ email }}</strong>
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center text-sm text-gray-500 mb-8">
                            <p>Simpan nomor tracking ini untuk mengecek status pengajuan Anda.</p>
                        </div>
                        
                        <!-- Actions -->
                        <div class="flex flex-col gap-3">
                            <Link href="/tracking">
                                <NButton type="primary" size="large" block>
                                    <template #icon>
                                        <NIcon><SearchOutline /></NIcon>
                                    </template>
                                    Cek Status Pengajuan
                                </NButton>
                            </Link>
                            <Link href="/">
                                <NButton size="large" block quaternary>
                                    <template #icon>
                                        <NIcon><HomeOutline /></NIcon>
                                    </template>
                                    Kembali ke Beranda
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
