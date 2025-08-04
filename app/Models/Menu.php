<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'route',
        'icon',
        'permission',
        'section',
        'roles',
        'order',
        'is_active',
        'has_submenu',
        'parent_id',
        'description'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'has_submenu' => 'boolean',
        'roles' => 'array'
    ];

    /**
     * Get the parent menu item
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    /**
     * Get the child menu items
     */
    public function children(): HasMany
    {
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('order');
    }

    /**
     * Scope for active menus
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for parent menus (no parent_id)
     */
    public function scopeParent($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope for specific role
     */
    public function scopeForRole($query, $role)
    {
        return $query->whereJsonContains('roles', $role);
    }

    /**
     * Scope for specific section
     */
    public function scopeInSection($query, $section)
    {
        return $query->where('section', $section);
    }

    /**
     * Get menus ordered by section and order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('section')->orderBy('order');
    }

    /**
     * Check if menu has specific role
     */
    public function hasRole($role): bool
    {
        return in_array($role, $this->roles ?? []);
    }

    /**
     * Get formatted roles as string
     */
    public function getRolesStringAttribute(): string
    {
        return implode(', ', $this->roles ?? []);
    }
}