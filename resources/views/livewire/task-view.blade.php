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
                <div class="flex justify-between items-center mt-6">
                    <div>
                        <x-button :href="route('start')" outline icon="arrow-left">Zurück</x-button>
                    </div>
                    <div class="flex justify-end space-x-2">
                        @if($task['status'] === 'running')
                            <x-button amber icon="stop">Stoppen</x-button>
                        @elseif($task['status'] === 'paused')
                            <x-button amber icon="play">Starten</x-button>
                        @endif
                    </div>
                </div>
                <div class="bg-gray-900 pb-10 mt-6">
                    <h2 class="px-4 text-base font-semibold leading-7 text-white sm:px-6 lg:px-8">
                        zu versendende E-Mails
                    </h2>
                    <table class="mt-6 w-full whitespace-nowrap text-left">
                        <thead class="border-b border-white/10 text-sm leading-6 text-white">
                        <tr>
                            <th scope="col" class="py-2 pl-4 pr-8 font-semibold sm:pl-6 lg:pl-8">E-Mail</th>
                            <th scope="col" class="py-2 pl-0 pr-8 font-semibold sm:table-cell">Name</th>
                            <th scope="col" class="py-2 pl-0 pr-4 font-semibold sm:pr-8 sm:text-left lg:pr-20">
                                Zu versenden am
                            </th>
                            <th scope="col" class="py-2 pl-0 pr-8 font-semibold md:table-cell lg:pr-20">
                                Versendet am
                            </th>
                            <th scope="col" class="py-2 pl-0 pr-8 font-semibold md:table-cell lg:pr-20"></th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">

                        @foreach($emails as $email)
                            <tr wire:key="email_{{ $email['id'] }}">
                                <td class="py-4 pl-4 pr-8 sm:pl-6 lg:pl-8">
                                    <div class="flex items-center gap-x-4">
                                        <div class="truncate text-sm font-medium leading-6 text-white">{{ $email['email_address']['address'] }}</div>
                                    </div>
                                </td>
                                <td class="py-4 pl-0 pr-4 sm:table-cell sm:pr-8">
                                    <div class="flex items-center gap-x-4">
                                        <div class="truncate text-sm font-medium leading-6 text-white">{{ $email['email_address']['name'] }}</div>
                                    </div>
                                </td>
                                <td class="py-4 pl-0 pr-4 text-sm leading-6 sm:pr-8 lg:pr-20">
                                    <div class="flex items-center gap-x-4">
                                        <div class="truncate text-sm font-medium leading-6 text-white">
                                            {{ \Carbon\Carbon::parse($email['send_at'])->diffForHumans(short: true) }}
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 pl-0 pr-4 text-sm leading-6 sm:pr-8 lg:pr-20">
                                    {{ $email['sent_at'] }}
                                </td>
                                <td class="py-4 pl-0 pr-4 text-sm leading-6 sm:pr-8 lg:pr-20">
                                    @if(!$email['send_at'])
                                        <a href="#" class="text-negative-400 hover:text-negative-300">Löschen</a>
                                    @endif
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
