/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    extend: {},
  },
  daisyui: {
    themes: ["cupcake", "bumblebee", "dracula"],
  },
  plugins: [
    require('daisyui'),
  ],
}

