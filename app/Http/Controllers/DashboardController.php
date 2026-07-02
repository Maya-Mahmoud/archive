<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentFile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $stats = [];

        if ($user->hasPermission('documents.view')) {
            $stats['documents'] = Document::count();
            $stats['files'] = DocumentFile::count();
            $stats['storage'] = $this->formatBytes((int) DocumentFile::sum('size'));
        }

        if ($user->hasPermission('documents.create')) {
            $stats['my_documents'] = Document::where('created_by', $user->id)->count();
        }

        if ($user->hasPermission('users.manage')) {
            $stats['users'] = User::count();
        }

        if ($user->hasPermission('roles.manage')) {
            $stats['roles'] = Role::count();
        }

        $recentDocuments = collect();
        $categoryBreakdown = collect();

        if ($user->hasPermission('documents.view')) {
            $recentDocuments = Document::with('creator')->latest()->take(5)->get();

            $categoryBreakdown = Document::selectRaw('COALESCE(NULLIF(category, ""), "Uncategorized") as name, COUNT(*) as total')
                ->groupBy('name')
                ->orderByDesc('total')
                ->take(6)
                ->get();
        }

        return view('dashboard', compact('stats', 'recentDocuments', 'categoryBreakdown'));
    }

    private function formatBytes(int $bytes): string
    {
        if ($bytes >= 1073741824) {
            return round($bytes / 1073741824, 2).' GB';
        }
        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 2).' MB';
        }
        if ($bytes >= 1024) {
            return round($bytes / 1024, 2).' KB';
        }

        return $bytes.' B';
    }
}
