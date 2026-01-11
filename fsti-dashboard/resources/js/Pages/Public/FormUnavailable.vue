<script setup>
import { Head, Link } from '@inertiajs/vue3'
import PublicLayout from '@/Layouts/PublicLayout.vue'
import {
    NCard,
    NResult,
    NButton
} from 'naive-ui'

defineOptions({
    layout: PublicLayout
})

const props = defineProps({
    message: String
})
</script>

<template>
    <Head title="Form Tidak Tersedia" />
    
    <NCard>
        <NResult
            status="warning"
            title="Form Tidak Tersedia"
            :description="message || 'Form ini sedang tidak tersedia untuk diisi.'"
        >
            <template #footer>
                <Link href="/">
                    <NButton type="primary">Kembali ke Beranda</NButton>
                </Link>
            </template>
        </NResult>
    </NCard>
</template>
