@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto p-6">

    <!-- หัวข้อ -->
    <h1 class="text-2xl font-bold mb-6">
        ระบบจัดการงานขนส่ง
    </h1>
<form action="/jobs/auto-assign" method="POST" class="mb-4">
    @csrf
    <button class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
        จัดรถอัตโนมัติ
    </button>
</form>

    <!-- กล่องเพิ่มงาน -->
    <div class="bg-white shadow rounded-lg p-6 mb-8">

        <h2 class="text-lg font-semibold mb-4">
            เพิ่มงานขนส่ง
        </h2>

        <form action="/jobs/store" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4">

            @csrf

            <!-- ลูกค้า -->
            <input type="text"
                name="customer"
                placeholder="ชื่อลูกค้า"
                class="border rounded px-3 py-2 w-full">

            <!-- ปลายทาง -->
            <input type="text"
                name="destination"
                placeholder="ปลายทาง"
                class="border rounded px-3 py-2 w-full">

            <!-- วันที่ส่ง -->
            <input type="date"
                name="delivery_date"
                class="border rounded px-3 py-2 w-full">


            <!-- ปุ่มเพิ่มงาน -->
            <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                เพิ่มงาน
            </button>

        </form>

    </div>



    <!-- ตารางรายการงาน -->
    <div class="bg-white shadow rounded-lg p-6">

        <h2 class="text-lg font-semibold mb-4">
            รายการงานขนส่ง
        </h2>

        <table class="w-full border">

            <thead class="bg-gray-100">

            <!--เพิ่มคอลัมน์ตรงนี้-->
                <tr>
                    <th class="p-3 text-left">ลูกค้า</th>
                    <th class="p-3 text-left">ปลายทาง</th>
                    <th class="p-3 text-left">วันที่ส่ง</th>
                    <th class="p-3 text-left">สถานะ</th>
                    <th class="p-3 text-left">คนขับ</th>
                </tr>

            </thead>

            <tbody>

                @foreach($jobs as $job)

                <tr class="border-t">

                    <td class="p-3">
                        {{ $job->customer }}
                    </td>

                    <td class="p-3">
                        {{ $job->destination }}
                    </td>

                    <td class="p-3">
                        {{ $job->delivery_date }}
                    </td>

                    <td class="p-3">
                      {{ $job->driver->name ?? '-' }}
                </td>

                    <td class="p-3">

                        @if($job->status == 'pending')

                        <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded">
                            รอจัดรถ
                        </span>

                        @else

                        <span class="bg-green-100 text-green-700 px-2 py-1 rounded">
                            จัดรถแล้ว
                        </span>

                        @endif

                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>
@if(session('success'))
<div class="bg-green-100 text-green-700 p-3 mb-4 rounded">
    {{ session('success') }}
</div>
@endif
@endsection