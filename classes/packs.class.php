<?php
Class Comparator_packs_adaptor {

    public function combine_packs( $items, $request ) {

        
        
        if( // Mobile only
            count($request['request-type']) == 1 && 
            in_array(1, $request['request-type']) 
            ) {
            $packs = $this->combine_mobile_packs($items, $request);
        }  elseif( // Internet + others
            in_array(2, $request['request-type']) &&
            !in_array(1, $request['request-type']) 
        ) { 
            $packs = $this->combine_internet_packs($items, $request);
        } elseif( // Mobile + Internet + Others
            in_array(1, $request['request-type']) &&
            in_array(2, $request['request-type'])
        ) { 
            $packs = $this->combine_mobile_internet_packs($items, $request);
        }

        return $packs;
    }

    private function combine_internet_packs($items, $request) {

        // We combine all items by Operator
        $packs = array();
        foreach( $items as $item ) {

            $totals = array(
                "price" => $item['price'],
                "promotion_price" => $item['promotion_price'],
            );

            $packs[ $item['operator'] ]['internet'] = $item;
            $packs[ $item['operator'] ]['totals'] = $totals;
        }

        return $packs;

    }

    private function combine_mobile_internet_packs($items, $request) {

        $packs = array();

        // Let's take the biggest mobile plan
        $high_mobile = '';
        foreach( $request['mobile'] as $plan=>$count ) {
            if( intval($count) != 0 ) { $high_mobile = $plan; }
        }
        // Get the biggest plan, we take just it
        $prior_plan = $request['internet'].'-'.$high_mobile;
        if( in_array(3, $request['request-type']) ) { $prior_plan .= '-tv'; }
        if( in_array(4, $request['request-type']) ) { $prior_plan .= '-fixe'; }

        // Remove low priority plans
        foreach( $items as $key=>$item ) {
            if( $item['product'] != $prior_plan ) { unset( $items[$key] ); }
        }
        
        // Add extra mobile if needed
        foreach( $items as $key=>$item ) {

            $totals = array();
           
            $extra_mobiles = [];

            foreach( $request['mobile'] as $plan=>$count ) {

                if( $count == 0) { continue; }
 
                $plan_check = $request['internet'].'-'.$plan;
                if( in_array(3, $request['request-type']) ) { $plan_check .= '-tv'; }
                if( in_array(4, $request['request-type']) ) { $plan_check .= '-fixe'; }

                global $wpdb;
                $query = "SELECT * FROM wp_comparator WHERE operator='{$item['operator']}' AND product='{$plan}' AND type='1'";
                $extra_items = $wpdb->get_results( $query, ARRAY_A );
                if( count($extra_items) > 0 ) {
                    $extra_item = $extra_items[0];
                }

                if( $plan_check == $item['product'] && $count == 1 ) { 
                    //unset($extra_item); 
                } elseif( $plan_check == $item['product'] && $count > 1 ) {
                    $count -= 1;
                }

                if( isset($extra_item) ) {
                    $extra_item['count'] = $count;
                    $extra_item['totals']['price'] = $extra_item['price'] * $count;
                    $extra_item['totals']['promotion_price'] = $extra_item['promotion_price'] * $count;

                    $totals['price'] += $extra_item['totals']['price'];
                    $totals['promotion_price'] += $extra_item['totals']['promotion_price'];

                    $extra_mobiles[] = $extra_item;
                }
                
            }

            $totals['price'] += $item['price'];
            $totals['promotion_price'] += $item['promotion_price'];

            $items[$key]['extra'] = $extra_mobiles;

            $packs[ $item['operator'] ] = array(
                "internet" => $item,
                "mobile" => $extra_mobiles,
                "totals" => $totals
            );

        }

        return $packs;

    }

    private function combine_mobile_packs($items, $request) {

        // We combine all items by Operator
        $packs = array();
        foreach( $items as $item ) {
            if( $item['type'] != 1 ) { continue; }

            $item['count'] = $request['mobile'][ $item['product'] ];
            $item['totals'] = array(
                "price" => $item['count'] * $item['price'],
                "promotion_price" => $item['count'] * $item['promotion_price'],
            );

            $packs[ $item['operator'] ]['mobile'][] = $item;
        }

        // Reiterate again
        foreach( $packs as $key=>$data ) {

            $sum = 0;
            $total_price = array_reduce($data['mobile'], function ($sum, $plan) { return $sum + $plan['totals']['price'];}, 0);

            $sum = 0;
            $total_promo_price = array_reduce($data['mobile'], function ($sum, $plan) { return $sum + $plan['totals']['promotion_price']; }, 0);

            $packs[$key]['totals'] = array(
                "price" => $total_price,
                "promotion_price" => $total_promo_price,
            );

        }

        return $packs;

    }

}