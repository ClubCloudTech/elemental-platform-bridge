<?php
/**
 * GetEmployees Coadjute Request.
 *
 * @package module/integration/coadjute/requests/class-getemployees.php
 */

namespace ElementalPlugin\Module\Integration\Coadjute\Requests;

use ElementalPlugin\Module\Integration\Coadjute\CoadjuteConnector;

use Sammyjo20\Saloon\Constants\Saloon;
use Sammyjo20\Saloon\Http\SaloonRequest;

class GetEmployees extends SaloonRequest {

	/**
	 * The connector class.
	 *
	 * @var string|null
	 */
	protected ?string $connector = CoadjuteConnector::class;

	/**
	 * The HTTP verb the request will use.
	 *
	 * @var string|null
	 */
	protected ?string $method = Saloon::GET;

	/**
	 * The endpoint of the request.
	 *
	 * @return string
	 */
	public function defineEndpoint(): string {
		return '/employees';
	}
}
