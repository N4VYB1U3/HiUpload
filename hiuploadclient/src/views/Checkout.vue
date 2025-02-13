<template>
  <form action="" @submit.prevent="submit">
      <div class="mb-6">
        <label for="name" class="inline-block text-sm mb-2">Your name</label>
        <input v-model="form.name" type="text" id="name" class="w-full border-2 border-gray-200 h-10 px-3 rounded-md">
      </div>
      <div class="mb-6">
        <label for="card" class="inline-block text-sm mb-2">Card details</label>
        <div id="card" ref="card"></div>
      </div>
      <app-button type="submit" title="Subscribe" :loading="loading" :disabled="loading" />
  </form>
</template>

<script>
// @ is an alias to /src
/* global Stripe */
import axios from 'axios'
import { mapActions } from 'vuex'
import AppButton from '@/components/AppButton'

const stripe = Stripe(process.env.VUE_APP_STRIPE_KEY)
const elements = stripe.elements()
const cardElement = elements.create('card')

export default {
    components: {
        AppButton,
    },

    props: {
        plan: {
            required: true,
            type: String
        }
    },

    data () {
        return {
            loading: false,

            intent: {
                client_secret: null
            },

            form: {
                name: ''
            }
        }
    },

    methods: {
        ...mapActions({
            me: 'auth/me',
            snack: 'snack/snack'
        }),

        async submit () {
            this.loading = true

            const { setupIntent, error } = await stripe.confirmCardSetup(
                this.intent.client_secret, {
                    payment_method: {
                        card: cardElement, 
                        billing_details: { name: this.form.name }
                    }
                }
            )

            if (error) {
                this.loading = false
            } else {
                await this.createSubscription(setupIntent.payment_method)
                this.loading = false
            }
        },

        async createSubscription (token) {
            let response = await axios.post('api/subscriptions', { plan: this.plan, token })
            await this.me()

            this.snack('Subscription created. Thank you!')
            this.$router.replace({ name: 'account' })
        }
    },

    async mounted () {
        let response = await axios.get('api/subscriptions/intent')
        this.intent.client_secret = response.data.data.client_secret

        cardElement.mount(this.$refs.card)
    },
}
</script>
