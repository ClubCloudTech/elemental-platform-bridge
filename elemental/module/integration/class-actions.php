<?php
/**
 * NMP Integration Package
 * Managing helper functions for Integration
 *
 * @package ElementalPlugin\Module\Integration
 */

namespace ElementalPlugin\Module\Integration;

use ElementalPlugin\Module\Integration\Coadjute\Requests\CreateUser;
use ElementalPlugin\Module\Integration\Coadjute\Requests\CreateCompany;
use ElementalPlugin\Module\Integration\Coadjute\Requests\CreateEmployee;

/**
 * Class Actions - NMP Engine Sync actions
 */
class Actions {
	/**
	 * Synchronize created employee with NMP Sync Engine.
	 *
	 * @param string $first_name - User First Name.
	 * @param string $last_name  - User Last Name.
	 * @param string $email      - User Email.
	 *
	 * @return array
	 */
	public function sync_employee($first_name, $last_name, $email, $password): array {
		try {
            $request = new CreateEmployee();
            $request->setData([
            	'sandbox' => true,
            	'first_name' => $first_name,
            	'last_name' => $last_name,
            	'email' => $email,
            	'password' => $password
            ]);
            $employee_response = $request->send();
        } catch (\Exception $e) {}

        $result = array('status' => true);

        if ($employee_response->failed()) {
            $result['status'] = false;
            $result['error'] = 'User could not be created. Error: ' . $employee_response->body();
        }
        
        return $result;
	}

	/**
	 * Synchronize created employee with NMP Sync Engine.
	 *
	 * @param string $first_name - User First Name.
	 * @param string $last_name  - User Last Name.
	 * @param string $email      - User Email.
	 *
	 * @return array
	 */
	public function sync_company($first_name, $last_name, $email): array {
		try {
            $request = new CreateCompany();
            $request->setData([
            	'sandbox' => true,
            	'first_name' => $first_name,
            	'last_name' => $last_name,
            	'email' => $email,
            ]);
            $company_response = $request->send();
        } catch (\Exception $e) {}

        $result = array('status' => true);

        if ($company_response->failed()) {
            $result['status'] = false;
            $result['error'] = 'Company could not be created. Error: ' . $company_response->body();
        }

        return $result;
	}

	/**
	 * Synchronize created employee with NMP Sync Engine.
	 *
	 * @param string $first_name - User First Name.
	 * @param string $last_name  - User Last Name.
	 * @param string $email      - User Email.
	 * @param string $password	 - User Password.
	 *
	 * @return array
	 */
	public function sync_user($first_name, $last_name, $email, $password): array {
		try {
            $request = new CreateUser();
            $request->setData([
            	'first_name' => $first_name,
            	'last_name' => $last_name,
            	'email' => $email,
            	'password' => $password
            ]);
            $user_response = $request->send();
        } catch (\Exception $e) {}

        $result = array('status' => true);

        if ($user_response->failed()) {
            $result['status'] = false;
            $result['error'] = 'Account could not be created. Error: ' . $user_response->body();
        }
        
        return $result;
	}
}