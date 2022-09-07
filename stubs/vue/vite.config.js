import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [vue()],
  root: './resources/',
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
        'main.js': './resources/js/main.js'
      }
    }
  },
  server: {
    port: 3000
  }
})
