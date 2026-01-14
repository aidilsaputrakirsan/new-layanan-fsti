<script setup>
import { NCard, NIcon, NText, NSpace } from 'naive-ui'
import {
    TextOutline,
    DocumentTextOutline,
    MailOutline,
    CalculatorOutline,
    CalendarOutline,
    ChevronDownOutline,
    RadioButtonOnOutline,
    CheckboxOutline,
    CloudUploadOutline
} from '@vicons/ionicons5'

const emit = defineEmits(['add-field'])

const fieldTypes = [
    { type: 'text', label: 'Text Input', icon: TextOutline },
    { type: 'textarea', label: 'Text Area', icon: DocumentTextOutline },
    { type: 'email', label: 'Email', icon: MailOutline },
    { type: 'number', label: 'Number', icon: CalculatorOutline },
    { type: 'date', label: 'Date', icon: CalendarOutline },
    { type: 'select', label: 'Dropdown', icon: ChevronDownOutline },
    { type: 'radio', label: 'Radio Button', icon: RadioButtonOnOutline },
    { type: 'checkbox', label: 'Checkbox', icon: CheckboxOutline },
    { type: 'file', label: 'File Upload', icon: CloudUploadOutline },
]

function handleAddField(type) {
    emit('add-field', type)
}
</script>

<template>
    <NCard title="Field Types" size="small">
        <NSpace vertical>
            <div
                v-for="field in fieldTypes"
                :key="field.type"
                class="field-item p-3 border rounded cursor-pointer hover:bg-gray-50 transition-colors"
                draggable="true"
                @click="handleAddField(field.type)"
                @dragstart="(e) => e.dataTransfer.setData('fieldType', field.type)"
            >
                <NSpace align="center">
                    <NIcon size="20" :component="field.icon" />
                    <NText>{{ field.label }}</NText>
                </NSpace>
            </div>
        </NSpace>
    </NCard>
</template>

<style scoped>
.field-item:hover {
    border-color: #18a058;
}
</style>
