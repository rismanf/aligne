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
                        <div class="d-flex justify-content-center align-items-center  text-white">
                            <div class="text-center">
                                <h2 class="mb-4">Select the pass you are applying for</h2>

                                <div class="text-start" style="margin-left: 50px">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" wire:model="user_type" name="user_type" value="1"
                                            id="radio1" onclick="showDetails(1)" required>
                                        <label class="form-check-label" for="radio1">
                                            End-customer
                                        </label>
                                    </div>

                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" wire:model="user_type" name="user_type" value="2"
                                            id="radio2" onclick="showDetails(2)">
                                        <label class="form-check-label" for="radio2">
                                            General Admission
                                        </label>
                                    </div>

                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" wire:model="user_type"  name="user_type" value="3"
                                            id="radio3" onclick="showDetails(3)">
                                        <label class="form-check-label" for="radio3">
                                            Partner/Sponsor
                                        </label>
                                    </div>
                                </div>
                            </div>
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
                            <input id="registerNameFirst" name="first_name" wire:model="first_name" type="text"
                                class="form-control" placeholder="First Name" aria-label="First Name" required />
                            @error('first_name')
                                <span class="text">*{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <input id="registerNameLast" name="last_name" wire:model="last_name" type="text"
                                class="form-control" placeholder="Last Name" aria-label="Last Name" required />
                            @error('last_name')
                                <span class="text">*{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <input id="registerPhone" name="phone" wire:model="phone" type="text"
                                inputmode="numeric" pattern="[0-9]+" class="form-control" placeholder="Phone Number"
                                aria-label="Phone Number" required />
                            @error('phone')
                                <span class="text">*{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-6">
                            <input id="registerEmail" name="email" wire:model="email" type="email"
                                class="form-control" placeholder="Email" aria-label="Email" required />
                            @error('email')
                                <span class="text">*{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <input id="registerCompany" name="company" wire:model="company" type="text"
                                class="form-control" placeholder="Company Name" aria-label="Company Name" required />
                            @error('company')
                                <span class="text">*{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <select id="registerjob" name="job" wire:model="job" class="form-control" required>
                                <option value="" disabled="">Select your Job Position
                                </option>
                                @foreach ($job_list as $group => $job)
                                    <optgroup label="{{ $group }}">
                                        @foreach ($job as $val)
                                            <option value="{{ $val['name'] }}">{{ $val['name'] }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            @error('job')
                                <span class="text">*{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <select id="registercountry" name="country" wire:model="country" class="form-control" required>
                                <option value="" disabled="">Select your Country
                                </option>
                                @foreach ($country_list as $group => $country)
                                    <optgroup label="{{ $group }}">
                                        @foreach ($country as $val)
                                            <option value="{{ $val['name'] }}">{{ $val['name'] }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            @error('country')
                                <span class="text">*{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <select class="form-control" id="registerindustry" name="industry" wire:model="industry" required>
                                <option value="" disabled="" selected="">Select your industry</option>
                                @foreach ($industry_list as $val)
                                    <option value="{{ $val->name }}">{{ $val->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <hr class="border-gray-300 my-4" />
                        <div>
                            <p>We’ll also create a personal agenda of sessions for you to attend based on the events and
                                topics that most interest you (subject to availability).</p>

                            <p>To help us program your personal agenda, please answer the following questions.</p>
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
