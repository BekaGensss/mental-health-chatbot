<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>419 | Halaman Kedaluwarsa</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="antialiased font-sans bg-gray-50 flex items-center justify-center min-h-screen">
    <div class="text-center p-8 bg-white shadow-xl rounded-xl border-t-8 border-orange-600">
        <h1 class="text-9xl font-extrabold text-orange-700 tracking-wider">419</h1>
        <div class="bg-gray-200 text-gray-700 px-4 py-2 mt-4 rounded-full inline-block text-xl font-medium">
            Halaman Kedaluwarsa (Page Expired)
        </div>
        <p class="text-gray-500 mt-6 text-lg">
            Sesi Anda telah kedaluwarsa karena tidak aktif. Silakan muat ulang halaman atau coba lagi.
        </p>
        <a href="{{ url()->full() }}" class="mt-6 inline-block px-6 py-3 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 transition shadow-md">
            Muat Ulang Halaman
        </a>
    </div>
</body>
</html>