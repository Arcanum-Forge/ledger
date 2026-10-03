<aside x-data class="flex h-full w-64 flex-col bg-[#19160f]">
    <x-sidebar.brand />

    <nav class="flex-1 overflow-y-auto px-3 py-6">

        <x-sidebar.nav-section title="Overview">
            <x-sidebar.nav-link route="dashboard" icon="tabler-layout-dashboard" label="Dashboard" />
        </x-sidebar.nav-section>

        <x-sidebar.nav-section title="The World">
            <x-sidebar.nav-link route="kingdoms.index" icon="tabler-crown" label="Kingdoms" />
            <x-sidebar.nav-link route="regions.index" icon="tabler-map" label="Regions" />
            <x-sidebar.nav-link route="rulers.index" icon="tabler-user-star" label="Rulers" />
            <x-sidebar.nav-link route="leaders.index" icon="tabler-users-group" label="Leaders" />
            <x-sidebar.nav-link route="factions.index" icon="tabler-chess-knight" label="Factions" />
            <x-sidebar.nav-link route="monsters.index" icon="tabler-skull" label="Monsters" />
        </x-sidebar.nav-section>

        <x-sidebar.nav-section title="The Archive">
            <x-sidebar.nav-link route="authors.index" icon="tabler-writing" label="Authors" />
            <x-sidebar.nav-link route="records.index" icon="tabler-book-2" label="Records" />
            <x-sidebar.nav-link route="threat-reports.index" icon="tabler-alert-triangle" label="Threat Reports" />
        </x-sidebar.nav-section>


        <x-sidebar.nav-section title="The Keepers">
            <x-sidebar.nav-link route="users.index" icon="tabler-users" label="Users" />
        </x-sidebar.nav-section>

    </nav>
    <x-sidebar.footer />
</aside>