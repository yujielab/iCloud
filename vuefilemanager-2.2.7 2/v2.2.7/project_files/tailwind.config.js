module.exports = {
	content: [
		'./resources/js/**/*.{js,jsx,ts,tsx,vue}',
		'./resources/views/vuefilemanager/*.blade.php',
	],
	darkMode: 'class',
	theme: {
		debugScreens: {
			position: ['bottom', 'right'],
		},
		screens: {
			'xs': '420px',
			'sm': '640px',
			'md': '768px',
			'lg': '1024px',
			'xl': '1280px',
			'2xl': '1536px',
		},
		fontFamily: {
			'sans': ['-apple-system', 'BlinkMacSystemFont', 'SF Pro Text', 'Helvetica Neue', 'sans-serif'],
			'display': ['-apple-system', 'BlinkMacSystemFont', 'SF Pro Display', 'Helvetica Neue', 'sans-serif'],
		},
		extend: {
			colors: {
				'apple-gray': {
					50: '#fbfbfd',
					100: '#f5f5f7',
					200: '#e8e8ed',
					300: '#d2d2d7',
					400: '#86868b',
					500: '#6e6e73',
					600: '#1d1d1f',
				},
				'apple-blue': '#0066cc',
				'apple-red': '#ff3b30',
				'apple-green': '#34c759',
				'apple-yellow': '#ffcc00',
			},
			scale: {
				'97': '.97',
			},
			borderWidth: {
				'3': '3px',
			},
			borderRadius: {
				'apple': '12px',
				'apple-sm': '8px',
			},
			backdropBlur: {
				'apple': '20px',
			},
			backgroundColor: theme => ({
				'dark-background': '#000000',
				'dark-foreground': '#1c1c1e',
				'2x-dark-foreground': '#2c2c2e',
				'4x-dark-foreground': '#3a3a3c',
				'light-background': theme('colors.apple-gray.50'),
				'light-300': theme('colors.apple-gray.200'),
			}),
			boxShadow: {
				'apple': '0 4px 16px rgba(0, 0, 0, 0.12)',
				'apple-dark': '0 4px 16px rgba(0, 0, 0, 0.3)',
				'apple-inner': 'inset 0 0 0 0.5px rgba(0, 0, 0, 0.1)',
				'apple-button': '0 1px 2px rgba(0, 0, 0, 0.05)',
			},
			screens: {
				'print': {'raw': 'print'},
			},
		},
	},
	plugins: [
		require('tailwindcss-debug-screens'),
		require('tailwind-scrollbar-hide'),
	],
}
