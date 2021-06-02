function serveCardHtml(card) {
    return "" +
    "<div class='card'>" +
        "<div class='card-body'>" +
            "<h5 class='card-title'>Card title</h5>" +
            "<p class='card-text'>Example</p>" +
         "</div>" +
    "</div>"
}

function servePlotHtml(card) {
    return `<p class="m-0"><a class="text-light">${card.name}: ${card.specialEffect}</a></p>`;
}
