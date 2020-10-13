
<?php echo $header; ?>
<div>
    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <?php echo $breadcrumb['separator']; ?><a
                href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>
    <?php if ($error_warning) { ?>
    <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>
    <div class="box">
        <div class="heading">
            <div class="buttons"><a onclick="$('#form').submit();" class="button"><span class="button_left button_save"></span><span class="button_middle"><?php echo $button_save; ?></span><span class="button_right"></span></a><a onclick="location='<?php echo $cancel; ?>';" class="button"><span class="button_left button_cancel"></span><span class="button_middle"><?php echo $button_cancel; ?></span><span class="button_right"></span></a></div>
        </div>
              <div class="container-fluid">
                <div class="humm-header">
                    <div class="humm-payment-logo"></div>
                    <div class="humm-payment-byline">Little things. Big things. Interest freeee!<br/>Humm Payment</div>
                </div>
                <div id="humm_simplepath">
                    <div id="simplepath_unsupported">
                        <p><a href="https://docs.shophumm.com.au/ecommerce/opencart/" target="_blank"> Humm  OpenCart
                                Documentation </a></p>
                    </div>
                </div>

                <div id="humm_simplepath_back">
                    <p>If you don't have a humm merchant account you may join <a
                                href="https://www.shophumm.com.au/sell-with-humm"
                                target="_blank"><span>here</span></a></p>
                </div>
            </div>
            <hr/>
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                <table class="form">
                    <tr>
                        <td><?php echo $entry_title; ?></td>
                        <td><select name="humm_title">
                                <?php foreach ($humm_title_show as $single_title) { ?>
                                <?php if ($single_title['code'] == $humm_title) { ?>
                                <option value="<?php echo $single_title['code']; ?>"
                                        selected="selected"><?php echo $single_title['name']; ?></option>
                                <?php } else { ?>
                                <option value="<?php echo $single_title['code']; ?>"><?php  echo $single_title['name'];; ?></option>
                                <?php } ?>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td><?php echo $entry_status; ?></td>
                        <td><select name="humm_status">
                                <?php if ($humm_status) { ?>
                                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                <option value="0"><?php echo $text_disabled; ?></option>
                                <?php } else { ?>
                                <option value="1"><?php echo $text_enabled; ?></option>
                                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td><span class="required">*</span> <?php echo $entry_merchantNo; ?></td>
                        <td><input type="text" name="humm_merchantNo" value="<?php echo $humm_merchantNo; ?>"/>
                            <?php if ($error_humm) { ?>
                            <span class="error"><?php echo $error_humm; ?></span>
                            <?php } ?></td>
                    </tr>
                    <tr>
                        <td><span class="required">*</span> <?php echo $entry_apikey; ?></td>
                        <td><input type="text" name="humm_apikey" value="<?php echo $humm_apikey; ?>"/>
                            <?php if ($error_password) { ?>
                            <span class="error"><?php echo $error_password; ?></span>
                            <?php } ?></td>
                    </tr>

                    <tr>
                        <td> <?php echo $entry_gateway; ?></td>
                        <td><input type="text" name="humm_gateway_environment" value="<?php echo $humm_gateway_environment; ?>"/>
                            <?php if ($error_password) { ?>
                            <span class="error"><?php echo $error_password; ?></span>
                            <?php } ?></td>
                    </tr>


                    <tr>
                        <td><?php echo $entry_test; ?></td>
                        <td><select name="humm_test">
                                <?php if ($humm_test == 'test') { ?>
                                <option value="test" selected="selected"><?php echo $text_test; ?></option>
                                <?php } else { ?>
                                <option value="test"><?php echo $text_test; ?></option>
                                <?php } ?>
                                <?php if ($humm_test == 'live') { ?>
                                <option value="live" selected="selected"><?php echo $text_live; ?></option>
                                <?php } else { ?>
                                <option value="live"><?php echo $text_live; ?></option>
                                <?php } ?>
                            </select></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_transaction; ?></td>
                        <td><select name="humm_transaction">
                                <?php if ($humm_transaction == 'PAYMENT') { ?>
                                <option value="PAYMENT" selected="selected"><?php echo $text_payment; ?></option>
                                <?php } else { ?>
                                <option value="PAYMENT"><?php echo $text_payment; ?></option>
                                <?php } ?>
                                <?php if ($humm_transaction == 'CAPTURE') { ?>
                                <option value="CAPTURE" selected="selected"><?php echo $text_capture; ?></option>
                                <?php } else { ?>
                                <option value="CAPTURE"><?php echo $text_capture; ?></option>
                                <?php } ?>
                                <?php if ($humm_transaction == 'AUTHENTICATE') { ?>
                                <option value="AUTHENTICATE"
                                        selected="selected"><?php echo $text_authenticate; ?></option>
                                <?php } else { ?>
                                <option value="AUTHENTICATE"><?php echo $text_authenticate; ?></option>
                                <?php } ?>
                            </select></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_total; ?></td>
                        <td><input type="text" name="humm_total" value="<?php echo $humm_total; ?>"/></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_order_status_completed; ?></td>
                        <td><select name="humm_order_status_completed">
                                <?php foreach ($order_statuses as $order_status) { ?>
                                <?php if ($order_status['order_status_id'] == $humm_order_status_completed) { ?>
                                <option value="<?php echo $order_status['order_status_id']; ?>"
                                        selected="selected"><?php echo $order_status['name']; ?></option>
                                <?php } else { ?>
                                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                <?php } ?>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td><?php echo $entry_order_status_pending; ?></td>
                        <td>
                            <select name="humm_order_status_pending">
                                <?php foreach ($order_statuses as $order_status) { ?>
                                <?php if ($order_status['order_status_id'] == $humm_order_status_pending) { ?>
                                <option value="<?php echo $order_status['order_status_id']; ?>"
                                        selected="selected"><?php echo $order_status['name']; ?></option>
                                <?php } else { ?>
                                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                <?php } ?>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_order_status_failed; ?></td>
                        <td><select name="humm_order_status_failed">
                                <?php foreach ($order_statuses as $order_status) { ?>
                                <?php if ($order_status['order_status_id'] == $humm_order_status_failed) { ?>
                                <option value="<?php echo $order_status['order_status_id']; ?>"
                                        selected="selected"><?php echo $order_status['name']; ?></option>
                                <?php } else { ?>
                                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                <?php } ?>
                                <?php } ?>
                            </select></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_geo_zone; ?></td>
                        <td><select name="humm_geo_zone_id">
                                <option value="0"><?php echo $text_all_zones; ?></option>
                                <?php foreach ($geo_zones as $geo_zone) { ?>
                                <?php if ($geo_zone['geo_zone_id'] == $humm_geo_zone_id) { ?>
                                <option value="<?php echo $geo_zone['geo_zone_id']; ?>"
                                        selected="selected"><?php echo $geo_zone['name']; ?></option>
                                <?php } else { ?>
                                <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                                <?php } ?>
                                <?php } ?>
                            </select></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_sort_order; ?></td>
                        <td><input type="text" name="humm_sort_order" value="<?php echo $humm_sort_order; ?>" size="1"/>
                        </td>
                    </tr>
                </table>
            </form>
    </div>
</div>
<?php echo $footer; ?>