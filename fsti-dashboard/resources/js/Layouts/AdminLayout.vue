<script setup>
import { ref, computed, h } from 'vue'
import { Link, usePage, router } from '@inertiajs/vue3'
import {
    NLayout,
    NLayoutSider,
    NLayoutHeader,
    NLayoutContent,
    NMenu,
    NIcon,
    NButton,
    NDropdown,
    NAvatar,
    NSpace,
    NText
} from 'naive-ui'
import {
    HomeOutline,
    FolderOutline,
    DocumentTextOutline,
    PaperPlaneOutline,
    SettingsOutline,
    LogOutOutline,
    MenuOutline,
    PersonOutline
} from '@vicons/ionicons5'

const collapsed = ref(false)
const page = usePage()

const user = computed(() => page.props.auth?.user)

const menuOptions = [
    {
        label: () => h(Link, { href: '/admin/dashboard' }, { default: () => 'Dashboard' }),
        key: 'dashboard',
        icon: () => h(NIcon, null, { default: () => h(HomeOutline) })
    },
    {
        label: () => h(Link, { href: '/admin/categories' }, { default: () => 'Kategori' }),
        key: 'categories',
        icon: () => h(NIcon, null, { default: () => h(FolderOutline) })
    },
    {
        label: () => h(Link, { href: '/admin/forms' }, { default: () => 'Form' }),
        key: 'forms',
        icon: () => h(NIcon, null, { default: () => h(DocumentTextOutline) })
    },
    {
        label: () => h(Link, { href: '/admin/submissions' }, { default: () => 'Pengajuan' }),
        key: 'submissions',
        icon: () => h(NIcon, null, { default: () => h(PaperPlaneOutline) })
    },
    {
        label: () => h(Link, { href: '/admin/settings' }, { default: () => 'Pengaturan' }),
        key: 'settings',
        icon: () => h(NIcon, null, { default: () => h(SettingsOutline) })
    }
]

const userMenuOptions = [
    {
        label: 'Logout',
        key: 'logout',
        icon: () => h(NIcon, null, { default: () => h(LogOutOutline) })
    }
]

function handleUserMenuSelect(key) {
    if (key === 'logout') {
        router.post('/logout')
    }
}
</script>

<template>
    <NLayout has-sider class="min-h-screen">
        <NLayoutSider
            bordered
            collapse-mode="width"
            :collapsed-width="64"
            :width="240"
            :collapsed="collapsed"
            show-trigger
            @collapse="collapsed = true"
            @expand="collapsed = false"
            class="bg-white"
        >
            <div class="h-16 flex items-center justify-center border-b">
                <NText strong v-if="!collapsed" class="text-lg">FSTI Dashboard</NText>
                <NText strong v-else>FD</NText>
            </div>
            <NMenu
                :collapsed="collapsed"
                :collapsed-width="64"
                :collapsed-icon-size="22"
                :options="menuOptions"
                class="mt-2"
            />
        </NLayoutSider>
        
        <NLayout>
            <NLayoutHeader bordered class="h-16 px-6 flex items-center justify-between bg-white">
                <NButton quaternary circle @click="collapsed = !collapsed">
                    <template #icon>
                        <NIcon><MenuOutline /></NIcon>
                    </template>
                </NButton>
                
                <NDropdown
                    :options="userMenuOptions"
                    @select="handleUserMenuSelect"
                >
                    <NSpace align="center" class="cursor-pointer">
                        <NAvatar round size="small">
                            <NIcon><PersonOutline /></NIcon>
                        </NAvatar>
                        <NText v-if="user">{{ user.name }}</NText>
                        <NText v-else>Admin</NText>
                    </NSpace>
                </NDropdown>
            </NLayoutHeader>
            
            <NLayoutContent class="p-6 bg-gray-50">
                <slot />
            </NLayoutContent>
        </NLayout>
    </NLayout>
</template>
