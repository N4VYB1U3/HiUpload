<template>
    <div class="flex items-center justify-between border-b-2 border-gray-100">
        <div class="text-sm truncate overflow-ellipsis w-6/12">
            {{ file.name }}
        </div>
        <div class="-mr-3 flex items-center">
            <app-file-link :file="file" />
            <a @click.prevent="deleteFile" href="" class="inline-block text-sm p-3 text-pink-500 font-medium">Delete</a>
        </div>
    </div>
</template>

<script>
import { mapActions, mapMutations } from 'vuex'
import AppFileLink from '@/components/AppFileLink'
export default {
    components: { 
        AppFileLink, 
    },

    props: {
        file: {
            required: true,
            type: Object
        }
    },

    methods: {
        ...mapActions({
            deleteFileAction: 'files/deleteFile',
            snack: 'snack/snack'
        }),

        ...mapMutations({
            decrementUsage: "usage/DECREMENT_USAGE"
        }),

        async deleteFile () {
            if (window.confirm('Are you sure you want to delete this file?')) {
                await this.deleteFileAction(this.file.uuid)
                this.snack('File was Deleted')
                this.decrementUsage(this.file.size)
            }
        }
    }
}
</script>