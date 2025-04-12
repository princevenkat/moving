<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'
import AppLogo from "@/components/AppLogo.vue";

const page = usePage()
const user = computed(() => page.props.auth?.user)
</script>

<template>
    <div class="navbar bg-base-100 fixed top-0 left-0 right-0 w-full">
        <div class="flex-auto">
            <Link href="/" class="flex items-center gap-x-2">
                <AppLogo />
            </Link>
        </div>

        <div class="flex-none gap-3">
            <template v-if="user">
                <div class="dropdown dropdown-end">
                    <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
                        <div class="w-10 rounded-full">
                            <img
                                :alt="`Avatar of ${user.name}`"
                                src="https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp"
                            />
                        </div>
                    </div>

                    <ul
                        tabindex="0"
                        class="menu menu-sm dropdown-content bg-base-100 rounded-box z-[1] mt-3 w-52 p-2 shadow"
                    >
                        <li>
                            <Link :href="route('profile.edit')" class="justify-between">
                                My Account
                            </Link>
                        </li>

                        <li>
                            <Link :href="route('logout')" method="post" as="button">Logout</Link>
                        </li>
                    </ul>
                </div>
            </template>

            <template v-else>
                <div  class="flex gap-2">
                    <Link :href="route('login')" class="btn btn-neutral">Login</Link>
                    <Link :href="route('register')" class="btn btn-outline border-2 rounded-lg">Register</Link>
                </div>
            </template>
        </div>
    </div>
</template>
