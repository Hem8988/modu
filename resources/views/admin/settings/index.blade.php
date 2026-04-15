@extends('layouts.admin')

@section('content')
<div style="max-width: 1200px; margin: 0 auto; padding: 20px;">
    
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 40px;">
        <div>
            <h1 style="font-size: 32px; font-weight: 900; color: #0f172a; letter-spacing: -1px; margin: 0;">Global Settings</h1>
            <p style="color: #64748b; font-weight: 500; margin-top: 5px;">Configure your system-wide SEO, branding, and tracking integrations.</p>
        </div>
        <div style="padding: 10px 20px; background: rgba(37,99,235,0.05); border-radius: 12px; border: 1px solid rgba(37,99,235,0.1);">
            <span style="font-size: 12px; font-weight: 800; color: #2563eb; text-transform: uppercase; letter-spacing: 1px;">System Health: Optimal ⚡</span>
        </div>
    </div>

    @if(session('success'))
        <div style="background: #ecfdf5; border: 1px solid #10b981; color: #065f46; padding: 16px; border-radius: 12px; margin-bottom: 30px; font-weight: 600;">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        
        <div style="display: grid; grid-template-columns: 300px 1fr; gap: 40px;">
            
            {{-- Navigation Tabs --}}
            <div style="display: flex; flex-direction: column; gap: 8px;">
                <div class="settings-tab active-tab" onclick="switchTab('seo', this)" style="padding: 15px 20px; background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; font-weight: 800; color: #0f172a; cursor: pointer; display: flex; align-items: center; gap: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
                    <span style="font-size: 20px;">🔍</span> SEO & Metadata
                </div>
                <div class="settings-tab" onclick="switchTab('tracking', this)" style="padding: 15px 20px; color: #64748b; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 12px;">
                    <span style="font-size: 20px;">🛠️</span> Tracking Scripts
                </div>
                <div class="settings-tab" onclick="switchTab('email', this)" style="padding: 15px 20px; color: #64748b; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 12px;">
                    <span style="font-size: 20px;">📧</span> Email Configuration
                </div>
                <div class="settings-tab" onclick="switchTab('sms', this)" style="padding: 15px 20px; color: #64748b; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 12px;">
                    <span style="font-size: 20px;">📱</span> SMS Automation
                </div>
                <div class="settings-tab" onclick="switchTab('master-products', this)" style="padding: 15px 20px; color: #64748b; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 12px;">
                    <span style="font-size: 20px;">📋</span> Master Product Options
                </div>
                <div class="settings-tab" onclick="switchTab('company', this)" style="padding: 15px 20px; color: #64748b; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 12px;">
                    <span style="font-size: 20px;">🏢</span> Company Details
                </div>


            </div>

            {{-- Settings Content --}}
            <div style="display: flex; flex-direction: column; gap: 30px;">
                
                {{-- SEO Card --}}
                <div id="section-seo" class="settings-section" style="background: #fff; border: 1px solid #e2e8f0; border-radius: 24px; padding: 40px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.02);">
                    <h2 style="font-size: 20px; font-weight: 800; color: #0f172a; margin-bottom: 30px; display: flex; align-items: center; gap: 12px;">
                        SEO Configuration
                    </h2>

                    <div style="display: flex; flex-direction: column; gap: 24px;">
                        <div class="form-group">
                            <label style="display: block; font-size: 11px; font-weight: 900; color: #64748b; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 1px;">Application / Site Name</label>
                            <input type="text" name="site_name" value="{{ $settings['site_name'] ?? 'ModuShade CRM' }}" 
                                   style="width: 100%; padding: 14px 20px; border-radius: 12px; border: 2px solid #f1f5f9; font-weight: 600; font-family: inherit; outline: none; transition: all 0.2s;" 
                                   onfocus="this.style.borderColor='#2563eb'; this.style.boxShadow='0 0 0 4px rgba(37,99,235,0.1)';" 
                                   onblur="this.style.borderColor='#f1f5f9'; this.style.boxShadow='none';">
                        </div>

                        <div class="form-group">
                            <label style="display: block; font-size: 11px; font-weight: 900; color: #64748b; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 1px;">Meta Title Pattern</label>
                            <input type="text" name="seo_title" value="{{ $settings['seo_title'] ?? 'ModuShade - Industrial Shade Solutions' }}" 
                                   style="width: 100%; padding: 14px 20px; border-radius: 12px; border: 2px solid #f1f5f9; font-weight: 600; font-family: inherit; outline: none; transition: all 0.2s;" 
                                   onfocus="this.style.borderColor='#2563eb'; this.style.boxShadow='0 0 0 4px rgba(37,99,235,0.1)';" 
                                   onblur="this.style.borderColor='#f1f5f9'; this.style.boxShadow='none';">
                            <span style="font-size: 12px; color: #94a3b8; margin-top: 6px; display: block;">Default title used when no specific page title is set.</span>
                        </div>

                        <div class="form-group">
                            <label style="display: block; font-size: 11px; font-weight: 900; color: #64748b; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 1px;">Global Meta Description</label>
                            <textarea name="seo_description" rows="3" 
                                   style="width: 100%; padding: 14px 20px; border-radius: 12px; border: 2px solid #f1f5f9; font-weight: 600; font-family: inherit; outline: none; transition: all 0.2s; resize: vertical;" 
                                   onfocus="this.style.borderColor='#2563eb'; this.style.boxShadow='0 0 0 4px rgba(37,99,235,0.1)';" 
                                   onblur="this.style.borderColor='#f1f5f9'; this.style.boxShadow='none';">{{ $settings['seo_description'] ?? '' }}</textarea>
                        </div>

                        <div class="form-group">
                            <label style="display: block; font-size: 11px; font-weight: 900; color: #64748b; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 1px;">Focus Keywords</label>
                            <input type="text" name="seo_keywords" value="{{ $settings['seo_keywords'] ?? 'CRM, Industrial Shade, ModuShade' }}" 
                                   style="width: 100%; padding: 14px 20px; border-radius: 12px; border: 2px solid #f1f5f9; font-weight: 600; font-family: inherit; outline: none; transition: all 0.2s;" 
                                   onfocus="this.style.borderColor='#2563eb'; this.style.boxShadow='0 0 0 4px rgba(37,99,235,0.1)';" 
                                   onblur="this.style.borderColor='#f1f5f9'; this.style.boxShadow='none';">
                        </div>
                    </div>
                </div>

                {{-- Company Details Card --}}
                <div id="section-company" class="settings-section" style="display: none; background: #fff; border: 1px solid #e2e8f0; border-radius: 24px; padding: 40px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.02);">
                    <h2 style="font-size: 20px; font-weight: 800; color: #0f172a; margin-bottom: 30px; display: flex; align-items: center; gap: 12px;">
                        Company Information
                    </h2>
                    <p style="color: #64748b; font-size: 13px; margin-top: -20px; margin-bottom: 30px;">These details will heavily appear on client-facing documents like Invoices, Quotes, and System Footers.</p>

                    <div style="display: flex; flex-direction: column; gap: 24px;">
                        <div class="form-group">
                            <label style="display: block; font-size: 11px; font-weight: 900; color: #64748b; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 1px;">Official Entity Name</label>
                            <input type="text" name="company_name" value="{{ $settings['company_name'] ?? 'ModuShade Industrial' }}" 
                                   style="width: 100%; padding: 14px 20px; border-radius: 12px; border: 2px solid #f1f5f9; font-weight: 600; font-family: inherit; outline: none; transition: all 0.2s;" 
                                   onfocus="this.style.borderColor='#2563eb'; this.style.boxShadow='0 0 0 4px rgba(37,99,235,0.1)';" 
                                   onblur="this.style.borderColor='#f1f5f9'; this.style.boxShadow='none';">
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                            <div class="form-group">
                                <label style="display: block; font-size: 11px; font-weight: 900; color: #64748b; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 1px;">Primary Phone</label>
                                <input type="text" name="company_phone" value="{{ $settings['company_phone'] ?? '+1 201 660 5298' }}" 
                                       style="width: 100%; padding: 14px 20px; border-radius: 12px; border: 2px solid #f1f5f9; font-weight: 600; font-family: inherit; outline: none; transition: all 0.2s;" 
                                       onfocus="this.style.borderColor='#2563eb';" onblur="this.style.borderColor='#f1f5f9';">
                            </div>
                            <div class="form-group">
                                <label style="display: block; font-size: 11px; font-weight: 900; color: #64748b; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 1px;">Business Email</label>
                                <input type="email" name="company_email" value="{{ $settings['company_email'] ?? 'info@modu-shade.com' }}" 
                                       style="width: 100%; padding: 14px 20px; border-radius: 12px; border: 2px solid #f1f5f9; font-weight: 600; font-family: inherit; outline: none; transition: all 0.2s;" 
                                       onfocus="this.style.borderColor='#2563eb';" onblur="this.style.borderColor='#f1f5f9';">
                            </div>
                        </div>

                        <div class="form-group">
                            <label style="display: block; font-size: 11px; font-weight: 900; color: #64748b; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 1px;">Company Website</label>
                            <input type="text" name="company_website" value="{{ $settings['company_website'] ?? 'info.modu-shade.com' }}" 
                                   style="width: 100%; padding: 14px 20px; border-radius: 12px; border: 2px solid #f1f5f9; font-weight: 600; font-family: inherit; outline: none; transition: all 0.2s;" 
                                   onfocus="this.style.borderColor='#2563eb';" onblur="this.style.borderColor='#f1f5f9';">
                        </div>

                        <div class="form-group">
                            <label style="display: block; font-size: 11px; font-weight: 900; color: #64748b; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 1px;">Address Line 1 (Street)</label>
                            <input type="text" name="company_address_1" value="{{ $settings['company_address_1'] ?? '24 Poplar Street' }}" 
                                   style="width: 100%; padding: 14px 20px; border-radius: 12px; border: 2px solid #f1f5f9; font-weight: 600; font-family: inherit; outline: none; transition: all 0.2s;" 
                                   onfocus="this.style.borderColor='#2563eb';" onblur="this.style.borderColor='#f1f5f9';">
                        </div>

                        <div class="form-group">
                            <label style="display: block; font-size: 11px; font-weight: 900; color: #64748b; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 1px;">Address Line 2 (City, State, Zip)</label>
                            <input type="text" name="company_address_2" value="{{ $settings['company_address_2'] ?? 'Creskill, NJ 07626' }}" 
                                   style="width: 100%; padding: 14px 20px; border-radius: 12px; border: 2px solid #f1f5f9; font-weight: 600; font-family: inherit; outline: none; transition: all 0.2s;" 
                                   onfocus="this.style.borderColor='#2563eb';" onblur="this.style.borderColor='#f1f5f9';">
                        </div>
                    </div>
                </div>

                {{-- Tracking Card --}}
                <div id="section-tracking" class="settings-section" style="display: none; background: #fff; border: 1px solid #e2e8f0; border-radius: 24px; padding: 40px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.02);">
                    <h2 style="font-size: 20px; font-weight: 800; color: #0f172a; margin-bottom: 30px; display: flex; align-items: center; gap: 12px;">
                        Custom Scripts & Tracking
                    </h2>

                    <div style="display: flex; flex-direction: column; gap: 24px;">
                        <div style="margin-bottom: 24px;">
                            <label style="display: block; font-size: 11px; font-weight: 900; color: #64748b; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 1px;">Header Scripts (Pixel, Analytics)</label>
                            <textarea name="header_scripts" rows="6" 
                                   style="width: 100%; padding: 14px 20px; border-radius: 12px; border: 2px solid #f1f5f9; font-weight: 500; font-family: 'Courier New', monospace; font-size: 13px; outline: none; transition: all 0.2s; resize: vertical; background: #f8fafc;" 
                                   onfocus="this.style.borderColor='#2563eb'; this.style.background='#fff';" 
                                   onblur="this.style.borderColor='#f1f5f9'; this.style.background='#f8fafc';"
                                   placeholder="Paste Google Analytics, FB Pixel, etc. here..."><?= ($settings['header_scripts'] ?? '') ?></textarea>
                            <span style="font-size: 12px; color: #94a3b8; margin-top: 6px; display: block;">These scripts are injected into the &lt;head&gt; section.</span>
                        </div>

                        <div style="margin-bottom: 24px;">
                            <label style="display: block; font-size: 11px; font-weight: 900; color: #64748b; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 1px;">Body Scripts (After Open Body Tag)</label>
                            <textarea name="body_scripts" rows="6" 
                                   style="width: 100%; padding: 14px 20px; border-radius: 12px; border: 2px solid #f1f5f9; font-weight: 500; font-family: 'Courier New', monospace; font-size: 13px; outline: none; transition: all 0.2s; resize: vertical; background: #f8fafc;" 
                                   onfocus="this.style.borderColor='#2563eb'; this.style.background='#fff';" 
                                   onblur="this.style.borderColor='#f1f5f9'; this.style.background='#f8fafc';"
                                   placeholder="Paste GTM noscript or other body opening scripts..."><?= ($settings['body_scripts'] ?? '') ?></textarea>
                            <span style="font-size: 12px; color: #94a3b8; margin-top: 6px; display: block;">These scripts are injected immediately after the opening &lt;body&gt; tag.</span>
                        </div>

                        <div>
                            <label style="display: block; font-size: 11px; font-weight: 900; color: #64748b; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 1px;">Footer Scripts (Before Close Body Tag)</label>
                            <textarea name="footer_scripts" rows="6" 
                                   style="width: 100%; padding: 14px 20px; border-radius: 12px; border: 2px solid #f1f5f9; font-weight: 500; font-family: 'Courier New', monospace; font-size: 13px; outline: none; transition: all 0.2s; resize: vertical; background: #f8fafc;" 
                                   onfocus="this.style.borderColor='#2563eb'; this.style.background='#fff';" 
                                   onblur="this.style.borderColor='#f1f5f9'; this.style.background='#f8fafc';"
                                   placeholder="Paste chat widgets or other footer scripts..."><?= ($settings['footer_scripts'] ?? '') ?></textarea>
                            <span style="font-size: 12px; color: #94a3b8; margin-top: 6px; display: block;">These scripts are injected just before the closing &lt;/body&gt; tag.</span>
                        </div>
                    </div>
                </div>

                {{-- Email Card --}}
                <div id="section-email" class="settings-section" style="display: none; background: #fff; border: 1px solid #e2e8f0; border-radius: 24px; padding: 40px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.02);">
                    <h2 style="font-size: 20px; font-weight: 800; color: #0f172a; margin-bottom: 30px; display: flex; align-items: center; gap: 12px;">
                        SMTP / Email Configuration
                    </h2>

                    <div style="display: flex; flex-direction: column; gap: 24px;">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div class="form-group">
                                <label style="display: block; font-size: 11px; font-weight: 900; color: #64748b; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 1px;">Mail Mailer</label>
                                <select name="mail_mailer" style="width: 100%; padding: 14px 20px; border-radius: 12px; border: 2px solid #f1f5f9; font-weight: 600; outline: none; background: #fff;">
                                    <option value="smtp" {{ ($settings['mail_mailer'] ?? 'log') === 'smtp' ? 'selected' : '' }}>SMTP</option>
                                    <option value="log" {{ ($settings['mail_mailer'] ?? 'log') === 'log' ? 'selected' : '' }}>Log (Testing)</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label style="display: block; font-size: 11px; font-weight: 900; color: #64748b; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 1px;">Encryption</label>
                                <select name="mail_encryption" style="width: 100%; padding: 14px 20px; border-radius: 12px; border: 2px solid #f1f5f9; font-weight: 600; outline: none; background: #fff;">
                                    <option value="tls" {{ ($settings['mail_encryption'] ?? 'tls') === 'tls' ? 'selected' : '' }}>TLS</option>
                                    <option value="ssl" {{ ($settings['mail_encryption'] ?? 'tls') === 'ssl' ? 'selected' : '' }}>SSL</option>
                                    <option value="null" {{ ($settings['mail_encryption'] ?? 'tls') === 'null' ? 'selected' : '' }}>None</option>
                                </select>
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
                            <div class="form-group">
                                <label style="display: block; font-size: 11px; font-weight: 900; color: #64748b; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 1px;">Mail Host</label>
                                <input type="text" name="mail_host" value="{{ $settings['mail_host'] ?? '' }}" placeholder="smtp.mailtrap.io"
                                       style="width: 100%; padding: 14px 20px; border-radius: 12px; border: 2px solid #f1f5f9; font-weight: 600; outline: none;">
                            </div>
                            <div class="form-group">
                                <label style="display: block; font-size: 11px; font-weight: 900; color: #64748b; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 1px;">Mail Port</label>
                                <input type="text" name="mail_port" value="{{ $settings['mail_port'] ?? '587' }}" placeholder="587"
                                       style="width: 100%; padding: 14px 20px; border-radius: 12px; border: 2px solid #f1f5f9; font-weight: 600; outline: none;">
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div class="form-group">
                                <label style="display: block; font-size: 11px; font-weight: 900; color: #64748b; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 1px;">Username</label>
                                <input type="text" name="mail_username" value="{{ $settings['mail_username'] ?? '' }}" 
                                       style="width: 100%; padding: 14px 20px; border-radius: 12px; border: 2px solid #f1f5f9; font-weight: 600; outline: none;">
                            </div>
                            <div class="form-group">
                                <label style="display: block; font-size: 11px; font-weight: 900; color: #64748b; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 1px;">Password</label>
                                <input type="password" name="mail_password" value="{{ $settings['mail_password'] ?? '' }}" 
                                       style="width: 100%; padding: 14px 20px; border-radius: 12px; border: 2px solid #f1f5f9; font-weight: 600; outline: none;">
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div class="form-group">
                                <label style="display: block; font-size: 11px; font-weight: 900; color: #64748b; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 1px;">From Address</label>
                                <input type="email" name="mail_from_address" value="{{ $settings['mail_from_address'] ?? '' }}" placeholder="hello@modushade.com"
                                       style="width: 100%; padding: 14px 20px; border-radius: 12px; border: 2px solid #f1f5f9; font-weight: 600; outline: none;">
                            </div>
                            <div class="form-group">
                                <label style="display: block; font-size: 11px; font-weight: 900; color: #64748b; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 1px;">From Name</label>
                                <input type="text" name="mail_from_name" value="{{ $settings['mail_from_name'] ?? 'Modu Shade' }}" 
                                       style="width: 100%; padding: 14px 20px; border-radius: 12px; border: 2px solid #f1f5f9; font-weight: 600; outline: none;">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SMS Card --}}
                <div id="section-sms" class="settings-section" style="display: none; background: #fff; border: 1px solid #e2e8f0; border-radius: 24px; padding: 40px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.02);">
                    <h2 style="font-size: 20px; font-weight: 800; color: #0f172a; margin-bottom: 30px; display: flex; align-items: center; gap: 12px;">
                        SMS Lead Automation
                    </h2>

                    <div style="display: flex; flex-direction: column; gap: 24px;">
                        <div style="display: flex; align-items: center; justify-content: space-between; padding: 20px; background: #f8fafc; border-radius: 16px; border: 2px solid #f1f5f9;">
                            <div>
                                <h4 style="margin: 0; font-size: 14px; font-weight: 800; color: #0f172a;">Enable First-Touch Automation</h4>
                                <p style="margin: 5px 0 0; font-size: 13px; color: #64748b;">Automatically send a text message when a new lead arrives.</p>
                            </div>
                            <label class="switch" style="position: relative; display: inline-block; width: 50px; height: 26px;">
                                <input type="checkbox" name="sms_enabled" {{ ($settings['sms_enabled'] ?? 'off') === 'on' ? 'checked' : '' }} style="opacity: 0; width: 0; height: 0; position: absolute;">
                                <span class="slider" style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #cbd5e1; transition: .4s; border-radius: 34px;"></span>
                            </label>
                        </div>

                        <div id="sms-sequence-container" style="display: flex; flex-direction: column; gap: 24px;">
                            {{-- Steps will be injected here by JS --}}
                        </div>

                        <button type="button" onclick="addStep()" style="width: 100%; padding: 16px; border: 2px dashed #e2e8f0; border-radius: 16px; background: #f8fafc; color: #64748b; font-weight: 800; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; justify-content: center; gap: 10px;" onmouseover="this.style.borderColor='#2563eb'; this.style.color='#2563eb';" onmouseout="this.style.borderColor='#e2e8f0'; this.style.color='#64748b';">
                            <span>➕</span> Add New Follow-up Step
                        </button>
                        
                        <input type="hidden" name="sms_sequence" id="sms_sequence_input">
                        
                        <div style="margin-top: 20px; padding-top: 30px; border-top: 1px dashed #e2e8f0;">
                            <h4 style="margin: 0 0 20px; font-size: 14px; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                                <span style="font-size: 18px;">🔑</span> Advanced Connectivity (Twilio API)
                            </h4>
                            
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                                <div class="form-group">
                                    <label style="display: block; font-size: 11px; font-weight: 900; color: #64748b; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 1px;">Twilio Account SID</label>
                                    <input type="text" name="twilio_sid" value="{{ $settings['twilio_sid'] ?? '' }}" 
                                           style="width: 100%; padding: 14px 20px; border-radius: 12px; border: 2px solid #f1f5f9; font-weight: 600; outline: none; transition: 0.2s;" 
                                           onfocus="this.style.borderColor='#2563eb';" onblur="this.style.borderColor='#f1f5f9';"
                                           placeholder="ACxxxxxxxxxxxxxxxxxxxxxxxx">
                                </div>
                                <div class="form-group">
                                    <label style="display: block; font-size: 11px; font-weight: 900; color: #64748b; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 1px;">Twilio Auth Token</label>
                                    <input type="password" name="twilio_token" value="{{ $settings['twilio_token'] ?? '' }}" 
                                           style="width: 100%; padding: 14px 20px; border-radius: 12px; border: 2px solid #f1f5f9; font-weight: 600; outline: none; transition: 0.2s;" 
                                           onfocus="this.style.borderColor='#2563eb';" onblur="this.style.borderColor='#f1f5f9';"
                                           placeholder="••••••••••••••••••••••••">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label style="display: block; font-size: 11px; font-weight: 900; color: #64748b; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 1px;">Twilio Sender Number (From)</label>
                                <input type="text" name="twilio_from" value="{{ $settings['twilio_from'] ?? '' }}" 
                                       style="width: 100%; padding: 14px 20px; border-radius: 12px; border: 2px solid #f1f5f9; font-weight: 600; outline: none; transition: 0.2s;" 
                                       onfocus="this.style.borderColor='#2563eb';" onblur="this.style.borderColor='#f1f5f9';"
                                       placeholder="+1234567890">
                                <span style="font-size: 12px; color: #94a3b8; margin-top: 6px; display: block;">Your verified Twilio phone number or Messaging Service SID.</span>
                            </div>
                        </div>
                    </div>
                </div>


                {{-- Master Product Options Card --}}
                <div id="section-master-products" class="settings-section" style="display: none; background: #fff; border: 1px solid #e2e8f0; border-radius: 24px; padding: 40px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.02);">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 30px; gap: 20px;">
                        <div>
                            <h2 style="font-size: 20px; font-weight: 800; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 12px;">
                                Global Attribute Registry
                            </h2>
                            <p style="color: #64748b; font-size: 14px; margin-top: 8px;">These options will appear as "Quick Add" buttons when you manage products in the Catalog.</p>
                        </div>
                        <button type="button" onclick="openMasterAttrModal()" style="background: var(--gold); color: white; border: none; padding: 12px 24px; border-radius: 12px; font-size: 13px; font-weight: 800; cursor: pointer; box-shadow: 0 4px 12px rgba(184, 155, 94, 0.2); white-space: nowrap;">
                            + Add Global Option
                        </button>
                    </div>

                    <div style="display: grid; gap: 16px;">
                        @forelse($masterAttributes as $ma)
                            <div style="background: #f8fafc; border: 1px solid #f1f5f9; padding: 20px; border-radius: 16px; display: flex; justify-content: space-between; align-items: center; transition: all 0.2s;" onmouseover="this.style.borderColor='#e2e8f0'; this.style.background='#fff';">
                                <div>
                                    <div style="font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 10px; font-size: 16px;">
                                        {{ $ma->label }}
                                        @if(!$ma->is_active) <span style="font-size: 10px; background: #fee2e2; color: #ef4444; padding: 3px 8px; border-radius: 6px; font-weight: 900; text-transform: uppercase;">Inactive</span> @endif
                                    </div>
                                    <div style="font-size: 13px; color: #64748b; margin-top: 6px; font-weight: 500; display: flex; gap: 15px;">
                                        <span><span style="color: #94a3b8; font-weight: 700;">Values:</span> {{ $ma->default_values ?: '(Custom)' }}</span>
                                        @if($ma->default_price > 0)
                                            <span style="color: #10b981; font-weight: 700;">+ ${{ number_format($ma->default_price, 2) }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div style="display: flex; gap: 10px;">
                                    <button type="button" onclick='openMasterAttrModal({{ $ma->id }}, "{{ addslashes($ma->label) }}", "{{ addslashes($ma->default_values) }}", {{ $ma->is_active ? 1 : 0 }}, {{ $ma->default_price }})' 
                                            style="background: #fff; border: 1px solid #e2e8f0; color: #64748b; width: 38px; height: 38px; border-radius: 10px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: 0.2s;" onmouseover="this.style.color='#2563eb'; this.style.borderColor='#2563eb';">✎</button>
                                    <button type="submit" form="delete-ma-{{ $ma->id }}" style="background: #fff; border: 1px solid #fee2e2; color: #ef4444; width: 38px; height: 38px; border-radius: 10px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: 0.2s;" onmouseover="this.style.background='#ef4444'; this.style.color='#fff';">✕</button>
                                </div>
                            </div>
                        @empty
                            <div style="text-align: center; padding: 60px; border: 2px dashed #f1f5f9; border-radius: 20px;">
                                <div style="font-size: 40px; margin-bottom: 16px; opacity: 0.3;">📋</div>
                                <h4 style="color: #64748b; margin: 0;">No Master Options Defined</h4>
                                <p style="color: #94a3b8; font-size: 13px; margin-top: 5px;">Add global options to speed up your product cataloging.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 16px; padding-bottom: 40px;">
                    <button type="reset" style="padding: 14px 28px; border-radius: 12px; border: 1px solid #e2e8f0; background: #fff; font-weight: 800; color: #64748b; cursor: pointer;">Discard Changes</button>
                    <button type="submit" style="padding: 14px 40px; border-radius: 12px; border: none; background: #2563eb; color: #fff; font-weight: 800; cursor: pointer; box-shadow: 0 10px 15px -3px rgba(37,99,235,0.25);">Save Settings ➔</button>
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
<div id="master-attr-modal" style="position: fixed; inset: 0; background: rgba(15,23,42,0.6); backdrop-filter: blur(8px); display: none; align-items: center; justify-content: center; z-index: 9999; padding: 20px;">
    <div style="background: #fff; width: 100%; max-width: 500px; border-radius: 24px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); overflow: hidden;">
        <div style="padding: 24px 28px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
            <h3 id="modal-attr-title" style="margin: 0; font-size: 18px; font-weight: 900; color: #0f172a;">📋 Configure Global Option</h3>
            <button onclick="closeMasterAttrModal()" style="background: none; border: none; font-size: 24px; color: #64748b; cursor: pointer;">&times;</button>
        </div>
        <form id="master-attr-form" method="POST">
            @csrf
            <div id="modal-attr-method"></div>
            <div style="padding: 28px;">
                <div style="display: grid; gap: 20px;">
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 900; color: #64748b; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 1px;">Option Label</label>
                        <input type="text" name="label" id="ma_label" required placeholder="e.g. Fabric Type" 
                               style="width: 100%; padding: 12px 16px; border-radius: 12px; border: 2px solid #f1f5f9; font-weight: 600; outline: none;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 900; color: #64748b; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 1px;">Default Surcharge ($)</label>
                        <input type="number" step="0.01" name="default_price" id="ma_price" placeholder="0.00" 
                               style="width: 100%; padding: 12px 16px; border-radius: 12px; border: 2px solid #f1f5f9; font-weight: 600; outline: none;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 900; color: #64748b; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 1px;">Default Multi-Selection Values</label>
                        <textarea name="default_values" id="ma_values" rows="3" placeholder="e.g. Scala, Deco, Silk (Separate by comma)" 
                                  style="width: 100%; padding: 12px 16px; border-radius: 12px; border: 2px solid #f1f5f9; font-weight: 600; outline: none; resize: none;"></textarea>
                        <span style="font-size: 12px; color: #94a3b8; margin-top: 6px; display: block;">Separate values with a comma. These will be clickable shortcuts.</span>
                    </div>
                </div>
            </div>
            <div style="padding: 20px 28px; background: #fafafa; border-top: 1px solid #e2e8f0; text-align: right; display: flex; justify-content: flex-end; gap: 12px;">
                <button type="button" onclick="closeMasterAttrModal()" style="padding: 10px 20px; border-radius: 12px; border: none; background: #e2e8f0; color: #64748b; font-weight: 700; cursor: pointer;">Cancel</button>
                <button type="submit" id="ma_submit_btn" style="padding: 10px 24px; border-radius: 12px; border: none; background: #2563eb; color: #fff; font-weight: 800; cursor: pointer;">Save Option</button>
            </div>
        </form>
    </div>
</div>

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
        
        document.getElementById('master-attr-modal').style.display = 'flex';
    }

    function closeMasterAttrModal() {
        document.getElementById('master-attr-modal').style.display = 'none';
    }

    // Auto-switch to Master tab if redirected back after save
    window.addEventListener('DOMContentLoaded', (event) => {
        if (window.location.hash === '#master') {
            switchTab('master-products', document.querySelector('[onclick*="master-products"]'));
        }
    });

    function switchTab(sectionId, el) {
        // Hide all sections
        document.querySelectorAll('.settings-section').forEach(section => {
            section.style.display = 'none';
        });

        // Show target section
        document.getElementById('section-' + sectionId).style.display = 'block';

        // Update tab styles
        document.querySelectorAll('.settings-tab').forEach(tab => {
            tab.style.background = 'transparent';
            tab.style.border = 'none';
            tab.style.boxShadow = 'none';
            tab.style.color = '#64748b';
            tab.style.fontWeight = '600';
        });

        // Set active tab style
        el.style.background = '#fff';
        el.style.border = '1px solid #e2e8f0';
        el.style.borderRadius = '12px';
        el.style.fontWeight = '800';
        el.style.color = '#0f172a';
        el.style.boxShadow = '0 4px 6px -1px rgba(0,0,0,0.05)';
    }

    // Multi-step SMS Sequence Logic
    let sequenceData = {!! $settings['sms_sequence'] ?? '[]' !!};


    const container = document.getElementById('sms-sequence-container');

    function renderSequence() {
        container.innerHTML = '';
        if (sequenceData.length === 0) {
            container.innerHTML = '<p style="text-align: center; color: #94a3b8; font-style: italic; padding: 20px;">No automation steps configured yet. Click the button below to start.</p>';
            return;
        }

        sequenceData.forEach((step, index) => {
            const stepHtml = `
                <div class="sequence-step" style="background: #fff; border: 2px solid #f1f5f9; border-radius: 20px; padding: 24px; position: relative;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px;">
                        <div>
                            <span style="display: inline-block; padding: 4px 12px; background: #2563eb; color: #fff; border-radius: 20px; font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Step ${index + 1}</span>
                            <h4 style="margin: 0; font-size: 16px; font-weight: 800; color: #0f172a;">Follow-up Message</h4>
                        </div>
                        <button type="button" onclick="removeStep(${index})" style="background: #fff; border: 1px solid #fee2e2; color: #ef4444; width: 32px; height: 32px; border-radius: 10px; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 18px;" title="Remove Step">×</button>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                        <div class="form-group">
                            <label style="display: block; font-size: 11px; font-weight: 900; color: #64748b; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 1px;">Wait After Previous Message</label>
                            <input type="number" onchange="updateStep(${index}, 'delay_value', this.value)" value="${step.delay_value || 0}" 
                                   style="width: 100%; padding: 12px 16px; border-radius: 12px; border: 2px solid #f1f5f9; font-weight: 600; outline: none; transition: 0.2s;" 
                                   onfocus="this.style.borderColor='#2563eb';" onblur="this.style.borderColor='#f1f5f9';">
                        </div>
                        <div class="form-group">
                            <label style="display: block; font-size: 11px; font-weight: 900; color: #64748b; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 1px;">Time Unit</label>
                            <select onchange="updateStep(${index}, 'delay_unit', this.value)" 
                                    style="width: 100%; padding: 12px 16px; border-radius: 12px; border: 2px solid #f1f5f9; font-weight: 600; outline: none; background: #fff;">
                                <option value="minutes" ${step.delay_unit === 'minutes' ? 'selected' : ''}>Minutes</option>
                                <option value="hours" ${step.delay_unit === 'hours' ? 'selected' : ''}>Hours</option>
                                <option value="days" ${step.delay_unit === 'days' ? 'selected' : ''}>Days</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label style="display: block; font-size: 11px; font-weight: 900; color: #64748b; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 1px;">Message Template</label>
                        <textarea onchange="updateStep(${index}, 'template', this.value)" rows="3" 
                               style="width: 100%; padding: 14px 20px; border-radius: 12px; border: 2px solid #f1f5f9; font-weight: 600; font-family: inherit; outline: none; transition: all 0.2s; resize: vertical;" 
                               onfocus="this.style.borderColor='#2563eb';" onblur="this.style.borderColor='#f1f5f9';"
                               placeholder="Enter SMS content...">${step.template || ''}</textarea>
                        <div style="margin-top: 10px; display: flex; gap: 8px; flex-wrap: wrap;">
                            <span style="font-size: 10px; background: #e2e8f0; padding: 4px 8px; border-radius: 6px; color: #475569; cursor: pointer;" onclick="insertTagAtStep(${index}, '@{{name}}')">@{{name}}</span>
                            <span style="font-size: 10px; background: #e2e8f0; padding: 4px 8px; border-radius: 6px; color: #475569; cursor: pointer;" onclick="insertTagAtStep(${index}, '@{{product_type}}')">@{{product_type}}</span>
                            <span style="font-size: 10px; background: #e2e8f0; padding: 4px 8px; border-radius: 6px; color: #475569; cursor: pointer;" onclick="insertTagAtStep(${index}, '@{{windows_count}}')">@{{windows_count}}</span>
                        </div>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', stepHtml);
        });
        
        // Final Sync to hidden input
        document.getElementById('sms_sequence_input').value = JSON.stringify(sequenceData);
    }

    function addStep() {
        sequenceData.push({
            delay_value: 2,
            delay_unit: 'minutes',
            template: "Hi @{{name}}, just checking in about your @{{product_type}} interest!"
        });
        renderSequence();
    }

    function removeStep(index) {
        if(confirm('Are you sure you want to remove this automation step?')) {
            sequenceData.splice(index, 1);
            renderSequence();
        }
    }

    function updateStep(index, field, value) {
        sequenceData[index][field] = value;
        document.getElementById('sms_sequence_input').value = JSON.stringify(sequenceData);
    }

    function insertTagAtStep(index, tag) {
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
</script>

<style>
    /* Switch Slider Styling */
    .switch input:checked + .slider { background-color: #2563eb; }
    .switch input:checked + .slider:before { transform: translateX(24px); }
    .slider:before {
        position: absolute; content: ""; height: 18px; width: 18px; left: 4px; bottom: 4px;
        background-color: white; transition: .4s; border-radius: 50%;
    }
</style>
@endsection
