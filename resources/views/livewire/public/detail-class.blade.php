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
        <div class="nav">
            {{-- <a href="{{ route('schedule', ['date' => $prev]) }}">&lt;</a> --}}
            <a href="">&lt;</a>
            <h2>{{ $days->first()->format('M') }} - {{ $days->last()->format('M Y') }}</h2>
            <a href="">&gt;</a>
            {{-- <a href="{{ route('schedule', ['date' => $next]) }}">&gt;</a> --}}
        </div>

        <div class="calendar">
            @foreach ($days as $day)
                <a href="{{ route('detail-class', ['id' => 1, 'date' => $day->toDateString()]) }}" class="day">
                    <div class="day-name">{{ $day->format('D') }}</div>
                    <div class="date {{ $day->format('Y-m-d') === $date ? 'today' : '' }}">
                        {{ $day->format('j') }}
                    </div>
                    <div class="dot"></div>
                </a>
            @endforeach
        </div>

    </div>
    <!-- Testimonials Section -->
    <section id="testimonials" class="testimonials section">

        <div class="container">

            <div class="row gy-4">

                <div class="col-lg-12" data-aos="fade-up" data-aos-delay="100">
                    <div class="testimonial-item">
                        <img src="assets/img/testimonials/testimonials-1.jpg" class="testimonial-img" alt="">
                        <h3>Saul Goodman</h3>
                        <h4>Ceo &amp; Founder</h4>
                        <div class="stars">
                            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                class="bi bi-star-fill"></i>
                        </div>
                        <p>
                            <i class="bi bi-quote quote-icon-left"></i>
                            <span>Proin iaculis purus consequat sem cure digni ssim donec porttitora entum suscipit
                                rhoncus. Accusantium quam, ultricies eget id, aliquam eget nibh et. Maecen aliquam,
                                risus at semper.</span>
                            <i class="bi bi-quote quote-icon-right"></i>
                        </p>
                    </div>
                </div><!-- End testimonial item -->



            </div>

        </div>

    </section><!-- /Testimonials Section -->
</div>
