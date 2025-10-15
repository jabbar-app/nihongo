import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            // Font families with Japanese text support
            fontFamily: {
                sans: ['Inter', 'Hiragino Sans', 'Noto Sans JP', 'Yu Gothic', 'Meiryo', ...defaultTheme.fontFamily.sans],
                japanese: ['Noto Sans JP', 'Hiragino Sans', 'Yu Gothic', 'Meiryo', 'sans-serif'],
                mono: ['JetBrains Mono', 'Courier New', 'monospace'],
            },

            // Japanese-inspired and speaking-focused color palette
            colors: {
                // Speaking-focused brand colors
                brand: {
                    primary: '#4F46E5',
                    secondary: '#6366F1',
                    accent: '#818CF8',
                },
                // Japanese-inspired sakura (cherry blossom) palette
                sakura: {
                    50: '#FFF5F7',
                    100: '#FFE4E9',
                    200: '#FFB7C5',
                    300: '#FF8FA3',
                    400: '#FF6B82',
                    500: '#FF4D6D',
                },
                // Japanese-inspired matcha (green tea) palette
                matcha: {
                    50: '#F0F7ED',
                    100: '#D4E7C5',
                    200: '#B8D89F',
                    300: '#9BC97A',
                    400: '#88B04B',
                    500: '#6B8E23',
                },
                // Audio-specific colors for speaking practice
                audio: {
                    wave: '#60A5FA',
                    playing: '#22C55E',
                    recording: '#DC2626',
                    paused: '#94A3B8',
                },
                // Additional accent colors
                sunset: {
                    orange: '#FF6B35',
                },
                midnight: {
                    blue: '#1A1B4B',
                },
            },

            // Japanese text-optimized font sizes
            fontSize: {
                'japanese-lg': ['1.125rem', { lineHeight: '1.75' }],
                'japanese-xl': ['1.5rem', { lineHeight: '1.75' }],
                'japanese-2xl': ['2rem', { lineHeight: '1.5' }],
            },

            // Custom spacing tokens including safe area insets
            spacing: {
                'safe-bottom': 'env(safe-area-inset-bottom)',
                'safe-top': 'env(safe-area-inset-top)',
            },

            // Speaking-focused animations
            animation: {
                'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                'bounce-subtle': 'bounce-subtle 0.6s ease-in-out',
                'slide-up': 'slide-up 0.3s ease-out',
                'fade-in': 'fade-in 0.4s ease-in',
                'glow': 'glow 2s ease-in-out infinite',
                'wave': 'wave 1.5s ease-in-out infinite',
            },

            // Custom keyframes for animations
            keyframes: {
                'bounce-subtle': {
                    '0%, 100%': { transform: 'translateY(0)' },
                    '50%': { transform: 'translateY(-10px)' },
                },
                'slide-up': {
                    '0%': { transform: 'translateY(20px)', opacity: '0' },
                    '100%': { transform: 'translateY(0)', opacity: '1' },
                },
                'fade-in': {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                'glow': {
                    '0%, 100%': { boxShadow: '0 0 5px rgba(79, 70, 229, 0.5)' },
                    '50%': { boxShadow: '0 0 20px rgba(79, 70, 229, 0.8)' },
                },
                'wave': {
                    '0%, 100%': { transform: 'scaleY(1)' },
                    '50%': { transform: 'scaleY(1.5)' },
                },
            },

            // Box shadows for depth
            boxShadow: {
                'audio': '0 4px 12px rgba(79, 70, 229, 0.3)',
                'card-hover': '0 6px 12px rgba(79, 70, 229, 0.3)',
            },

            // Border radius for consistency
            borderRadius: {
                'card': '8px',
            },
        },
    },

    plugins: [forms],
};
