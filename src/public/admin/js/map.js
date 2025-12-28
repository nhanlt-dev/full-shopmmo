(function ($) {
  "use strict";

  mapboxgl.accessToken =
    "pk.eyJ1IjoibmdvY3RyaW5oMTQ4IiwiYSI6ImNtN3R3YnhjZzBiYnYycW9sMGRmejFtdGIifQ.5cWj_ZgwGD1QGpio_Ymcog";

  const locations = [
    {
      coordinates: [108.21429540416786, 16.023595806506],
      properties: {
        image: "src/public/admin/images/section/page-title-career.jpg",
        url: "property-details-v1.html",
        title: "Trụ Sở Công Ty",
        location: "26 Nguyễn Duy, Khuê Trung, Cẩm Lệ, Đà Nẵng",
        // price: "$8.600",
        // beds: 3,
        // baths: 2,
        // sqft: "2,100",
      },
    },
  ];

  const map = new mapboxgl.Map({
    container: "map",
    style: "mapbox://styles/mapbox/light-v11",
    center: [108.21429540416786, 16.023595806506],
    zoom: 18,
    cooperativeGestures: true,
  });
  map.addControl(new mapboxgl.NavigationControl(), "top-left");

  map.addControl(
    new mapboxgl.GeolocateControl({
      positionOptions: {
        enableHighAccuracy: true,
      },
      trackUserLocation: true,
      showUserHeading: true,
    }),
    "top-left"
  );

  const popup = new mapboxgl.Popup({
    closeButton: false,
    closeOnClick: false,
    offset: [0, -15],
    className: "property-popup",
  });

  locations.forEach((location, index) => {
    const el = document.createElement("div");
    el.className = "map-marker-container";
    el.innerHTML = `
            <div class="marker-container">
                <div class="marker-card">
                    <div class=" face"></div>
                </div>
            </div>
        `;
    const popupContent = `
        <div class="map-listing-item">
    <div class="box-house">
        <div class="infoBox-close"><i class="icon icon-close"></i></div>
        <div class="image-wrap">
            <a href="#">
                <img src="${location.properties.image}" alt="${location.properties.image}">
            </a>
            <div class="list-btn flex gap-8">
                <a href="#" class="btn-icon save"><i class="icon-save"></i></a>
                <a href="#" class="btn-icon find"><i class="icon-find-plus"></i></a>
            </div>
        </div>
        <div class="content">
            <h5 class="title"><a href="${location.properties.url}">${location.properties.title}</a></h5>
            <p class="location text-1 flex items-center gap-8">
                <i class="icon-location"></i>  ${location.properties.location}
            </p>
        </div>
    </div>
        </div>

        `;
    const marker = new mapboxgl.Marker(el)
      .setLngLat(location.coordinates)
      .addTo(map);
    el.addEventListener("click", () => {
      const markerContainer = el.querySelector(".marker-container");
      if (markerContainer.classList.contains("clicked")) {
        markerContainer.classList.remove("clicked");

        popup.remove();
      } else {
        markerContainer.classList.add("clicked");
        popup.setLngLat(location.coordinates).setHTML(popupContent).addTo(map);
        const closeButton = popup.getElement().querySelector(".infoBox-close");
        closeButton.addEventListener("click", () => {
          markerContainer.classList.remove("clicked");
          popup.remove();
        });
      }
    });
  });
  map.dragRotate.disable();
  map.touchZoomRotate.disableRotation();
})(this.jQuery);
