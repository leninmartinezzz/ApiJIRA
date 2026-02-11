<?php
// app/Services/JiraServices.php - VERSIÓN UNIVERSAL
namespace App\Services;

use JiraRestApi\Configuration\ArrayConfiguration;
use JiraRestApi\Issue\IssueService;
use JiraRestApi\Project\ProjectService;
use Illuminate\Support\Facades\Log;

class JiraServices
{
    private $issueService;
    private $projectService;

    public function __construct()
    {
        $config = new ArrayConfiguration([
            'jiraHost' => config('services.jira.base_url'),
            'jiraUser' => config('services.jira.user_email'),
            'jiraPassword' => config('services.jira.api_token'),
            'useV3RestApi' => true,
            'timeout' => 30,
        ]);

        $this->issueService = new IssueService($config);
        $this->projectService = new ProjectService($config);
    }

    /**
     * Método UNIVERSAL para extraer datos del searchResult
     */
    private function extractFromSearchResult($searchResult)
    {
        $data = [
            'total' => 0,
            'issues' => [],
        ];

        // Método 1: Si es un objeto con métodos getTotal y getIssues
        if (is_object($searchResult)) {
            if (method_exists($searchResult, 'getTotal')) {
                $data['total'] = $searchResult->getTotal();
            } elseif (property_exists($searchResult, 'total')) {
                $data['total'] = $searchResult->total;
            }

            if (method_exists($searchResult, 'getIssues')) {
                $data['issues'] = $searchResult->getIssues();
            } elseif (property_exists($searchResult, 'issues')) {
                $data['issues'] = $searchResult->issues;
            }
        }
        // Método 2: Si es un array (algunas versiones devuelven array)
        elseif (is_array($searchResult)) {
            $data['issues'] = $searchResult;
            $data['total'] = count($searchResult);
        }

        return $data;
    }

