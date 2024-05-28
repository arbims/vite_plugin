import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [vue()],
  root: './assets/',
  base: '/assets/',
  build: {
    outDir: '../webroot/assets',
    assetsDir: '',
    manifest: true,
    rollupOptions: {
      output: {
        manualChunks: undefined
      },
      input: {
        'main.js': './assets/js/main.js'
      }
    }
  },
  server: {
    port: 3000
  }
})
