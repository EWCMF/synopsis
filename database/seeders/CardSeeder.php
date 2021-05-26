<?php

namespace Database\Seeders;

use App\Classes\BonusResourceCard;
use App\Classes\BuildingCard;
use App\Classes\PlotCard;
use App\Classes\PopulationCard;
use App\Classes\ResourceCard;
use App\Classes\TechnologyCard;
use App\Classes\UnitCard;
use App\Classes\WonderCard;
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

            //Population cards
            [
                'name' => '1 Population',
                'card_type_id' => $populationId,
                'properties' => json_encode(new PopulationCard('Grants 1 population', 1)),
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

            //Wonder cards
            [
                'name' => 'The Pyramids',
                'card_type_id' => $wonderId,
                'properties' => json_encode(new WonderCard(20, '+2 Free resources every turn', 1)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'name' => 'The Colossus',
                'card_type_id' => $wonderId,
                'properties' => json_encode(new WonderCard(12, 'Grants 1 culture, immune to attacks for 2 rounds', 2)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'name' => 'The Hanging Gardens',
                'card_type_id' => $wonderId,
                'properties' => json_encode(new WonderCard(12, 'Grants 3 happiness and 1 culture', 3)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'name' => 'The Great Library',
                'card_type_id' => $wonderId,
                'properties' => json_encode(new WonderCard(16, 'Grants 1 culture and -3 technology cost', 4)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'name' => 'The Great Walls',
                'card_type_id' => $wonderId,
                'properties' => json_encode(new WonderCard(16, 'Acts as permanent walls', 5)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],

            //Unit cards
            [
                'name' => 'Archer ',
                'card_type_id' => $unitId,
                'properties' => json_encode(new UnitCard('Cannot attack but defends against all units', 1)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'name' => 'Axeman',
                'card_type_id' => $unitId,
                'properties' => json_encode(new UnitCard('Destroys 1 building, defends against Swordsman, Axeman and Spearman', 2)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'name' => 'Catapult',
                'card_type_id' => $unitId,
                'properties' => json_encode(new UnitCard('Destroys walls (undefendable)', 3)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'name' => 'Chariot',
                'card_type_id' => $unitId,
                'properties' => json_encode(new UnitCard('Steals 1 population and destroys 1 building', 4)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'name' => 'Spearman',
                'card_type_id' => $unitId,
                'properties' => json_encode(new UnitCard('Steals 1 population, defends against chariots', 5)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'name' => 'Swordsman',
                'card_type_id' => $unitId,
                'properties' => json_encode(new UnitCard('Steals 1 population and destroys 1 building', 6)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],

            //Plot cards
            [
                'name' => 'Coast',
                'card_type_id' => $plotId,
                'properties' => json_encode(new PlotCard('2 Commerce base value', 1)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'name' => 'Mountains',
                'card_type_id' => $plotId,
                'properties' => json_encode(new PlotCard('2 Production base value', 2)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'name' => 'Grassland',
                'card_type_id' => $plotId,
                'properties' => json_encode(new PlotCard('2 Food base value', 3)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'name' => 'Forest',
                'card_type_id' => $plotId,
                'properties' => json_encode(new PlotCard('1 Production and 1 commerce base value', 4)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'name' => 'Hills',
                'card_type_id' => $plotId,
                'properties' => json_encode(new PlotCard('1 Food and 1 production base value', 5)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'name' => 'River',
                'card_type_id' => $plotId,
                'properties' => json_encode(new PlotCard('1 Food and 1 commerce base value', 6)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],

            //Technology cards
            [
                'name' => 'Monotheism',
                'card_type_id' => $technologyId,
                'properties' => json_encode(new TechnologyCard(12, 'Doubles the effect of temples', 1)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'name' => 'Literature',
                'card_type_id' => $technologyId,
                'properties' => json_encode(new TechnologyCard(12, 'Grants 3 culture', 2)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'name' => 'Agriculture',
                'card_type_id' => $technologyId,
                'properties' => json_encode(new TechnologyCard(12, '+1 Food per turn from each granary', 3)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'name' => 'Mining',
                'card_type_id' => $technologyId,
                'properties' => json_encode(new TechnologyCard(12, 'One-time bonus of 1 production per plot', 4)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'name' => 'Iron Working',
                'card_type_id' => $technologyId,
                'properties' => json_encode(new TechnologyCard(14, '+1 Production per turn from each forge', 5)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'name' => 'Mathematics',
                'card_type_id' => $technologyId,
                'properties' => json_encode(new TechnologyCard(14, 'Catapults can defend against all units', 6)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'name' => 'Writing',
                'card_type_id' => $technologyId,
                'properties' => json_encode(new TechnologyCard(14, "Steal 1 random card from rival player's hand", 7)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'name' => 'The Wheel',
                'card_type_id' => $technologyId,
                'properties' => json_encode(new TechnologyCard(14, "Draw 2 free cards", 8)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'name' => 'Pottery',
                'card_type_id' => $technologyId,
                'properties' => json_encode(new TechnologyCard(16, "Can spend 3 resources to draw 1 card each turn", 9)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'name' => 'Sailing',
                'card_type_id' => $technologyId,
                'properties' => json_encode(new TechnologyCard(16, "Can spend 3 resources to draw 1 card each turn", 9)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'name' => 'Sailing',
                'card_type_id' => $technologyId,
                'properties' => json_encode(new TechnologyCard(16, "Can trade 2 resources of one type for 1 resource of another type", 10)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'name' => 'Masonry',
                'card_type_id' => $technologyId,
                'properties' => json_encode(new TechnologyCard(16, "-1 Building costs", 11)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'name' => 'Currency',
                'card_type_id' => $technologyId,
                'properties' => json_encode(new TechnologyCard(16, "+1 Commerce per turn from each market", 12)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'name' => 'Currency',
                'card_type_id' => $technologyId,
                'properties' => json_encode(new TechnologyCard(16, "+1 Commerce per turn from each market", 12)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'name' => 'Code of Laws',
                'card_type_id' => $technologyId,
                'properties' => json_encode(new TechnologyCard(20, "Steal 3 resources from any 1 player", 13)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'name' => 'Engineering',
                'card_type_id' => $technologyId,
                'properties' => json_encode(new TechnologyCard(20, "All plots can house 2 buildings", 14)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'name' => 'Astronomy',
                'card_type_id' => $technologyId,
                'properties' => json_encode(new TechnologyCard(24, "Draw 1 extra card per turn", 15)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
        ]);
    }
}
