<script setup>
import { ref, computed, h } from 'vue'
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
    SearchOutline
} from '@vicons/ionicons5'

defineOptions({
    layout: AdminLayout
})

const props = defineProps({
    categories: {
        type: Object,
        default: () => ({ data: [], current_page: 1, last_page: 1 })
    },
    filters: {
        type: Object,
        default: () => ({})
    }
})

const message = useMessage()

const search = ref(props.filters?.search || '')
const typeFilter = ref(props.filters?.type || null)

const typeOptions = [
    { label: 'Semua Tipe', value: null },
    { label: 'Mahasiswa', value: 'mahasiswa' },
    { label: 'Dosen', value: 'dosen' }
]

const columns = [
    {
        title: 'Nama',
        key: 'name',
        ellipsis: { tooltip: true }
    },
    {
        title: 'Tipe',
        key: 'type',
        width: 120,
        render(row) {
            return h(NTag, {
                type: row.type === 'mahasiswa' ? 'info' : 'warning',
                size: 'small'
            }, { default: () => row.type === 'mahasiswa' ? 'Mahasiswa' : 'Dosen' })
        }
    },
    {
        title: 'Jumlah Form',
        key: 'forms_count',
        width: 120,
        align: 'center'
    },
    {
        title: 'Status',
        key: 'is_active',
        width: 100,
        render(row) {
            return h(NTag, {
                type: row.is_active ? 'success' : 'default',
                size: 'small'
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
                        onClick: () => router.get(`/admin/categories/${row.id}/edit`)
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
                        default: () => 'Yakin ingin menghapus kategori ini?'
                    })
                ]
            })
        }
    }
]

function handleSearch() {
    router.get('/admin/categories', {
        search: search.value || undefined,
        type: typeFilter.value || undefined
    }, {
        preserveState: true,
        replace: true
    })
}

function handleTypeChange(value) {
    typeFilter.value = value
    handleSearch()
}

function handleDelete(id) {
    router.delete(`/admin/categories/${id}`, {
        onSuccess: () => {
            message.success('Kategori berhasil dihapus')
        },
        onError: () => {
            message.error('Gagal menghapus kategori')
        }
    })
}

function handlePageChange(page) {
    router.get('/admin/categories', {
        page,
        search: search.value || undefined,
        type: typeFilter.value || undefined
    }, {
        preserveState: true
    })
}
</script>

<template>
    <Head title="Kategori" />
    
    <div>
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Kategori Layanan</h1>
            <Link href="/admin/categories/create">
                <NButton type="primary">
                    <template #icon>
                        <NIcon><AddOutline /></NIcon>
                    </template>
                    Tambah Kategori
                </NButton>
            </Link>
        </div>
        
        <NCard>
            <div class="flex gap-4 mb-4">
                <NInput
                    v-model:value="search"
                    placeholder="Cari kategori..."
                    clearable
                    @keyup.enter="handleSearch"
                    style="width: 300px"
                >
                    <template #prefix>
                        <NIcon><SearchOutline /></NIcon>
                    </template>
                </NInput>
                
                <NSelect
                    v-model:value="typeFilter"
                    :options="typeOptions"
                    placeholder="Filter Tipe"
                    style="width: 200px"
                    @update:value="handleTypeChange"
                />
            </div>
            
            <NDataTable
                :columns="columns"
                :data="categories.data"
                :bordered="false"
            />
            
            <div class="flex justify-end mt-4" v-if="categories.last_page > 1">
                <NSpace>
                    <NButton
                        :disabled="categories.current_page === 1"
                        @click="handlePageChange(categories.current_page - 1)"
                    >
                        Sebelumnya
                    </NButton>
                    <span class="py-2">
                        Halaman {{ categories.current_page }} dari {{ categories.last_page }}
                    </span>
                    <NButton
                        :disabled="categories.current_page === categories.last_page"
                        @click="handlePageChange(categories.current_page + 1)"
                    >
                        Selanjutnya
                    </NButton>
                </NSpace>
            </div>
        </NCard>
    </div>
</template>
