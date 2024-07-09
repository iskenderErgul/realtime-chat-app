<script>
export default {
    name: "Login",
    data() {
        return {
            user: {
                email: null,
                password: null,
            },
        }

    },
    mounted() {

    },
    methods: {
        async login(){
            this.$store.dispatch('login',this.user)
        },

    }
}
</script>


<template>
    <div class="flex items-center justify-center h-screen">
        <div class="w-full max-w-md">
            <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                <h2 class="text-center text-2xl font-bold mb-6">Login</h2>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                        Email
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        type="email"
                        placeholder="Email"
                        v-model="user.email"


                    />
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                        Password
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline"
                        placeholder="******************"
                        v-model="user.password"

                    />
                </div>
                <div class="flex items-center justify-end">
                    <button
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                        type="button"
                        @click="login"
                    >
                            Giriş Yap
                    </button>

                </div>
            </form>

        </div>
    </div>
</template>


<style >
</style>
