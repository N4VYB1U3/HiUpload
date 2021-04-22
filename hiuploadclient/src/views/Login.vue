<template>
    <div class="w-10/12 md:w-6/12 mx-auto">
      <form action="" @submit.prevent="login" class="mb-8">
        <div class="mb-6">
        <div class="mb-3">
          <label for="email" class="inline-block text-sm mb-2">Email address</label>
          <input v-model="form.email" type="text" id="email" class="w-full border-2 border-gray-200 h-10 px-3 rounded-md">
        </div>
        <div class="mb-3">
          <label for="password" class="inline-block text-sm mb-2">Password</label>
          <input v-model="form.password" type="password" id="password" class="w-full border-2 border-gray-200 h-10 px-3 rounded-md">
        </div>
        </div>

        <app-button type="submit" title="Login" :loading="loading" :disabled="loading"/>
      </form>
      <p class="text-sm text-gray-800">Not joined yet? <a href="" class="text-indigo-500">Create an account</a></p>
    </div>
</template>

<script>
// @ is an alias to /src
import { mapActions } from 'vuex'
import AppButton from '@/components/AppButton'

export default {
  components: {
    AppButton,
  },

  data () {
    return {
      loading: false,
      form: {
      email: '',
      password: '',
      }
    }
  },

  methods: {
    ...mapActions({
      loginAction: 'auth/login'
    }),

    async login () {
      this.loading = true
      try {
        await this.loginAction(this.form)

        this.loading = false
        
        this.$router.replace({ name: 'home' })
      } catch (e) {
        this.loading = false
      }

      
    }
  }
}
</script>
