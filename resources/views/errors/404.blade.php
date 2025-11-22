<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 | Halaman Tidak Ditemukan</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    // Mendefinisikan warna kustom untuk aksen yang elegan
                    colors: {
                        'primary-dark': '#1e293b', // slate-800
                        'accent-teal': '#14b8a6', // teal-500
                    }
                }
            }
        }
    </script>
</head>
<body class="antialiased bg-gray-900 text-white flex items-center justify-center min-h-screen p-4">

    <div class="text-center p-12 lg:p-16 bg-white shadow-2xl rounded-3xl max-w-lg w-full transform transition duration-500 hover:scale-[1.01]">
        
        <svg class="mx-auto h-20 w-20 text-accent-teal" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.332 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>

        <h1 class="text-9xl font-extrabold text-primary-dark tracking-tighter mt-4">
            404
        </h1>
        
        <div class="text-primary-dark px-4 py-2 mt-4 inline-block text-2xl font-black border-b-4 border-accent-teal">
            Halaman Tidak Ditemukan
        </div>
        
        <p class="text-gray-600 mt-6 text-lg tracking-wide">
            Mohon maaf, alamat web (URL) yang Anda akses tidak valid. Sumber daya yang Anda cari telah dipindahkan, dihapus, atau tidak pernah ada.
        </p>
        
        <a href="{{ url('/') }}" class="mt-8 inline-flex items-center justify-center px-8 py-3 bg-accent-teal text-white font-semibold rounded-xl text-lg hover:bg-teal-600 transition duration-300 transform hover:shadow-lg shadow-md focus:outline-none focus:ring-4 focus:ring-accent-teal focus:ring-opacity-50">
             <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Kembali ke Beranda
        </a>
        
    </div>
</body>
</html>