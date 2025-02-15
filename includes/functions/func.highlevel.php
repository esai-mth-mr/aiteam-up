<?php
function pr($arr, $exit = true)
{
    echo '<pre style="white-space: break-spaces;word-wrap: break-word;">';
    print_r($arr);
    echo '</pre>';
    if ($exit) {
        exit;
    }
}


/* function getAuthToken()
{
    // https://highlevel.stoplight.io/docs/integrations/00d0c0ecaa369-get-access-token
    $client = new \GuzzleHttp\Client();
    $response = $client->request('POST', 'https://services.leadconnectorhq.com/oauth/token', [
        'form_params' => [
            'client_id' => '',
            'client_secret' => '',
            'grant_type' => 'authorization_code'
        ],
        'headers' => [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer 0000000000000',
            'Content-Type' => 'application/x-www-form-urlencoded',
        ],
    ]);
    return $response->getBody();
} */



function getOrPostGHLData($method = "GET", $subUrl = 'users/', $data = '')
{
    $apiKey = get_option('ghl_api_key');

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => get_option('ghl_base_url') . $subUrl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            "Authorization: Bearer $apiKey"
        ),
        CURLOPT_POSTFIELDS => $data
    ));
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        return ['error' => "cURL Error #:" . $err];
    } else {
        return json_decode($response, true);
    }
}

/* 
    example body
    '{
        "businessName": "'.$businessName.'",
        "address": "'.$address.'",
        "city": "'.$city.'",
        "country": "US",
        "state": "CA",
        "postalCode": "94304",
        "website": "https://www.google.com",
        "timezone": "US/Central",

        "firstName": "'.$first_name.'",
        "lastName": "'.$last_name.'",
        "email": "'.$email.'",
        "phone": "+1000-000-0000",
        "settings": {
            "allowDuplicateContact": false,
            "allowDuplicateOpportunity": false,
            "allowFacebookNameMerge": false,
            "disableContactTimezone": false
        },
        "twilio": {
            "sid": "AC_'.time().'",
            "authToken": "77_'.time().'"
        },
        "snapshot": {
            "id": "'.$uid.'",
            "type": "vertical"
        },
        "mailgun": {
            "apiKey": "key-'.time().'",
            "domain": "replies.yourdomain.com"
        },
        "social": {
            "facebookUrl": "https://facebook.com/groups/",
            "linkedIn": "https://www.linkedin.com/groups/XXX/profile",
            "foursquare": "https://foursquare.com/groups/XXX",
            "twitter": "https://twitter.com/XXX",
            "instagram": "https://instagram.com/XXX",
            "youtube": "https://youtube.com/XXX",
            "pinterest": "https://pinterest.com/XXX",
            "googlePlaceId": "null"
        }
    }'
*/
function createGHLLocation($data)
{
    $businessName = !empty($data['businessName'])?$data['businessName']:"AiTeamUP-User-Biz-".time();
    $address = !empty($data['address'])?$data['address']:"AiTeamUP-User-Biz-Address";
    $city = !empty($data['city'])?$data['city']:"AiTeamUP-User-Biz-City";

    $first_name = !empty($data['first_name'])?$data['first_name']:"First-Name";
    $last_name = !empty($data['last_name'])?$data['last_name']:"";
    $email = !empty($data['email'])?$data['email']:"";

    if(empty($email)){
        return ["msg"=>"Email field is required"];
    }

    $data = '{
        "businessName": "'.$businessName.'",
        "address": "'.$address.'",
        "city": "'.$city.'",
        "country": "US",
        "state": "CA",
        "postalCode": "94304",
        "website": "https://www.google.com",
        "timezone": "US/Central",

        "firstName": "'.$first_name.'",
        "lastName": "'.$last_name.'",
        "email": "'.$email.'",
        "phone": "+1000-000-0000",
        "settings": {
            "allowDuplicateContact": false,
            "allowDuplicateOpportunity": false,
            "allowFacebookNameMerge": false,
            "disableContactTimezone": false
        },
        "social": {
            "facebookUrl": "https://facebook.com/groups/",
            "linkedIn": "https://www.linkedin.com/groups/XXX/profile",
            "foursquare": "https://foursquare.com/groups/XXX",
            "twitter": "https://twitter.com/XXX",
            "instagram": "https://instagram.com/XXX",
            "youtube": "https://youtube.com/XXX",
            "pinterest": "https://pinterest.com/XXX",
            "googlePlaceId": "null"
        }
    }';

    $r = getOrPostGHLData("POST", 'locations/', $data);
    //pr($r);
    return $r;
}

function getGHLLocationByEmail($email=''){
    $r = getOrPostGHLData("GET", 'locations/lookup?email='.$email, '');
    return $r;
}

