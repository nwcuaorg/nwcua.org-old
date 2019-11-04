<?php



// database object
class db {
	public $cn='';
	public $result='';
	public $show_errors=true;

	function db() {
		$this->cn = mysqli_connect( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
	}

	function query( $query ) {
		$select = mysqli_query( $this->cn, $query );
		if ( !empty( $select ) ) {
			while ( $rowselect = mysqli_fetch_object( $select ) ) {
				$results[] = $rowselect;
			}
		}
		mysqli_free_result( $select );
		if ( !empty( $results ) ) {
			return $results;
		} else {
			$this->handle_error();
			return false;
		}
	}

	function query_one( $query ) {
		$select = mysqli_query( $this->cn, $query );
		if ( !empty( $select ) ) {
			while ( $rowselect = mysqli_fetch_object( $select ) ) {
				$results[]=$rowselect;
			}
		}
		mysqli_free_result( $select );
		if ( !empty( $results ) ) {
			return $results[0];
		} else {
			$this->handle_error();
			return false;
		}
	}

	function update( $query ) {
		$update = mysqli_query( $this->cn, $query );
		if ( $update ) {
			return true;
		} else {
			$this->handle_error();
			return false;
		}
	}

	function insert( $query ) {
		$update = mysqli_query( $this->cn, $query );
		if ( $update ) {
			return $this->cn->insert_id;
		} else {
			$this->handle_error();
			return false;
		}
	}

	function close() {
		mysqli_close( $this->cn );
	}

	function handle_error() {
		if ( !empty( $this->cn->error ) && $this->show_errors ) {
			print $this->cn->error;
			die;
		}
	}

}


