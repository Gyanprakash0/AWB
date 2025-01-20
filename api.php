<?php

header("Access-Control-Allow-Origin: *");

header("Content-Type: application/json; charset=UTF-8");


$places = [
    [
        "name" => "Taj Mahal",
        "location" => "Agra, Uttar Pradesh",
        "estimated_budget" => "â‚¹10,000 - â‚¹15,000",
        "famous_food" => "Petha, Mughlai Cuisine",
        "nearby_places" => ["Agra Fort", "Mehtab Bagh", "Itmad-ud-Daulah's Tomb"],
        "nearest_airport" => "Agra Airport (AGR)",
        "best_time_to_visit" => "October to March",
        "precautions" => [
            "Stay hydrated, especially in summer.",
            "Be respectful of the site; avoid loud noises.",
            "Secure your belongings while visiting."
        ],
        "video_link" => "https://youtu.be/8HV1JVgqPM0?si=DmsOKEvEm_9dWsV7",
        "image" => "https://upload.wikimedia.org/wikipedia/commons/thumb/1/1d/Taj_Mahal_%28Edited%29.jpeg/1200px-Taj_Mahal_%28Edited%29.jpeg",
    ],
    [
        "name" => "Kedarnath Temple",
        "location" => "Kedarnath, Uttarakhand",
        "estimated_budget" => "â‚¹6,000 - â‚¹10,000",
        "famous_food" => "Aloo Puri, Bhatt Ki Chudkani",
        "nearby_places" => ["Badrinath", "Gaurikund", "Triyuginarayan Temple"],
        "nearest_airport" => "Jolly Grant Airport (Dehradun)",
        "best_time_to_visit" => "May to October",
        "precautions" => [
            "Ensure to carry sufficient warm clothing as temperatures can drop significantly.",
            "Stay hydrated and acclimatize to the altitude.",
            "Follow local guidelines and respect the sanctity of the temple.",
            "Travel with a certified guide if you're not familiar with the trekking routes."
        ],
        "video_link" => "https://youtu.be/OYiHRowaw2U?si=pOrURUqIQb-3fjYs",
        "image" => "https://travelobiz.com/wp-content/uploads/2019/05/Kedarnath-Yatra.jpg",
    ],
    [
        "name" => "Gateway of India",
        "location" => "Mumbai, Maharashtra",
        "estimated_budget" => "â‚¹8,000 - â‚¹12,000",
        "famous_food" => "Vada Pav, Pav Bhaji",
        "nearby_places" => ["Marine Drive", "Colaba Market", "Elephanta Caves"],
        "nearest_airport" => "Chhatrapati Shivaji Maharaj International Airport (BOM)",
        "best_time_to_visit" => "November to February",
        "precautions" => [
            "Be cautious of pickpockets in crowded areas.",
            "Avoid visiting late at night alone.",
            "Stay updated on local weather conditions."
        ],
        "video_link" => "https://youtu.be/zjJJko8iruE?si=ojWuVGfTt3Y_FUP-",
        "image" => "https://afar.brightspotcdn.com/dims4/default/07c9ffb/2147483647/strip/true/crop/1166x800+217+0/resize/660x453!/quality/90/?url=https%3A%2F%2Fk3-prod-afar-media.s3.us-west-2.amazonaws.com%2Fbrightspot%2F16%2F85%2F5c8949c60685339973fd53c817b8%2Foriginal-a54d684d8b0a182dd8ca390be1e6d627.jpg",
    ],
    [
        "name" => "Jaipur City",
        "location" => "Jaipur, Rajasthan",
        "estimated_budget" => "â‚¹5,000 - â‚¹10,000",
        "famous_food" => "Dal Baati Churma, Gatte Ki Sabzi",
        "nearby_places" => ["Hawa Mahal", "Amber Fort", "City Palace"],
        "nearest_airport" => "Jaipur International Airport (JAI)",
        "best_time_to_visit" => "October to March",
        "precautions" => [
            "Dress modestly when visiting temples.",
            "Avoid drinking tap water.",
            "Negotiate taxi fares before starting your journey."
        ],
        "video_link" => "https://youtu.be/uVEQFCLOIgI?si=oS33_ABbOavskcbC",
        "image" => "https://upload.wikimedia.org/wikipedia/commons/thumb/4/41/East_facade_Hawa_Mahal_Jaipur_from_ground_level_%28July_2022%29_-_img_01.jpg/1200px-East_facade_Hawa_Mahal_Jaipur_from_ground_level_%28July_2022%29_-_img_01.jpg",
    ],
    [
        "name" => "Varanasi",
        "location" => "Uttar Pradesh",
        "estimated_budget" => "â‚¹5,000 - â‚¹8,000",
        "famous_food" => "Kachori, Lassi",
        "nearby_places" => ["Sarnath", "Dashashwamedh Ghat", "Assi Ghat"],
        "nearest_airport" => "Lal Bahadur Shastri Airport (VNS)",
        "best_time_to_visit" => "October to March",
        "precautions" => [
            "Beware of strong currents when near the Ganges.",
            "Respect local customs and traditions.",
            "Avoid public displays of affection."
        ],
        "video_link" => "https://youtu.be/6gDBq8M_JOg?si=c47JtWdS9qAXBBEB",
        "image" =>"https://s7ap1.scene7.com/is/image/incredibleindia/manikarnika-ghat-city-hero?qlt=82&ts=1727959374496",
    ],
    [
        "name" => "Goa Beaches",
        "location" => "Goa",
        "estimated_budget" => "â‚¹10,000 - â‚¹15,000",
        "famous_food" => "Fish Curry, Bebinca",
        "nearby_places" => ["Anjuna Beach", "Baga Beach", "Fort Aguada"],
        "nearest_airport" => "Goa International Airport (GOI)",
        "best_time_to_visit" => "November to February",
        "precautions" => [
            "Avoid traveling alone at night.",
            "Keep valuables secure on the beach.",
            "Drink responsibly and stay hydrated."
        ],
        "video_link" => "https://youtu.be/N6yibD27dok?si=cV5ggNBXr_zfxNBP",
        "image" => "https://media.timeout.com/images/105984009/750/562/image.jpg",
    ],
    [
        "name" => "Kerala Backwaters",
        "location" => "Alleppey, Kerala",
        "estimated_budget" => "â‚¹7,000 - â‚¹12,000",
        "famous_food" => "Appam, Karimeen Polichathu",
        "nearby_places" => ["Kumarakom", "Vembanad Lake", "Marari Beach"],
        "nearest_airport" => "Cochin International Airport (COK)",
        "best_time_to_visit" => "September to March",
        "precautions" => [
            "Beware of monsoon flooding in July and August.",
            "Follow safety guidelines while on houseboats.",
            "Respect local wildlife in the area."
        ],
        "video_link" => "https://youtu.be/AP7MPrsQ-KE?si=vZ_aCzTfTORvQoX9",
        "image" => "https://www.shutterstock.com/image-photo/kerala-backwater-houseboat-traditional-allappey-600w-2454866115.jpg",
    ],
    [
        "name" => "Hampi",
        "location" => "Karnataka",
        "estimated_budget" => "â‚¹5,000 - â‚¹8,000",
        "famous_food" => "Ragi Mudde, Bisi Bele Bath",
        "nearby_places" => ["Vijaya Vitthala Temple", "Virupaksha Temple", "Hampi Bazaar"],
        "nearest_airport" => "Hubli Airport (HBX)",
        "best_time_to_visit" => "October to February",
        "precautions" => [
            "Carry water and snacks while exploring ruins.",
            "Be cautious while climbing hills.",
            "Respect the heritage sites."
        ],
        "video_link" => "https://youtu.be/fEq4Qxblzsw?si=S9JmL_3HIiCoM1s5",
        "image" => "https://backpackersunited.in/_next/image?url=https%3A%2F%2Fbpu-images-v1.s3.eu-north-1.amazonaws.com%2Fuploads%2F1721033522385_istockphoto-1270774245-1024x1024-transformed.jpeg&w=3840&q=100",
    ],
    [
        "name" => "Darjeeling",
        "location" => "West Bengal",
        "estimated_budget" => "â‚¹8,000 - â‚¹12,000",
        "famous_food" => "Darjeeling Tea, Momo",
        "nearby_places" => ["Tiger Hill", "Batasia Loop", "Peace Pagoda"],
        "nearest_airport" => "Bagdogra Airport (IXB)",
        "best_time_to_visit" => "March to May, September to November",
        "precautions" => [
            "Dress warmly; temperatures can drop at night.",
            "Be cautious of altitude sickness.",
            "Follow local guidelines during treks."
        ],
        "video_link" => "https://youtu.be/DWt_WQy1DCQ?si=L2AE8CL18_5GD54m",
        "image" => "https://www.indiatravel.app/wp-content/uploads/2024/02/places-to-visit-in-darjeeling.jpg",
    ],
    [
        "name" => "Mysore Palace",
        "location" => "Mysuru, Karnataka",
        "estimated_budget" => "â‚¹4,000 - â‚¹6,000",
        "famous_food" => "Mysore Pak, Ragi Mudde",
        "nearby_places" => ["Brindavan Gardens", "Chamundi Hill", "St. Philomena's Church"],
        "nearest_airport" => "Mysore Airport (MYQ)",
        "best_time_to_visit" => "October to March",
        "precautions" => [
            "Avoid touching artifacts in the palace.",
            "Respect photography rules.",
            "Stay hydrated and wear sunscreen."
        ],
        "video_link" => "https://youtu.be/F-HnBj5SJ2w?si=KzrHzSm5LxEYMAt9",
        "image" => "https://taxibazaar.in/assets/images/blog/mysore.jpg",
    ],
    [
        "name" => "Ajanta and Ellora Caves",
        "location" => "Maharashtra",
        "estimated_budget" => "â‚¹6,000 - â‚¹9,000",
        "famous_food" => "Puran Poli, Thalipeeth",
        "nearby_places" => ["Aurangabad", "Grishneshwar Temple", "Bibi Ka Maqbara"],
        "nearest_airport" => "Aurangabad Airport (IXU)",
        "best_time_to_visit" => "November to March",
        "precautions" => [
            "Avoid climbing on sculptures.",
            "Stay within marked paths.",
            "Wear comfortable shoes for walking."
        ],
        "video_link" => "https://youtu.be/wQXJyuYEJ2k?si=VHSp9l8XKpl3Huef",
        "image" => "https://travel-blog.happyeasygo.com/wp-content/uploads/2020/06/Ajanta-Caves-Monument.jpg",
    ],
    [
        "name" => "Ranthambore National Park",
        "location" => "Rajasthan",
        "estimated_budget" => "â‚¹10,000 - â‚¹15,000",
        "famous_food" => "Dal Baati, Gatte Ki Sabzi",
        "nearby_places" => ["Ranthambore Fort", "Kachida Valley", "Raj Bagh Ruins"],
        "nearest_airport" => "Jaipur International Airport (JAI)",
        "best_time_to_visit" => "October to June",
        "precautions" => [
            "Follow safety guidelines while on safari.",
            "Stay in designated areas during wildlife spotting.",
            "Avoid littering in the park."
        ],
        "video_link" => "https://youtu.be/5C0cFIfDv0o?si=jXav9QQ1Elqxd81D",
        "image" => "https://res.cloudinary.com/dyiffrkzh/image/upload/c_fill,f_auto,fl_progressive.strip_profile,g_center,h_400,q_auto,w_700/v1691581671/bbj/bh3m1d1xyaehjgzt3ckj.jpg",
    ],
    [
        "name" => "Rishikesh",
        "location" => "Uttarakhand",
        "estimated_budget" => "â‚¹4,000 - â‚¹7,000",
        "famous_food" => "Aloo Tikki, Chaat",
        "nearby_places" => ["Haridwar", "Neelkanth Mahadev Temple", "Laxman Jhula"],
        "nearest_airport" => "Dehradun Airport (DED)",
        "best_time_to_visit" => "September to November, March to May",
        "precautions" => [
            "Follow safety instructions for river rafting.",
            "Be cautious near riverbanks.",
            "Respect the tranquility of the spiritual atmosphere."
        ],
        "video_link" => "https://youtu.be/0wY9A5oji1E?si=2KGyURaVOD4_vdgE",
        "image" => "https://assets-news.housing.com/news/wp-content/uploads/2022/07/15104933/RISHIKESH-FEATURE-compressed.jpg",
    ],
    [
        "name" => "Andaman Islands",
        "location" => "Andaman and Nicobar Islands",
        "estimated_budget" => "â‚¹15,000 - â‚¹20,000",
        "famous_food" => "Seafood, Coconut Water",
        "nearby_places" => ["Havelock Island", "Neil Island", "Cellular Jail"],
        "nearest_airport" => "Veer Savarkar International Airport (IXZ)",
        "best_time_to_visit" => "October to May",
        "precautions" => [
            "Be cautious of strong currents in the sea.",
            "Avoid littering on beaches.",
            "Follow local guidelines for marine activities."
        ],
        "video_link" => "https://youtu.be/ASy5_JULXf8?si=55_ssTDtvDfI_x8c",
        "image" => "https://www.scubadiving-phuket.com/wp-content/uploads/2017/04/Scuba-Diving-Phuket-Andaman-Sea-Islands-Thailand.jpg",
    ],
    [
        "name" => "Khajuraho Temples",
        "location" => "Madhya Pradesh",
        "estimated_budget" => "â‚¹5,000 - â‚¹9,000",
        "famous_food" => "Dal Bafla, Chaat",
        "nearby_places" => ["Panna National Park", "Jewel of India Museum", "Raneh Falls"],
        "nearest_airport" => "Khajuraho Airport (HJR)",
        "best_time_to_visit" => "September to March",
        "precautions" => [
            "Respect local customs when visiting temples.",
            "Avoid climbing on sculptures.",
            "Stay hydrated and wear sunscreen."
        ],
        "video_link" => "https://youtu.be/KLh22u5rTIk?si=6cY8HwIj7gSoZx0K",
        "image" => "https://indiatravel.com/wp-content/uploads/2022/03/khajuraho-slider-imggg-3.jpg",
    ],
    [
        "name" => "Sundarbans National Park",
        "location" => "West Bengal",
        "estimated_budget" => "â‚¹8,000 - â‚¹12,000",
        "famous_food" => "Sundarbans Crab, Hilsa Fish",
        "nearby_places" => ["Pakhiralay", "Sajnekhali", "Ghosh Dighi"],
        "nearest_airport" => "Netaji Subhas Chandra Bose International Airport (CCU)",
        "best_time_to_visit" => "September to March",
        "precautions" => [
            "Stay within designated paths during exploration.",
            "Be cautious of wild animals.",
            "Follow safety guidelines from local guides."
        ],
        "video_link" => "https://youtu.be/G5Y_X9VeNrw?si=7RFaja9IklM05UeD",
        "image" => "https://www.elginhotels.com/wp-content/uploads/2020/03/kol-sundarban-national-park.jpg",
    ],
    [
        "name" => "Nainital",
        "location" => "Uttarakhand",
        "estimated_budget" => "â‚¹6,000 - â‚¹10,000",
        "famous_food" => "Bhelpuri, Aloo Tikki",
        "nearby_places" => ["Naini Lake", "Snow View Point", "Naina Devi Temple"],
        "nearest_airport" => "Pantnagar Airport (PGH)",
        "best_time_to_visit" => "March to June, September to November",
        "precautions" => [
            "Be cautious while boating on Naini Lake.",
            "Follow local guidelines for trekking.",
            "Keep warm clothing handy."
        ],
        "video_link" => "https://youtu.be/XtDgpuSXHDI?si=qQdLeeo2jqz9C-E0",
        "image" => "https://s3.india.com/wp-content/uploads/2024/03/Feature-Image_-Nainital-2.jpg?impolicy=Medium_Widthonly&w=350&h=263",
    ],
    [
        "name" => "Leh-Ladakh",
        "location" => "Jammu and Kashmir",
        "estimated_budget" => "â‚¹15,000 - â‚¹25,000",
        "famous_food" => "Momos, Thukpa",
        "nearby_places" => ["Pangong Lake", "Nubra Valley", "Khardung La Pass"],
        "nearest_airport" => "Kushok Bakula Rimpochee Airport (IXL)",
        "best_time_to_visit" => "May to September",
        "precautions" => [
            "Acclimatize to high altitudes.",
            "Carry sufficient warm clothing.",
            "Stay hydrated."
        ],
        "video_link" => "https://youtu.be/Y5dfturId8c?si=bW4vSMg7rbTjt83n",
        "image" => "https://www.prabhatkhabar.com/wp-content/uploads/2024/02/o-1.jpg",
    ],
    [
        "name" => "Mahabalipuram",
        "location" => "Tamil Nadu",
        "estimated_budget" => "â‚¹4,000 - â‚¹7,000",
        "famous_food" => "Seafood, Filter Coffee",
        "nearby_places" => ["Pancha Rathas", "Shore Temple", "Arjuna's Penance"],
        "nearest_airport" => "Chennai International Airport (MAA)",
        "best_time_to_visit" => "November to February",
        "precautions" => [
            "Avoid swimming in rough seas.",
            "Be cautious while exploring rocky areas.",
            "Stay hydrated."
        ],
        "video_link" => "https://youtu.be/-WbB2XHzXQs?si=SfbrEnyOT0KN2y1h",
        "image" => "https://www.tamilnadutourism.tn.gov.in/img/pages/large-desktop/mahabalipuram-1654870108_c538505993052d39e713.webp",
    ],
    [
        "name" => "Pondicherry",
        "location" => "Puducherry",
        "estimated_budget" => "â‚¹5,000 - â‚¹8,000",
        "famous_food" => "Biryani, French Pastries",
        "nearby_places" => ["Auroville", "Serenity Beach", "Sri Aurobindo Ashram"],
        "nearest_airport" => "Puducherry Airport (PNY)",
        "best_time_to_visit" => "October to March",
        "precautions" => [
            "Respect local culture and traditions.",
            "Avoid littering on beaches.",
            "Stay hydrated in the tropical climate."
        ],
        "video_link" => "https://youtu.be/LHjDD5Wo3AI?si=ep-jeeiZRxQrAbno",
        "image" => "https://travelmax.in/wp-content/uploads/2023/09/Feature_Image_French_Colony.jpg",
    ],
    [
        "name" => "Khajjiar",
        "location" => "Himachal Pradesh",
        "estimated_budget" => "â‚¹5,000 - â‚¹10,000",
        "famous_food" => "Chana Madra, Dham",
        "nearby_places" => ["Dalhousie", "Kalatope Wildlife Sanctuary", "Kalatop Khajjiar Sanctuary"],
        "nearest_airport" => "Gaggal Airport (DHM)",
        "best_time_to_visit" => "March to June, September to October",
        "precautions" => [
            "Dress warmly in winter.",
            "Be cautious while trekking.",
            "Stay on marked trails."
        ],
        "video_link" => "https://youtu.be/LO9WkG0Sxeo?si=WeUje-xyUoOxnu-9",
        "image" => "https://images.travelandleisureasia.com/wp-content/uploads/sites/2/2018/05/Khajjiar_Feature.jpg?tr=w-1200,q-60",
    ],
    [
        "name" => "Chikmagalur",
        "location" => "Karnataka",
        "estimated_budget" => "â‚¹6,000 - â‚¹10,000",
        "famous_food" => "Coffee, Bisi Bele Bath",
        "nearby_places" => ["Mullayanagiri", "Baba Budangiri", "Coffee Plantations"],
        "nearest_airport" => "Mangalore International Airport (IXE)",
        "best_time_to_visit" => "September to March",
        "precautions" => [
            "Avoid trekking during the monsoon.",
            "Follow safety guidelines for coffee plantations.",
            "Stay hydrated and wear sunscreen."
        ],
        "video_link" => "https://youtu.be/-4bs3gE4Aqs?si=9mUs6mbiTxF1CDLD",
        "image" => "https://www.chikmagalurtour.com/images/history-of-chikmagalur.jpg",
    ],
];

