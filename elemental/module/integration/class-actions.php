<?php
/**
 * NMP Integration Package
 * Managing helper functions for Integration
 *
 * @package ElementalPlugin\Module\Integration
 */

namespace ElementalPlugin\Module\Integration;

use ElementalPlugin\Module\Integration\Coadjute\Requests\CreateEmployee;
use ElementalPlugin\Module\Integration\Coadjute\Requests\GetEmployees;

/**
 * Class Actions - NMP Engine Sync actions
 */
class Actions {
	/**
	 * Verify if employee with email exists on NMP Sync Engine.
	 *
	 * @param string $email - User Email.
	 *
	 * @return boolean
	 */
	public function employee_exists( $email ): array {
		try {
			$request = new GetEmployees();
			$request->setQuery(
				array(
					'environment' => 'sandbox',
					'email'       => $email,
				)
			);
			$employee_response = $request->send();
		} catch ( \Exception $e ) {
			$result['status'] = false;
			$result['error']  = 'Employee can not be verified. Error: ' . $e->getMessage();
		}

		if ( $employee_response->failed() ) {
			$result['status'] = false;
			$result['error']  = 'Employee can not be verified. Error: ' . $employee_response->body();
		}

		return array( 'status' => $employee_response->json()['success'] );
	}

	/**
	 * Synchronize created employee with NMP Sync Engine.
	 *
	 * @param string $user_id       - User Id.
	 * @param string $first_name    - User First Name.
	 * @param string $last_name     - User Last Name.
	 * @param string $email         - User Email.
	 *
	 * @return array
	 */
	public function sync_employee( $user_id, $first_name, $last_name, $email, $password ): array {
		$result = array( 'status' => true );
		try {
			$request = new CreateEmployee();
			$request->setData(
				array(
					'environment' => 'sandbox',
					'user_id'     => $user_id,
					'first_name'  => $first_name,
					'last_name'   => $last_name,
					'email'       => $email,
					'password'    => $password,
				)
			);
			$employee_response = $request->send();
		} catch ( \Exception $e ) {
			$result['status'] = false;
			$result['error']  = 'User could not be created. Error: ' . $e->getMessage();
		}

		if ( $employee_response->failed() ) {
			$result['status'] = false;
			$result['error']  = 'User could not be created. Error: ' . $employee_response->body();
		}

		$result['data'] = $employee_response->json();

		return $result;
	}
}
