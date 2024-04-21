<?php
Class Sheets {

    private $spreadsheet_id = '1KwldG50QgonWTqQXiKoZ4i-NTHlp9rTcfK-ck_OFX5s';
    private $service = '';
    private $db;

    private $sheets = array( 
        'Mobile only', 
        'Internet only', 
        'Internet + TV',  
        'Internet + TV + Fixe',  
        'Internet + Mobile', 
        'Internet + TV + Mobile', 
        'Internet + TV + Mobile + Fixe', 
    );

    function __construct() {

        $this->init();
        $this->db = new Comparator_store;

    }

    private function init() {

        require_once( SB_PLUGIN_DIR_ABS.'/vendor/vendor/autoload.php' );

        // configure the Google Client
        $client = new \Google_Client();
        $client->setApplicationName('Google Sheets API');
        $client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
        $client->setAccessType('offline');
        // credentials.json is the key file we downloaded while setting up our Google Sheets API
        $path = SB_PLUGIN_DIR_ABS.'/credentials.json';
        $client->setAuthConfig($path);

        // configure the Sheets Service
        $this->service = new \Google_Service_Sheets($client);

    }

    public function get_sheet_data( $sheet_name ) {

        $service = $this->service;

        $spreadsheetId = $this->spreadsheet_id;
        $spreadsheet = $service->spreadsheets->get($spreadsheetId);

        $response = $service->spreadsheets_values->get($spreadsheetId, $range=$sheet_name);
        $rows = $response->getValues();

        // With headers
        $headers = array_shift($rows);
        // Combine the headers with each following row
        $array = [];
        foreach ($rows as $row) {
            if( count($row) != count($headers) ) { continue; }
            $data[] = array_combine($headers, $row);
        }

        return $data;
        
    }

    public function get_all_sheets() {

        $sheets_list = $this->sheets;

        $values = array();
        foreach( $sheets_list as $name ) {
            $values[$name] = $this->get_sheet_data( $name );
        }

        return $values;

    }

    public function load_sheets_data() {

        // Clear before inserting
        $this->db->clear_table();

        $sheets = $this->get_all_sheets();

        foreach( $sheets as $sheet_name=>$rows ) {
            $this->db->insert_data( $table_title=$sheet_name, $rows );
        }

    }

    


}