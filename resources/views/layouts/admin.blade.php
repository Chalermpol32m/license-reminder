<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>License System</title>

<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://unpkg.com/alpinejs" defer></script>

<script>
tailwind.config = { darkMode: 'class' }
</script>

</head>


<body class="bg-gray-100 dark:bg-gray-900 transition">

<div class="flex min-h-screen">


<!-- SIDEBAR -->
<aside class="w-64 bg-white dark:bg-gray-800 shadow-lg p-6 flex flex-col">

<h2 class="text-2xl font-bold mb-8 dark:text-white">
🚗 License App
</h2>


<nav class="space-y-2 text-lg">


<!-- Dashboard -->

<a href="{{ route('dashboard') }}"
class="flex items-center gap-2 px-4 py-2 rounded-lg
{{ request()->routeIs('dashboard') 
? 'bg-blue-600 text-white' 
: 'hover:bg-gray-200 dark:hover:bg-gray-700 dark:text-white' }}">
📊 Dashboard
</a>


<!-- Licenses -->

<a href="{{ route('licenses.index') }}"
class="flex items-center gap-2 px-4 py-2 rounded-lg
{{ request()->routeIs('licenses.index') || request()->routeIs('licenses.create') || request()->routeIs('licenses.edit')
? 'bg-blue-600 text-white'
: 'hover:bg-gray-200 dark:hover:bg-gray-700 dark:text-white' }}">
📋 Licenses
</a>


<!-- Gallery -->

<a href="{{ route('gallery') }}"
class="flex items-center gap-2 px-4 py-2 rounded-lg
{{ request()->routeIs('gallery')
? 'bg-blue-600 text-white'
: 'hover:bg-gray-200 dark:hover:bg-gray-700 dark:text-white' }}">
🖼 Gallery
</a>

</nav>



<!-- Logout -->

<form method="POST" action="{{ route('logout') }}" class="mt-auto pt-10">
@csrf

<button class="text-red-500 hover:underline">
🚪 Logout
</button>

</form>

</aside>



<!-- MAIN CONTENT -->

<main class="flex-1 p-10">


<!-- TOP BAR -->

<div class="flex justify-end mb-6">

<div class="relative">

<button id="bellButton" class="text-2xl relative">
🔔


@if( (($expired ?? 0) + ($alert3 ?? 0) + ($alert7 ?? 0) + ($alert15 ?? 0)) > 0 )

<span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs px-2 rounded-full">

{{ ($expired ?? 0) + ($alert3 ?? 0) + ($alert7 ?? 0) + ($alert15 ?? 0) }}

</span>

@endif


</button>



<!-- DROPDOWN -->

<div id="bellDropdown"
class="hidden absolute right-0 mt-3 w-64 bg-white dark:bg-gray-800 rounded-lg shadow-lg p-4">

<p class="font-semibold mb-2 dark:text-white">
การแจ้งเตือน
</p>


<p class="text-gray-500 text-sm">
❌ หมดอายุแล้ว : {{ $expired ?? 0 }}
</p>


<p class="text-red-500 text-sm">
🚨 ภายใน 3 วัน : {{ $alert3 ?? 0 }}
</p>


<p class="text-yellow-500 text-sm">
⚠ ภายใน 7 วัน : {{ $alert7 ?? 0 }}
</p>


<p class="text-blue-500 text-sm">
📅 ภายใน 15 วัน : {{ $alert15 ?? 0 }}
</p>


<a href="{{ route('licenses.index') }}"
class="text-blue-500 text-sm mt-3 block hover:underline">

ดูรายการทั้งหมด →

</a>

</div>

</div>

</div>



@yield('content')


</main>

</div>



<script>

const bellBtn = document.getElementById("bellButton");
const dropdown = document.getElementById("bellDropdown");

bellBtn.addEventListener("click", function(){

dropdown.classList.toggle("hidden");

});

</script>


</body>
</html>