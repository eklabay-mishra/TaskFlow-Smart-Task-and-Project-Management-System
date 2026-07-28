<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg p-4 p-md-5 rounded-4">
                <div class="text-center mb-4">
                    <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill fw-semibold mb-2">Get in Touch</span>
                    <h2 class="fw-bold">Contact TaskFlow Team</h2>
                    <p class="text-secondary">Have questions or need assistance? Send us a message and it will be saved directly into our database inbox.</p>
                </div>

                <form action="/contact" method="POST" id="contact-form">
                    <?= \Core\CSRF::field() ?>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Your Name</label>
                            <input type="text" name="name" class="form-control form-control-lg" placeholder="John Doe" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Email Address</label>
                            <input type="email" name="email" class="form-control form-control-lg" placeholder="john@example.com" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Subject</label>
                        <input type="text" name="subject" class="form-control form-control-lg" placeholder="Project Inquiry / Feedback" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-medium">Message</label>
                        <textarea name="message" class="form-control form-control-lg" rows="5" placeholder="How can we help you?" required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill shadow-sm">
                        <i class="bi bi-send-fill me-2"></i> Send Inquiry
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
