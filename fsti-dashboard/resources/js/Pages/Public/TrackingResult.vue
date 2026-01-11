<script setup>
import { Head, Link } from '@inertiajs/vue3'
import PublicLayout from '@/Layouts/PublicLayout.vue'
import {
    NCard,
    NDescriptions,
    NDescriptionsItem,
    NTag,
    NButton,
    NTimeline,
    NTimelineItem,
    NText,
    NAlert,
    NDivider
} from 'naive-ui'

defineOptions({
    layout: PublicLayout
})

const props = defineProps({
    submission: Object
})

const statusColors = {
    pending: 'warning',
    in_review: 'info',
    needs_revision: 'error',
    approved: 'success',
    rejected: 'error',
    completed: 'success',
}

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
    
    <div class="space-y-6">
        <NCard>
            <div class="text-center mb-6">
                <NText depth="3" class="block mb-2">Nomor Tracking</NText>
                <h1 class="text-2xl font-bold font-mono">{{ submission.tracking_number }}</h1>
            </div>
            
            <div class="flex justify-center mb-6">
                <NTag :type="statusColors[submission.status]" size="large">
                    {{ submission.status_label }}
                </NTag>
            </div>
            
            <NAlert
                v-if="submission.status === 'needs_revision' && submission.admin_notes"
                type="warning"
                title="Catatan dari Admin"
                class="mb-4"
            >
                {{ submission.admin_notes }}
            </NAlert>
            
            <NDescriptions label-placement="left" :column="1" bordered>
                <NDescriptionsItem label="Form">
                    {{ submission.form_title }}
                </NDescriptionsItem>
                <NDescriptionsItem label="Email">
                    {{ submission.email }}
                </NDescriptionsItem>
                <NDescriptionsItem label="Tanggal Pengajuan">
                    {{ formatDate(submission.created_at) }}
                </NDescriptionsItem>
                <NDescriptionsItem label="Terakhir Diperbarui">
                    {{ formatDate(submission.updated_at) }}
                </NDescriptionsItem>
            </NDescriptions>
        </NCard>
        
        <NCard title="Riwayat Status" v-if="submission.histories?.length > 0">
            <NTimeline>
                <NTimelineItem
                    v-for="(history, index) in submission.histories"
                    :key="index"
                    :type="statusColors[history.status]"
                >
                    <template #header>
                        <NTag :type="statusColors[history.status]" size="small">
                            {{ history.status_label }}
                        </NTag>
                    </template>
                    <NText depth="3" class="text-xs">
                        {{ formatDate(history.created_at) }}
                    </NText>
                    <NText v-if="history.notes" class="block mt-1 text-sm">
                        {{ history.notes }}
                    </NText>
                </NTimelineItem>
            </NTimeline>
        </NCard>
        
        <div class="text-center">
            <Link href="/tracking">
                <NButton>Cari Pengajuan Lain</NButton>
            </Link>
        </div>
    </div>
</template>
