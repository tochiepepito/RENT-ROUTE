/*
Author       : Dreamstechnologies
Template Name: Doccure - Bootstrap Template
Version      : 1.3
*/

google.maps.visualRefresh = true;
var slider, infowindow = null;
var bounds = new google.maps.LatLngBounds();
var map, current = 0;
var locations =[{
	"id":"01",
	"car_brand":"Toyota",
	"car_name":"Toyota Vios 2022",
	"car_image":'assets/img/car-list-1.jpg',
	"reviews":"(4.5) 120",
	"address":"Manila, Philippines",
	"km":"2.5km",
	"amount":"₱2,500",
	"lat":14.5995,
	"lng":120.9842,
	"icons":"'assets/img/icons/car-marker-01.svg'",
	"profile_link":"listing-details.html",
	"image":'assets/img/profiles/avatar-04.jpg'
	}, {
		
	"id":"02",
	"car_brand":"Honda",
	"car_name":"Honda Civic 2021",
	"car_image":'assets/img/car-list-2.jpg',
	"reviews":"(4.3) 95 ",
	"address":"Quezon City, Philippines",
	"km":"3.2km",
	"amount":"₱2,800",
	"lat":14.6760,
	"lng":121.0437,
	"icons":"'assets/img/icons/car-marker-01.svg'",
	"profile_link":"listing-details.html",
	"image":'assets/img/profiles/avatar-03.jpg'
	}, {
	"id":"03",
	"car_brand":"Nissan",
	"car_name":"Nissan Almera 2020",
	"car_image":'assets/img/car-list-3.jpg',
	"reviews":"(4.4) 110 ",
	"address":"Makati, Philippines",
	"km":"1.8km",
	"amount":"₱2,200",
	"lat":14.5547,
	"lng":121.0244,
	"icons":"default",
	"profile_link":"listing-details.html",
	"image":'assets/img/profiles/avatar-06.jpg'
	}, {
	"id":"04",
	"car_brand":"Mitsubishi",
	"car_name":"Mitsubishi Outlander 2023",
	"car_image":'assets/img/car-list-5.jpg',
	"reviews":"(4.6) 140 ",
	"address":"Cebu, Philippines",
	"km":"5.4km",
	"amount":"₱3,200",
	"lat":10.3157,
	"lng":123.8854,
	"icons":"default",
	"profile_link":"listing-details.html",
	"image":'assets/img/profiles/avatar-03.jpg'
	}, {
	"id":"05",
	"car_brand":"Hyundai",
	"car_name":"Hyundai Accent 2019",
	"car_image":'assets/img/car-list-6.jpg',
	"reviews":"(4.2) 85  ",
	"address":"Davao, Philippines",
	"km":"4.6km",
	"amount":"₱2,100",
	"lat":7.0731,
	"lng":125.6127,
	"icons":"default",
	"profile_link":"listing-details.html",
	"image":'assets/img/profiles/avatar-03.jpg'
	}
	];

/*var icons = {
  'default':'assets/img/marker.png'
};*/

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
'<div class="listing-item" style="width: 100%; display: inline-block;">'+											
									'<div class="listing-img">'+	
										'<a href="' + marker.profile_link + '">'+	
											'<img src="' + marker.car_image + '" class="img-fluid" alt="Audi">'+	
										'</a>'+	
										'<div class="fav-item justify-content-end">'+	
											'<a href="javascript:void(0)" class="fav-icon">'+	
												'<i class="feather-heart"></i>'+	
											'</a>	'+										
										'</div>'+		
										'<span class="featured-text">' + marker.car_brand + '</span>'+	
									'</div>'+											
									'<div class="listing-content">'+	
										'<div class="listing-features d-flex align-items-end justify-content-between">'+	
											'<div class="list-rating">'+	
												'<a href="javascript:void(0)" class="author-img">'+	
												'<img src="' + marker.image + '" class="img-fluid" alt="Audi">'+
												'</a>'+
												'<h3 class="listing-title">'+	
													'<a href="' + marker.profile_link + '">' + marker.car_name + '</a>'+	
												'</h3>			'+															  
												'<div class="list-rating">			'+					
												'	<i class="fas fa-star filled"></i>'+	
													'<i class="fas fa-star filled"></i>'+	
													'<i class="fas fa-star filled"></i>'+	
													'<i class="fas fa-star filled"></i>'+	
													'<i class="fas fa-star"></i>'+	
													'<span>' + marker.reviews + ' Reviews</span>'+
												'</div>'+
											'</div>'+
											'<div class="list-km">'+
												'<span class="km-count"><img src="assets/img/icons/map-pin.svg" alt="author">' + marker.km + '</span>'+
											'</div>'+
										'</div> '+
										'<div class="listing-details-group">'+
											'<ul>'+
												'<li>'+
													'<span><img src="assets/img/icons/car-parts-05.svg" alt="Manual"></span>'+
													'<p>Manual</p>'+
												'</li>'+
												'<li>'+
													'<span><img src="assets/img/icons/car-parts-02.svg" alt="10 KM"></span>'+
													'<p>10 KM</p>'+
												'</li>'+
												'<li>'+
													'<span><img src="assets/img/icons/car-parts-03.svg" alt="Petrol"></span>'+
													'<p>Petrol</p>'+
												'</li>'+
											'</ul>'+	
											'<ul>'+
												'<li>'+
													'<span><img src="assets/img/icons/car-parts-04.svg" alt="Power"></span>'+
													'<p>Power</p>'+
												'</li>'+
												'<li>'+
													'<span><img src="assets/img/icons/car-parts-05.svg" alt="2019"></span>'+	
													'<p>2019</p>'+	
												'</li>'+	
												'<li>'+	
													'<span><img src="assets/img/icons/car-parts-06.svg" alt="Persons"></span>'+	
													'<p>4 Persons</p>'+	
												'</li>'+	
											'</ul>'+	
										'</div>'+																	 
										'<div class="listing-location-details">'+	
											'<div class="listing-price">'+	
												'<span><i class="feather-map-pin"></i></span>' + marker.address + ''+	
											'</div>'+	
											'<div class="listing-price">'+	
												'<h6>' + marker.amount + '<span>/ Day</span></h6>'+	
											'</div>'+	
										'</div>'+	
										'<div class="listing-button">'+	
											'<a href="' + marker.profile_link + '" class="btn btn-order"><span><i class="feather-calendar me-2"></i></span>'+	'Rent Now</a>'+	
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
        car_brand: item.car_brand,
        car_name: item.car_name,
        amount: item.amount,
        profile_link: item.profile_link,
        reviews: item.reviews,
        animation: google.maps.Animation.DROP,
        icons: item.icons,
        image: item.image,
        km: item.km,
        car_image: item.car_image
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