@extends('layouts.admin')

@section('content')

<div class="flex justify-between items-center mb-6">

<h1 class="text-3xl font-bold dark:text-white">
📋 รายการใบขับขี่
</h1>

<div class="flex gap-3">

<a href="{{ route('licenses.create') }}"
class="flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
➕ เพิ่มใบขับขี่ </a>

</div>

</div>

<!-- Search + Sort + Legend -->

<div class="mb-6 flex flex-col md:flex-row gap-4 justify-between items-center">

<input
type="text"
id="searchInput"
placeholder="ค้นหาชื่อ / ทะเบียน / เลขใบขับขี่"
class="px-4 py-2 border rounded md:w-1/3 w-full">

<div class="flex items-center gap-4">

<div class="flex items-center gap-3 text-sm">

<span class="flex items-center gap-1">
<span class="w-3 h-3 bg-green-600 rounded-full"></span>
SAFE > 15 วัน
</span>

<span class="flex items-center gap-1">
<span class="w-3 h-3 bg-yellow-500 rounded-full"></span>
WARNING ≤ 7 วัน
</span>

<span class="flex items-center gap-1">
<span class="w-3 h-3 bg-red-600 rounded-full"></span>
DANGER ≤ 3 วัน
</span>

<span class="flex items-center gap-1">
<span class="w-3 h-3 bg-gray-500 rounded-full"></span>
EXPIRED ≤ 0 วัน
</span>

</div>

<a href="?sort=asc" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
เรียงวันหมดอายุ ↑
</a>

<a href="?sort=desc" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
เรียงวันหมดอายุ ↓
</a>

</div>

</div>

<!-- Table -->

<div class="bg-white dark:bg-blue-800 rounded-xl shadow overflow-visible">

<table class="min-w-full text-left">

<thead class="bg-blue-100 dark:bg-blue-700">
<tr>
<th class="p-4">ที่</th>
<th class="p-4">ชื่อ</th>
<th class="p-4">เลขใบขับขี่</th>
<th class="p-4">ทะเบียน</th>
<th class="p-4 text-center">รูป</th>
<th class="p-4">วันหมดอายุ</th>
<th class="p-4 text-center">สถานะ</th>
<th class="p-4 text-center">จัดการ</th>
</tr>
</thead>

<tbody id="licenseTable">

@foreach($licenses as $license)

@php
$badge = match($license->status) {
'safe' => 'bg-green-100 text-green-700',
'warning' => 'bg-yellow-100 text-yellow-700',
'danger' => 'bg-red-100 text-red-700',
'expired' => 'bg-gray-200 text-gray-500',
default => 'bg-gray-100 text-gray-700',
};

$borderColor = match($license->status) {
'danger' => 'border-l-4 border-red-600 bg-red-50',
'warning' => 'border-l-4 border-yellow-500 bg-yellow-50',
'safe' => 'border-l-4 border-green-600 bg-green-50',
'expired' => 'border-l-4 border-gray-500 bg-gray-100',
default => '',
};
@endphp

<tr class="border-t hover:bg-gray-50 hover:brightness-95 transition {{ $borderColor }}">

<td class="p-4">{{ $loop->iteration }}</td>

<td class="p-4">{{ $license->driver_name }}</td>

<td class="p-4">{{ $license->license_number }}</td>

<td class="p-4">{{ $license->plate_number }}</td>

<!-- IMAGE -->

<td class="p-4 text-center relative group">

@if($license->license_image)

<div class="w-24 h-16 bg-white p-1 rounded shadow flex items-center justify-center">
    <img 
        src="{{ cdn($license->license_image, 200) }}"
        class="max-w-full max-h-full object-contain"
    >
</div>

<!-- Hover Preview -->

<div 
    class="absolute opacity-0 group-hover:opacity-100
           pointer-events-none transition duration-200 z-[9999]
           left-20 top-1/2 -translate-y-1/2"
    onmouseenter="loadPreview(this, '{{ $license->license_image }}')"
>

```
<div class="bg-white dark:bg-gray-900 rounded-xl shadow-2xl p-4 w-[420px] border">

    <div class="aspect-[1.58/1] overflow-hidden rounded mb-3 border bg-white">

        <img class="preview-img w-full h-full object-cover hidden">

    </div>

    <div class="text-sm text-left space-y-1 dark:text-gray-200">
        <p><b>ชื่อ:</b> {{ $license->driver_name }}</p>
        <p><b>เลข:</b> {{ $license->license_number }}</p>
        <p><b>ทะเบียน:</b> {{ $license->plate_number }}</p>
        <p><b>หมดอายุ:</b> {{ \Carbon\Carbon::parse($license->expire_date)->format('d/m/Y') }}</p>
    </div>

</div>
```

</div>

@else <span class="text-gray-400 text-sm">ไม่มีรูป</span>
@endif

</td>

<td class="p-4">
{{ \Carbon\Carbon::parse($license->expire_date)->format('d/m/Y') }}
</td>

<td class="p-4 text-center">

<span class="px-3 py-1 text-sm rounded-full {{ $badge }}">
{{ strtoupper($license->status) }}

@if($license->days_left >= 0)
({{ $license->days_left }} วัน)
@else
(หมดอายุ)
@endif

</span>

</td>

<td class="p-4 text-center space-x-3">

<a href="{{ route('licenses.edit', $license->id) }}"
class="text-blue-600 hover:underline">
แก้ไข </a>

<form method="POST"
action="{{ route('licenses.destroy', $license->id) }}"
class="inline">

@csrf
@method('DELETE')

<button
class="text-red-600 hover:underline"
onclick="return confirm('ต้องการลบจริงหรือไม่?')">
ลบ </button>

</form>

</td>

</tr>

@endforeach

</tbody>

</table>

</div>

<!-- Pagination -->

<div class="mt-6">
{{ $licenses->links('pagination::tailwind') }}
</div>

<!-- Search -->

<script>
document.getElementById('searchInput').addEventListener('keyup', function(){

let value = this.value.toLowerCase();

document.querySelectorAll('#licenseTable tr').forEach(row => {
row.style.display =
row.innerText.toLowerCase().includes(value) ? '' : 'none';
});

});
</script>

<!-- Hover Load -->

<script>
function loadPreview(el, url) {
    let img = el.querySelector('.preview-img');

    if (!img.src) {
        img.src = url.replace('/upload/', '/upload/w_800,q_auto,f_auto,b_white/');
        img.onload = () => img.classList.remove('hidden');
    }
}
</script>

@endsection
