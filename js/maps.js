// Initialize Google Maps
function initMap() {
    // Default center (Nairobi coordinates)
    const defaultCenter = { lat: -1.2921, lng: 36.8219 };

    // Create map
    const map = new google.maps.Map(document.getElementById('property-map'), {
        zoom: 15,
        center: defaultCenter,
        styles: [
            {
                "featureType": "all",
                "elementType": "geometry",
                "stylers": [{ "color": "#f5f5f5" }]
            },
            {
                "featureType": "water",
                "elementType": "geometry",
                "stylers": [{ "color": "#e9e9e9" }, { "lightness": 17 }]
            }
        ]
    });

    // Check if we're displaying a single property or multiple properties
    if (typeof propertyLocation !== 'undefined') {
        // Single property display
        const marker = new google.maps.Marker({
            position: { lat: propertyLocation.lat, lng: propertyLocation.lng },
            map: map,
            title: propertyLocation.title,
            animation: google.maps.Animation.DROP
        });

        // Center map on the property
        map.setCenter({ lat: propertyLocation.lat, lng: propertyLocation.lng });

        // Add info window
        const infoWindow = new google.maps.InfoWindow({
            content: `
                <div class="property-info-window">
                    <h3>${propertyLocation.title}</h3>
                    <p>${propertyLocation.price}</p>
                    <a href="${propertyLocation.link}" class="btn btn-primary btn-sm">View Details</a>
                </div>
            `
        });

        // Show info window by default
        infoWindow.open(map, marker);

        // Add click listener to marker
        marker.addListener('click', () => {
            infoWindow.open(map, marker);
        });
    } else if (typeof propertyLocations !== 'undefined') {
        // Multiple properties display
        propertyLocations.forEach(location => {
            const marker = new google.maps.Marker({
                position: { lat: location.lat, lng: location.lng },
                map: map,
                title: location.title,
                animation: google.maps.Animation.DROP
            });

            // Add info window
            const infoWindow = new google.maps.InfoWindow({
                content: `
                    <div class="property-info-window">
                        <h3>${location.title}</h3>
                        <p>${location.price}</p>
                        <a href="${location.link}" class="btn btn-primary btn-sm">View Details</a>
                    </div>
                `
            });

            marker.addListener('click', () => {
                infoWindow.open(map, marker);
            });
        });
    }
}

// Function to get coordinates from address
function getCoordinates(address) {
    const geocoder = new google.maps.Geocoder();
    return new Promise((resolve, reject) => {
        geocoder.geocode({ address: address }, (results, status) => {
            if (status === 'OK') {
                resolve({
                    lat: results[0].geometry.location.lat(),
                    lng: results[0].geometry.location.lng()
                });
            } else {
                reject('Geocode was not successful for the following reason: ' + status);
            }
        });
    });
} 