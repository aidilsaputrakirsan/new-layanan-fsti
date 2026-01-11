<script setup>
import { ref } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import PublicLayout from '@/Layouts/PublicLayout.vue'
import {
    NCard,
    NInput,
    NButton,
    NSpace,
    NAlert,
    NIcon,
    NText
} from 'naive-ui'
import { SearchOutline } from '@vicons/ionicons5'

defineOptions({
    layout: PublicLayout
})

const props = defineProps({
    error: String,
    searchedNumber: String
})

const trackingNumber = ref(props.searchedNumber || '')
const loading = ref(false)

function handleSearch() {
    if (!trackingNumber.value.trim()) {
        return
    }
    
    loading.value = true
    router.get('/tracking/search', {
        tracking_number: trackingNumber.value.trim()
    }, {
        onFinish: () => {
            loading.value = false
        }
    })
}
</script>

<template>
    <Head title="Cek Status Pengajuan" />
    
    <NCard>
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold mb-2">Cek Status Pengajuan</h1>
            <NText depth="3">
                Masukkan nomor tracking untuk melihat status pengajuan Anda
            </NText>
        </div>
        
        <NAlert v-if="error" type="error" :title="error" class="mb-4" />
        
        <div class="max-w-md mx-auto">
            <NSpace vertical class="w-full">
                <NInput
                    v-model:value="trackingNumber"
                    placeholder="Contoh: FSTI-20260111-XXXXX"
                    size="large"
                    @keyup.enter="handleSearch"
                >
                    <template #prefix>
                        <NIcon><SearchOutline /></NIcon>
                    </template>
                </NInput>
                
                <NButton
                    type="primary"
                    size="large"
                    block
                    :loading="loading"
                    @click="handleSearch"
                >
                    Cari
                </NButton>
            </NSpace>
        </div>
        
        <div class="mt-8 text-center">
            <NText depth="3" class="text-sm">
                Nomor tracking diberikan setelah Anda berhasil mengirim pengajuan.
                <br />
                Simpan nomor tracking untuk memantau status pengajuan Anda.
            </NText>
        </div>
    </NCard>
</template>
