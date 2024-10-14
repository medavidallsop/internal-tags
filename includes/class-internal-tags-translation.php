<?php

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

if ( !class_exists( 'Internal_Tags_Translation' ) ) {

	class Internal_Tags_Translation {

		public function __construct() {

			add_action( 'init', array( $this, 'textdomain' ) );

		}

		public function textdomain() {

			// Loads the textdomain

			load_plugin_textdomain( 'internal-tags', false, dirname( plugin_basename( __DIR__ ) ) . '/languages' );

		}

	}

}
