<div>
    <style>
        .left-half {
            width: 60%;
            text-align: justify;
            color: white;
            padding: 0 20px;
            float: left;
        }

        .left-half p {
            line-height: 1.7;
            margin-bottom: 1.5em;
        }

        .left-half h2 {
            font-size: 2rem;
            margin-bottom: 1em;
            font-weight: bold;
        }
    </style>
    <section id="hero2" class="hero banner wrapper__relative">
        <div class="bg__img wrapper__absolute">
            <img src="/assets/img/banner-2-bg.webp" alt="Banner" />
        </div>
        <div class="container">
            <div class="section__content">
                <div class="section__heading section__heading--lg text-center text--white">
                    <h2 class="text-3xl font-bold mb-6">Why Join NeutraDC Summit Bali?</h2>
                </div>
                <div class="left-half">
                    <h3 class="mb-3 leading-relaxed">
                        This year, NeutraDC Summit 2025 takes you into the future of AI collaboration through an
                        immersive experience: the Theatre of AI.
                    </h3>

                    <h3 class="mb-3 leading-relaxed">
                        The summit brings together industry leaders, innovators, and policymakers to explore how
                        artificial intelligence is redefining business, infrastructure, and society. With bold ideas,
                        practical insights, and visionary conversations, the Theatre of AI provides a powerful stage to
                        uncover what’s next from data center innovation and digital sovereignty to interconnectivity and
                        sustainable AI infrastructure.
                    </h3>

                    <h3 class="mb-3 leading-relaxed">
                        Whether you're building the digital backbone of your enterprise or seeking strategic alliances,
                        NeutraDC Summit is where real collaboration begins.
                    </h3>

                    <h3 class="mb-3 leading-relaxed">
                        This is more than just exposure. It’s your opportunity to lead the conversation, shape industry
                        impact, and position your brand at the center of Indonesia’s digital future.
                    </h3>

                    <h3 class="mb-1 leading-relaxed">
                        Join us. Collaborate with us. Perform with us. </h3>
                    <h3 class="leading-relaxed">Because AI is not a solo act. It’s a
                        collaborative performance.
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

                                <input class="form-check-input" type="radio" wire:model="user_type" name="user_type"
                                    value="1" id="radio1" wire:click="showQuestions" required>
                                <label class="form-check-label" for="radio1">
                                    General Admission
                                </label>

                                <input class="form-check-input" type="radio" wire:model="user_type" name="user_type"
                                    value="2" id="radio2" wire:click="hideQuestions" >
                                <label class="form-check-label" for="radio2">
                                    Sponsor
                                </label>

                                <input class="form-check-input" type="radio" wire:model="user_type" name="user_type"
                                    value="3" id="radio3"  wire:click="hideQuestions" >
                                <label class="form-check-label" for="radio3">
                                    Partner
                                </label>
                            </div>
                        </div>
                        {{-- <div id="detail_pass_enduser" class="pass-detail" style="display: none;">
                            <p> End-customer</p>
                            You have selected an End-User /Specifier Pass, which is free to those who qualify.
                            This pass provides you with access to both events taking place on June 16-18: DCD>Connect |
                            Asia Pacific - Bali and DCD>Connect | Investment.
                            Both events are co-located at the Grand Hyatt Bali, Nusa Dua.
                        </div>
                        <div id="detail_pass_general" class="pass-detail" style="display: none;">
                            <p>General Admission</p>
                            Access to both co-located events on June 16-18: DCD>Connect | Asia Pacific - Bali and
                            DCD>Connect | Investment
                            ✅ Invitations to private activities including hosted lunches, cocktail hours and tech
                            exchange sessions (subject to availability)
                            ✅ Ability to book meetings through the event app
                            ✅ Complimentary food & beverages throughout the event
                        </div>
                        <div id="detail_pass_vendor" class="pass-detail" style="display: none;">
                            <p>Partner/Sponsor</p>
                            You have selected a Vendor Pass, which costs $1,795.
                            This pass provides you access to both events taking place on June 16-18: DCD>Connect | Asia
                            Pacific - Bali and DCD>Connect | Investment.
                            Both events are co-located at the Grand Hyatt Bali, Nusa Dua.
                        </div> --}}
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
                                <option value="" disabled="" selected="">Select your Job Position
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
                            <select id="registercountry" name="country" wire:model="country" class="form-control"
                                required>
                                <option value="" disabled selected>Select your Country</option>
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
                            <select class="form-control" id="registerindustry" name="industry" wire:model="industry"
                                required>
                                <option value="" disabled="" selected="">Select your industry</option>
                                @foreach ($industry_list as $val)
                                    <option value="{{ $val->name }}">{{ $val->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <hr class="border-gray-300 my-4" />
                        @if($questionForm)
                        <div id="detail_general_admission">

                            @foreach ($questions as $question)
                                <div class="mb-4">
                                    @error("answers.{$question->id}")
                                        <p class="text-sm">*{{ $message }}</p>
                                    @enderror
                                    <h2 class="text-sm font-semibold mb-2">{{ $question->question }}</h2>
                                    @if ($question->question == 'Logistics Support Needs:')
                                        <p class="text-sm"> Hotel: Mulia Nusa Dua Bali, starting from USD 341/night
                                            (IDR 6.000.000/night)</p>
                                        <p>Transportation: Dedicated car with driver (full-day), approx. USD 93/day
                                            (IDR 1.500.000/day)</p>
                                    @endif
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
                                                    value="{{ $option->id }}"
                                                    wire:model="answers.{{ $question->id }}"
                                                    class="form-check-input" />
                                                <span>{{ $option->option }}</span>
                                            </label><br>
                                        @endforeach
                                    @endif


                                    <hr class="border-gray-300 my-4" />
                                </div>
                            @endforeach
                        </div>
                        @endif
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

            // Tampilkan detail yang sesuai
            if (passType === 1) {
                document.getElementById('detail_general_admission').style.display = 'block';
            } else if (passType === 2) {
                document.getElementById('detail_general_admission').style.display = 'none';
            } else if (passType === 3) {
                document.getElementById('detail_general_admission').style.display = 'none';
            }
        }
    </script>
</div>
