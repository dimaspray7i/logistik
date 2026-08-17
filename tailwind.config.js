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
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                poppins: ['Poppins', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Logistik CRM Color Palette
                primary: '#D6453D',   // Primary Red
                cream: '#F5F1E6',     // Cream Soft Background
                warning: '#F2C94C',   // Yellow Warning
                success: '#168F4B',   // Green Success
                info: '#2D5DA8',      // Blue Info
                
                // Muted & Neutral Semantic Colors
                body: '#111827',
                muted: '#6B7280',
                border: '#E5E7EB',
                
                // Semantic Brand Group
                brand: {
                    red: '#D6453D',
                    cream: '#F5F1E6',
                    yellow: '#F2C94C',
                    green: '#168F4B',
                    blue: '#2D5DA8',
                    dark: '#111827',
                    muted: '#6B7280',
                    border: '#E5E7EB',
                }
            },
            borderRadius: {
                'btn': '12px',    // Buttons & Form Fields
                'card': '20px',   // Cards & Modals
                'badge': '999px', // Pill Badges
            },
            boxShadow: {
                'soft': '0 4px 20px -2px rgba(0, 0, 0, 0.05)',
                'card': '0 10px 30px -5px rgba(0, 0, 0, 0.04)',
            }
        },
    },

    plugins: [forms],
};