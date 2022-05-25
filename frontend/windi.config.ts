import { defineConfig } from 'windicss/helpers'
export default defineConfig({
  attributify: {
    prefix: 'w:',
  },
  extract: {
    include: ['**/*.{vue,html,jsx,tsx}'],
    exclude: ['node_modules', '.git'],
  },
  theme: {
    container: {
      center: true,
    },
    fontFamily: {
      sans: ['Manrope', 'sans-serif'],
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
        primary: '#FFFF00',
        grey: '#2E2E2E'
      },
    },
  }
})