<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_M_Category.php';
require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_Helper.php';

class WLSM_Category {
	public static function fetch_category() {
		if ( ! current_user_can( WLSM_ADMIN_CAPABILITY ) ) {
			die();
		}

		global $wpdb;

		$page_url = WLSM_M_Category::get_page_url();

		$query = WLSM_M_Category::fetch_query();

		$query_filter = $query;

		// Grouping.
		$group_by = ' ' . WLSM_M_Category::fetch_query_group_by();

		$query        .= $group_by;
		$query_filter .= $group_by;

		// Searching.
		$condition = '';
		if ( isset( $_POST['search']['value'] ) ) {
			$search_value = sanitize_text_field( $_POST['search']['value'] );
			if ( '' !== $search_value ) {
				$condition .= '' .
				'(c.label LIKE "%' . $search_value . '%")';

				$query_filter .= ( ' HAVING ' . $condition );
			}
		}

		// Ordering.
		$columns = array( 'c.label' );
		if ( isset( $_POST['order'] ) && isset( $columns[ $_POST['order']['0']['column'] ] ) ) {
			$order_by  = sanitize_text_field( $columns[ $_POST['order']['0']['column'] ] );
			$order_dir = sanitize_text_field( $_POST['order']['0']['dir'] );

			$query_filter .= ' ORDER BY ' . $order_by . ' ' . $order_dir;
		} else {
			$query_filter .= ' ORDER BY c.ID DESC';
		}

		// Limiting.
		$limit = '';
		if ( -1 != $_POST['length'] ) {
			$start  = absint( $_POST['start'] );
			$length = absint( $_POST['length'] );

			$limit  = ' LIMIT ' . $start . ', ' . $length;
		}

		// Total query.
		$rows_query = WLSM_M_Category::fetch_query_count();

		// Total rows count.
		$total_rows_count = $wpdb->get_var( $rows_query );

		// Filtered rows count.
		if ( $condition ) {
			$filter_rows_count = $wpdb->get_var( $rows_query . ' WHERE (' . $condition . ')' );
		} else {
			$filter_rows_count = $total_rows_count;
		}

		// Filtered limit rows.
		$filter_rows_limit = $wpdb->get_results( $query_filter . $limit );

		$data = array();
		if ( count( $filter_rows_limit ) ) {
			foreach ( $filter_rows_limit as $row ) {
				// Table columns.
				$data[] = array(
					esc_html( WLSM_M_Category::get_label_text( $row->label ) ),
					'<a class="text-primary" href="' . esc_url( $page_url . "&action=save&id=" . $row->ID ) . '"><span class="dashicons dashicons-edit"></span></a>&nbsp;&nbsp;
					<a class="text-danger wlsm-delete-category" data-nonce="' . esc_attr( wp_create_nonce( 'delete-category-' . $row->ID ) ) . '" data-category="' . esc_attr( $row->ID ) . '" href="#" data-message-title="' . esc_attr__( 'Please Confirm!', 'school-management' ) . '" data-message-content="' . esc_attr__( 'This will delete all data associated with this class.', 'school-management' ) . '" data-cancel="' . esc_attr__( 'Cancel', 'school-management' ) . '" data-submit="' . esc_attr__( 'Confirm', 'school-management' ) . '"><span class="dashicons dashicons-trash"></span></a>'
				);
			}
		}

		$output = array(
			'draw'            => intval( $_POST['draw'] ),
			'recordsTotal'    => $total_rows_count,
			'recordsFiltered' => $filter_rows_count,
			'data'            => $data,
		);

		echo json_encode( $output );
		die;
	}

