<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CpanelService
{
    private $host;
    private $username;
    private $apiToken;
    private $port;

    public function __construct()
    {
        $this->host = config('services.cpanel.host');
        $this->username = config('services.cpanel.username');
        $this->apiToken = config('services.cpanel.api_token');
        $this->port = config('services.cpanel.port', 2083);
    }

    /**
     * Crear un subdominio en cPanel
     */
    public function createSubdomain(string $subdomain, string $rootDomain)
    {
        try {
            $url = "https://{$this->host}:{$this->port}/execute/SubDomain/addsubdomain";

            $response = Http::withHeaders([
                'Authorization' => 'cpanel ' . $this->username . ':' . $this->apiToken,
            ])->get($url, [
                'domain' => $subdomain,
                'rootdomain' => $rootDomain,
                'dir' => "/domains/{$rootDomain}/public_html/backend/public",
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['status']) && $data['status'] == 1) {
                    Log::info("Subdominio creado exitosamente: {$subdomain}.{$rootDomain}");
                    
                    // Esperar un momento para que se propague
                    sleep(2);
                    
                    // Instalar SSL automáticamente
                    $this->installAutoSSL("{$subdomain}.{$rootDomain}");
                    
                    return [
                        'success' => true,
                        'message' => 'Subdominio creado exitosamente',
                        'subdomain' => "{$subdomain}.{$rootDomain}"
                    ];
                }
            }

            Log::error("Error creando subdominio: " . $response->body());
            
            return [
                'success' => false,
                'message' => 'Error al crear el subdominio',
                'error' => $response->json()
            ];

        } catch (\Exception $e) {
            Log::error("Excepción al crear subdominio: " . $e->getMessage());
            
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Instalar certificado SSL automático (AutoSSL)
     */
    public function installAutoSSL(string $domain)
    {
        try {
            $url = "https://{$this->host}:{$this->port}/execute/SSL/install_autossl_certificate";

            $response = Http::withHeaders([
                'Authorization' => 'cpanel ' . $this->username . ':' . $this->apiToken,
            ])->get($url, [
                'domain' => $domain,
            ]);

            if ($response->successful()) {
                Log::info("SSL instalado para: {$domain}");
                return true;
            }

            // Intentar con el método alternativo
            return $this->queueAutoSSL($domain);

        } catch (\Exception $e) {
            Log::error("Error instalando SSL: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Encolar dominio para AutoSSL (método alternativo)
     */
    private function queueAutoSSL(string $domain)
    {
        try {
            $url = "https://{$this->host}:{$this->port}/execute/SSL/set_autossl_metadata";

            $response = Http::withHeaders([
                'Authorization' => 'cpanel ' . $this->username . ':' . $this->apiToken,
            ])->get($url, [
                'domain' => $domain,
                'enabled' => 1,
            ]);

            if ($response->successful()) {
                Log::info("AutoSSL encolado para: {$domain}");
                return true;
            }

            return false;

        } catch (\Exception $e) {
            Log::error("Error encolando AutoSSL: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Eliminar un subdominio
     */
    public function deleteSubdomain(string $subdomain, string $rootDomain)
    {
        try {
            $fullDomain = "{$subdomain}.{$rootDomain}";
            $url = "https://{$this->host}:{$this->port}/execute/SubDomain/delsubdomain";

            $response = Http::withHeaders([
                'Authorization' => 'cpanel ' . $this->username . ':' . $this->apiToken,
            ])->get($url, [
                'domain' => $fullDomain,
            ]);

            if ($response->successful()) {
                Log::info("Subdominio eliminado: {$fullDomain}");
                return true;
            }

            return false;

        } catch (\Exception $e) {
            Log::error("Error eliminando subdominio: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Verificar estado del SSL de un dominio
     */
    public function checkSSLStatus(string $domain)
    {
        try {
            $url = "https://{$this->host}:{$this->port}/execute/SSL/list_certs";

            $response = Http::withHeaders([
                'Authorization' => 'cpanel ' . $this->username . ':' . $this->apiToken,
            ])->get($url);

            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['data'])) {
                    foreach ($data['data'] as $cert) {
                        if (isset($cert['domains']) && in_array($domain, $cert['domains'])) {
                            return [
                                'has_ssl' => true,
                                'issuer' => $cert['issuer_organizationName'] ?? 'Unknown',
                                'expires' => $cert['not_after'] ?? null,
                            ];
                        }
                    }
                }
            }

            return ['has_ssl' => false];

        } catch (\Exception $e) {
            Log::error("Error verificando SSL: " . $e->getMessage());
            return ['has_ssl' => false];
        }
    }
}
