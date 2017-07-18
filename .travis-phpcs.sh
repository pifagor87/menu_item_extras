#!/bin/bash

set -e $DRUPAL_TI_DEBUG

# Ensure the right Drupal version is installed.
# Note: This function is re-entrant.
drupal_ti_ensure_drupal

phpcs --report=full --runtime-set ignore_warnings_on_exit true --colors --standard=Drupal "$DRUPAL_TI_DRUPAL_DIR/modules/$DRUPAL_TI_MODULE_NAME" || true
