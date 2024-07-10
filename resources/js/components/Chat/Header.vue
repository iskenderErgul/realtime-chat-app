<template>
    <div class="bg-white border-b border-gray-300 fixed top-0 w-full shadow">
        <div class="p-4">
            <div class="grid grid-cols-3 gap-4">
                <div class="col-span-1 min-w-[250px]">
                    <div class="flex items-center">
                        <img src="https://picsum.photos/45" alt="" class="rounded-full">
                        <span class="font-semibold text-xl pl-1">{{ user.name }}</span>
                    </div>
                </div>
                <div class="col-span-2 flex justify-end items-center">
                    <button class="bg-blue-500 text-white px-4 py-2 rounded-md mr-2">Profile</button>
                    <button @click="logout" class="bg-red-500 text-white px-4 py-2 rounded-md">Logout</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';
import { computed } from 'vue';

const store = useStore();
const router = useRouter();

const user = computed(() => store.getters.user);

const logout = async () => {
    await store.dispatch('logout');
    await router.push('/login');
};
</script>

<style scoped>
/* Add custom styles if needed */
</style>
