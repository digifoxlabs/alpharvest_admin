<!doctype html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
      <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
  </head>
  <body>
    <h1 class="text-3xl font-bold underline">
      Alp Harvest ADMIN Panel!
    </h1>




<div x-data="{ show: false }">
    <button @click="show = ! show">Toggle Content</button>
    <p x-show="show">Alpine.js is working!</p>
</div>



  </body>
</html>