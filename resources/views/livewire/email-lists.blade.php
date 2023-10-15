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
                    <div>
                        @if($countImported < 1)
                            <p class="text-amber-500 text-xs">
                                Die Import-Datei muss folgende Spalten enthalten und muss eine CSV mit Komma als Trennzeichen sein:
                                <br>
                                <strong>email</strong>,<strong>name</strong>,<strong>salutation</strong>
                            </p>
                        @else
                            <p class="text-green-500 text-xs">
                                <x-badge outline green>
                                    {{ $countImported }} E-Mail Adressen importiert
                                </x-badge>
                            </p>
                        @endif
                        @if($isInvalidCSV)
                            <x-badge outline red>
                                Die CSV Datei ist ungültig
                            </x-badge>
                        @endif
                    </div>
                    <div class="flex flex-col space-y-2">
                        <div>
                            <x-input label="" placeholder="Listen Name" wire:model.live="name"/>
                        </div>
                        <div class="text-white">
                            @if($name)
                                <input type="file" wire:model="file">
                            @else
                                <x-badge outline amber>Name der neuen Liste eingeben</x-badge>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="bg-gray-900 pb-10 mt-6">
                    <div class="flex justify-between">
                        <h2 class="px-4 text-base font-semibold leading-7 text-white sm:px-6 lg:px-8">
                            E-Mail Listen
                        </h2>
                    </div>
                    <table class="mt-6 w-full whitespace-nowrap text-left">
                        <thead class="border-b border-white/10 text-sm leading-6 text-white">
                        <tr>
                            <th scope="col" class="py-2 pl-0 font-semibold">
                                Name
                            </th>
                            <th scope="col" class="py-2 pl-0 font-semibold">
                                Anzahl der E-Mail Adressen
                            </th>
                            <th scope="col" class="py-2 pl-0 font-semibold">
                                Angeschriebene E-Mail Adressen
                            </th>
                            <th scope="col" class="py-2 pl-0 font-semibold"></th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5" wire:poll.keep-alive.15s="poll">

                        @foreach($lists as $list)
                            <tr wire:key="list_{{ $list['id'] }}">
                                <td class="py-4 pr-0">
                                    <div class="flex items-center gap-x-4">
                                        <div class="truncate text-sm font-medium leading-6 text-white">
                                            {{ $list['name']['de'] }}
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 pr-0">
                                    <div class="flex items-center gap-x-4">
                                        <div class="truncate text-sm font-medium leading-6 text-white">
                                            {{ $list['email_addresses_count'] }}
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 pr-0">
                                    <div class="truncate text-sm font-medium leading-6 text-white flex justify-between">
                                        <div>
                                            {{ $list['emailsSent'] }}
                                        </div>

                                        @if(round($list['emailsSent'] / $list['email_addresses_count'] === 0))
                                            <x-badge outline amber>
                                                Fortschritt: {{ round($list['emailsSent'] / $list['email_addresses_count'] * 100) }}%
                                            </x-badge>
                                        @else
                                            <x-badge outline green>
                                                Fortschritt: {{ round($list['emailsSent'] / $list['email_addresses_count'] * 100) }}%
                                            </x-badge>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-4 pr-0">
                                    {{--<div wire:click="delete({{ $list['id'] }})"
                                         class="cursor-pointer text-negative-400 hover:text-negative-300">
                                        Löschen
                                    </div>--}}
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
