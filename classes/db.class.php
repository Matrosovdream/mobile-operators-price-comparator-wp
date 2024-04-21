<?php
Class Comparator_store {

    private $tables = array(
        "operators" => "wp_comparator"
    );

    private $fields = array(
        "Opérateur" => "operator",
        "Produit" => "product",
        "Nom" => "plan_name",
        "Vitesse de téléchargement (Mbps)" => "download_speed",
        "Vitesse de chargement (Mbps)" => "loading_speed",
        "Volume (GB)" => "volume",
        "Prix normal" => "price",
        "Promotion" => "promotion_price",
        "Durée de la promotion (mois)" => "promotion_duration",
        "Coût d'installation normal" => "installation_cost_normal",
        "Coût d'installation avec promotion" => "installation_cost_promotion",
        "Nombre de chaînes" => "channels_number",
        "Appels" => "calls",
        "SMS" => "sms",
        "Data (GB)" => "data",
    );

    private $types = array( 
        'Mobile only' => "1", 
        'Internet only' => "2", 
        'Internet + TV' => "1,3",  
        'Internet + TV + Fixe' => "2,3,4",  
        'Internet + Mobile' => "1,2", 
        'Internet + TV + Mobile' => "1,2,3", 
        'Internet + TV + Mobile + Fixe' => "1,2,3,4", 
    );

    public function clear_table() {

        global $wpdb;
        $wpdb->query( 'DELETE FROM '.$this->tables['operators'] );

    }

    public function insert_data( $table_title, $rows ) {

        foreach( $rows as $row ) {

            $data = array();
            
            // For search engine
            $type = $this->types[ $table_title ];
            $data['type'] = $type;

            foreach( $row as $title=>$val ) {
                $data[ $this->fields[$title] ] = $val;
            }

            if( count($data) > 1 ) {
                $this->insert_row( $this->format_fields($data) );
            }

        }

    }

    public function insert_row( $data ) {

        global $wpdb;

        $wpdb->insert( $this->tables['operators'], $data );

        return true;

    }

    public function get( $data ) {

        global $wpdb;

        if( is_array($data['WHERE']) && count( $data['WHERE'] ) > 0 ) {
            $where = $this->generate_where_query( $data['WHERE'] );
        }

        if( is_array($data['ORDER']) && count( $data['ORDER'] ) > 0 ) {
            $order = $this->generate_orberby_query( $data['ORDER'] );
        } else {
            $order = " ORDER BY price DESC";
        }       

        $query = "SELECT * FROM ".$this->tables['operators']." $where $order"; 
        //echo $query;

        $res = $wpdb->get_results( $query, 'ARRAY_A' );

        return $res;

    }

    public function format_fields( $rows ) {

        foreach( $rows as $key=>$val ) {

            if( $key == 'price' || $key == 'promotion_price' ) {
                $rows[$key] = $this->to_float($val); 
            }

        }

        return $rows;

    }

    public function to_float($val) {

        $cleanedValue = preg_replace("/[^0-9.]/", "", str_replace(',', '.', $val));
        $floatValue = round(floatval($cleanedValue), 2);
    
        return $floatValue;

    }

    public function generate_orberby_query($params) {

        if( !is_array($params) && count($params) == 0 ) { return false; }

        $query = " ORDER BY ";
        
        foreach( $params as $key=>$val ) {
            $query .= $key." ".$val;
        }

        return $query;

    }

    public function generate_where_query($params) {

        $query = '';

        foreach ($params as $param => $condition) {
            $method = strtoupper($condition['METHOD']) ?? 'AND';
            $data = $condition['data'];

            switch ($method) {
                case '=':
                    $query .= "(" . $this->generateEqualConditions($param, $data) . ") AND ";
                    break;
                case 'OR':
                    $query .= "(" . $this->generateOrConditions($param, $data) . ") AND ";
                    break;
                case 'OR_LIKE':
                        $query .= "(" . $this->generateOrLikeConditions($param, $data) . ") AND ";
                        break;    
                case 'AND':
                    $query .= "(" . $this->generateAndConditions($param, $data) . ") AND ";
                    break;
                case 'NOT_IN':
                    $query .= "($param NOT IN ('" . implode("', '", $data) . "')) AND ";
                    break;
                case 'IN':
                    $query .= "($param IN ('" . implode("', '", $data) . "')) AND ";
                    break;    
                case 'BETWEEN':
                    $query .= "(" . $this->generateBetweenCondition($param, $data) . ") AND ";
                    break;
                case 'EQUAL':
                    $query .= "(" . $this->generateEqualCondition($param, $data) . ") AND ";
                    break;
            }
        }

        if( $query != '' ) {

            // Remove the trailing 'AND' or 'OR' from the end of the query
            $query = rtrim($query, 'AND ');
            $query = rtrim($query, 'OR ');

            $query = "WHERE ".$query;

        }

        return $query;
    }

    private function generateOrConditions($param, $data) {
        return implode(" OR ", array_map(fn($item) => "$param='$item'", $data));
    }

    private function generateOrLikeConditions($param, $data) {
        return implode(" OR ", array_map(fn($item) => "$param LIKE '%$item%'", $data));
    }

    private function generateAndConditions($param, $data) {
        return implode(" AND ", array_map(fn($item) => "$param='$item'", $data));
    }

    private function generateBetweenCondition($param, $data) {
        return "$param BETWEEN {$data[0]} AND {$data[1]}";
    }

    private function generateEqualCondition($param, $data) {
        return "$param = '{$data}'";
    }

}




/*
CREATE TABLE `wp_comparator` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `type` varchar(100) NOT NULL,
  `operator` varchar(100) NOT NULL,
  `product` varchar(100) NOT NULL,
  `plan_name` varchar(100) NOT NULL,
  `download_speed` varchar(100) NOT NULL,
  `loading_speed` varchar(100) NOT NULL,
  `volume` varchar(100) NOT NULL,
  `price` float(100) NOT NULL,
  `promotion_price` float(100) NOT NULL,
  `promotion_duration` varchar(100) NOT NULL,
  `installation_cost_normal` varchar(100) NOT NULL,
  `installation_cost_promotion` varchar(100) NOT NULL,
  `channels_number` varchar(100) NOT NULL,
  `calls` varchar(100) NOT NULL,
  `sms` varchar(100) NOT NULL,
  `data` varchar(100) NOT NULL
)
*/