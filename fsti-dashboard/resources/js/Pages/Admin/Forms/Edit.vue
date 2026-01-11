<script setup>
import { ref, computed } from 'vue'
import { Head, useForm, Link } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import FieldPalette from '@/Components/FormBuilder/FieldPalette.vue'
import FieldEditor from '@/Components/FormBuilder/FieldEditor.vue'
import FormCanvas from '@/Components/FormBuilder/FormCanvas.vue'
import {
    NCard,
    NForm,
    NFormItem,
    NInput,
    NSelect,
    NSwitch,
    NButton,
    NSpace,
    NGrid,
    NGridItem,
    useMessage
} from 'naive-ui'

defineOptions({
    layout: AdminLayout
})

const props = defineProps({
    form: Object,
    categories: Array
})

const message = useMessage()
const formRef = ref(null)
const showFieldEditor = ref(false)
const editingField = ref(null)

const formData = useForm({
    category_id: props.form.category_id,
    title: props.form.title,
    description: props.form.description || '',
    schema: props.form.schema || {
        fields: [],
        settings: {
            submitButtonText: 'Kirim',
            successMessage: 'Pengajuan berhasil dikirim.'
        }
    },
    is_active: props.form.is_active
})

const categoryOptions = computed(() => {
    return props.categories.map(cat => ({
        label: `${cat.name} (${cat.type === 'mahasiswa' ? 'Mahasiswa' : 'Dosen'})`,
        value: cat.id
    }))
})

const rules = {
    title: [{ required: true, message: 'Judul form wajib diisi', trigger: 'blur' }],
    category_id: [{ required: true, message: 'Kategori wajib dipilih', trigger: 'change', type: 'number' }]
}

function generateFieldId() {
    return 'field_' + Math.random().toString(36).substr(2, 9)
}

function handleAddField(type) {
    const newField = {
        id: generateFieldId(),
        type,
        label: '',
        placeholder: '',
        required: false,
        options: ['select', 'radio'].includes(type) ? [{ label: '', value: '' }] : undefined,
        validation: {}
    }
    formData.schema.fields.push(newField)
    editingField.value = newField
    showFieldEditor.value = true
}

function handleEditField(field) {
    editingField.value = field
    showFieldEditor.value = true
}

function handleSaveField(updatedField) {
    const index = formData.schema.fields.findIndex(f => f.id === updatedField.id)
    if (index !== -1) {
        formData.schema.fields[index] = updatedField
    }
}

function handleDeleteField(fieldId) {
    const index = formData.schema.fields.findIndex(f => f.id === fieldId)
    if (index !== -1) {
        formData.schema.fields.splice(index, 1)
    }
}

function handleReorderFields(newFields) {
    formData.schema.fields = newFields
}

function handleDropField(type) {
    handleAddField(type)
}

function handleSubmit() {
    formRef.value?.validate((errors) => {
        if (!errors) {
            if (formData.schema.fields.length === 0) {
                message.error('Form harus memiliki minimal satu field')
                return
            }
            
            formData.put(`/admin/forms/${props.form.id}`, {
                onSuccess: () => {
                    message.success('Form berhasil diperbarui')
                },
                onError: () => {
                    message.error('Gagal memperbarui form')
                }
            })
        }
    })
}
</script>

<template>
    <Head title="Edit Form" />
    
    <div>
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Edit Form</h1>
        </div>
        
        <NGrid :cols="24" :x-gap="16">
            <NGridItem :span="6">
                <FieldPalette @add-field="handleAddField" />
            </NGridItem>
            
            <NGridItem :span="12">
                <FormCanvas
                    :fields="formData.schema.fields"
                    @edit-field="handleEditField"
                    @delete-field="handleDeleteField"
                    @reorder-fields="handleReorderFields"
                    @drop-field="handleDropField"
                />
            </NGridItem>
            
            <NGridItem :span="6">
                <NCard title="Form Settings" size="small">
                    <NForm
                        ref="formRef"
                        :model="formData"
                        :rules="rules"
                        label-placement="top"
                    >
                        <NFormItem label="Judul" path="title">
                            <NInput
                                v-model:value="formData.title"
                                placeholder="Judul form"
                            />
                        </NFormItem>
                        
                        <NFormItem label="Kategori" path="category_id">
                            <NSelect
                                v-model:value="formData.category_id"
                                :options="categoryOptions"
                                placeholder="Pilih kategori"
                            />
                        </NFormItem>
                        
                        <NFormItem label="Deskripsi">
                            <NInput
                                v-model:value="formData.description"
                                type="textarea"
                                placeholder="Deskripsi form"
                                :rows="3"
                            />
                        </NFormItem>
                        
                        <NFormItem label="Status">
                            <NSwitch v-model:value="formData.is_active">
                                <template #checked>Aktif</template>
                                <template #unchecked>Nonaktif</template>
                            </NSwitch>
                        </NFormItem>
                        
                        <NSpace vertical class="w-full">
                            <NButton
                                type="primary"
                                block
                                :loading="formData.processing"
                                @click="handleSubmit"
                            >
                                Simpan Perubahan
                            </NButton>
                            <Link href="/admin/forms">
                                <NButton block :disabled="formData.processing">
                                    Batal
                                </NButton>
                            </Link>
                        </NSpace>
                    </NForm>
                </NCard>
            </NGridItem>
        </NGrid>
        
        <FieldEditor
            v-model:show="showFieldEditor"
            :field="editingField"
            @save="handleSaveField"
            @delete="handleDeleteField"
        />
    </div>
</template>
