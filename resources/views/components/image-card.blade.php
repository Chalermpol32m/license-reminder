@props(['src', 'size' => 300])

<div class="relative bg-gray-100 rounded overflow-hidden">

    <!-- Skeleton -->
    <div class="absolute inset-0 animate-pulse bg-gray-300"></div>

    @if($src)
        <img
            src="{{ cdn($src, $size) }}"
            loading="lazy"
            class="w-full h-full object-cover opacity-0 transition duration-300"
            onload="this.classList.remove('opacity-0'); this.previousElementSibling.style.display='none';"
        >
    @else
        <div class="flex items-center justify-center h-full text-gray-400 text-sm">
            ไม่มีรูป
        </div>
    @endif

</div>