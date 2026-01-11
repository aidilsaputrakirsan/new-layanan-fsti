<script setup>
import { Head } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import {
    NCard,
    NGrid,
    NGridItem,
    NStatistic,
    NText
} from 'naive-ui'

defineOptions({
    layout: AdminLayout
})
</script>

<template>
    <Head title="Dashboard" />
    
    <div>
        <h1 class="text-2xl font-bold mb-6">Dashboard</h1>
        
        <NGrid :cols="4" :x-gap="16" :y-gap="16">
            <NGridItem>
                <NCard>
                    <NStatistic label="Total Pengajuan" :value="0" />
                </NCard>
            </NGridItem>
            <NGridItem>
                <NCard>
                    <NStatistic label="Pending" :value="0" />
                </NCard>
            </NGridItem>
            <NGridItem>
                <NCard>
                    <NStatistic label="Selesai" :value="0" />
                </NCard>
            </NGridItem>
            <NGridItem>
                <NCard>
                    <NStatistic label="Form Aktif" :value="0" />
                </NCard>
            </NGridItem>
        </NGrid>
        
        <NCard class="mt-6">
            <NText>Selamat datang di Dashboard Admin FSTI</NText>
        </NCard>
    </div>
</template>
