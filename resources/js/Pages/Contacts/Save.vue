<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import JetButton from '@/Components/Button.vue';
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { Link, useForm } from '@inertiajs/inertia-vue3';
import JetFormSection from '@/Components/FormSection.vue';
import JetInput from '@/Components/Input.vue';
import JetInputError from '@/Components/InputError.vue';
import JetLabel from '@/Components/Label.vue';
import JetActionMessage from '@/Components/ActionMessage.vue';
import JetSecondaryButton from '@/Components/SecondaryButton.vue';
import { createToaster } from "@meforma/vue-toaster";

const props = defineProps({
    user: Object,
    contact: Object,
});

const form = useForm({
    _method: 'POST',
    first_name: props.contact ? props.contact.first_name : '',
    last_name: props.contact ? props.contact.last_name : '',
    email: props.contact ? props.contact.email : '',
    phone: props.contact ? props.contact.phone : '',
    address: props.contact ? props.contact.address : '',
    photo: props.contact ? props.contact.photo : null,
    id: props.contact ? props.contact.id : null,
});

const toaster = createToaster({
    position: "bottom-left",
 });

const verificationLinkSent = ref(null);
const photoPreview = ref(null);
const photoInput = ref(null);

const addContactInformation = () => {
    if (photoInput.value) {
        form.photo = photoInput.value.files[0];
    }

    if (props.contact) {
        form._method = 'PUT'
        form.put(route('contact.update', props.contact.id), {
            errorBag: 'createContactInformation',
            preserveScroll: true,
            onSuccess: () => {
                toaster.success('Contact successfully updated!')
                clearPhotoFileInput()
            },
        });
    } else {
        form.post(route('contact.store'), {
            errorBag: 'createContactInformation',
            preserveScroll: true,
            onSuccess: () => {
                toaster.success('Contact successfully created!')
                clearPhotoFileInput()
            },
        });
    }


};

const selectNewPhoto = () => {
    photoInput.value.click();
};

const updatePhotoPreview = () => {
    const photo = photoInput.value.files[0];

    if (! photo) return;

    const reader = new FileReader();

    reader.onload = (e) => {
        photoPreview.value = e.target.result;
    };

    reader.readAsDataURL(photo);
};

const deletePhoto = () => {
    Inertia.delete(route('current-user-photo.destroy'), {
        preserveScroll: true,
        onSuccess: () => {
            photoPreview.value = null;
            clearPhotoFileInput();
        },
    });
};

const clearPhotoFileInput = () => {
    if (photoInput.value?.value) {
        photoInput.value.value = null;
    }
};
</script>

<template>
    <AppLayout title="Create New Contact">

        <JetFormSection @submitted="addContactInformation">
        <template #title>
            Contact Information
        </template>

        <template #description>
            Add New Contact information
        </template>

        <template #form>
            <!-- Profile Photo -->
            <div v-if="$page.props.jetstream.managesProfilePhotos" class="col-span-6 sm:col-span-4">
                <!-- Profile Photo File Input -->
                <input
                    ref="photoInput"
                    type="file"
                    class="hidden"
                    @change="updatePhotoPreview"
                >

                <JetLabel for="photo" value="Photo" />

                <!-- Current Profile Photo -->
                <div v-show="! photoPreview" class="mt-2">
                    <img :src="user.profile_photo_url" :alt="user.name" class="rounded-full h-20 w-20 object-cover">
                </div>

                <!-- New Profile Photo Preview -->
                <div v-show="photoPreview" class="mt-2">
                    <span
                        class="block rounded-full w-20 h-20 bg-cover bg-no-repeat bg-center"
                        :style="'background-image: url(\'' + photoPreview + '\');'"
                    />
                </div>

                <JetSecondaryButton class="mt-2 mr-2" type="button" @click.prevent="selectNewPhoto">
                    Select A New Photo
                </JetSecondaryButton>

                <JetSecondaryButton
                    v-if="user.profile_photo_path"
                    type="button"
                    class="mt-2"
                    @click.prevent="deletePhoto"
                >
                    Remove Photo
                </JetSecondaryButton>

                <JetInputError :message="form.errors.company_photo" class="mt-2" />
            </div>

            <!-- First Name -->
            <div class="col-span-6 sm:col-span-4">
                <JetLabel for="name" value="First Name" />
                <JetInput
                    id="name"
                    v-model="form.first_name"
                    type="text"
                    class="mt-1 block w-full"
                    autocomplete="name"
                />
                <JetInputError :message="form.errors.first_name" class="mt-2" />
            </div>

            <!-- Last Name -->
            <div class="col-span-6 sm:col-span-4">
                <JetLabel for="name" value="Last Name" />
                <JetInput
                    id="last-name"
                    v-model="form.last_name"
                    type="text"
                    class="mt-1 block w-full"
                    autocomplete="name"
                />
                <JetInputError :message="form.errors.last_name" class="mt-2" />
            </div>

            <!-- Email -->
            <div class="col-span-6 sm:col-span-4">
                <JetLabel for="email" value="Email" />
                <JetInput
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="mt-1 block w-full"
                />
                <JetInputError :message="form.errors.email" class="mt-2" />

                <div v-if="$page.props.jetstream.hasEmailVerification && user.email_verified_at === null">
                    <p class="text-sm mt-2">
                        Your email address is unverified.

                        <Link
                            :href="route('verification.send')"
                            method="post"
                            as="button"
                            class="underline text-gray-600 hover:text-gray-900"
                            @click.prevent="sendEmailVerification"
                        >
                            Click here to re-send the verification email.
                        </Link>
                    </p>

                    <div v-show="verificationLinkSent" class="mt-2 font-medium text-sm text-green-600">
                        A new verification link has been sent to your email address.
                    </div>
                </div>
            </div>

            <!-- Phone -->
            <div class="col-span-6 sm:col-span-4">
                <JetLabel for="name" value="Phone" />
                <JetInput
                    id="phone"
                    v-model="form.phone"
                    type="text"
                    class="mt-1 block w-full"
                    autocomplete="name"
                />
                <JetInputError :message="form.errors.phone" class="mt-2" />
            </div>

            <!-- Address -->
            <div class="col-span-6 sm:col-span-4">
                <JetLabel for="name" value="Address" />
                <textarea id="" rows="5" class="mt-1 block w-full" v-model="form.address"></textarea>
                <JetInputError :message="form.errors.address" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <JetActionMessage :on="form.recentlySuccessful" class="mr-3">
                Saved.
            </JetActionMessage>

            <JetButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                {{ props.contact ? 'Update' : 'Create' }} Contact
            </JetButton>
        </template>
    </JetFormSection>
    </AppLayout>
</template>
