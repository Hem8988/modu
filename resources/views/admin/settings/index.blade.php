@extends('layouts.admin')

@section('title', 'Global Settings')

@section('content')
<div class="container-fluid px-0 mb-5">
    
    <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
        <div>
            <h1 class="fw-bolder text-dark mb-1" style="font-size: 2rem; letter-spacing: -1px;">Global Settings</h1>
            <p class="text-secondary fw-semibold mb-0">Configure your system-wide SEO, branding, and tracking integrations.</p>
        </div>
        <div class="px-3 py-2 bg-primary bg-opacity-10 border border-primary-subtle rounded-3">
            <span class="text-primary fw-bold text-uppercase small" style="letter-spacing: 1px;">System Health: Optimal ⚡</span>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center fw-semibold rounded-4 shadow-sm border-0 bg-success bg-opacity-10 text-success mb-4">
            <i class="fas fa-check-circle me-3 fs-5"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        
        <div class="row g-4">
            
            {{-- Navigation Tabs --}}
            <div class="col-12 col-xl-3 col-lg-4">
                <div class="d-flex flex-column gap-2" id="settings-nav">
                    <div class="settings-tab active bg-white border fw-bold text-dark shadow-sm" onclick="switchTab('seo', this)">
                        <span class="fs-5">🔍</span> SEO & Metadata
                    </div>
                    <div class="settings-tab fw-semibold text-secondary" onclick="switchTab('tracking', this)">
                        <span class="fs-5">🛠️</span> Tracking Scripts
                    </div>
                    <div class="settings-tab fw-semibold text-secondary" onclick="switchTab('email', this)">
                        <span class="fs-5">📧</span> Email Config
                    </div>
                    <div class="settings-tab fw-semibold text-secondary" onclick="switchTab('sms', this)">
                        <span class="fs-5">📱</span> SMS Automation
                    </div>
                    <div class="settings-tab fw-semibold text-secondary" onclick="switchTab('master-products', this)">
                        <span class="fs-5">📋</span> Master Options
                    </div>
                    <div class="settings-tab fw-semibold text-secondary" onclick="switchTab('company', this)">
                        <span class="fs-5">🏢</span> Company Details
                    </div>
                </div>
            </div>

            {{-- Settings Content --}}
            <div class="col-12 col-xl-9 col-lg-8">
                
                {{-- SEO Card --}}
                <div id="section-seo" class="settings-section card border-0 shadow-sm rounded-4 p-4 p-md-5">
                    <h4 class="fw-bolder text-dark mb-4 d-flex align-items-center gap-2">SEO Configuration</h4>

                    <div class="row g-4">
                        <div class="col-12">
                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Application / Site Name</label>
                            <input type="text" name="site_name" class="form-control bg-light fw-bold py-2 custom-input" value="{{ $settings['site_name'] ?? 'ModuShade CRM' }}">
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Meta Title Pattern</label>
                            <input type="text" name="seo_title" class="form-control bg-light fw-bold py-2 custom-input" value="{{ $settings['seo_title'] ?? 'ModuShade - Industrial Shade Solutions' }}">
                            <div class="form-text text-secondary mt-2 small">Default title used when no specific page title is set.</div>
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Global Meta Description</label>
                            <textarea name="seo_description" rows="3" class="form-control bg-light fw-bold py-2 custom-input">{{ $settings['seo_description'] ?? '' }}</textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Focus Keywords</label>
                            <input type="text" name="seo_keywords" class="form-control bg-light fw-bold py-2 custom-input" value="{{ $settings['seo_keywords'] ?? 'CRM, Industrial Shade, ModuShade' }}">
                        </div>
                    </div>
                </div>

                {{-- Company Details Card --}}
                <div id="section-company" class="settings-section card border-0 shadow-sm rounded-4 p-4 p-md-5" style="display: none;">
                    <h4 class="fw-bolder text-dark mb-1 d-flex align-items-center gap-2">Company Information</h4>
                    <p class="text-secondary small fw-semibold mb-4">These details will heavily appear on client-facing documents like Invoices, Quotes, and System Footers.</p>

                    <div class="row g-4">
                        <div class="col-12">
                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Official Entity Name</label>
                            <input type="text" name="company_name" class="form-control bg-light fw-bold py-2 custom-input" value="{{ $settings['company_name'] ?? 'ModuShade Industrial' }}">
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Primary Phone</label>
                            <input type="text" name="company_phone" class="form-control bg-light fw-bold py-2 custom-input" value="{{ $settings['company_phone'] ?? '+1 201 660 5298' }}">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Business Email</label>
                            <input type="email" name="company_email" class="form-control bg-light fw-bold py-2 custom-input" value="{{ $settings['company_email'] ?? 'info@modu-shade.com' }}">
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Company Website</label>
                            <input type="text" name="company_website" class="form-control bg-light fw-bold py-2 custom-input" value="{{ $settings['company_website'] ?? 'info.modu-shade.com' }}">
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Address Line 1</label>
                            <input type="text" name="company_address_1" class="form-control bg-light fw-bold py-2 custom-input" value="{{ $settings['company_address_1'] ?? '24 Poplar Street' }}">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Address Line 2</label>
                            <input type="text" name="company_address_2" class="form-control bg-light fw-bold py-2 custom-input" value="{{ $settings['company_address_2'] ?? 'Creskill, NJ 07626' }}">
                        </div>
                    </div>
                </div>

                {{-- Tracking Scripts Card --}}
                <div id="section-tracking" class="settings-section card border-0 shadow-sm rounded-4 p-4 p-md-5" style="display: none;">
                    <h4 class="fw-bolder text-dark mb-4 d-flex align-items-center gap-2">Custom Scripts & Tracking</h4>

                    <div class="row g-4">
                        <div class="col-12">
                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Header Scripts (Pixel, Analytics)</label>
                            <textarea name="header_scripts" rows="6" class="form-control bg-light fw-semibold text-monospace custom-input" placeholder="Paste Google Analytics, FB Pixel, etc. here..."><?= ($settings['header_scripts'] ?? '') ?></textarea>
                            <div class="form-text text-secondary mt-2 small">Injected into the &lt;head&gt; section.</div>
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Body Scripts (After Open Body Tag)</label>
                            <textarea name="body_scripts" rows="6" class="form-control bg-light fw-semibold text-monospace custom-input" placeholder="Paste GTM noscript or other body opening scripts..."><?= ($settings['body_scripts'] ?? '') ?></textarea>
                            <div class="form-text text-secondary mt-2 small">Injected immediately after the opening &lt;body&gt; tag.</div>
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Footer Scripts (Before Close Body Tag)</label>
                            <textarea name="footer_scripts" rows="6" class="form-control bg-light fw-semibold text-monospace custom-input" placeholder="Paste chat widgets or other footer scripts..."><?= ($settings['footer_scripts'] ?? '') ?></textarea>
                            <div class="form-text text-secondary mt-2 small">Injected just before the closing &lt;/body&gt; tag.</div>
                        </div>
                    </div>
                </div>

                {{-- Email Configuration Card --}}
                <div id="section-email" class="settings-section card border-0 shadow-sm rounded-4 p-4 p-md-5" style="display: none;">
                    <h4 class="fw-bolder text-dark mb-4 d-flex align-items-center gap-2">SMTP / Email Configuration</h4>

                    <div class="row g-4">
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Mail Mailer</label>
                            <select name="mail_mailer" class="form-select bg-light fw-bold py-2 custom-input">
                                <option value="smtp" {{ ($settings['mail_mailer'] ?? 'log') === 'smtp' ? 'selected' : '' }}>SMTP</option>
                                <option value="log" {{ ($settings['mail_mailer'] ?? 'log') === 'log' ? 'selected' : '' }}>Log (Testing)</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Encryption</label>
                            <select name="mail_encryption" class="form-select bg-light fw-bold py-2 custom-input">
                                <option value="tls" {{ ($settings['mail_encryption'] ?? 'tls') === 'tls' ? 'selected' : '' }}>TLS</option>
                                <option value="ssl" {{ ($settings['mail_encryption'] ?? 'tls') === 'ssl' ? 'selected' : '' }}>SSL</option>
                                <option value="null" {{ ($settings['mail_encryption'] ?? 'tls') === 'null' ? 'selected' : '' }}>None</option>
                            </select>
                        </div>

                        <div class="col-12 col-md-8">
                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Mail Host</label>
                            <input type="text" name="mail_host" class="form-control bg-light fw-bold py-2 custom-input" value="{{ $settings['mail_host'] ?? '' }}" placeholder="smtp.mailtrap.io">
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Mail Port</label>
                            <input type="text" name="mail_port" class="form-control bg-light fw-bold py-2 custom-input" value="{{ $settings['mail_port'] ?? '587' }}" placeholder="587">
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Username</label>
                            <input type="text" name="mail_username" class="form-control bg-light fw-bold py-2 custom-input" value="{{ $settings['mail_username'] ?? '' }}">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Password</label>
                            <input type="password" name="mail_password" class="form-control bg-light fw-bold py-2 custom-input" value="{{ $settings['mail_password'] ?? '' }}">
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1">From Address</label>
                            <input type="email" name="mail_from_address" class="form-control bg-light fw-bold py-2 custom-input" value="{{ $settings['mail_from_address'] ?? '' }}" placeholder="hello@modushade.com">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1">From Name</label>
                            <input type="text" name="mail_from_name" class="form-control bg-light fw-bold py-2 custom-input" value="{{ $settings['mail_from_name'] ?? 'Modu Shade' }}">
                        </div>
                    </div>

                    {{-- Email Test Hub --}}
                    <div class="bg-primary bg-opacity-10 border border-primary-subtle rounded-4 p-4 mt-5">
                        <h6 class="fw-bolder text-primary mb-3 d-flex align-items-center gap-2">
                            <i class="fas fa-paper-plane"></i> Email Connection Test Utility
                        </h6>
                        <div class="row g-3">
                            <div class="col-12 col-md-8">
                                <input type="email" id="test_mail_address" class="form-control bg-white fw-bold py-2 custom-input" placeholder="Enter recipient email (e.g. your@email.com)" value="{{ auth()->user()->email }}">
                            </div>
                            <div class="col-12 col-md-4">
                                <button type="button" onclick="runEmailTest()" id="btn-test-email" class="btn btn-primary w-100 fw-bold py-2 shadow-sm">
                                    Run Email Test
                                </button>
                            </div>
                        </div>
                        <div id="email-test-feedback" class="mt-3 small fw-bold" style="display: none;"></div>
                    </div>

                    {{-- Client Email Automation Toggle --}}
                    <div class="d-flex align-items-center justify-content-between bg-light p-4 rounded-4 border mt-5">
                        <div>
                            <h5 class="fw-bolder text-dark mb-1">Enable Client Welcome Email</h5>
                            <p class="text-secondary small fw-semibold mb-0">Automatically send a thank-you email when a new lead arrives.</p>
                        </div>
                        <div class="form-check form-switch ms-3">
                            <input class="form-check-input fs-3 custom-switch" type="checkbox" name="welcome_email_enabled" {{ ($settings['welcome_email_enabled'] ?? 'off') === 'on' ? 'checked' : '' }}>
                        </div>
                    </div>
                </div>

                {{-- SMS Card --}}
                <div id="section-sms" class="settings-section card border-0 shadow-sm rounded-4 p-4 p-md-5" style="display: none;">
                    <h4 class="fw-bolder text-dark mb-4 d-flex align-items-center gap-2">SMS Lead Automation</h4>

                    <div class="d-flex align-items-center justify-content-between bg-light p-4 rounded-4 border mb-4">
                        <div>
                            <h5 class="fw-bolder text-dark mb-1">Enable First-Touch Automation</h5>
                            <p class="text-secondary small fw-semibold mb-0">Automatically send a text message when a new lead arrives.</p>
                        </div>
                        <div class="form-check form-switch ms-3">
                            <input class="form-check-input fs-3 custom-switch" type="checkbox" name="sms_enabled" {{ ($settings['sms_enabled'] ?? 'off') === 'on' ? 'checked' : '' }}>
                        </div>
                    </div>

                    {{-- Client Welcome SMS (Immediate) --}}
                    <div class="bg-light p-4 rounded-4 border mb-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <h5 class="fw-bolder text-dark mb-1">Send Immediate Client Confirmation</h5>
                                <p class="text-secondary small fw-semibold mb-0">Sends a "Thank You" or Lead Receipt SMS the moment they submit.</p>
                            </div>
                            <div class="form-check form-switch ms-3">
                                <input class="form-check-input fs-3 custom-switch" type="checkbox" name="welcome_sms_enabled" {{ ($settings['welcome_sms_enabled'] ?? 'off') === 'on' ? 'checked' : '' }}>
                            </div>
                        </div>
                        <div id="welcome-sms-template-container" style="{{ ($settings['welcome_sms_enabled'] ?? 'off') === 'on' ? '' : 'display:none;' }}">
                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Welcome SMS Template</label>
                            <textarea name="welcome_sms_template" rows="3" class="form-control bg-white fw-bold custom-input" placeholder="e.g. Hi [name], we received your request for [project]!">{{ $settings['welcome_sms_template'] ?? "Hi [name], we have received your request for [project]. Our team will contact you shortly!" }}</textarea>
                            <div class="form-text text-secondary mt-2 small">Available tags: <code>[name]</code>, <code>[project]</code>, <code>[phone]</code>, <code>[email]</code></div>
                        </div>
                    </div>

                    <div id="sms-sequence-container" class="d-flex flex-column gap-4 mb-4">
                        {{-- Steps will be injected here by JS --}}
                    </div>

                    <button type="button" onclick="addStep()" class="btn btn-light fw-bold border border-secondary-subtle border-2 text-primary w-100 py-2 rounded-4 custom-hover-dash">
                        <i class="fas fa-plus me-2"></i> Add New Follow-up Step
                    </button>
                    
                    <input type="hidden" name="sms_sequence" id="sms_sequence_input">
                    
                    <div class="mt-5 pt-4 border-top border-secondary-subtle border-dashed">
                        <h5 class="fw-bolder text-dark mb-4 d-flex align-items-center gap-2">
                            <span class="text-primary fs-5"><i class="fas fa-key"></i></span> Advanced Connectivity (Twilio API)
                        </h5>
                        
                        <div class="row g-4 mb-4">
                            <div class="col-12 col-md-6">
                                <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Twilio Account SID</label>
                                <input type="text" name="twilio_sid" class="form-control bg-light fw-bold py-2 custom-input" value="{{ $settings['twilio_sid'] ?? '' }}" placeholder="ACxxxxxxxxxxxxxxxxxxxxxxxx">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Twilio Auth Token</label>
                                <input type="password" name="twilio_token" class="form-control bg-light fw-bold py-2 custom-input" value="{{ $settings['twilio_token'] ?? '' }}" placeholder="••••••••••••••••••••••••">
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Twilio Sender Number (From)</label>
                                <input type="text" name="twilio_from" class="form-control bg-light fw-bold py-2 custom-input" value="{{ $settings['twilio_from'] ?? '' }}" placeholder="+1234567890">
                                <div class="form-text text-secondary mt-1 small">Your verified Twilio phone number or Messaging Service SID.</div>
                            </div>
                        </div>

                        {{-- Twilio Test Hub --}}
                        <div class="bg-primary bg-opacity-10 border border-primary-subtle rounded-4 p-4">
                            <h6 class="fw-bolder text-primary mb-3 d-flex align-items-center gap-2">
                                <i class="fas fa-vial"></i> Connection Test Utility
                            </h6>
                            <div class="row g-3">
                                <div class="col-12 col-md-8">
                                    <input type="text" id="test_twilio_phone" class="form-control bg-white fw-bold py-2 custom-input" placeholder="Enter your mobile number (e.g. +1...)" value="{{ $settings['admin_phone'] ?? '' }}">
                                </div>
                                <div class="col-12 col-md-4">
                                    <button type="button" onclick="runTwilioTest()" id="btn-test-twilio" class="btn btn-primary w-100 fw-bold py-2 shadow-sm">
                                        Run Connection Test
                                    </button>
                                </div>
                            </div>
                            <div id="twilio-test-feedback" class="mt-3 small fw-bold" style="display: none;"></div>
                        </div>
                    </div>
                </div>

                {{-- Master Product Options Card --}}
                <div id="section-master-products" class="settings-section card border-0 shadow-sm rounded-4 p-4 p-md-5" style="display: none;">
                    <div class="d-flex flex-wrap justify-content-between align-items-md-center gap-3 mb-4">
                        <div>
                            <h4 class="fw-bolder text-dark mb-1 d-flex align-items-center gap-2">Global Attribute Registry</h4>
                            <p class="text-secondary small fw-semibold mb-0">These options will appear as "Quick Add" buttons when you manage products in the Catalog.</p>
                        </div>
                        <button type="button" onclick="openMasterAttrModal()" class="btn btn-warning text-white fw-bold shadow-sm px-3 py-2 rounded-3 text-nowrap">
                            + Add Global Option
                        </button>
                    </div>

                    <div class="d-flex flex-column gap-3">
                        @forelse($masterAttributes as $ma)
                            <div class="bg-light border border-secondary-subtle p-3 p-md-4 rounded-4 d-flex flex-wrap justify-content-between align-items-center gap-3 custom-hover-card">
                                <div>
                                    <div class="fw-bolder text-dark fs-5 d-flex align-items-center gap-2 mb-1">
                                        {{ $ma->label }}
                                        @if(!$ma->is_active) <span class="badge bg-danger bg-opacity-10 text-danger border border-danger-subtle ms-2 px-2 py-1 text-uppercase small">Inactive</span> @endif
                                    </div>
                                    <div class="d-flex flex-wrap gap-3 small fw-semibold text-secondary">
                                        <span><span class="text-primary me-1">Values:</span> {{ $ma->default_values ?: '(Custom)' }}</span>
                                        @if($ma->default_price > 0)
                                            <span class="text-success fw-bold">+ ${{ number_format($ma->default_price, 2) }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="button" onclick='openMasterAttrModal({{ $ma->id }}, "{{ addslashes($ma->label) }}", "{{ addslashes($ma->default_values) }}", {{ $ma->is_active ? 1 : 0 }}, {{ $ma->default_price }})' 
                                            class="btn btn-light border text-secondary d-flex align-items-center justify-content-center p-2 rounded-3 hover-text-primary" style="width:40px; height:40px;">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>
                                    <button type="submit" form="delete-ma-{{ $ma->id }}" 
                                            class="btn btn-light border border-danger-subtle bg-danger bg-opacity-10 text-danger d-flex align-items-center justify-content-center p-2 rounded-3 hover-bg-danger" style="width:40px; height:40px;">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5 border border-dashed rounded-4 bg-light">
                                <div class="fs-1 opacity-25 mb-3">📋</div>
                                <h5 class="fw-bolder text-secondary mb-1">No Master Options Defined</h5>
                                <p class="text-secondary small fw-semibold">Add global options to speed up your product cataloging.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-3 mt-4 pt-3 pb-5">
                    <button type="reset" class="btn btn-light border fw-bold text-secondary px-3 py-2 rounded-3 shadow-sm">Discard Changes</button>
                    <button type="submit" class="btn btn-primary fw-bolder px-4 py-2 rounded-3 shadow-sm">Save Settings <i class="fas fa-arrow-right ms-2"></i></button>
                </div>

            </div>
        </div>
    </form>
</div>

{{-- Hidden Delete Forms --}}
@foreach($masterAttributes as $ma)
    <form id="delete-ma-{{ $ma->id }}" action="{{ route('admin.master-attributes.destroy', $ma->id) }}" method="POST" onsubmit="return confirm('Archive this global option?')">
        @csrf @method('DELETE')
    </form>
@endforeach

{{-- Master Attribute Modal --}}
<div class="modal fade" id="master-attr-modal" tabindex="-1" aria-hidden="true" style="backdrop-filter: blur(5px);">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-bottom-0 pt-4 px-4">
                <h4 class="modal-title fw-bolder" id="modal-attr-title">📋 Configure Global Option</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <form id="master-attr-form" method="POST">
                    @csrf
                    <div id="modal-attr-method"></div>
                    <div class="p-4">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Option Label</label>
                                <input type="text" name="label" id="ma_label" required placeholder="e.g. Fabric Type" class="form-control bg-light fw-bold py-2 custom-input">
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Default Surcharge ($)</label>
                                <input type="number" step="0.01" name="default_price" id="ma_price" placeholder="0.00" class="form-control bg-light fw-bold py-2 custom-input">
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Default Multi-Selection Values</label>
                                <textarea name="default_values" id="ma_values" rows="3" placeholder="e.g. Scala, Deco, Silk (Separate by comma)" class="form-control bg-light fw-bold py-2 custom-input" style="resize:none;"></textarea>
                                <div class="form-text text-secondary mt-2 small">Separate values with a comma. These will be clickable shortcuts.</div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-light p-4 border-top d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light border fw-bold text-secondary px-4 py-2 rounded-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" id="ma_submit_btn" class="btn btn-primary fw-bolder px-4 py-2 rounded-3 shadow-sm">Save Option</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openMasterAttrModal(id = null, label = '', values = '', active = 1, price = 0) {
        const form = document.getElementById('master-attr-form');
        const methodDiv = document.getElementById('modal-attr-method');
        const title = document.getElementById('modal-attr-title');
        
        form.reset();
        
        if (id) {
            title.innerText = '🔧 Edit Global Option';
            form.action = '/admin/master-attributes/' + id;
            methodDiv.innerHTML = '<input type="hidden" name="_method" value="PUT">';
            document.getElementById('ma_label').value = label;
            document.getElementById('ma_values').value = values;
            document.getElementById('ma_price').value = price;
        } else {
            title.innerText = '📋 Register Global Option';
            form.action = '/admin/master-attributes';
            methodDiv.innerHTML = '';
            document.getElementById('ma_price').value = 0;
        }
        
        const modal = new bootstrap.Modal(document.getElementById('master-attr-modal'));
        modal.show();
    }

    // Replace the custom modal close
    window.closeMasterAttrModal = function() {
        const modalEl = document.getElementById('master-attr-modal');
        const modal = bootstrap.Modal.getInstance(modalEl);
        if(modal) modal.hide();
    };

    // Auto-switch to Master tab
    window.addEventListener('DOMContentLoaded', (event) => {
        if (window.location.hash === '#master') {
            const tabs = Array.from(document.querySelectorAll('.settings-tab'));
            const targetTab = tabs.find(t => t.textContent.includes('Master Options'));
            if(targetTab) switchTab('master-products', targetTab);
        }
    });

    window.switchTab = function(sectionId, el) {
        // Hide all sections
        document.querySelectorAll('.settings-section').forEach(section => {
            section.style.display = 'none';
        });

        // Show target section
        document.getElementById('section-' + sectionId).style.display = 'block';

        // Update tab styles
        document.querySelectorAll('.settings-tab').forEach(tab => {
            tab.classList.remove('active', 'bg-white', 'border', 'shadow-sm', 'text-dark');
            tab.classList.add('text-secondary');
        });

        // Set active tab style
        el.classList.add('active', 'bg-white', 'border', 'shadow-sm', 'text-dark');
        el.classList.remove('text-secondary');
    };

    // Multi-step SMS Sequence Logic
    let sequenceData = {!! $settings['sms_sequence'] ?? '[]' !!};
    const container = document.getElementById('sms-sequence-container');

    window.renderSequence = function() {
        container.innerHTML = '';
        if (sequenceData.length === 0) {
            container.innerHTML = '<p class="text-center text-secondary small fst-italic py-4 w-100 mb-0 border border-dashed rounded-3 bg-white">No automation steps configured yet. Click the button below to start.</p>';
            return;
        }

        sequenceData.forEach((step, index) => {
            const stepHtml = `
                <div class="card border-0 shadow-sm rounded-4 border-start border-primary border-4 p-4 position-relative">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <span class="badge bg-primary rounded-pill mb-2">Step ${index + 1}</span>
                            <h5 class="fw-bolder m-0">Follow-up Message</h5>
                        </div>
                        <button type="button" onclick="removeStep(${index})" class="btn btn-light border border-danger-subtle bg-danger bg-opacity-10 text-danger rounded-3 d-flex align-items-center justify-content-center p-0" style="width:32px; height:32px;" title="Remove Step"><i class="fas fa-times"></i></button>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Wait After Previous</label>
                            <input type="number" onchange="updateStep(${index}, 'delay_value', this.value)" value="${step.delay_value || 0}" class="form-control bg-light fw-bold py-2 custom-input">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Time Unit</label>
                            <select onchange="updateStep(${index}, 'delay_unit', this.value)" class="form-select bg-light fw-bold py-2 custom-input">
                                <option value="minutes" ${step.delay_unit === 'minutes' ? 'selected' : ''}>Minutes</option>
                                <option value="hours" ${step.delay_unit === 'hours' ? 'selected' : ''}>Hours</option>
                                <option value="days" ${step.delay_unit === 'days' ? 'selected' : ''}>Days</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Message Template</label>
                        <textarea onchange="updateStep(${index}, 'template', this.value)" rows="3" class="form-control bg-light fw-bold custom-input" style="resize:vertical;" placeholder="Enter SMS content...">${step.template || ''}</textarea>
                        <div class="d-flex flex-wrap gap-2 mt-2">
                            <span class="badge bg-light border text-secondary border-secondary-subtle font-monospace px-2 py-1 shadow-sm rounded-2 cursor-pointer action-tag" onclick="insertTagAtStep(${index}, '@{{name}}')">@{{name}}</span>
                            <span class="badge bg-light border text-secondary border-secondary-subtle font-monospace px-2 py-1 shadow-sm rounded-2 cursor-pointer action-tag" onclick="insertTagAtStep(${index}, '@{{product_type}}')">@{{product_type}}</span>
                            <span class="badge bg-light border text-secondary border-secondary-subtle font-monospace px-2 py-1 shadow-sm rounded-2 cursor-pointer action-tag" onclick="insertTagAtStep(${index}, '@{{windows_count}}')">@{{windows_count}}</span>
                        </div>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', stepHtml);
        });
        
        // Final Sync to hidden input
        document.getElementById('sms_sequence_input').value = JSON.stringify(sequenceData);
    }

    window.addStep = function() {
        sequenceData.push({
            delay_value: 2,
            delay_unit: 'minutes',
            template: "Hi @{{name}}, just checking in about your @{{product_type}} interest!"
        });
        renderSequence();
    }

    window.removeStep = function(index) {
        if(confirm('Are you sure you want to remove this automation step?')) {
            sequenceData.splice(index, 1);
            renderSequence();
        }
    }

    window.updateStep = function(index, field, value) {
        sequenceData[index][field] = value;
        document.getElementById('sms_sequence_input').value = JSON.stringify(sequenceData);
    }

    window.insertTagAtStep = function(index, tag) {
        const textareas = container.querySelectorAll('textarea');
        const textarea = textareas[index];
        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        const text = textarea.value;
        textarea.value = text.substring(0, start) + tag + text.substring(end);
        updateStep(index, 'template', textarea.value);
        renderSequence();
    }

    // Initial Render
    renderSequence();

    window.runTwilioTest = function() {
        const phone = document.getElementById('test_twilio_phone')?.value;
        const sidEl = document.querySelector('input[name="twilio_sid"]');
        const tokenEl = document.querySelector('input[name="twilio_token"]');
        const fromEl = document.querySelector('input[name="twilio_from"]');
        
        const btn = document.getElementById('btn-test-twilio');
        const feedback = document.getElementById('twilio-test-feedback');

        if (!sidEl?.value || !tokenEl?.value || !fromEl?.value) {
            alert('Please fill in Twilio SID, Token, and From Number before testing.');
            return;
        }

        if (!phone) {
            alert('Please enter a destination phone number.');
            return;
        }

        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Running...';
        feedback.style.display = 'block';
        feedback.className = 'mt-3 small fw-bold text-secondary';
        feedback.innerText = 'Initializing connectivity probe...';

        fetch("{{ route('admin.settings.test-sms') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ 
                phone: phone, 
                sid: sidEl.value, 
                token: tokenEl.value, 
                from: fromEl.value 
            })
        })
        .then(response => response.json())
        .then(data => {
            btn.disabled = false;
            btn.innerText = 'Run Connection Test';
            if (data.success) {
                feedback.className = 'mt-3 small fw-bold text-success';
                feedback.innerText = '✓ ' + data.message;
            } else {
                feedback.className = 'mt-3 small fw-bold text-danger';
                feedback.innerText = '❌ ' + (data.message || 'Unknown error occurred.');
            }
        })
        .catch(error => {
            btn.disabled = false;
            btn.innerText = 'Run Connection Test';
            feedback.className = 'mt-3 small fw-bold text-danger';
            feedback.innerText = '❌ Failed to reach the server. Check your internet connection.';
        });
    }

    window.runEmailTest = function() {
        const email = document.getElementById('test_mail_address').value;
        const host = document.querySelector('input[name="mail_host"]').value;
        const port = document.querySelector('input[name="mail_port"]').value;
        const user = document.querySelector('input[name="mail_username"]').value;
        const pass = document.querySelector('input[name="mail_password"]').value;
        const enc = document.querySelector('select[name="mail_encryption"]').value;
        const from = document.querySelector('input[name="mail_from_address"]').value;
        const fromName = document.querySelector('input[name="mail_from_name"]').value;
        
        const btn = document.getElementById('btn-test-email');
        const feedback = document.getElementById('email-test-feedback');

        if (!email) {
            alert('Please enter a destination email address.');
            return;
        }

        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Sending...';
        feedback.style.display = 'block';
        feedback.className = 'mt-3 small fw-bold text-secondary';
        feedback.innerText = 'Attempting SMTP handshake...';

        fetch("{{ route('admin.settings.test-email') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ 
                email: email,
                mail_host: host,
                mail_port: port,
                mail_username: user,
                mail_password: pass,
                mail_encryption: enc,
                mail_from_address: from,
                mail_from_name: fromName
            })
        })
        .then(response => response.json())
        .then(data => {
            btn.disabled = false;
            btn.innerText = 'Run Email Test';
            if (data.success) {
                feedback.className = 'mt-3 small fw-bold text-success';
                feedback.innerText = '✓ ' + data.message;
            } else {
                feedback.className = 'mt-3 small fw-bold text-danger';
                feedback.innerText = '❌ ' + (data.message || 'Unknown error occurred.');
            }
        })
        .catch(error => {
            btn.disabled = false;
            btn.innerText = 'Run Email Test';
            feedback.className = 'mt-3 small fw-bold text-danger';
            feedback.innerText = '❌ Failed to reach the server. Check your internet connection.';
        });
    }
</script>

<style>
    .settings-tab { padding: 1rem 1.25rem; border-radius: 12px; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; gap: 0.75rem; }
    .settings-tab:hover { background-color: #f8fafc; }
    .settings-tab.active { background-color: #fff; cursor: default; }

    .custom-input { transition: all 0.2s; border: 2px solid #f1f5f9; }
    .custom-input:focus { border-color: #0d6efd; box-shadow: 0 0 0 4px rgba(13,110,253,0.1); background-color: #fff !important; }
    
    .border-dashed { border-style: dashed !important; }
    
    .cursor-pointer { cursor: pointer; }
    .action-tag:hover { border-color: #0d6efd !important; color: #0d6efd !important; }
    
    .custom-hover-dash:hover { border-color: #0d6efd !important; background-color: rgba(13,110,253,0.05) !important; }
    .custom-hover-card { transition: all 0.2s; }
    .custom-hover-card:hover { border-color: #cbd5e1 !important; background: #fff !important; box-shadow: 0 .125rem .25rem rgba(0,0,0,.075); transform: translateY(-1px); }
    
    .hover-text-primary:hover { color: #0d6efd !important; border-color: #0d6efd !important; }
    .hover-bg-danger:hover { background-color: #dc3545 !important; color: white !important; }
</style>
@endpush
@endsection
