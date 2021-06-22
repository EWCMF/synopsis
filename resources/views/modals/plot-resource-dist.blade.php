<div class="modal-header">
    <h5 id="plot-modal-title" class="modal-title">Distribute resources</h5>
</div>
<div id="plot-modal-body" class="modal-body">
    <div class="container">
        <div class="row">
            <div class="col-8">
                <div class="row">
                    @foreach ($hybridPlots as $hybridPlot)
                        <div class="col-6">
                            <p class="m-0">{{ $hybridPlot['name'] }}</p>
                            <div class="row ml-3">
                                <p>Population available:&nbsp;</p>
                                <div id="popAvail{{ $loop->index }}" class="popCounter">{{ $hybridPlot['attachedPopulation'] }}</div>
                                <div hidden id="popAvailMax{{ $loop->index }}">{{ $hybridPlot['attachedPopulation'] }}</div>
                            </div>
                            @switch($hybridPlot['specialEffectId'])
                                @case(4)
                                    <div class="row ml-3">
                                        <div class="col-4">
                                            <p>Production</p>
                                        </div>
                                        <div class="col-8">
                                            <div class="row">
                                                <button class="btn btn-primary mr-1" onclick="addResourceModal('{{ $loop->index }}', 'productionCounter')">+</button>
                                                <button class="btn btn-primary" onclick="subtractResourceModal('{{ $loop->index }}', 'productionCounter')">-</button>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row ml-3">
                                        <div class="col-4">
                                            <p>Commerce</p>
                                        </div>
                                        <div class="col-8">
                                            <div class="row">
                                                <button class="btn btn-primary mr-1" onclick="addResourceModal('{{ $loop->index }}', 'commerceCounter')">+</button>
                                                <button class="btn btn-primary" onclick="subtractResourceModal('{{ $loop->index }}', 'commerceCounter')">-</button>
                                            </div>
                                        </div>
                                    </div>
                                @break

                                @case(5)
                                    <div class="row ml-3">
                                        <div class="col-4">
                                            <p>Food</p>
                                        </div>
                                        <div class="col-8">
                                            <div class="row">
                                                <button class="btn btn-primary mr-1" onclick="addResourceModal('{{ $loop->index }}', 'foodCounter')">+</button>
                                                <button class="btn btn-primary" onclick="subtractResourceModal('{{ $loop->index }}', 'foodCounter')">-</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row ml-3">
                                        <div class="col-4">
                                            <p>Production</p>
                                        </div>
                                        <div class="col-8">
                                            <div class="row">
                                                <button class="btn btn-primary mr-1" onclick="addResourceModal('{{ $loop->index }}', 'productionCounter')">+</button>
                                                <button class="btn btn-primary" onclick="subtractResourceModal('{{ $loop->index }}', 'productionCounter')">-</button>
                                            </div>
                                        </div>
                                    </div>
                                @break

                                @case(6)
                                    <div class="row ml-3">
                                        <div class="col-4">
                                            <p>Food</p>
                                        </div>
                                        <div class="col-8">
                                            <div class="row">
                                                <button class="btn btn-primary mr-1" onclick="addResourceModal('{{ $loop->index }}', 'foodCounter')">+</button>
                                                <button class="btn btn-primary" onclick="subtractResourceModal('{{ $loop->index }}', 'foodCounter')">-</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row ml-3">
                                        <div class="col-4">
                                            <p>Commerce</p>
                                        </div>
                                        <div class="col-8">
                                            <div class="row">
                                                <button class="btn btn-primary mr-1" onclick="addResourceModal('{{ $loop->index }}', 'commerceCounter')">+</button>
                                                <button class="btn btn-primary" onclick="subtractResourceModal('{{ $loop->index }}', 'commerceCounter')">-</button>
                                            </div>
                                        </div>
                                    </div>
                                @break
                                @default

                            @endswitch
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-4">
                <div class="row">
                    <p>Commerce:&nbsp;</p>
                    <p id="commerceCounter">0</p>

                </div>
                <div class="row">
                    <p>Food:&nbsp;</p>
                    <p id="foodCounter">0</p>

                </div>
                <div class="row">
                    <p>Production:&nbsp;</p>
                    <p id="productionCounter">0</p>

                </div>
                <button id="distButton" class="btn btn-primary mt-3" onclick="comfirmDistribution()" disabled>Confirm</button>
            </div>
        </div>
    </div>
</div>
