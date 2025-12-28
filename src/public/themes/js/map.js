if ($("#map").length) {
  function initialize(lat, lng, title) {
    var myLatLng = { lat: lat, lng: lng };

    var mapOptions = {
      zoom: 17,
      scrollwheel: false,
      styles: [
        {
          featureType: "landscape",
          elementType: "all",
          stylers: [
            { hue: "#999999" },
            { saturation: -100 },
            { lightness: -33 },
            { visibility: "on" },
          ],
        },
        {
          featureType: "landscape.man_made",
          elementType: "geometry.fill",
          stylers: [{ visibility: "on" }, { color: "#b1b1b1" }],
        },
        {
          featureType: "landscape",
          elementType: "geometry.fill",
          stylers: [{ color: "#d1d1d1" }],
        },
        {
          featureType: "poi",
          elementType: "all",
          stylers: [
            { hue: "#aaaaaa" },
            { saturation: -100 },
            { lightness: -15 },
            { visibility: "on" },
          ],
        },
        {
          featureType: "road",
          elementType: "all",
          stylers: [
            { hue: "#999999" },
            { saturation: -100 },
            { lightness: -6 },
            { visibility: "on" },
          ],
        },
        {
          featureType: "transit",
          elementType: "all",
          stylers: [{ visibility: "off" }],
        },
        {
          featureType: "water",
          elementType: "geometry.fill",
          stylers: [{ color: "#afe0ff" }],
        },
        {
          featureType: "poi",
          elementType: "all",
          stylers: [{ visibility: "off" }],
        },
      ],
      center: myLatLng,
    };

    var map = new google.maps.Map(document.getElementById("map"), mapOptions);
    new google.maps.Marker({
      position: myLatLng,
      icon: "../public/themes/images/location-pin.png",
      animation: google.maps.Animation.DROP,
      map: map,
      title: title,
    });
  }

  $.ajax({
    url: "../apis/apiLocation.php",
    method: "POST",
    dataType: "json",
    data: { idUser: 3 }, // Gửi dưới dạng form-data
    success: function (response) {
      if (response.success && response.data) {
        let { lat, lng, titleLocation } = response.data;
        initialize(parseFloat(lat), parseFloat(lng), titleLocation);
      } else {
        console.error("Lỗi API:", response.message || "Dữ liệu không hợp lệ");
      }
    },
    error: function (xhr, status, error) {
      console.error("Lỗi tải tọa độ:", xhr.responseText || error);
    },
  });
}
