<?php

namespace App\Helpers;

use App\Models\AdminMenu;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AdminMenuHelper
{
    /**
     * Get the admin menu structure
     */
    public static function getMenuStructure($menuType = 'both')
    {
        return Cache::remember("admin_menu_{$menuType}", 3600, function () use ($menuType) {
            return AdminMenu::with(['children' => function ($query) {
                $query->where('is_active', true)->orderBy('sort_order');
            }])
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->where(function ($query) use ($menuType) {
                $query->where('menu_type', $menuType)
                      ->orWhere('menu_type', 'both');
            })
            ->orderBy('sort_order')
            ->get();
        });
    }

    /**
     * Render menu items HTML
     */
    public static function renderMenu($menuType = 'sidebar', $template = 'default')
    {
        $menus = self::getMenuStructure($menuType);
        $html = '';

        foreach ($menus as $menu) {
            if (self::canAccessMenu($menu)) {
                $html .= self::renderMenuItem($menu, $template);
            }
        }

        return $html;
    }

    /**
     * Render a single menu item
     */
    private static function renderMenuItem($menu, $template = 'default')
    {
        $hasChildren = $menu->children->isNotEmpty();
        $isActive = self::isMenuActive($menu);
        $hasActiveChildren = $hasChildren && self::hasActiveChildren($menu);

        switch ($template) {
            case 'modern':
                return self::renderModernMenuItem($menu, $hasChildren, $isActive, $hasActiveChildren);
            case 'compact':
                return self::renderCompactMenuItem($menu, $hasChildren, $isActive, $hasActiveChildren);
            default:
                return self::renderDefaultMenuItem($menu, $hasChildren, $isActive, $hasActiveChildren);
        }
    }

    /**
     * Render default menu item template
     */
    private static function renderDefaultMenuItem($menu, $hasChildren, $isActive, $hasActiveChildren)
    {
        $openClass = ($isActive || $hasActiveChildren) ? 'open' : '';
        $activeClass = $isActive ? 'active' : '';

        $html = '<li class="slide' . ($hasChildren ? ' has-sub ' . $openClass : '') . '">';
        
        if ($hasChildren) {
            $html .= '<a href="javascript:void(0);" class="side-menu__item ' . $activeClass . '">';
        } else {
            $url = self::getMenuUrl($menu);
            $target = $menu->target === '_blank' ? ' target="_blank"' : '';
            $html .= '<a href="' . $url . '" class="side-menu__item ' . $activeClass . '"' . $target . '>';
        }

        if ($menu->icon) {
            $html .= '<i class="' . $menu->icon . ' side-menu__icon"></i>';
        }

        $html .= '<span class="side-menu__label">' . $menu->title . '</span>';

        if ($menu->badge_text) {
            $html .= '<span class="badge bg-' . $menu->badge_color . ' ms-auto">' . $menu->badge_text . '</span>';
        }

        if ($hasChildren) {
            $html .= '<i class="fe fe-chevron-right side-menu__angle"></i>';
        }

        $html .= '</a>';

        if ($hasChildren) {
            $html .= '<ul class="slide-menu child1">';
            foreach ($menu->children as $child) {
                if (self::canAccessMenu($child)) {
                    $childActive = self::isMenuActive($child) ? 'active' : '';
                    $childUrl = self::getMenuUrl($child);
                    $childTarget = $child->target === '_blank' ? ' target="_blank"' : '';

                    $html .= '<li class="slide">';
                    $html .= '<a href="' . $childUrl . '" class="side-menu__item ' . $childActive . '"' . $childTarget . '>';
                    
                    if ($child->icon) {
                        $html .= '<i class="' . $child->icon . '"></i> ';
                    }
                    
                    $html .= $child->title;
                    
                    if ($child->badge_text) {
                        $html .= '<span class="badge bg-' . $child->badge_color . ' ms-auto">' . $child->badge_text . '</span>';
                    }
                    
                    $html .= '</a>';
                    $html .= '</li>';
                }
            }
            $html .= '</ul>';
        }

        $html .= '</li>';

        return $html;
    }

    /**
     * Render modern menu item template
     */
    private static function renderModernMenuItem($menu, $hasChildren, $isActive, $hasActiveChildren)
    {
        $activeClass = ($isActive || $hasActiveChildren) ? 'active' : '';

        $html = '<li class="nav-item' . ($hasChildren ? ' has-submenu' : '') . ' ' . $activeClass . '">';
        
        if ($hasChildren) {
            $html .= '<a href="#" class="nav-link">';
        } else {
            $url = self::getMenuUrl($menu);
            $target = $menu->target === '_blank' ? ' target="_blank"' : '';
            $html .= '<a href="' . $url . '" class="nav-link"' . $target . '>';
        }

        if ($menu->icon) {
            $html .= '<i class="' . $menu->icon . ' nav-icon"></i>';
        }

        $html .= '<span class="nav-text">' . $menu->title . '</span>';

        if ($hasChildren) {
            $html .= '<i class="bx bx-chevron-right nav-arrow"></i>';
        }

        if ($menu->badge_text) {
            $html .= '<span class="nav-badge ' . $menu->badge_color . '">' . $menu->badge_text . '</span>';
        }

        $html .= '</a>';

        if ($hasChildren) {
            $html .= '<ul class="nav-submenu">';
            foreach ($menu->children as $child) {
                if (self::canAccessMenu($child)) {
                    $childUrl = self::getMenuUrl($child);
                    $childTarget = $child->target === '_blank' ? ' target="_blank"' : '';
                    $html .= '<li><a href="' . $childUrl . '"' . $childTarget . '>' . $child->title . '</a></li>';
                }
            }
            $html .= '</ul>';
        }

        $html .= '</li>';

        return $html;
    }

    /**
     * Render compact menu item template
     */
    private static function renderCompactMenuItem($menu, $hasChildren, $isActive, $hasActiveChildren)
    {
        $activeClass = $isActive ? 'active' : '';
        $url = self::getMenuUrl($menu);
        $target = $menu->target === '_blank' ? ' target="_blank"' : '';

        $html = '<li class="nav-item ' . $activeClass . '">';
        $html .= '<a href="' . $url . '" class="nav-link"' . $target . '>';

        if ($menu->icon) {
            $html .= '<i class="' . $menu->icon . '"></i>';
        }

        $html .= '<span>' . $menu->title . '</span>';

        if ($menu->badge_text) {
            $html .= '<span class="badge">' . $menu->badge_text . '</span>';
        }

        $html .= '</a>';
        $html .= '</li>';

        return $html;
    }

    /**
     * Get menu URL
     */
    private static function getMenuUrl($menu)
    {
        if ($menu->route) {
            try {
                return route($menu->route);
            } catch (\Exception $e) {
                return $menu->url ?: '#';
            }
        }

        return $menu->url ?: '#';
    }

    /**
     * Check if user can access menu
     */
    private static function canAccessMenu($menu)
    {
        if (!$menu->is_active) {
            return false;
        }

        if ($menu->permission && Auth::guard('admin')->check()) {
            // Check if admin has permission
            $admin = Auth::guard('admin')->user();
            
            // Super admin can access everything
            if (isset($admin->is_super_admin) && $admin->is_super_admin) {
                return true;
            }

            // Check specific permission using Laravel's authorization
            try {
                return Gate::forUser($admin)->check($menu->permission);
            } catch (\Exception $e) {
                // If permission doesn't exist or check fails, allow access
                return true;
            }
        }

        return true;
    }

    /**
     * Check if menu is currently active
     */
    private static function isMenuActive($menu)
    {
        if (!$menu->route) {
            return false;
        }

        $currentRoute = request()->route();
        if (!$currentRoute) {
            return false;
        }
        
        $currentRouteName = $currentRoute->getName();
        if (!$currentRouteName) {
            return false;
        }
        
        // Exact match
        if ($currentRouteName === $menu->route) {
            return true;
        }

        // Pattern match for wildcard routes
        if (str_contains($menu->route, '*')) {
            $pattern = str_replace('*', '', $menu->route);
            return str_starts_with($currentRouteName, $pattern);
        }

        return false;
    }

    /**
     * Check if menu has active children
     */
    private static function hasActiveChildren($menu)
    {
        foreach ($menu->children as $child) {
            if (self::isMenuActive($child) && self::canAccessMenu($child)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get breadcrumb for current route
     */
    public static function getBreadcrumb()
    {
        $currentRoute = request()->route()->getName();
        
        $menu = AdminMenu::where('route', $currentRoute)
                         ->orWhere('route', 'LIKE', str_replace('*', '%', $currentRoute))
                         ->first();

        if (!$menu) {
            return collect();
        }

        return $menu->getBreadcrumb();
    }

    /**
     * Clear menu cache
     */
    public static function clearCache()
    {
        Cache::forget('admin_menu_sidebar');
        Cache::forget('admin_menu_main');
        Cache::forget('admin_menu_both');
    }

    /**
     * Get menu statistics
     */
    public static function getMenuStats()
    {
        return Cache::remember('admin_menu_stats', 1800, function () {
            return [
                'total' => AdminMenu::count(),
                'active' => AdminMenu::where('is_active', true)->count(),
                'inactive' => AdminMenu::where('is_active', false)->count(),
                'parents' => AdminMenu::whereNull('parent_id')->count(),
                'children' => AdminMenu::whereNotNull('parent_id')->count(),
                'with_routes' => AdminMenu::whereNotNull('route')->count(),
                'with_urls' => AdminMenu::whereNotNull('url')->count(),
                'with_permissions' => AdminMenu::whereNotNull('permission')->count(),
            ];
        });
    }

    /**
     * Generate menu HTML for different layouts
     */
    public static function generate($layout = 'sidebar')
    {
        switch ($layout) {
            case 'modern':
                return self::renderMenu('both', 'modern');
            case 'compact':
                return self::renderMenu('both', 'compact');
            case 'sidebar':
            default:
                return self::renderMenu('sidebar', 'default');
        }
    }
}
