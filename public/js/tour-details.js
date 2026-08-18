document.addEventListener('DOMContentLoaded', function () {
    initTourGallery();
    initTourMap();
});

function initTourGallery() {
    const mainImage = document.getElementById('mainTourImage');
    const thumbnails = document.querySelectorAll('.tour-thumbnail');

    if (!mainImage || thumbnails.length === 0) {
        return;
    }

    thumbnails.forEach(function (thumbnail) {
        thumbnail.addEventListener('click', function () {
            const image = thumbnail.dataset.image;
            const alt = thumbnail.dataset.alt;

            if (!image) {
                return;
            }

            mainImage.src = image;
            mainImage.alt = alt || '';

            thumbnails.forEach(function (item) {
                item.classList.remove('active');
            });

            thumbnail.classList.add('active');
        });
    });
}

function initTourMap() {
    const mapElement = document.getElementById('tourMap');
    const dataElement = document.getElementById('tourMapData');

    if (!mapElement || !dataElement) {
        return;
    }

    if (typeof L === 'undefined') {
        showMapError('Không thể tải thư viện bản đồ Leaflet.');
        return;
    }

    let locations = [];

    try {
        locations = JSON.parse(dataElement.textContent);
    } catch (error) {
        showMapError('Không đọc được dữ liệu địa điểm.');
        return;
    }

    if (!Array.isArray(locations)) {
        showMapError('Dữ liệu địa điểm không hợp lệ.');
        return;
    }

    const validLocations = locations.filter(function (location) {
        const latitude = Number(location.latitude);
        const longitude = Number(location.longitude);

        return (
            Number.isFinite(latitude)
            && Number.isFinite(longitude)
        );
    });

    if (validLocations.length === 0) {
        showMapError('Không có tọa độ hợp lệ để hiển thị.');
        return;
    }

    const firstLocation = validLocations[0];

    const map = L.map('tourMap', {
        scrollWheelZoom: false
    }).setView(
        [
            Number(firstLocation.latitude),
            Number(firstLocation.longitude)
        ],
        10
    );

    L.tileLayer(
        'https://tile.openstreetmap.org/{z}/{x}/{y}.png',
        {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }
    ).addTo(map);

    const bounds = [];
    const routePoints = [];

    validLocations.forEach(function (location, index) {
        const latitude = Number(location.latitude);
        const longitude = Number(location.longitude);

        const point = [
            latitude,
            longitude
        ];

        bounds.push(point);
        routePoints.push(point);

        const marker = L.marker(point).addTo(map);

        marker.bindPopup(
            buildPopupContent(
                location,
                index
            )
        );
    });

    if (routePoints.length >= 2) {
        L.polyline(
            routePoints,
            {
                weight: 4,
                opacity: 0.75
            }
        ).addTo(map);
    }

    function fitMap() {
        if (bounds.length === 1) {
            map.setView(
                bounds[0],
                12
            );

            return;
        }

        map.fitBounds(
            bounds,
            {
                padding: [40, 40]
            }
        );
    }

    fitMap();

    const fitButton = document.getElementById('fitTourMap');

    if (fitButton) {
        fitButton.addEventListener('click', function () {
            fitMap();
        });
    }

    setTimeout(function () {
        map.invalidateSize();
    }, 150);
}

function buildPopupContent(location, index) {
    const name = escapeHtml(
        location.location_name || 'Điểm đến'
    );

    const province = escapeHtml(
        location.province_city || ''
    );

    const country = escapeHtml(
        location.country || ''
    );

    const note = escapeHtml(
        location.note || ''
    );

    let place = '';

    if (province && country) {
        place = province + ' · ' + country;
    } else {
        place = province || country;
    }

    let html = `
        <div class="map-popup">
            <div class="map-popup-order">
                Điểm ${index + 1}
            </div>

            <strong>
                ${name}
            </strong>
    `;

    if (place) {
        html += `
            <span>
                ${place}
            </span>
        `;
    }

    if (note) {
        html += `
            <p>
                ${note}
            </p>
        `;
    }

    html += '</div>';

    return html;
}

function showMapError(message) {
    const mapElement = document.getElementById('tourMap');

    if (!mapElement) {
        return;
    }

    mapElement.innerHTML = `
        <div class="map-error">
            <strong>
                Không thể hiển thị bản đồ
            </strong>

            <span>
                ${escapeHtml(message)}
            </span>
        </div>
    `;
}

function escapeHtml(value) {
    return String(value)
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}