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
                <h2 id="plot-modal-currentTurn">Current turn: player 1</h2>
                <div class="row">
                    <div class="col-4">
                        <h5>Your plots</h5>
                        <div id="plot-modal-ownPlots">
                            @foreach ($ownplots as $ownPlot)

                            @endforeach
                            {{-- <p class="m-0"><a class="text-light">Coast: 2 Commerce base value</a></p> --}}
                        </div>
                    </div>
                    <div class="col-4">
                        <h5>Selection</h5>
                        <div id="plot-modal-selectionPlots">
                            @foreach ($selectionPlots as $selectionPlot)

                            @endforeach
                        </div>
                    </div>
                    <div class="col-4">
                        <h5>Foe plots</h5>
                        <div id="plot-modal-foePlots">
                            @foreach ($foePlots as $foePlots)

                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
