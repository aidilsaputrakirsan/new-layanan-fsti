<script setup>
import { computed } from 'vue'
import {
    NFormItem,
    NInput,
    NInputNumber,
    NSelect,
    NRadioGroup,
    NRadio,
    NCheckbox,
    NDatePicker,
    NUpload,
    NText
} from 'naive-ui'

const props = defineProps({
    field: {
        type: Object,
        required: true
    },
    value: {
        default: ''
    },
    file: {
        default: null
    }
})

const emit = defineEmits(['update:value', 'update:file'])

const selectOptions = computed(() => {
    if (!props.field.options) return []
    return props.field.options.map(opt => ({
        label: opt.label,
        value: opt.value
    }))
})

function handleValueUpdate(val) {
    emit('update:value', val)
}

function handleFileChange({ file }) {
    emit('update:file', file.file)
}

function handleFileRemove() {
    emit('update:file', null)
}
</script>

<template>
    <NFormItem :label="field.label" :path="field.id" :required="field.required">
        <!-- Text Input -->
        <NInput
            v-if="field.type === 'text'"
            :value="value"
            :placeholder="field.placeholder"
            @update:value="handleValueUpdate"
        />
        
        <!-- Textarea -->
        <NInput
            v-else-if="field.type === 'textarea'"
            :value="value"
            type="textarea"
            :placeholder="field.placeholder"
            :rows="4"
            @update:value="handleValueUpdate"
        />
        
        <!-- Email -->
        <NInput
            v-else-if="field.type === 'email'"
            :value="value"
            type="text"
            :placeholder="field.placeholder || 'email@example.com'"
            @update:value="handleValueUpdate"
        />
        
        <!-- Number -->
        <NInputNumber
            v-else-if="field.type === 'number'"
            :value="value"
            :placeholder="field.placeholder"
            :min="field.validation?.min"
            :max="field.validation?.max"
            style="width: 100%"
            @update:value="handleValueUpdate"
        />
        
        <!-- Date -->
        <NDatePicker
            v-else-if="field.type === 'date'"
            :value="value"
            type="date"
            style="width: 100%"
            @update:value="handleValueUpdate"
        />
        
        <!-- Select -->
        <NSelect
            v-else-if="field.type === 'select'"
            :value="value"
            :options="selectOptions"
            :placeholder="field.placeholder || 'Pilih...'"
            @update:value="handleValueUpdate"
        />
        
        <!-- Radio -->
        <NRadioGroup
            v-else-if="field.type === 'radio'"
            :value="value"
            @update:value="handleValueUpdate"
        >
            <NRadio
                v-for="option in field.options"
                :key="option.value"
                :value="option.value"
            >
                {{ option.label }}
            </NRadio>
        </NRadioGroup>
        
        <!-- Checkbox -->
        <NCheckbox
            v-else-if="field.type === 'checkbox'"
            :checked="value"
            @update:checked="handleValueUpdate"
        >
            {{ field.placeholder || 'Ya' }}
        </NCheckbox>
        
        <!-- File Upload -->
        <div v-else-if="field.type === 'file'" class="w-full">
            <NUpload
                :max="1"
                :default-upload="false"
                @change="handleFileChange"
                @remove="handleFileRemove"
            >
                <NButton>Pilih File</NButton>
            </NUpload>
            <NText v-if="field.validation?.allowedTypes" depth="3" class="text-xs mt-1 block">
                Tipe file: {{ field.validation.allowedTypes.join(', ') }}
                <span v-if="field.validation?.maxSize">
                    | Maks: {{ Math.round(field.validation.maxSize / 1024) }}MB
                </span>
            </NText>
        </div>
    </NFormItem>
</template>
