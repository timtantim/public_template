<?php

namespace App\Passport;

use Laravel\Passport\Bridge\ClientRepository as BaseClientRepository;

class PassportClientRepository extends BaseClientRepository
{
    /**
     * Determine if the given client can handle the given grant type.
     *
     * @param  \Laravel\Passport\Client  $record
     * @param  string  $grantType
     * @return bool
     */
    protected function handlesGrant($record, $grantType)
    {
        if (is_array($record->grant_types) && ! in_array($grantType, $record->grant_types)) {
            return false;
        }

        switch ($grantType) {
            case 'personal_access':
                return $record->personal_access_client && $record->confidential();
            case 'password':
                return $record->password_client;
            case 'client_credentials':
                return $record->confidential();
            default:
                return true;
        }
    }
}