    /**
     * PRUEBA DE CONEXIÓN - VERSIÓN UNIVERSAL
     */
    public function testConnection()
    {
        try {
            $searchResult = $this->issueService->search('project IS NOT NULL ORDER BY created DESC', 0, 1);
            $extracted = $this->extractFromSearchResult($searchResult);

            return [
                'success' => true,
                'message' => '✅ Conexión exitosa con JIRA',
                'total_issues' => $extracted['total'],
                'issues_found' => count($extracted['issues']),
                'debug_info' => [
                    'result_type' => gettype($searchResult),
                    'is_object' => is_object($searchResult),
                    'is_array' => is_array($searchResult),
                    'class_name' => is_object($searchResult) ? get_class($searchResult) : 'Not an object',
                ]
            ];

        } catch (\Exception $e) {
            Log::error('JIRA Connection Error: ' . $e->getMessage());

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
            ];
        }
    }

    /**
     * Obtener TODOS los issues - VERSIÓN UNIVERSAL
     */
    public function getAllIssues($jql = 'project = HelpyIT ORDER BY createdDate DESC', $maxResults = 50, $startAt = 0)
    {
        try {
            $searchResult = $this->issueService->search($jql, $startAt, $maxResults);
            $extracted = $this->extractFromSearchResult($searchResult);

            $issues = [];
            foreach ($extracted['issues'] as $issue) {
                $issues[] = $this->formatIssue($issue);
            }

            return [
                'total' => $extracted['total'],
                'start_at' => $startAt,
                'max_results' => $maxResults,
                'jql' => $jql,
                'issues' => $issues,
            ];

        } catch (\Exception $e) {
            Log::error('Error obteniendo todos los issues: ' . $e->getMessage());
            return [
                'total' => 0,
                'issues' => [],
                'error' => $e->getMessage(),
            ];
        }
    }

    public function getIssuesHelpYT($jql = 'project = HelpyIT and "Request Type" NOT IN ("Incidente de Infraestructura (COB)", "Solicitud de Infraestructura (COB)") and status NOT IN (Cerrada, Cancelled, "En Progreso", "En revisión", Resuelta, "Derivada a terceros", "Derivada Soporte N2", "Derivada Soporte N 2", Rechazado, "Esperando respuesta del usuario") AND reporter in membersOf("Cobeca-usuarios-farmacia") and assignee = EMPTY order BY createdDate desc', $maxResults = 50, $startAt = 0)
    {

     try {
            $searchResult = $this->issueService->search($jql, $startAt, $maxResults);
            $extracted = $this->extractFromSearchResult($searchResult);

            $issues = [];
            foreach ($extracted['issues'] as $issue) {
                $issues[] = $this->formatIssue($issue);
            }

            return [
                'total' => $extracted['total'],
                'start_at' => $startAt,
                'max_results' => $maxResults,
                'jql' => $jql,
                'issues' => $issues,
            ];

        } catch (\Exception $e) {
            Log::error('Error obteniendo todos los issues: ' . $e->getMessage());
            return [
                'total' => 0,
                'issues' => [],
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Formatear issue de manera segura
     */
    private function formatIssue($issue)
    {
        // Verificar si el issue tiene la estructura esperada
        if (!is_object($issue) || !property_exists($issue, 'fields')) {
            return [
                'key' => 'N/A',
                'summary' => 'Estructura inesperada',
                'debug' => gettype($issue)
            ];
        }

        return [
            'key' => $issue->key ?? 'N/A',
            'summary' => $issue->fields->summary ?? 'Sin título',
            'description' => $issue->fields->description ?? 'Sin descripción',
            'status' => $issue->fields->status->name ?? 'Desconocido',
            'priority' => $issue->fields->priority->name ?? 'Sin prioridad',
            'assignee' => isset($issue->fields->assignee) ? $issue->fields->assignee->displayName : 'Sin asignar',
            'reporter' => isset($issue->fields->reporter) ? $issue->fields->reporter->displayName : 'Sin reportero',
            'created' => $issue->fields->created ?? null,
            'updated' => $issue->fields->updated ?? null,
            'project' => [
                'key' => $issue->fields->project->key ?? null,
                'name' => $issue->fields->project->name ?? null,
            ],
        ];
    }

    /**
     * Obtener un issue específico
     */
    public function getIssue($issueKey)
    {
        try {
            $issue = $this->issueService->get($issueKey);
            return $this->formatIssue($issue);

        } catch (\Exception $e) {
            Log::error("Error obteniendo issue {$issueKey}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Obtener proyectos
     */
    public function getProjects()
    {
        try {
            $projects = $this->projectService->getAllProjects();

            $projectList = [];
            foreach ($projects as $project) {
                $projectList[] = [
                    'key' => $project->key,
                    'name' => $project->name,
                    'description' => $project->description ?? '',
                ];
            }

            return $projectList;

        } catch (\Exception $e) {
            Log::error('Error obteniendo proyectos: ' . $e->getMessage());
            return [];
        }
    }

public function assignIssue($issueKey, $assigneeId)
{
    try {
        Log::info("Iniciando proceso: Asignar {$issueKey} a {$assigneeId} y mover a En Progreso");

        $baseUrl = config('services.jira.base_url');
        $email = config('services.jira.user_email');
        $apiToken = config('services.jira.api_token');
        $auth = base64_encode("{$email}:{$apiToken}");

        // ===========================================
        // PASO 1: ASIGNAR EL ISSUE
        // ===========================================
        $urlAsignar = "{$baseUrl}/rest/api/3/issue/{$issueKey}/assignee";
        $dataAsignar = ['accountId' => $assigneeId];

        Log::info("PASO 1 - Asignando: {$urlAsignar}");
        Log::info("Data asignación: " . json_encode($dataAsignar));

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $urlAsignar,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS => json_encode($dataAsignar),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Basic ' . $auth,
                'Accept: application/json'
            ],
            CURLOPT_TIMEOUT => 30,
        ]);

        $responseAsignar = curl_exec($ch);
        $httpCodeAsignar = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        Log::info("HTTP Code asignación: {$httpCodeAsignar}");
        Log::info("Response asignación: {$responseAsignar}");

        // Verificar que la asignación fue exitosa (204 = Success)
        if ($httpCodeAsignar !== 204) {
            throw new \Exception("Error asignando issue. HTTP: {$httpCodeAsignar}");
        }

        // ===========================================
        // PASO 2: OBTENER TRANSICIONES DISPONIBLES
        // ===========================================
        $urlTransiciones = "{$baseUrl}/rest/api/3/issue/{$issueKey}/transitions";

        Log::info("PASO 2 - Obteniendo transiciones: {$urlTransiciones}");

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $urlTransiciones,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Basic ' . $auth,
                'Accept: application/json'
            ],
            CURLOPT_TIMEOUT => 30,
        ]);

        $responseTransiciones = curl_exec($ch);
        $httpCodeTransiciones = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCodeTransiciones !== 200) {
            throw new \Exception("Error obteniendo transiciones. HTTP: {$httpCodeTransiciones}");
        }

        $transiciones = json_decode($responseTransiciones, true);

        // Buscar el ID de la transición "En Progreso"
        $transitionId = null;
        foreach ($transiciones['transitions'] as $transition) {
            if (stripos($transition['name'], 'En Progreso') !== false ||
                stripos($transition['name'], 'In Progress') !== false ||
                stripos($transition['to']['name'], 'En Progreso') !== false ||
                stripos($transition['to']['name'], 'In Progress') !== false) {
                $transitionId = $transition['id'];
                Log::info("Transición encontrada: {$transition['name']} -> ID: {$transitionId}");
                break;
            }elseif(stripos($transition['name'], 'Analizar cambio') !== false ||
                stripos($transition['to']['name'], 'En revisión') !== false){
                $transitionId = $transition['id'];
                Log::info("Transición encontrada: {$transition['name']} -> ID: {$transitionId}");
                break;
            }
        }

        if (!$transitionId) {
            Log::warning("No se encontró transición 'En Progreso'. Transiciones disponibles:");
            foreach ($transiciones['transitions'] as $t) {
                Log::warning("- {$t['name']} (ID: {$t['id']}) -> to: {$t['to']['name']}");
            }
            throw new \Exception("No se encontró la transición 'En Progreso' para este issue");
        }

        // ===========================================
        // PASO 3: EJECUTAR LA TRANSICIÓN
        // ===========================================
        $urlEjecutar = "{$baseUrl}/rest/api/3/issue/{$issueKey}/transitions";
        $dataTransicion = [
            'transition' => ['id' => $transitionId]
        ];

        Log::info("PASO 3 - Ejecutando transición: {$urlEjecutar}");
        Log::info("Data transición: " . json_encode($dataTransicion));

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $urlEjecutar,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($dataTransicion),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Basic ' . $auth,
                'Accept: application/json'
            ],
            CURLOPT_TIMEOUT => 30,
        ]);

        $responseTransicion = curl_exec($ch);
        $httpCodeTransicion = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        Log::info("HTTP Code transición: {$httpCodeTransicion}");
        Log::info("Response transición: {$responseTransicion}");

        // Verificar que la transición fue exitosa (204 = Success)
        if ($httpCodeTransicion !== 204) {
            throw new \Exception("Error ejecutando transición. HTTP: {$httpCodeTransicion}");
        }

        // ===========================================
        // TODO EXITOSO
        // ===========================================
        return [
            'success' => true,
            'issueKey' => $issueKey,
            'assigneeId' => $assigneeId,
            'status' => 'En Progreso',
            'message' => 'Issue asignado y movido a En Progreso correctamente'
        ];

    } catch (\Exception $e) {
        Log::error("Error en proceso para issue {$issueKey}: " . $e->getMessage());
        throw $e;
    }
   }

}
