<script setup>
import { ref, computed } from 'vue'
import {
    NForm,
    NFormItem,
    NButton,
    NSpace,
    useMessage
} from 'naive-ui'
import FieldRenderer from './FieldRenderer.vue'

const props = defineProps({
    schema: {
        type: Object,
        required: true
    },
    loading: {
        type: Boolean,
        default: false
    }
})

const emit = defineEmits(['submit'])

const message = useMessage()
const formRef = ref(null)
const formData = ref({})
const fileData = ref({})

// Initialize form data
const fields = computed(() => props.schema?.fields || [])

// Initialize values
fields.value.forEach(field => {
    if (field.type === 'checkbox') {
        formData.value[field.id] = false
    } else if (field.type === 'file') {
        fileData.value[field.id] = null
    } else {
        formData.value[field.id] = ''
    }
})

// Build validation rules
const rules = computed(() => {
    const r = {}
    fields.value.forEach(field => {
        if (field.required && field.type !== 'file') {
            r[field.id] = [
                {
                    required: true,
                    message: `${field.label} wajib diisi`,
                    trigger: field.type === 'select' || field.type === 'radio' ? 'change' : 'blur'
                }
            ]
            
            if (field.type === 'email') {
                r[field.id].push({
                    type: 'email',
                    message: 'Format email tidak valid',
                    trigger: 'blur'
                })
            }
        }
    })
    return r
})

function handleFieldUpdate(fieldId, value) {
    formData.value[fieldId] = value
}

function handleFileUpdate(fieldId, file) {
    fileData.value[fieldId] = file
}

function handleSubmit() {
    formRef.value?.validate((errors) => {
        if (!errors) {
            // Check required files
            const missingFiles = fields.value
                .filter(f => f.type === 'file' && f.required && !fileData.value[f.id])
                .map(f => f.label)
            
            if (missingFiles.length > 0) {
                message.error(`File wajib diupload: ${missingFiles.join(', ')}`)
                return
            }
            
            emit('submit', {
                data: formData.value,
                files: fileData.value
            })
        }
    })
}
</script>

<template>
    <NForm
        ref="formRef"
        :model="formData"
        :rules="rules"
        label-placement="top"
    >
        <FieldRenderer
            v-for="field in fields"
            :key="field.id"
            :field="field"
            :value="formData[field.id]"
            :file="fileData[field.id]"
            @update:value="handleFieldUpdate(field.id, $event)"
            @update:file="handleFileUpdate(field.id, $event)"
        />
        
        <NFormItem>
            <NButton
                type="primary"
                size="large"
                block
                :loading="loading"
                @click="handleSubmit"
            >
                {{ schema?.settings?.submitButtonText || 'Kirim' }}
            </NButton>
        </NFormItem>
    </NForm>
</template>
