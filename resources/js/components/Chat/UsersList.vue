<template>
    <div class="mt-[82px] lg:container mx-auto">
        <div class="gap-4 pr-1">
            <div class="bg-white shadow-md">
                <div class="flex">
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Search"
                        class="w-full p-2 rounded-md border border-gray-500 focus:outline-none focus:ring focus:border-blue-400 mb-4"
                    >
                    <div class="relative inline-block text-left group mt-3">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="bi bi-three-dots-vertical w-6 h-6">
                            <path fill-rule="evenodd" d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                        </svg>
                        <div class="origin-top-right absolute right-0 w-40 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50 hidden group-hover:block">
                            <div class="py-1">

                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-200 cursor-pointer"  @click="goToAddFriend">Arkadaş Ekle</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-200 cursor-pointer"  @click="openAddGroupModal">Grup Oluştur</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="h-screen overflow-y-auto ml-2">

                    <div>
                        <h3 class="text-lg font-semibold mb-2">Friends</h3>
                        <div
                            v-for="friendItem in friends"
                            :key="friendItem.id"
                            @click="selectUser(friendItem.friend.id)"
                            class="flex cursor-pointer rounded-md hover:bg-gray-200 mb-2"
                        >
                            <div :style="{ backgroundColor: getRandomColor(friendItem.id) }" class="w-12 h-12 rounded-full mr-4 flex items-center justify-center">
                                <span class="text-xl font-semibold" v-if="!friendItem.friend.avatar">{{ getInitials(friendItem.friend.name, friendItem.friend.surname) }}</span>
                                <img v-else :src="friendItem.friend.avatar" alt="avatar" class="w-12 h-12 rounded-full">
                            </div>
                            <div>
                                <p class="font-semibold">{{ friendItem.friend.name }} {{ friendItem.friend.surname }}</p>
                                <p class="text-sm text-gray-600">{{ friendItem.friend.email }}</p>
                            </div>
                        </div>
                    </div>


                    <div class="mt-4">
                        <h3 class="text-lg font-semibold mb-2">Groups</h3>
                        <div
                            v-for="group in groups"
                            :key="group.id"
                            @click="selectGroup(group.id)"
                            class="flex cursor-pointer rounded-md hover:bg-gray-200 mb-2"
                        >
                            <div :style="{ backgroundColor: getRandomColor(group.id) }" class="w-12 h-12 rounded-full mr-4 flex items-center justify-center">
                                <span class="text-xl font-semibold text-white" v-if="!group.avatar">{{ getGroupInitials(group.name) }}</span>
                                <img v-else :src="group.avatar" alt="avatar" class="w-12 h-12 rounded-full">
                            </div>
                            <div>
                                <p class="font-semibold">{{ group.name }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <AddGroupModal v-if="showAddGroupModal" @close="closeAddGroupModal" />
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from "axios";
import { useRouter } from 'vue-router';
import AddGroupModal from "./AddGroupModal.vue";

const router = useRouter();
const searchQuery = ref('');
const friends = ref([]);
const groups = ref([]);
const showAddGroupModal = ref(false);
const emit = defineEmits(['userSelected','groupSelected']);

const openAddGroupModal = () => {
    showAddGroupModal.value = true;
};
onMounted(() => {
    getFriends();
    getGroups();
});

const getFriends = async () => {
    try {
        const response = await axios.get('/api/friends');
        friends.value = response.data;
    } catch (error) {
        console.error('Error fetching friends:', error);
    }
};

const getGroups = async () => {
    try {
        const response = await axios.get('/api/groups');
        groups.value = response.data;
    } catch (error) {
        console.error('Error fetching groups:', error);
    }
};
const goToAddFriend = async () => {
    await router.push({ name: 'AddFriend' });
};



const selectUser = (userId) => {
    emit('userSelected', userId);
};
const selectGroup = (groupId) => {
    emit('groupSelected',groupId);
}

const getInitials = (name, surname) => {
    const nameInitial = name ? name.charAt(0).toUpperCase() : '';
    const surnameInitial = surname ? surname.charAt(0).toUpperCase() : '';
    return nameInitial + surnameInitial;
};

const getGroupInitials = (name) => {
    const words = name.split(' ');
    let initials = '';
    words.forEach(word => {
        if (word.length > 0) {
            initials += word.charAt(0).toUpperCase();
        }
    });
    return initials;
};

const getRandomColor = (seed) => {
    const colors = [
        '#FF5733', '#33FF57', '#3357FF', '#F333FF', '#FF33F0',
        '#F0FF33', '#FF333F', '#33FFF0', '#33F0FF', '#5733FF'
    ];
    return colors[seed % colors.length];
};

</script>
