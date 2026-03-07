@extends('layouts.admin')

@section('content')

<div x-data="{ filter: 'all' }">

<h1 class="text-3xl font-bold mb-8 dark:text-white">
📊 สรุปภาพรวมใบขับขี่
</h1>

{{-- ALERT BAR --}}
@if($expired + $alert3 > 0)

<div class="mb-6 bg-red-100 border border-red-300 text-red-800 px-6 py-4 rounded-lg flex justify-between items-center">

<div>
🚨 มี <strong>{{ $expired + $alert3 }}</strong> คัน ที่ต้องต่อใบขับขี่
</div>

<a href="{{ route('licenses.index', ['status' => 'danger']) }}"
class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
ดูรายการ
</a>

</div>

@endif



<!-- STAT CARDS -->
<div class="grid grid-cols-5 gap-4 mb-8">

<div @click="filter='all'"
class="cursor-pointer bg-gradient-to-r from-blue-500 to-blue-600 text-white p-4 rounded-xl shadow hover:scale-105 transition">
<p class="text-sm opacity-80">ทั้งหมด</p>
<p class="text-2xl font-bold">{{ $total }}</p>
</div>


<div @click="filter='expired'"
class="cursor-pointer bg-gray-500 text-white p-4 rounded-xl shadow hover:scale-105 transition">
<p class="text-sm opacity-80">หมดอายุแล้ว</p>
<p class="text-2xl font-bold">{{ $expired }}</p>
</div>


<div @click="filter='3'"
class="cursor-pointer bg-red-500 text-white p-4 rounded-xl shadow hover:scale-105 transition">
<p class="text-sm opacity-80">ภายใน 3 วัน</p>
<p class="text-2xl font-bold">{{ $alert3 }}</p>
</div>


<div @click="filter='7'"
class="cursor-pointer bg-yellow-500 text-white p-4 rounded-xl shadow hover:scale-105 transition">
<p class="text-sm opacity-80">ภายใน 7 วัน</p>
<p class="text-2xl font-bold">{{ $alert7 }}</p>
</div>


<div @click="filter='15'"
class="cursor-pointer bg-blue-500 text-white p-4 rounded-xl shadow hover:scale-105 transition">
<p class="text-sm opacity-80">ภายใน 15 วัน</p>
<p class="text-2xl font-bold">{{ $alert15 }}</p>
</div>

</div>



<!-- TABLE -->
<div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">

<h2 class="text-xl font-bold mb-4 dark:text-white">
🚨 รายการใบขับขี่
</h2>

<table class="w-full text-left">

<thead>

<tr class="border-b">
<th class="py-2">ชื่อ</th>
<th>ทะเบียน</th>
<th>วันหมดอายุ</th>
<th>เหลือ</th>
</tr>

</thead>

<tbody>

@foreach($licenses as $license)

<tr
x-show="
filter=='all' ||
(filter=='expired' && {{ $license->days_left }} < 0) ||
(filter=='3' && {{ $license->days_left }} >=0 && {{ $license->days_left }} <=3) ||
(filter=='7' && {{ $license->days_left }} >3 && {{ $license->days_left }} <=7) ||
(filter=='15' && {{ $license->days_left }} >7 && {{ $license->days_left }} <=15)
"
class="border-t">

<td class="py-2">{{ $license->driver_name }}</td>

<td>{{ $license->plate_number }}</td>

<td>
{{ \Carbon\Carbon::parse($license->expire_date)->format('d/m/Y') }}
</td>

<td class="text-red-600 font-bold">
{{ $license->days_left }} วัน
</td>

</tr>

@endforeach

</tbody>

</table>

</div>

</div>

@endsection