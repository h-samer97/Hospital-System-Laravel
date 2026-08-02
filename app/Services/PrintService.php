<?php

namespace App\Services;

use App\Models\PrintLog;
use Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Log;

class PrintService
{

    public function __construct()
    {
        //
    }

    public function generateSigneURL(Model $document, string $routeName): string
    {
        // Determine route parameter name from route definition when possible
        try {
            $route = app('router')->getRoutes()->getByName($routeName);
            $paramNames = $route ? $route->parameterNames() : [];
        } catch (\Throwable $e) {
            $paramNames = [];
        }

        $params = [];
        if (!empty($paramNames)) {
            $params[$paramNames[0]] = $document->id;
        } else {
            $params[strtolower(class_basename($document))] = $document->id;
        }

        return URL::temporarySignedRoute(
            $routeName,
            now()->addMinute(30),
            $params
        );
    }

    public function logPrint(Model $document, Request $request, string $action = 'view'): PrintLog
    {

        $admin = Auth::guard('admins')->user();

        $log = PrintLog::create([
            'printable_type' => $document::class,
            'printable_id'  => $document->id,
            'admin_id' => $admin ? $admin->id : null,
            'action' => $action,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        \activity($document->getTable())
            ->performedOn($document)
            ->causedBy($admin)
            ->withProperties([
                'action'     => $action,
                'ip_address' => $request->ip(),
            ])->log('Admin printed ' . get_class($document) . " #{$document->id}");

        Log::channel('finance')->info('Document Print', [
            'type'       => class_basename($document),
            'id'         => $document->id,
            'admin_id'   => $admin->id,
            'action'     => $action,
            'ip'         => $request->ip(),
        ]);

        return $log;
    }

    /**
     * Alias for backward compatibility with existing typo usage.
     */
    public function logPring(Model $document, Request $request, string $action = 'view'): PrintLog
    {
        return $this->logPrint($document, $request, $action);
    }

    public function generatePDF(Model $document, string $view): \Illuminate\Http\Response
    {

        $pdf = Pdf::loadView($view, [
            'document' => $document,
            'print_date' => now()->format('Y-m-d H:i:s')
        ])->setPaper('a4')
            ->setOption('defaultFont', 'cairo')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true);

        return $pdf->download(
            \class_basename($document) . "_{$document->id}.pdf"
        );
    }
}
