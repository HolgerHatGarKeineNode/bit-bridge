<div class="min-h-full">
    <div class="py-10">
        <header>
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between">
                    <img src="{{ asset('einundzwanzig-horizontal-inverted.svg') }}" alt="Logo">
                    <div>

                    </div>
                    <h1 class="text-3xl font-bold leading-tight tracking-tight text-gray-200">Bit-Bridge</h1>
                </div>
            </div>
        </header>
        <main>

            @if($currentStep === 'disclaimer')
                <div class="bg-gray-900">
                    <div class="mx-auto max-w-7xl px-6 py-16">
                        <h2 class="text-2xl font-bold leading-10 tracking-tight text-white">Vorsicht</h2>
                        <p class="max-w-2xl text-base leading-7 text-gray-300">Bitte beachte folgende Dinge, bei der
                            Nutzung dieser App:</p>
                        <div class="mt-4">
                            <dl class="space-y-2">
                                <div>
                                    <dt class="text-base font-semibold leading-7 text-amber-500">
                                        Die E-Mails werden unter deinem Namen mit deiner E-Mail verschickt.
                                    </dt>
                                </div>
                                <div>
                                    <dt class="text-base font-semibold leading-7 text-amber-500">
                                        Deine E-Mail-Server Daten werden nur lokal auf deinem Rechner verschlüsselt
                                        gespeichert und nicht an Dritte weitergegeben.
                                    </dt>
                                </div>
                                <div>
                                    <dt class="text-base font-semibold leading-7 text-amber-500">
                                        Du kannst den Quellcode dieser App auf GitHub einsehen. <a
                                            href="https://github.com/HolgerHatGarKeineNode/bit-bridge"
                                            class="underline">Quellcode</a>
                                    </dt>
                                </div>
                                <div>
                                    <dt class="text-base font-semibold leading-7 text-amber-500">
                                        Die App stellt lediglich die technische Möglichkeit zum Versenden
                                        von E-Mails bereit und übernimmt keine Verantwortung für den Inhalt der
                                        versendeten Nachrichten.
                                    </dt>
                                </div>
                                <div>
                                    <x-button amber lg wire:click="continue">Weiter</x-button>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            @endif

            @if($currentStep === 'smtp')
                <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
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
                                </div>

                                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                    <div class="sm:col-span-3">
                                        <div class="w-full">
                                            <x-input
                                                hint="Dein Name wird als Absender angezeigt."
                                                wire:model="name" label="Deine Name" placeholder="Dein Name"/>
                                        </div>
                                    </div>
                                    <div class="sm:col-span-3">
                                        <div class="w-full">
                                            <x-input
                                                corner-hint="Wir als Absender angezeigt."
                                                hint="Hier werden die Test-E-Mails hin versendet."
                                                type="email" wire:model="recipients" label="Deine E-Mail Adresse"
                                                     placeholder="Deine E-Mail Adresse"/>
                                        </div>
                                    </div>

                                    <div class="sm:col-span-3">
                                        <x-input
                                            hint="Der Benutzername wird für die Authentifizierung beim E-Mail Server benötigt."
                                            wire:model="username" label="Benutzername" placeholder="Benutzername"/>
                                    </div>

                                    <div class="sm:col-span-3">
                                        <x-input
                                            hint="Das Passwort wird für die Authentifizierung beim E-Mail Server benötigt."
                                            type="password" wire:model="password" label="Passwort"
                                                 placeholder="Passwort"/>
                                    </div>

                                    <div class="sm:col-span-2">
                                        <x-input
                                            hint="Der SMTP-Server wird für den Versand der E-Mails benötigt."
                                            wire:model="server" label="SMTP-Server" placeholder="SMTP-Server"/>
                                    </div>

                                    <div class="sm:col-span-2">
                                        <x-input
                                            hint="Der SMTP-Port wird für den Versand der E-Mails benötigt."
                                            wire:model="port" label="SMTP-Port" placeholder="SMTP-Port"/>
                                    </div>

                                    <div class="sm:col-span-2">
                                        <x-select
                                            hint="Die Verschlüsselung wird für den Versand der E-Mails benötigt."
                                            :clearable="false"
                                            :options="$encryptionOptions"
                                            option-label="label"
                                            option-value="value"
                                            wire:model="encryption" label="SMTP-Verschlüsselung"
                                            placeholder="SMTP-Verschlüsselung"/>
                                    </div>

                                    <div class="sm:col-span-3">
                                        <x-button amber wire:click.prevent="test">Test E-Mail versenden</x-button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            @endif

            @if($currentStep === 'start')
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
                        @elseif($countTexts < 1)
                            <div>
                                <x-badge outline red>E-Mail Texte fehlen</x-badge>
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
                            <tbody class="divide-y divide-white/5" wire:poll.keep-alive.15s="poll">

                            @foreach($campaigns as $campaign)
                                <tr wire:key="campaign_{{ $campaign['id'] }}">
                                    <td class="py-4 pl-4 pr-8 sm:pl-6 lg:pl-8">
                                        <div class="flex items-center gap-x-4">
                                            <div
                                                class="truncate text-sm font-medium leading-6 text-white">{{ $campaign['text_type'] }}</div>
                                        </div>
                                    </td>
                                    <td class="py-4 pl-0 pr-4 sm:table-cell sm:pr-8">
                                        <div class="flex gap-x-3">
                                            <div
                                                class="font-mono text-sm leading-6 text-gray-400">{{ $campaign['email_list'] }}</div>
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
                                        @if(collect($campaign['emails'])->whereNotNull('sent_at')->count() === collect($campaign['emails'])->count())
                                            <div class="flex items-center justify-end gap-x-2 sm:justify-start">
                                                <div class="flex-none rounded-full p-1 text-green-400 bg-green-400/10">
                                                    <div class="h-1.5 w-1.5 rounded-full bg-current"></div>
                                                </div>
                                                <div class="text-white sm:block">fertig</div>
                                            </div>
                                        @else
                                            <div class="flex items-center justify-end gap-x-2 sm:justify-start">
                                                <div class="flex-none rounded-full p-1 text-amber-400 bg-amber-400/10">
                                                    <div class="h-1.5 w-1.5 rounded-full bg-current"></div>
                                                </div>
                                                <div class="text-white sm:block">läuft</div>
                                            </div>
                                        @endif
                                        <div>
                                            <x-badge outline amber>
                                                {{ collect($campaign['emails'])->whereNotNull('sent_at')->count() }}
                                                / {{ collect($campaign['emails'])->count() }} E-Mails versendet
                                            </x-badge>
                                        </div>
                                    </td>
                                    <td class="py-4 pl-0 pr-4 text-sm leading-6 sm:pr-8 lg:pr-20">
                                        <a href="{{ route('task', ['task' => $campaign['id']]) }}"
                                           class="text-amber-500 hover:text-amber-400">
                                            Öffnen
                                        </a>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

        </main>
    </div>
</div>
