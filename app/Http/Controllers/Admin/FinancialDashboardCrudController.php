<?php

namespace app\Http\Controllers\Admin;

use app\Http\Requests\FinancialDashboardRequest;
use Illuminate\Support\Facades\DB;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class FinancialDashboardCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class FinancialDashboardCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */ public function setup()
    {
        CRUD::setModel(\App\Models\SubscriptionPayment::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/financial');
        CRUD::setEntityNameStrings('payment', 'financial dashboard');
    }

    protected function setupListOperation()
    {
        // Disable create/update/delete for dashboard
        CRUD::denyAccess(['create', 'edit', 'delete']);
        
        // Add summary cards at the top
        $this->addSummaryCards();
        
        // Payment list
        CRUD::column('transaction_id')->label('Transaction ID');
        CRUD::column('user_id')->label('User')
            ->type('relationship')
            ->attribute('name');
        CRUD::column('amount_paid')->label('Amount')
            ->type('number')
            ->prefix('$')
            ->decimals(2);
        CRUD::column('payment_method')->label('Method');
        CRUD::column('status')->label('Status')
            ->type('enum');
        CRUD::column('paid_at')->label('Date')
            ->type('datetime');
    }

    protected function addSummaryCards()
    {
        // Calculate metrics
        $revenue = DB::table('subscription_payments')
            ->where('status', 'completed')
            ->sum('amount_paid');
            
        $activeSubs = DB::table('user_subscriptions')
            ->where('ends_at', '>', now())
            ->orWhereNull('ends_at')
            ->count();
            
        $mrr = DB::table('subscription_payments')
            ->where('status', 'completed')
            ->whereMonth('paid_at', now()->month)
            ->sum('amount_paid');
            
        // Add to CRUD data
        $this->data['financialMetrics'] = [
            'total_revenue' => $revenue,
            'active_subscriptions' => $activeSubs,
            'mrr' => $mrr,
            'arpu' => $activeSubs > 0 ? $mrr / $activeSubs : 0
        ];
    }
}
