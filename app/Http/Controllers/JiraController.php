<?php
// app/Http/Controllers/JiraController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\JiraServices;
use Illuminate\Support\Facades\Log;

class JiraController extends Controller
{
    protected $jiraServices;

    public function __construct(JiraServices $jiraServices)
    {
        $this->jiraServices = $jiraServices;
    }

    public function testConnection()
    {
        return response()->json($this->jiraServices->testConnection());
    }

    public function showIssue($issueKey)
    {
        $issue = $this->jiraServices->getIssue($issueKey);

        if (!$issue) {
            return response()->json([
                'success' => false,
                'error' => 'Issue no encontrado',
                'issue_key' => $issueKey
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $issue
        ]);
    }

    public function getAllIssues(Request $request)
    {
        $jql = $request->get('jql', 'project = HelpyIT ORDER BY createdDate DESC');
        $limit = min($request->get('limit', 50), 100);
        $page = max($request->get('page', 0), 0);
        $startAt = $page * $limit;

        $result = $this->jiraServices->getAllIssues($jql, $limit, $startAt);

        return response()->json($result);
    }


    public function getIssuesHelpYT(Request $request)
    {
        $jql = $request->get('jql', 'project = HelpyIT and "Request Type" NOT IN ("Incidente de Infraestructura (COB)", "Solicitud de Infraestructura (COB)") and status NOT IN (Cerrada, Cancelled, "En Progreso", "En revisión", Resuelta, "Derivada a terceros", "Derivada Soporte N2", "Derivada Soporte N 2", Rechazado, "Esperando respuesta del usuario") AND reporter in membersOf("Cobeca-usuarios-farmacia") and assignee = EMPTY order BY createdDate desc');
        $limit = min($request->get('limit', 50), 100);
        $page = max($request->get('page', 0), 0);
        $startAt = $page * $limit;

        $result = $this->jiraServices->getIssuesHelpYT($jql, $limit, $startAt);

        return response()->json($result);
    }


    public function getProjects()
    {
        $projects = $this->jiraServices->getProjects();

        return response()->json([
            'success' => true,
            'count' => count($projects),
            'data' => $projects
        ]);
    }

    public function assignIssue(Request $request, $issueKey)
{
    try {
        // Validar request
        $request->validate([
            'assigneeId' => 'required|string'
        ]);

        // Llamar al servicio para asignar el ticket
        $result = $this->jiraServices->assignIssue($issueKey, $request->assigneeId);

        return response()->json([
            'success' => true,
            'message' => 'Ticket asignado correctamente',
            'data' => $result
        ]);

    } catch (\Exception $e) {
        Log::error("Error asignando issue {$issueKey}: " . $e->getMessage());

        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
}

}
