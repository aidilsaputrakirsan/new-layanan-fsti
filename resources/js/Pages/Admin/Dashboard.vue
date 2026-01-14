<script setup>
import { ref, computed, h } from 'vue'
import { Head } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import {
    NCard,
    NStatistic,
    NText,
    NDatePicker,
    NSpace,
    NDataTable,
    NTag,
    NSpin,
    NEmpty
} from 'naive-ui'
import { use } from 'echarts/core'
import { CanvasRenderer } from 'echarts/renderers'
import { LineChart } from 'echarts/charts'
import {
    TitleComponent,
    TooltipComponent,
    LegendComponent,
    GridComponent
} from 'echarts/components'
import VChart from 'vue-echarts'

// Register ECharts components
use([
    CanvasRenderer,
    LineChart,
    TitleComponent,
    TooltipComponent,
    LegendComponent,
    GridComponent
])

defineOptions({
    layout: AdminLayout
})

const props = defineProps({
    statistics: {
        type: Object,
        default: () => ({
            total: 0,
            pending: 0,
            in_review: 0,
            needs_revision: 0,
            approved: 0,
            rejected: 0,
            completed: 0,
            active_forms: 0,
            active_categories: 0
        })
    },
    chartData: {
        type: Object,
        default: () => ({
            labels: [],
            datasets: []
        })
    },
    categoryBreakdown: {
        type: Array,
        default: () => []
    },
    formBreakdown: {
        type: Array,
        default: () => []
    },
    averageProcessingTime: {
        type: Object,
        default: () => ({
            average_hours: 0,
            average_days: 0,
            formatted: '-',
            total_completed: 0
        })
    }
})

const loading = ref(false)
const dateRange = ref(null)

// Line chart options for trends
const trendChartOption = computed(() => ({
    tooltip: {
        trigger: 'axis'
    },
    legend: {
        data: ['Total Pengajuan', 'Pending', 'Selesai'],
        bottom: 0
    },
    grid: {
        left: '3%',
        right: '4%',
        bottom: '15%',
        containLabel: true
    },
    xAxis: {
        type: 'category',
        boundaryGap: false,
        data: props.chartData.labels || []
    },
    yAxis: {
        type: 'value'
    },
    series: [
        {
            name: 'Total Pengajuan',
            type: 'line',
            smooth: true,
            data: props.chartData.datasets?.[0]?.data || [],
            lineStyle: { color: '#2080f0' },
            itemStyle: { color: '#2080f0' },
            areaStyle: { color: 'rgba(32, 128, 240, 0.1)' }
        },
        {
            name: 'Pending',
            type: 'line',
            smooth: true,
            data: props.chartData.datasets?.[1]?.data || [],
            lineStyle: { color: '#f0a020' },
            itemStyle: { color: '#f0a020' },
            areaStyle: { color: 'rgba(240, 160, 32, 0.1)' }
        },
        {
            name: 'Selesai',
            type: 'line',
            smooth: true,
            data: props.chartData.datasets?.[2]?.data || [],
            lineStyle: { color: '#18a058' },
            itemStyle: { color: '#18a058' },
            areaStyle: { color: 'rgba(24, 160, 88, 0.1)' }
        }
    ]
}))

// Table columns for category breakdown
const categoryColumns = [
    { title: 'Kategori', key: 'name' },
    { 
        title: 'Tipe', 
        key: 'type',
        render: (row) => h(NTag, { size: 'small', type: row.type === 'mahasiswa' ? 'info' : 'success' }, () => row.type)
    },
    { title: 'Form', key: 'total_forms', align: 'center' },
    { title: 'Pengajuan', key: 'total_submissions', align: 'center' }
]

// Table columns for form breakdown
const formColumns = [
    { title: 'Form', key: 'title', ellipsis: { tooltip: true } },
    { title: 'Kategori', key: 'category' },
    { title: 'Total', key: 'total_submissions', align: 'center' },
    { title: 'Pending', key: 'pending_submissions', align: 'center' },
    { title: 'Selesai', key: 'completed_submissions', align: 'center' }
]
</script>

<template>
    <Head title="Dashboard" />
    
    <div>
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Dashboard</h1>
            <NSpace>
                <NDatePicker
                    v-model:value="dateRange"
                    type="daterange"
                    clearable
                    :disabled="loading"
                    placeholder="Pilih rentang tanggal"
                />
            </NSpace>
        </div>

        <NSpin :show="loading">
            <!-- Summary Statistics - Row 1 -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <NCard>
                    <NStatistic label="Total Pengajuan" :value="statistics.total">
                        <template #suffix>
                            <NText depth="3" class="text-sm">pengajuan</NText>
                        </template>
                    </NStatistic>
                </NCard>
                <NCard>
                    <NStatistic label="Pending" :value="statistics.pending">
                        <template #prefix>
                            <span class="text-yellow-500">●</span>
                        </template>
                    </NStatistic>
                </NCard>
                <NCard>
                    <NStatistic label="Selesai" :value="statistics.completed">
                        <template #prefix>
                            <span class="text-green-500">●</span>
                        </template>
                    </NStatistic>
                </NCard>
                <NCard>
                    <NStatistic label="Form Aktif" :value="statistics.active_forms" />
                </NCard>
            </div>

            <!-- Summary Statistics - Row 2 -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-4">
                <NCard>
                    <NStatistic label="Sedang Ditinjau" :value="statistics.in_review">
                        <template #prefix>
                            <span class="text-blue-500">●</span>
                        </template>
                    </NStatistic>
                </NCard>
                <NCard>
                    <NStatistic label="Perlu Revisi" :value="statistics.needs_revision">
                        <template #prefix>
                            <span class="text-orange-500">●</span>
                        </template>
                    </NStatistic>
                </NCard>
                <NCard>
                    <NStatistic label="Rata-rata Waktu Proses" :value="averageProcessingTime.formatted">
                        <template #suffix>
                            <NText depth="3" class="text-xs">({{ averageProcessingTime.total_completed }} selesai)</NText>
                        </template>
                    </NStatistic>
                </NCard>
                <NCard>
                    <NStatistic label="Kategori Aktif" :value="statistics.active_categories" />
                </NCard>
            </div>

            <!-- Trend Chart -->
            <NCard class="mt-6" title="Tren Pengajuan (30 Hari Terakhir)">
                <div v-if="chartData.labels && chartData.labels.length > 0" style="height: 350px;">
                    <VChart :option="trendChartOption" autoresize />
                </div>
                <NEmpty v-else description="Belum ada data pengajuan" />
            </NCard>

            <!-- Breakdown Tables -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mt-6">
                <!-- Category Breakdown -->
                <NCard title="Breakdown per Kategori">
                    <NDataTable
                        :columns="categoryColumns"
                        :data="categoryBreakdown"
                        :bordered="false"
                        size="small"
                        :pagination="false"
                    />
                </NCard>

                <!-- Form Breakdown -->
                <NCard title="Top 10 Form (Pengajuan Terbanyak)">
                    <NDataTable
                        :columns="formColumns"
                        :data="formBreakdown"
                        :bordered="false"
                        size="small"
                        :pagination="false"
                    />
                </NCard>
            </div>
        </NSpin>
    </div>
</template>
