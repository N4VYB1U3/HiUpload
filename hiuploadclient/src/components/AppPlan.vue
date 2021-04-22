<template>
    <div class="border-2 border-gary-200 rounded-lg px-6 py-4 flex items-center justify-between">
        <div class="flex items-center">
            <h2 class="font-bold text-lg mr-4 text-indigo-500">{{ plan.name }}</h2>
            <span class="text-sm text-gray-800">{{ storageFormatted }}</span>
        </div>

        <span class="font-medium">${{ parseInt(plan.price, 10) / 100 }} / month</span>
    </div>
</template>

<script lang="ts">
export default {
    props: {
        plan: {
            required: true,
            type: Object
        }
    }, 

    computed: {
        storageFormatted () {
            return this.formatBytes(this.plan.storage)
        },
    },

    methods: {
        formatBytes(a,b){if(0==a)return"0 B";var c=1024,d=b||2,e=["Bytes","KB","MB","GB","TB","PB","EB","ZB","YB"],f=Math.floor(Math.log(a)/Math.log(c));return parseFloat((a/Math.pow(c,f)).toFixed(d))+" "+e[f]}
    }
}
</script>