<script setup>
import { ref, h } from 'vue'
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
    NPopconfirm,
    useMessage
} from 'naive-ui'
import {
    AddOutline,
    CreateOutline,
    TrashOutline,
    SearchOutline,
    EyeOutline
} from '@vicons/ionicons5'

defineOptions({
    layout: AdminLayout
})

const props = defineProps({
    forms: Object,
    categories: Array,
    filters: Object
})

const message = useMessage()

const search = ref(props.filters?.search || '')
const categoryFilter = ref(props.filters?.category_id ? Number(props.filters.category_id) : null)
const statusFilter = ref(props.filters?.status || null)

const categoryOptions = [
    { label: 'Semua Kategori', value: null },
    ...props.categories.map(cat => ({
        label: `${cat.name} (${cat.type === 'mahasiswa' ? 'Mahasiswa' : 'Dosen'})`,
        value: cat.id
    }))
]

const statusOptions = [
    { label: 'Semua Status', value: null },
    { label: 'Aktif', value: 'active' },
    { label: 'Nonaktif', value: 'inactive' }
]

const columns = [
    {
        title: 'Judul',
        key: 'title',
        ellipsis: { tooltip: true }
    },
    {
        title: 'Kategori',
        key: 'category',
        width: 150,
        render(row) {
            return row.category?.name || '-'
        }
    },
    {
        title: 'Fields',
        key: 'fields_count',
        width: 80,
        align: 'center',
        render(row) {
            return row.schema?.fields?.length || 0
        }
    },
    {
        title: 'Pengajuan',
        key: 'submissions_count',
        width: 100,
        align: 'center'
    },
    {
        title: 'Status',
        key: 'is_active',
        width: 100,
        render(row) {
            return h(NTag, {
                type: row.is_active ? 'success' : 'default',
                size: 'small',
                class: 'cursor-pointer',
                onClick: () => handleToggleStatus(row.id)
            }, { default: () => row.is_active ? 'Aktif' : 'Nonaktif' })
        }
    },
    {
        title: 'Aksi',
        key: 'actions',
        width: 150,
        render(row) {
            return h(NSpace, { size: 'small' }, {
                default: () => [
                    h(NButton, {
                        size: 'small',
                        quaternary: true,
                        type: 'info',
                        onClick: () => router.get(`/admin/forms/${row.id}/edit`)
                    }, {
                        icon: () => h(NIcon, null, { default: () => h(CreateOutline) })
                    }),
                    h(NPopconfirm, {
                        onPositiveClick: () => handleDelete(row.id)
                    }, {
                        trigger: () => h(NButton, {
                            size: 'small',
                            quaternary: true,
                            type: 'error'
                        }, {
                            icon: () => h(NIcon, null, { default: () => h(TrashOutline) })
                        }),
                        default: () => 'Yakin ingin menghapus form ini?'
                    })
                ]
            })
        }
    }
]

function handleSearch() {
    router.get('/admin/forms', {
        search: search.value || undefined,
        category_id: categoryFilter.value || undefined,
        status: statusFilter.value || undefined
    }, {
        preserveState: true,
        replace: true
    })
}

function handleFilterChange() {
    handleSearch()
}

function handleToggleStatus(id) {
    router.patch(`/admin/forms/${id}/toggle-status`, {}, {
        preserveState: true,
        onSuccess: () => {
            message.success('Status form berhasil diubah')
        }
    })
}

function handleDelete(id) {
    router.delete(`/admin/forms/${id}`, {
        onSuccess: () => {
            message.success('Form berhasil dihapus')
        },
        onError: () => {
            message.error('Gagal menghapus form')
        }
    })
}

function handlePageChange(page) {
    router.get('/admin/forms', {
        page,
        search: search.value || undefined,
        category_id: categoryFilter.value || undefined,
        status: statusFilter.value || undefined
    }, {
        preserveState: true
    })
}
</script>

<template>
    <Head title="Form" />
    
    <div>
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Daftar Form</h1>
            <Link href="/admin/forms/create">
                <NButton type="primary">
                    <template #icon>
                        <NIcon><AddOutline /></NIcon>
                    </template>
                    Buat Form
                </NButton>
            </Link>
        </div>
        
        <NCard>
            <div class="flex gap-4 mb-4">
                <NInput
                    v-model:value="search"
                    placeholder="Cari form..."
                    clearable
                    @keyup.enter="handleSearch"
                    style="width: 300px"
                >
                    <template #prefix>
                        <NIcon><SearchOutline /></NIcon>
                    </template>
                </NInput>
                
                <NSelect
                    v-model:value="categoryFilter"
                    :options="categoryOptions"
                    placeholder="Filter Kategori"
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
            </div>
            
            <NDataTable
                :columns="columns"
                :data="forms.data"
                :bordered="false"
            />
            
            <div class="flex justify-end mt-4" v-if="forms.last_page > 1">
                <NSpace>
                    <NButton
                        :disabled="forms.current_page === 1"
                        @click="handlePageChange(forms.current_page - 1)"
                    >
                        Sebelumnya
                    </NButton>
                    <span class="py-2">
                        Halaman {{ forms.current_page }} dari {{ forms.last_page }}
                    </span>
                    <NButton
                        :disabled="forms.current_page === forms.last_page"
                        @click="handlePageChange(forms.current_page + 1)"
                    >
                        Selanjutnya
                    </NButton>
                </NSpace>
            </div>
        </NCard>
    </div>
</template>
