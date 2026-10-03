<?php

namespace Database\Seeders;

use App\Models\Faction;
use App\Models\Kingdom;
use App\Models\Leader;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FactionSeeder extends Seeder
{
    public function run(): void
    {
        // Looks up a Kingdom by name so the seed data can keep referring to
        // headquarters by name below, instead of hardcoding ids that would
        // shift depending on seeding order. Returns null for anything not
        // found (or intentionally unknown, like The Veiled Hand's).
        $kingdomId = fn(string $name): ?int => Kingdom::where('name', $name)->value('id');

        // Same idea for leaders, except here we also create the Leader
        // record if it doesn't exist yet — leaders don't have their own
        // seeder elsewhere, so this is their point of origin.
        $leaderId = fn(string $name): int => Leader::firstOrCreate(
            ['slug' => Str::slug($name)],
            ['name' => $name],
        )->id;

        Faction::create([
            'slug' => 'silver-wardens',
            'name' => 'The Silver Wardens',
            'title' => 'Order of the Sacred Shield',
            'description' => 'An ancient knightly order sworn to defend the innocent and preserve the old laws of the realm. Their influence extends throughout the western kingdoms.',
            'type' => 'Knightly Order',
            'leader_id' => $leaderId('Grand Marshal Edric Vale'),
            'kingdom_id' => $kingdomId('Auren'),
            'members' => '8,400',
            'alignment' => 'Lawful Good',
            'influence' => 78,
            'status' => 'Active',
            'status_description' => 'Mobilizing forces near the eastern frontier.',
        ]);

        Faction::create([
            'slug' => 'merchant-guild',
            'name' => 'The Golden Ledger',
            'title' => 'Guild of Merchant Houses',
            'description' => 'A powerful alliance of merchant families controlling trade routes, banking houses and several of the realm’s most profitable ports.',
            'type' => 'Merchant Guild',
            'leader_id' => $leaderId('Lady Celestine Varr'),
            'kingdom_id' => $kingdomId('Auren'),
            'members' => '12,700',
            'alignment' => 'Lawful Neutral',
            'influence' => 91,
            'status' => 'Expanding',
            'status_description' => 'Three new trade routes established this year.',
        ]);

        Faction::create([
            'slug' => 'veil',
            'name' => 'The Veiled Hand',
            'title' => 'Keepers of Hidden Knowledge',
            'description' => 'A secretive network of spies, scholars and informants. Little is known of their true purpose, though their agents are believed to operate in every major city.',
            'type' => 'Secret Society',
            'leader_id' => null, // leader unknown, by design
            'kingdom_id' => null, // headquarters unknown, by design
            'members' => 'Unconfirmed',
            'alignment' => 'Neutral',
            'influence' => 64,
            'status' => 'Unconfirmed',
            'status_description' => 'Multiple agents reported missing in Valedorn.',
        ]);

        Faction::create([
            'slug' => 'ashen-covenant',
            'name' => 'The Ashen Covenant',
            'title' => 'Disciples of the Burning Star',
            'description' => 'A radical religious movement preaching that the current age must end before a new world can be born from sacred fire.',
            'type' => 'Cult',
            'leader_id' => $leaderId('The Ashen Prophet'),
            'kingdom_id' => $kingdomId('Drakmor'),
            'members' => '3,200+',
            'alignment' => 'Chaotic Evil',
            'influence' => 73,
            'status' => 'Hostile',
            'status_description' => 'Covenant activity detected beyond Drakmor.',
        ]);

        Faction::create([
            'slug' => 'emerald-circle',
            'name' => 'The Emerald Circle',
            'title' => 'Keepers of the Old Forest',
            'description' => 'A gathering of druids, rangers and ancient spirits dedicated to protecting the natural world from uncontrolled expansion and industrialization.',
            'type' => 'Druidic Circle',
            'leader_id' => $leaderId('Archdruid Elowen'),
            'kingdom_id' => $kingdomId('Elaria'),
            'members' => '2,100',
            'alignment' => 'Neutral Good',
            'influence' => 47,
            'status' => 'Stable',
            'status_description' => 'Forest borders remain undisturbed.',
        ]);

        Faction::create([
            'slug' => 'iron-legion',
            'name' => 'The Iron Legion',
            'title' => 'Army Without a Banner',
            'description' => 'A highly disciplined mercenary army whose loyalty belongs to gold rather than crown. Their soldiers have fought in nearly every major conflict of the last century.',
            'type' => 'Mercenary Company',
            'leader_id' => $leaderId('General Garran Holt'),
            'kingdom_id' => $kingdomId('Valedorn'),
            'members' => '14,000',
            'alignment' => 'Neutral',
            'influence' => 82,
            'status' => 'Contracted',
            'status_description' => 'Currently serving Valedorn under royal contract.',
        ]);
    }
}