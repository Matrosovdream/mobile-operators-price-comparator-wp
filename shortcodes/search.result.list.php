<?php
add_shortcode( 'comparator_search_results_list', 'comparator_search_results_list' );
function comparator_search_results_list($atts, $content) {

    $packs = json_decode($content, true);

    /*
    echo "<pre>";
    print_r($packs);
    echo "</pre>";
    die();
    */
    ?>

    <?php foreach( $packs as $provider=>$pack ) { ?>

        

        <div class="row sortable-div" style="border: 1px solid #f6f6f6" 
            data-price="<?php echo $pack['totals']['price']; ?>"
            data-promo-price="<?php echo str_replace('.', ',', $pack['totals']['promotion_price']); ?>"
            data-provider="<?php echo str_replace('.', ',', $provider); ?>"
            >
            
            

            <?php if( isset($pack['mobile']) && is_array($pack['mobile']) && count($pack['mobile']) > 0 ) { ?>
                <div class="col-12 col-sm-6 col-xl-3 mobile-block">

                    <div class="result-item-inner-block">
                        <?php foreach( $pack['mobile'] as $item ) { ?>
                            <div class="block-mobile">

                                <div class="titleproduct-logo-brand m-0 mb-2">
                                    <img class="w-100" src="https://cdn.astel.be/assets/content/brand/logo_small/10/Telenet-logo-small.svg" alt="Telenet">
                                </div>

                                <p class="operator-name"><?php echo $item['operator']; ?></p>
                                <p class="plan-name"><?php echo $item['count']; ?> * <?php echo $item['plan_name']; ?></p>

                                <div style="text-align: center;">
                                    <i class="fa fa-2x fa-mobile text-pink"></i>
                                </div>
                                <p class="plan-property">
                                    Minutes: <b><?php echo $item['calls']; ?></b>
                                </p>
                                <p class="plan-property">
                                    SMS: <b><?php echo $item['sms']; ?></b>
                                </p>
                                <p class="plan-property">
                                    Data: <b><?php echo $item['data']; ?> GB</b>
                                </p>

                            </div>
                        <?php } ?>   
                    </div>

                    <?php /* ?>
                    <h6>Mobile</h6>
                    <?php foreach( $pack['mobile'] as $item ) { ?>
                        <div class="block-mobile" style="border: 1px solid #f6f6f6; margin-bottom: 5px; padding: 5px;">
                            <p class=""><b><?php echo $item['operator']; ?></b></p>
                            <p class="">Plan: <?php echo $item['plan_name']; ?> * <?php echo $item['count']; ?></p>
                            <p class="">Product: <?php echo $item['product']; ?> </p>
                            <?php if( $item['totals']['price'] > $item['totals']['promotion_price'] ) { ?>
                                <p>
                                    Price: 
                                    <?php echo $item['totals']['promotion_price']; ?>
                                    <strike><?php echo $item['totals']['price']; ?></strike>
                                </p>
                            <?php } else { ?>    
                                <p class="">Price: <?php echo $item['totals']['price']; ?></p>
                            <?php } ?>    
                        </div>
                    <?php } ?>   
                    <?php */ ?> 
                    
                </div>
            <?php } ?>  

            <?php if( isset($pack['internet']) && is_array($pack['internet']) ) { ?>
                <div class="col-12 col-sm-6 col-xl-3 mobile-block">

                    <div class="result-item-inner-block">
                        <div class="titleproduct-logo-brand m-0 mb-2">
                            <img class="w-100" src="https://cdn.astel.be/assets/content/brand/logo_small/10/Telenet-logo-small.svg" alt="Telenet">
                        </div>

                        <p class="operator-name"><?php echo $pack['internet']['operator']; ?></p>
                        <p class="plan-name"><?php echo $pack['internet']['plan_name']; ?></p>

                        <?php if( 
                            (int) $pack['internet']['installation_cost_normal'] > (int) $pack['internet']['installation_cost_promotion'] &&
                            (int) $pack['internet']['installation_cost_promotion'] != 0
                            ) {
                        ?>
                            <p class="plan-promo">
                                Activation & installation at <?php echo $pack['internet']['installation_cost_promotion']; ?> instead of <?php echo $pack['internet']['installation_cost_normal']; ?>
                            </p>
                        <?php } ?>

                        <div style="text-align: center;">
                            <i class="fa fa-2x fa-laptop d-block text-center text-pink mb-2"></i>
                        </div>
                        <p class="plan-property">
                            Speed: <b><?php echo $pack['internet']['download_speed']; ?> Mbps</b>
                        </p>
                        <p class="plan-property">
                            Upload: <b><?php echo $pack['internet']['loading_speed']; ?> Mbps</b>
                        </p>
                        <p class="plan-property">
                            Volume: <b><?php echo $pack['internet']['volume']; ?> GB</b>
                        </p>

                        <?php /* ?>
                        <h6>Internet</h6>
                        <div class="block-mobile" style="border: 1px solid #f6f6f6; margin-bottom: 5px; padding: 5px;">
                            <p class=""><b><?php echo $pack['internet']['operator']; ?></b></p>
                            <p class="">Plan: <?php echo $pack['internet']['plan_name']; ?></p>
                            <p class="">Product: <?php echo $pack['internet']['product']; ?> </p>
                            <?php if( $pack['internet']['price'] > $pack['internet']['promotion_price'] ) { ?>
                                <p>
                                    Price: 
                                    <?php echo $pack['internet']['promotion_price']; ?>
                                    <strike><?php echo $pack['internet']['price']; ?></strike>
                                </p>
                            <?php } else { ?>    
                                <p class="">Price: <?php echo $pack['internet']['price']; ?></p>
                            <?php } ?>   
                        </div>
                        <?php */ ?>
                    </div>
                    
                </div>
            <?php } ?>  

            <?php if( isset($pack['internet']) && is_array($pack['internet']) && strpos($pack['internet']['product'], 'fixe') ) { ?>
                <div class="col-12 col-sm-6 col-xl-2 fixe-block">
                    <div class="result-item-inner-block">
                    
                            <h6>Fixe</h6>                   
                    
                    </div>
                </div> 
            <?php } ?>  

            <?php if( isset($pack['internet']) && is_array($pack['internet']) && strpos($pack['internet']['product'], 'tv') ) { ?>
                <div class="col-12 col-sm-6 col-xl-2 fixe-block">
                    <div class="result-item-inner-block">
                    
                            <h6>TV</h6>                   
                    
                    </div>
                </div>                   
            <?php } ?> 
            

            <div class="col-12 col-sm-6 col-xl-2 summary-block">
            <h4 style="color: red; font-weight: bold;"><?php echo $provider; ?></h4>
                <h4>Totals</h4>
                <?php if( $pack['totals']['price'] > $pack['totals']['promotion_price'] ) { ?>
                    <p>
                        Price: 
                        <?php echo $pack['totals']['promotion_price']; ?>
                        <strike><?php echo $pack['totals']['price']; ?></strike>
                    </p>
                <?php } else { ?>    
                    <p class="">Price: <?php echo $pack['totals']['promotion_price']; ?></p>
                <?php } ?> 

                <a 
                    href="/index.php/comparator-user-form/?product=<?php echo http_build_query($pack); ?>" 
                    class="open-product-form btn btn-primary btn-right-arrow rounded-0 gtm-add-to-cart" 
                    >
                    <span class=""><?php echo _('Commander'); ?></span>
                </a>

            </div>
        </div>

    <?php } ?>

<?php
	return ob_get_clean();
}