<?php

namespace App\Config;

class Environment {
    private $variables = [];

    public function __construct(array $variables)
    {
        $this->variables = $variables;
    }

    public function get(string $key, $default = null)
    {
        return $this->variables[$key] ?? $default;
    }

    public static function load(string $path)
    {
        if (file_exists($path)) {
            $envContent = file_get_contents($path);
            $envVariables = [];

            // Dividir el contenido en líneas y procesar cada línea
            foreach (explode("\n", $envContent) as $line) {
                // Ignorar líneas vacías y comentarios
                if (!empty($line) && strpos($line, '=') !== false && strpos($line, '#') !== 0) {
                    // Dividir la línea en clave y valor
                    [$key, $value] = explode('=', $line, 2);
                    // Eliminar espacios en blanco alrededor de la clave y el valor
                    $envVariables[trim($key)] = trim($value);
                }
            }            

            // Aca llama al contructor de esta clase
            return new self($envVariables);
            
        } else {
            throw new \Exception('El archivo .env no existe');
        }

    }
    
}
