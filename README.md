# humm-opencart-2.03

BPI

home page
upload/catalog/view/theme/default/template/common/home.tpl

 <div class="mb-1">
      <script
              src="https://widgets.shophumm.co.nz/content/scripts/more-info-small.js"></script
      >
 </div>
 
 
Cart page
upload/catalog/view/theme/default/template/checkout/cart.tpl

<div class="col-sm-4 col-sm-offset-8">
        <script src="https://widgets.shophumm.com.au/content/scripts/price-info.js?productPrice=><?php echo $total['text']; ?>"></script>
 </div>
 
 
Product Page 
 
upload/catalog/view/theme/default/template/product/product.tpl
 
 
 <?php if (!$special) { ?>
             <li>
               <h2><?php echo $price; ?></h2>
               <script src="https://widgets.shophumm.co.nz/content/scripts/price-info.js?productPrice=<?php echo $price ?>&little=f5"></script>
               <script src="https://widgets.shophumm.co.nz/content/scripts/price-info.js?productPrice=<?php echo $price ?>&little=w10"></script>
 </li>
   
   
   
admin console


change upload/admin/view/template/common/headerHumm.tpl to upload/admin/view/template/common/header.tpl




   
   