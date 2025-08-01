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
        /* Color Palette Variables */
        :root {
            --soft-cream: #F6F4EB;
            --sandstone: #D4BDA5;
            --deep-espresso: #4B2E2E;
            --olive-mist: #A6A58A;
            --linen-rose: #CBB4A0;
        }

        .class-detail-container {
            background: linear-gradient(135deg, var(--soft-cream) 0%, var(--sandstone) 100%);
            min-height: 100vh;
            padding: 1.5rem 0;
        }

        .class-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 20px rgba(75, 46, 46, 0.1);
            border: 1px solid var(--linen-rose);
        }

        .class-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--deep-espresso);
            text-align: center;
            margin-bottom: 0.5rem;
        }

        .class-subtitle {
            text-align: center;
            color: var(--olive-mist);
            font-size: 1rem;
        }

        .week-navigation {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            padding: 1.2rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 3px 15px rgba(75, 46, 46, 0.1);
            border: 1px solid var(--linen-rose);
        }

        .nav-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .nav-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--deep-espresso);
            color: white;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .nav-btn:hover {
            background: var(--olive-mist);
            transform: scale(1.05);
        }

        .nav-btn:disabled {
            background: var(--linen-rose);
            color: #999;
            cursor: not-allowed;
            transform: none;
        }

        .week-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--deep-espresso);
            margin: 0;
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 0.4rem;
        }

        .day-card {
            background: white;
            border-radius: 10px;
            padding: 0.8rem 0.4rem;
            text-align: center;
            text-decoration: none;
            color: var(--deep-espresso);
            transition: all 0.3s ease;
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
        }

        .day-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(75, 46, 46, 0.15);
            color: var(--deep-espresso);
            border-color: var(--olive-mist);
        }

        .day-card.selected {
            background: var(--deep-espresso);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(75, 46, 46, 0.3);
        }

        .day-name {
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.4rem;
            opacity: 0.7;
        }

        .day-number {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 0.4rem;
        }

        .schedule-indicator {
            width: 6px;
            height: 6px;
            background: var(--olive-mist);
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
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 4px 20px rgba(75, 46, 46, 0.1);
            border: 1px solid var(--linen-rose);
        }

        .schedule-card {
            background: white;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 0.8rem;
            box-shadow: 0 2px 10px rgba(75, 46, 46, 0.08);
            border-left: 3px solid var(--olive-mist);
            transition: all 0.3s ease;
        }

        .schedule-card:hover {
            transform: translateX(3px);
            box-shadow: 0 4px 15px rgba(75, 46, 46, 0.12);
        }

        .schedule-time {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--deep-espresso);
            margin-bottom: 0.3rem;
        }

        .schedule-class {
            font-size: 1rem;
            font-weight: 600;
            color: var(--deep-espresso);
            margin-bottom: 0.2rem;
        }

        .schedule-level {
            color: var(--olive-mist);
            font-size: 0.8rem;
            margin-bottom: 0.8rem;
        }

        .schedule-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.8rem;
        }

        .trainer-info {
            color: var(--deep-espresso);
            font-weight: 500;
            font-size: 0.9rem;
        }

        .capacity-info {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            color: var(--olive-mist);
            font-size: 0.8rem;
        }

        .capacity-bar {
            width: 50px;
            height: 4px;
            background: var(--soft-cream);
            border-radius: 2px;
            overflow: hidden;
        }

        .capacity-fill {
            height: 100%;
            background: var(--olive-mist);
            transition: width 0.3s ease;
        }

        .book-btn {
            padding: 0.5rem 1.5rem;
            border-radius: 20px;
            border: none;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
            display: inline-block;
            font-size: 0.9rem;
        }

        .book-btn.available {
            background: var(--deep-espresso);
            color: white;
        }

        .book-btn.available:hover {
            background: var(--olive-mist);
            transform: translateY(-1px);
        }

        .book-btn.full {
            background: var(--linen-rose);
            color: var(--deep-espresso);
        }

        .book-btn.expired {
            background: var(--soft-cream);
            color: var(--olive-mist);
        }

        .no-schedule {
            text-align: center;
            padding: 2rem;
            color: var(--olive-mist);
        }

        .no-schedule-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        @media (max-width: 768px) {
            .calendar-grid {
                grid-template-columns: repeat(4, 1fr);
                gap: 0.3rem;
            }
            
            .day-card {
                padding: 0.6rem 0.3rem;
            }
            
            .class-title {
                font-size: 1.5rem;
            }
            
            .schedule-info {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }

            .schedule-card {
                padding: 0.8rem;
            }
        }
    </style>

    <!-- Class Detail Container -->
    <div class="class-detail-container">
        <div class="container">
            <!-- Class Header -->
            <div class="class-header">
                <h1 class="class-title">{{ $class_name }}</h1>
                <p class="class-subtitle">Choose your preferred class schedule</p>
            </div>

            @php
                $currentDate = \Carbon\Carbon::parse($date);
                $today = \Carbon\Carbon::today();
                $startOfCurrentWeek = $today->copy()->startOfWeek();
                $endLimit = $today->copy()->addMonth(); // batas maksimal ke depan (1 bulan)
                $prevDate = $currentDate->copy()->subWeek()->toDateString();
                $nextDate = $currentDate->copy()->addWeek()->toDateString();
            @endphp

            <!-- Week Navigation -->
            <div class="week-navigation">
                <div class="nav-header">
                    {{-- Tombol Prev: hanya aktif kalau tanggal sekarang > minggu ini --}}
                    @if ($currentDate->greaterThan($startOfCurrentWeek))
                        <a href="{{ route('detail-class', ['id' => $id, 'date' => $prevDate]) }}" class="nav-btn">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    @else
                        <button class="nav-btn" disabled>
                            <i class="fas fa-chevron-left"></i>
                        </button>
                    @endif

                    <h2 class="week-title">
                        {{ $currentDate->startOfWeek()->format('M d') }} - {{ $currentDate->endOfWeek()->format('M d, Y') }}
                    </h2>

                    {{-- Tombol Next: hanya aktif kalau currentDate < 1 bulan ke depan --}}
                    @if ($currentDate->lessThan($endLimit))
                        <a href="{{ route('detail-class', ['id' => $id, 'date' => $nextDate]) }}" class="nav-btn">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    @else
                        <button class="nav-btn" disabled>
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    @endif
                </div>

                <!-- Calendar Grid -->
                <div class="calendar-grid">
                    @foreach ($days as $day)
                        @php
                            $hasSchedule = $schedule->contains(function ($s) use ($day) {
                                return \Carbon\Carbon::parse($s->start_time)->toDateString() === $day->toDateString();
                            });
                            $isSelected = $day->format('Y-m-d') === $date;
                        @endphp
                        <a href="{{ route('detail-class', ['id' => $id, 'date' => $day->toDateString()]) }}" 
                           class="day-card {{ $isSelected ? 'selected' : '' }}">
                            <div class="day-name">{{ $day->format('D') }}</div>
                            <div class="day-number">{{ $day->format('j') }}</div>
                            @if ($hasSchedule)
                                <div class="schedule-indicator"></div>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Schedules Container -->
            <div class="schedules-container">
                @if ($schedule_now->count() > 0)
                    <h3 style="margin-bottom: 1.5rem; color: #2d3748; font-weight: 600;">
                        Available Classes for {{ \Carbon\Carbon::parse($date)->format('l, M d, Y') }}
                    </h3>
                    
                    @foreach ($schedule_now as $val)
                        @php
                            $availableSlots = $val->capacity - $val->capacity_book;
                            $scheduleDateTime = \Carbon\Carbon::parse($val->start_time);
                            $now = \Carbon\Carbon::now();
                            $diffInHours = $now->diffInHours($scheduleDateTime, false);
                            $capacityPercentage = ($val->capacity_book / $val->capacity) * 100;
                        @endphp
                        
                        <div class="schedule-card">
                            <div class="schedule-time">
                                {{ Carbon::parse($val->start_time)->format('h:i A') }}
                            </div>
                            
                            <div class="schedule-class">{{ $val->classes->name }}</div>
                            <div class="schedule-level">{{ $val->classes->level_class }}</div>
                            
                            <div class="schedule-info">
                                <div>
                                    <div class="trainer-info">
                                        <i class="fas fa-user-tie" style="margin-right: 0.5rem;"></i>
                                        {{ $val->trainer->name }}
                                    </div>
                                </div>
                                
                                <div class="capacity-info">
                                    <span>{{ $availableSlots }}/{{ $val->capacity }} available</span>
                                    <div class="capacity-bar">
                                        <div class="capacity-fill" style="width: {{ $capacityPercentage }}%"></div>
                                    </div>
                                </div>
                                
                                <div>
                                    @if ($availableSlots == 0)
                                        <span class="book-btn full">Full</span>
                                    @elseif ($scheduleDateTime->isPast())
                                        <span class="book-btn expired">Expired</span>
                                    @elseif ($diffInHours < 1)
                                        <span class="book-btn expired">Too Late</span>
                                    @else
                                        <a href="{{ route('checkout_class', $val->id) }}" class="book-btn available">
                                            Book Now
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="no-schedule">
                        <div class="no-schedule-icon">📅</div>
                        <h3>No Classes Available</h3>
                        <p>There are no classes scheduled for this date. Please select another date.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
