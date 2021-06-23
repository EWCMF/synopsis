<div class="modal-header">
    <h5 id="plot-modal-title" class="modal-title">Select plot</h5>
</div>
<div id="plot-modal-body" class="modal-body">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="row">
                    @foreach ($allowedPlots as $allowedPlotKey => $allowedPlotValue)
                        <div class="col-4">
                            <p class="m-0">{{ $allowedPlotValue['name'] }}</p>
                            <div class="row ml-3">
                                <p>Population available:&nbsp;</p>
                                <div>{{ $allowedPlotValue['attachedPopulation'] }}</div>
                            </div>
                            <div>
                                @if (!empty($allowedPlotValue['attachedBuildings']))
                                <p>Buildings:</p>
                                @endif
                                @forelse ($allowedPlotValue['attachedBuildings'] as $attachedBuilding )
                                <p>{{$attachedBuilding['name']}}</p>
                                @empty
                                <p>Buildings: none</p>
                                @endforelse
                            </div>
                            <div>
                                <button class="btn btn-primary" onclick="confirmBuildingOnPlot('{{$allowedPlotKey}}', '{{$buildingIndex}}')">Confirm</button>
                            </div>
                        </div>
                    @endforeach
                </div>
                <button class="btn btn-primary mt-3" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
