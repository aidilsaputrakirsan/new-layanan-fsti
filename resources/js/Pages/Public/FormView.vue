<script setup>
import { ref } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import FormRenderer from '@/Components/FormRenderer/FormRenderer.vue'
import { NInput, NIcon, NButton, useMessage } from 'naive-ui'
import { HomeOutline, MailOutline, DocumentTextOutline } from '@vicons/ionicons5'

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
    
    Object.keys(data).forEach(key => {
        if (data[key] !== null && data[key] !== undefined && data[key] !== '') {
            formData.append(key, data[key])
        }
    })
    
    Object.keys(files).forEach(key => {
        if (files[key]) {
            formData.append(key, files[key])
        }
    })
    
    router.post(`/form/${props.form.id}/submit`, formData, {
        forceFormData: true,
        onFinish: () => loading.value = false,
        onError: (errors) => {
            const firstError = Object.values(errors)[0]
            message.error(Array.isArray(firstError) ? firstError[0] : firstError || 'Terjadi kesalahan')
        }
    })
}
</script>

<template>
    <Head :title="form.title" />
    
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <header class="bg-white shadow-sm sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <Link href="/" class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-lg">F</span>
                        </div>
                        <div>
                            <h1 class="text-lg font-bold text-blue-600">FSTI ITK</h1>
                            <p class="text-xs text-gray-500">Layanan Administrasi</p>
                        </div>
                    </Link>
                    <nav class="hidden md:flex items-center gap-6">
                        <Link href="/" class="text-gray-600 hover:text-blue-600 transition">Beranda</Link>
                        <Link href="/tracking" class="text-gray-600 hover:text-blue-600 transition">Tracking</Link>
                    </nav>
                </div>
            </div>
        </header>

        <!-- Hero Section -->
        <section class="bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 text-white py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-4 mb-3">
                    <div class="w-12 h-12 bg-blue-500/30 rounded-xl flex items-center justify-center">
                        <NIcon size="24"><DocumentTextOutline /></NIcon>
                    </div>
                    <div>
                        <p v-if="form.category" class="text-blue-200 text-sm">{{ form.category.name }}</p>
                        <h1 class="text-2xl md:text-3xl font-bold">{{ form.title }}</h1>
                    </div>
                </div>
                <p v-if="form.description" class="text-white/80 max-w-3xl">
                    {{ form.description }}
                </p>
            </div>
        </section>

        <!-- Form Section -->
        <section class="py-8">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white rounded-2xl shadow-lg p-6 md:p-8 border border-gray-100">
                    <!-- Email Field -->
                    <div class="mb-6 pb-6 border-b border-gray-100">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <NInput
                            v-model:value="email"
                            placeholder="Masukkan email untuk notifikasi status"
                            size="large"
                        >
                            <template #prefix>
                                <NIcon class="text-gray-400"><MailOutline /></NIcon>
                            </template>
                        </NInput>
                        <p class="text-xs text-gray-500 mt-2">
                            Notifikasi status pengajuan akan dikirim ke email ini
                        </p>
                    </div>
                    
                    <!-- Dynamic Form -->
                    <FormRenderer
                        :schema="form.schema"
                        :loading="loading"
                        @submit="handleSubmit"
                    />
                </div>
            </div>
        </section>

        <!-- Back Button -->
        <section class="pb-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <Link href="/">
                    <NButton quaternary size="large">
                        <template #icon>
                            <NIcon><HomeOutline /></NIcon>
                        </template>
                        Kembali ke Beranda
                    </NButton>
                </Link>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gray-900 text-gray-400 py-8 mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm">
                © Fakultas Sains dan Teknologi Informasi - ITK. Hak Cipta Dilindungi.
            </div>
        </footer>
    </div>
</template>
