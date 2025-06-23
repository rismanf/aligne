<div>
    <section id="hero2" class="hero banner wrapper__relative">
        <div class="bg__img wrapper__absolute">
            <img src="/assets/img/banner-2-bg.webp" alt="Banner" />
        </div>
        <div class="container">
            <div class="section__content">
                <div class="section__heading section__heading--lg text-center text--white">
                    <h2>NeutraDC Summit 2025</h2>
                    <h3>
                        Be the first to know about our upcoming events,
                        <br />
                        industry trends, and exclusive insights just for
                        you.
                    </h3>
                </div>
            </div>
        </div>
    </section>

    <section id="contact" class="contact">
        <div class="container">
            <div class="section__content">
                <div class="form__wrapper bg__danger bg__danger--linear">
                    <form wire:submit="save" class="row g-4">
                        <div class="col-md-12 text-center">
                            <h2 class="text-sm font-semibold mb-2">Select the pass you are applying for</h2>
                            <label class="flex items-center space-x-2 mb-2">
                                <input type="radio" name="user_type" value="1" onclick="showDetails(1)"
                                    class="form-check-input" />
                                <span>End User</span>
                            </label><br>
                            <label class="flex items-center space-x-2 mb-2">
                                <input type="radio" name="user_type" value="2" onclick="showDetails(2)"
                                    class="form-check-input" />
                                <span>General Admission pass</span>
                            </label><br>
                            <label class="flex items-center space-x-2 mb-2">
                                <input type="radio" name="user_type" value="3" onclick="showDetails(3)"
                                    class="form-check-input" />
                                <span>Vendor Pass</span>
                            </label>
                        </div>
                        <div id="detail_pass_enduser" class="pass-detail" style="display: none;">
                            You have selected an End-User /Specifier Pass, which is free to those who qualify.
                            This pass provides you with access to both events taking place on June 16-18: DCD>Connect |
                            Asia Pacific - Bali and DCD>Connect | Investment.
                            Both events are co-located at the Grand Hyatt Bali, Nusa Dua.
                        </div>
                        <div id="detail_pass_general" class="pass-detail" style="display: none;">
                            Access to both co-located events on June 16-18: DCD>Connect | Asia Pacific - Bali and
                            DCD>Connect | Investment
                            ✅ Invitations to private activities including hosted lunches, cocktail hours and tech
                            exchange sessions (subject to availability)
                            ✅ Ability to book meetings through the event app
                            ✅ Complimentary food & beverages throughout the event
                        </div>
                        <div id="detail_pass_vendor" class="pass-detail" style="display: none;">
                            You have selected a Vendor Pass, which costs $1,795.
                            This pass provides you access to both events taking place on June 16-18: DCD>Connect | Asia
                            Pacific - Bali and DCD>Connect | Investment.
                            Both events are co-located at the Grand Hyatt Bali, Nusa Dua.
                        </div>
                        <hr>
                        <div class="col-md-6">
                            <input id="contactNameFirst" name="first_name" wire:model="first_name" type="text"
                                class="form-control" placeholder="First Name" aria-label="First Name" required />
                            @error('first_name')
                                <span class="text">*{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <input id="contactNameLast" name="last_name" wire:model="last_name" type="text"
                                class="form-control" placeholder="Last Name" aria-label="Last Name" required />
                            @error('last_name')
                                <span class="text">*{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <input id="contactCompany" name="company" wire:model="company" type="text"
                                class="form-control" placeholder="Company Name" aria-label="Company Name" required />
                            @error('company')
                                <span class="text">*{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <input id="contactJob" name="job" wire:model="job" type="text" class="form-control"
                                placeholder="Job Title" aria-label="Job Title" required />
                            @error('job')
                                <span class="text">*{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <input id="contactCountry" name="country" wire:model="country" type="text"
                                class="form-control" placeholder="Country" aria-label="Country" required />
                            @error('country')
                                <span class="text">*{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <input id="contactPhone" name="phone" wire:model="phone" type="text"
                                inputmode="numeric" pattern="[0-9]+" class="form-control" placeholder="Phone Number"
                                aria-label="Phone Number" required />
                            @error('phone')
                                <span class="text">*{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-12">
                            <input id="contactEmail" name="email" wire:model="email" type="email"
                                class="form-control" placeholder="Email" aria-label="Email" required />
                            @error('email')
                                <span class="text">*{{ $message }}</span>
                            @enderror
                        </div>
                        {{-- <div class="col-md-6">
                            <input id="password" name="password" wire:model="password" type="password"
                                class="form-control" placeholder="Password" aria-label="password" required />
                            @error('password')
                                <span class="text">*{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <input id="passwordConfirm" name="password_confirmation"
                                wire:model="password_confirmation" type="password" class="form-control"
                                placeholder="Password Confirm" aria-label="password confirm" required />
                            @error('password_confirmation')
                                <span class="text">*{{ $message }}</span>
                            @enderror
                        </div> --}}

                        <hr class="border-gray-300 my-4" />
                        <div>
                            You have selected an End-User /Specifier Pass, which is free to those who qualify.
                            This pass provides you with access to both events taking place on June 16-18:
                            DCD>Connect |
                            Asia Pacific - Bali and DCD>Connect | Investment.
                            Both events are co-located at the Grand Hyatt Bali, Nusa Dua.
                        </div>
                        @foreach ($questions as $question)
                            <div class="mb-4">
                                @error("answers.{$question->id}")
                                    <p class="text-sm">*{{ $message }}</p>
                                @enderror
                                <h2 class="text-sm font-semibold mb-2">{{ $question->question }}</h2>
                                @if ($question->question_type === 'multiple')
                                    @foreach ($question->options as $option)
                                        <label class="flex items-center space-x-2 mb-2">
                                            <input type="checkbox"
                                                wire:model="answers.{{ $question->id }}.{{ $option->id }}"
                                                class="form-check-input" />
                                            <span>{{ $option->option }}</span>
                                        </label><br>
                                    @endforeach
                                @else
                                    @foreach ($question->options as $option)
                                        <label class="flex items-center space-x-2 mb-2">
                                            <input type="radio" name="question_{{ $question->id }}"
                                                value="{{ $option->id }}" wire:model="answers.{{ $question->id }}"
                                                class="form-check-input" />
                                            <span>{{ $option->option }}</span>
                                        </label><br>
                                    @endforeach
                                @endif


                                <hr class="border-gray-300 my-4" />
                            </div>
                        @endforeach
                        <div class="col-12">
                            <button label="save" type="submit" class="btn btn__primary" spinner="save">
                                Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script>
        function showDetails(passType) {
            // Sembunyikan semua detail pass
            const details = document.querySelectorAll('.pass-detail');
            details.forEach(detail => {
                detail.style.display = 'none';
            });
            // Tampilkan detail yang sesuai
            if (passType === 1) {
                document.getElementById('detail_pass_enduser').style.display = 'block';
            } else if (passType === 2) {
                document.getElementById('detail_pass_general').style.display = 'block';
            } else if (passType === 3) {
                document.getElementById('detail_pass_vendor').style.display = 'block';
            }
        }
    </script>
</div>
