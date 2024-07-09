<template>
    <div class="flex items-center justify-center h-screen">
        <div class="w-full max-w-md">
            <ul class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                <li v-for="user in users" :key="user.id" class="mb-4 cursor-pointer">
                    <a @click="redirectToUser(user.id)">
                        <div class="flex items-center">
                            <span class="mr-2">{{ user.name }}</span>
                            <span class="text-sm text-gray-500">{{ user.email }}</span>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';


const users = ref([]);



const router = useRouter();

const fetchUsers = async () => {
    try {
        const response = await axios.get('/api/users'); // API endpoint for fetching users
        users.value = response.data;
    } catch (error) {
        console.error('Error fetching users:', error);
    }
};

const redirectToUser = (friendId) => {
    router.push(`/chat/${friendId}/`);
};




onMounted(fetchUsers);
</script>

