<script setup>
import { ref, computed } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import {
    NCard,
    NDescriptions,
    NDescriptionsItem,
    NTag,
    NButton,
    NSpace,
    NTimeline,
    NTimelineItem,
    NModal,
    NForm,
    NFormItem,
    NSelect,
    NInput,
    NDivider,
    NIcon,
    NText,
    useMessage
} from 'naive-ui'
import {
    ArrowBackOutline,
    DocumentOutline,
    DownloadOutline
} from '@vicons/ionicons5'

defineOptions({
    layout: AdminLayout
})

const props = defineProps({
    submission: Object
})

const message = useMessage()
const showStatusModal = ref(false)
const statusForm = ref({
    status: props.submission.status,
    notes: ''
})

const statusOptions = [
    { label: 'Menunggu', value: 'pending' },
    { label: 'Sedang Ditinjau', value: 'in_review' },
    { label: 'Perlu Revisi', value: 'needs_revision' },
    { label: 'Disetujui', value: 'approved' },
    { label: 'Ditolak', value: 'rejected' },
    { label: 'Selesai', value: 'completed' },
]

const statusColors = {
    pending: 'warning',
    in_review: 'info',
    needs_revision: 'error',
    approved: 'success',
    rejected: 'error',
    completed: 'success',
}

const statusLabels = {
    pending: 'Menunggu',
    in_review: 'Sedang Ditinjau',
    needs_revision: 'Perlu Revisi',
    approved: 'Disetujui',
    rejected: 'Ditolak',
    completed: 'Selesai',
}

const formFields = computed(() => {
    return props.submission.form?.schema?.fields || []
})

function getFieldLabel(fieldId) {
    const field = formFields.value.find(f => f.id === fieldId)
    return field?.label || fieldId
}

function getFieldValue(fieldId) {
    return props.submission.data?.[fieldId] || '-'
}

function handleUpdateStatus() {
    router.patch(`/admin/submissions/${props.submission.id}/status`, statusForm.value, {
        onSuccess: () => {
            message.success('Status berhasil diperbarui')
            showStatusModal.value = false
        },
        onError: () => {
            message.error('Gagal memperbarui status')
        }
    })
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
    <Head title="Detail Pengajuan" />
    
    <div>
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center gap-4">
                <Link href="/admin/submissions">
                    <NButton quaternary circle>
                        <template #icon>
                            <NIcon><ArrowBackOutline /></NIcon>
                        </template>
                    </NButton>
                </Link>
                <div>
                    <h1 class="text-2xl font-bold">{{ submission.tracking_number }}</h1>
                    <NText depth="3">{{ submission.form?.title }}</NText>
                </div>
            </div>
            <NButton type="primary" @click="showStatusModal = true">
                Update Status
            </NButton>
        </div>
        
        <div class="grid grid-cols-3 gap-6">
            <div class="col-span-2 space-y-6">
                <NCard title="Data Pengajuan">
                    <NDescriptions label-placement="left" :column="1" bordered>
                        <NDescriptionsItem
                            v-for="field in formFields"
                            :key="field.id"
                            :label="field.label"
                        >
                            <template v-if="field.type === 'file'">
                                <div v-if="submission.files?.find(f => f.field_id === field.id)">
                                    <a
                                        :href="`/storage/${submission.files.find(f => f.field_id === field.id).path}`"
                                        target="_blank"
                                        class="text-blue-600 hover:underline flex items-center gap-1"
                                    >
                                        <NIcon><DocumentOutline /></NIcon>
                                        {{ submission.files.find(f => f.field_id === field.id).original_name }}
                                    </a>
                                </div>
                                <span v-else>-</span>
                            </template>
                            <template v-else>
                                {{ getFieldValue(field.id) }}
                            </template>
                        </NDescriptionsItem>
                    </NDescriptions>
                </NCard>
                
                <NCard title="File Lampiran" v-if="submission.files?.length > 0">
                    <div class="space-y-2">
                        <div
                            v-for="file in submission.files"
                            :key="file.id"
                            class="flex items-center justify-between p-3 bg-gray-50 rounded"
                        >
                            <div class="flex items-center gap-2">
                                <NIcon><DocumentOutline /></NIcon>
                                <div>
                                    <NText>{{ file.original_name }}</NText>
                                    <NText depth="3" class="block text-xs">
                                        {{ getFieldLabel(file.field_id) }}
                                    </NText>
                                </div>
                            </div>
                            <a :href="`/storage/${file.path}`" target="_blank">
                                <NButton size="small">
                                    <template #icon>
                                        <NIcon><DownloadOutline /></NIcon>
                                    </template>
                                    Download
                                </NButton>
                            </a>
                        </div>
                    </div>
                </NCard>
            </div>
            
            <div class="space-y-6">
                <NCard title="Informasi">
                    <NDescriptions label-placement="top" :column="1">
                        <NDescriptionsItem label="Status">
                            <NTag :type="statusColors[submission.status]" size="large">
                                {{ statusLabels[submission.status] }}
                            </NTag>
                        </NDescriptionsItem>
                        <NDescriptionsItem label="Email">
                            {{ submission.email }}
                        </NDescriptionsItem>
                        <NDescriptionsItem label="Tanggal Pengajuan">
                            {{ formatDate(submission.created_at) }}
                        </NDescriptionsItem>
                        <NDescriptionsItem label="Catatan Admin" v-if="submission.admin_notes">
                            {{ submission.admin_notes }}
                        </NDescriptionsItem>
                    </NDescriptions>
                </NCard>
                
                <NCard title="Riwayat Status">
                    <NTimeline v-if="submission.histories?.length > 0">
                        <NTimelineItem
                            v-for="history in submission.histories"
                            :key="history.id"
                            :type="statusColors[history.status]"
                        >
                            <template #header>
                                <NTag :type="statusColors[history.status]" size="small">
                                    {{ statusLabels[history.status] }}
                                </NTag>
                            </template>
                            <NText depth="3" class="text-xs">
                                {{ formatDate(history.created_at) }}
                                <span v-if="history.changed_by">
                                    oleh {{ history.changed_by.name }}
                                </span>
                            </NText>
                            <NText v-if="history.notes" class="block mt-1 text-sm">
                                {{ history.notes }}
                            </NText>
                        </NTimelineItem>
                    </NTimeline>
                    <NText v-else depth="3">Belum ada riwayat</NText>
                </NCard>
            </div>
        </div>
        
        <NModal v-model:show="showStatusModal" preset="card" title="Update Status" style="width: 500px">
            <NForm label-placement="top">
                <NFormItem label="Status">
                    <NSelect
                        v-model:value="statusForm.status"
                        :options="statusOptions"
                    />
                </NFormItem>
                <NFormItem label="Catatan">
                    <NInput
                        v-model:value="statusForm.notes"
                        type="textarea"
                        placeholder="Catatan untuk pemohon (opsional)"
                        :rows="3"
                    />
                </NFormItem>
            </NForm>
            <template #footer>
                <NSpace justify="end">
                    <NButton @click="showStatusModal = false">Batal</NButton>
                    <NButton type="primary" @click="handleUpdateStatus">Simpan</NButton>
                </NSpace>
            </template>
        </NModal>
    </div>
</template>
