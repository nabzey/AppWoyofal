<?php

namespace App\Core;

use App\Core\Abstract\Singleton;
use Symfony\Component\Yaml\Yaml;

class ServiceContainer extends Singleton
{
    private array $services = [];
    private array $singletons = [];
    private array $config = [];

    protected function __construct()
    {
        parent::__construct();
        $this->loadConfiguration();
    }

    private function loadConfiguration(): void
    {
        $configFile = __DIR__ . '/../config/services.yml';
        if (file_exists($configFile)) {
            $this->config = Yaml::parseFile($configFile);
        }
    }

    public function get(string $serviceName): object
    {
        // Gestion spéciale pour PDO
        if ($serviceName === 'PDO') {
            if (isset($this->singletons['PDO'])) {
                return $this->singletons['PDO'];
            }
            $pdo = \App\Core\Abstract\Database::getConnection();
            $this->singletons['PDO'] = $pdo;
            return $pdo;
        }

        // Vérifier si c'est un singleton déjà instancié
        if (isset($this->singletons[$serviceName])) {
            return $this->singletons[$serviceName];
        }

        // Chercher dans la configuration
        $serviceConfig = $this->findServiceConfig($serviceName);
        
        if (!$serviceConfig) {
            throw new \Exception("Service '$serviceName' non trouvé dans la configuration");
        }

        $className = $serviceConfig['class'];
        
        if (!class_exists($className)) {
            throw new \Exception("Classe '$className' non trouvée");
        }

        // Résoudre les dépendances
        $dependencies = [];
        if (isset($serviceConfig['dependencies'])) {
            foreach ($serviceConfig['dependencies'] as $dependency) {
                $dependencies[] = $this->get($dependency);
            }
        }

        // 🔥 CORRECTION : Gérer les classes Singleton différemment
        $service = $this->instantiateService($className, $dependencies);

        // Stocker si singleton
        if ($serviceConfig['singleton'] ?? false) {
            $this->singletons[$serviceName] = $service;
        }

        return $service;
    }

    /**
     * Instancie un service en gérant les Singletons et les classes normales
     */
    private function instantiateService(string $className, array $dependencies): object
    {
        // Vérifier si c'est une classe qui hérite de Singleton
        if (is_subclass_of($className, \App\Core\Abstract\Singleton::class)) {
            // Pour les Singletons, on utilise getInstance() et on injecte les dépendances si possible
            $instance = $className::getInstance();
            
            // Si le singleton a besoin de dépendances après instantiation, on peut les injecter
            // (cette partie dépend de votre implémentation spécifique)
            
            return $instance;
        }

        // Pour les classes normales, instanciation classique
        return empty($dependencies) 
            ? new $className() 
            : new $className(...$dependencies);
    }

    private function findServiceConfig(string $serviceName): ?array
    {
        $sections = ['repositories', 'services', 'controllers'];
        
        foreach ($sections as $section) {
            if (isset($this->config['services'][$section][$serviceName])) {
                return $this->config['services'][$section][$serviceName];
            }
        }
        
        return null;
    }

    public function getConfig(string $key = null): mixed
    {
        if ($key === null) {
            return $this->config;
        }

        $keys = explode('.', $key);
        $value = $this->config;

        foreach ($keys as $k) {
            if (!isset($value[$k])) {
                return null;
            }
            $value = $value[$k];
        }

        return $value;
    }

    public function has(string $serviceName): bool
    {
        return $this->findServiceConfig($serviceName) !== null;
    }
    
    public static function fromYaml(string $yamlPath): self
    {
        $instance = self::getInstance();
        if (file_exists($yamlPath)) {
            $instance->config = \Symfony\Component\Yaml\Yaml::parseFile($yamlPath);
        }
        return $instance;
    }
}