module.exports = {
  future: {
    // removeDeprecatedGapUtilities: true,
    // purgeLayersByDefault: true,
  },
  purge: {
    content: [
      './resources/views/**/*.blade.php',
      './resources/views/**/*.wire.blade.php',
    ],
  },
  theme: {
    extend: {
        colors: {
            twitter: {
                50: '#F4FAFE',
                100: '#E8F6FE',
                200: '#C7E8FC',
                300: '#A5D9FA',
                400: '#61BDF6',
                500: '#1DA1F2',
                600: '#1A91DA',
                700: '#116191',
                800: '#0D486D',
                900: '#093049',
            },
        },
        fontFamily: {
          handwritten: [
              '"Permanent Marker"',
              '"Comic Sans MS"',
              'cursive',
              'sans-serif',
          ],
        },
        minHeight: {
            '0': '0',
            'xs': '20rem',
            'sm': '30rem',
            'md': '40rem',
            'lg': '50rem',
            'xl': '60rem',
        },
    },
  },
  variants: {},
  plugins: [
    require('@tailwindcss/ui'),
    require('@tailwindcss/custom-forms'),
    require('@tailwindcss/typography'),
  ],
}
