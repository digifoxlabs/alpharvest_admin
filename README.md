# Create Laravel Project
composer create-project laravel/laravel alpharvest-admin

# Install Tailwind CSS
npm install tailwindcss @tailwindcss/vite

# Install AlpineJs
npm install alpinejs
  # in resources/js/app.js
    import Alpine from 'alpinejs';
    window.Alpine = Alpine;
    Alpine.start();
  # in layout.blade
    <head>
      <!-- ... other head elements ... -->
      @vite(['resources/css/app.css', 'resources/js/app.js'])
     </head>

# Custom script to run both commands Concurrently
First Install
npm install concurrently --save-dev

"scripts": {
  "dev": "vite",
  "serve": "php artisan serve",
  "hot": "concurrently \"php artisan serve\" \"npm run dev\""
}

Run CMD: npm run hot 

# For deployment
npm run build