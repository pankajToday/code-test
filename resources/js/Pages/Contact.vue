<template>

    <Head title="Contacts" />

    <div class="p-6 bg-gray-200 ">



        <!-- Top Actions Bar -->
        <div class="flex justify-between mb-6 ">
            <h1 class="text-2xl font-bold text-gray-800">Contact </h1>
            <button @click="showImportModal = true"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Import Contacts
            </button>
        </div>



        <!-- Add Contact Form -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Name</label>
                    <input v-model="contact.name" type="text" :class="{ 'has-error': errors.name }"
                        class="mt-1 p-2 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-200 focus:ring-blue-200">
                    <span class="error" v-if="errors.name">{{ errors.name[0] }}</span>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Country Code</label>
                    <input v-model="contact.country_code" type="text" :class="{ 'has-error': errors.name }"
                        class="mt-1 p-2 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-200 focus:ring-blue-200">
                    <span class="error" v-if="errors.country_code">{{ errors.country_code[0] }}</span>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Number</label>
                    <input v-model="contact.number" type="text" :class="{ 'has-error': errors.name }"
                        class="mt-1 p-2 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-200 focus:ring-blue-200">
                    <span class="error" v-if="errors.number">{{ errors.number[0] }}</span>
                </div>
            </div>
            <button @click="createContact"
                class="mt-4 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition  cursor-pointer">
                Save
            </button>
        </div>



        <!-- Contacts Table -->
        <div class="bg-white rounded-lg shadow">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Country Code</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Number</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="contact in contacts.data" :key="contact.id">
                        <td class="px-6 py-4 whitespace-nowrap">{{ contact.name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ contact.country_code }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ contact.number }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">

                            <button @click="showUpdateModal = true, getContact(contact)"
                                class="text-blue-600 hover:text-blue-900 mr-2">Edit</button>

                            <button @click="deleteContact(contact.id)"
                                class="text-red-600 hover:text-red-900">Delete</button>

                        </td>
                    </tr>
                </tbody>
            </table>


            <div class="px-6 py-4 bg-white border-t">
                <div class="flex justify-between items-center">
                    <div class="text-sm text-gray-600">
                        Showing {{ contacts.from }} to {{ contacts.to }} of {{ contacts.total }} results
                    </div>
                    <div class="flex space-x-2">
                        <template v-for="link in contacts.links" :key="link.label">
                            <button 
                                @click="changePage(link.url)"
                                :disabled="!link.url"
                                :class="[
                                    'px-3 py-1 rounded cursor-pointer',
                                    link.active ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700',
                                    !link.url && 'opacity-50 cursor-not-allowed'
                                ]"
                                v-html="link.label"
                            ></button>
                        </template>
                    </div>
                </div>
            </div>


        </div>
    </div>


    <!-- Import Modal -->
    <div v-if="showImportModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg w-1/2">
            <h2 class="text-xl font-bold mb-4">Import Contacts</h2>

            <input type="file" 
                ref="fileInput" 
                @change="handleFileChange" 
                accept=".xml"
                class="mb-4">

            <div class="flex justify-end gap-2">
                <button @click="showImportModal = false"
                    class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 transition  cursor-pointer">Cancel</button>
                <button  @click="handleFileChange($event)"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition  cursor-pointer">Import</button>
            </div>
        </div>
    </div>

    <!-- Update Modal -->
    <div v-if="showUpdateModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="p-6 rounded-lg w-1/2 bg-white">
            <div class="bg-gray-200 rounded-lg">
                <h2 class="text-xl font-bold mb-4 p-4 ">Update Contact</h2>
            </div>

            <div class="bg-gray-200  space-y-4 p-6 rounded-lg w-full">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Name</label>
                    <input v-model="contact.name" type="text" :class="{ 'has-error': errors.name }"
                        class="mt-1 p-2 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-200 focus:ring-blue-200">
                    <span class="error" v-if="errors.name">{{ errors.name[0] }}</span>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Country Code</label>
                    <input v-model="contact.country_code" type="text" :class="{ 'has-error': errors.name }"
                        class="mt-1 p-2 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-200 focus:ring-blue-200">
                    <span class="error" v-if="errors.country_code">{{ errors.country_code[0] }}</span>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Number</label>
                    <input v-model="contact.number" type="text" :class="{ 'has-error': errors.name }"
                        class="mt-1 p-2 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-200 focus:ring-blue-200">
                    <span class="error" v-if="errors.number">{{ errors.number[0] }}</span>
                </div>
            </div>

            <div class="flex justify-end gap-2 mt-4">
                <button @click="showUpdateModal = false"
                    class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 transition cursor-pointer">Cancel</button>
                <button @click="updateContact"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition  cursor-pointer">Update</button>
            </div>
        </div>

    </div>

