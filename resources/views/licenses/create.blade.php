@extends('layouts.admin')

@section('content')

<div class="max-w-6xl mx-auto">

<h1 class="text-3xl font-bold mb-6 dark:text-white">
➕ เพิ่มข้อมูลใบขับขี่
</h1>

<div class="bg-white dark:bg-gray-800 p-8 rounded-xl shadow">

<form method="POST"
action="{{ route('licenses.store') }}"
enctype="multipart/form-data">

@csrf

<div class="grid md:grid-cols-2 gap-8">

<!-- LEFT : FORM -->
<div class="space-y-4">

<div>
<label class="block mb-2 font-semibold dark:text-white">ชื่อคนขับ</label>
<input type="text" name="driver_name"
class="w-full border rounded-lg px-3 py-2" required>
</div>

<div>
<label class="block mb-2 font-semibold dark:text-white">เลขใบขับขี่</label>
<input type="text" name="license_number"
class="w-full border rounded-lg px-3 py-2" required>
</div>

<div>
<label class="block mb-2 font-semibold dark:text-white">ทะเบียนรถ</label>
<input type="text" name="plate_number"
class="w-full border rounded-lg px-3 py-2" required>
</div>

<div>
<label class="block mb-2 font-semibold dark:text-white">วันหมดอายุ</label>

<input type="date" name="expire_date" id="expire_date"
class="w-full border rounded-lg px-3 py-2" required>

<p id="days_left_text" class="text-sm mt-2"></p>
</div>

<div>
<label class="block mb-2 font-semibold dark:text-white">รูปใบขับขี่</label>

<input type="file"
name="license_image"
id="imageInput"
accept="image/*"
class="w-full border rounded-lg px-3 py-2">
</div>

</div>

<!-- RIGHT : PREVIEW -->
<div class="flex flex-col items-center justify-center">

<p class="mb-3 font-semibold dark:text-white">Preview</p>

<div class="border rounded-xl p-4 bg-gray-50">

<img id="preview"
src="https://via.placeholder.com/250x160?text=No+Image"
class="rounded w-64 object-cover">

</div>

<p class="text-sm text-gray-400 mt-2">
รูปจะแสดงเมื่อเลือกไฟล์
</p>

</div>

</div>

<!-- BUTTON -->
<div class="mt-8 flex gap-4">

<button
class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
💾 บันทึก
</button>

<a href="{{ route('licenses.index') }}"
class="px-6 py-2 bg-gray-400 text-white rounded-lg hover:bg-gray-500">
ยกเลิก
</a>

</div>

</form>

</div>

</div>

<script>

// 🔥 Preview Image
document.getElementById('imageInput').addEventListener('change', function(){

const file = this.files[0];

if(file){

// ตรวจ type
if(!file.type.startsWith('image/')){
alert('กรุณาเลือกไฟล์รูปภาพ');
this.value = '';
return;
}

const reader = new FileReader();

reader.onload = function(e){
document.getElementById('preview').src = e.target.result;
}

reader.readAsDataURL(file);

}else{
// reset
document.getElementById('preview').src =
"https://via.placeholder.com/250x160?text=No+Image";
}

});


// 🔥 Days Left Calculator
document.getElementById("expire_date").addEventListener("change", function(){

let expireDate = new Date(this.value);
let today = new Date();

let diff = expireDate - today;
let days = Math.ceil(diff / (1000 * 60 * 60 * 24));

let text = document.getElementById("days_left_text");

if(days < 0){
text.innerHTML = "❌ ใบขับขี่หมดอายุแล้ว";
text.className = "text-red-600 text-sm mt-2";
}else{
text.innerHTML = "⏳ เหลืออีก " + days + " วัน";
text.className = "text-blue-600 text-sm mt-2";
}

});

</script>

@endsection