<div class="d-flex flex-column">
    <div class="h-100 mb-5">
        <div class="row h-100 border light-grey">
            <div class="col-3">
                <h5 class="mt-1">Resources</h5>
                <div id="foeResources">
                    @isset($foeHand)
                        <p>Commerce: {{$foeHand['resources']['commerce']}}</p>
                        <p>Food: {{$foeHand['resources']['food']}}</p>
                        <p>Production: {{$foeHand['resources']['production']}}</p>
                        <p>Culture: {{$foeHand['resources']['culture']}}</p>
                        <p>Happiness: {{$foeHand['resources']['happiness']}}</p>
                        <p>Victory points: {{$foeHand['resources']['victoryPoints']}}</p>
                    @endisset
                </div>
            </div>
            <div class="col-3">
                <h5 class="mt-1">Tech</h5>
                <div id="foeTech">
                    @isset($foeHand)
                        @foreach ($foeHand['techs'] as $foeTech)
                        <p class="m-0" onclick='showCard(this, JSON.parse(`@json($foeTech)`), false)'><a class="text-light">{{ $foeTech['name'] }}</a></p>
                        @endforeach
                    @endisset

                </div>
            </div>
            <div class="col-4">
                <h5 class="mt-1">Plots</h5>
                <div id="foePlots">
                    @isset($foeHand)
                    <div class="row">
                        @foreach ($foeHand['plots'] as $foePlot)
                        <div class="col-6">
                        <p class="m-0" onclick='showCard(this, JSON.parse(`@json($foePlot)`), false)'><a class="text-light">{{ $foePlot['name'] }}</a></p>
                        <p class="mb-0 ml-3">Population: {{$foePlot['attachedPopulation']}}</p>
                        @if (empty($foePlot['attachedBuildings']))
                        <p class="ml-3">No buildings</p>
                        @else
                            @foreach ($foePlot['attachedBuildings'] as $building)
                            <p class="mb-0 ml-3" onclick='showCard(this, JSON.parse(`@json($building)`), false, true)'><a class="text-light">{{ $building['name'] }}</a></p>
                            @endforeach
                        @endif
                        </div>
                        @endforeach
                    </div>
                    @endisset

                </div>
            </div>
            <div class="col-2">
                <h5 class="mt-1">Play cards</h5>
                <div id="foePlayCards">
                    @isset($foeHand)
                        {{ count($foeHand['hand']) }}
                    @endisset
                </div>
            </div>
        </div>
    </div>
    <div class="h-100">
        <div class="row h-100">
            <div class="col-2">
                <div class="d-flex flex-column border h-100 justify-content-center align-items-center light-grey">
                    <h5>
                        Deck
                    </h5>
                    <div id="deck">
                        {{ $deckLength }}
                    </div>
                </div>
            </div>
            <div class="col-2 pl-0 pr-4">
                <div class="d-flex flex-column border h-100 justify-content-center align-items-center light-grey">
                    <h5>
                        Discard pile
                    </h5>
                    <div id="discardPile">
                        {{ $discardPileLength }}
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="row w-100 h-100 border light-grey">
                    <div class="col-6 h-100 border-right pb-3">
                        <h5 class="mt-1">
                            Purchaseable plots
                        </h5>
                        <div id="purchaseablePlots">
                            @foreach ($purchaseablePlots as $purchaseablePlot)
                            <p class="m-0" onclick='showCard(this, JSON.parse(`@json($purchaseablePlot)`), false)' data-index="{{ $loop->index }}" data-deck="purchaseablePlots"><a class="text-light">{{ $purchaseablePlot['name'] }}</a></p>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-6">
                        <h5 class="mt-1">
                            Purchaseable tech
                        </h5>
                        <div id="purchaseableTech">
                            @foreach ($purchaseableTechs as $purchaseableTech)
                            <p class="m-0" onclick='showCard(this, JSON.parse(`@json($purchaseableTech)`), false)' data-index="{{ $loop->index }}" data-deck="purchaseableTechs"><a class="text-light">{{ $purchaseableTech['name'] }}</a></p>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="row w-100 h-100 border light-grey">
                    <div class="col-6 h-100 border-right">
                        <h5 class="mt-1">
                            Attacking
                        </h5>
                        <div id="attacking">
                            @isset($attacking['index'])
                            <p>Attacker: {{$players[$attacking['index']]['name']}}</p>
                            @endisset

                            @isset($attacking['units'])
                            @foreach ($attacking['units'] as $attacker)
                            <p class="mb-0 ml-3" onclick='showCard(this, JSON.parse(`@json($attacker)`), false)'><a class="text-light">{{ $attacker['name'] }}</a></p>
                            @endforeach
                            @endisset

                        </div>
                    </div>
                    <div class="col-6">
                        <h5 class="mt-1">
                            Defending
                        </h5>
                        <div id="defending">
                            @isset($defending['index'])
                            <p>Defender: {{$players[$defending['index']]['name']}}</p>
                            @endisset

                            @isset($defending['units'])
                            @foreach ($defending['units'] as $defender)
                            <p class="mb-0 ml-3" onclick='showCard(this, JSON.parse(`@json($defender)`), false)'><a class="text-light">{{ $defender['name'] }}</a></p>
                            @endforeach
                            @endisset

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="h-100 mt-5">
        <div class="row h-100 border light-grey">
            <div class="col-3">
                <h5 class="mt-1">Resources</h5>
                <div id="ownResources">
                    @isset($ownHand)
                        <p>Commerce: {{$ownHand['resources']['commerce']}}</p>
                        <p>Food: {{$ownHand['resources']['food']}}</p>
                        <p>Production: {{$ownHand['resources']['production']}}</p>
                        <p>Culture: {{$ownHand['resources']['culture']}}</p>
                        <p>Happiness: {{$ownHand['resources']['happiness']}}</p>
                        <p>Victory points: {{$ownHand['resources']['victoryPoints']}}</p>
                    @endisset
                </div>

            </div>
            <div class="col-3">
                <h5 class="mt-1">Tech</h5>
                <div id="ownTech">
                    @foreach ($ownHand['techs'] as $ownTech)
                    <p class="m-0" onclick='showCard(this, JSON.parse(`@json($ownTech)`), false)' data-index="{{ $loop->index }}" data-deck="ownTech"><a class="text-light">{{ $ownTech['name'] }}</a></p>
                    @endforeach
                </div>
            </div>
            <div class="col-4">
                <h5 class="mt-1">Plots</h5>
                <div id="ownPlots">
                    <div class="row">
                    @foreach ($ownHand['plots'] as $ownPlot)
                    <div class="col-6">
                    <p class="m-0" onclick='showCard(this, JSON.parse(`@json($ownPlot)`), false)' data-index="{{ $loop->index }}" data-deck="ownPlots"><a class="text-light">{{ $ownPlot['name'] }}</a></p>
                    <p class="mb-0 ml-3">Population: {{$ownPlot['attachedPopulation']}}</p>
                    @if (empty($ownPlot['attachedBuildings']))
                        <p class="ml-3">No buildings</p>
                    @else
                        @foreach ($ownPlot['attachedBuildings'] as $building)
                        <p class="mb-0 ml-3" onclick='showCard(this, JSON.parse(`@json($building)`), false, true)' data-index="{{ $loop->index }}" data-deck="attachedBuildings"><a class="text-light">{{ $building['name'] }}</a></p>
                        @endforeach
                    @endif
                    </div>
                    @endforeach
                    </div>
                </div>
            </div>
            <div class="col-2">
                <h5 class="mt-1">Play cards</h5>
                <div id="ownPlayCards">
                    @foreach ($ownHand['hand'] as $ownPlayCard)
                    <p id="ownCard{{ $loop->index }}" class="m-0" onclick='showCard(this, JSON.parse(`@json($ownPlayCard)`), true)' data-index="{{ $loop->index }}" data-deck="playDeck"><a class="text-light">{{ $ownPlayCard['name'] }}</a></p>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
