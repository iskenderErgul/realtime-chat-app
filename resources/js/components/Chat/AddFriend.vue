<template>
    <div class="container mx-auto mt-10">
        <button
            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline absolute top-2 right-2"
            type="button"
            @click="goBack"

        >
            Geri
        </button>
        <h1 class="text-2xl font-bold mb-4">Add Friends</h1>
        <input v-model="searchQuery" type="text" placeholder="Search Users" class="w-full p-2 rounded-md border border-gray-500 focus:outline-none focus:ring focus:border-blue-400 mb-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div v-for="user in filteredUsers" :key="user.id" class="p-4 border rounded-md flex justify-between items-center">
                <div class="flex items-center">
                    <div :style="{ backgroundColor: getRandomColor(user.id) }" class="w-12 h-12 rounded-full mr-4 flex items-center justify-center">
                        <span class="text-xl font-semibold" v-if="!user.avatar">{{ getInitials(user.name, user.surname) }}</span>
                        <img v-else :src="user.avatar" alt="avatar" class="w-12 h-12 rounded-full">
                    </div>
                    <div>
                        <p class="font-semibold">{{ user.name }} {{user.surname}}</p>
                        <p class="text-sm text-gray-600">{{ user.email }}</p>
                    </div>
                </div>
                <button @click="addFriend(user.id)" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 focus:outline-none">Add Friend</button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';
import router from "../../router/router.js";
import {useStore} from "vuex";
const store = useStore();

const searchQuery = ref('');
const users = ref([]);
const currentUser = computed(() => store.getters.user);

const getAllUsers = async () => {
    try {
        const response = await axios.get('/api/users');
        users.value = response.data;
    } catch (error) {
        console.error('Error fetching users:', error);
    }
};

const addFriend = async (userId) => {
    try {
        await axios.post('/api/friends', { friend_id: userId });
        alert('Friend added successfully!');
    } catch (error) {
        console.error('Error adding friend:', error);
    }
};

const filteredUsers = computed(() => {
    if (!searchQuery.value.trim()) {
        return users.value.filter(user => user.id !== currentUser.value.id); // Filter out current user
    }
    const searchTerm = searchQuery.value.toLowerCase();
    return users.value.filter(user =>
        (user.name.toLowerCase().includes(searchTerm) || user.email.toLowerCase().includes(searchTerm)) && user.id !== currentUser.value.id
    );
});

const getInitials = (name, surname) => {
    const nameInitial = name ? name.charAt(0).toUpperCase() : '';
    const surnameInitial = surname ? surname.charAt(0).toUpperCase() : '';
    return nameInitial + surnameInitial;
};

const getRandomColor = (seed) => {
    const colors = [
        '#FF5733', '#33FF57', '#3357FF', '#F333FF', '#FF33F0',
        '#F0FF33', '#FF333F', '#33FFF0', '#33F0FF', '#5733FF'
    ];
    return colors[seed % colors.length];
};

const goBack = () => {
    router.go(-1);
};

onMounted(() => {
    getAllUsers();

});
</script>
