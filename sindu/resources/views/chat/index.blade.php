<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Live Chat SINDU</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen">
    <div class="max-w-6xl mx-auto p-4 md:p-8">
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="bg-emerald-600 text-white p-4 flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-bold">Live Chat Posyandu</h1>
                    <p class="text-sm text-emerald-100">Kirim pertanyaan dari user ke admin atau balas dari admin</p>
                </div>
                <a href="{{ Auth::user()->isKader() ? '/admin' : '/' }}" class="text-sm font-semibold underline">Kembali</a>
            </div>

            <div class="grid md:grid-cols-[280px_1fr] min-h-[620px]">
                <aside class="border-r border-slate-200 bg-slate-50 p-4">
                    <h2 class="font-semibold text-slate-700 mb-3">Daftar Kontak</h2>
                    <div class="space-y-2">
                        @foreach($users as $user)
                            <a href="{{ route('chat.index', ['user_id' => $user->id]) }}" class="block rounded-2xl border {{ $selectedUserId == $user->id ? 'border-emerald-500 bg-white' : 'border-slate-200 bg-slate-50' }} p-3">
                                <div class="font-semibold text-slate-700">{{ $user->name }}</div>
                                <div class="text-xs text-slate-500">{{ $user->role === 'kader' ? 'Admin/Kader' : 'Pengguna' }}</div>
                            </a>
                        @endforeach
                    </div>
                </aside>

                <section class="flex flex-col">
                    <div class="flex-1 p-4 space-y-3 overflow-y-auto" id="chat-box">
                        @forelse($messages as $message)
                            <div class="flex {{ $message->sender_id === Auth::id() ? 'justify-end' : 'justify-start' }}">
                                <div class="max-w-[80%] rounded-2xl px-4 py-3 {{ $message->sender_id === Auth::id() ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-700' }}">
                                    <div class="text-xs opacity-75 mb-1">{{ $message->sender->name }}</div>
                                    <div>{{ $message->message }}</div>
                                    <div class="text-[10px] mt-1 opacity-70">{{ $message->created_at->format('H:i') }}</div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-slate-400 py-16">Pilih kontak untuk memulai percakapan.</div>
                        @endforelse
                    </div>

                    @if($selectedUserId)
                        <form id="chat-form" class="border-t border-slate-200 p-4 flex gap-2">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $selectedUserId }}">
                            <input type="text" name="message" id="message-input" placeholder="Ketik pesan..." class="flex-1 border border-slate-200 rounded-2xl px-4 py-3 outline-none focus:border-emerald-500">
                            <button type="submit" class="bg-emerald-600 text-white px-4 rounded-2xl font-semibold">Kirim</button>
                        </form>
                    @endif
                </section>
            </div>
        </div>
    </div>

    <script>
        const form = document.getElementById('chat-form');
        const box = document.getElementById('chat-box');
        const input = document.getElementById('message-input');

        if (form) {
            form.addEventListener('submit', async function (e) {
                e.preventDefault();
                const formData = new FormData(form);
                const message = formData.get('message');
                if (!message || !message.trim()) return;

                const response = await fetch('{{ route('chat.store') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                if (response.ok) {
                    form.reset();
                    window.location.reload();
                }
            });
        }
    </script>
</body>
</html>