	public static function save() {
		if ( ! current_user_can( WLSM_ADMIN_CAPABILITY ) ) {
			die();
		}
		WLSM_Helper::check_demo();
		try {
			ob_start();
			global $wpdb;

			$category_id = isset( $_POST['category_id'] ) ? absint( $_POST['category_id'] ) : 0;

			if ( $category_id ) {
				if ( ! wp_verify_nonce( $_POST[ 'edit-category-' . $category_id ], 'edit-category-' . $category_id ) ) {
					die();
				}
			} else {
				if ( ! wp_verify_nonce( $_POST['add-category'], 'add-category' ) ) {
					die();
				}
			}

			// Checks if class exists.
			if ( $category_id ) {
				$category = WLSM_M_Category::get_category( $category_id );

				if ( ! $category ) {
					throw new Exception( esc_html__( 'Category not found.', 'school-management' ) );
				}
			}

			$label = isset( $_POST['label'] ) ? sanitize_text_field( $_POST['label'] ) : '';

			// Start validation.
			$errors = array();

			if ( empty( $label ) ) {
				$errors['label'] = esc_html__( 'Please provide class name.', 'school-management' );
			}

			if ( strlen( $label ) > 191 ) {
				$errors['label'] = esc_html__( 'Maximum length cannot exceed 191 characters.', 'school-management' );
			}

			// Checks if class already exists with this label.
			if ( $category_id ) {
				$category_exist = $wpdb->get_var( $wpdb->prepare( 'SELECT COUNT(*) as count FROM ' . WLSM_CATEGORY . ' as c WHERE c.label = %s AND c.ID != %d', $label, $category_id ) );
			} else {
				$category_exist = $wpdb->get_var( $wpdb->prepare( 'SELECT COUNT(*) as count FROM ' . WLSM_CATEGORY . ' as c WHERE c.label = %s', $label ) );
			}

			if ( $category_exist ) {
				$errors['label'] = esc_html__( 'Category already exists.', 'school-management' );
			}
			// End validation.

		} catch ( Exception $exception ) {
			$buffer = ob_get_clean();
			if ( ! empty( $buffer ) ) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error( $response );
		}

		if ( count( $errors ) < 1 ) {
			try {
				$wpdb->query( 'BEGIN;' );

				// Data to update or insert.
				$data = array(
					'label' => $label
				);

				// Checks if update or insert.
				if ( $category_id ) {
					$data['updated_at'] = current_time( 'Y-m-d H:i:s' );

					$success = $wpdb->update( WLSM_CATEGORY, $data, array( 'ID' => $category_id ) );
					$message = esc_html__( 'Category updated successfully.', 'school-management' );
					$reset   = false;
				} else {
					$data['created_at'] = current_time( 'Y-m-d H:i:s' );

					$success = $wpdb->insert( WLSM_CATEGORY, $data );
					$message = esc_html__( 'Category added successfully.', 'school-management' );
					$reset   = true;
				}

				$buffer = ob_get_clean();
				if ( ! empty( $buffer ) ) {
					throw new Exception( $buffer );
				}

				if ( false === $success ) {
					throw new Exception( $wpdb->last_error );
				}

				$wpdb->query( 'COMMIT;' );

				wp_send_json_success( array( 'message' => $message, 'reset' => $reset ) );
			} catch ( Exception $exception ) {
				$wpdb->query( 'ROLLBACK;' );
				wp_send_json_error( $exception->getMessage() );
			}
		}
		wp_send_json_error( $errors );
	}

	public static function delete() {
		if ( ! current_user_can( WLSM_ADMIN_CAPABILITY ) ) {
			die();
		}

		WLSM_Helper::check_demo();

		try {
			ob_start();
			global $wpdb;

			$category_id = isset( $_POST['category_id'] ) ? absint( $_POST['category_id'] ) : 0;

			if ( ! wp_verify_nonce( $_POST[ 'delete-category-' . $category_id ], 'delete-category-' . $category_id ) ) {
				die();
			}

			// Checks if class exists.
			$category = WLSM_M_Category::get_category( $category_id );

			if ( ! $category ) {
				throw new Exception( esc_html__( 'Category not found.', 'school-management' ) );
			}

		} catch ( Exception $exception ) {
			$buffer = ob_get_clean();
			if ( ! empty( $buffer ) ) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error( $response );
		}

		try {
			$wpdb->query( 'BEGIN;' );

			$success = $wpdb->delete( WLSM_CATEGORY, array( 'ID' => $category_id ) );
			$message = esc_html__( 'Category deleted successfully.', 'school-management' );

			$exception = ob_get_clean();
			if ( ! empty( $exception ) ) {
				throw new Exception( $exception );
			}

			if ( false === $success ) {
				throw new Exception( $wpdb->last_error );
			}

			$wpdb->query( 'COMMIT;' );

			wp_send_json_success( array( 'message' => $message ) );
		} catch ( Exception $exception ) {
			$wpdb->query( 'ROLLBACK;' );
			wp_send_json_error( $exception->getMessage() );
		}
	}
}
