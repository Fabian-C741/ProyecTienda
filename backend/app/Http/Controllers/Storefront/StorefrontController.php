<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class StorefrontController extends Controller
{
    /**
     * Obtener tenant desde el middleware o por slug
     */
    private function getTenant($slug = null)
    {
        // Si viene slug (ruta alternativa /tienda/{slug}), buscar tenant
        if ($slug) {
            $tenant = Tenant::where('slug', $slug)->firstOrFail();
            // Compartir globalmente para las vistas
            view()->share('tenant', $tenant);
            return $tenant;
        }
        
        // Si no hay slug, usar el tenant del middleware (subdomain)
        return app('tenant');
    }

    /**
     * Mostrar la página principal de la tienda del tenant
     */
    public function home($slug = null)
    {
        $tenant = $this->getTenant($slug);

        // Productos destacados del tenant
        $featuredProducts = Product::where('tenant_id', $tenant->id)
            ->where('is_active', true)
            ->where('is_featured', true)
            ->with('category')
            ->take(8)
            ->get();

        // Productos recientes
        $recentProducts = Product::where('tenant_id', $tenant->id)
            ->where('is_active', true)
            ->with('category')
            ->latest()
            ->take(12)
            ->get();

        // Categorías con productos
        $categories = Category::whereHas('products', function($q) use ($tenant) {
            $q->where('tenant_id', $tenant->id)
              ->where('is_active', true);
        })->withCount(['products' => function($q) use ($tenant) {
            $q->where('tenant_id', $tenant->id)
              ->where('is_active', true);
        }])->get();

        return view('storefront.home', compact(
            'tenant',
            'featuredProducts',
            'recentProducts',
            'categories'
        ));
    }

    /**
     * Listado de productos de la tienda
     */
    public function products(Request $request, $slug = null)
    {
        $tenant = $this->getTenant($slug);

        $query = Product::where('tenant_id', $tenant->id)
            ->where('is_active', true)
            ->with('category');

        // Filtro por categoría
        if ($request->category) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Búsqueda
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%");
            });
        }

        // Ordenamiento
        switch ($request->sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            default:
                $query->latest();
        }

        $products = $query->paginate(24);

        $categories = Category::whereHas('products', function($q) use ($tenant) {
            $q->where('tenant_id', $tenant->id)
              ->where('is_active', true);
        })->get();

        return view('storefront.products', compact('tenant', 'products', 'categories'));
    }

    /**
     * Detalle de producto
     */
    public function product($productSlug, $slug = null)
    {
        $tenant = $this->getTenant($slug);

        $product = Product::where('tenant_id', $tenant->id)
            ->where('slug', $productSlug)
            ->where('is_active', true)
            ->with('category')
            ->firstOrFail();

        // Productos relacionados de la misma categoría
        $relatedProducts = Product::where('tenant_id', $tenant->id)
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        return view('storefront.product-detail', compact('tenant', 'product', 'relatedProducts'));
    }

    /**
     * Página "Acerca de"
     */
    public function about($slug = null)
    {
        $tenant = $this->getTenant($slug);

        return view('storefront.about', compact('tenant'));
    }

    /**
     * Contacto
     */
    public function contact($slug = null)
    {
        $tenant = $this->getTenant($slug);

        return view('storefront.contact', compact('tenant'));
    }

    /**
     * Página personalizada
     */
    public function page($pageSlug, $slug = null)
    {
        $tenant = $this->getTenant($slug);
        
        $page = $tenant->pages()
            ->where('slug', $pageSlug)
            ->where('is_published', true)
            ->firstOrFail();

        return view('storefront.page', compact('tenant', 'page'));
    }
}
