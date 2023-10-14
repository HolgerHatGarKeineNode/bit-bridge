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
                </div>
                <form class="my-12" wire:submit="send">
                    <div class="space-y-12">
                        <div class="border-b border-white/10 pb-6">
                            <div class="flex w-full justify-between">
                                <div class="w-6/12">
                                    <h2 class="text-base font-semibold leading-7 text-white">E-Mail Server
                                        Einstellungen</h2>
                                    <p class="mt-1 text-sm leading-6 text-gray-400">
                                        Trage hier die Zugangsdaten deines E-Mail
                                        Servers ein, damit die App in deinem Namen die Empfänger anschreiben kann.
                                    </p>
                                    <div class="w-full">
                                        @if($testSent)
                                            <x-badge green>
                                                Eine Test-E-Mail wurde an deine Adresse {{ $recipients }} versendet.
                                            </x-badge>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex flex-col space-y-2">
                                    <div class="w-full">
                                        <x-input wire:model="name" label="Deine Name" placeholder="Dein Name"/>
                                    </div>
                                </div>
                                <div class="flex flex-col space-y-2">
                                    <div class="w-full">
                                        <x-input type="email" wire:model="recipients" label="Deine E-Mail Adresse"
                                                 placeholder="Deine E-Mail Adresse"/>
                                    </div>
                                    <div>
                                        <x-button wire:click.prevent="test">Test E-Mail versenden</x-button>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                <div class="sm:col-span-3">
                                    <x-input wire:model="username" label="Benutzername" placeholder="Benutzername"/>
                                </div>

                                <div class="sm:col-span-3">
                                    <x-input type="password" wire:model="password" label="Passwort"
                                             placeholder="Passwort"/>
                                </div>

                                <div class="sm:col-span-2">
                                    <x-input wire:model="server" label="SMTP-Server" placeholder="SMTP-Server"/>
                                </div>

                                <div class="sm:col-span-2">
                                    <x-input wire:model="port" label="SMTP-Port" placeholder="SMTP-Port"/>
                                </div>

                                <div class="sm:col-span-2">
                                    <x-select
                                        :clearable="false"
                                        :options="$encryptionOptions"
                                        option-label="label"
                                        option-value="value"
                                        wire:model="encryption" label="SMTP-Verschlüsselung"
                                        placeholder="SMTP-Verschlüsselung"/>
                                </div>

                                <div class="sm:col-span-6">
                                    <hr class="border border-white">
                                </div>

                                <div class="sm:col-span-3">
                                    <x-select
                                        :clearable="false"
                                        :options="$emailTypeOptions"
                                        option-label="label"
                                        option-value="value"
                                        wire:model.live="type" label="E-Mail Typ" placeholder="E-Mail Typ"/>
                                </div>

                                <div class="sm:col-span-3">
                                    <x-select
                                        :clearable="false"
                                        :options="$emailListsOptions"
                                        option-label="label"
                                        option-value="value"
                                        wire:model.live="list" label="E-Mail Liste" placeholder="E-Mail Liste"/>
                                </div>

                                <div class="sm:col-span-6">
                                    @if($count > 0)
                                        <div class="w-full flex justify-center">
                                            <div>
                                                <x-badge amber outline>
                                                    Mit dieser Einstellung würden {{ $count }} E-Mails versendet werden.
                                                </x-badge>
                                            </div>
                                        </div>
                                    @endif
                                    @if($count < 1 && $warning)
                                        <div class="w-full flex justify-center">
                                            <div>
                                                <x-badge warning outline>
                                                    Es wurden keine E-Mails gefunden oder alle Empfänger wurden schon
                                                    einmal angeschrieben.
                                                </x-badge>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="mt-4 flex items-center justify-center gap-x-6">
                        @if(!$warning && $count > 0 && $testSent)
                            <x-button type="submit" amber icon="play">
                                Kampagne starten
                            </x-button>
                        @elseif(!$testSent)
                            <x-badge negative>
                                Du muss erst eine Test-E-Mail versenden, damit die Kampagne gestartet werden kann.
                            </x-badge>
                        @elseif($count < 1)
                            <x-badge amber>
                                Wähle eine E-Mail Liste und einen E-Mail-Typen aus, damit die Kampagne gestartet werden kann.
                            </x-badge>
                        @endif
                    </div>

                </form>
            </div>
        </main>
    </div>
</div>
