import { TinyColor, readability } from '@ctrl/tinycolor'

export const useContrastColor = (color) => {
  const bgColor = new TinyColor(color)
  const fgColor = new TinyColor('#FFFFFF')
  const altfgColor = new TinyColor('#222329')

  const test1 = readability(bgColor, fgColor)
  const test2 = readability(bgColor, altfgColor)

  return (test1 < test2) ? '#222329' : '#FFFFFF'
}

export const useContrastColorClass = (color) => {
  const bgColor = new TinyColor(color)
  const fgColor = new TinyColor('#FFFFFF')
  const altfgColor = new TinyColor('#222329')

  const test1 = readability(bgColor, fgColor)
  const test2 = readability(bgColor, altfgColor)

  return (test1 < test2) ? 'contrast' : 'white'
}
