<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form-humm" data-toggle="tooltip" title="<?php echo $button_save; ?>"
                        class="btn btn-primary"><i class="fa fa-save"></i></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default">
                    <i class="fa fa-reply"></i>
                </a>
            </div>
            <h1><?php echo $heading_title; ?></h1>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="container-fluid">
        <?php if ($error_humm_warning) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_humm_warning; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
            </div>
            <div class="panel-body">

                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-humm" class="form-horizontal">
                    <fieldset>
                        <legend><?php echo $text_heading_display; ?></legend>
                        <p><?php echo $text_description_display; ?></p>

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-title">
                                <span data-toggle="tooltip" title="<?php echo $help_title; ?>">
                                    <?php echo $entry_title; ?>
                                </span>
                            </label>
                            <div class="col-sm-10">
                                <select name="humm_title" id="humm-title" class="form-control">
                                    <option value=""><?php echo $text_select; ?></option>
                                    <option value="Oxipay" <?php echo $humm_title == 'Oxipay' ? ' selected' : ''; ?>>Oxipay</option>
                                    <option value="Humm" <?php echo $humm_title =='Humm' ? ' selected' : ''; ?>>Humm</option>
                                    
                                </select>
                                <?php if ($error_humm_region) { ?>
                                <div class="text-danger"><?php echo $error_humm_region; ?></div>
                                <?php } ?>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset>
                        <legend><?php echo $text_heading_shop; ?></legend>
                        <p><?php echo $text_description_shop; ?></p>

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-shop-name">
                                <span data-toggle="tooltip" title="<?php echo $help_shop_name; ?>">
                                    <?php echo $entry_shop_name; ?>
                                </span>
                            </label>
                            <div class="col-sm-10">
                                <input type="text" name="humm_shop_name" value="<?php echo $humm_shop_name; ?>"
                                       placeholder="<?php echo $entry_shop_name; ?>" id="input-shop-name"
                                       class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-region">
                                <?php echo $entry_region; ?>
                            </label>
                            <div class="col-sm-10">
                                <select name="humm_region" id="input-region" class="form-control">
                                    <option value=""><?php echo $text_select; ?></option>
                                    <?php foreach ($regions as $region) { ?>
                                    <option value="<?php echo $region['code']; ?>" <?php echo $region['code'] == $humm_region ? ' selected' : ''; ?>>
                                        <?php echo $region['name']; ?>
                                    </option>
                                    <?php } ?>
                                </select>
                                <?php if ($error_humm_region) { ?>
                                <div class="text-danger"><?php echo $error_humm_region; ?></div>
                                <?php } ?>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset>
                        <legend><?php echo $text_heading_gateway; ?></legend>
                        <p><?php echo $text_description_gateway; ?></p>

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-gateway-environment">
                                <?php echo $entry_gateway_environment; ?>
                            </label>
                            <div class="col-sm-10">
                                <select name="humm_gateway_environment" id="input-gateway-environment"
                                        class="form-control">
                                    <?php foreach ($gateway_environments as $gateway_environment) { ?>
                                    <option value="<?php echo $gateway_environment['code']; ?>"
                                    <?php echo $gateway_environment['code'] == $humm_gateway_environment ? ' selected' : ''; ?>
                                    ><?php echo $gateway_environment['name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group<?php echo $humm_gateway_environment != 'other' ? ' hidden' : '' ?>">
                            <label class="col-sm-2 control-label" for="input-gateway-url">
                                <span data-toggle="tooltip" title="<?php echo $help_gateway_url; ?>">
                                    <?php echo $entry_gateway_url; ?>
                                </span>
                            </label>
                            <div class="col-sm-10">
                                <input type="text" name="humm_gateway_url" value="<?php echo $humm_gateway_url; ?>"
                                       placeholder="<?php echo $entry_gateway_url; ?>" id="input-gateway-url"
                                       class="form-control"/>
                                <?php if ($error_humm_gateway_url) { ?>
                                <div class="text-danger"><?php echo $error_humm_gateway_url; ?></div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-merchant-id">
                                <span data-toggle="tooltip" title="<?php echo $help_merchant_id; ?>">
                                    <?php echo $entry_merchant_id; ?>
                                </span>
                            </label>
                            <div class="col-sm-10">
                                <input type="text" name="humm_merchant_id" value="<?php echo $humm_merchant_id; ?>"
                                       placeholder="<?php echo $entry_merchant_id; ?>" id="input-merchant-id"
                                       class="form-control"/>
                                <?php if ($error_humm_merchant_id) { ?>
                                <div class="text-danger"><?php echo $error_humm_merchant_id; ?></div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-api-key">
                                <span data-toggle="tooltip" title="<?php echo $help_api_key; ?>">
                                    <?php echo $entry_api_key; ?>
                                </span>
                            </label>
                            <div class="col-sm-10">
                                <input type="text" name="humm_api_key" value="<?php echo $humm_api_key; ?>"
                                       placeholder="<?php echo $entry_api_key; ?>" id="input-api-key"
                                       class="form-control"/>
                                <?php if ($error_humm_api_key) { ?>
                                <div class="text-danger"><?php echo $error_humm_api_key; ?></div>
                                <?php } ?>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset>
                        <legend><?php echo $text_heading_general; ?></legend>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-status">
                                <?php echo $entry_status; ?>
                            </label>
                            <div class="col-sm-10">
                                <select name="humm_status" id="input-status" class="form-control">
                                    <option value="1"
                                    <?php echo $humm_status ? ' selected' : ''; ?>><?php echo $text_enabled; ?></option>
                                    <option value="0"
                                    <?php echo !$humm_status ? ' selected' : ''; ?>
                                    ><?php echo $text_disabled; ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-order-status-completed">
                                <?php echo $entry_order_status_completed; ?>
                            </label>
                            <div class="col-sm-10">
                                <select name="humm_order_status_completed_id" id="input-order-status-completed"
                                        class="form-control">
                                    <?php foreach ($order_statuses as $order_status) { ?>
                                    <option value="<?php echo $order_status['order_status_id']; ?>"
                                    <?php echo $order_status['order_status_id'] == $humm_order_status_completed_id ? ' selected' : ''; ?>
                                    ><?php echo $order_status['name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-order-status-pending">
                                <?php echo $entry_order_status_pending; ?>
                            </label>
                            <div class="col-sm-10">
                                <select name="humm_order_status_pending_id" id="input-order-status-pending"
                                        class="form-control">
                                    <?php foreach ($order_statuses as $order_status) { ?>
                                    <option value="<?php echo $order_status['order_status_id']; ?>"
                                    <?php echo $order_status['order_status_id'] == $humm_order_status_pending_id ? ' selected' : ''; ?>
                                    ><?php echo $order_status['name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-order-status-failed">
                                <?php echo $entry_order_status_failed; ?>
                            </label>
                            <div class="col-sm-10">
                                <select name="humm_order_status_failed_id" id="input-order-status-failed"
                                        class="form-control">
                                    <?php foreach ($order_statuses as $order_status) { ?>
                                    <option value="<?php echo $order_status['order_status_id']; ?>"
                                    <?php echo $order_status['order_status_id'] == $humm_order_status_failed_id ? ' selected' : ''; ?>
                                    ><?php echo $order_status['name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-geo-zone">
                                <?php echo $entry_geo_zone; ?>
                            </label>
                            <div class="col-sm-10">
                                <select name="humm_geo_zone_id" id="input-geo-zone" class="form-control">
                                    <option value="0"><?php echo $text_all_zones; ?></option>
                                    <?php foreach ($geo_zones as $geo_zone) { ?>
                                    <option value="<?php echo $geo_zone['geo_zone_id']; ?>"
                                    <?php echo $geo_zone['geo_zone_id'] == $humm_geo_zone_id ? ' selected' : ''; ?>
                                    ><?php echo $geo_zone['name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-sort-order">
                                <?php echo $entry_sort_order; ?>
                            </label>
                            <div class="col-sm-10">
                                <input type="text" name="humm_sort_order" value="<?php echo $humm_sort_order; ?>"
                                       placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order"
                                       class="form-control"/>
                            </div>
                        </div>
                    </fieldset>
                </form>

            </div>
        </div>
    </div>
</div>
<script>
    (function ($) {
        $('#input-gateway-environment').on('change', function (e) {
            var isOther = $(this).val() == 'other',
                urlEl = $('#input-gateway-url').closest('.form-group');

            urlEl[isOther ? 'removeClass' : 'addClass']('hidden');
        }).trigger('change');
    })(jQuery);
</script>
<?php echo $footer; ?>