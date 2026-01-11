<script setup>
import { ref } from 'vue'
import { Head, useForm, Link } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import {
    NCard,
    NForm,
    NFormItem,
    NInput,
    NSelect,
    NSwitch,
    NButton,
    NSpace,
    NInputNumber,
    useMessage
} from 'naive-ui'

defineOptions({
    layout: AdminLayout
})

const message = useMessage()
const formRef = ref(null)

const form = useForm({
    name: '',
    description: '',
    type: null,
    icon: '',
    order: 0,
    is_active: true
})

const typeOptions = [
    { label: 'Mahasiswa', value: 'mahasiswa' },
    { label: 'Dosen', value: 'dosen' }
]

const rules = {
    name: [
        { required: true, message: 'Nama kategori wajib diisi', trigger: 'blur' }
    ],
    type: [
        { required: true, message: 'Tipe kategori wajib dipilih', trigger: 'change' }
    ]
}

function handleSubmit() {
    formRef.value?.validate((errors) => {
        if (!errors) {
            form.post('/admin/categories', {
                onSuccess: () => {
                    message.success('Kategori berhasil ditambahkan')
                },
                onError: () => {
                    message.error('Gagal menambahkan kategori')
                }
            })
        }
    })
}
</script>

<template>
    <Head title="Tambah Kategori" />
    
    <div>
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Tambah Kategori</h1>
        </div>
        
        <NCard style="max-width: 600px">
            <NForm
                ref="formRef"
                :model="form"
                :rules="rules"
                label-placement="left"
                label-width="120"
            >
                <NFormItem label="Nama" path="name">
                    <NInput
                        v-model:value="form.name"
                        placeholder="Masukkan nama kategori"
                        :disabled="form.processing"
                    />
                </NFormItem>
                
                <NFormItem label="Deskripsi" path="description">
                    <NInput
                        v-model:value="form.description"
                        type="textarea"
                        placeholder="Masukkan deskripsi (opsional)"
                        :disabled="form.processing"
                        :rows="3"
                    />
                </NFormItem>
                
                <NFormItem label="Tipe" path="type">
                    <NSelect
                        v-model:value="form.type"
                        :options="typeOptions"
                        placeholder="Pilih tipe kategori"
                        :disabled="form.processing"
                    />
                </NFormItem>
                
                <NFormItem label="Icon" path="icon">
                    <NInput
                        v-model:value="form.icon"
                        placeholder="Nama icon (opsional)"
                        :disabled="form.processing"
                    />
                </NFormItem>
                
                <NFormItem label="Urutan" path="order">
                    <NInputNumber
                        v-model:value="form.order"
                        :min="0"
                        :disabled="form.processing"
                        style="width: 100%"
                    />
                </NFormItem>
                
                <NFormItem label="Status" path="is_active">
                    <NSwitch
                        v-model:value="form.is_active"
                        :disabled="form.processing"
                    >
                        <template #checked>Aktif</template>
                        <template #unchecked>Nonaktif</template>
                    </NSwitch>
                </NFormItem>
                
                <NFormItem label=" ">
                    <NSpace>
                        <NButton
                            type="primary"
                            :loading="form.processing"
                            @click="handleSubmit"
                        >
                            Simpan
                        </NButton>
                        <Link href="/admin/categories">
                            <NButton :disabled="form.processing">Batal</NButton>
                        </Link>
                    </NSpace>
                </NFormItem>
            </NForm>
        </NCard>
    </div>
</template>
