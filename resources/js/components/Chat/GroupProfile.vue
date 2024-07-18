<template>
    <Header></Header>
    <div class="max-w-4xl mx-auto p-6 bg-white shadow-md rounded-lg mt-24">
        <!-- Grup Bilgileri Bölümü -->
        <div class="border-b pb-4 mb-4 bg-gray-50 p-4 rounded-md shadow-sm">
            <h1 class="text-3xl text-center font-bold mb-2">{{ group.name }}</h1>
            <p class="text-gray-600">{{ group.description }}</p>
        </div>

        <!-- Üyeler Bölümü -->
        <div class="mb-6 bg-gray-50 p-4 rounded-md shadow-sm">
            <h2 class="text-2xl font-semibold mb-4">Üyeler</h2>
            <ul>
                <li v-for="member in members" :key="member.id" class="flex items-center justify-between bg-gray-100 rounded-lg p-2 mb-2">
                    <div>
                        <span class="font-semibold">{{ member.name }} {{ member.surname }}</span>
                        <span v-if="member.pivot.is_admin" class="text-green-600 ml-2">(Yönetici)</span>
                    </div>
                    <div>
                        <!-- Yönetici değilse ve üye ise gruptan çık butonu göster -->
                        <button v-if="!isAdmin && member.id === authUser.id" @click="leaveGroup" class="text-red-600 hover:text-red-800 ml-2">Gruptan Çık</button>
                        <!-- Yönetici ise yönetici işlemleri butonları göster -->
                        <button v-if="isAdmin && !member.pivot.is_admin" @click="assignAdmin(member.id)" class="text-blue-600 hover:text-blue-800">Yönetici Ata</button>
                        <button v-if="isAdmin && member.pivot.is_admin" @click="removeAdmin(member.id)" class="text-red-600 hover:text-red-800 ml-2">Yöneticiliği Kaldır</button>
                        <button v-if="isAdmin" @click="removeMember(member.id)" class="text-red-600 hover:text-red-800 ml-2">Üyeyi Çıkar</button>
                    </div>
                </li>
            </ul>
        </div>

        <!-- Grup Bilgilerini Düzenleme Bölümü (Sadece Yönetici için görünür) -->
        <div v-if="isAdmin" class="mb-6 bg-gray-50 p-4 rounded-md shadow-sm">
            <button @click="toggleEditMode" class="bg-blue-500 text-white px-4 py-2 rounded-md mb-4">
                {{ editMode ? 'Düzenlemeyi İptal Et' : 'Grup Bilgilerini Düzenle' }}
            </button>

            <div v-if="editMode" class="space-y-4">
                <input v-model="editName" type="text" placeholder="Grup Adı" class="w-full px-4 py-2 border rounded-md">
                <textarea v-model="editDescription" placeholder="Grup Açıklaması" class="w-full px-4 py-2 border rounded-md"></textarea>
                <div>
                    <button @click="updateGroup" class="bg-green-500 text-white px-4 py-2 rounded-md">Kaydet</button>
                    <button @click="toggleEditMode" class="bg-gray-500 text-white px-4 py-2 rounded-md ml-2">İptal</button>
                </div>
            </div>
        </div>

        <!-- Arkadaş Ekleme Bölümü -->
        <div v-if="isAdmin" class="bg-gray-50 p-4 rounded-md shadow-sm">
            <h2 class="text-xl font-semibold mb-2">Arkadaşlarını Ekle</h2>
            <ul class="mb-4">
                <li v-for="friend in availableFriends" :key="friend.id" class="flex items-center justify-between mb-2 border-b pb-2">
                    <span class="mr-2">{{ friend.friend.name }} {{ friend.friend.surname }} </span>
                    <button @click="addMember(friend.friend.id)" class="text-black py-1 px-4 rounded-lg hover:bg-green-400 bg-green-500 ">Ekle</button>
                </li>
            </ul>
        </div>
    </div>
</template>


<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
import Header from "./Header.vue";

const router = useRouter();
const selectedGroupId = router.currentRoute.value.params.selectedGroupId;

const group = ref({});
const members = ref([]);
const editMode = ref(false);
const editName = ref('');
const editDescription = ref('');
const friends = ref([]);
const authUser = ref({});

const fetchGroupDetails = async () => {
    try {
        const response = await axios.get(`/api/groups/${selectedGroupId}`);
        group.value = response.data;
        editName.value = group.value.name;
        editDescription.value = group.value.description;
    } catch (error) {
        console.error('Grup bilgileri alınırken hata oluştu:', error);
    }
};

const getFriends = async () => {
    try {
        const response = await axios.get('/api/friends');
        friends.value = response.data;
    } catch (error) {
        console.error('Arkadaşlar alınırken hata oluştu:', error);
    }
};

const fetchGroupMembers = async () => {
    try {
        const response = await axios.get(`/api/groups/${selectedGroupId}/members`);
        members.value = response.data;
    } catch (error) {
        console.error('Grup üyeleri alınırken hata oluştu:', error);
    }
};

const fetchAuthUser = async () => {
    try {
        const response = await axios.get('/api/user');
        authUser.value = response.data;
    } catch (error) {
        console.error('Oturum açmış kullanıcı bilgileri alınırken hata oluştu:', error);
    }
};

const assignAdmin = async (userId) => {
    try {
        await axios.patch(`/api/groups/${selectedGroupId}/members/${userId}/assign-admin`);
        fetchGroupMembers();
    } catch (error) {
        console.error('Yönetici atanırken hata oluştu:', error);
    }
};

const removeAdmin = async (userId) => {
    try {
        await axios.patch(`/api/groups/${selectedGroupId}/members/${userId}/remove-admin`);
        fetchGroupMembers();
    } catch (error) {
        console.error('Yöneticilik kaldırılırken hata oluştu:', error);
    }
};

const addMember = async (friendId) => {
    try {
        await axios.post(`/api/groups/${selectedGroupId}/members`, {
            friend_id: friendId,
        });
        fetchGroupMembers();
    } catch (error) {
        console.error('Üye eklenirken hata oluştu:', error);
    }
};

const removeMember = async (userId) => {
    try {
        await axios.delete(`/api/groups/${selectedGroupId}/members/${userId}`);
        fetchGroupMembers();
    } catch (error) {
        console.error('Üye çıkarılırken hata oluştu:', error);
    }
};

const leaveGroup = async () => {
    try {
        await axios.delete(`/api/groups/${selectedGroupId}/members/${authUser.value.id}`);
        await router.push('/chat');
    } catch (error) {
        console.error('Gruptan çıkarken hata oluştu:', error);
    }
};
const updateGroup = async () => {
    try {
        await axios.put(`/api/groups/${selectedGroupId}`, {
            name: editName.value,
            description: editDescription.value,
        });
        fetchGroupDetails();
        toggleEditMode();
    } catch (error) {
        console.error('Grup güncellenirken hata oluştu:', error);
    }
};

const toggleEditMode = () => {
    editMode.value = !editMode.value;
};

const availableFriends = computed(() => {
    return friends.value.filter(friend => !members.value.some(member => member.id === friend.friend.id));
});

const isAdmin = computed(() => {
    return members.value.some(member => member.id === authUser.value.id && member.pivot.is_admin);
});

onMounted(() => {
    fetchGroupDetails();
    fetchGroupMembers();
    getFriends();
    fetchAuthUser();
});
</script>
