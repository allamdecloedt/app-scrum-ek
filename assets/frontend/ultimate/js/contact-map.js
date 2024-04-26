





let map;

async function initMap() {
  // The location of Uluru
  const position = { lat: 33.58602834264379, lng: -7.6312771867287985 };
  // Request needed libraries.
  //@ts-ignore
  const { Map } = await google.maps.importLibrary("maps");
  const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");

  // The map, centered at Uluru
  map = new Map(document.getElementById("map"), {
    zoom: 4,
    center: position,
    mapId: "DecloedtMap",
     mapTypeControlOptions: {
      style: google.maps.MapTypeControlStyle.DROPDOWN_MENU,
      mapTypeIds: ["roadmap", "terrain","satellite", "hybrid"],
    },
  });



  // The marker, positioned at Uluru
  const marker = new AdvancedMarkerElement({
    map: map,
    position: position,
    title: "Decloedt Office",
  });
}

initMap();