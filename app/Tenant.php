<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{

    protected $connection = 'mysql';
    
    protected $fillable = [
        'name',
        'domain',
        'reception_enabled',
        'restaurant_enabled',
        'kitchen_enabled',
        'clients_enabled',
        'analytics_enabled',
        'admin_enabled'
    ];

    public function scopeActive($query)
    {
        return $query->where('active', '=', true);
    }

    public function scopeAdmin($query)
    {
        return $query->where('admin', '=', true);
    }

    public function scopeNotAdmin($query)
    {
        return $query->where('admin', '=', false);
    }

    public function scopeSubdomain($query, $subdomain)
    {
        return $query->where('subdomain', '=', $subdomain);
    }
}
