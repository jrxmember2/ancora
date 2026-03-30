const defaultTheme = require('tailwindcss/defaultTheme');
const daisyui = require('daisyui');

module.exports = {
  content: [
    './app/**/*.php',
    './modules/**/*.php',
    './public/**/*.php',
    './public/assets/js/**/*.js',
    './src/**/*.{css,js}'
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Inter', ...defaultTheme.fontFamily.sans],
      },
      colors: {
        brand: {
          DEFAULT: '#941415',
          strong: '#7f0f10',
          accent: '#c79b61',
        },
      },
      boxShadow: {
        soft: '0 18px 40px rgba(0,0,0,0.14)',
        premium: '0 24px 60px rgba(148,20,21,0.18)',
      },
      borderRadius: {
        '2xl': '1.25rem',
        '3xl': '1.75rem',
      },
    },
  },
  daisyui: {
    logs: false,
    themes: [
      {
        light: {
          'primary': '#941415',
          'primary-content': '#ffffff',
          'secondary': '#c79b61',
          'secondary-content': '#1f1f1f',
          'accent': '#7f0f10',
          'accent-content': '#ffffff',
          'neutral': '#241d1e',
          'neutral-content': '#f6f1f0',
          'base-100': '#ffffff',
          'base-200': '#f8f4f4',
          'base-300': '#efe8e7',
          'base-content': '#241d1e',
          'info': '#2563eb',
          'success': '#059669',
          'warning': '#d97706',
          'error': '#dc2626',
          '--rounded-box': '1.1rem',
          '--rounded-btn': '0.9rem',
          '--rounded-badge': '9999px',
          '--animation-btn': '0.22s',
          '--animation-input': '0.2s',
          '--btn-focus-scale': '0.98',
          '--border-btn': '1px',
          '--tab-border': '1px',
          '--tab-radius': '0.9rem',
        },
      },
      {
        dark: {
          'primary': '#941415',
          'primary-content': '#ffffff',
          'secondary': '#c79b61',
          'secondary-content': '#1f1f1f',
          'accent': '#d1495b',
          'accent-content': '#ffffff',
          'neutral': '#181314',
          'neutral-content': '#f5efee',
          'base-100': '#1c1718',
          'base-200': '#241d1f',
          'base-300': '#31282a',
          'base-content': '#f5efee',
          'info': '#3b82f6',
          'success': '#10b981',
          'warning': '#f59e0b',
          'error': '#ef4444',
          '--rounded-box': '1.1rem',
          '--rounded-btn': '0.9rem',
          '--rounded-badge': '9999px',
          '--animation-btn': '0.22s',
          '--animation-input': '0.2s',
          '--btn-focus-scale': '0.98',
          '--border-btn': '1px',
          '--tab-border': '1px',
          '--tab-radius': '0.9rem',
        },
      },
    ],
  },
  plugins: [daisyui],
};
