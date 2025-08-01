<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\ClassBooking;
use App\Models\ClassFeedback as ClassFeedbackModel;
use Illuminate\Support\Facades\Auth;
use Mary\Traits\Toast;

class ClassFeedback extends Component
{
    use Toast;

    public $bookingId;
    public $booking;
    
    // Feedback form fields
    public $rating = 5;
    public $comment = '';
    public $recommend = true;
    public $is_anonymous = false;
    
    // Aspect ratings
    public $instructor_rating = 5;
    public $facility_rating = 5;
    public $cleanliness_rating = 5;
    public $atmosphere_rating = 5;
    public $difficulty_rating = 3;

    public function mount($bookingId)
    {
        $this->bookingId = $bookingId;
        $this->loadBooking();
    }

    public function loadBooking()
    {
        $this->booking = ClassBooking::with([
            'classSchedule.classes.groupClass',
            'classSchedule.trainer',
            'feedback'
        ])
        ->where('id', $this->bookingId)
        ->where('user_id', Auth::id())
        ->first();

        if (!$this->booking) {
            abort(404, 'Booking not found');
        }

        // If feedback already exists, load it
        if ($this->booking->hasFeedback()) {
            $feedback = $this->booking->feedback;
            $this->rating = $feedback->rating;
            $this->comment = $feedback->comment;
            $this->recommend = $feedback->recommend;
            $this->is_anonymous = $feedback->is_anonymous;
            
            if ($feedback->aspects) {
                $this->instructor_rating = $feedback->aspects['instructor'] ?? 5;
                $this->facility_rating = $feedback->aspects['facility'] ?? 5;
                $this->cleanliness_rating = $feedback->aspects['cleanliness'] ?? 5;
                $this->atmosphere_rating = $feedback->aspects['atmosphere'] ?? 5;
                $this->difficulty_rating = $feedback->aspects['difficulty'] ?? 3;
            }
        }
    }

    public function submitFeedback()
    {
        // Validate that user can give feedback
        if (!$this->booking->canGiveFeedback()) {
            $this->toast(
                type: 'error',
                title: 'Cannot Submit Feedback',
                description: 'You cannot submit feedback for this booking at this time.',
                position: 'toast-top toast-end',
                icon: 'o-x-circle',
                css: 'alert-error',
                timeout: 5000
            );
            return;
        }

        // Validate input
        $this->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'recommend' => 'boolean',
            'is_anonymous' => 'boolean',
            'instructor_rating' => 'required|integer|min:1|max:5',
            'facility_rating' => 'required|integer|min:1|max:5',
            'cleanliness_rating' => 'required|integer|min:1|max:5',
            'atmosphere_rating' => 'required|integer|min:1|max:5',
            'difficulty_rating' => 'required|integer|min:1|max:5',
        ]);

        try {
            // Create or update feedback
            $feedbackData = [
                'class_booking_id' => $this->booking->id,
                'user_id' => Auth::id(),
                'class_schedule_id' => $this->booking->class_schedule_id,
                'rating' => $this->rating,
                'comment' => $this->comment,
                'recommend' => $this->recommend,
                'is_anonymous' => $this->is_anonymous,
                'aspects' => [
                    'instructor' => $this->instructor_rating,
                    'facility' => $this->facility_rating,
                    'cleanliness' => $this->cleanliness_rating,
                    'atmosphere' => $this->atmosphere_rating,
                    'difficulty' => $this->difficulty_rating,
                ],
            ];

            if ($this->booking->hasFeedback()) {
                // Update existing feedback
                $this->booking->feedback->update($feedbackData);
                $message = 'Your feedback has been updated successfully!';
            } else {
                // Create new feedback
                ClassFeedbackModel::create($feedbackData);
                $message = 'Thank you for your feedback!';
            }

            $this->toast(
                type: 'success',
                title: 'Feedback Submitted',
                description: $message,
                position: 'toast-top toast-end',
                icon: 'o-check-circle',
                css: 'alert-success',
                timeout: 3000
            );

            // Reload booking to show updated feedback
            $this->loadBooking();

        } catch (\Exception $e) {
            $this->toast(
                type: 'error',
                title: 'Error',
                description: 'Failed to submit feedback: ' . $e->getMessage(),
                position: 'toast-top toast-end',
                icon: 'o-x-circle',
                css: 'alert-error',
                timeout: 5000
            );
        }
    }

    public function render()
    {
        return view('livewire.user.class-feedback')->layout('components.layouts.website', [
            'title' => 'Class Feedback',
            'description' => 'Share your experience and help us improve our classes',
            'keywords' => 'feedback, review, class, experience',
            'image' => asset('images/logo.png'),
            'url' => url()->current(),
        ]);
    }
}
