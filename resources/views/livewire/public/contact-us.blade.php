<div>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    <section id="hero" class="hero banner contact">
        <div class="grid grid__stack">
            <div class="bg__right bg__img">
                <img class="only__desktop" src="{{ asset('/assets/img/banner-bg-contact.webp') }}" alt="Banner" />
                <img class="only__mobile" src="{{ asset('/assets/img/banner-bg-contact.webp') }}" alt="Banner" />
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-md-7">
                        <div class="section__content">
                            <div class="section__heading text--danger text__xl">
                                <h1>Get in Touch</h1>
                                <p>
                                    Want to learn more about NeutraDC
                                    data center, network &
                                    infrastructure capabilities? Get in
                                    touch with us now
                                </p>
                                <div class="link__wrapper vstack">
                                    <a href="mailto:sales.admin@neutradc.com" class="cta cta__alt">
                                        <svg class="icon" aria-hidden="true">
                                            <use xlink:href="#svg-email"></use>
                                        </svg>
                                        sales.admin@neutradc.com
                                    </a>
                                    <a href="#" class="cta cta__alt" target="_blank">
                                        <svg class="icon" aria-hidden="true">
                                            <use xlink:href="#svg-pin"></use>
                                        </svg>
                                        Telkom Landmark Tower, 5th
                                        Floor, Jl. Gatot Subroto South
                                        Jakarta, Indonesia
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="contact" class="contact">
        <div class="container">

            <div class="section__content">

                <div class="form__wrapper bg__danger bg__danger--linear bg__danger">

                    <form class="row g-4" wire:submit="save">
                        <div class="col-md-6">
                            <input id="contactNameFirst" type="text" class="form-control" placeholder="First Name"
                                aria-label="First Name" wire:model.defer="first_name" required />
                            @error('first_name')
                                <span class="text">*{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <input id="contactNameLast" type="text" class="form-control" placeholder="Last Name"
                                aria-label="Last Name" wire:model.defer="last_name" required />
                            @error('last_name')
                                <span class="text">*{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <input id="contactCompany" type="text" class="form-control" placeholder="Company Name"
                                aria-label="Company Name" wire:model.defer="company" required />
                            @error('company')
                                <span class="text">*{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <input id="contactJob" type="text" class="form-control" placeholder="Job Title"
                                aria-label="Job Title" wire:model.defer="job" required />
                            @error('job')
                                <span class="text">*{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <input id="contactCountry" type="text" class="form-control" placeholder="Country"
                                aria-label="Country" wire:model.defer="country" required />
                            @error('country')
                                <span class="text">*{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <input id="contactPhone" type="text" inputmode="numeric" pattern="[0-9]+"
                                class="form-control" placeholder="Phone Number" aria-label="Phone Number"
                                wire:model.defer="phone" required />
                            @error('phone')
                                <span class="text">*{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-12">
                            <input id="contactEmail" type="email" class="form-control" placeholder="Email"
                                aria-label="Email" wire:model.defer="email" required />
                            @error('email')
                                <span class="text">*{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-12">
                            <textarea id="contactMessage" class="form-control" placeholder="Message" aria-label="Message" required rows="4"
                                wire:model.defer="message"></textarea>
                            @error('message')
                                <span class="text">*{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-12">
                            <div class="form-check">
                                @error('terms')
                                    <span class="text">*{{ $message }}</span>
                                @enderror
                                <input class="form-check-input" type="checkbox" value="" id="contactTerms"
                                    aria-describedby="contactTermsFeedback" wire:model.defer="terms" required />
                                <label class="form-check-label" for="contactTerms">
                                    I By submitting this Contact Us
                                    form, you consent to receiving
                                    information related to NeutraDC
                                    products and services. (you must
                                    agree before submitting) NeutraDC
                                    Privacy Policy
                                </label>
                            </div>
                        </div>

                        <div class="col-12">
                            <button label="save" type="submit" class="btn btn__primary" spinner="save">
                                Submit
                            </button>
                        </div>
                        @if (session('success'))
                            <div x-data="{ show: true }" x-show="show" x-cloak
                                style="background-color:#d1fae5; border:1px solid #34d399; color:#065f46; padding: 0.75rem 1rem; border-radius: 6px; margin-bottom: 1rem; display: flex; justify-content: space-between; align-items: center;width: 100%;">

                                <span style="flex-grow: 1;">
                                    {{ session('success') }}

                                </span>

                                <button @click="show = false"
                                    style="background: none; border: none; font-weight: bold;
                   font-size: 1.25rem; color: #065f46; cursor: pointer;
                   margin-left: 1rem; line-height: 1;">
                                    &times;
                                </button>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
