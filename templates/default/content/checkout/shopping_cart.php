<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2006 osCommerce

  Released under the GNU General Public License
*/
?>

<?php echo osc_image(DIR_WS_IMAGES . 'table_background_cart.gif', $osC_Template->getPageTitle(), null, null, 'id="pageIcon"'); ?>

<h1><?php echo $osC_Template->getPageTitle(); ?></h1>

<?php
  if ($osC_ShoppingCart->hasContents()) {
?>

<form name="shopping_cart" action="<?php echo osc_href_link(FILENAME_CHECKOUT, 'action=update_product', 'SSL'); ?>" method="post">

<div class="moduleBox">
  <h6><?php echo $osC_Language->get('shopping_cart_heading'); ?></h6>

  <div class="content">
    <table border="0" width="100%" cellspacing="0" cellpadding="2">

<?php
    $_cart_date_added = null;

    foreach ($osC_ShoppingCart->getProducts() as $products) {
      if ($products['date_added'] != $_cart_date_added) {
        $_cart_date_added = $products['date_added'];
?>

      <tr>
        <td colspan="4"><?php echo sprintf($osC_Language->get('date_added_to_shopping_cart'), $products['date_added']); ?></td>
      </tr>

<?php
      }
?>

      <tr>
        <td valign="top" width="60"><?php echo osc_link_object(osc_href_link(FILENAME_CHECKOUT, 'action=cartRemove&products_id=' . $products['id'], 'SSL'), osc_draw_image_button('small_delete.gif', $osC_Language->get('button_delete'))); ?></td>
        <td valign="top">

<?php
      echo osc_link_object(osc_href_link(FILENAME_PRODUCTS, $products['keyword']), '<b>' . $products['name'] . '</b>');

      if ( (STOCK_CHECK == '1') && ($osC_ShoppingCart->isInStock($products['id']) === false) ) {
        echo '<span class="markProductOutOfStock">' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . '</span>';
      }

      echo '&nbsp;(Top Category)';

      if ($osC_ShoppingCart->hasAttributes($products['id'])) {
        foreach ($osC_ShoppingCart->getAttributes($products['id']) as $attributes) {
          echo osc_draw_hidden_field('id[' . $products['id'] . '][' . $attributes['options_id'] . ']', $attributes['options_values_id']);

          echo '<br />- ' . $attributes['products_options_name'] . ': ' . $attributes['products_options_values_name'];
        }
      }
?>

        </td>
        <td valign="top"><?php echo osc_draw_input_field('cart_quantity[]', $products['quantity'], 'size="4"') . osc_draw_hidden_field('products_id[]', $products['id']); ?></td>
        <td valign="top" align="right"><?php echo '<b>' . $osC_Currencies->displayPrice($products['final_price'], $products['tax_class_id'], $products['quantity']) . '</b>'; ?></td>
      </tr>

<?php
    }
?>

    </table>
  </div>

  <table border="0" width="100%" cellspacing="0" cellpadding="2">

<?php
// HPDL
//    if ($osC_OrderTotal->hasActive()) {
//      foreach ($osC_OrderTotal->getResult() as $module) {
      foreach ($osC_ShoppingCart->getOrderTotals() as $module) {
        echo '    <tr>' . "\n" .
             '      <td align="right">' . $module['title'] . '</td>' . "\n" .
             '      <td align="right">' . $module['text'] . '</td>' . "\n" .
             '    </tr>';
      }
//    }
?>

  </table>

<?php
    if ( (STOCK_CHECK == '1') && ($osC_ShoppingCart->hasStock() === false) ) {
      if (STOCK_ALLOW_CHECKOUT == '1') {
        echo '<p class="stockWarning" align="center">' . sprintf($osC_Language->get('products_out_of_stock_checkout_possible'), STOCK_MARK_PRODUCT_OUT_OF_STOCK) . '</p>';
      } else {
        echo '<p class="stockWarning" align="center">' . sprintf($osC_Language->get('products_out_of_stock_checkout_not_possible'), STOCK_MARK_PRODUCT_OUT_OF_STOCK) . '</p>';
      }
    }
?>

</div>

<div class="submitFormButtons">
  <span style="float: right;"><?php echo osc_link_object(osc_href_link(FILENAME_CHECKOUT, 'shipping', 'SSL'), osc_draw_image_button('button_checkout.gif', $osC_Language->get('button_checkout'))); ?></span>

  <?php echo osc_draw_image_submit_button('button_update_cart.gif', $osC_Language->get('button_update_cart')); ?>
</div>

</form>

<?php
  } else {
?>

<p><?php echo $osC_Language->get('shopping_cart_empty'); ?></p>

<div class="submitFormButtons" style="text-align: right;">
  <?php echo osc_link_object(osc_href_link(FILENAME_DEFAULT), osc_draw_image_button('button_continue.gif', $osC_Language->get('button_continue'))); ?>
</div>

<?php
  }
?>