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
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-200 cursor-pointer" @click="goToProfile(user.id)">Profile</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-200 cursor-pointer" @click="goToAddFriend">Arkadaş Ekle</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-200 cursor-pointer" @click="openAddGroupModal">Grup Oluştur</a>
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
        <!-- Add Group Modal -->
        <div v-if="showAddGroupModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-2xl font-semibold mb-4">Add New Group</h2>
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 font-semibold">Group Name</label>
                    <input v-model="groupName" type="text" id="name" class="w-full px-4 py-2 border rounded-md" required>
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-gray-700 font-semibold">Description</label>
                    <textarea v-model="groupDescription" id="description" class="w-full px-4 py-2 border rounded-md" required></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold">Members</label>
                    <ul v-if="selectedMembers.length > 0" class="mt-2">
                        <li v-for="member in selectedMembers" :key="member.id" class="flex items-center justify-between bg-gray-100 px-3 py-1 rounded-md mb-1">
                            <span>{{ member.name }} {{ member.surname }}</span>
                            <button type="button" @click="removeMember(member.id)" class="ml-2 text-red-500">Remove</button>
                        </li>
                    </ul>
                    <div class="mt-2">
                        <button v-for="friend in friends" :key="friend.id" type="button" @click="addMember(friend.friend)" class="bg-blue-500 text-white px-4 py-2 rounded-md mr-2">{{ friend.friend.name }} {{ friend.friend.surname }}</button>
                    </div>
                </div>
                <div class="flex justify-end">
                    <div>
                        <button @click="createGroup" class="bg-green-500 text-white px-4 py-2 rounded-md mr-2">Create Group</button>
                    </div>
                    <div>
                        <button @click="closeModal" class="bg-gray-500 text-white px-4 py-2 rounded-md">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {ref, onMounted, computed} from 'vue';
import axios from "axios";
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';
import { useToast } from 'vue-toastification';

const store = useStore();
const router = useRouter();
const searchQuery = ref('');
const friends = ref([]);
const groups = ref([]);
const emit = defineEmits(['userSelected','groupSelected']);

const showAddGroupModal = ref(false);
const groupName = ref('');
const groupDescription = ref('');
const selectedMembers = ref([]);
const user = computed(() => store.getters.user);

const toast = useToast();

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

const goToProfile = async (userId) => {
    await router.push({ name: 'userProfile', params: { userId } });
};

const selectUser = (userId) => {
    emit('userSelected', userId);
};

const selectGroup = (groupId) => {
    emit('groupSelected',groupId);
};

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

const openAddGroupModal = () => {
    showAddGroupModal.value = true;
};

const closeModal = () => {
    showAddGroupModal.value = false;
    groupName.value = '';
    groupDescription.value = '';
    selectedMembers.value = [];
};

const createGroup = async () => {
    try {
        const response = await axios.post('/api/groups', {
            name: groupName.value,
            description: groupDescription.value,
            members: selectedMembers.value.map(member => member.id)
        });
        getGroups();
        closeModal();
        toast.success('Grup Başarıyla Oluşturuldu');
    } catch (error) {
        toast.error('Grup Oluşturulamadı');
    }
};

const addMember = (friend) => {
    if (!selectedMembers.value.some(member => member.id === friend.id)) {
        selectedMembers.value.push(friend);
    }
};

const removeMember = memberId => {
    selectedMembers.value = selectedMembers.value.filter(member => member.id !== memberId);
};
</script>

<style scoped>
/* İsteğe bağlı olarak stil ekleyebilirsiniz */
</style>
