<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Record;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RecordSeeder extends Seeder
{
    public function run(): void
    {
        // Creates/looks up an Author by name, same pattern as
        // FactionSeeder's leaderId. 'Unknown' authorship isn't a real
        // author — it's the absence of one — so it resolves to null
        // rather than creating an Author literally named "Unknown".
        $authorId = fn(?string $name): ?int => $name === null || $name === 'Unknown'
            ? null
            : Author::firstOrCreate(['slug' => Str::slug($name)], ['name' => $name])->id;

        Record::create([
            'slug' => 'fall-of-black-crown',
            'title' => 'The Fall of the Black Crown',
            'excerpt' => 'The final battle of the War of Ashes ended the reign of Emperor Vael and shattered the old Drakmorian Empire.',
            'content' => <<<'TEXT'
The final battle of the War of Ashes ended the reign of Emperor Vael and shattered the old Drakmorian Empire.

The armies of the eastern kingdoms had spent seventeen years fighting against the forces of the Black Crown. The final confrontation took place beneath the ruined walls of Drakmor, where the imperial standard was finally brought down.

Contemporary accounts disagree on the exact circumstances surrounding Emperor Vael's death. Some records claim that he fell during the final assault, while others suggest that he disappeared into the ruins beneath the imperial palace.

Whatever the truth, the collapse of the Black Crown marked the end of the old empire and began the political age that would eventually give rise to the kingdoms recognized today.
TEXT,
            'category' => 'Historical Event',
            'era' => 'Age of Ash',
            'date' => 'Year 411',
            'author_id' => $authorId('Royal Historians'),
            'importance' => 'Critical',
            'confidential' => false,
        ]);

        Record::create([
            'slug' => 'treaty-of-golden-fields',
            'title' => 'The Treaty of Golden Fields',
            'excerpt' => 'A historic agreement between Auren, Elaria and the northern city-states that established the modern western borders.',
            'content' => <<<'TEXT'
The Treaty of Golden Fields established the political boundaries of the western realms following decades of territorial disputes.

Representatives from Auren, Elaria and the northern city-states gathered at Golden Fields to negotiate a lasting settlement.

The treaty remains one of the foundational documents of the modern western kingdoms.
TEXT,
            'category' => 'Treaty',
            'era' => 'Age of Crowns',
            'date' => 'Year 623',
            'author_id' => $authorId('Royal Archive'),
            'importance' => 'Important',
            'confidential' => false,
        ]);

        Record::create([
            'slug' => 'first-dragon-sighting',
            'title' => 'The First Dragon Sighting',
            'excerpt' => 'An incomplete account describing a winged creature observed above the northern mountains. The authenticity remains disputed.',
            'content' => <<<'TEXT'
An incomplete account describing a winged creature observed above the northern mountains.

The original manuscript is damaged beyond complete restoration. Several witnesses claimed to have observed an enormous winged creature crossing the moonlit sky.

No physical evidence has ever been recovered.

The authenticity of the account remains disputed among royal historians.
TEXT,
            'category' => 'Mystery',
            'era' => 'Before the Crown',
            'date' => 'Unknown',
            'author_id' => $authorId('Unknown'),
            'importance' => 'Notable',
            'confidential' => true,
        ]);

        Record::create([
            'slug' => 'rise-of-aldermar',
            'title' => 'The Rise of Aldemar IV',
            'excerpt' => 'The recorded history of Aldemar IV, from his early military campaigns to his coronation as King of Auren.',
            'content' => <<<'TEXT'
The recorded history of Aldemar IV, from his early military campaigns to his coronation as King of Auren.

Aldemar's early years were defined by military campaigns along the western frontier. His victories established his reputation among the noble houses of Auren.

Following the death of his predecessor, Aldemar was crowned King and began the reforms that would define his reign.
TEXT,
            'category' => 'Biography',
            'era' => 'Age of Crowns',
            'date' => 'Year 781',
            'author_id' => $authorId('Brother Cael'),
            'importance' => 'Notable',
            'confidential' => false,
        ]);

        Record::create([
            'slug' => 'war-of-the-three-kings',
            'title' => 'The War of the Three Kings',
            'excerpt' => 'A seventeen-year conflict that reshaped the eastern realms and created the borders still recognized today.',
            'content' => <<<'TEXT'
The War of the Three Kings lasted seventeen years and reshaped the eastern realms.

The conflict began as a succession dispute but quickly expanded into a war involving nearly every major power in the region.

The eventual peace established borders that remain recognized to this day.
TEXT,
            'category' => 'War',
            'era' => 'Age of Kings',
            'date' => 'Year 512–529',
            'author_id' => $authorId('Eastern Archives'),
            'importance' => 'Critical',
            'confidential' => false,
        ]);

        Record::create([
            'slug' => 'the-silent-plague',
            'title' => 'The Silent Plague',
            'excerpt' => 'A mysterious illness that emptied entire villages without leaving any known trace of its origin.',
            'content' => <<<'TEXT'
The Silent Plague remains one of the most mysterious disasters recorded in the royal archives.

Entire villages were reportedly emptied within weeks. Those who survived described little more than an unexplained silence preceding the deaths.

No definitive cause has ever been established.
TEXT,
            'category' => 'Disaster',
            'era' => 'Age of Shadows',
            'date' => 'Year 302',
            'author_id' => $authorId('Temple Records'),
            'importance' => 'Important',
            'confidential' => false,
        ]);

        Record::create([
            'slug' => 'founding-of-elaria',
            'title' => 'The Founding of Elaria',
            'excerpt' => 'Ancient elven records describing the first gathering of the forest clans beneath the Emerald Tree.',
            'content' => <<<'TEXT'
Ancient elven records describe the first gathering of the forest clans beneath the Emerald Tree.

The gathering eventually became the foundation of Elaria and established the traditions that continue to guide the realm.
TEXT,
            'category' => 'Foundation',
            'era' => 'First Age',
            'date' => 'Year 12',
            'author_id' => $authorId('Emerald Circle'),
            'importance' => 'Important',
            'confidential' => false,
        ]);

        Record::create([
            'slug' => 'the-ashen-prophecy',
            'title' => 'The Ashen Prophecy',
            'excerpt' => 'A forbidden prophecy concerning a coming age of fire and the return of an unnamed ancient power.',
            'content' => <<<'TEXT'
A forbidden prophecy concerning a coming age of fire and the return of an unnamed ancient power.

The surviving fragments are kept under restricted access within the deepest section of the archive.

Several interpretations exist, but none have been accepted as authoritative.
TEXT,
            'category' => 'Prophecy',
            'era' => 'Unknown',
            'date' => 'Unknown',
            'author_id' => $authorId('Unknown'),
            'importance' => 'Critical',
            'confidential' => true,
        ]);
    }
}