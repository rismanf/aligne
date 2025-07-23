<?php

namespace App\Livewire\Public;

use App\Models\Menu;
use App\Models\Schedule;
use App\Models\UserKuota;
use App\Models\UserSchedule;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CheckoutClass extends Component
{

    public $id, $schedule, $uniqueCode;
    public $data_schedule;
    public $cek_quota;
    public function mount($id)
    {
        $this->id = $id;
        $this->schedule = Schedule::with('classes', 'trainer')->find($id);
        $this->data_schedule = UserSchedule::where('schedule_id', $id)->where('user_id', Auth::id())->count();
        $quota = UserKuota::where('user_id', Auth::id())->where('product_id', $this->id)->where('is_active', 1)->get();
        $this->cek_quota = 0;
        if (!$quota->isEmpty()) {
            $c = 0;
            foreach ($quota as $item) {
                if ($item->end_date > now()) {
                    $this->cek_quota = $c + $item->kuota;
                    $c = $item->kuota;
                }
            }
        }
    }
    public function render()
    {

        $menu = Menu::where('name', 'About Us')->first();

        return view('livewire.public.checkout-class')->layout('components.layouts.website', [
            'title' => $menu->title,
            'description' => $menu->description,
            'keywords' => $menu->keywords,
            'image' => asset('images/logo.png'),
            'url' => url()->current(),
        ]);
    }

    public function save()
    {
        $this->validate([
            'id' => 'required',
        ]);

        $data_schedule = Schedule::find($this->id);

        if (!$data_schedule) {
            $this->toastError('Schedule not found.');
            return;
        }

        $code = 'R-' . strtoupper(substr(uniqid(), 0, 5)) . '-' . rand(10, 99);

        $qr = new QrCode($code);
        $writer = new PngWriter();
        $result = $writer->write($qr);

        $filename = "qrcodes/{$code}.png";

        // Simpan ke local storage (public disk)
        Storage::disk('public')->put($filename, $result->getString());

        // Dapatkan URL publik (misalnya: http://localhost:8000/storage/qrcodes/xxx.png)
        $url_code = asset('storage/' . $filename);

        UserSchedule::create(
            [
                'user_id' => Auth::id(),
                'schedule_id' => $this->id,
                'created_by_id' => Auth::id(),
                'code' => $code,
                'url_code' => $url_code
            ]
        );

        $data_schedule->update([
            'quota' => $data_schedule->quota - 1,
            'quora_book' => $data_schedule->quora_book + 1
        ]);

        session()->flash('success', 'Order booking successfully!, Please wait 3 seconds.');

        $this->js(<<<'JS'
        setTimeout(function () {
            window.location.href = "/user/booking"; // Redirect to user order page after 3 seconds
        }, 3000);
    JS);
    }
}
