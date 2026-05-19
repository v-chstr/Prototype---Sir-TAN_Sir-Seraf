@extends('layouts.app')

@section('title', 'Contact Us - SPUP Evaluation System')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="text-center mb-5 animate-fade-in">
                <h2 class="fw-bold"><i class="bi bi-envelope me-2" style="color: var(--spup-primary);"></i>Contact Us</h2>
                <p class="text-muted">Have questions or concerns? We'd love to hear from you!</p>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 text-center">
                        <div class="card-body">
                            <i class="bi bi-geo-alt display-4" style="color: var(--spup-primary);"></i>
                            <h5 class="mt-3">Address</h5>
                            <p class="text-muted small">Mabini St., Tuguegarao City, Cagayan 3500, Philippines</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 text-center">
                        <div class="card-body">
                            <i class="bi bi-telephone display-4" style="color: var(--spup-primary);"></i>
                            <h5 class="mt-3">Phone</h5>
                            <p class="text-muted small">(078) 844-1872<br>(078) 844-2635</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 text-center">
                        <div class="card-body">
                            <i class="bi bi-envelope display-4" style="color: var(--spup-primary);"></i>
                            <h5 class="mt-3">Email</h5>
                            <p class="text-muted small">info@spup.edu.ph<br>registrar@spup.edu.ph</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-lg mt-5 animate-fade-in">
                <div class="card-body p-5">
                    <h4 class="fw-bold mb-4 text-center">Send us a Message</h4>

                    @if(session('success'))
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('contact.store') }}">
                        @csrf

                        <div style="position:absolute;left:-10000px;top:auto;width:1px;height:1px;overflow:hidden;" aria-hidden="true">
                            <label for="website">Website</label>
                            <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="{{ old('name', auth()->user()->name ?? '') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="{{ old('email', auth()->user()->email ?? '') }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="subject" class="form-label">Subject <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="subject" name="subject" 
                                   value="{{ old('subject') }}" placeholder="What is this about?" required>
                        </div>

                        <div class="mb-4">
                            <label for="message" class="form-label">Message <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="message" name="message" rows="5" 
                                      placeholder="Type your message here..." required>{{ old('message') }}</textarea>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-spup btn-lg">
                                <i class="bi bi-send me-2"></i>Send Message
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
