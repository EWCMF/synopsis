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
                'card_type_id' => $resourceId,
                'properties' => json_encode(new ResourceCard('1 Commerce', 'Commerce', 1)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'card_type_id' => $resourceId,
                'properties' => json_encode(new ResourceCard('5 Commerce', 'Commerce', 5)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'card_type_id' => $resourceId,
                'properties' => json_encode(new ResourceCard('1 Production', 'Production', 1)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'card_type_id' => $resourceId,
                'properties' => json_encode(new ResourceCard('5 Production', 'Production', 5)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'card_type_id' => $resourceId,
                'properties' => json_encode(new ResourceCard('1 Food', 'Food', 1)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'card_type_id' => $resourceId,
                'properties' => json_encode(new ResourceCard('5 Food', 'Commerce', 5)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],

            //Bonus resource cards
            [
                'card_type_id' => $bonusId,
                'properties' => json_encode(new BonusResourceCard('2 Bonus Commerce', 'Commerce', 2, false)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'card_type_id' => $bonusId,
                'properties' => json_encode(new BonusResourceCard('3 Bonus Commerce', 'Commerce', 3, false)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'card_type_id' => $bonusId,
                'properties' => json_encode(new BonusResourceCard('2 Bonus Production', 'Production', 2, false)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'card_type_id' => $bonusId,
                'properties' => json_encode(new BonusResourceCard('3 Bonus Production', 'Production', 3, false)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'card_type_id' => $bonusId,
                'properties' => json_encode(new BonusResourceCard('2 Bonus Food', 'Food', 2, false)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'card_type_id' => $bonusId,
                'properties' => json_encode(new BonusResourceCard('3 Bonus Food', 'Food', 3, false)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'card_type_id' => $bonusId,
                'properties' => json_encode(new BonusResourceCard('1 Bonus Wild', 'Wild', 1, true)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'card_type_id' => $bonusId,
                'properties' => json_encode(new BonusResourceCard('2 Bonus Wild', 'Wild', 2, true)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],

            //Population cards
            [
                'card_type_id' => $populationId,
                'properties' => json_encode(new PopulationCard('1 Population', 'Grants 1 population', 1)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],

            //Building cards
            [
                'card_type_id' => $buildingId,
                'properties' => json_encode(new BuildingCard('Market', 6, '100% commerce from base plot value', 1)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'card_type_id' => $buildingId,
                'properties' => json_encode(new BuildingCard('Barrack', 4, 'Chariots and swordsmen steal 2 population', 2)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'card_type_id' => $buildingId,
                'properties' => json_encode(new BuildingCard('Forge', 6, '100% production from base plot value', 3)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'card_type_id' => $buildingId,
                'properties' => json_encode(new BuildingCard('Temple', 4, 'Grants 1 happiness and 1 culture ', 4)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'card_type_id' => $buildingId,
                'properties' => json_encode(new BuildingCard('Library', 8, 'Grants 1 culture and -2 technology costs', 5)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'card_type_id' => $buildingId,
                'properties' => json_encode(new BuildingCard('Monastery', 6, 'Grants 2 culture and -1 technology costs', 6)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'card_type_id' => $buildingId,
                'properties' => json_encode(new BuildingCard('Granary', 6, '100% food from base plot value', 7)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'card_type_id' => $buildingId,
                'properties' => json_encode(new BuildingCard('Colosseum', 4, 'Grants 2 happiness', 8)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'card_type_id' => $buildingId,
                'properties' => json_encode(new BuildingCard('Walls', 4, 'Repels 1 attacker', 9)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],

            //Wonder cards
            [
                'card_type_id' => $wonderId,
                'properties' => json_encode(new WonderCard('The Pyramids', 20, '+2 Free resources every turn', 1)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'card_type_id' => $wonderId,
                'properties' => json_encode(new WonderCard('The Colossus', 12, 'Grants 1 culture, immune to attacks for 2 rounds', 2)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'card_type_id' => $wonderId,
                'properties' => json_encode(new WonderCard('The Hanging Gardens', 12, 'Grants 3 happiness and 1 culture', 3)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'card_type_id' => $wonderId,
                'properties' => json_encode(new WonderCard('The Great Library', 16, 'Grants 1 culture and -3 technology cost', 4)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'card_type_id' => $wonderId,
                'properties' => json_encode(new WonderCard('The Great Walls', 16, 'Acts as permanent walls', 5)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],

            //Unit cards
            [
                'card_type_id' => $unitId,
                'properties' => json_encode(new UnitCard('Archer ', 'Cannot attack but defends against all units', 1)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'card_type_id' => $unitId,
                'properties' => json_encode(new UnitCard('Axeman', 'Destroys 1 building, defends against Swordsman, Axeman and Spearman', 2)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'card_type_id' => $unitId,
                'properties' => json_encode(new UnitCard('Catapult', 'Destroys walls (undefendable)', 3)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'card_type_id' => $unitId,
                'properties' => json_encode(new UnitCard('Chariot', 'Steals 1 population and destroys 1 building', 4)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'card_type_id' => $unitId,
                'properties' => json_encode(new UnitCard('Spearman', 'Steals 1 population, defends against chariots', 5)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'card_type_id' => $unitId,
                'properties' => json_encode(new UnitCard('Swordsman', 'Steals 1 population and destroys 1 building', 6)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],

            //Plot cards
            [
                'card_type_id' => $plotId,
                'properties' => json_encode(new PlotCard('Coast', '2 Commerce base value', 1)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'card_type_id' => $plotId,
                'properties' => json_encode(new PlotCard('Mountains', '2 Production base value', 2)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'card_type_id' => $plotId,
                'properties' => json_encode(new PlotCard('Grassland', '2 Food base value', 3)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'card_type_id' => $plotId,
                'properties' => json_encode(new PlotCard('Forest', '1 Production and 1 commerce base value', 4)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'card_type_id' => $plotId,
                'properties' => json_encode(new PlotCard('Hills', '1 Food and 1 production base value', 5)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'card_type_id' => $plotId,
                'properties' => json_encode(new PlotCard('River', '1 Food and 1 commerce base value', 6)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],

            //Technology cards
            [
                'card_type_id' => $technologyId,
                'properties' => json_encode(new TechnologyCard('Monotheism', 12, 'Doubles the effect of temples', 1)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'card_type_id' => $technologyId,
                'properties' => json_encode(new TechnologyCard('Literature', 12, 'Grants 3 culture', 2)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'card_type_id' => $technologyId,
                'properties' => json_encode(new TechnologyCard('Agriculture', 12, '+1 Food per turn from each granary', 3)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'card_type_id' => $technologyId,
                'properties' => json_encode(new TechnologyCard('Mining', 12, 'One-time bonus of 1 production per plot', 4)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'card_type_id' => $technologyId,
                'properties' => json_encode(new TechnologyCard('Iron Working', 14, '+1 Production per turn from each forge', 5)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'card_type_id' => $technologyId,
                'properties' => json_encode(new TechnologyCard('Mathematics', 14, 'Catapults can defend against all units', 6)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'card_type_id' => $technologyId,
                'properties' => json_encode(new TechnologyCard('Writing', 14, "Steal 1 random card from rival player's hand", 7)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'card_type_id' => $technologyId,
                'properties' => json_encode(new TechnologyCard('The Wheel', 14, "Draw 2 free cards", 8)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'card_type_id' => $technologyId,
                'properties' => json_encode(new TechnologyCard('Pottery', 16, "Can spend 3 resources to draw 1 card each turn", 9)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'card_type_id' => $technologyId,
                'properties' => json_encode(new TechnologyCard('Sailing', 16, "Can trade 2 resources of one type for 1 resource of another type", 10)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'card_type_id' => $technologyId,
                'properties' => json_encode(new TechnologyCard('Masonry', 16, "-1 Building costs", 11)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'card_type_id' => $technologyId,
                'properties' => json_encode(new TechnologyCard('Currency', 16, "+1 Commerce per turn from each market", 12)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'card_type_id' => $technologyId,
                'properties' => json_encode(new TechnologyCard('Code of Laws', 20, "Steal 3 resources from any 1 player", 13)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'card_type_id' => $technologyId,
                'properties' => json_encode(new TechnologyCard('Engineering', 20, "All plots can house 2 buildings", 14)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'card_type_id' => $technologyId,
                'properties' => json_encode(new TechnologyCard('Astronomy', 24, "Draw 1 extra card per turn", 15)),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
        ]);
    }
}
