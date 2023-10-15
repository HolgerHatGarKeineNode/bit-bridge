<div class="min-h-full">
    <div class="py-10">
        <header>
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between">
                    <img src="{{ asset('einundzwanzig-horizontal-inverted.svg') }}" alt="Logo">
                    <div>
                        <x-badge xs outline warning>Test Modus</x-badge>
                    </div>
                    <h1 class="text-3xl font-bold leading-tight tracking-tight text-gray-200">Bit-Bridge</h1>
                </div>
            </div>
        </header>
        <main>
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="mt-6 flex space-x-2 justify-end">
                    <x-button :href="route('emailTexts')" icon="document-text" outline amber>
                        E-Mail Texte
                    </x-button>
                    <x-button :href="route('emailLists')" icon="at-symbol" outline amber>
                        E-Mail Listen
                    </x-button>
                    @if($countLists < 1)
                        <div>
                            <x-badge outline red>E-Mail Listen fehlen</x-badge>
                        </div>
                    @else
                        <x-button
                            :href="route('smtpSettings')" amber>
                            Neue Kampagne starten
                        </x-button>
                    @endif
                </div>
                <div class="bg-gray-900 pb-10">
                    <h2 class="px-4 text-base font-semibold leading-7 text-white sm:px-6 lg:px-8">Laufende
                        Kampagnen</h2>
                    <table class="mt-6 w-full whitespace-nowrap text-left">
                        <thead class="border-b border-white/10 text-sm leading-6 text-white">
                        <tr>
                            <th scope="col" class="py-2 pl-4 pr-8 font-semibold sm:pl-6 lg:pl-8">Name</th>
                            <th scope="col" class="py-2 pl-0 pr-8 font-semibold sm:table-cell">E-Mail Liste</th>
                            <th scope="col" class="py-2 pl-0 pr-4 font-semibold sm:pr-8 sm:text-left lg:pr-20">
                                Gestartet
                            </th>
                            <th scope="col" class="py-2 pl-0 pr-8 font-semibold md:table-cell lg:pr-20"></th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">

                        @foreach($campaigns as $campaign)
                            <tr wire:key="campaign_{{ $campaign['id'] }}">
                                <td class="py-4 pl-4 pr-8 sm:pl-6 lg:pl-8">
                                    <div class="flex items-center gap-x-4">
                                        <div class="truncate text-sm font-medium leading-6 text-white">{{ $campaign['email_type'] }}</div>
                                    </div>
                                </td>
                                <td class="py-4 pl-0 pr-4 sm:table-cell sm:pr-8">
                                    <div class="flex gap-x-3">
                                        <div class="font-mono text-sm leading-6 text-gray-400">{{ $campaign['email_list'] }}</div>
                                    </div>
                                </td>
                                <td class="py-4 pl-0 pr-4 text-sm leading-6 sm:pr-8 lg:pr-20">
                                    <div class="flex items-center justify-end gap-x-2 sm:justify-start">
                                        <div class="flex-none rounded-full p-1 text-green-400 bg-green-400/10">
                                            <div class="h-1.5 w-1.5 rounded-full bg-current"></div>
                                        </div>
                                        <div class="hidden text-white sm:block">
                                            {{ \Illuminate\Support\Carbon::parse($campaign['started_at'])->toDateTimeString() }}
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 pl-0 pr-4 text-sm leading-6 sm:pr-8 lg:pr-20">
                                    <div class="flex items-center justify-end gap-x-2 sm:justify-start">
                                        <div class="flex-none rounded-full p-1 text-amber-400 bg-amber-400/10">
                                            <div class="h-1.5 w-1.5 rounded-full bg-current"></div>
                                        </div>
                                        <div class="hidden text-white sm:block">läuft</div>
                                    </div>
                                </td>
                                <td class="py-4 pl-0 pr-4 text-sm leading-6 sm:pr-8 lg:pr-20">
                                    <a href="{{ route('task', ['task' => $campaign['id']]) }}" class="text-amber-400 hover:text-amber-300">
                                        Öffnen
                                    </a>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>
