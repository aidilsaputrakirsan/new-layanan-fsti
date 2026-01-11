<script setup>
import { Head, Link } from '@inertiajs/vue3'
import PublicLayout from '@/Layouts/PublicLayout.vue'
import {
    NCard,
    NResult,
    NButton,
    NSpace,
    NText,
    NTag
} from 'naive-ui'

defineOptions({
    layout: PublicLayout
})

const props = defineProps({
    trackingNumber: String,
    email: String
})
</script>

<template>
    <Head title="Pengajuan Berhasil" />
    
    <NCard>
        <NResult
            status="success"
            title="Pengajuan Berhasil!"
            description="Pengajuan Anda telah berhasil dikirim dan sedang diproses."
        >
            <template #footer>
                <div class="text-center mb-6">
                    <NText depth="3" class="block mb-2">Nomor Tracking:</NText>
                    <NTag type="success" size="large" class="text-lg font-mono">
                        {{ trackingNumber }}
                    </NTag>
                    <NText depth="3" class="block mt-4 text-sm">
                        Simpan nomor tracking ini untuk mengecek status pengajuan Anda.
                        <br />
                        Notifikasi akan dikirim ke: <strong>{{ email }}</strong>
                    </NText>
                </div>
                
                <NSpace justify="center">
                    <Link href="/tracking">
                        <NButton type="primary">Cek Status Pengajuan</NButton>
                    </Link>
                    <Link href="/">
                        <NButton>Kembali ke Beranda</NButton>
                    </Link>
                </NSpace>
            </template>
        </NResult>
    </NCard>
</template>
