@extends('tenant.layout')

@section('title', 'Configuración')
@section('page-title', 'Configuración de la Tienda')

@section('styles')
<style>
    .nav-pills .nav-link {
        color: #6b7280;
        border-radius: 0.5rem;
        padding: 0.75rem 1.25rem;
    }
    .nav-pills .nav-link.active {
        background-color: var(--primary-color);
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-3">
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-pills flex-column" id="settingsTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active w-100 text-start" id="store-tab" 
                                data-bs-toggle="pill" data-bs-target="#store" type="button">
                            <i class="bi bi-shop me-2"></i>Datos de la Tienda
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link w-100 text-start" id="appearance-tab" 
                                data-bs-toggle="pill" data-bs-target="#appearance" type="button">
                            <i class="bi bi-palette me-2"></i>Apariencia
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link w-100 text-start" id="payment-tab" 
                                data-bs-toggle="pill" data-bs-target="#payment" type="button">
                            <i class="bi bi-credit-card me-2"></i>Mercado Pago
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="col-lg-9">
        <div class="tab-content" id="settingsTabContent">
            <!-- Store Settings -->
            <div class="tab-pane fade show active" id="store" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Datos de la Tienda</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('vendedor.configuracion.tienda') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Nombre de la Tienda</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" 
                                           value="{{ old('name', $tenant->name) }}">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email de Contacto</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" 
                                           value="{{ old('email', $tenant->email) }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Teléfono</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" 
                                           value="{{ old('phone', $tenant->phone) }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="address" class="form-label">Dirección</label>
                                    <input type="text" class="form-control @error('address') is-invalid @enderror" 
                                           id="address" name="address" 
                                           value="{{ old('address', $tenant->address) }}">
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="logo" class="form-label">Logo de la Tienda</label>
                                @if($settings && $settings->logo)
                                    <div class="mb-2">
                                        <img src="{{ Storage::url($settings->logo) }}" alt="Logo" 
                                             class="img-thumbnail" style="max-height: 100px;">
                                    </div>
                                @endif
                                <input type="file" class="form-control @error('logo') is-invalid @enderror" 
                                       id="logo" name="logo" accept="image/*">
                                <small class="text-muted">Tamaño máximo: 2MB</small>
                                @error('logo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="banner" class="form-label">Banner Principal</label>
                                @if($settings && $settings->banner)
                                    <div class="mb-2">
                                        <img src="{{ Storage::url($settings->banner) }}" alt="Banner" 
                                             class="img-thumbnail" style="max-height: 150px;">
                                    </div>
                                @endif
                                <input type="file" class="form-control @error('banner') is-invalid @enderror" 
                                       id="banner" name="banner" accept="image/*">
                                <small class="text-muted">Tamaño máximo: 4MB. Recomendado: 1920x600px</small>
                                @error('banner')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-2"></i>Guardar Cambios
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Appearance Settings -->
            <div class="tab-pane fade" id="appearance" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Personalización de Apariencia</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('vendedor.configuracion.apariencia') }}" method="POST">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="primary_color" class="form-label">Color Primario</label>
                                    <input type="color" class="form-control form-control-color" 
                                           id="primary_color" name="primary_color" 
                                           value="{{ old('primary_color', $settings->primary_color ?? '#4f46e5') }}">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="secondary_color" class="form-label">Color Secundario</label>
                                    <input type="color" class="form-control form-control-color" 
                                           id="secondary_color" name="secondary_color" 
                                           value="{{ old('secondary_color', $settings->secondary_color ?? '#10b981') }}">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="font_family" class="form-label">Fuente</label>
                                <select class="form-select" id="font_family" name="font_family">
                                    <option value="system-ui" {{ ($settings->font_family ?? 'system-ui') === 'system-ui' ? 'selected' : '' }}>
                                        System UI (Predeterminada)
                                    </option>
                                    <option value="Arial" {{ ($settings->font_family ?? '') === 'Arial' ? 'selected' : '' }}>
                                        Arial
                                    </option>
                                    <option value="Georgia" {{ ($settings->font_family ?? '') === 'Georgia' ? 'selected' : '' }}>
                                        Georgia
                                    </option>
                                    <option value="'Courier New'" {{ ($settings->font_family ?? '') === "'Courier New'" ? 'selected' : '' }}>
                                        Courier New
                                    </option>
                                </select>
                            </div>
                            
                            <hr>
                            
                            <h6 class="mb-3">Opciones de Visualización</h6>
                            
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="show_categories" 
                                       name="show_categories" value="1" 
                                       {{ old('show_categories', $settings->show_categories ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="show_categories">
                                    Mostrar categorías en el menú
                                </label>
                            </div>
                            
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="show_search" 
                                       name="show_search" value="1" 
                                       {{ old('show_search', $settings->show_search ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="show_search">
                                    Mostrar barra de búsqueda
                                </label>
                            </div>
                            
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="show_reviews" 
                                       name="show_reviews" value="1" 
                                       {{ old('show_reviews', $settings->show_reviews ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="show_reviews">
                                    Permitir reseñas de productos
                                </label>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-2"></i>Guardar Cambios
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Payment Settings -->
            <div class="tab-pane fade" id="payment" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Configuración de Mercado Pago</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>¿Cómo obtener tus credenciales?</strong><br>
                            1. Ingresa a tu cuenta de Mercado Pago<br>
                            2. Ve a Configuración > Credenciales<br>
                            3. Copia el Access Token y el Public Key
                        </div>
                        
                        <form action="{{ route('vendedor.configuracion.mercadopago') }}" method="POST">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="mp_access_token" class="form-label">Access Token</label>
                                <input type="password" class="form-control @error('mp_access_token') is-invalid @enderror" 
                                       id="mp_access_token" name="mp_access_token" 
                                       placeholder="APP_USR-XXXXXXXXXXXX-XXXXXX-XXXXXXXXXXXXXXXX">
                                <small class="text-muted">
                                    {{ $paymentGateway && $paymentGateway->access_token ? '✓ Configurado' : 'No configurado' }}
                                </small>
                                @error('mp_access_token')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="mp_public_key" class="form-label">Public Key</label>
                                <input type="text" class="form-control @error('mp_public_key') is-invalid @enderror" 
                                       id="mp_public_key" name="mp_public_key" 
                                       placeholder="APP_USR-XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX"
                                       value="{{ old('mp_public_key', $paymentGateway->public_key ?? '') }}">
                                @error('mp_public_key')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="mp_is_active" 
                                       name="mp_is_active" value="1" 
                                       {{ old('mp_is_active', $paymentGateway->is_active ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="mp_is_active">
                                    Activar Mercado Pago como método de pago
                                </label>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-2"></i>Guardar Configuración
                                </button>
                                
                                @if($paymentGateway && $paymentGateway->access_token)
                                <button type="button" class="btn btn-outline-secondary" id="testMercadoPago">
                                    <i class="bi bi-check-circle me-2"></i>Probar Conexión
                                </button>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('testMercadoPago')?.addEventListener('click', async function() {
    const btn = this;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Probando...';
    
    try {
        const response = await fetch('{{ route("vendedor.configuracion.testMercadoPago") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('✓ Conexión exitosa con Mercado Pago');
        } else {
            alert('✗ Error: ' + (data.message || 'No se pudo conectar'));
        }
    } catch (error) {
        alert('✗ Error al probar la conexión');
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-check-circle me-2"></i>Probar Conexión';
    }
});
</script>
@endsection
