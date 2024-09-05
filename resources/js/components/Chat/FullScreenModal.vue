<template>
    <div v-if="isOpen" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50">
        <div class="relative">
            <button @click="close" class="absolute top-4 right-4 text-white text-2xl">×</button>
            <img :src="imageSrc" alt="Full Screen Image" class="max-w-full max-h-screen object-contain"/>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';

const props = defineProps({
    imageSrc: String,
    isOpen: Boolean,
});

const emit = defineEmits(['update:isOpen']);

const close = () => {
    emit('update:isOpen', false);
};
</script>

<style scoped>
/* Styles for the modal */
</style>
