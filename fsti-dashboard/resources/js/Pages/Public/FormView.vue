<script setup>
import { ref } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import PublicLayout from '@/Layouts/PublicLayout.vue'
import FormRenderer from '@/Components/FormRenderer/FormRenderer.vue'
import {
    NCard,
    NText,
    NFormItem,
    NInput,
    NDivider,
    useMessage
} from 'naive-ui'

defineOptions({
    layout: PublicLayout
})

const props = defineProps({
    form: Object
})

const message = useMessage()
const email = ref('')
const loading = ref(false)

function handleSubmit({ data, files }) {
    if (!email.value) {
        message.error('Email wajib diisi')
        return
    }
    
    loading.value = true
    
    const formData = new FormData()
    formData.append('email', email.value)
    
    // Add form data
    Object.keys(data).forEach(key => {
        if (data[key] !== null && data[key] !== undefined && data[key] !== '') {
            formData.append(key, data[key])
        }
    })
    
    // Add files
    Object.keys(files).forEach(key => {
        if (files[key]) {
            formData.append(key, files[key])
        }
    })
    
    router.post(`/form/${props.form.slug}/submit`, formData, {
        forceFormData: true,
        onFinish: () => {
            loading.value = false
        },
        onError: (errors) => {
            const firstError = Object.values(errors)[0]
            if (Array.isArray(firstError)) {
                message.error(firstError[0])
            } else {
                message.error(firstError || 'Terjadi kesalahan')
            }
        }
    })
}
</script>

<template>
    <Head :title="form.title" />
    
    <div>
        <NCard>
            <template #header>
                <div>
                    <h1 class="text-2xl font-bold">{{ form.title }}</h1>
                    <NText v-if="form.category" depth="3">
                        {{ form.category.name }}
                    </NText>
                </div>
            </template>
            
            <NText v-if="form.description" class="block mb-4">
                {{ form.description }}
            </NText>
            
            <NDivider />
            
            <NFormItem label="Email" required>
                <NInput
                    v-model:value="email"
                    placeholder="Masukkan email untuk notifikasi"
                    type="text"
                />
            </NFormItem>
            
            <FormRenderer
                :schema="form.schema"
                :loading="loading"
                @submit="handleSubmit"
            />
        </NCard>
    </div>
</template>
