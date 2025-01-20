<?php
/*
  ************************************************************
  *                        AWB                                 *
  *                       Copyright                              *
  *   Gyan Prakash Pandey   *   Ansh Shukla   *   Shiwan Tripathi   *
  *   Code is open source, but please do not remove this credit. *
  ************************************************************



  Azure 
*/?>

<?php
function translateText($text, $targetLanguage) {
    $apiKey = ''; // Use Key 1
    $region = 'northeurope';

    $url = "https://api.cognitive.microsofttranslator.com/translate?api-version=3.0&to=" . $targetLanguage;

    $headers = [
        'Content-Type: application/json',
        'Ocp-Apim-Subscription-Key: ' . $apiKey,
        'Ocp-Apim-Subscription-Region: ' . $region
    ];

    $data = [['Text' => $text]];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);
    curl_close($ch);

    if ($response === false) {
        return 'Translation error';
    }

    $result = json_decode($response, true);
    return $result[0]['translations'][0]['text'] ?? 'Translation failed';
}

header('Content-Type: application/json');
$requestBody = file_get_contents('php://input');
$requestData = json_decode($requestBody, true);
$translatedText = translateText($requestData['text'], $requestData['language']);
echo json_encode(['translatedText' => $translatedText]);
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
