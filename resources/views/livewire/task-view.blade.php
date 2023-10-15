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
                        @if(collect($emails)->whereNotNull('sent_at')->count() !== collect($emails)->count())
                            @if($task['status'] === 'running')
                                <x-button amber icon="stop" wire:click="pause">Stoppen</x-button>
                            @elseif($task['status'] === 'paused')
                                <x-button amber icon="play" wire:click="play">Starten</x-button>
                            @endif
                        @endif
                    </div>
                </div>
                <div class="bg-gray-900 pb-10 mt-6">
                    <div class="flex justify-between">
                        <h2 class="px-4 text-base font-semibold leading-7 text-white sm:px-6 lg:px-8">
                            zu versendende E-Mails
                        </h2>
                        <div class="font-bold text-white">
                            Kampagne: <span class="text-amber-500">{{ $task['text_type'] }}</span> an <span class="text-amber-500">{{ $task['email_list'] }}</span>
                        </div>
                        <div>
                            <x-badge outline green>
                                Fortschritt: {{ round(collect($emails)->whereNotNull('sent_at')->count() / collect($emails)->count() * 100) }}%
                            </x-badge>
                        </div>
                        <div>
                            <x-badge outline amber>
                                {{ collect($emails)->whereNotNull('sent_at')->count() }} / {{ collect($emails)->count() }} E-Mails versendet
                            </x-badge>
                        </div>
                    </div>
                    <table class="mt-6 w-full whitespace-nowrap text-left">
                        <thead class="border-b border-white/10 text-sm leading-6 text-white">
                        <tr>
                            <th scope="col" class="py-2 pl-0 font-semibold">
                                E-Mail</th>
                            <th scope="col" class="py-2 pl-0 font-semibold">
                                Name
                            </th>
                            <th scope="col" class="py-2 pl-0 font-semibold">
                                Zu versenden am
                            </th>
                            <th scope="col" class="py-2 pl-0 font-semibold">
                                Versendet am
                            </th>
                            <th scope="col" class="py-2 pl-0 font-semibold"></th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5" wire:poll.keep-alive.15s="poll">

                        @foreach($emails as $email)
                            <tr wire:key="email_{{ $email['id'] }}">
                                <td class="py-4 pr-0">
                                    <div class="flex items-center gap-x-4">
                                        <div class="truncate text-sm font-medium leading-6 text-white">
                                            {{ $email['email_address']['address'] }}
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 pr-0">
                                    <div class="flex items-center gap-x-4">
                                        <div class="truncate text-sm font-medium leading-6 text-white">
                                            {{ $email['email_address']['name'] }}
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 pr-0">
                                    <div class="flex items-center gap-x-4">
                                        <div class="truncate text-sm font-medium leading-6 text-white">
                                            {{ \Carbon\Carbon::parse($email['send_at'])->diffForHumans(short: true) }}
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 pr-0">
                                    <div class="truncate text-sm font-medium leading-6 text-white">
                                        {{ $email['sent_at'] }}
                                    </div>
                                </td>
                                <td class="py-4 pr-0">
                                    @if(!$email['sent_at'])
                                        <div wire:click="delete({{ $email['id'] }})"
                                             class="cursor-pointer text-negative-400 hover:text-negative-300">
                                            Löschen
                                        </div>
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
