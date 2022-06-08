import { defineConfig } from 'windicss/helpers'
export default defineConfig({
  attributify: {
    prefix: 'w:',
  },
  extract: {
    include: ['**/*.{vue,html,jsx,tsx}'],
    exclude: ['node_modules', '.git'],
  },
  shortcuts: {
    'btn-primary': 'block rounded-full border-1  p-2 text-center bg-color1 border-color1 text-black',
    'btn-outline': 'block rounded-full border-1  p-2 text-center bg-transparent border-white text-white'
  },
  theme: {
    container: {
      center: true,
    },
    fontFamily: {
      sans: ['Manrope', 'sans-serif'],
    },
    fontSize: {
      'xs':   ['11px', '14px'],
      'sm':   ['12px', '15px'],
      'base': ['15px', '19px'],
      'lg':   ['17px', '19px'],
      'xl':   ['22px', '30px'],
      '2xl':  ['25px', '30px'],
      '3xl':  ['30px', '39px']
    },
    extend: {
      colors: {
        'color1': '#FFFF00',
        'color2': '#34F9AF',
        'color3': '#D377FF',
        'color4': '#42B0FF',
        'color5': '#FF5BA0',
        'grey': '#2E2E2E'
      },
    },
  }
})