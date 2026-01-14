<script setup>
import { ref, computed } from 'vue'
import { useForm, usePage } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import {
    NCard,
    NTabs,
    NTabPane,
    NForm,
    NFormItem,
    NInput,
    NInputNumber,
    NButton,
    NSpace,
    NAlert,
    useMessage
} from 'naive-ui'

defineOptions({
    layout: AdminLayout
})

const props = defineProps({
    settings: {
        type: Object,
        required: true
    }
})

const message = useMessage()
const page = usePage()

const flash = computed(() => page.props.flash || {})

// Application settings form
const appForm = useForm({
    app_name: props.settings.application.app_name || '',
    app_description: props.settings.application.app_description || '',
    contact_email: props.settings.application.contact_email || '',
    contact_phone: props.settings.application.contact_phone || '',
    contact_address: props.settings.application.contact_address || ''
})

// Email settings form
const emailForm = useForm({
    mail_from_name: props.settings.email.mail_from_name || '',
    mail_from_address: props.settings.email.mail_from_address || ''
})

// File upload settings form
const fileForm = useForm({
    max_file_size: props.settings.file_upload.max_file_size || 5120,
    allowed_file_types: props.settings.file_upload.allowed_file_types || 'pdf,image,doc,excel'
})

const submitAppSettings = () => {
    appForm.post('/admin/settings/application', {
        preserveScroll: true,
        onSuccess: () => {
            message.success('Pengaturan aplikasi berhasil disimpan')
        },
        onError: () => {
            message.error('Gagal menyimpan pengaturan')
        }
    })
}

const submitEmailSettings = () => {
    emailForm.post('/admin/settings/email', {
        preserveScroll: true,
        onSuccess: () => {
            message.success('Pengaturan email berhasil disimpan')
        },
        onError: () => {
            message.error('Gagal menyimpan pengaturan')
        }
    })
}

const submitFileSettings = () => {
    fileForm.post('/admin/settings/file-upload', {
        preserveScroll: true,
        onSuccess: () => {
            message.success('Pengaturan upload file berhasil disimpan')
        },
        onError: () => {
            message.error('Gagal menyimpan pengaturan')
        }
    })
}
</script>

<template>
    <div class="p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Pengaturan Sistem</h1>
            <p class="text-gray-600">Kelola pengaturan aplikasi, email, dan upload file</p>
        </div>

        <NCard>
            <NTabs type="line" animated>
                <!-- Application Settings Tab -->
                <NTabPane name="application" tab="Aplikasi">
                    <NForm
                        :model="appForm"
                        label-placement="top"
                        class="max-w-2xl"
                    >
                        <NFormItem label="Nama Aplikasi" path="app_name">
                            <NInput
                                v-model:value="appForm.app_name"
                                placeholder="Masukkan nama aplikasi"
                            />
                        </NFormItem>

                        <NFormItem label="Deskripsi" path="app_description">
                            <NInput
                                v-model:value="appForm.app_description"
                                type="textarea"
                                placeholder="Deskripsi singkat aplikasi"
                                :rows="3"
                            />
                        </NFormItem>

                        <NFormItem label="Email Kontak" path="contact_email">
                            <NInput
                                v-model:value="appForm.contact_email"
                                placeholder="admin@example.com"
                            />
                        </NFormItem>

                        <NFormItem label="Telepon Kontak" path="contact_phone">
                            <NInput
                                v-model:value="appForm.contact_phone"
                                placeholder="+62 xxx xxxx xxxx"
                            />
                        </NFormItem>

                        <NFormItem label="Alamat" path="contact_address">
                            <NInput
                                v-model:value="appForm.contact_address"
                                type="textarea"
                                placeholder="Alamat lengkap"
                                :rows="2"
                            />
                        </NFormItem>

                        <NFormItem>
                            <NButton
                                type="primary"
                                :loading="appForm.processing"
                                @click="submitAppSettings"
                            >
                                Simpan Pengaturan
                            </NButton>
                        </NFormItem>
                    </NForm>
                </NTabPane>

                <!-- Email Settings Tab -->
                <NTabPane name="email" tab="Email">
                    <NAlert type="info" class="mb-4">
                        Pengaturan SMTP dikonfigurasi melalui file .env. Di sini Anda dapat mengatur nama dan alamat pengirim email.
                    </NAlert>

                    <NForm
                        :model="emailForm"
                        label-placement="top"
                        class="max-w-2xl"
                    >
                        <NFormItem label="Nama Pengirim" path="mail_from_name">
                            <NInput
                                v-model:value="emailForm.mail_from_name"
                                placeholder="FSTI Admin"
                            />
                        </NFormItem>

                        <NFormItem label="Email Pengirim" path="mail_from_address">
                            <NInput
                                v-model:value="emailForm.mail_from_address"
                                placeholder="noreply@example.com"
                            />
                        </NFormItem>

                        <NFormItem>
                            <NButton
                                type="primary"
                                :loading="emailForm.processing"
                                @click="submitEmailSettings"
                            >
                                Simpan Pengaturan
                            </NButton>
                        </NFormItem>
                    </NForm>
                </NTabPane>

                <!-- File Upload Settings Tab -->
                <NTabPane name="file-upload" tab="Upload File">
                    <NForm
                        :model="fileForm"
                        label-placement="top"
                        class="max-w-2xl"
                    >
                        <NFormItem label="Ukuran File Maksimal (KB)" path="max_file_size">
                            <NInputNumber
                                v-model:value="fileForm.max_file_size"
                                :min="100"
                                :max="51200"
                                :step="512"
                                style="width: 100%"
                            />
                            <template #feedback>
                                <span class="text-gray-500 text-sm">
                                    {{ (fileForm.max_file_size / 1024).toFixed(1) }} MB
                                </span>
                            </template>
                        </NFormItem>

                        <NFormItem label="Tipe File yang Diizinkan" path="allowed_file_types">
                            <NInput
                                v-model:value="fileForm.allowed_file_types"
                                placeholder="pdf,image,doc,excel"
                            />
                            <template #feedback>
                                <span class="text-gray-500 text-sm">
                                    Pisahkan dengan koma. Tipe: pdf, image, doc, excel
                                </span>
                            </template>
                        </NFormItem>

                        <NFormItem>
                            <NButton
                                type="primary"
                                :loading="fileForm.processing"
                                @click="submitFileSettings"
                            >
                                Simpan Pengaturan
                            </NButton>
                        </NFormItem>
                    </NForm>
                </NTabPane>
            </NTabs>
        </NCard>
    </div>
</template>
