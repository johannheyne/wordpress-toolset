<?php

	class Tool_Dashboard_Widget {

		public $p = false;

		public function __construct( $p ) {

			$defaults = array(
				'id' => false,
				'title' => false,
				'content' => false,
				'controls' => null,
				'args' => null,
			);

			$this->p = array_replace_recursive( $defaults, $p );

			$this->add_action();
		}

		public function add_action() {

			add_action('wp_dashboard_setup', array( $this, 'add_dashboard_widget' ) );
		}

		public function add_dashboard_widget() {

			if ( $this->p['id'] && $this->p['title'] && $this->p['content'] ) {

				wp_add_dashboard_widget( $this->p['id'], $this->p['title'], $this->p['content'], $this->p['controls'], $this->p['args'] );
			}
		}
	}
