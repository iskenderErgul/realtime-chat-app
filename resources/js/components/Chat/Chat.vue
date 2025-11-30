<template>
    <div class="grid grid-rows-[auto,1fr] h-screen overflow-hidden bg-gradient-chat">

        <div class="w-full">
            <Header></Header>
        </div>


        <div class="grid grid-cols-1 lg:grid-cols-[300px,1fr] xl:grid-cols-[350px,1fr] h-full">

            <!-- Sidebar - Hidden on mobile when chat is selected -->
            <div class="sidebar-panel overflow-y-auto glass-sidebar border-r border-white/20" :class="{'hidden': selectedUserId || selectedGroupId, 'lg:block': true}">
                <UserListComponent :currentUserId="currentUserId" :users="users" @userSelected="setSelectedUser" @groupSelected="setSelectedGroup" />
            </div>

            <!-- Chat Window -->
            <div v-if="!selectedGroupId" class="flex flex-col flex-1 overflow-hidden">
                <ChatWindowComponent :users="users" :currentUser="currentUser" :selectedUserId="selectedUserId"/>
            </div>

            <div v-else class="flex flex-col flex-1 overflow-hidden">
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
import GroupChatWindow from "./GroupChatWindow.vue";

const store = useStore();


const currentUser = computed(() => store.getters.user);
const currentUserId = currentUser.value.id;
const selectedUserId = ref(null);
const selectedGroupId = ref(null);
const users =ref([]);

const setSelectedUser = (userId) => {
    selectedUserId.value = userId;
    selectedGroupId.value = null;
    router.push(`/chat/${userId}`);
};

const setSelectedGroup = (groupId) => {
    selectedGroupId.value = groupId;
    selectedUserId.value = null;
};
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

<style scoped>
.glass-sidebar {
    background: rgba(255, 255, 255, 0.7);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
}

/* Mobile responsive fixes */
@media (max-width: 1023px) {
    .sidebar-panel {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 10;
    }
}
</style>
