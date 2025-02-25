<template>
    <Toaster />
    <nav
        class="fixed  inset-x-0 mx-auto top-6 z-30 flex justify-between items-center  w-full  max-w-screen-md  lg:max-w-screen-xl  border rounded-xl bg-primary-foreground/40 backdrop-blur-sm p-6">

        <!-- Logo -->
        <div class="logo">
            <img class="w-28" :src="Logo" alt="">
        </div>
        <NavigationMenu>
            <NavigationMenuList>
                <!-- <NavigationMenuItem>
                    <NavigationMenuTrigger>Getting started</NavigationMenuTrigger>
                    <NavigationMenuContent>
                        <ul
                            class="grid gap-3 p-6 md:w-[400px] lg:w-[500px] lg:grid-cols-[minmax(0,.75fr)_minmax(0,1fr)]">
                            <li class="row-span-3">
                                <NavigationMenuLink as-child>
                                    <a class="flex h-full w-full select-none flex-col justify-end rounded-md bg-gradient-to-b from-muted/50 to-muted p-6 no-underline outline-none focus:shadow-md"
                                        href="/">
                                        <img src="https://www.radix-vue.com/logo.svg" class="h-6 w-6">
                                        <div class="mb-2 mt-4 text-lg font-medium">
                                            shadcn/ui
                                        </div>
                                        <p class="text-sm leading-tight text-muted-foreground">
                                            Beautifully designed components built with Radix UI and
                                            Tailwind CSS.
                                        </p>
                                    </a>
                                </NavigationMenuLink>
                            </li>

                            <li>
                                <NavigationMenuLink as-child>
                                    <a href="/docs/introduction"
                                        class="block select-none space-y-1 rounded-md p-3 leading-none no-underline outline-none transition-colors hover:bg-accent hover:text-accent-foreground focus:bg-accent focus:text-accent-foreground">
                                        <div class="text-sm font-medium leading-none">Introduction</div>
                                        <p class="line-clamp-2 text-sm leading-snug text-muted-foreground">
                                            Re-usable components built using Radix UI and Tailwind CSS.
                                        </p>
                                    </a>
                                </NavigationMenuLink>
                            </li>
                            <li>
                                <NavigationMenuLink as-child>
                                    <a href="/docs/installation"
                                        class="block select-none space-y-1 rounded-md p-3 leading-none no-underline outline-none transition-colors hover:bg-accent hover:text-accent-foreground focus:bg-accent focus:text-accent-foreground">
                                        <div class="text-sm font-medium leading-none">Installation</div>
                                        <p class="line-clamp-2 text-sm leading-snug text-muted-foreground">
                                            How to install dependencies and structure your app.
                                        </p>
                                    </a>
                                </NavigationMenuLink>
                            </li>
                            <li>
                                <NavigationMenuLink as-child>
                                    <a href="/docs/typography"
                                        class="block select-none space-y-1 rounded-md p-3 leading-none no-underline outline-none transition-colors hover:bg-accent hover:text-accent-foreground focus:bg-accent focus:text-accent-foreground">
                                        <div class="text-sm font-medium leading-none">Typography</div>
                                        <p class="line-clamp-2 text-sm leading-snug text-muted-foreground">
                                            Styles for headings, paragraphs, lists...etc
                                        </p>
                                    </a>
                                </NavigationMenuLink>
                            </li>
                        </ul>
                    </NavigationMenuContent>
                </NavigationMenuItem> -->
                <NavigationMenuItem>
                    <NavigationMenuTrigger>Find work</NavigationMenuTrigger>
                    <NavigationMenuContent>
                        <ul class="grid w-[400px] gap-3 p-4 md:w-[500px] md:grid-cols-2 lg:w-[600px] ">
                            <li v-for="component in components" :key="component.title">
                                <NavigationMenuLink as-child>
                                    <Link :href="component.href"
                                        class="flex items-center gap-2 select-none space-y-1 rounded-md p-3 leading-none no-underline outline-none transition-colors hover:bg-accent hover:text-accent-foreground focus:bg-accent focus:text-accent-foreground">
                                    <div class="navigaion-icon self-baseline">
                                        <component :is="component.icon" />
                                    </div>
                                    <div class="navigaion-links space-y-1">
                                        <div class="text-sm font-medium leading-none">{{ component.title }}</div>
                                        <p class="line-clamp-2 text-sm leading-snug text-muted-foreground">
                                            {{ component.description }}
                                        </p>
                                    </div>
                                    </Link>
                                </NavigationMenuLink>
                            </li>
                        </ul>
                    </NavigationMenuContent>
                </NavigationMenuItem>
                <NavigationMenuItem>
                    <Link :href="route('job.index')">
                    <NavigationMenuLink :class="navigationMenuTriggerStyle()">
                        Messages
                    </NavigationMenuLink>
                    </Link>
                </NavigationMenuItem>
            </NavigationMenuList>
        </NavigationMenu>

        <!-- Guest User -->
        <div v-if="!page.props.auth.user" class="flex justify-center items-center gap-4">
            <Link :href="route('login')">
            <Button :variant="'outline'">
                Log in
            </Button>
            </Link>

            <Link :href="route('register')">
            <Button class=" bg-primaryBtn">
                Sign up
            </Button>
            </Link>
        </div>

        <!-- Authenticated User -->
        <div v-else>
            <UserProfileDropdown @toastError="emitErrorMessage" />
        </div>
    </nav>
</template>

<script setup>
import { Button } from '@/components/ui/button';
import {
    NavigationMenu,
    NavigationMenuContent,
    NavigationMenuItem,
    NavigationMenuLink,
    NavigationMenuList,
    NavigationMenuTrigger,
    navigationMenuTriggerStyle,
} from '@/components/ui/navigation-menu'
import { toast, Toaster } from '@/Components/ui/toast';
import UserProfileDropdown from '@/Components/UserProfileDropdown.vue';
import eventBus from '@/Composable/eventBus';
import { usePage } from '@inertiajs/vue3';
import { Binoculars, Scroll, UserSearch } from 'lucide-vue-next';
const Logo = '/Assets/Images/msar.svg';
const page = usePage();
const components = [
    {
        title: 'Publish a job',
        href: route('job.create'),
        description: 'For clients looking to post a job and find the suitable freelancer to work on.',
        icon: Scroll,
    },
    {
        title: 'Find job',
        href: route('job.index'),
        description:
            'For freelancers who is looking for a job',
        icon: Binoculars,
    },
    {
        title: 'Hire freelancer',
        href: '/docs/components/progress',
        description: 'For clients looking to hire and send a direct offer to freelancers.',
        icon: UserSearch
    },
]


eventBus.on('errorMessage', () => {
    toast({
        title: 'something went wrong, Please try again later',
        variant: 'destructive'
    })
})

</script>
