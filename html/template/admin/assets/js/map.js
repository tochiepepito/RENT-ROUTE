/*
Author       : Dreamstechnologies
Template Name: Dreamsrent - Bootstrap Template
Version      : 1.0
*/

google.maps.visualRefresh = true;
var slider, infowindow = null;
var bounds = new google.maps.LatLngBounds();
var map, current = 0;
var locations =[{
	"id":"01",
	"car_name":"Toyota Vios 2022",
	"speciality":"Speed : 160 km/h",
	"address":"Manila, Philippines",
	"lat":14.5995,
	"lng":120.9842,
	"icons":"default",
	"profile_link":"car-details.html",
	"image":'assets/img/car/car-01.png'
	}, {		
	"id":"02",
	"car_name":"Honda Civic 2021",
	"speciality":"Speed : 180 km/h",
	"address":"Quezon City, Philippines",
	"lat":14.6760,
	"lng":121.0437,
	"icons":"default",
	"profile_link":"car-details.html",
	"image":'assets/img/car/car-02.jpg'
	}, {
	"id":"03",
	"car_name":"Nissan Almera 2020",
	"speciality":"Speed : 170 km/h",
	"address":"Makati, Philippines",
	"lat":14.5547,
	"lng":121.0244,
	"icons":"default",
	"profile_link":"car-details.html",
	"image":'assets/img/car/car-03.jpg'
	}, {
	"id":"04",
	"car_name":"Mitsubishi Outlander 2023",
	"speciality":"Speed : 190 km/h",
	"address":"Cebu, Philippines",
	"lat":10.3157,
	"lng":123.8854,
	"icons":"default",
	"profile_link":"car-details.html",
	"image":'assets/img/car/car-04.jpg'
	}, {
	"id":"05",
	"car_name":"Hyundai Accent 2019",
	"speciality":"Speed : 175 km/h",
	"address":"Davao, Philippines",
	"lat":7.0731,
	"lng":125.6127,
	"icons":"default",
	"profile_link":"car-details.html",
	"image":'assets/img/car/car-05.jpg'
	}, {
	"id":"06",
	"car_name":"Toyota Innova 2021",
	"speciality":"Speed : 185 km/h",
	"address":"Iloilo, Philippines",
	"lat":10.6922,
	"lng":122.5597,
	"icons":"default",
	"profile_link":"car-details.html",
	"image":'assets/img/car/car-06.jpg'
	}, {
	"id":"07",
	"car_name":"Ford Ranger 2022",
	"speciality":"Speed : 195 km/h",
	"address":"Bacolod, Philippines",
	"lat":10.3957,
	"lng":123.0047,
	"icons":"default",
	"profile_link":"car-details.html",
	"image":'assets/img/car/car-07.jpg'
	}, {
	"id":"08",
	"car_name":"Chevrolet Trailblazer 2020",
	"speciality":"Speed : 188 km/h",
	"address":"Cagayan de Oro, Philippines",
	"lat":8.4866,
	"lng":124.6648,
	"icons":"default",
	"profile_link":"car-details.html",
	"image":'assets/img/car/car-08.jpg'
	}, {
	"id":"09",
	"car_name":"Toyota Fortuner 2023",
	"speciality":"Speed : 200 km/h",
	"address":"Davao, Philippines",
	"lat":7.0900,
	"lng":125.6150,
	"icons":"default",
	"profile_link":"car-details.html",
	"image":'assets/img/car/car-09.jpg'
	}
];

var icons = {
  'default':'assets/img/icons/marker.svg'
};

function show() {
    infowindow.close();
  if (!map.slide) {
    return;
  }
    var next, marker;
    if (locations.length == 0 ) {
       return
     } else if (locations.length == 1 ) {
       next = 0;
     }
    if (locations.length >1) {
      do {
        next = Math.floor (Math.random()*locations.length);
      } while (next == current)
    }
    current = next;
    marker = locations[next];
    setInfo(marker);
    infowindow.open(map, marker);
}

function initialize() {
    var bounds = new google.maps.LatLngBounds();
    var mapOptions = {
        zoom: 6,
		center: new google.maps.LatLng(12.8797, 121.7740),
        scrollwheel: false,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
		
    };
  
     map = new google.maps.Map(document.getElementById('map'), mapOptions);
    map.slide = true;

    setMarkers(map, locations);
    infowindow = new google.maps.InfoWindow({
        content: "loading..."
    });
    google.maps.event.addListener(infowindow, 'closeclick',function(){
       infowindow.close();
    });
    slider = window.setTimeout(show, 3000);
}

function setInfo(marker) {
  var content = 
'<div class="card border-0 mb-0" style="width: 100%; display: inline-block;">'+
	'<div class="card-body pt-0 p-2 d-flex align-items-center justify-content-between gap-3">'+
		'<div class="d-flex align-items-center">'+
			'<a href="' + marker.profile_link + '" class="avatar flex-shrink-0 me-2avatar-rounded" tabindex="0" target="_blank">'+
				'<img class="img-fluid" alt="' + marker.car_name + '" src="' + marker.image + '">'+
			'</a>'+
			'<div class="ms-2">'+
				'<h6 class="fs-14 fw-semibold mb-1">'+
					'<a href="' + marker.profile_link + '" tabindex="0">' + marker.car_name + '</a>'+
				'</h6>'+
				'<p class="fs-13">' + marker.speciality + '</p>'+
			'</div>'+
		'</div>'+
		'<div>'+
			'<a  href="' + marker.profile_link + '" tabindex="0" class="text-decoration-underline fw-medium link-violet">View</a>'+
		'</div>'+
	'</div>'+
'</div>';
  infowindow.setContent(content);
}

function setMarkers(map, markers) {
  for (var i = 0; i < markers.length; i++) {
    var item = markers[i];
    var latlng = new google.maps.LatLng(item.lat, item.lng);
    var marker = new google.maps.Marker({
        position: latlng,
        map: map,
        car_name: item.car_name,
        address: item.address,
        speciality: item.speciality,
        next_available: item.next_available,
        amount: item.amount,
        profile_link: item.profile_link,
        total_review: item.total_review,
        animation: google.maps.Animation.DROP,
        icon: icons[item.icons],
        image: item.image
        });
        bounds.extend(marker.position);
        markers[i] = marker;
        google.maps.event.addListener(marker, "click", function () {
            setInfo(this);
            infowindow.open(map, this);
            window.clearTimeout(slider);
        });
    }
    map.fitBounds(bounds);
  google.maps.event.addListener(map, 'zoom_changed', function() {
    if (map.zoom > 16) map.slide = false;
  });
}

google.maps.event.addDomListener(window, 'load', initialize);