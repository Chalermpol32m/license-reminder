<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Transport System</title>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Kanit&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Kanit', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100">

    <!-- 🔷 NAVBAR -->
    <nav class="bg-white shadow mb-6">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">

            <div class="text-xl font-bold text-blue-600">
                🚚 Transport System
            </div>

            <div class="space-x-4">
                <a href="/vehicles" class="text-gray-700 hover:text-blue-600">
    เพิ่มทะเบียนรถ
</a>

                <a href="/licenses" class="text-gray-700 hover:text-blue-600">
                    เพิ่มใบขับขี่
                </a>

                <a href="/logout" class="bg-red-500 text-white px-3 py-1 rounded">
                    Logout
                </a>
                
            </div>

        </div>
    </nav>

    <!-- 🔽 CONTENT -->
    <main>
        @yield('content')
    </main>

</body>
</html>