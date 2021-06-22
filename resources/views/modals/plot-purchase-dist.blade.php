<div class="modal-header">
    <h5 id="plot-modal-title" class="modal-title">Purchase plot</h5>
    <input type="hidden" id="purchasedPlotIndex" value="{{ $plotCardIndex }}">
</div>
<div id="plot-modal-body" class="modal-body">
    <div class="container">
        <div class="row">
            <div class="col-8">
                <div class="row">
                    <div class="col-4">
                        <div class="row ml-3">
                            <p>Commerce available:&nbsp;</p>
                            <div id="commerceAvail">{{ $resources['commerce'] }}</div>
                            <div hidden id="commerceAvailMax">{{ $resources['food'] }}</div>
                        </div>
                    </div>
                    <div class="col-8">
                        <button class="btn btn-primary mr-1" onclick="addPurchaseModal('commerceAvail')">+</button>
                        <button class="btn btn-primary" onclick="subtractPurchaseModal('commerceAvail')">-</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <div class="row ml-3">
                            <p>food available:&nbsp;</p>
                            <div id="foodAvail">{{ $resources['food'] }}</div>
                            <div hidden id="foodAvailMax">{{ $resources['food'] }}</div>
                        </div>
                    </div>
                    <div class="col-8">
                        <button class="btn btn-primary mr-1" onclick="addPurchaseModal('foodAvail')">+</button>
                        <button class="btn btn-primary" onclick="subtractPurchaseModal('foodAvail')">-</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <div class="row ml-3">
                            <p>Production available:&nbsp;</p>
                            <div id="productionAvail">{{ $resources['production'] }}</div>
                            <div hidden id="productionAvail">{{ $resources['production'] }}</div>
                        </div>
                    </div>
                    <div class="col-8">
                        <button class="btn btn-primary mr-1" onclick="addPurchaseModal('productionAvail')">+</button>
                        <button class="btn btn-primary" onclick="subtractPurchaseModal('productionAvail')">-</button>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="row">
                    <p>Resources needed:&nbsp;</p>
                    <p id="resourcesNeeded">{{ $needed }}</p>

                </div>
                <div class="row">
                    <p>Resources added:&nbsp;</p>
                    <p id="resourcesAdded">0</p>
                </div>

                <div class="row">
                    <button class="btn btn-primary mt-3 mr-3" data-dismiss="modal">Cancel</button>
                    <button id="plotDistButton" class="btn btn-primary mt-3" onclick="comfirmPlotPurchase()" disabled>Confirm</button>
                </div>

            </div>
        </div>
    </div>
</div>
