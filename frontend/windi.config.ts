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
    fontSize: {
      'sm': ['12px', '15px'],
      'base': ['15px', '19px'],
      'lg': ['17px', '19px'],
      'xl': ['22px', '30px'],
      '2xl': ['25px', '30px'],
      '3xl': ['30px', '39px']
    },
    extend: {
      colors: {
        primary: '#FFFF00',
        grey: '#2E2E2E'
      },
    },
  }
})