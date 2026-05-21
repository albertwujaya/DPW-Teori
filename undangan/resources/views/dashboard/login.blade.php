<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Albert &amp; Selviana</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&family=Cormorant+Garamond:ital,wght@0,400;1,600&display=swap" rel="stylesheet">
    <style>
        body { font-family:'Cormorant Garamond',serif; background-color:#f4f1ea; }
        .classic-border { border: 8px double #4a3728; }
        h1 { font-family:'Cinzel',serif; }
        input:focus { outline:none; }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen">
    <div class="bg-[#fdfbf7] p-8 rounded-lg shadow-2xl classic-border w-full max-w-md text-center">
        <div class="flex justify-center mb-4">
            <svg class="w-16 h-16 text-[#4a3728]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
        </div>

        <h1 class="text-2xl font-bold text-[#4a3728] mb-2 tracking-widest uppercase">Login Admin</h1>
        <p style="font-style:italic;color:#888;font-size:0.95rem;margin-bottom:24px;">Albert &amp; Selviana Wedding</p>

        @if (session('error'))
        <div style="color:#9b2335;font-size:0.85rem;padding:10px;background:#fde8ea;border-radius:6px;margin-bottom:16px;">
            {{ session('error') }}
        </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
            @csrf
            <input type="text" name="username" placeholder="Username"
                class="w-full p-3 bg-transparent border-b-2 border-[#4a3728] focus:border-yellow-700 placeholder-[#4a3728]/50"
                value="{{ old('username') }}" required autocomplete="username">

            <input type="password" name="password" placeholder="Password"
                class="w-full p-3 bg-transparent border-b-2 border-[#4a3728] focus:border-yellow-700 placeholder-[#4a3728]/50"
                required autocomplete="current-password">

            <button type="submit"
                class="mt-8 w-full px-10 py-3 border border-[#4a3728] text-[#4a3728] hover:bg-[#4a3728] hover:text-white transition duration-300 font-bold uppercase tracking-widest">
                Masuk
            </button>
        </form>

        <div style="margin-top:20px;padding-top:16px;border-top:1px solid #e0ddd5;">
            <a href="{{ route('home') }}" style="font-size:0.82rem;color:#6f816a;text-decoration:none;">
                ← Kembali ke Halaman Undangan
            </a>
        </div>
    </div>
</body>
</html>
