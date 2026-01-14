<script setup>
import { ref, computed } from 'vue'
import {
    NCard,
    NEmpty,
    NIcon,
    NText,
    NSpace,
    NButton,
    NTag
} from 'naive-ui'
import {
    ReorderTwoOutline,
    CreateOutline,
    TrashOutline,
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

const props = defineProps({
    fields: {
        type: Array,
        default: () => []
    }
})

const emit = defineEmits(['edit-field', 'delete-field', 'reorder-fields', 'drop-field'])

const draggedIndex = ref(null)

const fieldIcons = {
    text: TextOutline,
    textarea: DocumentTextOutline,
    email: MailOutline,
    number: CalculatorOutline,
    date: CalendarOutline,
    select: ChevronDownOutline,
    radio: RadioButtonOnOutline,
    checkbox: CheckboxOutline,
    file: CloudUploadOutline,
}

const fieldTypeLabels = {
    text: 'Text',
    textarea: 'Textarea',
    email: 'Email',
    number: 'Number',
    date: 'Date',
    select: 'Dropdown',
    radio: 'Radio',
    checkbox: 'Checkbox',
    file: 'File',
}

function handleDragStart(index) {
    draggedIndex.value = index
}

function handleDragOver(e, index) {
    e.preventDefault()
    if (draggedIndex.value !== null && draggedIndex.value !== index) {
        const newFields = [...props.fields]
        const [removed] = newFields.splice(draggedIndex.value, 1)
        newFields.splice(index, 0, removed)
        emit('reorder-fields', newFields)
        draggedIndex.value = index
    }
}

function handleDragEnd() {
    draggedIndex.value = null
}

function handleDrop(e) {
    e.preventDefault()
    const fieldType = e.dataTransfer.getData('fieldType')
    if (fieldType) {
        emit('drop-field', fieldType)
    }
}

function handleEdit(field) {
    emit('edit-field', field)
}

function handleDelete(fieldId) {
    emit('delete-field', fieldId)
}
</script>

<template>
    <NCard title="Form Preview" size="small">
        <div
            class="min-h-[400px] border-2 border-dashed border-gray-300 rounded-lg p-4"
            @dragover.prevent
            @drop="handleDrop"
        >
            <NEmpty v-if="fields.length === 0" description="Drag fields here or click to add">
                <template #icon>
                    <NIcon size="48" color="#ccc">
                        <DocumentTextOutline />
                    </NIcon>
                </template>
            </NEmpty>
            
            <div v-else class="space-y-3">
                <div
                    v-for="(field, index) in fields"
                    :key="field.id"
                    class="field-card p-4 bg-white border rounded-lg shadow-sm cursor-move hover:shadow-md transition-shadow"
                    draggable="true"
                    @dragstart="handleDragStart(index)"
                    @dragover="(e) => handleDragOver(e, index)"
                    @dragend="handleDragEnd"
                >
                    <div class="flex items-center justify-between">
                        <NSpace align="center">
                            <NIcon size="16" color="#999">
                                <ReorderTwoOutline />
                            </NIcon>
                            <NIcon size="18" :component="fieldIcons[field.type]" />
                            <div>
                                <NText strong>{{ field.label || 'Untitled Field' }}</NText>
                                <div class="flex items-center gap-2 mt-1">
                                    <NTag size="tiny" :bordered="false">
                                        {{ fieldTypeLabels[field.type] }}
                                    </NTag>
                                    <NTag v-if="field.required" size="tiny" type="error" :bordered="false">
                                        Required
                                    </NTag>
                                </div>
                            </div>
                        </NSpace>
                        
                        <NSpace>
                            <NButton size="tiny" quaternary @click="handleEdit(field)">
                                <template #icon>
                                    <NIcon><CreateOutline /></NIcon>
                                </template>
                            </NButton>
                            <NButton size="tiny" quaternary type="error" @click="handleDelete(field.id)">
                                <template #icon>
                                    <NIcon><TrashOutline /></NIcon>
                                </template>
                            </NButton>
                        </NSpace>
                    </div>
                </div>
            </div>
        </div>
    </NCard>
</template>

<style scoped>
.field-card:hover {
    border-color: #18a058;
}
</style>
