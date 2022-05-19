import { defineConfig } from 'windicss/helpers'
export default defineConfig({
  extract: {
    include: ['**/*.{vue,html,jsx,tsx}'],
    exclude: ['node_modules', '.git'],
  },
  safeList: 'router-link-active',
  transformCSS: 'pre',
  theme: {
    container: {
      center: true,
    },
    fontFamily: {
      sans: ['Roboto Condensed', 'sans-serif'],
    },
    extend: {
      fontSize: {
        xxs: ['11px', '15px'],
        xs: ['13px', '20px'],
        sm: ['15px', '22px'],
        md: ['16px', '24px'],
        base: ['18px', '34px'],
        lg: ['20px', '30px'],
        xl: ['22px', '30px'],
      },
      colors: {
        primary: '#0070c0',
      },
    },
  }
})