<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            @if($booking)
                <!-- Class Information Card -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-4">
                        <div class="text-center mb-3">
                            <h5 class="text-muted mb-2">Share Your Experience</h5>
                            <h3 class="fw-bold">{{ $booking->classSchedule->classes->name }}</h3>
                        </div>

                        <!-- Class Details -->
                        <div class="row text-center mb-3">
                            <div class="col-6">
                                <small class="text-muted d-block">Date</small>
                                <strong>{{ $booking->classSchedule->start_time->format('D, M j, Y') }}</strong>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Trainer</small>
                                <strong>{{ $booking->classSchedule->trainer->name }}</strong>
                            </div>
                        </div>

                        <!-- Class Type Badge -->
                        <div class="text-center">
                            <span class="badge 
                                @if($booking->classSchedule->classes->groupClass->name == 'REFORMER CLASS') bg-info
                                @elseif($booking->classSchedule->classes->groupClass->name == 'CHAIR CLASS') bg-success
                                @else bg-warning @endif 
                                px-3 py-2">
                                {{ $booking->classSchedule->classes->groupClass->name }}
                            </span>
                        </div>
                    </div>
                </div>

                @if($booking->canGiveFeedback() || $booking->hasFeedback())
                    <!-- Feedback Form -->
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4">
                            <form wire:submit.prevent="submitFeedback">
                                <!-- Overall Rating -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Overall Rating</label>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rating-stars">
                                            @for($i = 1; $i <= 5; $i++)
                                                <button type="button" 
                                                        wire:click="$set('rating', {{ $i }})"
                                                        class="btn btn-link p-0 me-1 {{ $rating >= $i ? 'text-warning' : 'text-muted' }}"
                                                        style="font-size: 1.5rem;">
                                                    <i class="fas fa-star"></i>
                                                </button>
                                            @endfor
                                        </div>
                                        <span class="badge bg-primary">{{ $rating }}/5</span>
                                    </div>
                                </div>

                                <!-- Detailed Aspects -->
                                <div class="mb-4">
                                    <h6 class="fw-bold mb-3">Rate Specific Aspects</h6>
                                    
                                    <!-- Instructor Rating -->
                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <label class="form-label">Instructor Quality</label>
                                        </div>
                                        <div class="col-6">
                                            <div class="d-flex align-items-center">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <button type="button" 
                                                            wire:click="$set('instructor_rating', {{ $i }})"
                                                            class="btn btn-link p-0 me-1 {{ $instructor_rating >= $i ? 'text-warning' : 'text-muted' }}"
                                                            style="font-size: 1rem;">
                                                        <i class="fas fa-star"></i>
                                                    </button>
                                                @endfor
                                                <span class="ms-2 small">{{ $instructor_rating }}/5</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Facility Rating -->
                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <label class="form-label">Facility & Equipment</label>
                                        </div>
                                        <div class="col-6">
                                            <div class="d-flex align-items-center">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <button type="button" 
                                                            wire:click="$set('facility_rating', {{ $i }})"
                                                            class="btn btn-link p-0 me-1 {{ $facility_rating >= $i ? 'text-warning' : 'text-muted' }}"
                                                            style="font-size: 1rem;">
                                                        <i class="fas fa-star"></i>
                                                    </button>
                                                @endfor
                                                <span class="ms-2 small">{{ $facility_rating }}/5</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Cleanliness Rating -->
                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <label class="form-label">Cleanliness</label>
                                        </div>
                                        <div class="col-6">
                                            <div class="d-flex align-items-center">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <button type="button" 
                                                            wire:click="$set('cleanliness_rating', {{ $i }})"
                                                            class="btn btn-link p-0 me-1 {{ $cleanliness_rating >= $i ? 'text-warning' : 'text-muted' }}"
                                                            style="font-size: 1rem;">
                                                        <i class="fas fa-star"></i>
                                                    </button>
                                                @endfor
                                                <span class="ms-2 small">{{ $cleanliness_rating }}/5</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Atmosphere Rating -->
                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <label class="form-label">Class Atmosphere</label>
                                        </div>
                                        <div class="col-6">
                                            <div class="d-flex align-items-center">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <button type="button" 
                                                            wire:click="$set('atmosphere_rating', {{ $i }})"
                                                            class="btn btn-link p-0 me-1 {{ $atmosphere_rating >= $i ? 'text-warning' : 'text-muted' }}"
                                                            style="font-size: 1rem;">
                                                        <i class="fas fa-star"></i>
                                                    </button>
                                                @endfor
                                                <span class="ms-2 small">{{ $atmosphere_rating }}/5</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Difficulty Rating -->
                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <label class="form-label">Difficulty Level</label>
                                        </div>
                                        <div class="col-6">
                                            <div class="d-flex align-items-center">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <button type="button" 
                                                            wire:click="$set('difficulty_rating', {{ $i }})"
                                                            class="btn btn-link p-0 me-1 {{ $difficulty_rating >= $i ? 'text-warning' : 'text-muted' }}"
                                                            style="font-size: 1rem;">
                                                        <i class="fas fa-star"></i>
                                                    </button>
                                                @endfor
                                                <span class="ms-2 small">{{ $difficulty_rating }}/5</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Comment -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Your Comments</label>
                                    <textarea wire:model="comment" 
                                              class="form-control" 
                                              rows="4" 
                                              placeholder="Share your experience, what you liked, or suggestions for improvement..."></textarea>
                                    <div class="form-text">Optional - Help us understand your experience better</div>
                                </div>

                                <!-- Recommendation -->
                                <div class="mb-4">
                                    <div class="form-check">
                                        <input wire:model="recommend" 
                                               class="form-check-input" 
                                               type="checkbox" 
                                               id="recommend">
                                        <label class="form-check-label fw-bold" for="recommend">
                                            I would recommend this class to others
                                        </label>
                                    </div>
                                </div>

                                <!-- Anonymous Option -->
                                <div class="mb-4">
                                    <div class="form-check">
                                        <input wire:model="is_anonymous" 
                                               class="form-check-input" 
                                               type="checkbox" 
                                               id="anonymous">
                                        <label class="form-check-label" for="anonymous">
                                            Submit feedback anonymously
                                        </label>
                                    </div>
                                    <div class="form-text">Your name won't be shown with this feedback</div>
                                </div>

                                <!-- Submit Button -->
                                <div class="d-grid gap-2">
                                    @if($booking->hasFeedback())
                                        <button type="submit" class="btn btn-warning">
                                            <i class="fas fa-edit me-2"></i>
                                            Update Feedback
                                        </button>
                                    @else
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-paper-plane me-2"></i>
                                            Submit Feedback
                                        </button>
                                    @endif
                                    
                                    <a href="{{ route('user.booking') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>
                                        Back to Bookings
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    @if($booking->hasFeedback())
                        <!-- Feedback Submitted Info -->
                        <div class="card mt-3 border-0 bg-light">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    <small class="text-muted">
                                        Feedback submitted on {{ $booking->feedback->submitted_at->format('M j, Y \a\t H:i') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    @endif

                @else
                    <!-- Cannot Give Feedback -->
                    <div class="card border-0 bg-light">
                        <div class="card-body p-4 text-center">
                            <i class="fas fa-info-circle text-muted mb-3" style="font-size: 2rem;"></i>
                            <h5 class="mb-3">Feedback Not Available</h5>
                            <p class="text-muted mb-3">
                                @if($booking->visit_status !== 'visited')
                                    You need to attend the class before you can provide feedback.
                                @elseif(now()->lt($booking->classSchedule->end_time))
                                    Feedback will be available after the class ends.
                                @elseif(now()->gt($booking->getFeedbackDeadline()))
                                    The feedback period for this class has expired.
                                @else
                                    Feedback is not available for this booking.
                                @endif
                            </p>
                            <a href="{{ route('user.booking') }}" class="btn btn-primary">
                                <i class="fas fa-arrow-left me-2"></i>
                                Back to Bookings
                            </a>
                        </div>
                    </div>
                @endif
            @else
                <!-- Booking Not Found -->
                <div class="text-center py-5">
                    <h3 class="mb-3">Booking Not Found</h3>
                    <p class="text-muted mb-4">We couldn't find the booking you're looking for.</p>
                    <a href="{{ route('user.booking') }}" class="btn btn-primary">
                        View All Bookings
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
