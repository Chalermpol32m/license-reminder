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

    <!-- GALLERY GRID -->
    <div id="galleryGrid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">

        @forelse($licenses as $license)

        <div class="gallery-card bg-white dark:bg-gray-800 rounded-xl shadow-md p-4 text-center">

            <div class="aspect-[3/2] overflow-hidden rounded-lg bg-gray-100 flex items-center justify-center">

                @if($license->license_image)
                    <img
                        src="{{ $license->license_image }}"
                        class="license-img preview-img w-full h-full object-cover">
                @else
                    <span class="text-gray-400 text-sm">ไม่มีรูป</span>
                @endif

            </div>

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

</div>

<!-- POPUP PREVIEW -->
<div id="imagePopup" class="fixed hidden z-50 pointer-events-none">
    <img id="popupImage"
        class="w-72 rounded-lg shadow-2xl border">
</div>

<!-- SEARCH SCRIPT -->
<script>
document.addEventListener("DOMContentLoaded", function(){

    const input = document.getElementById("searchInput");

    input.addEventListener("keyup", function(){

        let keyword = input.value.toLowerCase();

        document.querySelectorAll(".gallery-card").forEach(function(card){

            let name = card.querySelector(".driver-name").textContent.toLowerCase();
            let plate = card.querySelector(".plate-number").textContent.toLowerCase();

            if(name.includes(keyword) || plate.includes(keyword)){
                card.style.display = "";
            }else{
                card.style.display = "none";
            }

        });

    });

});
</script>

<!-- HOVER POPUP SCRIPT -->
<script>

const popup = document.getElementById("imagePopup");
const popupImg = document.getElementById("popupImage");

document.querySelectorAll(".preview-img").forEach(img => {

    img.addEventListener("mouseenter", function(){
        popup.classList.remove("hidden");
        popupImg.src = this.src;
    });

    img.addEventListener("mousemove", function(e){
        popup.style.top = (e.pageY - 220) + "px";
        popup.style.left = (e.pageX + 20) + "px";
    });

    img.addEventListener("mouseleave", function(){
        popup.classList.add("hidden");
    });

});
</script>

<!-- STYLE -->
<style>

.gallery-card{
    transition: all 0.3s ease;
}

.gallery-card:hover{
    transform: translateY(-8px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.2);
}

.gallery-card:hover .license-img{
    transform: scale(1.15);
}

.license-img{
    transition: transform 0.3s ease;
}

</style>

@endsection