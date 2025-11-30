<template>
    <div class="mt-[80px] lg:container mx-auto">
        <div class="gap-4">
            <div class="pt-2 px-3">
                <div class="flex items-center gap-2 mb-4">
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Aratın veya yeni bir sohbet başlatın"
                        class="input-modern flex-1 p-3 rounded-xl border-0 focus:outline-none focus:ring-2 focus:ring-indigo-400 text-sm"
                    >
                    <div class="relative inline-block text-left group">
                        <button class="p-2 hover:bg-white/50 rounded-lg transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-6 h-6 text-gray-700">
                                <path fill-rule="evenodd" d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                            </svg>
                        </button>
                        <div class="origin-top-right absolute right-0 w-48 rounded-xl shadow-xl bg-white ring-1 ring-black ring-opacity-5 z-50 hidden group-hover:block overflow-hidden">
                            <div class="py-1">
                                <a href="#" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gradient-primary hover:text-white cursor-pointer transition-all" @click="goToProfile(user.id)">
                                    <font-awesome-icon :icon="['fas', 'user']" class="mr-2" />
                                    Profile
                                </a>
                                <a href="#" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gradient-primary hover:text-white cursor-pointer transition-all" @click="goToAddFriend">
                                    <font-awesome-icon :icon="['fas', 'user-plus']" class="mr-2" />
                                    Arkadaş Ekle
                                </a>
                                <a href="#" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gradient-primary hover:text-white cursor-pointer transition-all" @click="openAddGroupModal">
                                    <font-awesome-icon :icon="['fas', 'users']" class="mr-2" />
                                    Grup Oluştur
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="h-screen overflow-y-auto pr-2">
                    <!-- Friends List -->
                    <div>
                        <div
                            v-for="friendItem in filteredFriends"
                            :key="friendItem.id"
                            @click="selectUser(friendItem.friend.id)"
                            class="flex items-center p-3 cursor-pointer rounded-xl hover:bg-white/60 mb-2 transition-all hover:shadow-md group"
                        >
                            <div class="avatar w-12 h-12 bg-gradient-primary rounded-full mr-3 flex items-center justify-center flex-shrink-0">
                                <span class="text-lg font-semibold text-white" v-if="!friendItem.friend.avatar">{{ getInitials(friendItem.friend.name, friendItem.friend.surname) }}</span>
                                <img v-else :src="friendItem.friend.avatar" alt="avatar" class="w-12 h-12 rounded-full object-cover">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-800 truncate">{{ friendItem.friend.name }} {{ friendItem.friend.surname }}</p>
                                <p class="text-gray-600 text-sm truncate">
                                    {{ formatLastMessage(friendItem.last_message, friendItem.last_message_sender_id) }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Groups List -->
                    <div class="mt-6" v-if="filteredGroups.length > 0">
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 px-2">Gruplar</h3>
                        <div
                            v-for="group in filteredGroups"
                            :key="group.id"
                            @click="selectGroup(group.id)"
                            class="flex items-center p-3 cursor-pointer rounded-xl hover:bg-white/60 mb-2 transition-all hover:shadow-md group"
                            v-tooltip="group.name"
                        >
                            <div class="avatar w-12 h-12 bg-gradient-secondary rounded-full mr-3 flex items-center justify-center flex-shrink-0">
                                <span class="text-lg font-semibold text-white" v-if="!group.avatar">{{ getGroupInitials(group.name) }}</span>
                                <img v-else :src="group.avatar" alt="avatar" class="w-12 h-12 rounded-full object-cover">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-800 truncate">{{ getTruncatedGroupName(group.name) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Add Group Modal -->
        <div v-if="showAddGroupModal" class="fixed inset-0 flex items-center justify-center bg-black/50 backdrop-blur-sm z-50 p-4">
            <div class="glass-card rounded-2xl p-8 max-w-md w-full shadow-2xl animate-fade-in">
                <h2 class="text-2xl font-bold text-gradient mb-6">Yeni Grup Oluştur</h2>
                <div class="mb-5">
                    <label for="name" class="block text-gray-700 font-semibold mb-2">Grup Adı</label>
                    <input v-model="groupName" type="text" id="name" class="input-modern w-full px-4 py-3 rounded-xl" required>
                </div>
                <div class="mb-5">
                    <label for="description" class="block text-gray-700 font-semibold mb-2">Açıklama</label>
                    <textarea v-model="groupDescription" id="description" class="input-modern w-full px-4 py-3 rounded-xl min-h-[100px]" required></textarea>
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Üyeler</label>
                    <ul v-if="selectedMembers.length > 0" class="mt-2 space-y-2 mb-3">
                        <li v-for="member in selectedMembers" :key="member.id" class="flex items-center justify-between bg-gray-100 px-4 py-2 rounded-lg">
                            <span class="font-medium">{{ member.name }} {{ member.surname }}</span>
                            <button type="button" @click="removeMember(member.id)" class="text-red-500 hover:text-red-700 font-semibold">Kaldır</button>
                        </li>
                    </ul>
                    <div class="flex flex-wrap gap-2">
                        <button v-for="friend in messagedFriends" :key="friend.friend.id" type="button" @click="addMember(friend.friend)" class="btn-modern bg-gradient-primary text-white px-4 py-2 rounded-lg text-sm">
                            {{ friend.friend.name }} {{ friend.friend.surname }}
                        </button>
                    </div>
                </div>
                <div class="flex gap-3">
                    <button @click="createGroup" class="btn-modern flex-1 bg-gradient-to-r from-emerald-500 to-teal-500 text-white px-6 py-3 rounded-xl font-semibold shadow-lg">
                        Grup Oluştur
                    </button>
                    <button @click="closeModal" class="btn-modern flex-1 bg-gray-500 text-white px-6 py-3 rounded-xl font-semibold shadow-lg">
                        İptal
                    </button>
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
    getFriendsWithLastMessage();
});

const getFriendsWithLastMessage = async () => {
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
    console.log('goToAddFriend çalıştı')
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
