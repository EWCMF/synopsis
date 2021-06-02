<div id="plot-modal" class="modal" tabindex="-1">
    <div class="modal-dialog modal-xl">
      <div class="modal-content light-grey">
        <div class="modal-header">
          <h5 id="plot-modal-title" class="modal-title">Choose plots</h5>
          <button id="plot-modal-close-button" type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div id="plot-modal-body" class="modal-body">
            <div class="container">
                <h2 id="plot-modal-currentTurn">Current turn: {{$currentTurn}}</h2>
                <div class="row">
                    <div class="col-4">
                        <h5>Your plots</h5>
                        <div id="plot-modal-ownPlots">
                            @foreach ($ownPlots as $ownPlot)
                             <p class="m-0"><a class="text-light">{{$ownPlot['name']}}: {{$ownPlot['specialEffect']}}</a></p>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-4">
                        <h5>Selection</h5>
                        <div id="plot-modal-selectionPlots">
                            @foreach ($selectionPlots as $selectionPlot)
                            <p class="m-0"><a class="text-light">{{$selectionPlot['name']}}: {{$selectionPlot['specialEffect']}}</a></p>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-4">
                        <h5>Foe plots</h5>
                        <div id="plot-modal-foePlots">
                            @foreach ($foePlots as $foePlots)
                            <p class="m-0"><a class="text-light">{{$foePlots['name']}}: {{$foePlots['specialEffect']}}</a></p>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
