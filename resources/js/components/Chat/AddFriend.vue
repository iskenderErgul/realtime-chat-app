<template>
   <Header></Header>
    <div class="container mx-auto mt-[120px]">

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
import {useStore} from "vuex";
import Header from "./Header.vue";
const store = useStore();
import { useToast } from 'vue-toastification';

const toast = useToast();

const searchQuery = ref('');
const users = ref([]);
const friends = ref([]);
const currentUser = computed(() => store.getters.user);

const getAllUsers = async () => {
    try {
        const response = await axios.get('/api/users');
        users.value = response.data;
    } catch (error) {
        console.error('Error fetching users:', error);
    }
};

const getFriends = async () => {
    try {
        const response = await axios.get('/api/friends');
        friends.value = response.data;
    } catch (error) {
        console.error('Error fetching friends:', error);
    }
};

const addFriend = async (userId) => {
    try {
        await axios.post('/api/friends', { friend_id: userId });
        toast.success('Kullanıcı Başarıyla eklendi');
        getFriends();
    } catch (error) {
        toast.error('Kullanıcı Eklenemedi');
    }
};

const filteredUsers = computed(() => {
    const friendIds = friends.value.map(friend => friend.id);
    if (!searchQuery.value.trim()) {
        return users.value.filter(user => user.id !== currentUser.value.id && !friendIds.includes(user.id));
    }
    const searchTerm = searchQuery.value.toLowerCase();
    return users.value.filter(user =>
        (user.name.toLowerCase().includes(searchTerm) || user.email.toLowerCase().includes(searchTerm)) &&
        user.id !== currentUser.value.id &&
        !friendIds.includes(user.id)
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

onMounted(() => {
    getAllUsers();
    getFriends();
});
</script>
