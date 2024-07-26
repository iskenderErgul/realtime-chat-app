<template>
    <div class="grid grid-rows-[auto,1fr] h-screen overflow-hidden ">

        <div class="w-full ">
            <Header></Header>
        </div>


        <div class="grid grid-cols-[1fr,4fr] h-full  ">

            <div class="min-w-[200px] overflow-y-auto  bg-gray-200">
                <UserListComponent :currentUserId="currentUserId" :users="users" @userSelected="setSelectedUser" @groupSelected="setSelectedGroup"/>
            </div>

            <!-- Chat Window -->
            <div v-if="!selectedGroupId" class="flex flex-col flex-1 overflow-hidden  bg-gray-100">
                <ChatWindowComponent :users="users" :currentUser="currentUser" :selectedUserId="selectedUserId"/>
            </div>

            <div v-else class="flex flex-col flex-1 overflow-hidden  bg-gray-100">
                <GroupChatWindow :currentUser="currentUser"  :selectedGroupId="selectedGroupId"/>
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
import '../../echo.js'
import GroupChatWindow from "@/components/Chat/GroupChatWindow.vue";

const store = useStore();


const currentUser = computed(() => store.getters.user);
const currentUserId = currentUser.value.id;
const selectedUserId = ref(null);
const selectedGroupId = ref(null);

const setSelectedUser = (userId) => {
    selectedUserId.value = userId;
    selectedGroupId.value = null;
    router.push(`/chat/${userId}`);
};

const setSelectedGroup = (groupId) => {
    selectedGroupId.value = groupId;
    selectedUserId.value = null;
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
