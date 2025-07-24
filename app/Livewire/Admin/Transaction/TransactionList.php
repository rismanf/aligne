<?php

namespace App\Livewire\Admin\Transaction;

use App\Models\ClassMembership;
use App\Models\UserKuota;
use App\Models\UserProduk;
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
        ['id' => 'Paid', 'name' => 'Paid'],
        ['id' => 'waiting payment confirmation', 'name' => 'waiting payment confirmation'],
        ['id' => 'Unpaid', 'name' => 'Unpaid']
    ];
    public function render()
    {
        $title = 'Transaction Management';
        $breadcrumbs = [
            [
                'link' => route("admin.home"), // route('home') = nama route yang ada di web.php
                'label' => 'Home', // label yang ditampilkan di breadcrumb
                'icon' => 's-home',
            ],
            [
                // 'link' => route("admin.role.index"), // route('home') = nama route yang ada di web.php
                'label' => 'Transaction',
            ],
        ];

        $query = UserProduk::orderBy('created_at', 'desc');

        if ($this->select_status) {
            $query->where('payment_status', $this->select_status);
        }
        $transaction = $query->paginate(5);

        $transaction->getCollection()->transform(function ($val, $index) use ($transaction) {
            $val->row_number = ($transaction->currentPage() - 1) * $transaction->perPage() + $index + 1;
            return $val;
        });


        $t_headers = [
            ['key' => 'row_number', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'invoice_number', 'label' => 'Avatar'],
            ['key' => 'total_price', 'label' => 'Name'],
            ['key' => 'payment_status', 'label' => 'title'],
            ['key' => 'updated_at', 'label' => 'Updated At'],
            ['key' => 'action', 'label' => 'Action', 'class' => 'justify-center w-1'],
        ];

        return view('livewire.admin.transaction.transaction-list', [
            't_headers' => $t_headers,
            'userproduct' => $transaction,
        ])->layout('components.layouts.app', [
            'breadcrumbs' => $breadcrumbs,
            'title' => $title,
        ]);
    }

    public function showEditModal($id)
    {
        $data = UserProduk::with('product', 'user')->find($id);
        $this->id = $data->id;
        $this->invoice_number = $data->invoice_number;
        $this->name = $data->user->name;
        $this->product = $data->product->name;
        $this->payment_method = $data->payment_method;
        $this->payment_proof = $data->payment_proof;
        $this->total_price = $data->total_price;
        $this->paid_at = $data->paid_at;
        $this->editForm = true;
    }

    public function update()
    {
        $data = UserProduk::with('product', 'user')->find($this->id);
        $data_kuota = ClassMembership::where('membership_id', $data->product_id)->get();

        if ($data->product->valid_until == null) {
            $valid = '9999-01-01';
        } else {
            $valid = now()->addDays($data->product->valid_until)->format('Y-m-d');
        }
        foreach ($data_kuota as $item) {
            $tes = UserKuota::create([
                'user_id' => $data->user_id,
                'product_id' => $data->product_id,
                'class_id' => $item->class_id,
                'kuota' => $item->quota,
                'invoice_number' => $data->invoice_number,
                'start_date' => now(),
                'end_date' => $valid,
            ]);
        }

        // dd($tes);
        $data->update([
            'payment_status' => 'Paid',
            'confirmed_at' => now(),
            'confirmed_by' => auth()->id(),
        ]);

        $this->reset();
        $this->editForm = false;

        $this->toast(
            type: 'success',
            title: 'Payment Confirmed',               // optional (text)
            description: null,                  // optional (text)
            position: 'toast-top toast-end',    // optional (daisyUI classes)
            icon: 'o-information-circle',       // Optional (any icon)
            css: 'alert-info',                  // Optional (daisyUI classes)
            timeout: 3000,                      // optional (ms)
            redirectTo: null                    // optional (uri)
        );
    }
}
