@extends('layouts.admin')

@section('content')

<div class="max-w-7xl mx-auto">

<!-- SEARCH -->

<div class="mb-6">
    <input
        id="searchInput"
        type="text"
        placeholder="🔍 ค้นหาชื่อคนขับ หรือทะเบียน..."
        class="px-4 py-2 border rounded-lg md:w-1/3 w-full">
</div>

<!-- TITLE -->

<h1 class="text-3xl font-bold mb-8 flex items-center gap-2 dark:text-white">
    🖼 Gallery ใบขับขี่
</h1>

<!-- GRID -->

<div id="galleryGrid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">

@forelse($licenses as $license)

<div class="gallery-card relative z-10 bg-white dark:bg-gray-800 rounded-xl shadow-md p-4 text-center hover:z-50">


<div class="aspect-[3/2] rounded-lg bg-gray-100 relative group overflow-visible">

    <!-- Skeleton -->
    <div class="absolute inset-0 animate-pulse bg-gray-300"></div>

    @if($license->license_image)

    <!-- IMAGE -->
    <img
        src="{{ cdn($license->license_image, 300) }}"
        loading="lazy"
        class="license-img w-full h-full object-cover opacity-0 transition duration-300"
        onload="this.classList.remove('opacity-0'); this.previousElementSibling.style.display='none';"
    >

    <!-- 🔥 POPUP -->
    <div class="popup absolute top-1/2 -translate-y-1/2
                opacity-0 group-hover:opacity-100
                pointer-events-none transition duration-200 z-[9999]">

        <div class="bg-white rounded-xl shadow-2xl p-3 w-64">

            <img 
                src="{{ cdn($license->license_image, 600) }}"
                class="w-full rounded"
            >

        </div>

    </div>

    @endif

</div>

<!-- TEXT -->
<p class="driver-name mt-3 font-semibold text-gray-800 dark:text-white">
    {{ $license->driver_name }}
</p>

<p class="plate-number text-sm text-gray-500">
    {{ $license->plate_number }}
</p>

</div>

@empty

<p class="text-gray-500">ไม่มีข้อมูล</p>
@endforelse

</div>

<!-- PAGINATION -->

<div class="mt-8">
{{ $licenses->links('pagination::tailwind') }}
</div>

</div>

<!-- 🔍 SEARCH -->

<script>
document.addEventListener("DOMContentLoaded", function(){

const input = document.getElementById("searchInput");

input.addEventListener("keyup", function(){

let keyword = input.value.toLowerCase();

document.querySelectorAll(".gallery-card").forEach(function(card){

let name = card.querySelector(".driver-name").textContent.toLowerCase();
let plate = card.querySelector(".plate-number").textContent.toLowerCase();

card.style.display =
(name.includes(keyword) || plate.includes(keyword)) ? "" : "none";

});

});

});
</script>

<!-- 🔥 SMART POPUP ENGINE -->

<script>
document.querySelectorAll('.gallery-card').forEach(card => {

    const popup = card.querySelector('.popup');

    card.addEventListener('mouseenter', () => {

        const rect = card.getBoundingClientRect();
        const popupWidth = 260; // ขนาด popup
        const spaceRight = window.innerWidth - rect.right;

        // ถ้าพื้นที่ขวาไม่พอ → ไปซ้าย
        if (spaceRight < popupWidth) {
            popup.style.left = 'auto';
            popup.style.right = '100%';
            popup.style.marginRight = '10px';
            popup.style.marginLeft = '0';
        } else {
            popup.style.left = '100%';
            popup.style.right = 'auto';
            popup.style.marginLeft = '10px';
            popup.style.marginRight = '0';
        }

    });

});
</script>

<!-- 🎨 STYLE -->

<style>

.gallery-card{
transition: all 0.25s ease;
}

.gallery-card:hover{
transform: translateY(-6px);
box-shadow: 0 12px 25px rgba(0,0,0,0.15);
z-index: 50;
}

.gallery-card:hover .license-img{
transform: scale(1.08);
}

.license-img{
transition: transform 0.3s ease;
}

</style>

@endsection

