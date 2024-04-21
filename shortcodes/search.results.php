<?php
add_shortcode( 'comparator_search_results', 'comparator_search_results' );
function comparator_search_results($atts, $content) {

    $content = json_decode($content, true);
    $result = $content['result'];
    $request = $content['request'];

    $packs = $result['packs'];
    ?>

    <?php if( count( $result['packs'] ) > 0 ) { ?>

        <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 col-12">
            <form id="results-filter" >
<div class="result-sort form-group row">
    <select class="form-control" name="sorting" id="sortingSelect">
        <option>Order</option>
        <option value="price_desc">Price desc</option>
        <option value="price_asc">Price asc</option>
        <option value="price_promo_desc">Price promo desc</option>
        <option value="price_promo_asc">Price promo asc</option>
    </select>
</div>
    <?php foreach( $result['filters'] as $key=>$item ) { ?>
        <h5><?php echo $item['title']; ?></h5>
        <div class="filter-block">
            <?php if( $item['view'] == 'checkbox' ) { ?>
                <?php foreach( $item['values'] as $val ) { ?>
                    <p class="mb-0" style="margin: 0;">
                        <input  type="checkbox" name="filter[<?php echo $key ?>][]" value="<?php echo $val; ?>" id="operator-<?php echo $val; ?>" />
                        <label for="operator-<?php echo $val; ?>"><?php echo $val; ?></label>
                    </p>
                <?php } ?>
            <?php } ?>    
            <?php if( $item['view'] == 'range' ) { ?>
                <div class="row">
                    <div class="col-md-6">
                    <input 
                        id="price-slider" 
                        type="text" 
                        data-slider-min="<?php echo $item['values']['min']; ?>" 
                        data-slider-max="<?php echo $item['values']['max']; ?>" 
                        data-slider-step="1" 
                        data-slider-value="[<?php echo $item['values']['min']; ?>,<?php echo $item['values']['max']; ?>]"
                        />
                        <span id="price-range"><?php echo $item['values']['min']; ?> - <?php echo $item['values']['max']; ?></span>

                        <input type="hidden" id="min-price" name="filter[price][min]" value="<?php echo $item['values']['min']; ?>">
                        <input type="hidden" id="max-price" name="filter[price][max]" value="<?php echo $item['values']['max']; ?>">
                    </div>
                </div>
            <?php } ?>     
        </div>
        <hr>
    <?php } ?>    
</form> 
            </div>
            <div class="col-md-9 col-12 result-list" id="result-list">
                <?php echo do_shortcode('[comparator_search_results_list]'.json_encode($packs).'[/comparator_search_results_list]'); ?>
            </div>
        </div>
        </div>
        <?php } else { ?>
        <div style="text-align: center;">No results</div>
    <?php } ?>    
    <?php
	return ob_get_clean();
}