</template>

<script setup>

import { ref } from 'vue'
import { Head } from '@inertiajs/vue3'
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import Swal from 'sweetalert2'

const showImportModal = ref(false);
const showUpdateModal = ref(false);
const contact = ref({
    id: null,
    name: '',
    country_code: '',
    number: '',
});
const errors = ref({ name: null, country_code: null, number: null })
const fileInput = ref(null);
const page = ref(1);
const contacts = ref({
    data: [],
    links: [],
    current_page: 1,
    from: 0,
    to: 0,
    total: 0
});



const fetchContacts = () => {
    axios.get(`/api/contact?page=${page.value}`)
        .then(response => {
            contacts.value.data = response.data.data
            contacts.value.from =  response.data.meta.from
            contacts.value.to =  response.data.meta.to
            contacts.value.total =  response.data.meta.total
            contacts.value.links =  response.data.meta.links
        })
        .catch(error => {
            console.error('Error fetching contacts', error)
        })
}

const createContact = () => {
    axios.post('/api/contact', contact.value)
        .then(response => {

            fetchContacts()

            contact.value = {
                id: null,
                name: '',
                country_code: '',
                number: '',
            }


            toast.success('Contact created successfully!');
            errors.value = { name: null, country_code: null, number: null }

        })
        .catch(error => {
            if (error.response?.status === 422) {
                errors.value = error.response.data.errors;
                toast.error('Please check the form for errors');
            } else {
                toast.error('Something went wrong. try after sometime.');
            }
        })
}

const getContact = (data) => {

    contact.value = {
        id: data.id,
        name: data.name,
        country_code: data.country_code,
        number: data.number,
    };

}

const updateContact = () => {
    axios.put(`/api/contact/${contact.value.id}`, contact.value)
        .then(response => {

            fetchContacts();

            contact.value = {
                id: null,
                name: '',
                country_code: '',
                number: '',
            };

            showUpdateModal.value = false;
            toast.success('Contact updated successfully');
            errors.value = { name: null, country_code: null, number: null }
        })
        .catch(error => {
            toast.error('Something went wrong. try after sometime.');
        })
}

const deleteContact = (id) => {

    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes!"
    }).then((result) => {
        if (result.isConfirmed) {
            axios.delete(`/api/contact/${id}`)
                .then(response => {

                    fetchContacts();

                    toast.success('Contact deleted successfully');
                })
                .catch(error => {

                    console.error('Error deleting contact:', error)
                    toast.error('Something went wrong. try after sometime.');

                })
        }
    });

    
}

const handleFileChange = (event) => { 
    console.log( 're', !event.target.files   ) 
    if(  event.target.files &&  event.target.files[0].type !== 'text/xml'  ) {
        toast.error('Please select a valid XML file');
        return ;
    }
    else if( !event.target.files  ){
        toast.error('Please select a XML file');
        return ;
    }
  

    const file = event.target.files[0]; 

    
    if (file && file.type === 'text/xml') {
        uploadFile(file);
    } else {
        toast.error('Please select a valid XML file');
    }
};

const uploadFile = (file) => {
    const formData = new FormData();
    formData.append('file', file);

    axios.post('/api/contact/import-xml', formData, {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    })
    .then(response => {

        fetchContacts();
        showImportModal.value = false;
        toast.success('Contacts imported successfully!');
        if (fileInput.value) {
            fileInput.value.value = '';
        }

    })
    .catch(error => {
        toast.error('Error importing contacts. Please try again.');
        console.error('Import error:', error);
    });
};


const changePage = (url) => {
    if (url) {
        page.value = new URL(url).searchParams.get('page');
        fetchContacts(page);
    }
}

// Initial fetch
fetchContacts();


</script>


<style scoped>
.error {
    color: #dc2626;
    font-size: 0.875rem;
    margin-top: 0.25rem;
    display: block;
    font-weight: 500;
}

.error:hover {
    color: #b91c1c;
}

input.has-error {
    border-color: #dc2626;
    background-color: #fef2f2;
}

.pagination-link {
    @apply px-3 py-1 rounded;
}

.pagination-link:disabled {
    @apply opacity-50 cursor-not-allowed;
}
</style>
