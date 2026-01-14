<script setup>
import { Link } from '@inertiajs/vue3'
import { NLayout, NLayoutHeader, NLayoutContent, NText, NSpace, NButton } from 'naive-ui'
</script>

<template>
    <NLayout class="min-h-screen">
        <NLayoutHeader bordered class="h-16 px-6 flex items-center justify-between bg-white">
            <Link href="/">
                <NText strong class="text-lg">FSTI Dashboard</NText>
            </Link>
            
            <NSpace>
                <Link href="/tracking">
                    <NButton quaternary>Cek Status Pengajuan</NButton>
                </Link>
            </NSpace>
        </NLayoutHeader>
        
        <NLayoutContent class="p-6 bg-gray-50">
            <div class="max-w-4xl mx-auto">
                <slot />
            </div>
        </NLayoutContent>
        
        <div class="bg-white border-t py-4 text-center">
            <NText depth="3">© {{ new Date().getFullYear() }} FSTI - Fakultas Sains dan Teknologi Informasi</NText>
        </div>
    </NLayout>
</template>
