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
}

class EnvironmentLoader {
    public static function load(string $path): Environment
    {
        $envContent = file_get_contents($path);
        $envVariables = [];
        foreach (explode("\n", $envContent) as $line) {
            [$key, $value] = explode('=', $line, 2);
            $envVariables[trim($key)] = trim($value);
        }
        return new Environment($envVariables);
    }
}

$environment = EnvironmentLoader::load(__DIR__ . '/../../.env');

?>
