@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto p-6">

<style>
@keyframes fade-in {
    from { opacity: 0; transform: translateY(10px) scale(0.97);}
    to { opacity: 1; transform: translateY(0) scale(1);}
}
.animate-fade-in {
    animation: fade-in 0.25s ease;
}
</style>

<h1 class="text-2xl font-bold mb-6">🚗 จัดการรถ / ทะเบียน</h1>

<a href="/jobs" 
   class="inline-block mb-4 bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-lg">
    ← กลับไปหน้าจัดงาน
</a>

@if(session('success'))
<div class="bg-green-100 text-green-700 p-3 mb-4 rounded">
    {{ session('success') }}
</div>
@endif

<!-- ➕ เพิ่มรถ -->
<div class="bg-white p-6 rounded-xl shadow mb-6">
    <form action="/vehicles/store" method="POST" class="flex gap-3">
        @csrf

        <input id="plate" name="plate_number" placeholder="ทะเบียนรถ"
            class="border px-3 py-2 rounded w-40"
            onkeydown="nextField(event, 'type')">

        <input id="type" name="type" placeholder="ประเภทรถ"
            class="border px-3 py-2 rounded w-40"
            onkeydown="submitForm(event)">

        <button class="bg-blue-600 text-white px-4 py-2 rounded">
            เพิ่ม
        </button>
    </form>
</div>

<!-- 📋 ตาราง -->
<div class="bg-white p-6 rounded-xl shadow">
    <table class="w-full">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2">ทะเบียน</th>
                <th class="p-2">ประเภท</th>
                <th class="p-2">สถานะ</th>
            </tr>
        </thead>

        <tbody>
        @foreach($vehicles as $v)
        <tr class="border-t cursor-pointer hover:bg-gray-50"
            onclick="editVehicle({{ $v->id }}, `{{ $v->plate_number }}`, `{{ $v->type }}`)">
            <td class="p-2">{{ $v->plate_number }}</td>
            <td class="p-2">{{ $v->type }}</td>
            <td class="p-2">{{ $v->status }}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>

</div>

<!-- Modal -->
<div id="editModal" 
class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">

<div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 animate-fade-in">

    <h2 class="text-xl font-bold mb-4">✏️ แก้ไขข้อมูลรถ</h2>

    <form id="editForm" method="POST">
        @csrf

        <div class="mb-4">
            <label class="text-sm text-gray-600">ทะเบียนรถ</label>
            <input id="edit_plate" name="plate_number"
                class="border w-full px-3 py-2 rounded-lg">
        </div>

        <div class="mb-4">
            <label class="text-sm text-gray-600">ประเภทรถ</label>
            <input id="edit_type" name="type"
                class="border w-full px-3 py-2 rounded-lg">
        </div>

        <div class="flex justify-end gap-2">
            <button type="button" onclick="closeModal()"
                class="bg-gray-300 px-4 py-2 rounded">
                ยกเลิก
            </button>

            <button onclick="return confirm('ยืนยันแก้ไข?')"
                class="bg-blue-600 text-white px-4 py-2 rounded">
                บันทึก
            </button>
        </div>
    </form>

</div>
</div>

<script>
function editVehicle(id, plate, type) {
    const modal = document.getElementById('editModal');

    modal.classList.remove('hidden');

    document.getElementById('edit_plate').value = plate;
    document.getElementById('edit_type').value = type ?? '';

    document.getElementById('editForm').action = '/vehicles/update/' + id;

    setTimeout(() => {
        document.getElementById('edit_plate').focus();
    }, 100);
}

function closeModal() {
    document.getElementById('editModal').classList.add('hidden');
}

// click bg close
document.getElementById('editModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});

// esc close
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeModal();
});

// enter submit
document.getElementById('editForm').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        if (confirm('ยืนยันแก้ไขข้อมูลนี้?')) {
            this.submit();
        }
    }
});

// form input enter
function nextField(e, nextId) {
    if (e.key === 'Enter') {
        e.preventDefault();
        document.getElementById(nextId).focus();
    }
}

function submitForm(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        e.target.form.submit();
    }
}
</script>

@endsection