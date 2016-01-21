<?php

function dwqa_add_notice( $message, $type = 'success' ) {
	if ( ! did_action( 'init' ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'This function should not be called before init.', 'dwqa' ), '1.0.0' );
		return;
	}

	global $dwqa;

	$notices = $dwqa->session->get( 'dwqa-notices', array() );

	$notices[ $type ][] = $message;

	$dwqa->session->set( 'dwqa-notices', $notices );
}

function dwqa_clear_notices() {
	if ( ! did_action( 'init' ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'This function should not be called before init.', 'dwqa' ), '1.0.0' );
		return;
	}

	global $dwqa;
	$dwqa->session->set( 'dwqa-notices', null );
}

add_action( 'dwqa_before_question_submit_form', 'dwqa_print_notices' );
function dwqa_print_notices() {
	if ( ! did_action( 'init' ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'This function should not be called before init.', 'dwqa' ), '1.0.0' );
		return;
	}

	global $dwqa;

	$notices = $dwqa->session->get( 'dwqa-notices', array() );
	$types = array( 'error', 'success', 'info' );

	foreach( $types as $type ) {
		if ( dwqa_count_notices( $type ) > 0 ) {
			foreach( $notices[ $type ] as $message ) {
				printf( '<p class="alert alert-%s">%s</p>', $type, $message );
			}
		}
	}

	dwqa_clear_notices();
}

function dwqa_count_notices( $type = '' ) {
	if ( ! did_action( 'init' ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'This function should not be called before init.', 'dwqa' ), '1.0.0' );
		return;
	}

	global $dwqa;
	$all_notices = $dwqa->session->get( 'dwqa-notices', array() );
	$count = 0;
	if ( isset( $all_notices[ $type ] ) ) {
		$count = absint( sizeof( $all_notices[ $type ] ) );
	} elseif ( empty( $type ) ) {
		foreach( $all_notices as $notices ) {
			$count += absint( sizeof( $notices ) );
		}
	}

	return $count;
}

class DWQA_Session {
	protected $_data = array();
	protected $_dirty = false;

	public function __get( $key ) {
		return $this->get( $key );
	}

	public function __set( $key, $value ) {
		$this->set( $key, $value );
	}

	public function __isset( $key ) {
		return isset( $this->_data[ sanitize_title( $key ) ] );
	}

	public function __unset( $key ) {
		if ( isset( $this->_data[ $key ] ) ) {
			unset( $this->_data[ $key ] );
			$this->_dirty = true;
		}
	}

	public function get( $key, $default = '' ) {
		$key = sanitize_key( $key );
		return isset( $this->_data[ $key ] ) ? maybe_unserialize( $this->_data[ $key ] ) : $default;
	}

	public function set( $key, $value ) {
		if ( $value !== $this->get( $key ) ) {
			$this->_data[ sanitize_key( $key ) ] = maybe_serialize( $value );
			$this->_dirty = true;
		}
	}
}