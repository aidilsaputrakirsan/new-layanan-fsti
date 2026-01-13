<script setup>
import { ref, h, computed } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import {
    NCard,
    NDataTable,
    NButton,
    NSpace,
    NSelect,
    NInput,
    NTag,
    NIcon,
    NDatePicker,
    useMessage
} from 'naive-ui'
import {
    SearchOutline,
    EyeOutline,
    DownloadOutline
} from '@vicons/ionicons5'

defineOptions({
    layout: AdminLayout
})

const props = defineProps({
    submissions: {
        type: Object,
        default: () => ({ data: [], current_page: 1, last_page: 1 })
    },
    forms: {
        type: Array,
        default: () => []
    },
    filters: {
        type: Object,
        default: () => ({})
    }
})

const message = useMessage()

const search = ref(props.filters?.search || '')
const formFilter = ref(props.filters?.form_id ? Number(props.filters.form_id) : null)
const statusFilter = ref(props.filters?.status || null)
const dateRange = ref(null)

const formOptions = computed(() => [
    { label: 'Semua Form', value: null },
    ...(props.forms || []).map(f => ({ label: f.title, value: f.id }))
])

const statusOptions = [
    { label: 'Semua Status', value: null },
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
    in_review: 'Ditinjau',
    needs_revision: 'Revisi',
    approved: 'Disetujui',
    rejected: 'Ditolak',
    completed: 'Selesai',
}

const columns = [
    {
        title: 'Tracking',
        key: 'tracking_number',
        width: 180,
        render(row) {
            return h('span', { class: 'font-mono text-sm' }, row.tracking_number)
        }
    },
    {
        title: 'Form',
        key: 'form',
        ellipsis: { tooltip: true },
        render(row) {
            return row.form?.title || '-'
        }
    },
    {
        title: 'Email',
        key: 'email',
        ellipsis: { tooltip: true }
    },
    {
        title: 'Status',
        key: 'status',
        width: 120,
        render(row) {
            return h(NTag, {
                type: statusColors[row.status] || 'default',
                size: 'small'
            }, { default: () => statusLabels[row.status] || row.status })
        }
    },
    {
        title: 'Tanggal',
        key: 'created_at',
        width: 150,
        render(row) {
            return new Date(row.created_at).toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            })
        }
    },
    {
        title: 'Aksi',
        key: 'actions',
        width: 80,
        render(row) {
            return h(NButton, {
                size: 'small',
                quaternary: true,
                type: 'info',
                onClick: () => router.get(`/admin/submissions/${row.id}`)
            }, {
                icon: () => h(NIcon, null, { default: () => h(EyeOutline) })
            })
        }
    }
]

function handleSearch() {
    const params = {
        search: search.value || undefined,
        form_id: formFilter.value || undefined,
        status: statusFilter.value || undefined,
    }
    
    if (dateRange.value && dateRange.value.length === 2) {
        const formatDate = (ts) => {
            const d = new Date(ts)
            return d.toISOString().split('T')[0]
        }
        params.date_from = formatDate(dateRange.value[0])
        params.date_to = formatDate(dateRange.value[1])
    }
    
    router.get('/admin/submissions', params, {
        preserveState: true,
        replace: true
    })
}

function handleFilterChange() {
    handleSearch()
}

function handleExport() {
    const params = new URLSearchParams()
    if (formFilter.value) params.append('form_id', formFilter.value)
    if (statusFilter.value) params.append('status', statusFilter.value)
    if (dateRange.value && dateRange.value.length === 2) {
        const formatDate = (ts) => {
            const d = new Date(ts)
            return d.toISOString().split('T')[0]
        }
        params.append('date_from', formatDate(dateRange.value[0]))
        params.append('date_to', formatDate(dateRange.value[1]))
    }
    
    window.location.href = `/admin/submissions/export?${params.toString()}`
}

function handlePageChange(page) {
    router.get('/admin/submissions', {
        page,
        search: search.value || undefined,
        form_id: formFilter.value || undefined,
        status: statusFilter.value || undefined,
    }, {
        preserveState: true
    })
}
</script>

<template>
    <Head title="Pengajuan" />
    
    <div>
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Daftar Pengajuan</h1>
            <NButton @click="handleExport">
                <template #icon>
                    <NIcon><DownloadOutline /></NIcon>
                </template>
                Export CSV
            </NButton>
        </div>
        
        <NCard>
            <div class="flex flex-wrap gap-4 mb-4">
                <NInput
                    v-model:value="search"
                    placeholder="Cari tracking/email..."
                    clearable
                    @keyup.enter="handleSearch"
                    style="width: 250px"
                >
                    <template #prefix>
                        <NIcon><SearchOutline /></NIcon>
                    </template>
                </NInput>
                
                <NSelect
                    v-model:value="formFilter"
                    :options="formOptions"
                    placeholder="Filter Form"
                    style="width: 200px"
                    @update:value="handleFilterChange"
                />
                
                <NSelect
                    v-model:value="statusFilter"
                    :options="statusOptions"
                    placeholder="Filter Status"
                    style="width: 150px"
                    @update:value="handleFilterChange"
                />
                
                <NDatePicker
                    v-model:value="dateRange"
                    type="daterange"
                    clearable
                    @update:value="handleFilterChange"
                />
            </div>
            
            <NDataTable
                :columns="columns"
                :data="submissions.data"
                :bordered="false"
            />
            
            <div class="flex justify-end mt-4" v-if="submissions.last_page > 1">
                <NSpace>
                    <NButton
                        :disabled="submissions.current_page === 1"
                        @click="handlePageChange(submissions.current_page - 1)"
                    >
                        Sebelumnya
                    </NButton>
                    <span class="py-2">
                        Halaman {{ submissions.current_page }} dari {{ submissions.last_page }}
                    </span>
                    <NButton
                        :disabled="submissions.current_page === submissions.last_page"
                        @click="handlePageChange(submissions.current_page + 1)"
                    >
                        Selanjutnya
                    </NButton>
                </NSpace>
            </div>
        </NCard>
    </div>
</template>
