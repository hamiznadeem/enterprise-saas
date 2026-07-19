<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Start Free Trial — SwiftPOS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        *{font-family:'Inter',sans-serif;margin:0;padding:0;box-sizing:border-box;}
        body{background:#f0f4f8;min-height:100vh;}

        /* ── Top Bar ── */
        .top-bar{background:#fff;border-bottom:1px solid #e2e8f0;padding:0.75rem 1.5rem;display:flex;align-items:center;justify-content:space-between;}
        .top-bar .logo{display:flex;align-items:center;gap:0.5rem;text-decoration:none;}
        .top-bar .logo-icon{width:2rem;height:2rem;background:#2563eb;border-radius:0.5rem;display:flex;align-items:center;justify-content:center;}
        .top-bar .logo-text{font-weight:700;font-size:1.125rem;color:#1e293b;}
        .top-bar .login-link{font-size:0.8125rem;color:#64748b;text-decoration:none;font-weight:500;}
        .top-bar .login-link:hover{color:#2563eb;}

        /* ── Hero ── */
        .hero{text-align:center;padding:2.5rem 1rem 1.5rem;}
        .hero h1{font-size:1.75rem;font-weight:800;color:#0f172a;line-height:1.3;}
        .hero h1 span{color:#2563eb;}
        .hero p{font-size:0.9375rem;color:#64748b;margin-top:0.5rem;}

        /* ── Container ── */
        .container{max-width:40rem;margin:0 auto;padding:0 1rem 3rem;}

        /* ── Section Label ── */
        .section-label{font-size:0.875rem;font-weight:600;color:#334155;margin-bottom:0.75rem;}

        /* ── Business Type Cards ── */
        .biz-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:0.625rem;margin-bottom:1.75rem;}
        .biz-card{background:#fff;border:2px solid #e2e8f0;border-radius:0.875rem;padding:1.25rem 0.75rem;text-align:center;cursor:pointer;transition:all 0.2s ease;user-select:none;position:relative;}
        .biz-card:hover{border-color:#93c5fd;background:#f8faff;}
        .biz-card.active{border-color:#2563eb;background:#eff6ff;box-shadow:0 0 0 3px rgba(37,99,235,0.12);}
        .biz-card .biz-icon{width:2.75rem;height:2.75rem;border-radius:0.75rem;display:flex;align-items:center;justify-content:center;margin:0 auto 0.5rem;font-size:1.25rem;}
        .biz-card .biz-label{font-size:0.75rem;font-weight:600;color:#334155;line-height:1.3;}
        .biz-card.active .biz-label{color:#2563eb;}
        .biz-card .check-mark{position:absolute;top:0.375rem;right:0.5rem;width:1.125rem;height:1.125rem;background:#2563eb;border-radius:50%;display:none;align-items:center;justify-content:center;}
        .biz-card.active .check-mark{display:flex;}

        /* ── Outlet Pills ── */
        .outlet-pills{display:flex;gap:0.625rem;flex-wrap:wrap;margin-bottom:1.75rem;}
        .outlet-pill{background:#fff;border:2px solid #e2e8f0;border-radius:2rem;padding:0.5rem 1.5rem;font-size:0.8125rem;font-weight:600;color:#475569;cursor:pointer;transition:all 0.2s ease;user-select:none;}
        .outlet-pill:hover{border-color:#93c5fd;}
        .outlet-pill.active{border-color:#2563eb;background:#2563eb;color:#fff;}

        /* ── Form Card ── */
        .form-card{background:#fff;border-radius:1rem;border:1px solid #e2e8f0;padding:1.75rem;margin-bottom:2rem;}

        /* ── Input ── */
        .field{margin-bottom:1rem;}
        .field label{display:block;font-size:0.8125rem;font-weight:500;color:#475569;margin-bottom:0.375rem;}
        .field input{width:100%;padding:0.6875rem 0.875rem;border:1.5px solid #e2e8f0;border-radius:0.625rem;font-size:0.875rem;outline:none;transition:border-color 0.15s,box-shadow 0.15s;background:#f8fafc;}
        .field input:focus{border-color:#2563eb;box-shadow:0 0 0 3px rgba(37,99,235,0.08);background:#fff;}
        .field input::placeholder{color:#94a3b8;}
        .field-pw{position:relative;}
        .field-pw input{padding-right:2.75rem;}
        .toggle-pw{position:absolute;right:0.75rem;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#94a3b8;padding:0.25rem;}
        .toggle-pw:hover{color:#64748b;}

        /* ── URL Field ── */
        .url-field{display:flex;align-items:stretch;border:1.5px solid #e2e8f0;border-radius:0.625rem;overflow:hidden;transition:border-color 0.15s,box-shadow 0.15s;background:#f8fafc;}
        .url-field:focus-within{border-color:#2563eb;box-shadow:0 0 0 3px rgba(37,99,235,0.08);background:#fff;}
        .url-field input{border:none;background:transparent;outline:none;padding:0.6875rem 0.875rem;font-size:0.875rem;flex:1;color:#334155;font-weight:500;}
        .url-field input::placeholder{color:#94a3b8;font-weight:400;}
        .url-suffix{display:flex;align-items:center;padding:0 0.875rem;background:#f1f5f9;font-size:0.8125rem;color:#64748b;font-weight:500;white-space:nowrap;border-left:1px solid #e2e8f0;}

        /* ── Plan Cards ── */
        .plan-card{background:#f8fafc;border:2px solid #e2e8f0;border-radius:0.75rem;padding:1rem 0.75rem;text-align:center;cursor:pointer;transition:all 0.2s ease;user-select:none;position:relative;}
        .plan-card:hover{border-color:#93c5fd;}
        .plan-card.active{border-color:#2563eb;background:#eff6ff;box-shadow:0 0 0 3px rgba(37,99,235,0.12);}
        .plan-card .plan-name{font-size:0.8125rem;font-weight:700;color:#1e293b;margin-bottom:0.25rem;}
        .plan-card.active .plan-name{color:#2563eb;}
        .plan-card .plan-price{font-size:0.75rem;color:#64748b;}
        .plan-card .plan-trial{font-size:0.6875rem;color:#059669;font-weight:600;margin-top:0.25rem;}
        .plan-card .check-mark{position:absolute;top:0.25rem;right:0.375rem;width:1rem;height:1rem;background:#2563eb;border-radius:50%;display:none;align-items:center;justify-content:center;}
        .plan-card.active .check-mark{display:flex;}

        /* ── Error Box ── */
        .error-box{background:#fef2f2;border:1px solid #fecaca;border-radius:0.625rem;padding:0.75rem 1rem;margin-bottom:1rem;}
        .error-box ul{list-style:none;padding:0;margin:0;}
        .error-box li{font-size:0.8125rem;color:#dc2626;padding:0.125rem 0;}

        /* ── Honeypot ── */
        .hp-field{position:absolute;left:-9999px;opacity:0;height:0;overflow:hidden;}

        /* ── Submit Button ── */
        .btn-submit{width:100%;padding:0.8125rem;background:#2563eb;color:#fff;font-size:0.9375rem;font-weight:700;border:none;border-radius:0.625rem;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:0.5rem;transition:all 0.15s ease;box-shadow:0 4px 14px -2px rgba(37,99,235,0.35);letter-spacing:0.01em;}
        .btn-submit:hover{background:#1d4ed8;box-shadow:0 6px 20px -2px rgba(37,99,235,0.45);}
        .btn-submit:disabled{opacity:0.6;cursor:not-allowed;}
        .spinner{width:1.125rem;height:1.125rem;border:2.5px solid rgba(255,255,255,0.3);border-top-color:#fff;border-radius:50%;animation:spin 0.6s linear infinite;}
        @keyframes spin{to{transform:rotate(360deg);}}

        /* ── Testimonials ── */
        .testimonials{margin-top:1rem;}
        .testimonials h3{font-size:0.875rem;font-weight:700;color:#334155;text-align:center;margin-bottom:1rem;text-transform:uppercase;letter-spacing:0.05em;}
        .testi-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:0.75rem;}
        .testi-card{background:#fff;border:1px solid #e2e8f0;border-radius:0.875rem;padding:1.25rem;text-align:center;}
        .testi-card img{width:2.5rem;height:2.5rem;border-radius:50%;object-fit:cover;margin:0 auto 0.5rem;border:2px solid #e2e8f0;}
        .testi-card .testi-name{font-size:0.8125rem;font-weight:600;color:#1e293b;}
        .testi-card .testi-role{font-size:0.6875rem;color:#94a3b8;margin-bottom:0.5rem;}
        .testi-card .testi-text{font-size:0.75rem;color:#64748b;line-height:1.5;font-style:italic;}
        .testi-card .testi-stars{color:#f59e0b;font-size:0.625rem;margin-bottom:0.375rem;}

        /* ── Footer ── */
        .footer-text{text-align:center;font-size:0.75rem;color:#94a3b8;margin-top:1.5rem;}
        .footer-text a{color:#2563eb;text-decoration:none;font-weight:500;}
        .footer-text a:hover{text-decoration:underline;}

        /* ── Row Grid ── */
        .row-2{display:grid;grid-template-columns:1fr 1fr;gap:1rem;}

        /* ── Responsive ── */
        @media(max-width:640px){
            .biz-grid{grid-template-columns:repeat(2,1fr);}
            .testi-grid{grid-template-columns:1fr;}
            .plan-grid{grid-template-columns:1fr;}
            .hero h1{font-size:1.375rem;}
            .form-card{padding:1.25rem;}
            .row-2{grid-template-columns:1fr;}
        }
    </style>
</head>
<body>

    <!-- Top Bar -->
    <div class="top-bar">
        <a href="{{ route('landing') }}" class="logo">
            <div class="logo-icon"><i class="fa-solid fa-bolt" style="color:#fff;font-size:0.75rem;"></i></div>
            <span class="logo-text">SwiftPOS</span>
        </a>
        </div>

    <!-- Hero -->
    <div class="hero">
        <h1>Register your SwiftPOS today for <span>FREE</span></h1>
        <p>Get started in 2 minutes. No credit card required.</p>
    </div>

    <div class="container">

        <form method="POST" action="{{ route('trial.register') }}" id="trialForm">
            @csrf

            <!-- Honeypot -->
            <div class="hp-field"><input type="text" name="website" tabindex="-1" autocomplete="off"></div>

            @if ($errors->any())
                <div class="error-box">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li><i class="fa-solid fa-circle-exclamation" style="margin-right:0.25rem;"></i>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Business Type -->
            <p class="section-label">What type of business do you run?</p>
            <div class="biz-grid">
                <div class="biz-card @if(old('business_type') == 'mart') active @endif" onclick="selectBiz(this, 'mart')">
                    <div class="check-mark"><i class="fa-solid fa-check" style="color:#fff;font-size:0.5625rem;"></i></div>
                    <div class="biz-icon" style="background:#fff7ed;color:#ea580c;"><i class="fa-solid fa-cart-shopping"></i></div>
                    <div class="biz-label">Mart / Grocery</div>
                </div>
                <div class="biz-card @if(old('business_type') == 'restaurant') active @endif" onclick="selectBiz(this, 'restaurant')">
                    <div class="check-mark"><i class="fa-solid fa-check" style="color:#fff;font-size:0.5625rem;"></i></div>
                    <div class="biz-icon" style="background:#fef2f2;color:#dc2626;"><i class="fa-solid fa-utensils"></i></div>
                    <div class="biz-label">Restaurant</div>
                </div>
                <div class="biz-card @if(old('business_type') == 'cafe') active @endif" onclick="selectBiz(this, 'cafe')">
                    <div class="check-mark"><i class="fa-solid fa-check" style="color:#fff;font-size:0.5625rem;"></i></div>
                    <div class="biz-icon" style="background:#fffbeb;color:#d97706;"><i class="fa-solid fa-mug-hot"></i></div>
                    <div class="biz-label">Cafe / Bakery</div>
                </div>
                <div class="biz-card @if(old('business_type') == 'retail') active @endif" onclick="selectBiz(this, 'retail')">
                    <div class="check-mark"><i class="fa-solid fa-check" style="color:#fff;font-size:0.5625rem;"></i></div>
                    <div class="biz-icon" style="background:#fdf2f8;color:#db2777;"><i class="fa-solid fa-shirt"></i></div>
                    <div class="biz-label">Retail / Fashion</div>
                </div>
                <div class="biz-card @if(old('business_type') == 'clinic') active @endif" onclick="selectBiz(this, 'clinic')">
                    <div class="check-mark"><i class="fa-solid fa-check" style="color:#fff;font-size:0.5625rem;"></i></div>
                    <div class="biz-icon" style="background:#ecfdf5;color:#059669;"><i class="fa-solid fa-heart-pulse"></i></div>
                    <div class="biz-label">Clinic / Pharmacy</div>
                </div>
                <div class="biz-card @if(old('business_type') == 'general_store') active @endif" onclick="selectBiz(this, 'general_store')">
                    <div class="check-mark"><i class="fa-solid fa-check" style="color:#fff;font-size:0.5625rem;"></i></div>
                    <div class="biz-icon" style="background:#f5f3ff;color:#7c3aed;"><i class="fa-solid fa-store"></i></div>
                    <div class="biz-label">General Store</div>
                </div>
            </div>
            <input type="hidden" id="business_type" name="business_type" value="{{ old('business_type') }}">

            <!-- Number of Outlets -->
            <p class="section-label">Number of outlets maintained</p>
            <div class="outlet-pills">
                <div class="outlet-pill @if(old('outlets') == '1') active @endif" onclick="selectOutlet(this, '1')">1</div>
                <div class="outlet-pill @if(old('outlets') == '2-5') active @endif" onclick="selectOutlet(this, '2-5')">2 – 5</div>
                <div class="outlet-pill @if(old('outlets') == '6-10') active @endif" onclick="selectOutlet(this, '6-10')">6 – 10</div>
                <div class="outlet-pill @if(old('outlets') == '10+') active @endif" onclick="selectOutlet(this, '10+')">10+</div>
            </div>
            <input type="hidden" id="outlets" name="outlets" value="{{ old('outlets') }}">

            <!-- Details Form -->
            <div class="form-card">

                <!-- Row 1: Name + Phone -->
                <div class="row-2">
                    <div class="field">
                        <label for="owner_name">Your Name</label>
                        <input type="text" id="owner_name" name="owner_name" value="{{ old('owner_name') }}" required placeholder="Ahmed Khan">
                    </div>
                    <div class="field">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required placeholder="03XX-XXXXXXX">
                    </div>
                </div>

                <!-- Row 2: Business Name + City -->
                <div class="row-2">
                    <div class="field">
                        <label for="business_name">Shop / Business Name</label>
                        <input type="text" id="business_name" name="business_name" value="{{ old('business_name') }}" required placeholder="My Super Mart" oninput="autoSlug()">
                    </div>
                    <div class="field">
                        <label for="city">City</label>
                        <input type="text" id="city" name="city" value="{{ old('city') }}" required placeholder="Lahore">
                    </div>
                </div>

                <!-- Row 3: Location -->
                <div class="field">
                    <label for="location">Business Location / Address</label>
                    <input type="text" id="location" name="location" value="{{ old('location') }}" required placeholder="Shop #12, Main Market, Gulberg III">
                </div>

                <!-- Row 4: Web Access URL -->
                <div class="field">
                    <label>Web Access URL</label>
                    <div class="url-field">
                        <input type="text" id="domain" name="domain" value="{{ old('domain') }}" required placeholder="your-business" oninput="cleanSlug()">
                        <div class="url-suffix">.yoursaas.com</div>
                    </div>
                    <p style="font-size:0.6875rem;color:#94a3b8;margin-top:0.25rem;">Only lowercase letters, numbers, and hyphens</p>
                </div>

                <!-- Row 5: Email -->
                <div class="field">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="you@example.com">
                </div>

                <!-- Row 6: Password -->
                <div class="row-2">
                    <div class="field">
                        <label for="password">Password</label>
                        <div class="field-pw">
                            <input type="password" id="password" name="password" required minlength="8" placeholder="Min 8 characters">
                            <button type="button" onclick="togglePw('password',this)" class="toggle-pw"><i class="fa-solid fa-eye" style="font-size:0.8125rem;"></i></button>
                        </div>
                    </div>
                    <div class="field">
                        <label for="password_confirmation">Confirm Password</label>
                        <div class="field-pw">
                            <input type="password" id="password_confirmation" name="password_confirmation" required minlength="8" placeholder="Re-enter password">
                            <button type="button" onclick="togglePw('password_confirmation',this)" class="toggle-pw"><i class="fa-solid fa-eye" style="font-size:0.8125rem;"></i></button>
                        </div>
                    </div>
                </div>

                <!-- Plan Type (Static Free Trial) -->
                <div class="field" style="margin-bottom:1.5rem;">
                    <label>Plan Type</label>
                    <div style="background:#f0fdf4;border:2px solid #22c55e;border-radius:0.75rem;padding:1rem 1.25rem;display:flex;align-items:center;gap:1rem;">
                        <div style="width:2.5rem;height:2.5rem;background:#22c55e;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fa-solid fa-check" style="color:#fff;font-size:0.875rem;"></i>
                        </div>
                        <div style="flex:1;">
                            <div style="font-size:0.9375rem;font-weight:700;color:#166534;">Free 14-Day Trial</div>
                            <div style="font-size:0.75rem;color:#4ade80;margin-top:0.125rem;">Full access to all features. No credit card needed.</div>
                        </div>
                        <div style="text-align:right;">
                            <div style="font-size:1.25rem;font-weight:800;color:#166534;">Free</div>
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <button type="submit" id="submitBtn" class="btn-submit">
                    <span id="btnText">REGISTER</span>
                    <div id="btnSpinner" class="spinner" style="display:none;"></div>
                </button>
            </div>
        </form>

        <!-- Testimonials -->
        <div class="testimonials">
            <h3>Trusted by 500+ businesses</h3>
            <div class="testi-grid">
                <div class="testi-card">
                    <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=100&h=100&fit=crop&crop=face" alt="User">
                    <div class="testi-stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
                    <div class="testi-name">Bilal Ahmed</div>
                    <div class="testi-role">Owner, SuperMart</div>
                    <div class="testi-text">"Switched from manual billing. My staff learned it in 10 minutes. Best decision ever."</div>
                </div>
                <div class="testi-card">
                    <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=100&h=100&fit=crop&crop=face" alt="User">
                    <div class="testi-stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
                    <div class="testi-name">Sara Malik</div>
                    <div class="testi-role">Manager, Spice Kitchen</div>
                    <div class="testi-text">"KOT system and table management changed our restaurant operations completely."</div>
                </div>
                <div class="testi-card">
                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&h=100&fit=crop&crop=face" alt="User">
                    <div class="testi-stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
                    <div class="testi-name">Usman Raza</div>
                    <div class="testi-role">Owner, Brew & Bean</div>
                    <div class="testi-text">"Tried 3 POS systems before. This one actually works. Fast, simple, reliable."</div>
                </div>
            </div>
        </div>

        <p class="footer-text">
            By registering you agree to our <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>
        </p>
    </div>

    <script>
        // Business type selection
        function selectBiz(el, value) {
            document.querySelectorAll('.biz-card').forEach(c => c.classList.remove('active'));
            el.classList.add('active');
            document.getElementById('business_type').value = value;
        }

        // Outlet selection
        function selectOutlet(el, value) {
            document.querySelectorAll('.outlet-pill').forEach(c => c.classList.remove('active'));
            el.classList.add('active');
            document.getElementById('outlets').value = value;
        }

        // Plan selection
        function selectPlan(el, value) {
            document.querySelectorAll('.plan-card').forEach(c => c.classList.remove('active'));
            el.classList.add('active');
            document.getElementById('plan_id').value = value;
        }

        // Auto slug from business name
        function autoSlug() {
            const name = document.getElementById('business_name').value;
            const slug = name.toLowerCase().replace(/[^a-z0-9\s-]/g,'').replace(/\s+/g,'-').replace(/-+/g,'-').replace(/^-|-$/g,'');
            document.getElementById('domain').value = slug;
        }

        // Clean slug manually
        function cleanSlug() {
            const f = document.getElementById('domain');
            f.value = f.value.toLowerCase().replace(/[^a-z0-9-]/g,'').replace(/-+/g,'-').replace(/^-|-$/g,'');
        }

        // Toggle password
        function togglePw(id, btn) {
            const f = document.getElementById(id);
            const i = btn.querySelector('i');
            if (f.type === 'password') { f.type = 'text'; i.className = 'fa-solid fa-eye-slash'; }
            else { f.type = 'password'; i.className = 'fa-solid fa-eye'; }
        }

        // Submit loading + validation
        document.getElementById('trialForm').addEventListener('submit', function(e) {
            // Honeypot check
            if (document.querySelector('input[name="website"]').value) {
                e.preventDefault();
                return;
            }
            // Required field checks
            if (!document.getElementById('business_type').value) {
                e.preventDefault();
                alert('Please select your business type.');
                return;
            }
            if (!document.getElementById('outlets').value) {
                e.preventDefault();
                alert('Please select number of outlets.');
                return;
            }

            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            document.getElementById('btnText').textContent = 'CREATING YOUR ACCOUNT...';
            document.getElementById('btnSpinner').style.display = 'block';
        });
    </script>

</body>
</html>