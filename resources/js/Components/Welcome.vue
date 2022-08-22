<script setup>
import JetApplicationLogo from '@/Components/ApplicationLogo.vue';
</script>

<template>
    <div>
        <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
            <div>
                <JetApplicationLogo class="block h-12 w-auto" />
            </div>

            <div class="mt-8 text-2xl">
                Welcome {{ $page.props.user.name }}!
            </div>

            <div class="mt-8 text-2xl">
                Greetings of the day!
            </div>
        </div>
    </div>
</template>
