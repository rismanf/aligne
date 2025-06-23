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
                                        <input class="form-check-input" type="radio" name="user_type" value="1"
                                            id="radio1" onclick="showDetails(1)">
                                        <label class="form-check-label" for="radio1">
                                            End-customer
                                        </label>
                                    </div>

                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="user_type" value="2"
                                            id="radio2" onclick="showDetails(2)">
                                        <label class="form-check-label" for="radio2">
                                            General Admission
                                        </label>
                                    </div>

                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="user_type" value="3"
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
                            <select name="job_position" class="form-control">
                                 <option value="" disabled="" selected="">Select your Job Position</option>
                                <optgroup label="Executive Level">
                                    <option value="President Director / CEO">President Director / CEO</option>
                                    <option value="Vice President (VP)">Vice President (VP)</option>
                                    <option value="Chief Operating Officer (COO)">Chief Operating Officer (COO)</option>
                                    <option value="Chief Technology Officer (CTO)">Chief Technology Officer (CTO)
                                    </option>
                                    <option value="Chief Financial Officer (CFO)">Chief Financial Officer (CFO)</option>
                                    <option value="Chief Marketing Officer (CMO)">Chief Marketing Officer (CMO)</option>
                                    <option value="Chief Commercial Officer (CCO)">Chief Commercial Officer (CCO)
                                    </option>
                                    <option value="Chief Information Officer (CIO)">Chief Information Officer (CIO)
                                    </option>
                                </optgroup>

                                <optgroup label="Director Level">
                                    <option value="Managing Director">Managing Director</option>
                                    <option value="Director of Sales">Director of Sales</option>
                                    <option value="Director of Marketing">Director of Marketing</option>
                                    <option value="Director of Operations">Director of Operations</option>
                                    <option value="IT Director">IT Director</option>
                                    <option value="Finance Director">Finance Director</option>
                                    <option value="Business Development Director">Business Development Director
                                    </option>
                                </optgroup>

                                <optgroup label="Managerial Level">
                                    <option value="General Manager (GM)">General Manager (GM)</option>
                                    <option value="Senior Manager">Senior Manager</option>
                                    <option value="Marketing Manager">Marketing Manager</option>
                                    <option value="Sales Manager">Sales Manager</option>
                                    <option value="Product Manager">Product Manager</option>
                                    <option value="IT Manager">IT Manager</option>
                                    <option value="Operations Manager">Operations Manager</option>
                                    <option value="Project Manager">Project Manager</option>
                                </optgroup>

                                <optgroup label="Specialist / Staff Level">
                                    <option value="Business Analyst">Business Analyst</option>
                                    <option value="Software Engineer">Software Engineer</option>
                                    <option value="Network Engineer">Network Engineer</option>
                                    <option value="System Administrator">System Administrator</option>
                                    <option value="Product Specialist">Product Specialist</option>
                                    <option value="Marketing Executive">Marketing Executive</option>
                                    <option value="Sales Executive">Sales Executive</option>
                                    <option value="Finance Staff">Finance Staff</option>
                                    <option value="Admin Staff">Admin Staff</option>
                                </optgroup>

                                <optgroup label="Others">
                                    <option value="Consultant">Consultant</option>
                                    <option value="Investor">Investor</option>
                                    <option value="Media / Journalist">Media / Journalist</option>
                                    <option value="Government Official">Government Official</option>
                                    <option value="Academic / Lecturer">Academic / Lecturer</option>
                                    <option value="Student">Student</option>
                                    <option value="Freelancer">Freelancer</option>
                                    <option value="Others">Others</option>
                                </optgroup>
                            </select>

                        </div>
                        <div class="col-md-6">
                            <input id="contactCountry" name="country" wire:model="country" type="text"
                                class="form-control" placeholder="Country" aria-label="Country" required />
                            @error('country')
                                <span class="text">*{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <select class="form-control" id="which_industry-1" name="which_industry-1" required>
                                <option value="" disabled="" selected="">Select your industry</option>
                                <option value="Banking, Finance &amp; Fintech">Banking, Finance &amp; Fintech</option>
                                <option value="Construction &amp; Design">Construction &amp; Design</option>
                                <option value="Consumer Manufacturing &amp; Retail">Consumer Manufacturing &amp; Retail
                                </option>
                                <option value="E-Commerce">E-Commerce</option>
                                <option value="Education &amp; Research">Education &amp; Research</option>
                                <option value="Energy &amp; Utilities">Energy &amp; Utilities</option>
                                <option value="Government &amp; Public Agencies">Government &amp; Public Agencies
                                </option>
                                <option value="Healthcare &amp; Pharmaceuticals">Healthcare &amp; Pharmaceuticals
                                </option>
                                <option value="Hospitality &amp; Tourism">Hospitality &amp; Tourism</option>
                                <option value="Industrial Manufacturing">Industrial Manufacturing</option>
                                <option value="Logistics &amp; Transport">Logistics &amp; Transport</option>
                                <option value="Media &amp; Entertainment">Media &amp; Entertainment</option>
                                <option value="Primary Industries (Oil &amp; Gas, mining, agriculture)">Primary
                                    Industries (Oil
                                    &amp; Gas, mining, agriculture)</option>
                                <option value="Telecommunications">Telecommunications</option>
                                <option value="Cloud Service Provider">Cloud Service Provider</option>
                                <option value="Data Center/Colocation Provider/REIT">Data Center/Colocation
                                    Provider/REIT
                                </option>
                                <option value="IT Consultants &amp; System Integrator">IT Consultants &amp; System
                                    Integrator
                                </option>
                                <option value="IT/DC Equipment Provider">IT/DC Equipment Provider</option>
                                <option value="IT/DC Solution &amp; Service Provider">IT/DC Solution &amp; Service
                                    Provider
                                </option>
                                <option value="Others">Others</option>
                            </select>
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
