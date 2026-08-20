document.addEventListener(
    'DOMContentLoaded',
    function () {
        initDestinationMap();
    }
);

function initDestinationMap() {
    const mapElement =
        document.getElementById(
            'destinationMap'
        );

    const dataElement =
        document.getElementById(
            'destinationMapData'
        );

    if (
        !mapElement
        || !dataElement
        || typeof L === 'undefined'
    ) {
        return;
    }

    let destination;

    try {
        destination =
            JSON.parse(
                dataElement.textContent
            );
    } catch (error) {
        showDestinationMapError();
        return;
    }

    const latitude =
        Number(
            destination.latitude
        );

    const longitude =
        Number(
            destination.longitude
        );

    if (
        !Number.isFinite(latitude)
        || !Number.isFinite(longitude)
    ) {
        showDestinationMapError();
        return;
    }

    const map =
        L.map(
            mapElement
        ).setView(
            [
                latitude,
                longitude
            ],
            13
        );

    L.tileLayer(
        'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
        {
            maxZoom: 19,
            attribution:
                '&copy; OpenStreetMap contributors'
        }
    ).addTo(map);

    const marker =
        L.marker(
            [
                latitude,
                longitude
            ]
        ).addTo(map);

    marker
        .bindPopup(
            buildDestinationPopup(
                destination
            )
        )
        .openPopup();
}

function buildDestinationPopup(
    destination
) {
    const name =
        escapeDestinationHtml(
            destination.name
            || 'Điểm đến'
        );

    const province =
        escapeDestinationHtml(
            destination.province
            || ''
        );

    const country =
        escapeDestinationHtml(
            destination.country
            || ''
        );

    let place = '';

    if (
        province
        && country
    ) {
        place =
            province
            + ' · '
            + country;
    } else {
        place =
            province
            || country;
    }

    let html =
        '<div class="destination-map-popup">'
        + '<strong>'
        + name
        + '</strong>';

    if (place) {
        html +=
            '<span>'
            + place
            + '</span>';
    }

    html += '</div>';

    return html;
}

function showDestinationMapError() {
    const mapElement =
        document.getElementById(
            'destinationMap'
        );

    if (!mapElement) {
        return;
    }

    mapElement.innerHTML =
        '<div style="padding:30px;text-align:center;">'
        + 'Không thể hiển thị bản đồ.'
        + '</div>';
}

function escapeDestinationHtml(
    value
) {
    return String(value)
        .replaceAll(
            '&',
            '&amp;'
        )
        .replaceAll(
            '<',
            '&lt;'
        )
        .replaceAll(
            '>',
            '&gt;'
        )
        .replaceAll(
            '"',
            '&quot;'
        )
        .replaceAll(
            "'",
            '&#039;'
        );
}