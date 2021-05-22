<?php

namespace Database\Seeders;

use App\Classes\BonusResourceCard;
use App\Classes\BuildingCard;
use App\Classes\ResourceCard;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $plotId = DB::table('card_types')->where('name', 'Plot')->value('id');
        $resourceId = DB::table('card_types')->where('name', 'Resource')->value('id');
        $populationId = DB::table('card_types')->where('name', 'Population')->value('id');
        $technologyId = DB::table('card_types')->where('name', 'Technology')->value('id');
        $unitId = DB::table('card_types')->where('name', 'Unit')->value('id');
        $buildingId = DB::table('card_types')->where('name', 'Building')->value('id');
        $wonderId = DB::table('card_types')->where('name', 'Wonder')->value('id');
        $bonusId = DB::table('card_types')->where('name', 'Bonus resource')->value('id');

        $createdAt = Carbon::now();
        $updatedAt = Carbon::now();

        DB::table('cards')->insert([
            //Resource cards
            [
                'name' => '1 Commerce',
                'card_type_id' => $resourceId,
                'properties' => json_encode(new ResourceCard('Commerce', 1)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'name' => '5 Commerce',
                'card_type_id' => $resourceId,
                'properties' => json_encode(new ResourceCard('Commerce', 5)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'name' => '1 Production',
                'card_type_id' => $resourceId,
                'properties' => json_encode(new ResourceCard('Production', 1)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'name' => '5 Production',
                'card_type_id' => $resourceId,
                'properties' => json_encode(new ResourceCard('Production', 5)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'name' => '1 Food',
                'card_type_id' => $resourceId,
                'properties' => json_encode(new ResourceCard('Food', 1)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'name' => '5 Food',
                'card_type_id' => $resourceId,
                'properties' => json_encode(new ResourceCard('Commerce', 5)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],

            //Bonus resource cards
            [
                'name' => '2 Bonus Commerce',
                'card_type_id' => $bonusId,
                'properties' => json_encode(new BonusResourceCard('Commerce', 2, false)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'name' => '3 Bonus Commerce',
                'card_type_id' => $bonusId,
                'properties' => json_encode(new BonusResourceCard('Commerce', 3, false)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'name' => '2 Bonus Production',
                'card_type_id' => $bonusId,
                'properties' => json_encode(new BonusResourceCard('Production', 2, false)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'name' => '3 Bonus Production',
                'card_type_id' => $bonusId,
                'properties' => json_encode(new BonusResourceCard('Production', 3, false)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'name' => '2 Bonus Food',
                'card_type_id' => $bonusId,
                'properties' => json_encode(new BonusResourceCard('Food', 2, false)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'name' => '3 Bonus Food',
                'card_type_id' => $bonusId,
                'properties' => json_encode(new BonusResourceCard('Food', 3, false)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'name' => '1 Bonus Wild',
                'card_type_id' => $bonusId,
                'properties' => json_encode(new BonusResourceCard('Wild', 1, true)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'name' => '2 Bonus Wild',
                'card_type_id' => $bonusId,
                'properties' => json_encode(new BonusResourceCard('Wild', 2, true)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],

            //Building cards
            [
                'name' => 'Market',
                'card_type_id' => $buildingId,
                'properties' => json_encode(new BuildingCard(6, '100% commerce from base plot value', 1)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'name' => 'Barrack',
                'card_type_id' => $buildingId,
                'properties' => json_encode(new BuildingCard(4, 'Chariots and swordsmen steal 2 population', 2)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'name' => 'Forge',
                'card_type_id' => $buildingId,
                'properties' => json_encode(new BuildingCard(6, '100% production from base plot value', 3)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'name' => 'Temple',
                'card_type_id' => $buildingId,
                'properties' => json_encode(new BuildingCard(4, 'Grants 1 happiness and 1 culture ', 4)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'name' => 'Library',
                'card_type_id' => $buildingId,
                'properties' => json_encode(new BuildingCard(8, 'Grants 1 culture and -2 technology costs', 5)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'name' => 'Monastery',
                'card_type_id' => $buildingId,
                'properties' => json_encode(new BuildingCard(6, 'Grants 2 culture and -1 technology costs', 6)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'name' => 'Granary',
                'card_type_id' => $buildingId,
                'properties' => json_encode(new BuildingCard(6, '100% food from base plot value', 7)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'name' => 'Colosseum',
                'card_type_id' => $buildingId,
                'properties' => json_encode(new BuildingCard(4, 'Grants 2 happiness', 8)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'name' => 'Walls',
                'card_type_id' => $buildingId,
                'properties' => json_encode(new BuildingCard(4, 'Repels 1 attacker', 9)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
        ]);
    }
}
