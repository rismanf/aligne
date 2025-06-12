<?php

namespace App\Livewire\Admin\Invoice;

use App\Models\Invoice;
use App\Models\Participant;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Mary\Traits\Toast;

class ListInvoice extends Component
{
    use Toast;

    public $invoice_id,
        $invoice_code,
        $invoice_status,
        $total_price,
        $total_participant,
        $detail_participants = [];

    public bool $detailModal = false;

    public function showDetailModal($invoice_id)
    {
        $invoice = Invoice::find($invoice_id);
        if (!$invoice) {
            $this->error('Error', 'Invoice not found.');
            return;
        }
        $this->total_participant = $invoice->total_participants;
        $this->total_price = $invoice->total_price;
        $this->detail_participants = $invoice->participants->map(function ($participant) {
            return [
                'full_name' => $participant->full_name,
                'email' => $participant->email,
                'status' => $participant->status,
            ];
        })->toArray();

        $this->invoice_id = $invoice_id;
        $this->invoice_code = $invoice->invoice_code;
        $this->invoice_status = $invoice->status;


        $this->detailModal = true;
    }

    public function confirm($id)
    {
        $invoice = Invoice::find($id);

        if (!$invoice) {
            $this->error('Error', 'Invoice not found.');
            return;
        }

        $invoice->update([
            'status' => 'Waiting Payment Confirmation',
            'updated_by_id' => Auth::id(),
        ]);
        $participants = Participant::where('invoice_code', $invoice->invoice_code)->get();

        $participants->each(function ($participant) {
            $participant->update([
                'status' => 'Waiting Payment Confirmation',
            ]);
        });

        $this->success(
            'Success',
            'Invoice Confirm successfully!',
            redirectTo: route('admin.invoice.index')
        );
    }

    public function approve($id)
    {
        $invoice = Invoice::find($id);

        if (!$invoice) {
            $this->error('Error', 'Invoice not found.');
            return;
        }

        $invoice->update([
            'status' => 'paid',
            'updated_by_id' => Auth::id(),
        ]);
        $participants = Participant::where('invoice_code', $invoice->invoice_code)->get();

        $participants->each(function ($participant) {
            $participant->update([
                'status' => 'paid',
            ]);
        });

        $this->success(
            'Success',
            'Invoice Paid successfully!',
            redirectTo: route('admin.invoice.index')
        );
    }
    public function render()
    {
        $title = 'Invoice Management';
        $breadcrumbs = [
            [
                'link' => route("admin.home"), // route('home') = nama route yang ada di web.php
                'label' => 'Home', // label yang ditampilkan di breadcrumb
                'icon' => 's-home',
            ],
            [
                // 'link' => route("admin.role.index"), // route('home') = nama route yang ada di web.php
                'label' => 'Invoice',
            ],
        ];

        $id_user = Auth::id();

        if (Auth::user()->hasRole('User')) {
            $invoice = Invoice::where('created_by_id', $id_user)->paginate(10);
        } else {
            $invoice = Invoice::paginate(10);
        }
        $invoice->getCollection()->transform(function ($val, $index) use ($invoice) {
            $val->row_number = ($invoice->currentPage() - 1) * $invoice->perPage() + $index + 1;
            return $val;
        });
        // Uncomment the line below to debug the total price

        $t_headers = [
            ['key' => 'row_number', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'invoice_code', 'label' => 'Invoice'],
            ['key' => 'total_participants', 'label' => 'Total Participants'],
            ['key' => 'total_price', 'label' => 'Price'],
            ['key' => 'status', 'label' => 'Status'],
            ['key' => 'action', 'label' => 'Action', 'class' => 'justify-center w-1'],
        ];

        return view('livewire.admin.invoice.list-invoice', [
            't_headers' => $t_headers,
            'invoice' => $invoice,
            'id_user' => $id_user,
        ])->layout('components.layouts.app', [
            'breadcrumbs' => $breadcrumbs,
            'title' => $title,
        ]);
    }
}
