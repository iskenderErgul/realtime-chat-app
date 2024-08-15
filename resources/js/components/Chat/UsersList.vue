<template>
    <div class="mt-[80px] lg:container mx-auto border-r border-black ">
        <div class="gap-4 bg-gray-100 ">
            <div class=" shadow-md pt-2">
                <div class="flex pl-2">
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Aratın veya yeni bir sohbet başlatın"
                        class="w-full p-2 rounded-md border-b border-gray-500 focus:outline-none focus:ring focus:border-blue-400 mb-4 bg-gray-100 text-black"
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
                        <div
                            v-for="friendItem in filteredFriends"
                            :key="friendItem.id"
                            @click="selectUser(friendItem.friend.id)"
                            class="flex cursor-pointer rounded-md hover:bg-gray-200 mb-2"
                        >
                            <div class="w-12 h-12 bg-gray-300 rounded-full mr-4 flex items-center justify-center">
                                <span class="text-xl font-semibold" v-if="!friendItem.friend.avatar">{{ getInitials(friendItem.friend.name, friendItem.friend.surname) }}</span>
                                <img v-else :src="friendItem.friend.avatar" alt="avatar" class="w-12 h-12 rounded-full">
                            </div>
                            <div>
                                <p class="font-semibold">{{ friendItem.friend.name }} {{ friendItem.friend.surname }}</p>
                                <p class="text-gray-600 text-sm">
                                    {{ formatLastMessage(friendItem.last_message, friendItem.last_message_sender_id) }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <div
                            v-for="group in filteredGroups"
                            :key="group.id"
                            @click="selectGroup(group.id)"
                            class="flex cursor-pointer rounded-md hover:bg-gray-200 mb-2"
                            v-tooltip="group.name"
                        >
                            <div class="w-12 h-12 bg-gray-300 rounded-full mr-4 flex items-center justify-center">
                                <span class="text-xl font-semibold " v-if="!group.avatar">{{ getGroupInitials(group.name) }}</span>
                                <img v-else :src="group.avatar" alt="avatar" class="w-12 h-12 rounded-full">
                            </div>
                            <div>
                                <p class="font-semibold">{{ getTruncatedGroupName(group.name) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Add Group Modal -->
        <div v-if="showAddGroupModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-2xl font-semibold mb-4">Yeni Grup Oluştur</h2>
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 font-semibold">Grup Adı</label>
                    <input v-model="groupName" type="text" id="name" class="w-full px-4 py-2 border rounded-md" required>
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-gray-700 font-semibold">Açıklama</label>
                    <textarea v-model="groupDescription" id="description" class="w-full px-4 py-2 border rounded-md" required></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold">Üyeler</label>
                    <ul v-if="selectedMembers.length > 0" class="mt-2">
                        <li v-for="member in selectedMembers" :key="member.id" class="flex items-center justify-between bg-gray-100 px-3 py-1 rounded-md mb-1">
                            <span>{{ member.name }} {{ member.surname }}</span>
                            <button type="button" @click="removeMember(member.id)" class="ml-2 text-red-500">Kaldır</button>
                        </li>
                    </ul>
                    <div class="mt-2">
                        <button v-for="friend in messagedFriends" :key="friend.friend.id" type="button" @click="addMember(friend.friend)" class="bg-blue-500 text-white px-4 py-2 rounded-md mr-2">{{ friend.friend.name }} {{ friend.friend.surname }}</button>
                    </div>
                </div>
                <div class="flex justify-end">
                    <div>
                        <button @click="createGroup" class="bg-green-500 text-white px-4 py-2 rounded-md mr-2">Grup Oluştur</button>
                    </div>
                    <div>
                        <button @click="closeModal" class="bg-gray-500 text-white px-4 py-2 rounded-md">İptal</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from "axios";
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';
import { useToast } from 'vue-toastification';

const store = useStore();
const router = useRouter();
const searchQuery = ref('');
const messagedFriends = ref([]);
const groups = ref([]);
const emit = defineEmits(['userSelected', 'groupSelected']);

const showAddGroupModal = ref(false);
const groupName = ref('');
const groupDescription = ref('');
const selectedMembers = ref([]);
const user = computed(() => store.getters.user);

const toast = useToast();

onMounted(() => {
    getGroups();
    getMessagedFriends();
});

const getMessagedFriends = async () => {
    try {
        const response = await axios.get('/api/friends/messaged');
        messagedFriends.value = response.data;
    } catch (error) {
        console.error('Error fetching friends:', error);
    }
};

const formatLastMessage = (message, senderId) => {
    if (!message) return '';
    const userId = store.getters.user.id;
    const senderLabel = senderId === userId ? 'Siz: ' : '';
    return senderLabel + message;
};

const getGroups = async () => {
    try {
        const response = await axios.get('/api/groups');
        groups.value = response.data;
    } catch (error) {
        console.error('Error fetching groups:', error);
    }
};

const filteredFriends = computed(() => {
    if (!searchQuery.value) return messagedFriends.value;
    const query = searchQuery.value.toLowerCase();
    return messagedFriends.value.filter(friendItem => {
        const name = `${friendItem.friend.name} ${friendItem.friend.surname}`.toLowerCase();
        return name.includes(query);
    });
});

const filteredGroups = computed(() => {
    if (!searchQuery.value) return groups.value;
    const query = searchQuery.value.toLowerCase();
    return groups.value.filter(group => {
        return group.name.toLowerCase().includes(query);
    });
});

const goToAddFriend = async () => {
    await router.push({ name: 'AddFriend' });
};

const goToProfile = async (userId) => {
    await router.push({ name: 'userProfile', params: { userId } });
};

const openAddGroupModal = () => {
    showAddGroupModal.value = true;
};

const closeModal = () => {
    showAddGroupModal.value = false;
};

const selectUser = (userId) => {
    emit('userSelected', userId);
};

const selectGroup = (groupId) => {
    emit('groupSelected', groupId);
};

const getInitials = (name, surname) => {
    const initials = (name[0] || '') + (surname[0] || '');
    return initials.toUpperCase();
};

const getGroupInitials = (name) => {
    const initials = name.split(' ').map(word => word[0]).join('');
    return initials.toUpperCase();
};

const getTruncatedGroupName = (name) => {
    return name.length > 20 ? name.slice(0, 20) + '...' : name;
};

const addMember = (member) => {
    if (!selectedMembers.value.find(m => m.id === member.id)) {
        selectedMembers.value.push(member);
    }
};

const removeMember = (id) => {
    selectedMembers.value = selectedMembers.value.filter(member => member.id !== id);
};

const createGroup = async () => {
    try {
        await axios.post('/api/groups', {
            name: groupName.value,
            description: groupDescription.value,
            members: selectedMembers.value.map(member => member.id)
        });
        toast.success('Grup başarıyla oluşturuldu.');
        closeModal();
    } catch (error) {
        toast.error('Grup oluşturulurken bir hata oluştu.');
    }
};
</script>
