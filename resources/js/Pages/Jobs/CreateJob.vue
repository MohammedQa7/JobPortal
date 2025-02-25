<template>
    <Toaster />
    <div class="balance text-sm text-right font-bold  pt-6">
        Account balance: ${{ page.props.auth.user?.balance }}
    </div>
    <form @submit.prevent="createJob" class="space-y-5">
        <div class="creation-fields space-y-5">
            <div class="grid gap-2">
                <Label for="subject">Title</Label>
                <Input v-model="form.title" id="subject" placeholder="Develop a website" />
                <InputError :message="form.errors.title" />
            </div>
            <div class="grid gap-2">
                <Label for="description">Description</Label>
                <Textarea v-model="form.description" class="min-h-48" id="description"
                    placeholder="Please include all information relevant to your issue." />
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <Label for="area">Project duration</Label>
                    <Input v-model="form.duration" type="number" id="timeline" placeholder="40 days" />
                    <InputError :message="form.errors.duration" />
                </div>
                <div class="space-y-2">
                    <Label for="security-level">Project budget</Label>
                    <Input v-model="form.budget" id="budget" placeholder="$500" />
                    <InputError :message="form.errors.budget" />
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <Label>Requried skills <span class=" font-normal">( max 8 )</span></Label>
                    <TagsInput v-model="form.skills">
                        <TagsInputItem v-for="skill in form.skills" :key="skill" :value="skill">
                            <TagsInputItemText />
                            <TagsInputItemDelete />
                        </TagsInputItem>
                        <TagsInputInput placeholder="skills..." :disabled="form.skills.length >= 8" />
                    </TagsInput>
                    <InputError :message="form.errors.skills" />
                </div>
                <div class="space-y-2">
                    <Label for="security-level">Project category</Label>
                    <Select v-model="form.selectedCategory">
                        <SelectTrigger>
                            <SelectValue placeholder="Select a category" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem v-for="(category, index) in categories.data" :key="index" :value="category.id">
                                {{ category.name }}
                            </SelectItem>
                        </SelectContent>
                    </Select>

                    <InputError :message="form.errors.budget" />
                </div>
            </div>
        </div>

        <div class="grid gap-2">
            <!-- FilePond -->
            <Label for="security-level">Attachments</Label>
            <file-pond name="attachments" ref="filepond" class-name="my-pond" allow-multiple="true"
                label-idle="Drop Or Click to Upload All attachments" v-bind:files="myFiles"
                v-on:init="handleFilePondInit" :server="{
                    url: '',
                    process: {
                        url: route('upload'),
                        method: 'post',
                        onload: handleMultipleFilePondLoad,
                    },
                    revert: handleMultipleFilePondRevert,
                    headers: {
                        'X-CSRF-TOKEN': $page.props.csrf
                    }
                }" />
            <InputError v-if="page.props.errors.attachments" class="mt-2"
                :errorMessage="page.props.errors.attachments" />
        </div>

        <Button class="bg-primaryBtn" :disabled="form.processing"> Save changes </Button>
    </form>

</template>


<script setup>
import { ref, watch } from 'vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import vueFilePond from 'vue-filepond';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.esm.js';
import 'filepond/dist/filepond.min.css';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css';
import MainLayout from '@/Layouts/MainLayout.vue';
import { Input } from '@/components/ui/input';
import Label from '@/components/ui/label/Label.vue';
import Textarea from '@/components/ui/textarea/Textarea.vue';
import InputError from '@/Components/InputError.vue';
import Select from '@/components/ui/select/Select.vue';
import SelectTrigger from '@/components/ui/select/SelectTrigger.vue';
import SelectContent from '@/components/ui/select/SelectContent.vue';
import SelectValue from '@/components/ui/select/SelectValue.vue';
import SelectItem from '@/components/ui/select/SelectItem.vue';
import TagsInput from '@/Components/ui/tags-input/TagsInput.vue';
import TagsInputItem from '@/Components/ui/tags-input/TagsInputItem.vue';
import TagsInputInput from '@/Components/ui/tags-input/TagsInputInput.vue';
import TagsInputItemText from '@/Components/ui/tags-input/TagsInputItemText.vue';
import TagsInputItemDelete from '@/Components/ui/tags-input/TagsInputItemDelete.vue';
import Button from '@/components/ui/button/Button.vue';
import { Toaster } from '@/Components/ui/toast';
import { useToast } from '@/components/ui/toast/use-toast';
import Card from '@/Components/ui/card/Card.vue';
import CardContent from '@/Components/ui/card/CardContent.vue';
const { toast } = useToast();
// Create FilePond component
const FilePond = vueFilePond(FilePondPluginImagePreview);
const filepond = ref();
const myFiles = ref([]);
// ---
const page = usePage();
const propsData = defineProps({
    categories: Array,
});

const form = useForm({
    title: '',
    description: '',
    budget: null,
    duration: '',
    selectedCategory: null,
    skills: ['web dev', 'business'],
    attachments: [],
});


//  filepond init function
function handleFilePondInit() {
    // example of instance method call on pond reference
    filepond.value.getFiles();
};

// Handling multi Image load/store Filepone
function handleMultipleFilePondLoad(response) {
    form.attachments.push(response);
    return response;
}

// Handling multi Image Revert Filepone
function handleMultipleFilePondRevert(attachmentID, load, error) {
    form.attachments = form.attachments.filter((attachment) => attachment !== attachmentID);
    router.post(route('revert'), { attachment_path: attachmentID }, {
        preserveScroll: true,
    });
}

const createJob = () => {
    form.post(route('job.store'), {
        preserveScroll: true,
        onSuccess: () => {
            page.props.auth.user.balance -= form.budget;
        },
        onError: (error) => {
            error.balance
                ? toast({
                    title: error.balance,
                    variant: 'destructive',
                })

                : toast({
                    title: 'Something went wrong, please try again',
                    variant: 'destructive',
                });
        }
    });
}




defineOptions({
    layout: MainLayout,
})

</script>