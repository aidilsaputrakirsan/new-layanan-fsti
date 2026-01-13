<script setup>
import { Head, Link } from '@inertiajs/vue3'
import { NIcon, NButton, NTag, NAlert } from 'naive-ui'
import { 
    SearchOutline, 
    HomeOutline, 
    CheckmarkCircleOutline,
    TimeOutline,
    AlertCircleOutline,
    CloseCircleOutline,
    HourglassOutline
} from '@vicons/ionicons5'

const props = defineProps({
    submission: Object
})

const statusConfig = {
    pending: { color: 'warning', icon: HourglassOutline, bg: 'bg-amber-50', border: 'border-amber-200', text: 'text-amber-700' },
    in_review: { color: 'info', icon: TimeOutline, bg: 'bg-blue-50', border: 'border-blue-200', text: 'text-blue-700' },
    needs_revision: { color: 'warning', icon: AlertCircleOutline, bg: 'bg-orange-50', border: 'border-orange-200', text: 'text-orange-700' },
    approved: { color: 'success', icon: CheckmarkCircleOutline, bg: 'bg-green-50', border: 'border-green-200', text: 'text-green-700' },
    rejected: { color: 'error', icon: CloseCircleOutline, bg: 'bg-red-50', border: 'border-red-200', text: 'text-red-700' },
    completed: { color: 'success', icon: CheckmarkCircleOutline, bg: 'bg-emerald-50', border: 'border-emerald-200', text: 'text-emerald-700' },
}

const currentStatus = statusConfig[props.submission?.status] || statusConfig.pending

function formatDate(date) {
    return new Date(date).toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    })
}
</script>

<template>
    <Head title="Status Pengajuan" />
    
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

        <!-- Content -->
        <section class="py-12">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
                <!-- Status Card -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                    <!-- Tracking Number Header -->
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white p-6 text-center">
                        <p class="text-blue-200 text-sm mb-1">Nomor Tracking</p>
                        <h1 class="text-2xl md:text-3xl font-bold font-mono">{{ submission.tracking_number }}</h1>
                    </div>
                    
                    <!-- Status Badge -->
                    <div :class="['p-6 text-center border-b', currentStatus.bg, currentStatus.border]">
                        <div class="flex items-center justify-center gap-3">
                            <div :class="['w-12 h-12 rounded-full flex items-center justify-center', currentStatus.bg]">
                                <NIcon size="28" :class="currentStatus.text">
                                    <component :is="currentStatus.icon" />
                                </NIcon>
                            </div>
                            <div class="text-left">
                                <p class="text-sm text-gray-500">Status Saat Ini</p>
                                <NTag :type="currentStatus.color" size="large">
                                    {{ submission.status_label }}
                                </NTag>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Admin Notes -->
                    <NAlert
                        v-if="submission.status === 'needs_revision' && submission.admin_notes"
                        type="warning"
                        class="m-6"
                    >
                        <template #header>Catatan dari Admin</template>
                        {{ submission.admin_notes }}
                    </NAlert>
                    
                    <!-- Details -->
                    <div class="p-6 space-y-4">
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div class="bg-gray-50 rounded-xl p-4">
                                <p class="text-sm text-gray-500 mb-1">Form</p>
                                <p class="font-medium text-gray-800">{{ submission.form_title }}</p>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-4">
                                <p class="text-sm text-gray-500 mb-1">Email</p>
                                <p class="font-medium text-gray-800">{{ submission.email }}</p>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-4">
                                <p class="text-sm text-gray-500 mb-1">Tanggal Pengajuan</p>
                                <p class="font-medium text-gray-800">{{ formatDate(submission.created_at) }}</p>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-4">
                                <p class="text-sm text-gray-500 mb-1">Terakhir Diperbarui</p>
                                <p class="font-medium text-gray-800">{{ formatDate(submission.updated_at) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- History Timeline -->
                <div v-if="submission.histories?.length > 0" class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <h2 class="text-lg font-bold text-gray-800 mb-6">Riwayat Status</h2>
                    <div class="space-y-4">
                        <div v-for="(history, index) in submission.histories" 
                             :key="index"
                             class="flex gap-4">
                            <div class="flex flex-col items-center">
                                <div :class="[
                                    'w-3 h-3 rounded-full',
                                    index === 0 ? 'bg-blue-600' : 'bg-gray-300'
                                ]"></div>
                                <div v-if="index < submission.histories.length - 1" 
                                     class="w-0.5 h-full bg-gray-200 my-1"></div>
                            </div>
                            <div class="flex-1 pb-4">
                                <div class="flex items-center gap-2 mb-1">
                                    <NTag :type="statusConfig[history.status]?.color || 'default'" size="small">
                                        {{ history.status_label }}
                                    </NTag>
                                    <span class="text-xs text-gray-400">
                                        {{ formatDate(history.created_at) }}
                                    </span>
                                </div>
                                <p v-if="history.notes" class="text-sm text-gray-600">
                                    {{ history.notes }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <Link href="/tracking">
                        <NButton size="large" class="w-full sm:w-auto">
                            <template #icon>
                                <NIcon><SearchOutline /></NIcon>
                            </template>
                            Cari Pengajuan Lain
                        </NButton>
                    </Link>
                    <Link href="/">
                        <NButton size="large" quaternary class="w-full sm:w-auto">
                            <template #icon>
                                <NIcon><HomeOutline /></NIcon>
                            </template>
                            Kembali ke Beranda
                        </NButton>
                    </Link>
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