// Get the HTTP method of the request (GET)
$method = $_SERVER['REQUEST_METHOD'];

// Example coordinates for nearby places and airport
$latitude = isset($_GET['lat']) ? $_GET['lat'] : null;
$longitude = isset($_GET['lon']) ? $_GET['lon'] : null;

switch($method) {
    case 'GET':
        // If coordinates are passed, calculate distances
        if ($latitude && $longitude) {
            foreach ($places as &$place) {
                $place['distance_from_location'] = calculateDistance($latitude, $longitude, getCoordinates($place['location']));
            }
        }
        
        
        echo json_encode($places);
        break;

    default:
        http_response_code(405);  
        echo json_encode(["message" => "Method not allowed"]);
        break;
}


function calculateDistance($lat1, $lon1, $coords2) {
    $lat2 = $coords2['lat'];
    $lon2 = $coords2['lon'];
    
    $earth_radius = 6371;  

    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);

    $a = sin($dLat / 2) * sin($dLat / 2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($dLon / 2) * sin($dLon / 2);

    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

    return $earth_radius * $c;  
}

function getCoordinates($location) {
    $coords = [
        "Agra, Uttar Pradesh" => ["lat" => 27.1751, "lon" => 78.0421],
        "Mumbai, Maharashtra" => ["lat" => 19.0760, "lon" => 72.8777],
        "Jaipur, Rajasthan" => ["lat" => 26.9124, "lon" => 75.7873],
        "Alleppey, Kerala" => ["lat" => 9.4981, "lon" => 76.3388],
        "Amritsar, Punjab" => ["lat" => 31.6340, "lon" => 74.8723],
        "Kedarnath, Uttarakhand" => ["lat" => 30.7333, "lon" => 79.0669],
        "Varanasi, Uttar Pradesh" => ["lat" => 25.2820, "lon" => 82.9560],
        "Goa" => ["lat" => 15.2993, "lon" => 74.1240],
        "Hampi, Karnataka" => ["lat" => 15.3352, "lon" => 76.4610],
        "Darjeeling, West Bengal" => ["lat" => 27.0369, "lon" => 88.2622],
        "Mysuru, Karnataka" => ["lat" => 12.2958, "lon" => 76.6394],
        "Ajanta, Maharashtra" => ["lat" => 20.5497, "lon" => 75.6882],
        "Ranthambore National Park, Rajasthan" => ["lat" => 26.0173, "lon" => 76.6369],
        "Rishikesh, Uttarakhand" => ["lat" => 30.0866, "lon" => 78.2672],
        "Andaman Islands" => ["lat" => 11.6230, "lon" => 92.7265],
        "Khajuraho, Madhya Pradesh" => ["lat" => 24.8308, "lon" => 79.9192],
        "Sundarbans, West Bengal" => ["lat" => 21.9499, "lon" => 88.7470],
        "Nainital, Uttarakhand" => ["lat" => 29.3802, "lon" => 79.4549],
        "Leh, Jammu and Kashmir" => ["lat" => 34.1526, "lon" => 77.5775],
        "Mahabalipuram, Tamil Nadu" => ["lat" => 12.6200, "lon" => 80.1939],
        "Pondicherry" => ["lat" => 11.9332, "lon" => 79.8125],
        "Khajjiar, Himachal Pradesh" => ["lat" => 32.5420, "lon" => 76.0010],
        "Chikmagalur, Karnataka" => ["lat" => 13.3220, "lon" => 75.7776],
       
    ];

    return isset($coords[$location]) ? $coords[$location] : ["lat" => 0, "lon" => 0];
}
?>

<?php
/*
  ************************************************************
  *                        AWB                                 *
  *                       Copyright                              *
  *   Gyan Prakash Pandey   *   Ansh Shukla   *   Shiwan Tripathi   *
  *   Code is open source, but please do not remove this credit. *
  ************************************************************
*/?>