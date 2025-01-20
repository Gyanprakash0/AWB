<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$user_input = isset($_GET['query']) ? trim($_GET['query']) : '';

if (empty($user_input)) {
    echo json_encode([
        "status" => "error",
        "message" => "Please provide a place name to search."
    ]);
    exit;
}

// Function to scrape Wikipedia for information
function scrapeWikipedia($query) {
    $url = "https://en.wikipedia.org/wiki/" . urlencode($query);
    
    // Get the contents of the page
    $html = @file_get_contents($url);
    
    if ($html === FALSE) {
        return [
            "status" => "not_found",
            "message" => "Could not retrieve data from Wikipedia.",
        ];
    }

    // Create a new DOMDocument
    $dom = new DOMDocument();
    @$dom->loadHTML($html); // Suppress warnings due to invalid HTML

    // Extract the first paragraph
    $paragraphs = $dom->getElementsByTagName('p');
    $info = '';
    
    foreach ($paragraphs as $para) {
        $info .= $para->textContent . "\n";
        if (strlen($info) > 500) { // Limit to first 500 characters
            break;
        }
    }

    return [
        "status" => "success",
        "info" => trim($info),
        "links" => [
            "Wikipedia Link" => $url,
        ],
    ];
}

// Process the query
$response = scrapeWikipedia($user_input);
echo json_encode($response);
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