function createGHLUser($data)
{
    $firstName = !empty($data['first_name']) ? $data['first_name'] : '';
    $lastName = !empty($data['last_name']) ? $data['last_name'] : '';
    $email = !empty($data['email']) ? $data['email'] : '';
    $password = !empty($data['password']) ? $data['password'] : '';
    if (empty($firstName) || empty($email) || empty($password)) {
        return ['error' => "required field missing. check first_name, email and password"];;
    }
    $data = '{
        "firstName": "' . $firstName . '",
        "lastName": "' . $lastName . '",
        "email": "' . $email . '",
        "password": "' . $password . '",
        "type": "account",
        "role": "user",
        "locationIds": [
            "' . get_option('ghl_location_id') . '"
        ],
        "permissions": {
            "campaignsEnabled": true,
            "campaignsReadOnly": false,
            "contactsEnabled": true,
            "workflowsEnabled": true,
            "triggersEnabled": true,
            "funnelsEnabled": true,
            "websitesEnabled": false,
            "opportunitiesEnabled": true,
            "dashboardStatsEnabled": true,
            "bulkRequestsEnabled": true,
            "appointmentsEnabled": true,
            "reviewsEnabled": true,
            "onlineListingsEnabled": true,
            "phoneCallEnabled": true,
            "conversationsEnabled": true,
            "assignedDataOnly": false,
            "adwordsReportingEnabled": false,
            "membershipEnabled": false,
            "facebookAdsReportingEnabled": false,
            "attributionsReportingEnabled": false,
            "settingsEnabled": true,
            "tagsEnabled": true,
            "leadValueEnabled": true,
            "marketingEnabled": true
        }
    }';
    $r = getOrPostGHLData("POST", 'users/', $data);
    return $r;
}

function editGHLUser($userId, $data)
{
    $firstName = !empty($data['first_name']) ? $data['first_name'] : '';
    $lastName = !empty($data['last_name']) ? $data['last_name'] : '';
    $email = !empty($data['email']) ? $data['email'] : '';
    $password = !empty($data['password']) ? $data['password'] : '';
    if (empty($userId) || empty($firstName) || empty($email) || empty($password)) {
        return ['error' => "required field missing. check userId, first_name, email and password"];;
    }
    $data = '{
        "firstName": "' . $firstName . '",
        "lastName": "' . $lastName . '",
        "email": "' . $email . '",
        "password": "' . $password . '",
        "type": "account",
        "role": "user",
        "locationIds": [
            "' . get_option('ghl_location_id') . '"
        ],
        "permissions": {
            "campaignsEnabled": true,
            "campaignsReadOnly": false,
            "contactsEnabled": true,
            "workflowsEnabled": true,
            "triggersEnabled": true,
            "funnelsEnabled": true,
            "websitesEnabled": false,
            "opportunitiesEnabled": true,
            "dashboardStatsEnabled": true,
            "bulkRequestsEnabled": true,
            "appointmentsEnabled": true,
            "reviewsEnabled": true,
            "onlineListingsEnabled": true,
            "phoneCallEnabled": true,
            "conversationsEnabled": true,
            "assignedDataOnly": false,
            "adwordsReportingEnabled": false,
            "membershipEnabled": false,
            "facebookAdsReportingEnabled": false,
            "attributionsReportingEnabled": false,
            "settingsEnabled": true,
            "tagsEnabled": true,
            "leadValueEnabled": true,
            "marketingEnabled": true
        }
    }';
    $r = getOrPostGHLData("POST", 'users/' . $userId, $data);
    echo $r;
}

function getGHLUsers()
{
    $r = getOrPostGHLData("GET", 'users/', '');
    return $r;
}

function createGHLContact()
{
    $data = '{
        "email": "john@deo.com",
        "phone": "+188873241975",
        "firstName": "John",
        "lastName": "Deo",
        "name": "John Deo",
        "dateOfBirth": "1990-09-25",
        "address1": "3535 1st St N",
        "city": "Dolomite",
        "state": "AL",
        "country": "US",
        "postalCode": "35061",
        "companyName": "DGS VolMAX2",
        "website": "35062",
        "tags": [
            "aute consequat ad ea",
            "dolor sed"
        ],
        "source": "public api",
        "customField": {
            "__custom_field_id__": "exercitation"
        }
    }';
    $r = getOrPostGHLData("POST", 'contacts/', $data);
    return $r;
}

function getGHLContacts($limit = 100)
{
    $r = getOrPostGHLData("GET", 'contacts/?limit=' . $limit, '');
    return $r;
}

function getLocations()
{
    $r = getOrPostGHLData("GET", 'locations/');
    //pr($r);
    return $r;
}