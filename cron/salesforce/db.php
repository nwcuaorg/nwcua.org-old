<?php


ini_set( 'memory_limit', '16M' );


// database object
class db {
	public $cn='';
	public $result='';
	public $show_errors=true;

	function db() {
		$this->cn=mysqli_connect( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
	}

	function query( $query ) {
		$select=$this->cn->query( $query );
		if ( !empty( $select ) ) {
			while ( $rowselect=$select->fetch_object() ) {
				$results[]=$rowselect;
			}
		}
		if ( !empty( $results ) ) {
			return $results;
		} else {
			$this->handle_error();
			return false;
		}
	}

	function query_one( $query ) {
		$select=$this->cn->query( $query );
		if ( !empty( $select ) ) {
			while ( $rowselect=$select->fetch_object() ) {
				$results[]=$rowselect;
			}
		}
		if ( !empty( $results ) ) {
			return $results[0];
		} else {
			$this->handle_error();
			return false;
		}
	}

	function update( $query ) {
		$update=$this->cn->query( $query );
		if ( $update ) {
			return true;
		} else {
			$this->handle_error();
			return false;
		}
	}

	function insert( $query ) {
		$update=$this->cn->query( $query );
		if ( $update ) {
			return $this->cn->insert_id;
		} else {
			$this->handle_error();
			return false;
		}
	}

	function handle_error() {
		if ( !empty( $this->cn->error ) && $this->show_errors ) {
			print $this->cn->error;
			die;
		}
	}

}


// instantiate
$db = new db;


