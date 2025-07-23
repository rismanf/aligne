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
                        <div class="col-md-6">
                            <input id="contactNameFirst" name="firstname" wire:model.defer="firstname" type="text"
                                class="form-control" placeholder="First Name" aria-label="First Name" required/>
                            @error('firstname')
                                <span class="text">*{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <input id="contactNameLast" name="lastname" wire:model.defer="lastname" type="text"
                                class="form-control" placeholder="Last Name" aria-label="Last Name" required/>
                            @error('lastname')
                                <span class="text">*{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <input id="contactCompany" name="company" wire:model.defer="company" type="text"
                                class="form-control" placeholder="Company Name" aria-label="Company Name" required/>
                            @error('company')
                                <span class="text">*{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <input id="contactJob" name="job" wire:model.defer="job" type="text"
                                class="form-control" placeholder="Job Title" aria-label="Job Title" required/>
                            @error('job')
                                <span class="text">*{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <input id="contactCountry" name="country" wire:model.defer="country" type="text"
                                class="form-control" placeholder="Country" aria-label="Country" required/>
                            @error('country')
                                <span class="text">*{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <input id="contactPhone" name="phone" wire:model.defer="phone" type="text"
                                inputmode="numeric" pattern="[0-9]+" class="form-control" placeholder="Phone Number"
                                aria-label="Phone Number" required/>
                            @error('phone')
                                <span class="text">*{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-12">
                            <input id="contactEmail" name="email" wire:model.defer="email" type="email"
                                class="form-control" placeholder="Email" aria-label="Email" required/>
                            @error('email')
                                <span class="text">*{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <input id="password" name="password" wire:model.defer="password" type="password"
                                class="form-control" placeholder="Password" aria-label="password" required/>
                            @error('password')
                                <span class="text">*{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <input id="passwordConfirm" name="password_confirmation"
                                wire:model.defer="password_confirmation" type="password" class="form-control"
                                placeholder="Password Confirm" aria-label="password confirm" required/>
                            @error('password_confirmation')
                                <span class="text">*{{ $message }}</span>
                            @enderror
                        </div>
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
</div>
