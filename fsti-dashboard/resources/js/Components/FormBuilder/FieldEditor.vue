<script setup>
import { ref, computed, watch } from 'vue'
import {
    NDrawer,
    NDrawerContent,
    NForm,
    NFormItem,
    NInput,
    NSwitch,
    NInputNumber,
    NButton,
    NSpace,
    NDynamicInput,
    NSelect,
    NDivider,
    NText
} from 'naive-ui'

const props = defineProps({
    show: Boolean,
    field: Object
})

const emit = defineEmits(['update:show', 'save', 'delete'])

const localField = ref(null)

watch(() => props.field, (newField) => {
    if (newField) {
        localField.value = JSON.parse(JSON.stringify(newField))
        // Ensure options array exists for select/radio
        if (['select', 'radio'].includes(localField.value.type) && !localField.value.options) {
            localField.value.options = [{ label: '', value: '' }]
        }
        // Ensure validation object exists
        if (!localField.value.validation) {
            localField.value.validation = {}
        }
    }
}, { immediate: true })

const hasOptions = computed(() => {
    return localField.value && ['select', 'radio'].includes(localField.value.type)
})

const isFileField = computed(() => {
    return localField.value && localField.value.type === 'file'
})

const fileTypeOptions = [
    { label: 'PDF', value: 'pdf' },
    { label: 'Images (JPG, PNG)', value: 'image' },
    { label: 'Documents (DOC, DOCX)', value: 'doc' },
    { label: 'Spreadsheets (XLS, XLSX)', value: 'excel' },
]

function handleClose() {
    emit('update:show', false)
}

function handleSave() {
    emit('save', localField.value)
    handleClose()
}

function handleDelete() {
    emit('delete', localField.value.id)
    handleClose()
}

function addOption() {
    if (!localField.value.options) {
        localField.value.options = []
    }
    localField.value.options.push({ label: '', value: '' })
}

function removeOption(index) {
    localField.value.options.splice(index, 1)
}
</script>

<template>
    <NDrawer :show="show" :width="400" @update:show="handleClose">
        <NDrawerContent title="Edit Field" closable>
            <NForm v-if="localField" label-placement="top">
                <NFormItem label="Label">
                    <NInput v-model:value="localField.label" placeholder="Field label" />
                </NFormItem>
                
                <NFormItem label="Placeholder" v-if="!['checkbox', 'radio', 'file', 'date'].includes(localField.type)">
                    <NInput v-model:value="localField.placeholder" placeholder="Placeholder text" />
                </NFormItem>
                
                <NFormItem label="Required">
                    <NSwitch v-model:value="localField.required" />
                </NFormItem>
                
                <NDivider v-if="hasOptions">Options</NDivider>
                
                <div v-if="hasOptions" class="mb-4">
                    <div v-for="(option, index) in localField.options" :key="index" class="flex gap-2 mb-2">
                        <NInput
                            v-model:value="option.label"
                            placeholder="Label"
                            size="small"
                            class="flex-1"
                        />
                        <NInput
                            v-model:value="option.value"
                            placeholder="Value"
                            size="small"
                            class="flex-1"
                        />
                        <NButton
                            size="small"
                            type="error"
                            quaternary
                            @click="removeOption(index)"
                            :disabled="localField.options.length <= 1"
                        >
                            ×
                        </NButton>
                    </div>
                    <NButton size="small" dashed block @click="addOption">
                        + Add Option
                    </NButton>
                </div>
                
                <NDivider v-if="isFileField">File Settings</NDivider>
                
                <template v-if="isFileField">
                    <NFormItem label="Allowed File Types">
                        <NSelect
                            v-model:value="localField.validation.allowedTypes"
                            :options="fileTypeOptions"
                            multiple
                            placeholder="Select allowed types"
                        />
                    </NFormItem>
                    
                    <NFormItem label="Max File Size (KB)">
                        <NInputNumber
                            v-model:value="localField.validation.maxSize"
                            :min="1"
                            :max="10240"
                            placeholder="Max size in KB"
                            style="width: 100%"
                        />
                    </NFormItem>
                </template>
                
                <NDivider>Validation</NDivider>
                
                <template v-if="['text', 'textarea'].includes(localField.type)">
                    <NFormItem label="Min Length">
                        <NInputNumber
                            v-model:value="localField.validation.minLength"
                            :min="0"
                            style="width: 100%"
                        />
                    </NFormItem>
                    <NFormItem label="Max Length">
                        <NInputNumber
                            v-model:value="localField.validation.maxLength"
                            :min="0"
                            style="width: 100%"
                        />
                    </NFormItem>
                </template>
                
                <template v-if="localField.type === 'number'">
                    <NFormItem label="Min Value">
                        <NInputNumber
                            v-model:value="localField.validation.min"
                            style="width: 100%"
                        />
                    </NFormItem>
                    <NFormItem label="Max Value">
                        <NInputNumber
                            v-model:value="localField.validation.max"
                            style="width: 100%"
                        />
                    </NFormItem>
                </template>
            </NForm>
            
            <template #footer>
                <NSpace justify="space-between" class="w-full">
                    <NButton type="error" ghost @click="handleDelete">
                        Delete Field
                    </NButton>
                    <NSpace>
                        <NButton @click="handleClose">Cancel</NButton>
                        <NButton type="primary" @click="handleSave">Save</NButton>
                    </NSpace>
                </NSpace>
            </template>
        </NDrawerContent>
    </NDrawer>
</template>
