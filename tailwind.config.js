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
                // Palet Warna Logistics CRM
                primary: '#D6453D',   // Red
                cream: '#F5F1E6',     // Cream Background
                warning: '#F2C94C',   // Yellow
                success: '#168F4B',   // Green
                info: '#2D5DA8',      // Blue
                
                // Alias untuk kemudahan semantic
                brand: {
                    red: '#D6453D',
                    cream: '#F5F1E6',
                    yellow: '#F2C94C',
                    green: '#168F4B',
                    blue: '#2D5DA8',
                }
            },
            borderRadius: {
                'btn': '12px',    // Button & Input
                'card': '20px',   // Card & Modal
                'badge': '999px', // Badge pill
            },
            boxShadow: {
                'soft': '0 4px 20px -2px rgba(0, 0, 0, 0.05)',
            }
        },
    },

    plugins: [forms],
};