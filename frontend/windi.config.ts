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
  safelist: 'bg-white bg-black bg-contrast bg-$highlight-contrast btn-dropdown ring-black border-black text-black ring-white text-white text-contrast text-$higlight-contrast fill-white fill-contrast fill-$highlight-contrast opacity-100',
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
      transitionTimingFunction: {
        'in-expo': 'cubic-bezier(0.95, 0.05, 0.795, 0.035)',
        'out-expo': 'cubic-bezier(0.19, 1, 0.22, 1)',
      },
      colors: {
        color1: '#FFFF00',
        color2: '#34F9AF',
        color3: '#D377FF',
        color4: '#42B0FF',
        color5: '#FF5BA0',
        grey: '#2E2E2E',
        contrast: '#222329'
      },
      zIndex: {
        'z-1': 'z-1',
        'z-2': 'z-2',
        'z-3': 'z-3',
        'z-4': 'z-4',
        'z-5': 'z-5',
        'z-6': 'z-6',
        'z-7': 'z-7',
        'z-8': 'z-8',
        'z-9': 'z-9',
        'z-10': 'z-10',
        'z-11': 'z-11',
        'z-12': 'z-12',
        'z-13': 'z-13',
        'z-14': 'z-14',
        'z-15': 'z-15',
        'z-16': 'z-16',
        'z-17': 'z-17',
        'z-18': 'z-18',
        'z-19': 'z-19',
        'z-20': 'z-20',
        'z-21': 'z-21',
        'z-22': 'z-22',
        'z-23': 'z-23',
        'z-24': 'z-24',
        'z-25': 'z-25',
        'z-26': 'z-26',
        'z-27': 'z-27',
        'z-28': 'z-28',
        'z-29': 'z-29',
        'z-30': 'z-30',
        'z-31': 'z-31',
        'z-32': 'z-32',
        'z-33': 'z-33',
        'z-34': 'z-34',
        'z-35': 'z-35',
        'z-36': 'z-36',
        'z-37': 'z-37',
        'z-38': 'z-38',
        'z-39': 'z-39',
        'z-40': 'z-40',
        'z-41': 'z-41',
        'z-42': 'z-42',
        'z-43': 'z-43',
        'z-44': 'z-44',
        'z-45': 'z-45',
        'z-46': 'z-46',
        'z-47': 'z-47',
        'z-48': 'z-48',
        'z-49': 'z-49',
        'z-50': 'z-50',
        'z-51': 'z-51',
        'z-52': 'z-52',
        'z-53': 'z-53',
        'z-54': 'z-54',
        'z-55': 'z-55',
        'z-56': 'z-56',
        'z-57': 'z-57',
        'z-58': 'z-58',
        'z-59': 'z-59',
        'z-60': 'z-60',
        'z-61': 'z-61',
        'z-62': 'z-62',
        'z-63': 'z-63',
        'z-64': 'z-64',
        'z-65': 'z-65',
        'z-66': 'z-66',
        'z-67': 'z-67',
        'z-68': 'z-68',
        'z-69': 'z-69',
        'z-70': 'z-70',
        'z-71': 'z-71',
        'z-72': 'z-72',
        'z-73': 'z-73',
        'z-74': 'z-74',
        'z-75': 'z-75',
        'z-76': 'z-76',
        'z-77': 'z-77',
        'z-78': 'z-78',
        'z-79': 'z-79',
        'z-80': 'z-80',
        'z-81': 'z-81',
        'z-82': 'z-82',
        'z-83': 'z-83',
        'z-84': 'z-84',
        'z-85': 'z-85',
        'z-86': 'z-86',
        'z-87': 'z-87',
        'z-88': 'z-88',
        'z-89': 'z-89',
        'z-90': 'z-90',
        'z-91': 'z-91',
        'z-92': 'z-92',
        'z-93': 'z-93',
        'z-94': 'z-94',
        'z-95': 'z-95',
        'z-96': 'z-96',
        'z-97': 'z-97',
        'z-98': 'z-98',
        'z-99': 'z-99',
        'z-100': 'z-100'
      }
    },
  },
  plugins: [
    require('tailwindcss-container-bleed'),
    require('windicss/plugin/scroll-snap'),
  ],
})
