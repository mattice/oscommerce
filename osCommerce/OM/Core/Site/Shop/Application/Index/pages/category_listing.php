<?php
/*
  osCommerce Online Merchant $osCommerce-SIG$
  Copyright (c) 2010 osCommerce (http://www.oscommerce.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  use osCommerce\OM\Core\OSCOM;
?>

<?php echo osc_image(DIR_WS_IMAGES . $OSCOM_Template->getPageImage(), $OSCOM_Template->getPageTitle(), HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, 'id="pageIcon"'); ?>

<h1><?php echo $OSCOM_Template->getPageTitle(); ?></h1>

<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr>

<?php
    if ( sizeof($OSCOM_Category->getPathArray()) > 1 ) {
// check to see if there are deeper categories within the current category
      $category_links = array_reverse($OSCOM_Category->getPathArray());

      for( $i=0, $n=sizeof($category_links); $i<$n; $i++ ) {
        $Qcategories = $OSCOM_Database->query('select count(*) as total from :table_categories c, :table_categories_description cd where c.parent_id = :parent_id and c.categories_id = cd.categories_id and cd.language_id = :language_id');
        $Qcategories->bindInt(':parent_id', $category_links[$i]);
        $Qcategories->bindInt(':language_id', $OSCOM_Language->getID());
        $Qcategories->execute();

        if ( $Qcategories->valueInt('total') < 1 ) {
          // do nothing, go through the loop
        } else {
          $Qcategories = $OSCOM_Database->query('select c.categories_id, cd.categories_name, c.categories_image, c.parent_id from :table_categories c, :table_categories_description cd where c.parent_id = :parent_id and c.categories_id = cd.categories_id and cd.language_id = :language_id order by sort_order, cd.categories_name');
          $Qcategories->bindInt(':parent_id', $category_links[$i]);
          $Qcategories->bindInt(':language_id', $OSCOM_Language->getID());
          $Qcategories->execute();

          break; // we've found the deepest category the customer is in
        }
      }
    } else {
      $Qcategories = $OSCOM_Database->query('select c.categories_id, cd.categories_name, c.categories_image, c.parent_id from :table_categories c, :table_categories_description cd where c.parent_id = :parent_id and c.categories_id = cd.categories_id and cd.language_id = :language_id order by sort_order, cd.categories_name');
      $Qcategories->bindInt(':parent_id', $OSCOM_Category->getID());
      $Qcategories->bindInt(':language_id', $OSCOM_Language->getID());
      $Qcategories->execute();
    }

    $number_of_categories = $Qcategories->numberOfRows();

    $rows = 0;

    while ( $Qcategories->next() ) {
      $rows++;

      $width = (int)(100 / MAX_DISPLAY_CATEGORIES_PER_ROW) . '%';

      echo '    <td align="center" class="smallText" width="' . $width . '" valign="top">' . osc_link_object(OSCOM::getLink(null, 'Index', 'cPath=' . $OSCOM_CategoryTree->buildBreadcrumb($Qcategories->valueInt('categories_id'))), osc_image(DIR_WS_IMAGES . 'categories/' . $Qcategories->value('categories_image'), $Qcategories->value('categories_name')) . '<br />' . $Qcategories->value('categories_name')) . '</td>' . "\n";

      if ( (($rows / MAX_DISPLAY_CATEGORIES_PER_ROW) == floor($rows / MAX_DISPLAY_CATEGORIES_PER_ROW)) && ($rows != $number_of_categories) ) {
        echo '  </tr>' . "\n" .
             '  <tr>' . "\n";
      }
    }
?>

  </tr>
</table>
