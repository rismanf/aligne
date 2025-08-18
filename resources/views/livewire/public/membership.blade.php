<div>
    <!-- Pricing Section -->
    <section id="pricing" class="pricing section">
        <div class="container">
            <!-- Section Header -->
            <div class="section-header text-center mb-5">
                <h2>Membership Packages</h2>
                <p>Choose the perfect membership package for your fitness journey</p>
            </div>

            <!-- Category Filter -->
            <div class="row justify-content-center mb-4">
                <div class="col-lg-8">
                    <div class="text-center">
                        @foreach($categories as $key => $name)
                            <button 
                                wire:click="filterByCategory('{{ $key }}')" 
                                class="btn {{ $selectedCategory === $key ? 'btn-custom' : 'btn-outline-custom' }} mx-2 mb-2">
                                {{ $name }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Dynamic Category Sections -->
            @foreach($groupedProducts as $categoryKey => $categoryData)
                <div class="mb-5">
                    <h3 class="text-center mb-4">{{ $categoryData['display_name'] }}</h3>
                    @if($categoryData['description'])
                        <p class="text-center text-muted mb-4">{{ $categoryData['description'] }}</p>
                    @endif
                    <div class="row gy-4 justify-content-center">
                        @foreach ($categoryData['products'] as $product)
                            <div class="col-lg-4">
                                <div class="pricing-item {{ $product->is_popular  ? 'featured' : '' }}">
                                    @if($product->is_popular)
                                        <div class="popular-badge">Most Popular</div>
                                    @endif
                                    
                                    <div class="package-header">
                                        <h3>{{ $product->name }}</h3>
                                        <div class="price">
                                            <h4><sup>IDR</sup>{{ number_format($product->price, 0, ',', '.') }}</h4>
                                        </div>
                                        @if($product->valid_until)
                                            <div class="validity">{{ $product->valid_until }} Days Validity</div>
                                        @endif
                                    </div>

                                    <div class="package-content">
                                        <p class="description">{{ $product->description }}</p>
                                        
                                        <div class="class-details">
                                            <h5>Included Classes:</h5>
                                            @php
                                                $totalQuota = $product->groupClasses->first()->pivot->quota ?? 0;
                                                $signatureClasses = $product->groupClasses->whereIn('category', ['reformer', 'chair']);
                                            @endphp
                                            
                                            @if($signatureClasses->count() > 0)
                                                <div class="class-item">
                                                    <span class="class-name">Reformer / Chair Class</span>
                                                    <span class="class-quota">{{ $totalQuota }}x Classes</span>
                                                </div>
                                            @else
                                                @foreach ($product->groupClasses as $groupClass)
                                                    <div class="class-item">
                                                        <span class="class-name">{{ $groupClass->category_name }}</span>
                                                        <span class="class-quota">{{ $groupClass->pivot->quota }}x Classes</span>
                                                        {{-- <span class="class-category badge badge-{{ $groupClass->category }}">
                                                            {{ $groupClass->category_name }}
                                                        </span> --}}
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>

                                    <div class="package-footer">
                                        @auth
                                            <a href="{{ route('checkout', $product->id) }}" class="cta-btn">
                                                Select Package
                                            </a>
                                        @else
                                            <a href="{{ route('login') }}" class="cta-btn">
                                                Login to Purchase
                                            </a>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            @if($products->count() === 0)
                <div class="row">
                    <div class="col-12 text-center">
                        <div class="alert alert-info">
                            <h4>No packages available</h4>
                            <p>There are currently no membership packages available for the selected category.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- Additional Styles -->
    <style>
        .pricing-item {
            position: relative;
            background: #fff;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .pricing-item:hover {
            transform: translateY(-10px);
        }

        .pricing-item.featured {
            border: 3px solid var(--default-color, #4b2e2e);
            transform: scale(1.05);
        }

        .popular-badge {
            position: absolute;
            top: -15px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--default-color, #4b2e2e);
            color: white;
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .package-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .package-header h3 {
            color: #333;
            margin-bottom: 10px;
        }

        .price h4 {
            color: var(--default-color, #4b2e2e);
            font-size: 2.5rem;
            font-weight: bold;
            margin: 10px 0;
        }

        .validity {
            background: #f8f9fa;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            color: #666;
            display: inline-block;
        }

        .package-content {
            flex-grow: 1;
            margin-bottom: 20px;
        }

        .class-details h5 {
            color: #333;
            margin-bottom: 15px;
            font-size: 16px;
        }

        .class-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }

        .class-item:last-child {
            border-bottom: none;
        }

        .class-name {
            font-weight: 500;
            color: #333;
        }

        .class-quota {
            font-weight: bold;
            color: var(--default-color, #4b2e2e);
        }

        .class-category {
            font-size: 11px;
            padding: 2px 8px;
        }

        .badge-reformer { background-color: #28a745; }
        .badge-chair { background-color: #ffc107; color: #000; }
        .badge-functional { background-color: #dc3545; }

        .package-footer {
            text-align: center;
        }

        

        .btn-custom {
            background-color: var(--default-color, #4b2e2e);
            border-color: var(--default-color, #4b2e2e);
            color: white;
        }

        .btn-custom:hover {
            background-color: #3a2323;
            border-color: #3a2323;
            color: white;
        }

        .btn-outline-custom {
            border-color: var(--default-color, #4b2e2e);
            color: var(--default-color, #4b2e2e);
            background-color: transparent;
        }

        .btn-outline-custom:hover {
            background-color: var(--default-color, #4b2e2e);
            border-color: var(--default-color, #4b2e2e);
            color: white;
        }
    </style>
</div>
