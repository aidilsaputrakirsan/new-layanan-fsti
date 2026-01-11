<script setup>
import { Link } from '@inertiajs/vue3'
import { Head } from '@inertiajs/vue3'
import PublicLayout from '@/Layouts/PublicLayout.vue'
import { NCard, NGrid, NGi, NText, NIcon, NButton, NEmpty, NCollapse, NCollapseItem } from 'naive-ui'
import { DocumentTextOutline, ArrowForwardOutline, SchoolOutline, PersonOutline } from '@vicons/ionicons5'

const props = defineProps({
    type: String,
    categories: Array,
})

const typeLabel = props.type === 'mahasiswa' ? 'Mahasiswa' : 'Dosen'
const typeColor = props.type === 'mahasiswa' ? 'blue' : 'green'
</script>

<template>
    <Head :title="`Layanan ${typeLabel}`" />
    
    <PublicLayout>
        <div class="max-w-6xl mx-auto py-12 px-6">
            <div class="text-center mb-12">
                <NIcon size="64" :class="`text-${typeColor}-500 mb-4`">
                    <SchoolOutline v-if="type === 'mahasiswa'" />
                    <PersonOutline v-else />
                </NIcon>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">
                    Layanan {{ typeLabel }}
                </h1>
                <NText depth="3">
                    Pilih layanan yang Anda butuhkan
                </NText>
            </div>
            
            <div v-if="categories && categories.length > 0">
                <div v-for="category in categories" :key="category.id" class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-700 mb-4 flex items-center gap-2">
                        <span v-if="category.icon">{{ category.icon }}</span>
                        {{ category.name }}
                    </h2>
                    <p v-if="category.description" class="text-gray-500 mb-4">{{ category.description }}</p>
                    
                    <NGrid :cols="3" :x-gap="16" :y-gap="16" v-if="category.forms && category.forms.length > 0">
                        <NGi v-for="form in category.forms" :key="form.id">
                            <NCard hoverable class="h-full">
                                <div class="flex flex-col h-full">
                                    <div class="flex items-start gap-3 mb-3">
                                        <NIcon size="24" class="text-gray-400 mt-1">
                                            <DocumentTextOutline />
                                        </NIcon>
                                        <div class="flex-1">
                                            <h3 class="font-medium text-gray-800">{{ form.title }}</h3>
                                            <NText depth="3" class="text-sm" v-if="form.description">
                                                {{ form.description }}
                                            </NText>
                                        </div>
                                    </div>
                                    <div class="mt-auto pt-4">
                                        <Link :href="`/form/${form.slug}`">
                                            <NButton :type="type === 'mahasiswa' ? 'primary' : 'success'" block>
                                                <template #icon>
                                                    <NIcon><ArrowForwardOutline /></NIcon>
                                                </template>
                                                Isi Form
                                            </NButton>
                                        </Link>
                                    </div>
                                </div>
                            </NCard>
                        </NGi>
                    </NGrid>
                    
                    <NEmpty v-else description="Belum ada form dalam kategori ini" />
                </div>
            </div>
            
            <NEmpty v-else description="Belum ada layanan tersedia" class="py-12" />
            
            <div class="text-center mt-8">
                <Link href="/">
                    <NButton quaternary>
                        ← Kembali ke Beranda
                    </NButton>
                </Link>
            </div>
        </div>
    </PublicLayout>
</template>
