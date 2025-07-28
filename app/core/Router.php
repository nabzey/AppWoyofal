<?php
namespace App\Core;

class Router
{
    private array $routes = [];

    public function addRoute(string $method, string $path, callable $handler): void
    {
        $method = strtoupper($method);
        $this->routes[$method][$path] = $handler;
        
        // Log d'ajout de route pour debug
        error_log("Route ajoutée: $method $path");
    }

    public function dispatch(string $method, string $path)
    {
        $method = strtoupper($method);
        
        // Log pour debug avec plus d'informations
        error_log("=== ROUTER DEBUG ===");
        error_log("Requête: $method $path");
        error_log("Routes $method disponibles: " . json_encode(array_keys($this->routes[$method] ?? [])));
        error_log("Toutes les routes: " . json_encode($this->getAllRoutesFlat()));
        
        // Vérifier si la route exacte existe
        if (isset($this->routes[$method][$path])) {
            error_log("Route trouvée, exécution...");
            try {
                $result = call_user_func($this->routes[$method][$path]);
                error_log("Route exécutée avec succès");
                return $result;
            } catch (Exception $e) {
                error_log("Erreur lors de l'exécution de la route: " . $e->getMessage());
                return [
                    'data' => null,
                    'statut' => 'error',
                    'code' => 500,
                    'message' => 'Erreur lors de l\'exécution de la route: ' . $e->getMessage()
                ];
            }
        }
        
        // Si aucune route trouvée
        error_log("Aucune route trouvée pour: $method $path");
        return [
            'data' => null,
            'statut' => 'error',
            'code' => 404,
            'message' => "Route non trouvée: $method $path",
            'routes_disponibles' => $this->getAllRoutesFlat()
        ];
    }
    
    // Méthode utilitaire pour lister toutes les routes
    public function getRoutes(): array
    {
        return $this->routes;
    }
    
    // Méthode pour obtenir toutes les routes dans un format plat
    private function getAllRoutesFlat(): array
    {
        $flat = [];
        foreach ($this->routes as $method => $paths) {
            foreach ($paths as $path => $handler) {
                $flat[] = "$method $path";
            }
        }
        return $flat;
    }
}