<template>
    <a href="" @click.prevent="getLink" class="inline-block text-sm p-3 text-indigo-500 font-medium">Get sharable link</a>
</template>

<script>
import axios from 'axios'
import { mapActions } from 'vuex'

export default {
    props: {
        file: {
            required: true,
            type: Object
        }
    },

    methods: {
        ...mapActions({
            snack: 'snack/snack'
        }),

        copyToClipboard (value) {
            let dummy = document.createElement('input')
            dummy.value = value
            document.body.appendChild(dummy)
            dummy.select()
            document.execCommand('copy')
            document.body.removeChild(dummy)

            this.snack('Copied to clipboard!')
        },

        async getLink () {
            let response = await axios.post(`api/files/${this.file.uuid}/links`)

            this.copyToClipboard(response.data.data.url)
        }
    }
}
</script>