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
                        <x-button :href="route('start', ['withoutDisclaimer' => true])" outline icon="arrow-left">Zurück</x-button>
                    </div>
                    <div>
                        @if($countImported < 1)
                            <p class="text-amber-500 text-xs">
                                Die Import-Datei muss folgende Spalten enthalten und muss eine CSV mit Komma als
                                Trennzeichen sein:
                                <br>
                                <strong>text</strong>,<strong>subject</strong>
                            </p>
                        @else
                            <p class="text-green-500 text-xs">
                                <x-badge outline green>
                                    {{ $countImported }} Texte importiert
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
                            <x-input
                                corner-hint="Beispiel: Orange Pill"
                                label="" placeholder="Kampagnen-Typ" wire:model.live="name"/>
                        </div>
                        <div class="text-white">
                            @if($name)
                                <input type="file" wire:model="file">
                            @else
                                <x-badge outline amber>Name des Kampagnen-Typs</x-badge>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="bg-gray-900 pb-10 mt-6">
                    <div class="flex justify-between">
                        <h2 class="px-4 text-base font-semibold leading-7 text-white sm:px-6 lg:px-8">
                            E-Mail Texte
                        </h2>
                    </div>

                    @if(count($texts) < 1)
                        <div class="w-full flex justify-center">
                            <x-button lg wire:click="loadDemo" amber icon="download">
                                Load demo data
                            </x-button>
                        </div>
                    @endif

                    <div x-data="{ active: 0 }" class="mx-auto w-full min-h-[16rem] space-y-4">

                        @foreach($texts as $group => $ts)
                            <div
                                wire:key="group_{{ str($group)->slug() }}"
                                class="divide-y divide-gray-200 overflow-hidden rounded-lg bg-black shadow sm:grid grid-cols-1 sm:gap-px sm:divide-y-0">
                                <div
                                    x-data="{
                                        id: {{ $loop->iteration }},
                                        get expanded() {
                                            return this.active === this.id
                                        },
                                        set expanded(value) {
                                            this.active = value ? this.id : null
                                        },
                                    }"
                                    role="region" class="rounded-lg bg-amber-500 shadow">
                                    <h2>
                                        <button
                                            x-on:click="expanded = !expanded"
                                            :aria-expanded="expanded"
                                            class="flex w-full items-center justify-between px-6 py-4 text-xl font-bold"
                                        >
                                            <span>{{ $group }}</span>
                                            <span x-show="expanded" aria-hidden="true" class="ml-4">&minus;</span>
                                            <span x-show="!expanded" aria-hidden="true" class="ml-4">&plus;</span>
                                        </button>
                                    </h2>
                                    <div x-show="expanded" x-collapse>
                                        <div
                                            class="group divide-y divide-gray-200 space-y-4 relative bg-black p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-500">
                                            @foreach($ts as $t)
                                                <div wire:key="text_{{ $t['id'] }}" class="py-4">
                                                    <div>
                                                        <x-badge outline amber>
                                                            Betreff: {{ $t['subject'] }}
                                                        </x-badge>
                                                    </div>
                                                    <p class="mt-2 text-base text-gray-200">
                                                        {!! nl2br($t['text']) !!}
                                                    </p>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                            </div>
                        @endforeach

                    </div>

                </div>
            </div>
        </main>
    </div>
</div>
