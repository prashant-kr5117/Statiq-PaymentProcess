<?php

class ZohoInventory
{
    public $organizationId;
    public function __construct()
    {
        $this->organizationId = '60006170914';
    }
    function getAccessToken()
    {
        date_default_timezone_set('Asia/Kolkata');
        $crm_config = array(
            'client_id' => '1000.IASGS9N5FCUUXHN0PLNOLZZAPEVATD',
            'client_secret' => '76f585ee032a494523bf30fa36882af7ee82a9452b',
            'refresh_token' => '1000.5d82e0ff40e3995c2e717b11034307fb.079773740e03f54ac2aec6013cd16d19',
            'grant_type' => 'refresh_token',
            'redirect_uri' => 'http://localhost/Statiq-PaymentProcess/',
            "api_domain" => "https://crm.zoho.in/",
            'account_domain' => 'https://accounts.zoho.in/',
        );
        $tokenFile = 'zoho/access_token.json';
        $zoho_crm_session = file_get_contents($tokenFile);
        $zoho_crm_session = json_decode($zoho_crm_session, true);
        $current_time = date('Y-m-d H:i:s');
        // print_r($zoho_crm_session);
        // echo $current_time." ".$zoho_crm_session['expiring_at'];die;
        if (isset($zoho_crm_session['access_token']) && ($current_time < $zoho_crm_session['expiring_at'])) {
            
            return $zoho_crm_session;
        } else {
            // echo "New";
            $url = $crm_config['account_domain'] . 'oauth/v2/token';
            $postData = [
                'client_id' => $crm_config['client_id'],
                'client_secret' => $crm_config['client_secret'],
                'refresh_token' => $crm_config['refresh_token'],
                'grant_type' => $crm_config['grant_type'],
            ];

            $ch = curl_init(); // Create a curl handle
            curl_setopt($ch, CURLOPT_URL, $url); // Third party API URL
            curl_setopt($ch, CURLOPT_POST, false); // To set POST method true
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData); // To send data to the API URL
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // To set SSL Verifier false
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // To set return response from the API
            $response = curl_exec($ch); // To execute the handle and get the response
            $response = json_decode($response, true);
            if (isset($response['access_token'])) {
                $now = time();
                $ten_minutes = $now + (5 * 60);
                $startDate = date('Y-m-d H:i:s', $now);
                $endDate = date('Y-m-d H:i:s', $ten_minutes);
                $current_time = date('Y-m-d H:i:s');
                $response['expiring_at'] = $endDate;
                file_put_contents($tokenFile, json_encode($response));
                return $response;
            } else {
                echo "Error Processing Request";
            }
        }
    }

    function getAllPI()
    {
        $credentials = $this->getAccessToken();
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://inventory.zoho.in/api/v1/cm_vendor_payment_approval',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $credentials['access_token'],
                'Cookie: BuildCookie_60006170914=1; JSESSIONID=1A1F50F041F86AF49F78480F01766F3D; _zcsr_tmp=195eaec2-9fbc-40a6-a628-5ff6754b9cd8; zalb_3241fad02e=bd1d8e8ad28c5cf91bf756692723b075; zomcscook=195eaec2-9fbc-40a6-a628-5ff6754b9cd8'
            ),
        ));

        $result = curl_exec($curl);
        curl_close($curl);
        $data = json_decode($result, TRUE);
        $code = $data['code'];
        if ($code != 0) {
            return $data['message'];
        }

        return $data["module_records"];
    }

    function getPI_Details($module_record_id)
    {
        $credentials = $this->getAccessToken();
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://inventory.zoho.in/api/v1/cm_vendor_payment_approval/' . $module_record_id . '?organization_id=60006170914',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $credentials['access_token'],
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        // Check for cURL errors 
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            curl_close($curl);
            return json_encode(array('error' => true, 'message' => $error_msg));
        }

        curl_close($curl);

        // Check if the response is empty
        if (empty($response)) {
            return json_encode(array('error' => true, 'message' => 'Empty response from API.'));
        }

        return $response; // Return the raw response (or parse it as JSON if needed)
    }

    function getPurchase_Order_Details($module_record_id)
    {
        $credentials = $this->getAccessToken();
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://inventory.zoho.in/api/v1/purchaseorders/' . $module_record_id . '?organization_id=60006170914',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $credentials['access_token'],
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        // Check for cURL errors 
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            curl_close($curl);
            return json_encode(array('error' => true, 'message' => $error_msg));
        }

        curl_close($curl);

        // Check if the response is empty
        if (empty($response)) {
            return json_encode(array('error' => true, 'message' => 'Empty response from API.'));
        }

        return $response; // Return the raw response (or parse it as JSON if needed)
    }


    function getLoggedInUser()
    {

        $credentials = $this->getAccessToken();
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://inventory.zoho.in/api/v1/users/me?organization_id=60006170914',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                 'Authorization: Bearer ' . $credentials['access_token'],
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    public function uploadDocument($filePath)
    {
        // Step 1: Check if the file exists
        if (!file_exists($filePath)) {
            return ['message' => 'Error: File not found at ' . $filePath];
        }

        // Step 2: Get the access token
        $credentials = $this->getAccessToken();
        $accessToken = $credentials['access_token'] ?? null;

        if (!$accessToken) {
            return ['message' => 'Access token is invalid or missing'];
        }

        // Step 3: Set up the cURL request
        $url = 'https://www.zohoapis.in/inventory/v1/documents?organization_id=60006170914';
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => [
                'attachment' => new CURLFile($filePath),
            ],
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $accessToken,
            ],
        ]);

        // Step 4: Execute the request
        $response = curl_exec($curl);

        // Step 5: Handle errors
        if (curl_errno($curl)) {
            $error = curl_error($curl);
            curl_close($curl);
            return ['message' => 'cURL Error: ' . $error];
        }

        curl_close($curl);
        file_put_contents('debug.log', "\nUploading Attachment: \n" . print_r($response), FILE_APPEND);

        // Step 6: Decode and return the API response
        return json_decode($response, true);
    }

    public function create_TransferRequest($payload)
    {
        $credentials = $this->getAccessToken();
        if (!$credentials['access_token']) {
            file_put_contents('debug.log', "Access token is invalid or missing.\n", FILE_APPEND);
            return ['message' => 'Access token error'];
        }
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://inventory.zoho.in/api/v1/cm_vendor_payment_approval?organization_id=60006170914',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $credentials['access_token'],
                'Content-Type: application/json',
            ],
        ]);
        $response = curl_exec($curl);
        $error = curl_error($curl);

        curl_close($curl);

        if ($error) {
            // file_put_contents('debug.log', "cURL Error:\n" . $error, FILE_APPEND);
            return ['message' => 'cURL Error: ' . $error];
        }
        return json_decode($response, true);
    }


    public function create_vendor_payment($payload)
    {
        $credentials = $this->getAccessToken();
        if (!$credentials['access_token']) {
            file_put_contents('debug.log', "Access token is invalid or missing.\n", FILE_APPEND);
            return ['message' => 'Access token error'];
        }
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://inventory.zoho.in/api/v1/vendorpayments/?organization_id=60006170914',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $credentials['access_token'],
                'Content-Type: application/json',
            ],
        ]);
        $response = curl_exec($curl);
        $error = curl_error($curl);

        curl_close($curl);

        if ($error) {
            return ['message' => 'cURL Error: ' . $error];
        }
        return json_decode($response, true);
    }
    
    public function create_running_payment_Terms($payload)
    {
        $credentials = $this->getAccessToken();
        if (!$credentials['access_token']) {
            file_put_contents('debug.log', "Access token is invalid or missing.\n", FILE_APPEND);
            return ['message' => 'Access token error'];
        }
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://inventory.zoho.in/api/v1/cm_running_payment_terms?organization_id=60006170914',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $credentials['access_token'],
                'Content-Type: application/json',
            ],
        ]);
        $response = curl_exec($curl);
        $error = curl_error($curl);

        curl_close($curl);

        if ($error) {
            // file_put_contents('debug.log', "cURL Error:\n" . $error, FILE_APPEND);
            return ['message' => 'cURL Error: ' . $error];
        }
        return json_decode($response, true);
    }

    public function updateZohoInventoryRecord($recordId, $payload)
    {
        $url = "https://inventory.zoho.in/api/v1/cm_vendor_payment_approval/$recordId?organization_id=60006170914";

        $credentials = $this->getAccessToken();
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer " . $credentials['access_token'],
                'Content-Type: application/json'
            ),
        ));

        // Execute cURL request
        $response = curl_exec($curl);


        // Handle errors
        if (curl_errno($curl)) {
            $error = curl_error($curl);
            curl_close($curl);
            return [
                'success' => false,
                'error' => $error,
            ];
        }

        curl_close($curl);

        // Decode response
        $responseDecoded = json_decode($response, true);

        return [
            'success' => true,
            'response' => $responseDecoded,
        ];
        
    }

    function update_PO_Record($recordId, $payload)
    {
        $url = "https://inventory.zoho.in/api/v1/purchaseorders/$recordId?organization_id=60006170914";

        $credentials = $this->getAccessToken();
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer " . $credentials['access_token'],
                'Content-Type: application/json'
            ),
        ));
        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            $error = curl_error($curl);
            curl_close($curl);
            return [
                'success' => false,
                'error' => $error,
            ];
        }

        curl_close($curl);

        // Decode response
        $responseDecoded = json_decode($response, true);

        return [
            'success' => true,
            'response' => $responseDecoded,
        ];
    }
    function update_runningPaymentTerms_Record($recordId, $payload)
    {
        $url = "https://inventory.zoho.in/api/v1/cm_running_payment_terms/$recordId?organization_id=60006170914";

        $credentials = $this->getAccessToken();
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer " . $credentials['access_token'],
                'Content-Type: application/json'
            ),
        ));
        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            $error = curl_error($curl);
            curl_close($curl);
            return [
                'success' => false,
                'error' => $error,
            ];
        }

        curl_close($curl);

        // Decode response
        $responseDecoded = json_decode($response, true);

        return [
            'success' => true,
            'response' => $responseDecoded,
        ];
    }




}
