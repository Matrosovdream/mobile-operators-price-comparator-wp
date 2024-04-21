<?php
class Comparator_search {

    private $params = array();
    private $sorting = array();
    private $db;

    public $services = array(
        "1" => "Mobile",
        "2" => "Internet",
        "3" => "TV",
        "4" => "Phone"
    );

    public function __construct( $params=array(), $sorting=array() ) {

        $this->params = $params;
        $this->sorting = $sorting;
        $this->db = new Comparator_store;
        $this->packs = new Comparator_packs_adaptor;

    }

    public function submit() {

        $query = $this->prepare_query();
        $query['ORDER'] = $this->prepare_order($this->sorting);

        $items = $this->db->get( $data=$query );
        
        if( count( $items ) > 0 ) {

            $packs = $this->packs->combine_packs( $items, $this->params );

            $data = array(
                "filters" => $this->combine_filters( $packs ),
                "packs" => $packs,
            );
        }  else {
            $data = array(
                "filters" => array(),
                "packs" => array(),
            );
        }

        return $data;

    }



    public function combine_short_products( $items ) {

        if( !is_array($items) || count($items) == 0 ) { return false; }

        $res = array();
        foreach( $items as $item ) {
            $res[] = $item['product'];
        }

        return $res;

    }

    public function combine_filters( $items ) {

        if( !is_array($items) || count($items) == 0 ) { return false; }

        $prices = array();
        $operators = array();
        foreach( $items as $operator=>$item ) {
            $prices[] = $item['totals']['price'];
            $operators[] = $operator;
        }

        return array(
            "price" => array(
                "view" => "range",
                "title" => "Price",
                "values" => array(
                    "min" => min($prices),
                    "max" => max($prices),
                ),
            ),
            "operators" => array(
                "view" => "checkbox",
                "title" => "Operator",
                "values" => array_unique($operators)
            )
        );

    }

    private function prepare_order( $data ) {

        if( !$data ) { return false; }

        $raw = explode('_', $data);
        return array( $raw[0] => $raw[1] );

    }

    private function prepare_query() {

        $params = $this->params;

        $data = array();
        $data['WHERE'] = array();

        // Internet plans
        $params['products'] = $this->adapt_internet_plans( $params );
        
        foreach( $params as $key=>$val ) {

            if( $key == 'request-type' && count($val) > 0 ) {
                /*$data['WHERE']['type'] = array(
                    "METHOD" => "AND",
                    "data" => array( implode(',', $params['request-type']) )
                );*/
            } elseif( $key == 'region' && $val != '' ) {
                $operators = $this->filter_by_region( $params['region'] );
                
                if( is_array($operators) && count($operators) > 0 ) {
                    $data['WHERE']['operator'] = array(
                        "METHOD" => "NOT_IN",
                        "data" => $operators
                    );
                }
            } elseif( $key == 'products' && !is_array($val) && $val != '' ) {
                $plans_search = explode(',', $val);
            } elseif( $key == 'products' && is_array($val) ) {
                $data['WHERE']['product'] = array(
                    "METHOD" => "IN",
                    "data" => $val
                );
            } elseif( $key == 'price' ) {

                $data['WHERE']['price'] = array(
                    "METHOD" => "BETWEEN",
                    "data" => array( $val['min'], $val['max'] )
                );

            } elseif( $key == 'operators' ) {

                $data['WHERE']['operator'] = array(
                    "METHOD" => "IN",
                    "data" => $val
                );
            
            }

        }

        return $data;

    }

    private function adapt_internet_plans( $params ) {

        $set = array();

        $mobile = array();
        if( isset($params['mobile']) && is_array($params['mobile']) && count( $params['mobile'] ) > 0 ) {
            
            foreach( $params['mobile'] as $plan=>$count ) {
                if( $count > 0 ) {
                    $mobile[] = $plan;
                }
            }
        }

        if( isset($params['internet']) ) { $set[0] = $params['internet']; }

        // TV
        if( isset($params['request-type']) && is_array($params['request-type']) && in_array('3', $params['request-type']) ) { $set[2] = 'tv'; }

        // Fixe
        if( isset($params['request-type']) && is_array($params['request-type']) && in_array('4', $params['request-type']) ) { $set[3] = 'fixe'; }

        $plans = array();
        if( count($mobile) > 0 ) {
            foreach( $mobile as $val ) {
                $set[1] = $val;
                ksort($set);
                $plans[] = implode('-', $set );
            }
        } else {
            $plans[] = implode('-', $set);
        }

        return $plans;

    }

    // Restricted providers
    private function filter_by_region( $region='' ) {

        if( $region == '' ) { return false; }

        $data = array(
            "Flanders" => array("VOO", "Zuny"),
            "Wallonia" => array("Telenet"),
        );

        return $data[$region];


    }

}