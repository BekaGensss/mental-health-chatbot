<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 | Akses Dilarang</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="antialiased font-sans bg-gray-50 flex items-center justify-center min-h-screen">
    <div class="text-center p-8 bg-white shadow-xl rounded-xl border-t-8 border-yellow-600">
        <h1 class="text-9xl font-extrabold text-yellow-700 tracking-wider">403</h1>
        <div class="bg-gray-200 text-gray-700 px-4 py-2 mt-4 rounded-full inline-block text-xl font-medium">
            Akses Dilarang (Forbidden)
        </div>
        <p class="text-gray-500 mt-6 text-lg">
            Anda tidak memiliki izin untuk mengakses sumber daya ini.
        </p>
        <a href="{{ url('/') }}" class="mt-6 inline-block px-6 py-3 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700 transition shadow-md">
            Kembali ke Halaman Utama
        </a>
    </div>
</body>
</html>