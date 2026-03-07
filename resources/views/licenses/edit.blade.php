@extends('layouts.admin')

@section('content')

<div class="max-w-5xl mx-auto">

<h1 class="text-3xl font-bold mb-8 dark:text-white">
✏️ แก้ไขใบขับขี่
</h1>

<div class="grid md:grid-cols-2 gap-8">

<!-- LEFT FORM -->
<div class="bg-white dark:bg-gray-800 p-8 rounded-xl shadow">

<form method="POST"
action="{{ route('licenses.update',$license->id) }}"
enctype="multipart/form-data">

@csrf
@method('PUT')

<div class="space-y-6">

<div>
<label class="block text-sm mb-1">ชื่อคนขับ</label>

<input type="text"
name="driver_name"
value="{{ $license->driver_name }}"
class="w-full border rounded-lg px-4 py-2"
required>
</div>


<div>
<label class="block text-sm mb-1">เลขใบขับขี่</label>

<input type="text"
name="license_number"
value="{{ $license->license_number }}"
class="w-full border rounded-lg px-4 py-2"
required>
</div>


<div>
<label class="block text-sm mb-1">ทะเบียนรถ</label>

<input type="text"
name="plate_number"
value="{{ $license->plate_number }}"
class="w-full border rounded-lg px-4 py-2"
required>
</div>


<div>
<label class="block text-sm mb-1">วันหมดอายุ</label>

<input type="date"
name="expire_date"
value="{{ $license->expire_date }}"
class="w-full border rounded-lg px-4 py-2"
required>
</div>


<div>
<label class="block text-sm mb-1">อัพโหลดรูปใหม่</label>

<input type="file"
name="license_image"
id="imageInput"
class="w-full border rounded-lg px-4 py-2">
</div>


<div class="flex gap-3 pt-4">



<button
class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
💾 บันทึกการแก้ไข
</button>
<a href="/licenses"
class="px-5 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">
ยกเลิก
</a>
</div>

</div>

</form>

</div>



<!-- RIGHT : IMAGE PREVIEW -->

<div class="bg-white dark:bg-gray-800 p-8 rounded-xl shadow flex flex-col items-center justify-center">

<h2 class="text-lg font-semibold mb-4 dark:text-white">
ตัวอย่างรูปใบขับขี่
</h2>

@if($license->license_image)

<img id="preview"
src="{{ asset('storage/'.$license->license_image) }}"
class="rounded-lg border shadow max-h-[250px]">

@else

<img id="preview"
src="https://via.placeholder.com/400x250?text=License+Preview"
class="rounded-lg border shadow max-h-[250px]">

@endif
</div>

</div>

</div>

</div>

<script>

// preview image when upload

document.getElementById('imageInput')
.addEventListener('change', function(e){

const file = e.target.files[0]

if(file){

const reader = new FileReader()

reader.onload = function(e){

document.getElementById('previewImage')
.src = e.target.result

}

reader.readAsDataURL(file)

}

})

</script>

@endsection