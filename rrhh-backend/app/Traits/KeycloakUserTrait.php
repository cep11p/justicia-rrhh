<?php

namespace App\Traits;

use App\Models\GenericUser;

trait KeycloakUserTrait
{
    /**
     * Get the authenticated Keycloak user
     */
    protected function getKeycloakUser(): ?GenericUser
    {
        $user = request()->user();

        if ($user instanceof GenericUser) {
            return $user;
        }

        return null;
    }

    /**
     * Check if the current user has a specific realm role
     */
    protected function hasRealmRole(string $role): bool
    {
        $user = $this->getKeycloakUser();
        return $user ? $user->hasRealmRole($role) : false;
    }

    /**
     * Check if the current user has any of the specified realm roles
     */
    protected function hasAnyRealmRole(array $roles): bool
    {
        $user = $this->getKeycloakUser();
        return $user ? $user->hasAnyRole($roles) : false;
    }

    /**
     * Check if the current user has a specific resource role
     */
    protected function hasResourceRole(string $role, ?string $clientId = null): bool
    {
        $user = $this->getKeycloakUser();
        return $user ? $user->hasResourceRole($role, $clientId) : false;
    }

    /**
     * Get all realm roles of the current user
     */
    protected function getUserRealmRoles(): array
    {
        $user = $this->getKeycloakUser();
        return $user ? $user->getRealmRoles() : [];
    }

    /**
     * Get all resource roles of the current user for a specific client
     */
    protected function getUserResourceRoles(?string $clientId = null): array
    {
        $user = $this->getKeycloakUser();
        return $user ? $user->getResourceRolesForClient($clientId) : [];
    }
}
