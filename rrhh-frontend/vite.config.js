import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import { resolve } from 'path'

export default defineConfig({
  plugins: [vue()],
  resolve: {
    alias: {
      '@': resolve(__dirname, 'src')
    }
  },
  define: {
    __VUE_OPTIONS_API__: true, // true porque usás Options API
    // __VUE_PROD_DEVTOOLS__: false, // false para producción
    __VUE_PROD_HYDRATION_MISMATCH_DETAILS__: false, // false porque no usás SSR
  }
})
