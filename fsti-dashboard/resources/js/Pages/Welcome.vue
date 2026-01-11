<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue'
import { NCard, NGrid, NGi, NText, NIcon, NButton } from 'naive-ui'
import { SchoolOutline, PersonOutline } from '@vicons/ionicons5'

defineProps({
    message: String,
})
</script>

<template>
    <PublicLayout>
        <div class="max-w-6xl mx-auto py-12 px-6">
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-gray-800 mb-4">
                    Layanan Administrasi FSTI
                </h1>
                <p class="text-gray-600 text-lg">{{ message }}</p>
            </div>
            
            <NGrid :cols="2" :x-gap="24" :y-gap="24">
                <NGi>
                    <NCard hoverable class="h-full">
                        <div class="text-center py-8">
                            <NIcon size="64" class="text-blue-500 mb-4">
                                <SchoolOutline />
                            </NIcon>
                            <h2 class="text-2xl font-semibold mb-2">Layanan Mahasiswa</h2>
                            <NText depth="3">
                                Akses berbagai layanan administrasi untuk mahasiswa
                            </NText>
                            <div class="mt-6">
                                <NButton type="primary" size="large">
                                    Lihat Layanan
                                </NButton>
                            </div>
                        </div>
                    </NCard>
                </NGi>
                
                <NGi>
                    <NCard hoverable class="h-full">
                        <div class="text-center py-8">
                            <NIcon size="64" class="text-green-500 mb-4">
                                <PersonOutline />
                            </NIcon>
                            <h2 class="text-2xl font-semibold mb-2">Layanan Dosen</h2>
                            <NText depth="3">
                                Akses berbagai layanan administrasi untuk dosen
                            </NText>
                            <div class="mt-6">
                                <NButton type="success" size="large">
                                    Lihat Layanan
                                </NButton>
                            </div>
                        </div>
                    </NCard>
                </NGi>
            </NGrid>
        </div>
    </PublicLayout>
</template>
