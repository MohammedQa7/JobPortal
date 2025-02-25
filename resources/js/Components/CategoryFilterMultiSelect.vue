<template>
    <Popover v-model:open="open" class="">
        <PopoverTrigger as-child>
            <Button variant="outline" size="sm" class="w-full justify-between items-center flex">
                <p v-if="selectedCategories.length <= 0">Select Cateogries </p>
                <div class="flex justify-center items-center">
                    <Badge v-if="selectedCount" class="ms-2">
                        {{ selectedCategories.length }} selected</Badge>
                </div>
                <ChevronDown class="size-8  transition-all" :class="{ 'rotate-180': open }" />
            </Button>
        </PopoverTrigger>

        <PopoverContent class="p-0 " side="bottom">
            <Command>
                <CommandInput placeholder="search by name..." />
                <CommandList>
                    <CommandEmpty>No results found.</CommandEmpty>
                    <CommandGroup>
                        <CommandItem v-for="category in categories" :key="category.slug" :value="category.name"
                            @select="SelectItems(category.slug)" class="flex justify-between items-center"
                            :class="{ 'bg-muted': selectedCategories.includes(category) }">

                            <div class="members-details flex items-center gap-2">
                                <div class="flex flex-col items-start">
                                    <span>{{ category.name }}</span>
                                </div>
                            </div>

                            <Check v-show="selectedCategories.some(obj => obj == category.slug)" class="size-4" />

                        </CommandItem>
                    </CommandGroup>
                </CommandList>
            </Command>
        </PopoverContent>
    </Popover>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import Popover from './ui/popover/Popover.vue';
import PopoverTrigger from './ui/popover/PopoverTrigger.vue';
import Button from './ui/button/Button.vue';
import Badge from './ui/badge/Badge.vue';
import PopoverContent from './ui/popover/PopoverContent.vue';
import Command from './ui/command/Command.vue';
import CommandInput from './ui/command/CommandInput.vue';
import CommandList from './ui/command/CommandList.vue';
import CommandEmpty from './ui/command/CommandEmpty.vue';
import CommandGroup from './ui/command/CommandGroup.vue';
import CommandItem from './ui/command/CommandItem.vue';
import { Check, ChevronDown } from 'lucide-vue-next';
const emit = defineEmits();
const open = ref(false);
const propsData = defineProps({
    categories: Array,
    preSelectedCategories: Array
})

const selectedCategories = ref([]);

const SelectItems = (selectedCategory) => {
    console.log(selectedCategory);

    if (!selectedCategories.value.some(obj => obj == selectedCategory)) {
        selectedCategories.value.push(selectedCategory);
        emitCategoryToParent();

    } else {
        const clickedIndex = selectedCategories.value.findIndex(item => item === selectedCategory);
        selectedCategories.value.splice(clickedIndex, 1);
        emitCategoryToParent();
    }
}

const selectedCount = computed(() => {
    return selectedCategories.value.length > 0;
})


const emitCategoryToParent = () => {
    emit('bindSelectedCategories', selectedCategories.value);
}

onMounted(() => {
    propsData.preSelectedCategories.length > 0
        ? selectedCategories.value = propsData.preSelectedCategories
        : selectedCategories.value;
})
</script>