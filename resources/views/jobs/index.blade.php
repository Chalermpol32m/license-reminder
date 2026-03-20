@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto p-6">

    <!-- 🔷 HEADER -->
     <!-- 📂 IMPORT EXCEL -->
<div class="bg-white p-4 rounded-xl shadow mb-6 border">

    <h2 class="text-lg font-semibold mb-3">📂 นำเข้าข้อมูลจาก Excel</h2>

    <form action="/jobs/import" method="POST" enctype="multipart/form-data" 
        class="flex flex-col md:flex-row gap-3 items-center">
        @csrf

        <input type="file" name="file"
            class="border p-2 rounded w-full md:w-auto">

        <button class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 shadow">
            🚀 Import Excel
        </button>
    </form>

    <p class="text-sm text-gray-500 mt-2">
        รูปแบบไฟล์: customer | destination | delivery_date
    </p>

</div>
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <h1 class="text-3xl font-bold text-gray-800">
            🚚 Dashboard งานขนส่ง
        </h1>

        <form action="/jobs/auto-assign" method="POST">
            @csrf
            <button onclick="return confirm('ยืนยันจัดรถอัตโนมัติ?')"
                class="bg-gradient-to-r from-green-500 to-emerald-600 
                text-white px-6 py-2 rounded-xl shadow-lg 
                hover:scale-105 hover:shadow-xl transition">
                ⚡ Auto Assign
            </button>
        </form>
    </div>

    <!-- 📊 SUMMARY -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

        <!-- งานทั้งหมด -->
        <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition p-5 border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">งานทั้งหมด</p>
                    <h2 class="text-3xl font-bold">{{ $jobs->count() }}</h2>
                </div>
                <div class="text-3xl">📦</div>
            </div>
        </div>

        <!-- รอจัด -->
        <div class="bg-yellow-50 rounded-2xl shadow-md hover:shadow-xl transition p-5 border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-600 text-sm">รอจัดรถ</p>
                    <h2 class="text-3xl font-bold">
                        {{ $jobs->where('status','pending')->count() }}
                    </h2>
                </div>
                <div class="text-3xl">⏳</div>
            </div>
        </div>

        <!-- สำเร็จ -->
        <div class="bg-green-50 rounded-2xl shadow-md hover:shadow-xl transition p-5 border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-600 text-sm">จัดรถแล้ว</p>
                    <h2 class="text-3xl font-bold">
                        {{ $jobs->where('status','assigned')->count() }}
                    </h2>
                </div>
                <div class="text-3xl">✅</div>
            </div>
        </div>

    </div>

    <!-- 🔍 SEARCH -->
    <form method="GET" class="mb-6">
        <input type="text" name="search" placeholder="🔍 ค้นหาลูกค้า / ปลายทาง"
            class="w-full md:w-1/3 border rounded-xl px-4 py-2 shadow 
            focus:ring-2 focus:ring-blue-300 focus:outline-none">
    </form>

    <!-- ➕ ADD JOB -->
    <div class="bg-white shadow-xl rounded-2xl p-6 mb-6 border">

        <h2 class="text-xl font-semibold mb-4">➕ เพิ่มงาน</h2>

        <form action="/jobs/store" method="POST"
            class="grid grid-cols-1 md:grid-cols-4 gap-4">
            @csrf

            <input name="customer" placeholder="ลูกค้า"
                class="border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-200">

            <input name="destination" placeholder="ปลายทาง"
                class="border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-200">

            <input type="date" name="delivery_date"
                class="border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-200">

            <button class="bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow">
                เพิ่ม
            </button>
        </form>

    </div>

    <!-- 📋 TABLE -->
    <div class="bg-white shadow-xl rounded-2xl p-6 border">

        <h2 class="text-xl font-semibold mb-4">📋 รายการงาน</h2>

        <div class="overflow-x-auto">
            <table class="w-full">

                <!-- header -->
                <thead class="bg-gray-50 text-gray-500 text-sm uppercase tracking-wide">
                    <tr>
                        <th class="p-3 text-left">ลูกค้า</th>
                        <th class="p-3 text-left">ปลายทาง</th>
                        <th class="p-3 text-left">วันที่</th>
                        <th class="p-3 text-left">สถานะ</th>
                        <th class="p-3 text-left">คนขับ</th>
                    </tr>
                </thead>

                <!-- body -->
                <tbody class="text-gray-700">

                    @foreach($jobs as $job)
                    <tr class="border-b hover:bg-gray-50 hover:shadow-sm transition">

                        <td class="p-3 font-medium">{{ $job->customer }}</td>
                        <td class="p-3">{{ $job->destination }}</td>
                        <td class="p-3">{{ $job->delivery_date }}</td>

                        <td class="p-3">
                            @if($job->status == 'pending')
                                <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold">
                                    รอจัด
                                </span>
                            @elseif($job->status == 'assigned')
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                                    สำเร็จ
                                </span>
                            @else
                                <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-xs">
                                    ไม่ทราบ
                                </span>
                            @endif
                        </td>

                        <td class="p-3">
                            {{ $job->driver->name ?? '-' }}
                        </td>

                    </tr>
                    @endforeach

                </tbody>

            </table>
        </div>

    </div>

</div>

@endsection