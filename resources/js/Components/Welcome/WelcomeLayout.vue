<script setup>
import { Link } from "@inertiajs/vue3";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
</script>

<template>
    <div
        class="relative min-h-screen bg-gray-100 dark:bg-gray-900 flex flex-col items-center justify-center p-6 lg:p-0"
    >
        <Link href="/" class="absolute top-8 left-8 z-30">
            <ApplicationLogo class="w-16 h-16 fill-current text-white" />
        </Link>
        <slot />
        <div
            class="relative flex-1 lg:flex-none w-full lg:w-1/2 h-full absolute top-0 right-0"
        >
            <div class="absolute lg:hidden inset-0 bg-black/50 z-10" />
            <img
                src="https://picsum.photos/1200/900?random=1"
                alt="Familia protegida por un seguro"
                class="h-full w-full object-cover"
            />
        </div>
    </div>
</template>
