<?php
add_shortcode( 'comparator_search_form', 'comparator_search_form' );
function comparator_search_form() {

    $mobile_plans = array(
        "mobile-xs" => "limited sms, limited calls, limited data, reallly basic use. 0-5 GB", 
        "mobile-s" => "small consumption. 6-10 GB",  
        "mobile-m" => "average consumption. 11-30 GB",  
        "mobile-l" => "large consumption. 31-80 GB",  
        "mobile-xl" => "everything illimited, intensive use. 81+ GB", 
    );

    $internet_plans = array(
        "internet-s" => "limited volume and limited speed", 
        "internet-m" => "illimited volume and average speed",  
        "internet-l" => "illimited volume and fast speed",  
    );

    $regions = array(
        "Wallonia",
        "Flanders",
        "Brussels",
    );

	ob_start();
    ?>



        <div id="comparator-top-text" class="row">
            <form action="#" method="post" id="comparatorFormData" autocomplete="on" role="form" class="form-horizontal" accept-charset="utf-8">

                <div class="row px-3 pb-3 pt-0 pt-md-3" id="comparatif_operateur">
                    <div class="col parameters-wrapper bordered pt-1 pb-3 ">
                        <div class="parameters position-relative">
                            <h3 class="centered-axis-x d-none d-md-block">
                                        <span class=" bg-white px-3 text-uppercase">
                                            Composez votre pack
                                        </span>
                                    </h3>
                            <div class="row">
                                <!-- MOBILES -->
                                <div class="col-12 col-sm-6 col-xl-3 parameter-block " id="parameter_block_mobile">
                                    <div class="parameter-block-title">
                                        <div class="checkbox-wrapper d-flex">
                                            <input class="pr-3 request-type" name="request-type[]" id="is_mobile" value="1" type="checkbox" checked="checked">
                                            <label for="is_mobile" class="cursor-pointer"> <i class="fa fa-2x pr-2 fa-mobile text-pink" aria-hidden="true" id="icon_mobile"></i> Nombre de GSM dans votre ménage </label>
                                        </div>
                                    </div>

                                    <?php foreach( $mobile_plans as $key=>$title ) { ?>

                                        <?php /* ?>
                                        <div class="input_choice mb-2 radio">
                                            <input name="mobile" id="mobile_<?php echo $key; ?>" type="radio" value="<?php echo $key; ?>">
                                            <label class="form-check-label" for="mobile_<?php echo $key; ?>">
                                                <?php echo $title; ?>
                                            </label>
                                        </div>
                                        <?php */ ?>

                                        <!-- inputs mobile -->
                                        <?php  ?>
                                        <div class="d-flex flex-row no-gutters mb-1 mb-sm-0 input_choice pl-1">
                                            <div class="mr-2">
                                                <div class="btn-group d-flex align-items-center group-plus-minus" role="group" aria-label="First group"> 
                                                    <i class="fa fa-minus-circle" onclick=""></i>
                                                    <input 
                                                        class="text-center" 
                                                        id="mobile_<?php echo $key; ?>" 
                                                        name="mobile[<?php echo $key; ?>]" 
                                                        type="text" 
                                                        value="0" 
                                                        style="width:20px"
                                                    > 
                                                    <i class="fa fa-plus-circle" onclick=""></i> 
                                                </div>
                                            </div>
                                            <label class="block-extra-option" for="mobile_<?php echo $key; ?>"> 
                                                <?php echo $title; ?>
                                            </label>
                                        </div>
                                        <?php  ?>

                                    <?php } ?>

                                </div>
                                <!-- NET -->
                                <div class="col-12 col-sm-6 col-xl-3 parameter-block " id="parameter_block_internet">
                                    <div class="parameter-block-title">
                                        <div class="checkbox-wrapper d-flex">
                                            <input class="pr-3 request-type" name="request-type[]" id="is_internet" value="2" type="checkbox">
                                            <label for="is_internet" class="cursor-pointer"> <i class="fa fa-2x pr-2 fa-laptop text-pink" aria-hidden="true"></i> Internet Wifi à la maison </label>
                                        </div>
                                    </div>
                                    <!-- inputs net -->
                                    <div id="block_params_internet" class="pl-1">

                                        <?php $count=0; ?>
                                        <?php foreach( $internet_plans as $key=>$title ) { ?>

                                            <div class="input_choice mb-2 radio">
                                                <input 
                                                    name="internet"
                                                    id="internet_<?php echo $key; ?>" 
                                                    type="radio" 
                                                    value="<?php echo $key; ?>" 
                                                    disabled
                                                    <?php if( $count == 0 ) { echo "checked"; } ?>
                                                 />
                                                <label class="form-check-label" for="internet_<?php echo $key; ?>">
                                                    <?php echo $title; ?>
                                                </label>
                                            </div>

                                            <?php $count++; ?>
                                        <?php } ?>

                                    </div>
                                </div>

                                <!-- Fixe -->
                                <div class="col-12 col-sm-6 col-xl-3 parameter-block " id="parameter_block_tv">
                                    <div class="parameter-block-title">
                                        <div class="checkbox-wrapper d-flex">
                                            <input class="pr-3 request-type" name="request-type[]" id="is_fixe" value="4" type="checkbox">
                                            <label for="is_fixe" class="cursor-pointer"> 
                                                <i class="fa fa-2x pr-2 fa-phone text-pink" aria-hidden="true"></i>
                                                Téléphone fixe									
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- TV -->
                                <div class="col-12 col-sm-6 col-xl-3 parameter-block " id="parameter_block_tv">
                                    <div class="parameter-block-title">
                                        <div class="checkbox-wrapper d-flex">
                                            <input class="pr-3 request-type" name="request-type[]" id="is_tv" value="3" type="checkbox">
                                            <label for="is_tv" class="cursor-pointer"> <i class="fa fa-2x pr-2 fa-television text-pink" aria-hidden="true"></i> Télévision </label>
                                        </div>
                                    </div>
                                </div>                                

                            </div>
                        </div>

                        <div class="row row-postal-code-order-by">
                            <div class="col-12 col-md-6">
                                <div class="mb-2">
                                    <label for="YcziHe8"><b>Region</b></label>
                                </div>
                                <div class="d-flex mb-2">
                                    <div class="flex-fill">
                                        <div class="typeahead border rounded position-relative">

                                            <select name="region" class="form-select form-control">

                                                <option></option>

                                                <?php foreach( $regions as $region ) { ?>
                                                    <option value="<?php echo $region; ?>"><?php echo $region; ?></option>
                                                <?php } ?>    

                                            </select>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div>

    <div id="search-results"></div>

    <?php
	return ob_get_clean();
}