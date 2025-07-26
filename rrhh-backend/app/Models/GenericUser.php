<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class GenericUser extends Authenticatable
{
    protected $attributes = [];
    protected $table = null; // No necesitamos tabla para usuarios JWT
    public $timestamps = false; // No necesitamos timestamps
    protected $fillable = ['id', 'email', 'name', 'given_name', 'family_name', 'preferred_username', 'email_verified', 'realm_roles', 'resource_roles', 'scope', 'client_id'];

    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    public function getAuthIdentifierName()
    {
        return 'id';
    }

    public function getAuthIdentifier()
    {
        return $this->attributes['id'];
    }

    public function getAuthPassword()
    {
        return null;
    }

    public function getAuthPasswordName()
    {
        return 'password';
    }

    public function getRememberToken()
    {
        return null;
    }

    public function setRememberToken($value)
    {
        // No implementation needed for JWT tokens
    }

    public function getRememberTokenName()
    {
        return null;
    }

    // Sobrescribir métodos de Eloquent para trabajar con atributos personalizados
    public function getAttribute($key)
    {
        return $this->attributes[$key] ?? null;
    }

    public function setAttribute($key, $value)
    {
        $this->attributes[$key] = $value;
        return $this;
    }

    public function getKey()
    {
        return $this->getAuthIdentifier();
    }

    public function getKeyName()
    {
        return 'id';
    }

    public function getCustomAttribute($key, $default = null)
    {
        return $this->attributes[$key] ?? $default;
    }

    public function getEmail()
    {
        return $this->attributes['email'] ?? null;
    }

    public function getName()
    {
        return $this->attributes['name'] ?? null;
    }

    public function getGivenName()
    {
        return $this->attributes['given_name'] ?? null;
    }

    public function getFamilyName()
    {
        return $this->attributes['family_name'] ?? null;
    }

    public function getPreferredUsername()
    {
        return $this->attributes['preferred_username'] ?? null;
    }

    public function isEmailVerified()
    {
        return $this->attributes['email_verified'] ?? false;
    }

    public function getRealmRoles()
    {
        return $this->attributes['realm_roles'] ?? [];
    }

    public function getResourceRoles()
    {
        return $this->attributes['resource_roles'] ?? [];
    }

    public function getResourceRolesForClient($clientId = null)
    {
        $resourceRoles = $this->getResourceRoles();

        if ($clientId) {
            return $resourceRoles[$clientId]['roles'] ?? [];
        }

        // Si no se especifica cliente, devolver roles del cliente actual
        $currentClientId = $this->attributes['client_id'] ?? null;
        if ($currentClientId && isset($resourceRoles[$currentClientId])) {
            return $resourceRoles[$currentClientId]['roles'] ?? [];
        }

        return [];
    }

    public function getScope()
    {
        return $this->attributes['scope'] ?? '';
    }

    public function getClientId()
    {
        return $this->attributes['client_id'] ?? null;
    }

    // Métodos de compatibilidad con la versión anterior
    public function getRoles()
    {
        return $this->getRealmRoles();
    }

    public function hasRole($role)
    {
        return in_array($role, $this->getRealmRoles()) ||
               in_array($role, $this->getResourceRolesForClient());
    }

    public function hasAnyRole($roles)
    {
        if (is_string($roles)) {
            return $this->hasRole($roles);
        }

        $allRoles = array_merge($this->getRealmRoles(), $this->getResourceRolesForClient());
        return !empty(array_intersect($roles, $allRoles));
    }

    public function hasRealmRole($role)
    {
        return in_array($role, $this->getRealmRoles());
    }

    public function hasResourceRole($role, $clientId = null)
    {
        return in_array($role, $this->getResourceRolesForClient($clientId));
    }

    public function getAllAttributes()
    {
        return $this->attributes;
    }
}
