<template>

    <div class="mt-[90px] lg:container mx-auto">
        <div class="gap-4 mx-2 my-2">
            <div class="bg-white shadow-md">
                <input
                    v-model="searchQuery"
                    type="text"
                    placeholder="Search"
                    class="w-full p-2 rounded-md border border-gray-500 focus:outline-none focus:ring focus:border-blue-400 mb-4"
                >

                <div class="h-screen overflow-y-auto ml-1">
                    <div
                        v-for="user in filteredUsers"
                        :key="user.id"
                        @click="selectUser(user.id)"
                        class="flex cursor-pointer rounded-md hover:bg-gray-200 mb-2"
                    >
                        <div>
                            <img :src="'https://picsum.photos/45?random=' + user.id" alt="" class="rounded-full">
                        </div>
                        <div class="ml-3">
                            <p class="font-semibold">{{ user.name }}</p>
                            <p class="text-sm text-gray-600">{{ user.email }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {computed, ref} from 'vue';

const props = defineProps({
    users: Array,
    currentUserId: Number,
});

const emit = defineEmits(['userSelected']);

const selectUser = (userId) => {
    emit('userSelected', userId);
};

const searchQuery = ref('');

const filteredUsers = computed(() => {
    if (!searchQuery.value.trim()) {
        return props.users.filter(user => user.id !== props.currentUserId);
    } else {
        const searchTerm = searchQuery.value.toLowerCase();
        return props.users.filter(user => {
            return user.name.toLowerCase().includes(searchTerm) || user.email.toLowerCase().includes(searchTerm);
        });
    }
});
</script>
