import { defineConfig } from 'windicss/helpers'

export default defineConfig({
  extract: {
    include: ['**/*.{vue,html,jsx,tsx}'],
    exclude: ['node_modules', '.git'],
  },
  shortcuts: {
    'btn-primary':
      'block rounded-3xl border-1 p-2 text-center bg-color1 border-color1 text-black',
    'btn-highlight':
      'block rounded-3xl border-1 p-2 text-center bg-$highlight border-$highlight text-$highlight-contrast',
    'btn-outline':
      'block rounded-3xl border-1 p-2 text-center bg-transparent border-white text-white',
  },
  safelist: 'bg-white bg-black btn-dropdown',
  theme: {
    container: {
      center: true,
    },
    fontFamily: {
      sans: ['Manrope', 'sans-serif'],
    },
    fontSize: {
      xs: ['11px', '14px'],
      sm: ['12px', '15px'],
      base: ['15px', '19px'],
      lg: ['17px', '19px'],
      xl: ['22px', '30px'],
      '2xl': ['25px', '30px'],
      '3xl': ['30px', '39px'],
      xxl: ['64px', '64px'],
    },
    extend: {
      colors: {
        color1: '#FFFF00',
        color2: '#34F9AF',
        color3: '#D377FF',
        color4: '#42B0FF',
        color5: '#FF5BA0',
        grey: '#2E2E2E',
      },
    },
  },
  plugins: [
    require('tailwindcss-container-bleed'),
    require('windicss/plugin/scroll-snap')
  ],
})
