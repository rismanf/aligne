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
        .class-detail-container {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 2rem 0;
        }

        .class-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .class-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #2d3748;
            text-align: center;
            margin-bottom: 0.5rem;
        }

        .class-subtitle {
            text-align: center;
            color: #718096;
            font-size: 1.1rem;
        }

        .week-navigation {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .nav-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .nav-btn {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .nav-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .nav-btn:disabled {
            background: #e2e8f0;
            color: #a0aec0;
            cursor: not-allowed;
            transform: none;
        }

        .week-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: #2d3748;
            margin: 0;
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 0.5rem;
        }

        .day-card {
            background: white;
            border-radius: 12px;
            padding: 1rem 0.5rem;
            text-align: center;
            text-decoration: none;
            color: inherit;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
        }

        .day-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            border-color: #667eea;
        }

        .day-card.selected {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        .day-name {
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
            opacity: 0.7;
        }

        .day-number {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .schedule-indicator {
            width: 8px;
            height: 8px;
            background: #48bb78;
            border-radius: 50%;
            margin: 0 auto;
            opacity: 0.8;
        }

        .selected .schedule-indicator {
            background: rgba(255, 255, 255, 0.8);
        }

        .schedules-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .schedule-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border-left: 4px solid #667eea;
            transition: all 0.3s ease;
        }

        .schedule-card:hover {
            transform: translateX(5px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }

        .schedule-time {
            font-size: 1.5rem;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 0.5rem;
        }

        .schedule-class {
            font-size: 1.2rem;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 0.3rem;
        }

        .schedule-level {
            color: #718096;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .schedule-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .trainer-info {
            color: #4a5568;
            font-weight: 500;
        }

        .capacity-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #718096;
        }

        .capacity-bar {
            width: 60px;
            height: 6px;
            background: #e2e8f0;
            border-radius: 3px;
            overflow: hidden;
        }

        .capacity-fill {
            height: 100%;
            background: linear-gradient(90deg, #48bb78, #38a169);
            transition: width 0.3s ease;
        }

        .book-btn {
            padding: 0.75rem 2rem;
            border-radius: 25px;
            border: none;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .book-btn.available {
            background: linear-gradient(135deg, #48bb78, #38a169);
            color: white;
        }

        .book-btn.available:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(72, 187, 120, 0.4);
        }

        .book-btn.full {
            background: #fed7d7;
            color: #c53030;
        }

        .book-btn.expired {
            background: #e2e8f0;
            color: #a0aec0;
        }

        .no-schedule {
            text-align: center;
            padding: 3rem;
            color: #718096;
        }

        .no-schedule-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        @media (max-width: 768px) {
            .calendar-grid {
                grid-template-columns: repeat(4, 1fr);
                gap: 0.3rem;
            }
            
            .day-card {
                padding: 0.8rem 0.3rem;
            }
            
            .class-title {
                font-size: 2rem;
            }
            
            .schedule-info {
                flex-direction: column;
                align-items: flex-start;
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
