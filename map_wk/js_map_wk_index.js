// Initialize the map
var map = L.map('map').setView([23.8103, 90.4125], 13); // Example coordinates (Dhaka)

// Add OpenStreetMap tiles
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

// Create a red marker icon
var redIcon = L.icon({
    iconUrl: 'https://cdn.jsdelivr.net/gh/pointhi/leaflet-color-markers/img/marker-icon-red.png',
    iconSize: [25, 41], // Size of the icon
    iconAnchor: [12, 41], // Anchor point of the icon
    popupAnchor: [1, -34] // Popup anchor point
});

// Function to create a marker with a red icon (for requests)
function createRequestMarker(lat, lng, bags, bloodGroup, mobile) {
    var marker = L.marker([lat, lng], { icon: redIcon }).addTo(map);

    // Popup content with styled text
    var content = `
        <div style="text-align: center;">
            <div style="font-size: 20px; color: red;">${bloodGroup}</div>
            <div>${bags} Bag(s)</div>
            <div>${mobile}</div>
        </div>
    `;
    
    marker.bindPopup(content).openPopup();
}

// Function to create a marker with a default icon (for donations)
function createDonateMarker(lat, lng, bags, bloodGroup, mobile) {
    var marker = L.marker([lat, lng]).addTo(map);

    // Popup content with styled text
    var content = `
        <div style="text-align: center;">
            <div style="font-size: 20px; color: red;">${bloodGroup}</div>
            <div>${bags} Bag(s)</div>
            <div>${mobile}</div>
        </div>
    `;
    
    marker.bindPopup(content).openPopup();
}

// Handle "Request Blood" form submission
document.getElementById('requestForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent form submission

    var bags = document.getElementById('bags').value;
    var bloodGroup = document.getElementById('bloodGroup').value;
    var mobile = document.getElementById('mobile').value;

    // Get user's current location and place a marker
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                var lat = position.coords.latitude;
                var lng = position.coords.longitude;
                createRequestMarker(lat, lng, bags, bloodGroup, mobile);
            },
            function() {
                alert('Unable to retrieve your location.');
            }
        );
    } else {
        alert('Geolocation is not supported by your browser.');
    }
});

// Handle "Donate Blood" form submission
document.getElementById('donateForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent form submission

    var bags = document.getElementById('donateBags').value;
    var bloodGroup = document.getElementById('donateBloodGroup').value;
    var mobile = document.getElementById('donateMobile').value;

    // Get user's current location and place a marker
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                var lat = position.coords.latitude;
                var lng = position.coords.longitude;
                createDonateMarker(lat, lng, bags, bloodGroup, mobile);
            },
            function() {
                alert('Unable to retrieve your location.');
            }
        );
    } else {
        alert('Geolocation is not supported by your browser.');
    }
});
