<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">

    <!-- Styles -->
    @vite('resources/css/app.css')
</head>
<body class="antialiased bg-gray-900">
<div class="min-h-full">
    <div class="py-10">
        <header>
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between">
                    <img src="{{ asset('einundzwanzig-horizontal-inverted.svg') }}" alt="Logo">
                    <h1 class="text-3xl font-bold leading-tight tracking-tight text-gray-200">Bit-Bridge</h1>
                </div>
            </div>
        </header>
        <main>
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <form class="my-12">
                    <div class="space-y-12">
                        <div class="border-b border-white/10 pb-12">
                            <h2 class="text-base font-semibold leading-7 text-white">E-Mail Server Einstellungen</h2>
                            <p class="mt-1 text-sm leading-6 text-gray-400">
                                Trage hier die Zugangsdaten deines E-Mail
                                Servers ein, damit die App in deinem Namen die Empfänger anschreiben kann.
                            </p>

                            <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                <div class="sm:col-span-3">
                                    <label for="username" class="block text-sm font-medium leading-6 text-white">
                                        Username
                                    </label>
                                    <div class="mt-2">
                                        <input type="text" name="username" id="username" autocomplete="username"
                                               class="block w-full rounded-md border-0 bg-white/5 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6">
                                    </div>
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="server" class="block text-sm font-medium leading-6 text-white">
                                        SMTP Server
                                    </label>
                                    <div class="mt-2">
                                        <input type="text" name="server" id="server" autocomplete="server"
                                               class="block w-full rounded-md border-0 bg-white/5 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6">
                                    </div>
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="password" class="block text-sm font-medium leading-6 text-white">
                                        SMTP Password
                                    </label>
                                    <div class="mt-2">
                                        <input type="password" name="password" id="password" autocomplete="password"
                                               class="block w-full rounded-md border-0 bg-white/5 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-end gap-x-6">
                        <button type="submit"
                                class="rounded-md bg-indigo-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">
                            E-Mails abschicken
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>
</body>
</html>
