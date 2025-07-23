<div>
    <!-- Service Details Section -->
    <section id="service-details" class="service-details section">

        <div class="container">

            <div class="row gy-4">

                <div class="col-lg-4">
                    <div class="services-list">
                        <a href="{{ route('user.profile') }}" class="active">Profile</a>
                        <a href="{{ route('user.booking') }}">My Booking</a>
                        <a href="{{ route('user.order') }}">My Order</a>
                        <a href="{{ route('logout') }}">Logout</a>
                    </div>

                </div>

                <div class="col-lg-8">                    
                    <h3>My Profile</h3>
                   
                    <ul>
                        <li><i class="bi bi-circle"></i> <span>Name: {{ auth()->user()->name }}</span></li>
                        <li><i class="bi bi-circle"></i> <span>Email: {{ auth()->user()->email }}.</span></li>
                    </ul>
                    <hr>
                   <h3>My Membership</h3>
                   <p>
                    <p>
                        Sunt rem odit accusantium omnis perspiciatis officia. Laboriosam aut consequuntur recusandae
                        mollitia doloremque est architecto cupiditate ullam. Quia est ut occaecati fuga. Distinctio ex
                        repellendus eveniet velit sint quia sapiente cumque. Et ipsa perferendis ut nihil. Laboriosam
                        vel voluptates tenetur nostrum. Eaque iusto cupiditate et totam et quia dolorum in. Sunt
                        molestiae ipsum at consequatur vero. Architecto ut pariatur autem ad non cumque nesciunt qui
                        maxime. Sunt eum quia impedit dolore alias explicabo ea.
                    </p>
                </div>

            </div>

        </div>

    </section><!-- /Service Details Section -->
</div>
