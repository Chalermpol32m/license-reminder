<!DOCTYPE html>
<html>
<head>
    <title>ระบบแจ้งเตือนใบขับขี่</title>
</head>
<body>

    <div style="background:#eee;padding:15px;">
        <a href="/licenses">บันทึกใบขับขี่</a> |
        <a href="/dashboard">แสดงภาพรวมสถานะใบขับขี่</a> |
        <form method="POST" action="{{ route('logout') }}" style="display:inline;">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </div>

    <hr>

    <div style="padding:20px;">
        @yield('content')
    </div>

</body>
</html>