<template>
<span class="inline-block text-sm font-medium text-gray-800 text-center bg-gray-200 rounded-lg py-1 px-2">
    Usage: {{ usageFormatted }} / {{ storageFormatted }} ({{ percentageUsed }}%) 
</span>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
    computed: {
        ...mapGetters({
            usage: 'usage/usage',
            user: 'auth/user'
        }),

        usageFormatted () {
            return this.formatBytes(this.usage)
        },

        storageFormatted () {
            return this.formatBytes(this.user.plan.storage)
        },

        percentageUsed () {
            return ((this.usage / this.user.plan.storage) * 100).toFixed(1)
        }
    },

    methods: {
        ...mapActions({
            getUsage: 'usage/getUsage'
        }),

        formatBytes(a,b){if(0==a)return"0 B";var c=1024,d=b||2,e=["Bytes","KB","MB","GB","TB","PB","EB","ZB","YB"],f=Math.floor(Math.log(a)/Math.log(c));return parseFloat((a/Math.pow(c,f)).toFixed(d))+" "+e[f]}
    },

    mounted () {
        this.getUsage()
    }
}
</script>