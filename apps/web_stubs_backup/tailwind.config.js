/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './resources/**/*.vue',
    './resources/**/*.ts',
    './resources/**/*.js',
    './resources/**/*.blade.php',
  ],
  theme: { extend: {} },
  plugins: [require('@tailwindcss/typography')],
}
