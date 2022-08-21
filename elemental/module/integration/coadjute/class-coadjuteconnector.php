<?php

namespace ElementalPlugin\Module\Integration\Coadjute;

use ElementalPlugin\Module\Integration\Integration;

use ElementalPlugin\Library\Factory;
use ElementalPlugin\DAO\TokenDAO;
use ElementalPlugin\Module\Membership\DAO\MemberSyncDAO;

use Sammyjo20\Saloon\Http\Auth\TokenAuthenticator;
use Sammyjo20\Saloon\Interfaces\AuthenticatorInterface;
use Sammyjo20\Saloon\Http\SaloonConnector;
use Sammyjo20\Saloon\Traits\Plugins\AcceptsJson;

class CoadjuteConnector extends SaloonConnector
{
    use AcceptsJson;

    /**
     * The Base URL of the API.
     *
     * @return string
     */
    public function defineBaseUrl(): string
    {
        return get_option( Integration::SETTING_INTEGRATION_API_BASEURL );
    }

    /**
     * The config options that will be applied to every request.
     *
     * @return string[]
     */
    public function defaultConfig(): array
    {
        return [
            'timeout' => 30,
        ];
    }

    public function defaultAuth(): ?AuthenticatorInterface
    {
        $user_id        = \get_current_user_id();
        $token_object   = Factory::get_instance( TokenDAO::class )->get_by_id( $user_id );
        
        return new TokenAuthenticator( $token_object->get_user_token() );
    }
}
