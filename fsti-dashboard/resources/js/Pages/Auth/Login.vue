<script setup>
import { ref, computed } from 'vue'
import { useForm, Head } from '@inertiajs/vue3'
import {
    NCard,
    NForm,
    NFormItem,
    NInput,
    NButton,
    NSpace,
    NText,
    NAlert,
    NIcon
} from 'naive-ui'
import {
    MailOutline,
    LockClosedOutline,
    LogInOutline
} from '@vicons/ionicons5'

const form = useForm({
    email: '',
    password: '',
    remember: false
})

const formRef = ref(null)

const rules = {
    email: [
        { required: true, message: 'Email wajib diisi', trigger: 'blur' },
        { type: 'email', message: 'Format email tidak valid', trigger: 'blur' }
    ],
    password: [
        { required: true, message: 'Password wajib diisi', trigger: 'blur' },
        { min: 6, message: 'Password minimal 6 karakter', trigger: 'blur' }
    ]
}

const hasErrors = computed(() => {
    return Object.keys(form.errors).length > 0
})

const errorMessage = computed(() => {
    if (form.errors.email) return form.errors.email
    if (form.errors.password) return form.errors.password
    return null
})

function handleSubmit() {
    formRef.value?.validate((errors) => {
        if (!errors) {
            form.post('/login', {
                onFinish: () => {
                    form.password = ''
                }
            })
        }
    })
}
</script>

<template>
    <Head title="Login" />
    
    <div class="min-h-screen flex items-center justify-center bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900">FSTI Dashboard</h1>
                <p class="mt-2 text-gray-600">Silakan login untuk melanjutkan</p>
            </div>
            
            <NCard>
                <NAlert
                    v-if="hasErrors"
                    type="error"
                    :title="errorMessage"
                    class="mb-4"
                    closable
                />
                
                <NForm
                    ref="formRef"
                    :model="form"
                    :rules="rules"
                    @submit.prevent="handleSubmit"
                >
                    <NFormItem label="Email" path="email">
                        <NInput
                            v-model:value="form.email"
                            placeholder="Masukkan email"
                            type="text"
                            :disabled="form.processing"
                        >
                            <template #prefix>
                                <NIcon :component="MailOutline" />
                            </template>
                        </NInput>
                    </NFormItem>
                    
                    <NFormItem label="Password" path="password">
                        <NInput
                            v-model:value="form.password"
                            placeholder="Masukkan password"
                            type="password"
                            show-password-on="click"
                            :disabled="form.processing"
                        >
                            <template #prefix>
                                <NIcon :component="LockClosedOutline" />
                            </template>
                        </NInput>
                    </NFormItem>
                    
                    <NSpace vertical class="w-full">
                        <NButton
                            type="primary"
                            block
                            :loading="form.processing"
                            :disabled="form.processing"
                            attr-type="submit"
                        >
                            <template #icon>
                                <NIcon :component="LogInOutline" />
                            </template>
                            {{ form.processing ? 'Memproses...' : 'Login' }}
                        </NButton>
                    </NSpace>
                </NForm>
            </NCard>
            
            <p class="mt-4 text-center text-sm text-gray-500">
                Dashboard Layanan Administrasi FSTI
            </p>
        </div>
    </div>
</template>
