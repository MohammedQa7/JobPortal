<template>
    <DropdownMenu>
        <DropdownMenuTrigger class="flex  items-center ">
            <Avatar class="h-10 w-10 rounded-full">
                <AvatarImage />
                <AvatarFallback class="rounded-lg">
                    {{ page.props.auth.user.name.slice(0, 2).toUpperCase() }}
                </AvatarFallback>
            </Avatar>
        </DropdownMenuTrigger>

        <DropdownMenuContent class="w-[--radix-dropdown-menu-trigger-width] min-w-56 rounded-lg" side="bottom"
            align="end" :side-offset="4">
            <DropdownMenuLabel class="p-0 font-normal">
                <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                    <Avatar class="h-8 w-8 rounded-lg">
                        <AvatarImage />
                        <AvatarFallback class="rounded-lg">
                            CN
                        </AvatarFallback>
                    </Avatar>
                    <div class="grid flex-1 text-left text-sm leading-tight">
                        <span class="truncate font-semibold">{{ page.props.auth.user.name }}</span>
                        <span class="truncate text-xs">{{ page.props.auth.user.email }}</span>
                        <UserBalanceDialog />
                    </div>
                </div>
                <DropdownMenuGroup v-if="!page.props.auth.isProfileCompleted">
                    <Link :href="route('profile.setup.create')">
                    <DropdownMenuItem class="bg-yellow-100/40 ">
                        <MailWarning class="text-yellow-700" />
                        <h1 class="text-yellow-700 font-semibold">
                            Complete your profile.
                        </h1>
                    </DropdownMenuItem>
                    </Link>
                </DropdownMenuGroup>

                <DropdownMenuGroup v-if="!page.props.auth.isPhoneNumberVerified">
                    <Link :href="route('mobile.verification.notice', {
                        username: page.props.auth.user.username
                    })">
                    <DropdownMenuItem class="bg-red-100/40 ">
                        <MailWarning class="text-red-700" />
                        <h1 class="text-red-700 font-semibold">
                            Verify phone number
                        </h1>
                    </DropdownMenuItem>
                    </Link>
                </DropdownMenuGroup>
            </DropdownMenuLabel>
            <DropdownMenuSeparator />
            <DropdownMenuGroup>
                <Link :href="route('proposal.index')">
                <DropdownMenuItem>
                    <HandCoins />
                    Proposals and offers
                </DropdownMenuItem>
                </Link>
            </DropdownMenuGroup>


            <DropdownMenuGroup>
                <Link :href="route('user.jobs')">
                <DropdownMenuItem>
                    <Scroll />
                    Posted jobs
                </DropdownMenuItem>
                </Link>
            </DropdownMenuGroup>
            <DropdownMenuSeparator />
            <DropdownMenuGroup>
                <DropdownMenuItem>
                    <BadgeCheck />
                    Account
                </DropdownMenuItem>
                <DropdownMenuItem>
                    <CreditCard />
                    Billing
                </DropdownMenuItem>
                <DropdownMenuItem>
                    <Bell />
                    Notifications
                </DropdownMenuItem>
            </DropdownMenuGroup>
            <DropdownMenuSeparator />
            <Link :href="route('logout')" class="!cursor-pointer w-full" method="post" as="button">
            <DropdownMenuItem class="cursor-pointer">
                <LogOut />
                Log out
            </DropdownMenuItem>
            </Link>
        </DropdownMenuContent>
    </DropdownMenu>
</template>

<script setup>
import { BadgeCheck, Bell, ChevronDown, CreditCard, HandCoins, LogOut, MailWarning, Scroll, Sparkles } from 'lucide-vue-next';
import { DropdownMenuGroup, DropdownMenuItem, DropdownMenuSeparator, DropdownMenuTrigger } from './ui/dropdown-menu';
import DropdownMenuContent from './ui/dropdown-menu/DropdownMenuContent.vue';
import DropdownMenuLabel from './ui/dropdown-menu/DropdownMenuLabel.vue';
import Avatar from './ui/avatar/Avatar.vue';
import AvatarImage from './ui/avatar/AvatarImage.vue';
import AvatarFallback from './ui/avatar/AvatarFallback.vue';
import DropdownMenu from './ui/dropdown-menu/DropdownMenu.vue';
import { usePage } from '@inertiajs/vue3';
import UserBalanceDialog from './UserBalanceDialog.vue';

const page = usePage();

</script>