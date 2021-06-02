<div class="d-flex flex-column vh-100">
    <div class="h-100 mb-5">
        <div class="row h-100 border light-grey">
            <div class="col-3">
                <h5 class="mt-1">Resources</h5>
                <div id="foeResources">
                    @isset($foeHand)
                        @foreach ($foeHand['resources'] as $foeResource)

                        @endforeach
                    @endisset

                </div>
            </div>
            <div class="col-3">
                <h5 class="mt-1">Tech</h5>
                <div id="foeTech">
                    @isset($foeHand)
                        @foreach ($foeHand['techs'] as $foeTech)

                        @endforeach
                    @endisset

                </div>
            </div>
            <div class="col-3">
                <h5 class="mt-1">Plots</h5>
                <div id="foePlots">
                    @isset($foeHand)
                        @foreach ($foeHand['plots'] as $foePlot)

                        @endforeach
                    @endisset

                </div>
            </div>
            <div class="col-3">
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
                    <div class="col-6 h-100 border-right">
                        <h5 class="mt-1">
                            Purchaseable plots
                        </h5>
                        <div id="purchaseablePlots">
                            @foreach ($purchaseablePlots as $purchaseablePlot)

                            @endforeach
                        </div>
                    </div>
                    <div class="col-6">
                        <h5 class="mt-1">
                            Purchaseable tech
                        </h5>
                        <div id="purchaseableTech">
                            @foreach ($purchaseableTechs as $purchaseableTech)

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
                            @foreach ($attacking as $attacker)

                            @endforeach
                        </div>
                    </div>
                    <div class="col-6">
                        <h5 class="mt-1">
                            Defending
                        </h5>
                        <div id="defending">
                            @foreach ($defending as $defender)

                            @endforeach
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
                    @foreach ($ownHand['resources'] as $ownResource)

                    @endforeach
                </div>
            </div>
            <div class="col-3">
                <h5 class="mt-1">Tech</h5>
                <div id="ownTech">
                    @foreach ($ownHand['techs'] as $ownTech)

                    @endforeach
                </div>
            </div>
            <div class="col-3">
                <h5 class="mt-1">Plots</h5>
                <div id="ownPlots">
                    @foreach ($ownHand['plots'] as $ownPlot)

                    @endforeach
                </div>
            </div>
            <div class="col-3">
                <h5 class="mt-1">Play cards</h5>
                <div id="ownPlayCards">
                    @foreach ($ownHand['hand'] as $ownPlayCard)

                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
