<?php

namespace App\Livewire\Admin\Transaction;

use App\Models\ClassMembership;
use App\Models\UserKuota;
use App\Models\UserMembership;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class TransactionList extends Component
{
    use Toast, WithPagination;

    public $editForm = false;

    public $id, $invoice_number, $name, $product, $total_price, $payment_method, $payment_proof, $paid_at;
    public $select_status;
    public $status_list = [
        ['id' => 'paid', 'name' => 'Paid'],
        ['id' => 'unpaid', 'name' => 'Unpaid'],
        ['id' => 'pending', 'name' => 'Pending Confirmation']
    ];

    public function render()
    {
        $title = 'Transaction Management';
        $breadcrumbs = [
            [
                'link' => route("admin.home"),
                'label' => 'Home',
                'icon' => 's-home',
            ],
            [
                'label' => 'Transaction',
            ],
        ];

        $query = UserMembership::with(['user', 'membership'])
            ->orderBy('created_at', 'desc');

        if ($this->select_status) {
            $query->where('payment_status', $this->select_status);
        }

        $transactions = $query->paginate(10);

        $transactions->getCollection()->transform(function ($val, $index) use ($transactions) {
            $val->row_number = ($transactions->currentPage() - 1) * $transactions->perPage() + $index + 1;
            return $val;
        });

        $t_headers = [
            ['key' => 'row_number', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'invoice_number', 'label' => 'Invoice'],
            ['key' => 'user.name', 'label' => 'Customer'],
            ['key' => 'membership.name', 'label' => 'Package'],
            ['key' => 'total_price', 'label' => 'Total'],
            ['key' => 'payment_status', 'label' => 'Status'],
            ['key' => 'created_at', 'label' => 'Date'],
            ['key' => 'action', 'label' => 'Action', 'class' => 'justify-center w-1'],
        ];

        return view('livewire.admin.transaction.transaction-list', [
            't_headers' => $t_headers,
            'transactions' => $transactions,
        ])->layout('components.layouts.app', [
            'breadcrumbs' => $breadcrumbs,
            'title' => $title,
        ]);
    }

    public function showEditModal($id)
    {
        $data = UserMembership::with(['membership', 'user'])->find($id);
        
        if (!$data) {
            $this->toastError('Transaction not found.');
            return;
        }

        $this->id = $data->id;
        $this->invoice_number = $data->invoice_number;
        $this->name = $data->user->name;
        $this->product = $data->membership->name;
        $this->payment_method = $data->payment_method;
        $this->payment_proof = $data->payment_proof;
        $this->total_price = $data->total_price;
        $this->paid_at = $data->paid_at;
        $this->editForm = true;
    }

    public function update()
    {
        $membership = UserMembership::with(['membership', 'user'])->find($this->id);
        
        if (!$membership) {
            $this->toastError('Transaction not found.');
            return;
        }

        // Check if already confirmed
        if ($membership->payment_status === 'paid') {
            $this->toastError('This transaction has already been confirmed.');
            return;
        }

        try {
            // Activate the membership (this will create user quotas automatically)
            $membership->activate();

            $this->reset();
            $this->editForm = false;

            $this->toast(
                type: 'success',
                title: 'Payment Confirmed Successfully',
                description: 'User membership has been activated and quotas have been allocated.',
                position: 'toast-top toast-end',
                icon: 'o-check-circle',
                css: 'alert-success',
                timeout: 3000,
                redirectTo: null
            );

        } catch (\Exception $e) {
            $this->toastError('Failed to confirm payment: ' . $e->getMessage());
        }
    }

    public function rejectPayment($id)
    {
        $membership = UserMembership::find($id);
        
        if (!$membership) {
            $this->toastError('Transaction not found.');
            return;
        }

        $membership->update([
            'payment_status' => 'rejected',
            'status' => 'rejected',
            'confirmed_by' => auth()->id(),
            'confirmed_at' => now(),
        ]);

        $this->toast(
            type: 'warning',
            title: 'Payment Rejected',
            description: 'The payment has been rejected.',
            position: 'toast-top toast-end',
            icon: 'o-x-circle',
            css: 'alert-warning',
            timeout: 3000,
            redirectTo: null
        );
    }

    public function getStatusBadgeClass($status)
    {
        return match($status) {
            'paid' => 'badge-success',
            'unpaid' => 'badge-error',
            'pending' => 'badge-warning',
            'rejected' => 'badge-error',
            default => 'badge-neutral'
        };
    }
}
