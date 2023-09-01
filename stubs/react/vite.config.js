import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [react()],
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
        'main.jsx': './resources/js/main.jsx'
      }
    }
  }
})