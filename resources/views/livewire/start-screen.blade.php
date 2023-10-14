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
                <form class="my-12" wire:submit="send">
                    <div class="space-y-12">
                        <div class="border-b border-white/10 pb-12">
                            <h2 class="text-base font-semibold leading-7 text-white">E-Mail Server Einstellungen</h2>
                            <p class="mt-1 text-sm leading-6 text-gray-400">
                                Trage hier die Zugangsdaten deines E-Mail
                                Servers ein, damit die App in deinem Namen die Empfänger anschreiben kann.
                            </p>

                            <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                <div class="sm:col-span-3">
                                    <x-input wire:model="username" label="Benutzername" placeholder="Benutzername" />
                                </div>

                                <div class="sm:col-span-3">
                                    <x-input type="password" wire:model="password" label="Passwort" placeholder="Passwort" />
                                </div>

                                <div class="sm:col-span-2">
                                    <x-input wire:model="server" label="SMTP-Server" placeholder="SMTP-Server" />
                                </div>

                                <div class="sm:col-span-2">
                                    <x-input wire:model="port" label="SMTP-Port" placeholder="SMTP-Port" />
                                </div>

                                <div class="sm:col-span-2">
                                    <x-select
                                        :clearable="false"
                                        :options="$encryptionOptions"
                                        option-label="label"
                                        option-value="value"
                                        wire:model="encryption" label="SMTP-Verschlüsselung" placeholder="SMTP-Verschlüsselung" />
                                </div>

                                <div class="sm:col-span-6">
                                    <x-input wire:model="recipients" label="Test-Empfänger" placeholder="Test-Empfänger" corner-hint="kommagetrennt"/>
                                </div>

                                <div class="sm:col-span-3">
                                    <x-select
                                        :clearable="false"
                                        :options="$emailTypeOptions"
                                        option-label="label"
                                        option-value="value"
                                        wire:model="encryption" label="E-Mail Typ" placeholder="E-Mail Typ" />
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-center gap-x-6">
                        <button type="submit"
                                class="cursor-pointer rounded-md bg-indigo-500 px-3 py-2 text-xl font-semibold text-white shadow-sm hover:bg-indigo-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">
                            E-Mails abschicken
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>
