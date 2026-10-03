<?php

use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts.landing')]
    class extends Component {

    public function render()
    {
        $pillars = [
            ['number' => '01', 'symbol' => 'crown', 'label' => 'Kingdoms', 'title' => 'The Realms', 'description' => 'Map the powers of your world. Record rulers, populations, territories, alliances, and the histories of every realm.'],
            ['number' => '02', 'symbol' => 'chess-knight', 'label' => 'Factions', 'title' => 'Those Who Rule', 'description' => 'Document knightly orders, merchant houses, secret societies, cults, circles, and every force shaping the realm.'],
            ['number' => '03', 'symbol' => 'book-2', 'label' => 'Lore & Records', 'title' => 'The History', 'description' => 'Preserve wars, treaties, biographies, prophecies, mysteries, disasters, and the events that defined an age.'],
            ['number' => '04', 'symbol' => 'skull', 'label' => 'Monsters', 'title' => 'The Bestiary', 'description' => 'Catalogue creatures discovered across the realm, from common beasts to the things best left unnamed.'],
            ['number' => '05', 'symbol' => 'swords', 'label' => 'Threat Reports', 'title' => 'The Intelligence', 'description' => 'Track emerging threats, sightings, hostile movements, disappearances, and dangers facing the realm.'],
            ['number' => '06', 'symbol' => 'archive', 'label' => 'The Archive', 'title' => 'One Living Record', 'description' => 'Bring every part of your world together in one evolving archive built for stories with depth and history.'],
        ];

        return $this->view([
            'pillars' => $pillars,
        ]);
    }

};