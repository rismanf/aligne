<div>
    @php
        use Carbon\Carbon;

        // Ambil tanggal awal minggu dari query string jika ada
        $startOfWeek = request('date') ? Carbon::parse(request('date'))->startOfWeek() : now()->startOfWeek();

        // Buat list 7 hari dari hari Minggu
        $days = collect(range(0, 6))->map(fn($i) => $startOfWeek->copy()->addDays($i));
        $prev = $startOfWeek->copy()->subWeek()->toDateString();
        $next = $startOfWeek->copy()->addWeek()->toDateString();
    @endphp
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fdfaf5;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .wrapper {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
            box-sizing: border-box;
        }

        .nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .nav a {
            font-size: 24px;
            text-decoration: none;
            color: #333;
        }

        .nav h2 {
            margin: 0;
            font-size: 20px;
        }

        .calendar {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(80px, 1fr));
            gap: 10px;
        }

        .day {
            display: flex;
            flex-direction: column;
            align-items: center;
            min-width: 80px;
            text-decoration: none;
            color: inherit;
        }

        .day-name {
            font-weight: bold;
            color: #555;
        }

        .date {
            margin-top: 6px;
            width: 40px;
            height: 40px;
            line-height: 40px;
            border-radius: 50%;
            display: inline-block;
            box-sizing: border-box;
            background-color: transparent;
            text-align: center;
        }

        .today {
            background-color: #4b4b23;
            color: white;
        }

        .dot {
            width: 6px;
            height: 6px;
            background-color: #4b4b23;
            border-radius: 50%;
            margin-top: 6px;
        }

        @media screen and (max-width: 600px) {
            .calendar {
                grid-template-columns: repeat(4, 1fr);
            }
        }
    </style>
    <!-- Blog Posts Section -->

    <div class="wrapper">
        <h1 class="text-center">{{ $class_name }}</h1>
        @php

            $currentDate = \Carbon\Carbon::parse($date);
            $today = \Carbon\Carbon::today();

            $startOfCurrentWeek = $today->copy()->startOfWeek();
            $endLimit = $today->copy()->addMonth(); // batas maksimal ke depan (1 bulan)

            $prevDate = $currentDate->copy()->subWeek()->toDateString();
            $nextDate = $currentDate->copy()->addWeek()->toDateString();
        @endphp

        <div class="nav">
            {{-- Tombol Prev: hanya aktif kalau tanggal sekarang > minggu ini --}}
            @if ($currentDate->greaterThan($startOfCurrentWeek))
                <a href="{{ route('detail-class', ['id' => $id, 'date' => $prevDate]) }}">&lt;</a>
            @else
                <span class="text-muted">&lt;</span>
            @endif

            <h2>{{ $currentDate->startOfWeek()->format('M d') }} - {{ $currentDate->endOfWeek()->format('M d, Y') }}
            </h2>
            {{-- Tombol Next: hanya aktif kalau currentDate < 1 bulan ke depan --}}
            @if ($currentDate->lessThan($endLimit))
                <a href="{{ route('detail-class', ['id' => $id, 'date' => $nextDate]) }}">&gt;</a>
            @else
                <span class="text-muted">&gt;</span>
            @endif
        </div>

        <div class="calendar">
            @foreach ($days as $day)
                @php
                    $hasSchedule = $schedule->contains(function ($s) use ($day) {
                        return \Carbon\Carbon::parse($s->start_time)->toDateString() === $day->toDateString();
                    });
                @endphp
                <a href="{{ route('detail-class', ['id' => $id, 'date' => $day->toDateString()]) }}" class="day">
                    <div class="day-name">{{ $day->format('D') }}</div>
                    <div class="date {{ $day->format('Y-m-d') === $date ? 'today' : '' }}">
                        {{ $day->format('j') }}
                    </div>
                    @if ($hasSchedule)
                        <div class="dot"></div>
                    @endif
                </a>
            @endforeach
        </div>

    </div>
    @if ($schedule_now->count() > 0)
        <!-- Testimonials Section -->
        <section id="testimonials" class="pricing section">

            <div class="container">
                @foreach ($schedule_now as $val)
                    <div class="row gy-4">
                        <div class="col-lg-12" data-aos="fade-up" data-aos-delay="100">
                            <div class="testimonial-item">
                                <div
                                    class="border rounded p-3 bg-light d-flex justify-content-between align-items-center">
                                    <!-- Left Section -->
                    <div class="d-flex align-items-center" style="gap: 1rem;">
                        <div class="fw-bold fs-5">{{ Carbon::parse($val->start_time)->format('h:i A') }}
                        </div>
                        <div>
                            <div class="fw-semibold">{{ $val->classes->name }}</div>
                            <div class="fw-muted">{{ $val->classes->level_class }}</div>
                        </div>
                    </div>

                    <!-- Middle Section -->
                    <div class="text-center">
                        <div class="fw-muted ">Trainer : {{ $val->trainer->name }}</div>
                        <div class="text-muted mb-1">Available : {{ $val->capacity - $val->capacity_book }}/{{ $val->capacity }}</div>
                    </div>

                                    <!-- Right Section -->

                    @php
                        $availableSlots = $val->capacity - $val->capacity_book;
                        $scheduleDateTime = \Carbon\Carbon::parse($val->start_time);
                        $now = \Carbon\Carbon::now();
                        $diffInHours = $now->diffInHours($scheduleDateTime, false);
                    @endphp

                    @if ($availableSlots == 0)
                        <div class="text-end">
                            <a href="#" class="btn btn-danger rounded-pill px-4">Full</a>
                        </div>
                    @elseif ($scheduleDateTime->isPast())
                        <div class="text-end">
                            <a href="#" class="btn btn-danger rounded-pill px-4">Expired</a>
                        </div>
                    @elseif ($diffInHours < 1)
                        <div class="text-end">
                            <a href="#" class="btn btn-warning rounded-pill px-4">Too Late</a>
                        </div>
                    @else
                        <div class="text-end">
                            <a href="{{ route('checkout_class', $val->id) }}"
                                class="btn btn-primary rounded-pill px-4">Book Now</a>
                        </div>
                    @endif

                                </div>
                            </div>
                        </div><!-- End testimonial item -->
                    </div>
                @endforeach
            </div>

        </section><!-- /Testimonials Section -->
    @else
        <!-- Testimonials Section -->
        <section id="testimonials" class="testimonials section">

            <div class="container">

                <div class="row gy-4">

                    <div class="col-lg-12" data-aos="fade-up" data-aos-delay="100">
                        <div class="testimonial-item">
                            <h3>No Schedule</h3>
                        </div>
                    </div><!-- End testimonial item -->



                </div>

            </div>

        </section><!-- /Testimonials Section -->
    @endif

</div>
