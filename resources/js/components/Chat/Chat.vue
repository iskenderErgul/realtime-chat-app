<template>
    <div class="grid grid-rows-[auto,1fr] h-screen">
        <!-- Header -->
        <div class="w-full">
            <Header></Header>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-[1fr,4fr] h-full ">
            <!-- User List Component -->
            <div class="min-w-[200px] overflow-hidden overflow-x-hidden bg-gray-200">
                <UserListComponent :currentUserId="currentUserId" :users="users" @userSelected="setSelectedUser"/>
            </div>

            <!-- Chat Window -->
            <div class="flex flex-col flex-1 overflow-y-auto bg-gray-100">
                <ChatWindowComponent :users="users" :currentUser="currentUser" :selectedUserId="selectedUserId"/>
            </div>
        </div>
    </div>
</template>

<script setup>
import {computed, onMounted, ref} from 'vue';
import { useStore } from 'vuex';
import ChatWindowComponent from './ChatWindow.vue';
import UserListComponent from './UsersList.vue';
import Header from './Header.vue';
import axios from "axios";
import router from "../../router/router.js";

const store = useStore();

const currentUser = computed(() => store.getters.user);
const currentUserId = currentUser.value.id;
const selectedUserId = ref(null);

const setSelectedUser = (userId) => {
    selectedUserId.value = userId;
    router.push(`/chat/${userId}`);
};

const users =ref([]);
const getAllUsers = async () =>  {
    try {

        const resp = await axios.get('/api/users');
        users.value = resp.data;
    } catch (error) {
        console.error('Error fetching users:', error);
    }
}

onMounted(() => {
    getAllUsers();
});

</script>
