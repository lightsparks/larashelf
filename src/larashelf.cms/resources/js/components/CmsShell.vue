<script setup lang="ts">
import { ref, computed, onMounted, watch } from "vue";
import { useTheme, useDisplay } from "vuetify";

const props = withDefaults(defineProps<{
    title?: string
    showLogout?: boolean
    showNav?: boolean
}>(), {
    title: 'LaraShelf',
    showLogout: false,
    showNav: true,
});

const emit = defineEmits<{ (e: "logout"): void }>();

// Theme toggle (unchanged)
const theme = useTheme();
const isDark = computed(() => theme.global.current.value.dark);
function toggleDarkMode() {
    theme.change(isDark.value ? "light" : "dark");
    localStorage.setItem("ls_theme", isDark.value ? "dark" : "light");
}
onMounted(() => {
    const saved = localStorage.getItem("ls_theme");
    if (saved === "dark" || saved === "light") theme.change(saved);
});

// Drawer state (only if nav is shown)
const { mdAndUp } = useDisplay();
const drawer = ref(true);
const rail = ref(false);

onMounted(() => {
    if (!props.showNav) return;
    drawer.value = (localStorage.getItem("ls_drawer_open") ?? (mdAndUp.value ? "1" : "0")) === "1";
    rail.value   = localStorage.getItem("ls_drawer_rail") === "1";
});
watch(drawer, v => props.showNav && localStorage.setItem("ls_drawer_open", v ? "1" : "0"));
watch(rail,   v => props.showNav && localStorage.setItem("ls_drawer_rail", v ? "1" : "0"));

function toggleDrawer() {
    if (!props.showNav) return;
    if (mdAndUp.value) rail.value = !rail.value;
    else drawer.value = !drawer.value;
}

// Nav items
type NavItem = { text: string; icon: string; to: string; exact?: boolean };
const items: NavItem[] = [
    { text: "Dashboard",  icon: "mdi-view-dashboard", to: "/dashboard", exact: true },
    { text: "Users",      icon: "mdi-account-multiple", to: "/users" },
    { text: "Categories", icon: "mdi-shape-outline", to: "/categories" },
    { text: "Items",      icon: "mdi-package-variant", to: "/items" },
];
</script>

<template>
    <v-app>
        <v-app-bar :elevation="0" color="primary" :dark="isDark">
            <v-btn v-if="showNav" icon="mdi-menu" variant="text" @click="toggleDrawer" />
            <v-toolbar-title class="text-h6">{{ title ?? "LaraShelf" }}</v-toolbar-title>
            <v-spacer />
            <slot name="actions" />
            <v-btn
                icon="mdi-theme-light-dark"
                variant="text"
                :title="isDark ? 'Light mode' : 'Dark mode'"
                :aria-pressed="isDark"
                @click="toggleDarkMode"
            />
            <v-btn
                v-if="showLogout"
                class="ml-1"
                variant="text"
                icon="mdi-logout"
                @click="$emit('logout')"
                title="Logout"
            />
        </v-app-bar>

        <v-navigation-drawer
            v-if="showNav"
            v-model="drawer"
            :rail="mdAndUp ? rail : false"
            :permanent="mdAndUp"
            :temporary="!mdAndUp"
            color="surface"
            elevation="2"
            width="280"
            rail-width="72"
        >
            <v-list nav density="comfortable">
                <v-list-subheader class="text-medium-emphasis">Management</v-list-subheader>
                <v-list-item
                    v-for="item in items"
                    :key="item.to"
                    :to="item.to"
                    :exact="item.exact ?? false"
                    link
                    rounded="lg"
                >
                    <template #prepend><v-icon :icon="item.icon" /></template>
                    <v-list-item-title>{{ item.text }}</v-list-item-title>
                </v-list-item>
            </v-list>
        </v-navigation-drawer>

        <v-main style="min-height: 100vh">
            <slot />
        </v-main>
    </v-app>
</template>
