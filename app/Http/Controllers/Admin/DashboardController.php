<?Php
namespace app\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\AdminController;
use app\Models\User;
use app\Models\Admin;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\Artisan;

use RealRashid\SweetAlert\Facades\Alert;

class DashboardController extends AdminController
{
    public function setup()
    {
        $this->data['title'] = 'Roman Account Dashboard';
    }

    public function index()
    {
        $this->data['title'] = trans('backpack::base.dashboard');
        $this->data['breadcrumbs'] = [
            trans('backpack::crud.admin') => backpack_url('dashboard'),
            trans('backpack::base.dashboard') => false,
        ];

        $this->data['system'] = $this->getSystemMetrics();
        $this->data['users'] = $this->getUserMetrics();
        $this->data['security'] = $this->getSecurityMetrics();
        $this->data['database'] = $this->getDatabaseMetrics();
        $this->data['activities'] = $this->getRecentActivities();

        return view(backpack_view('dashboard'), $this->data);
    }

    // System Metrics
    private function getSystemMetrics()
    {
        return [
            'cache_size' => $this->getCacheSize(),
            'php_version' => phpversion(),
            'load_avg' => function_exists('sys_getloadavg') ? sys_getloadavg()[0] : 'N/A',
            //'queue_jobs' => DB::table('jobs')->count(),
            'uptime' => @exec('uptime -p') ?: 'N/A'
        ];
    }

    // User Metrics
    private function getUserMetrics()
    {
        return [
            'total' => User::count(),
            'active' => User::where('last_login', '>=', now()->subDay())->count(),
            'new_today' => User::whereDate('created_at', today())->count(),
            'banned' => User::where('is_banned', true)->count(),
            'admins' => User::where('is_admin', true)->count(),
            'unverified' => User::whereNull('email_verified_at')
                ->where('created_at', '<', now()->subDay())
                ->count()
        ];
    }

    // Security Metrics
    private function getSecurityMetrics()
    {
        return [
            'failed_logins' => DB::table('failed_login_attempts')
                ->where('created_at', '>=', now()->subDay())
                ->count(),
            'suspicious_ips' => DB::table('failed_login_attempts')
                ->select('ip_address', DB::raw('count(*) as attempts'))
                ->where('created_at', '>=', now()->subHour())
                ->groupBy('ip_address')
                ->having('attempts', '>', 5)
                ->get(),
            'banned_ips' => DB::table('banned_ips')->count()
        ];
    }

    // Database Metrics
    private function getDatabaseMetrics()
    {
        return [
            'size' => $this->getDbSize(),
            'tables' => DB::select('SHOW TABLE STATUS')
        ];
    }

    // Recent Activities (using Spatie)
    private function getRecentActivities()
    {
        return Activity::with(['causer', 'subject'])
            ->latest()
            ->limit(10)
            ->get()
            ->map(function ($activity) {
                return [
                    'description' => $activity->description,
                    'causer' => $activity->causer?->name ?? 'System',
                    'subject' => $activity->subject?->getTable() ?? 'N/A',
                    'time' => $activity->created_at->diffForHumans(),
                    'properties' => $activity->properties
                ];
            });
    }

    // Helper Methods
    private function getCacheSize()
    {
        $size = 0;
        $files = glob(storage_path('framework/cache/*'));
        foreach ($files as $file) {
            $size += filesize($file);
        }
        return round($size / 1024, 2) . ' KB';
    }

    private function getDbSize()
    {
        return DB::select("
            SELECT table_name AS `table`, 
            round(((data_length + index_length) / 1024 / 1024), 2) `size_mb` 
            FROM information_schema.TABLES 
            WHERE table_schema = ?
            ORDER BY (data_length + index_length) DESC
        ", [config('database.connections.mysql.database')]);
    }

    // Action Methods
    public function clearCache()
    {
        Artisan::call('optimize:clear');
        activity()->log('Cache cleared manually');
        Alert::success('Cache cleared successfully')->flash();
        return back();
    }

    public function blockUser($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_banned' => true]);

        DB::table('sessions')->where('user_id', $id)->delete();

        activity()
            ->causedBy(backpack_user())
            ->performedOn($user)
            ->log('User account banned');

        Alert::success('User blocked successfully')->flash();
        return back();
    }

    public function banIp($ip)
    {
        DB::table('banned_ips')->insert([
            'ip' => $ip,
            'banned_by' => backpack_user()->id,
            'reason' => 'Manual ban from dashboard'
        ]);

        activity()
            ->causedBy(backpack_user())
            ->withProperties(['ip' => $ip])
            ->log('IP address banned');

        Alert::success('IP banned successfully')->flash();
        return back();
    }

    // DashboardController.php
public function getSystemHealth()
{
    return [
        'cpu_load' => function_exists('sys_getloadavg') ? sys_getloadavg()[0] : 'N/A',
        'memory_usage' => round(memory_get_usage(true)/1048576, 2).' MB',
        'disk_space' => round(disk_free_space('/')/1073741824, 2).' GB free',
        'active_connections' => DB::select("SHOW STATUS WHERE `variable_name` = 'Threads_connected'")[0]->Value,
        'last_cron' => Cache::get('last_cron_run', 'Never')
    ];
}
}
