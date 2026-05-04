@extends('layouts.admin')

@section('content')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWix+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkR4j8R2WUE00s/" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="{{ asset('raw-material/style.css') }}">

<header class="app-shell rounded-2xl p-4 md:p-6 shadow-lg" aria-label="ส่วนหัวระบบ">
    <div class="flex items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="logo-badge"><i class="fa-solid fa-scale-balanced"></i></div>
            <div>
                <h1 class="text-xl md:text-2xl font-bold">ระบบบันทึกการรับวัตถุดิบ(ตราชั่ง)</h1>
                <p class="text-sm text-slate-200">Ocean Blue • Modern Playful</p>
            </div>
        </div>
        <button id="menuToggle" class="md:hidden menu-btn" aria-label="เปิดเมนูนำทาง"><i class="fa-solid fa-bars"></i></button>
        <nav id="desktopNav" class="hidden md:flex items-center gap-2" aria-label="เมนูหลัก">
            <button data-section="receive" class="nav-btn active"><i class="fa-solid fa-truck-ramp-box"></i> รับสินค้า</button>
            <button data-section="product" class="nav-btn"><i class="fa-solid fa-box"></i> เพิ่มสินค้า</button>
            <button data-section="customer" class="nav-btn"><i class="fa-solid fa-users"></i> เพิ่มลูกค้า</button>
            <button data-section="report" class="nav-btn"><i class="fa-solid fa-chart-column"></i> รายงาน</button>
        </nav>
    </div>
    <nav id="mobileNav" class="hidden md:hidden mt-4 grid gap-2" aria-label="เมนูมือถือ"></nav>
</header>

<main class="mt-6 space-y-6">
    <section id="section-receive" class="panel">
        <h2><i class="fa-solid fa-truck-ramp-box"></i> บันทึกข้อมูลการรับสินค้า</h2>
        <form id="receiveForm" class="grid md:grid-cols-2 xl:grid-cols-4 gap-3" novalidate>
            <input name="date" type="date" required aria-label="วันที่รับ">
            <input name="plate" placeholder="ทะเบียนรถ" required aria-label="ทะเบียนรถ">
            <input name="company" placeholder="บริษัท" required aria-label="บริษัท">
            <input name="item" placeholder="สินค้า" required aria-label="สินค้า">
            <input name="bags" type="number" min="1" placeholder="จำนวนถุง" required aria-label="จำนวนถุง">
            <input name="gross" type="number" min="0" step="0.01" placeholder="น้ำหนักรถหนัก" required aria-label="น้ำหนักรถหนัก">
            <input name="tare" type="number" min="0" step="0.01" placeholder="น้ำหนักรถเบา" required aria-label="น้ำหนักรถเบา">
            <input name="net" type="number" min="0" step="0.01" placeholder="สุทธิ" required aria-label="น้ำหนักสุทธิ">
            <input name="inTime" type="time" required aria-label="เวลาชั่งเข้า">
            <input name="outTime" type="time" required aria-label="เวลาชั่งออก">
            <input name="operator" placeholder="ผู้บันทึกข้อมูล" required aria-label="ผู้บันทึกข้อมูล">
            <div class="flex gap-2">
                <button class="btn-primary" type="submit">บันทึกข้อมูล</button>
                <button class="btn-secondary" type="reset">ยกเลิก</button>
            </div>
        </form>
    </section>

    <section id="section-product" class="panel hidden">
        <h2><i class="fa-solid fa-box"></i> เพิ่มชื่อสินค้า</h2>
        <form id="productForm" class="grid md:grid-cols-4 gap-3" novalidate>
            <input name="name" placeholder="ชื่อสินค้า" required aria-label="ชื่อสินค้า">
            <button class="btn-primary" type="submit">ตกลง</button>
            <button class="btn-secondary" type="button" data-action="edit-product">แก้ไข</button>
            <button class="btn-danger" type="button" data-action="delete-product">ลบ</button>
        </form>
        <ul id="productList" class="list-board"></ul>
    </section>

    <section id="section-customer" class="panel hidden">
        <h2><i class="fa-solid fa-users"></i> เพิ่มชื่อลูกค้า</h2>
        <form id="customerForm" class="grid md:grid-cols-4 gap-3" novalidate>
            <input name="name" placeholder="ชื่อลูกค้า" required aria-label="ชื่อลูกค้า">
            <button class="btn-primary" type="submit">ตกลง</button>
            <button class="btn-secondary" type="button" data-action="edit-customer">แก้ไข</button>
            <button class="btn-danger" type="button" data-action="delete-customer">ลบ</button>
        </form>
        <ul id="customerList" class="list-board"></ul>
    </section>

    <section id="section-report" class="panel hidden">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <h2><i class="fa-solid fa-chart-column"></i> รายงานการรับวัตถุดิบ</h2>
            <div class="flex flex-wrap gap-2">
                <select id="reportPeriod" aria-label="เลือกรูปแบบรายงาน"><option value="all">ทั้งหมด</option><option value="day">รายวัน</option><option value="month">รายเดือน</option><option value="year">รายปี</option></select>
                <input id="reportDate" type="date" aria-label="ตัวกรองวันที่">
                <button id="exportCsv" class="btn-primary" type="button"><i class="fa-solid fa-file-export"></i> Export CSV</button>
                <button id="printReport" class="btn-secondary" type="button"><i class="fa-solid fa-print"></i> สั่งพิมพ์</button>
            </div>
        </div>
        <div id="loading" class="hidden loading"><span class="spinner"></span> กำลังโหลดข้อมูล...</div>
        <div class="overflow-auto mt-4">
            <table class="min-w-full data-table">
                <thead><tr><th>วันที่</th><th>ทะเบียน</th><th>บริษัท</th><th>สินค้า</th><th>สุทธิ</th><th>ผู้บันทึก</th></tr></thead>
                <tbody id="reportBody"></tbody>
            </table>
        </div>
    </section>
</main>

<footer class="mt-8 text-center text-sm text-slate-500">ระบบบันทึกการรับวัตถุดิบ(ตราชั่ง) © 2026</footer>
<script src="{{ asset('raw-material/script.js') }}"></script>
@endsection
